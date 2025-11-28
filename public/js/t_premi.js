import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated, watch, computed } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

// reactive mode helpers
const isRead = computed(() => !!route.params.id && route.params.id !== 'create')
const actionText = computed(() => (route.params.id === 'create' ? 'Create' : (route.query.action || '')))
/*
  isView true hanya jika ada id dan tidak ada query.action (mis. /module/123).
  Jika route menggunakan ?action=Edit maka isView = false.
*/
const isView = computed(() => isRead.value && !route.query.action)


console.log(isView.value)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const formErrors = ref({})
const tsId = `ts=` + (Date.parse(new Date()))

const isApproval = route.query.is_approval

// ENDPOINT API
const endpointApi = 't_premi'
onBeforeMount(() => {
  document.title = 'Transaction Premi'
})

// @if( !$id ) | --- LANDING TABLE --- |

const isOpen = ref(false)

const values = reactive({
  nama_supir: null,
  start_date: null,
  end_date: null,
  hutang_supir: 0,
  hutang_dibayar: 0,
  total_premi_diterima: 0
})

const laporanPremi = ref([])
const laporanPremiAll = ref([])

// --- Open / Close modal ---
const openModal = async () => {
  isOpen.value = true
  await getLaporanPremi()
}

const closeModal = () => {
  isOpen.value = false
}

// --- untuk mengambil data t_premi ---
const getLaporanPremi = async () => {
  try {
    const response = await fetch(`${store.server.url_backend}/operation/t_premi?paginate=999999999`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    })
    if (!response.ok) throw new Error('Gagal memuat data')
    const result = await response.json()
    laporanPremiAll.value = result.data || []
    applyFilters()
  } catch (error) {
    console.error('Error mengambil data t_premi:', error)
    swal({
      icon: 'error',
      title: 'Gagal memuat data!',
      text: error.message,
    })
  }
}

// Helpers to parse tanggal_spk
const getTanggalSpkFromRow = (row) => {
  let dateStr = null

  if (row['t_spk_angkutan.tanggal_spk']) {
    dateStr = row['t_spk_angkutan.tanggal_spk']
  } else if (row.t_spk_angkutan && row.t_spk_angkutan.tanggal_spk) {
    dateStr = row.t_spk_angkutan.tanggal_spk
  }

  if (!dateStr) return null

  const d = new Date(dateStr)
  if (isNaN(d.getTime())) {
    // try parsing dd/mm/yyyy → yyyy-mm-dd
    const maybe = new Date(dateStr.replace(/(\d{2})\/(\d{2})\/(\d{4})/, '$3-$2-$1'))
    return isNaN(maybe.getTime()) ? null : maybe
  }

  return d
}


const formatToISODate = (d) => {
  const date = d instanceof Date ? d : new Date(d)
  if (isNaN(date.getTime())) return null
  const yyyy = date.getFullYear()
  const mm = String(date.getMonth() + 1).padStart(2, '0')
  const dd = String(date.getDate()).padStart(2, '0')
  return `${yyyy}-${mm}-${dd}`
}

// Ensure each displayed row has a _checked boolean
const normalizeDisplayedRows = (rows) => {
  return rows.map(r => {
    // keep previous checked state if any
    return Object.assign({}, r, { _checked: r._checked === true })
  })
}

// Apply filters: nama_supir + tanggal range; also ensure _checked present
const applyFilters = () => {
  let rows = Array.isArray(laporanPremiAll.value) ? laporanPremiAll.value.slice() : []

  if (values.nama_supir) {
    rows = rows.filter((r) => {
      const supirField = r['t_spk_angkutan.supir'] ?? (r.t_spk_angkutan && r.t_spk_angkutan.supir)
      if (supirField == null) return false
      if (typeof supirField === 'object') {
        return String(supirField.id) === String(values.nama_supir)
      }
      return String(supirField) === String(values.nama_supir)
    })
  } else {
    // if no supir selected we should not display any rows (enforce requirement)
    rows = []
  }

  if (values.start_date || values.end_date) {
    const start = values.start_date ? new Date(values.start_date) : null
    const end = values.end_date ? new Date(values.end_date) : null
    const endInclusive = end ? new Date(end.getFullYear(), end.getMonth(), end.getDate(), 23, 59, 59, 999) : null

    rows = rows.filter((r) => {
      const d = getTanggalSpkFromRow(r)
      if (!d) return false
      if (start && d < start) return false
      if (endInclusive && d > endInclusive) return false
      return true
    })
  }

  // normalize selection state for displayed rows only
  laporanPremi.value = normalizeDisplayedRows(rows)

  // recalc hutang
  kurangBayar()
}

// When a supir is selected from FieldSelect
const onSelectSupir = (v) => {
  // FieldSelect may emit the id (primitive) or an object (with id). Normalize to id.
  if (v && typeof v === 'object' && ('id' in v)) {
    values.nama_supir = v.id
  } else {
    values.nama_supir = v || null
  }

  if (values.nama_supir) {
    // auto-set start/end from available rows for that supir (if any)
    const targetRows = laporanPremiAll.value.filter((r) => {
      const supirField = r['t_spk_angkutan.supir'] ?? (r.t_spk_angkutan && r.t_spk_angkutan.supir)
      if (supirField == null) return false
      if (typeof supirField === 'object') return String(supirField.id) === String(values.nama_supir)
      return String(supirField) === String(values.nama_supir)
    })

    const dates = targetRows
      .map((r) => getTanggalSpkFromRow(r))
      .filter((d) => d !== null)
      .map((d) => d.getTime())

    if (dates.length > 0) {
      const min = new Date(Math.min(...dates))
      const max = new Date(Math.max(...dates))
      values.start_date = formatToISODate(min)
      values.end_date = formatToISODate(max)
    } else {
      values.start_date = null
      values.end_date = null
    }
  } else {
    values.start_date = null
    values.end_date = null
  }

  // clear previous checks whenever filter changes
  clearSelections()
  applyFilters()
}

const onDateChange = (field, v) => {
  values[field] = v || null
  clearSelections()
  applyFilters()
}

// Clear selection flags on all data
const clearSelections = () => {
  laporanPremi.value = laporanPremi.value.map(r => Object.assign({}, r, { _checked: false }))
  laporanPremiAll.value = laporanPremiAll.value.map(r => Object.assign({}, r, { _checked: false }))
}

// Sum logic
const formatCurrency = (value) => {
  if (value === null || value === undefined || value === '') return 'Rp 0'
  const num = parseFloat(value) || 0
  return 'Rp ' + num.toLocaleString('id-ID')
}

// Select-all helper (only for displayed rows)
const allDisplayedSelected = computed(() => {
  if (!laporanPremi.value || laporanPremi.value.length === 0) return false
  return laporanPremi.value.every(r => r._checked === true)
})
const selectedRows = computed(() => {
  return (laporanPremi.value || []).filter(r => r._checked)
})

const kurangBayar = () => {
  if (!Array.isArray(selectedRows.value)) {
    values.hutang_supir = 0
    values.total_premi_diterima = (parseFloat(values.hutang_supir) || 0) - (parseFloat(values.hutang_dibayar) || 0)
    return
  }

  const sum = selectedRows.value.reduce((acc, row) => {
    const v = parseFloat(row.total_premi)
    return acc + (isNaN(v) ? 0 : v)
  }, 0)

  values.hutang_supir = sum
  const dibayar = parseFloat(values.hutang_dibayar) || 0
  values.total_premi_diterima = values.hutang_supir - dibayar
}

// Watches
watch(selectedRows, () => {
  kurangBayar()
})

watch(
  [() => values.hutang_supir, () => values.hutang_dibayar],
  () => {
    const dibayar = parseFloat(values.hutang_dibayar) || 0
    values.total_premi_diterima = (parseFloat(values.hutang_supir) || 0) - dibayar
  }
)


const selectedIds = computed(() => {
  return selectedRows.value.map(r => r.id).filter(Boolean)
})

const toggleSelectAll = (checked) => {
  laporanPremi.value = laporanPremi.value.map(r => Object.assign({}, r, { _checked: !!checked }))
}

// Print: validate and perform same logic as original printPremi but using selectedIds
// const onPrintButton = async () => {
//   // Validasi: supir harus dipilih, tanggal range harus diisi
//   console.log('laporanPremi (displayed):', laporanPremi.value)
//   console.log('selectedRows:', selectedRows.value)
//   console.log('selectedIds raw:', selectedRows.value.map(r => r.id))
//   if (!values.nama_supir) {
//     if (swal && swal.fire) await swal.fire({ icon: 'warning', text: 'Silakan pilih Nama Supir terlebih dahulu.' })
//     return
//   }
//   if (!values.start_date || !values.end_date) {
//     if (swal && swal.fire) await swal.fire({ icon: 'warning', text: 'Silakan isi Periode (start_date dan end_date).' })
//     return
//   }

//   const ids = [...new Set(selectedIds.value)]
//   console.log('iki cuk :', ids)
//   if (!ids.length) {
//     if (swal && swal.fire) await swal.fire({ icon: 'warning', text: 'Pilih minimal satu baris yang akan dicetak.' })
//     return
//   }

//   // Extra safety: ensure each selected row still matches driver + date filters
//   const invalid = selectedRows.value.some(r => {
//     const supirField = r['t_spk_angkutan.supir'] ?? (r.t_spk_angkutan && r.t_spk_angkutan.supir)
//     const supirMatch = supirField && (typeof supirField === 'object' ? String(supirField.id) === String(values.nama_supir) : String(supirField) === String(values.nama_supir))
//     if (!supirMatch) return true

//     const d = getTanggalSpkFromRow(r)
//     if (!d) return true
//     const start = new Date(values.start_date)
//     const endInclusive = new Date(new Date(values.end_date).getFullYear(), new Date(values.end_date).getMonth(), new Date(values.end_date).getDate(), 23, 59, 59, 999)
//     if (d < start || d > endInclusive) return true

//     return false
//   })

//   if (invalid) {
//     if (swal && swal.fire) await swal.fire({ icon: 'warning', text: 'Beberapa baris terpilih tidak sesuai dengan filter Supir / Periode. Silakan periksa kembali.' })
//     return
//   }

//   const confirm = swal && swal.fire ? await swal.fire({
//     icon: 'warning',
//     text: `Yakin print ${ids.length} laporan premi?`,
//     iconColor: '#1469AE',
//     confirmButtonColor: '#1469AE',
//     showDenyButton: true
//   }) : { isConfirmed: true }

//   if (!confirm.isConfirmed) return

//   isRequesting.value = true
//   try {
//     if (!window.premiCounter) {
//       window.premiCounter = 0;
//     }

//     function generateKode() {
//       window.premiCounter++;
//       const bulan = new Date().getMonth() + 1;
//       const tahun = new Date().getFullYear().toString().slice(-2);
//       const romawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][bulan - 1];

//       return String(window.premiCounter).padStart(3, '0') + "/PRMI/" + romawi + "/" + tahun;
//     }

//     const kode = generateKode();
//     const queryParams = [
//       ...ids.map((i) => `id[]=${encodeURIComponent(i)}`),
//       `supir_id=${encodeURIComponent(values.nama_supir)}`,
//       `start_date=${encodeURIComponent(values.start_date)}`,
//       `end_date=${encodeURIComponent(values.end_date)}`,
//       `hutang_supir=${encodeURIComponent(values.hutang_supir ?? 0)}`,
//       `hutang_dibayar=${encodeURIComponent(values.hutang_dibayar ?? 0)}`,
//       `total_premi_diterima=${encodeURIComponent(values.total_premi_diterima ?? 0)}`
//     ].join("&");
//     const previewUrl = `${store.server.url_backend}/web/laporan_premi?${queryParams}&kode=${kode}`
//     window.open(previewUrl, '_blank')

//     const updatePromises = ids.map(id =>
//       fetch(`${store.server.url_backend}/operation/${endpointApi}/print?id=${id}`, {
//         method: 'POST',
//         headers: {
//           'Content-Type': 'application/json',
//           Authorization: `${store.user.token_type} ${store.user.token}`
//         }
//       }).then(async res => {
//         if (!res.ok) {
//           let text = await res.text()
//           throw new Error(`Gagal menandai id ${id} sebagai printed. Status ${res.status}. ${text}`)
//         }
//         return res.json().catch(() => ({}))
//       })
//     )

//     const settled = await Promise.allSettled(updatePromises)
//     const rejected = settled.filter(s => s.status === 'rejected')

//     if (rejected.length > 0) {
//       const messages = rejected
//         .map(r => r.reason?.message || JSON.stringify(r.reason))
//         .slice(0, 5)
//         .join('\n')
//       if (swal && swal.fire) {
//         await swal.fire({
//           icon: 'warning',
//           text: `Beberapa item gagal di-update sebagai printed:\n${messages}`
//         })
//       }
//     } else {
//       if (swal && swal.fire) {
//         await swal.fire({
//           icon: 'success',
//           text: `Semua ${ids.length} laporan premi berhasil ditandai sebagai printed`
//         })
//       }
//     }

//     router.replace('/' + route.params.modul)
//   } catch (err) {
//     console.error(err)
//     if (swal && swal.fire) {
//       await swal.fire({
//         icon: 'error',
//         text: err?.message || String(err)
//       })
//     }
//   } finally {
//     isRequesting.value = false
//   }
// }

const onPrintButton = async () => {
  try {
    isRequesting.value = true;

    // Ambil rows: mendukung ref atau array plain
    const rows = Array.isArray(laporanPremi) ? laporanPremi : (laporanPremi?.value ?? []);

    // Jika ada selectedIds (ref) gunakan itu sebagai prioritas (fallback ke row._checked)
    let ids = [];
    if (typeof selectedIds !== 'undefined' && selectedIds?.value && Array.isArray(selectedIds.value) && selectedIds.value.length) {
      ids = [...new Set(selectedIds.value)];
    } else {
      ids = rows
        .filter(r => r && r._checked)
        .map(r => r.id ?? r['id'] ?? r['premi_id'] ?? r['t_spk_angkutan.id'] ?? r['t_spk_angkutan_id'] ?? null)
        .filter(Boolean);
    }

    if (!ids.length) {
      if (swal && swal.fire) await swal.fire({ icon: 'warning', text: 'Pilih minimal satu baris sebelum mencetak.' });
      return;
    }

    // Validasi supir & periode
    if (!values.nama_supir) {
      if (swal && swal.fire) await swal.fire({ icon: 'warning', text: 'Silakan pilih Nama Supir terlebih dahulu.' });
      return;
    }
    if (!values.start_date || !values.end_date) {
      if (swal && swal.fire) await swal.fire({ icon: 'warning', text: 'Silakan isi Periode (start_date dan end_date).' });
      return;
    }

    // Simpan laporan premi di server (endpoint Anda)
    const res = await fetch(`${store.server.url_backend}/operation/t_premi/laporan`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify({
        supir_id: values.nama_supir,
        start_date: values.start_date,
        end_date: values.end_date,
        hutang_supir: values.hutang_supir ?? 0,
        hutang_dibayar: values.hutang_dibayar ?? 0,
        total_premi_diterima: values.total_premi_diterima ?? 0,
        id: ids // kirim array id yang dipilih
      }),
    });

    if (!res.ok) {
      const responseJson = await res.json().catch(() => ({}));
      if (swal && swal.fire) await swal.fire({ icon: 'error', text: responseJson.message || 'Gagal simpan data Premi' });
      return;
    }

    // Jika simpan berhasil, buat kode dan preview URL lalu buka preview di tab baru
    // (kode mengikuti logic lama: counter global + romawi bulan + 2-digit tahun)
    if (!window.premiCounter) window.premiCounter = 0;
    function generateKode() {
      window.premiCounter++;
      const bulan = new Date().getMonth() + 1;
      const tahun = new Date().getFullYear().toString().slice(-2);
      const romawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][bulan - 1];
      return String(window.premiCounter).padStart(3, '0') + "/PRMI/" + romawi + "/" + tahun;
    }
    const kode = generateKode();

    const queryParams = [
      ...ids.map((i) => `id[]=${encodeURIComponent(i)}`),
      `supir_id=${encodeURIComponent(values.nama_supir)}`,
      `start_date=${encodeURIComponent(values.start_date)}`,
      `end_date=${encodeURIComponent(values.end_date)}`,
      `hutang_supir=${encodeURIComponent(values.hutang_supir ?? 0)}`,
      `hutang_dibayar=${encodeURIComponent(values.hutang_dibayar ?? 0)}`,
      `total_premi_diterima=${encodeURIComponent(values.total_premi_diterima ?? 0)}`
    ].join("&");
    const previewUrl = `${store.server.url_backend}/web/laporan_premi?${queryParams}&kode=${encodeURIComponent(kode)}`;

    // Buka preview
    try {
      window.open(previewUrl, '_blank');
    } catch (e) {
      // Jika blocking popup, beri tahu user
      console.warn('Gagal membuka preview di tab baru', e);
      if (swal && swal.fire) await swal.fire({ icon: 'info', text: 'Preview diblokir oleh browser. Silakan periksa popup atau buka URL preview secara manual.' });
    }

    // Update total_premi terima (jika endpoint ada)
    const updatePremi = await fetch(`${store.server.url_backend}/operation/t_premi/update_premi_terima`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify({
        id: ids,
        total_premi: values.total_premi_diterima ?? 0
      }),
    });

    if (!updatePremi.ok) {
      const respUpd = await updatePremi.json().catch(() => ({}));
      if (swal && swal.fire) await swal.fire({ icon: 'error', text: respUpd.message || 'Gagal update total premi' });
      return;
    }

    // Opsional: tandai printed per id (seperti implementasi lama)
    const updatePrintedPromises = ids.map(id =>
      fetch(`${store.server.url_backend}/operation/${endpointApi}/print?id=${encodeURIComponent(id)}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        }
      }).then(async res => {
        if (!res.ok) {
          let text = await res.text().catch(() => '');
          throw new Error(`Gagal menandai id ${id} sebagai printed. Status ${res.status}. ${text}`);
        }
        return res.json().catch(() => ({}));
      })
    );

    const settled = await Promise.allSettled(updatePrintedPromises);
    const rejected = settled.filter(s => s.status === 'rejected');

    if (rejected.length > 0) {
      const messages = rejected.map(r => r.reason?.message || JSON.stringify(r.reason)).slice(0, 5).join('\n');
      if (swal && swal.fire) {
        await swal.fire({ icon: 'warning', text: `Beberapa item gagal di-update sebagai printed:\n${messages}` });
      }
    } else {
      if (swal && swal.fire) {
        await swal.fire({ icon: 'success', text: `Semua ${ids.length} laporan premi berhasil diproses` });
      }
    }

    router.replace('/' + route.params.modul);
  } catch (err) {
    console.error(err);
    if (swal && swal.fire) {
      await swal.fire({ icon: 'error', text: err?.message || String(err) });
    }
  } finally {
    isRequesting.value = false;
  }
};

const onPost = () => {
  // keep existing Post behavior or implement as needed
  swal.fire({ icon: 'info', text: 'Feature Post belum diimplementasikan.' })
}

// reapply filters when master data changes
watch(laporanPremiAll, () => {
  applyFilters()
})

// TABLE
const table = reactive({
  api: {
    url: `${store.server.url_backend}/operation/${endpointApi}`,
    headers: {
      'Content-Type': 'application/json',
      authorization: `${store.user.token_type} ${store.user.token}`,
    },
    params: {
      simplest: false,
      searchfield: 'this.id, this.no_premi, this.tgl, this.total_premi, this.catatan',
    },
    onsuccess(response) {
      return { ...response, page: response.current_page, hasNext: response.has_next };
    },
  },
  columns: [
    {
      headerName: 'No',
      valueGetter: ({ node }) => node.rowIndex + 1,
      width: 60,
      sortable: false,
      cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
    },
    {
      headerName: 'No. Premi',
      field: 'no_premi',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-center',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Tanggal',
      field: 'tgl',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-center',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Total Nominal',
      field: 'total_premi',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-end',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
      valueFormatter: (params) => {
        const value = Number(params.value) || 0;
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
      }
    },
    {
      headerName: 'Catatan',
      field: 'catatan',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Status',
      field: 'status',
      flex: 1,
      filter: false,
      cellClass: ['border-r', '!border-gray-200', 'justify-center',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      // filter: 'ColFilter',
      cellRenderer: (params) => {
        return params.data['status'] == 1
          ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
          : (params.data['status'] == 'DRAFT' ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'POST' ? `<span class="text-amber-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : (params.data['status'] == 'IN APPROVAL' ? `<span class="text-sky-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                : (params.data['status'] == 'REVISED' ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                  : (params.data['status'] == 'APPROVED' ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                    : (params.data['status'] == 'REJECTED' ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                      : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`))))))
      }
    },
  ],
  actions: [
    {
      title: 'Hapus', icon: 'trash', class: 'bg-red-600 text-light-100', click: deleteData,
      show: (row) => row.status === 'DRAFT',
    },
    {
      title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?${tsId}`)
    },
    {
      title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Edit&${tsId}`),
      show: (row) => row.status === 'DRAFT' || row.status === 'REVISED',
    },
    {
      title: 'Copy', icon: 'copy', class: 'bg-gray-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Copy&${tsId}`),
    },
    {
      icon: 'location-arrow',
      title: "Post Data",
      class: 'bg-rose-700 text-white',
      show: (row) => row.status === 'DRAFT',
      async click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Post Data?',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',

          showDenyButton: true
        }).then(async (res) => {
          if (res.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/${endpointApi}/post`
              isRequesting.value = true
              const res = await fetch(dataURL, {
                method: 'POST',
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                body: JSON.stringify({ id: row.id })
              })
              if (!res.ok) {
                if ([400, 422, 500].includes(res.status)) {
                  const responseJson = await res.json()
                  formErrors.value = responseJson.errors || {}
                  throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to post data")
                } else {
                  throw ("Failed when trying to post data")
                }
              }
              const responseJson = await res.json()
              swal.fire({
                icon: 'success',
                text: responseJson.message
              })
              // const resultJson = await res.json()
            } catch (err) {
              isBadForm.value = true
              swal.fire({
                icon: 'error',
                iconColor: '#1469AE',
                confirmButtonColor: '#1469AE',
                text: err
              })
            }
            isRequesting.value = false

            apiTable.value.reload()
          }
        })
      }
    },

    {
      icon: 'location-arrow',
      title: "Send for approval",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status === 'POST' || row.status === 'REVISED',
      async click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Send for approval?',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',

          showDenyButton: true
        }).then(async (res) => {
          if (res.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/${endpointApi}/send_approval`
              isRequesting.value = true
              const res = await fetch(dataURL, {
                method: 'POST',
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                body: JSON.stringify({ id: row.id })
              })
              if (!res.ok) {
                if ([400, 422, 500].includes(res.status)) {
                  const responseJson = await res.json()
                  formErrors.value = responseJson.errors || {}
                  throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to post data")
                } else {
                  throw ("Failed when trying to post data")
                }
              }
              const responseJson = await res.json()
              swal.fire({
                icon: 'success',
                text: responseJson.message
              })
              // const resultJson = await res.json()
            } catch (err) {
              isBadForm.value = true
              swal.fire({
                icon: 'error',
                iconColor: '#1469AE',
                confirmButtonColor: '#1469AE',
                text: err
              })
            }
            isRequesting.value = false

            apiTable.value.reload()
          }
        })
      }
    }
  ],
});

// DELETE DATA
async function deleteData(row) {
  const result = await swal.fire({
    icon: 'warning',
    text: 'Hapus Data Terpilih?',
    confirmButtonText: 'Yes',
    showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  try {
    isRequesting.value = true;

    const res = await fetch(`${store.server.url_backend}/operation/${endpointApi}/${row.id}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });

    if (!res.ok) {
      const resultJson = await res.json();
      throw new Error(resultJson.message || 'Failed when trying to remove data');
    }

    apiTable.value.reload();
  } catch (err) {
    isBadForm.value = true;
    swal.fire({ icon: 'error', text: err.message });
  } finally {
    isRequesting.value = false;
  }
}

// FILTER
const filterButton = ref(null);
function filterShowData(params) {
  filterButton.value = filterButton.value === params ? null : params;
  table.api.params.where = filterButton.value !== null ? `this.status='${filterButton.value}'` : null;
  apiTable.value.reload();
}

onActivated(() => {
  if (apiTable.value && route.query.reload) {
    apiTable.value.reload();
  }
});

// @else | --- FORM DATA --- |

// HOT KEY (CTRL+S)
const handleKeyDown = (event) => {
  if (event?.ctrlKey && event?.key === 's' && actionText.value) {
    event.preventDefault();
    onSave();
  }
}

onMounted(() => { window.addEventListener('keydown', handleKeyDown) });
onBeforeUnmount(() => { window.removeEventListener('keydown', handleKeyDown) });

// FORM DATA
let default_value = {
  data: { status: 'DRAFT', no_draft: 'Generate by System', no_premi: 'Generate by System', total_bon_tambahan: 0 },
  detail: []
}

const data = reactive({ ...default_value.data });

const initArr = {
  t_premi_id: data.id || 0,
  keterangan: ''
}
const detailArr = reactive([])


// GET DATA FROM API
onBeforeMount(async () => {
  if (actionText.value === 'Create' || data.status === 'DRAFT') {
    data.tgl = getCurrentDateFormatted();
  }

  if (!isRead.value) return;

  try {
    let trx_id;

    if (route.query.is_approval) {
      const dataApprovalURL = `${store.server.url_backend}/operation/generate_approval/${route.params.id}`;
      isRequesting.value = true;

      const headers = {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      };

      const fetchData = async (url, params = {}) => {
        const queryString = new URLSearchParams(params).toString();
        const response = await fetch(`${url}?${queryString}`, { headers });
        return response.json();
      };

      // FETCH HEADER DATA
      await fetchData(dataApprovalURL, { join: false, transform: false }).then((res) => {
        trx_id = res.data.trx_id;
        console.log(res, trx_id);
      });

    }
    const editedId = route.params.id;
    const dataURL = trx_id ? `${store.server.url_backend}/operation/${endpointApi}/${trx_id}` : `${store.server.url_backend}/operation/${endpointApi}/${editedId}`;
    isRequesting.value = true;

    const headers = {
      'Content-Type': 'application/json',
      Authorization: `${store.user.token_type} ${store.user.token}`,
    };

    const fetchData = async (url, params = {}) => {
      const queryString = new URLSearchParams(params).toString();
      const response = await fetch(`${url}?${queryString}`, { headers });
      return response.json();
    };

    // FETCH HEADER DATA
    await fetchData(dataURL, { join: true, transform: false }).then((res) => {
      // default_value.data = res.data;
      detailArr.push(...res.data.t_premi_d);
      for (const key in res.data) {
        data[key] = res.data[key];
      }

      if (actionText.value === 'Copy') {

        data.no_draft = default_value.data.no_draft;
        data.no_premi = default_value.data.no_premi;
        data.status = default_value.data.status;
        data.tgl = getCurrentDateFormatted();
      }
    });

    const dataURLSPK = `${store.server.url_backend}/operation/t_spk_angkutan/${data.t_spk_angkutan_id}`;
    await fetchData(dataURLSPK, { join: true, transform: false }).then((res) => {
      // default_value.data = res.data;
      if (res.data) {
        getDetailNPWPContainer(res.data['t_detail_npwp_container_1_id'], res.data['t_detail_npwp_container_2_id']);
        data.no_container = res.data['no_container_1'];
        data.no_angkutan = res.data['t_buku_order_1.angkutan'];
        data.head_deskripsi2 = res.data['head.kode'];
        data.tanggal_out = res.data.tanggal_out;
        data.waktu_out = res.data.waktu_out;
        data.no_bon_sementara = res.data.no_bon_sementara;
        data.tanggal_bon = res.data.tanggal_bon;

        data.tanggal_in = res.data.tanggal_in;
        data.waktu_in = res.data.waktu_in;
        data.m_karyawan_id = res.data['supir.id'];
        data.chasis = res.data.chasis;
        data.total_sangu = res.data.sangu;
        data.sektor = res.data['sektor1.deskripsi'];
        data.ke = res.data.ke;
        data.dari = res.data.dari;
      }
      else {
        data.head_deskripsi2 = '';
        data.no_container = '';
        data.no_order = '';
        data.no_angkutan = '';
        data.tanggal_out = '';
        data.waktu_out = '';
        data.no_bon_sementara = '';
        data.tanggal_bon = '';

        data.tanggal_in = '';
        data.waktu_in = '';
        data.ukuran_container = '';
        data.m_karyawan_id = '';
        data.chasis = '';
        data.total_sangu = '';
        data.sektor = '';
        data.ke = '';
        data.dari = '';
      }

      console.log('SPK response', res);

    });



  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error', text: err, allowOutsideClick: false, confirmButtonText: 'Kembali',
    }).then(() => { router.back() });
  } finally {
    isRequesting.value = false;
  }
});


// ADD & DELETE DETAIL
const addDetail = () => {
  detailArr.push({ ...initArr });
}

const delDetail = (index) => {
  detailArr.splice(index, 1);
}

const deleteDetailAll = () => {
  swal.fire({
    icon: 'warning', text: 'Hapus semua detail data?', showDenyButton: true,
  }).then((res) => {
    if (res.isConfirmed) {
      detail.data = [];
    }
  })
}

// ACTION BUTTON
function onReset() {
  swal.fire({
    icon: 'warning', text: 'Reset semua data?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      for (const key in data) {
        data[key] = default_value.data[key];
      }
      detail.data = default_value.detail.map(item => ({ ...item }));
    }
  })
}

function onBack() {
  router.replace('/' + modulPath)
}

async function onSave() {
  //console.log(data, detailArr);
  //console.log('ini panjangggg', detailArr.length);
  const result = await swal.fire({
    icon: 'warning', text: 'Simpan data?', showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  try {
    const isCreating = ['Create', 'Copy'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : '/' + route.params.id}`;
    isRequesting.value = true;

    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify({
        ...data,
        t_premi_d: detailArr,
      }),
    });

    if (!res.ok) {
      const responseJson = await res.json();
      formErrors.value = responseJson.errors || {};
      swal.fire({ icon: 'error', text: responseJson.message || "Failed when trying to post data" });
    } else {
      router.replace(`/${modulPath}?reload=${Date.now()}`);
    }

  } catch (err) {
    isBadForm.value = true;
    swal.fire({ icon: 'error', text: err });
  } finally {
    isRequesting.value = false;
  }
}

const getTarifPremi = async (t_1_id, t_2_id) => {
  // console.log("AAAAAAA  AAAAAAA", t_1_id, t_2_id)
  const headers = {
    'Content-Type': 'application/json',
    Authorization: `${store.user.token_type} ${store.user.token}`,
  };

  const dataURL = `${store.server.url_backend}/operation/m_tarif_premi_bckp/get_tarif_premi`;
  const params = {
    get_tarif_premi: true,
    spk_id: data.t_spk_angkutan_id
  };

  const fetchData = async (url, params = {}) => {
    const queryString = new URLSearchParams(params).toString();
    const response = await fetch(`${url}?${queryString}`, { headers });
    return response.json();
  };

  // FETCH HEADER DATA
  await fetchData(dataURL, params).then((res) => {
    // console.log('tarifff', res.premi);
    if (res.premi) data.tarif_premi = res.premi;
    else data.tarif_premi = '';
  });

}

const getDetailNPWPContainer = async (t_1_id, t_2_id) => {
  console.log("AAAAAAAAAAAAAA", t_1_id, t_2_id)
  if (t_1_id) {
    const url1 = `${store.server.url_backend}/operation/t_buku_order_d_npwp/${t_1_id}`
    const res1 = await fetch(url1, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      params: {
        join: true,
        // where: `t_purchase_order_id=${id}`
      },
    })
    if (!res1.ok) throw new Error("Failed when trying to read data")
    const result1 = await res1.json();
    data.ukuran_container = (result1.data['ukuran.deskripsi'] ?? '-') + ', ';
    data.no_order = (result1.data['t_buku_order.no_buku_order'] ?? '-') + ', ';
    data.no_angkutan = (result1.data['t_buku_order.angkutan'] ?? '-') + ', ';
  }
  else {
    data.ukuran_container = '-, ';
    // data.no_order = '-, ';
    data.no_order = ' ';
    data.no_angkutan = '-, ';

  }
  if (t_2_id) {
    const url2 = `${store.server.url_backend}/operation/t_buku_order_d_npwp/${t_2_id}`;
    const res2 = await fetch(url2, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      params: {
        join: true,
        // where: `t_purchase_order_id=${id}`
      },
    })
    if (!res2.ok) throw new Error("Failed when trying to read data")
    const result2 = await res2.json();
    data.ukuran_container += (result2.data['ukuran.deskripsi'] ?? '-');
    data.no_order += (result2.data['t_buku_order.no_buku_order'] ?? '-');
    data.no_angkutan += (result2.data['t_buku_order.angkutan'] ?? '-');
  }
  else {
    data.ukuran_container += '-';
    data.no_order += '-';
    data.no_angkutan += '-';

  }

}


// watch(
//   () => [detailArr, data.tarif_premi, data.tol, data.total_sangu, data.hutang_dibayar],
//   () => {
//     let total = 0;

//     // Hitung total dari detail nominal
//     for (let idx = 0; idx < detailArr.length; idx++) {
//       if (detailArr[idx].nominal) total += Number(detailArr[idx].nominal);
//     }

//     // Tambahkan tarif premi jika > 0
//     if (data.tarif_premi && Number(data.tarif_premi) > 0) {
//       total += Number(data.tarif_premi);
//     }

//     // Tambahkan tol jika ada
//     if (data.tol) total += Number(data.tol);

//     // Kurangi total sangu (kalau ada)
//     if (data.total_sangu) total -= Number(data.total_sangu);

//     // Kurangi hutang dibayar (kalau ada)
//     if (data.hutang_dibayar) total -= Number(data.hutang_dibayar);

//     // Kalau tarif premi 0, pastikan tidak membuat hasil jadi minus
//     if (Number(data.tarif_premi) === 0 && total < 0) {
//       total = Math.abs(total);
//     }

//     data.total_premi = total;
//   },
//   { deep: true }
// );

// Watch pengganti — paste menggantikan watch lama
watch(
  // Trigger: perhatikan perubahan panjang & nominal detail, serta field-field yang relevan
  () => [
    Array.isArray(detailArr) ? detailArr.length : 0,
    Array.isArray(detailArr) ? detailArr.map(d => String(d?.nominal ?? '')).join('|') : '',
    data.tarif_premi,
    data.tol,
    data.total_sangu,
    data.hutang_dibayar
  ],
  () => {
    const toNum = v => {
      const n = Number(v);
      return Number.isFinite(n) ? n : 0;
    };

       // MODE VIEW: gunakan value yang dikirim server, jangan hitung ulang
    if (isView.value) {
      // API kadang mengirim string, parse aman jadi number
      const parsed = parseFloat(data.total_premi)
      data.total_premi = Number.isFinite(parsed) ? parsed : 0
      return
    }

    // Jumlahkan nominal dari detailArr (jika ada)
    let totalDetail = 0;
    if (Array.isArray(detailArr)) {
      for (let i = 0; i < detailArr.length; i++) {
        totalDetail += toNum(detailArr[i]?.nominal);
      }
    }

    // Basis perhitungan: total_sangu (positif)
    let total = toNum(data.total_sangu);

    // Tambahkan tarif premi (boleh nol)
    total += toNum(data.tarif_premi);

    // Tambahkan tol
    total += toNum(data.tol);

    // Tambahkan detail
    total += totalDetail;

    // Kurangi hutang yang dibayar
    total -= toNum(data.hutang_dibayar);

    // Safety: pastikan tidak NaN/Infinity
    if (!Number.isFinite(total)) total = 0;

    // Simpan hasil
    data.total_premi = total;
  },
  { immediate: true } // immediate agar nilai terhitung saat mount
);

// watch([() => data.tarif_premi, () => data.tol], () => {
//   data.total_premi = 0;
//   for (let idx = 0; idx < detailArr.length; idx++) {
//     if (detailArr[idx].nominal) data.total_premi += Number(detailArr[idx].nominal);
//   }
//   if (data.tarif_premi) data.total_premi += data.tarif_premi;
//   if (data.tol) data.total_premi += data.tol;
// });
// // watch([() => data.total_bon_tambahan, () => data.sangu], () => {
// //   data.total_sangu = Number(data.total_bon_tambahan) + Number(data.sangu);

// // });

const getCurrentDateFormatted = () => {
  const date = new Date();
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};

async function sendApproval() {
  swal.fire({
    icon: 'warning',
    text: 'Send Approval?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',
    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const isCreating = ['Create', 'Copy'].includes(actionText.value);
        const dataURLSimpan = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : '/' + route.params.id}`;
        isRequesting.value = true;


        const resSimpan = await fetch(dataURLSimpan, {
          method: isCreating ? 'POST' : 'PUT',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`,
          },
          body: JSON.stringify({
            ...data,
            t_premi_d: detailArr,
          }),
        }).then(async response => {
          // if (!response.ok) {
          //   throw new Error(`HTTP error! status: ${response.status}`);
          // }
          if (!response.ok) {
            const responseJson = await resSimpan.json();
            formErrors.value = responseJson.errors || {};
            swal.fire({ icon: 'error', text: responseJson.message || "Failed when trying to post data" });
          }
          return response.json(); // Parse JSON from the response body
        }).then(async data => {
          console.log('Parsed response JSON:', data);
          data.id = data.id;
          const dataURL = `${store.server.url_backend}/operation/${endpointApi}/send_approval`
          isRequesting.value = true
          const res = await fetch(dataURL, {
            method: 'POST',
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            body: JSON.stringify({ id: data.id })
          })
          if (!res.ok) {
            if ([400, 422, 500].includes(res.status)) {
              const responseJson = await res.json()
              formErrors.value = responseJson.errors || {}
              throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to Send Approval")
            } else {
              throw ("Failed when trying to Send Approval")
            }
          }
          const responseJson = await res.json()
          swal.fire({
            icon: 'success',
            text: responseJson.message
          })
        });
      } catch (err) {
        console.error('Error occurred:', err);
        isBadForm.value = true;
        swal.fire({
          icon: 'error',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',
          text: err.message || "An unexpected error occurred"
        });
      } finally {
        isRequesting.value = false;
        router.replace('/' + modulPath);
      }
    }
  });
}

async function progress(status) {

  if (status == 'REVISED' && (data.alasan_revisi == '' || !data.alasan_revisi)) {
    swal.fire({
      icon: 'warning',
      text: `Isi alasan revisi terlebih dahulu`
    })
    next = false
    return
  }

  swal.fire({
    icon: 'warning',
    text: status == 'APPROVED' ? 'Approve?' : status == 'REJECTED' ? 'Reject?' : 'Revise?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',

    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const dataURL = `${store.server.url_backend}/operation/${endpointApi}/progress`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: 'POST',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify({ id: route.params.id, type: status, note: data.alasan_revisi ? data.alasan_revisi : 'a' })
        })
        if (!res.ok) {
          if ([400, 422, 500].includes(res.status)) {
            const responseJson = await res.json()
            formErrors.value = responseJson.errors || {}
            throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to Approved")
          } else {
            throw ("Failed when trying to Approved")
          }
        }
        const responseJson = await res.json()
        swal.fire({
          icon: 'success',
          text: responseJson.message
        })
        // const resultJson = await res.json()
      } catch (err) {
        isBadForm.value = true
        swal.fire({
          icon: 'error',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',
          text: err
        })
      }
      isRequesting.value = false;
      router.replace('/' + modulPath);
    }
  })
}
//  @endif | --- END --- |
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))