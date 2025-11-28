import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const actionText = ref(route.params.id === 'create' ? 'Tambah' : route.query.action)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const formErrors = ref({})
const activeTabIndex = ref(0)
const detailArr = ref([])
const detailArrAju = ref([])
const detailArrBerkas = ref([])
const tsId = `ts=` + (Date.parse(new Date()))

// ------------------------------ PERSIAPAN
const endpointApi = 't_buku_order'
const VendpointApi = 'v_buku_order'
onBeforeMount(() => {
  document.title = 'Transaksi Buku Order'
})

//  @if( $id )------------------- JS CONTENT ! PENTING JANGAN DIHAPUS

// HOT KEY
onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
})
onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeyDown);
})

const handleKeyDown = (event) => {
  console.log(event)
  if (event?.ctrlKey && event?.key === 's' && actionText.value) {
    event.preventDefault(); // Prevent the default behavior (e.g., saving the page)
    onSave();
  }
}

let initialValues = {}
const changedValues = []

let values = reactive({})

// DEFAULT VALUE BEFORE MOUNT --UBAH DISINI
const defaultValues = () => {
  values.tgl = new Date().toLocaleDateString('en-GB')
  values.status = 'DRAFT'
  // values.ukuran = 25
  values.dispensasi_closing_cont = false
  values.dispensasi_closing_doc = false
}

const onReset = async (alert = false) => {
  let next = false
  if (alert) {
    swal.fire({
      icon: 'warning',
      text: 'Anda yakin akan mereset data ini?',
      showDenyButton: true
    }).then((res) => {
      if (res.isConfirmed) {
        detailArr.value = []
        const newValues = {
          tipe_order: '',
        };

        for (const key in newValues) {
          if (newValues.hasOwnProperty(key)) {
            values[key] = newValues[key];
          }
        }
      }
    })
  }

  setTimeout(() => {
    defaultValues()
  }, 100)
}

// Table Detail
const addDetail = () => {
  const rowsToAdd = values.jumlah_row || 0; // Ensure rowsToAdd is a number
  const newDetails = [];

  for (let i = 0; i < rowsToAdd; i++) {
    const tempItem = {
      no_buku_order: null,
      depo: null,
      no_prefix: null,
      no_suffix: null,
      jenis: null,
      ukuran: values.ukuran
    };
    newDetails.push(tempItem);
  }

  detailArr.value = [...detailArr.value, ...newDetails];
};

// Table Detail
const addDetailBerkas = () => {
  const newDetailBerkas = {
    nama_berkas: null,
    foto_berkas: null,
    tgl: new Date().toLocaleDateString('en-GB'),
    catatan: null
  };

  detailArrBerkas.value = [...detailArrBerkas.value, newDetailBerkas];
};

function acceptTypeDetail(itemBerkas) {
  const file = itemBerkas.foto_berkas
  if (file) {
    const indexFile = file.lastIndexOf('.')
    const extensionFile = file.slice(indexFile + 1).toLowerCase()

    const isImage = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'webp'].includes(extensionFile)
    return isImage ? 'image/*' : 'application/pdf'
  }
  return 'image/*, application/pdf'
}

const removeDetail = (index) => {
  detailArr.value.splice(index, 1)
}


const removeDetailBerkas = (index) => {
  detailArrBerkas.value.splice(index, 1)
}
// End Table Detail

onBeforeMount(async () => {
  onReset();
  if (isRead) {
    try {
      const editedId = route.params.id;
      const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${editedId}`;
      isRequesting.value = true;

      const params = { transform: false, scopes: 'WithDetailAju' };
      const fixedParams = new URLSearchParams(params);
      const res = await fetch(dataURL + '?' + fixedParams, {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `${store.user.token_type} ${store.user.token}`
        }
      });

      if (!res.ok) throw new Error("Failed when trying to read data");
      const resultJson = await res.json();
      initialValues = resultJson.data;
      initialValues.dispensasi_closing_cont = initialValues.dispensasi_closing_cont ? 1 : 0;
      initialValues.dispensasi_closing_doc = initialValues.dispensasi_closing_doc ? 1 : 0;
      initialValues.kode_cust = initialValues['m_customer.kode']


      if (actionText.value?.toLowerCase() === 'copy' && initialValues.uid) {
        delete initialValues.uid;
        delete initialValues.berkas_coo
      }

      // Menambahkan Data Ke Detail NPWP
      initialValues.t_buku_order_d_npwp?.forEach((item) => {
        item.no_buku_order = initialValues.no_buku_order
        if (actionText.value?.toLowerCase() === 'copy') {
          delete item.uid;
          initialValues.status = 'DRAFT'
          initialValues.no_buku_order = null
          item.no_buku_order = null
        }
        detailArr.value = [item, ...detailArr.value];
      });

      // Menambahkan Data Ke Detail AJU
      initialValues.relation_ppjk?.forEach((itemAju) => {
        itemAju['t_ppjk_id'] = itemAju['id']
        itemAju['peb_pib'] = itemAju['no_peb_pib']
        itemAju['no_ppjk'] = itemAju['no_aju']
        detailArrAju.value = [itemAju, ...detailArrAju.value];
      });

      // Menambahkan Data Ke Detail Berkas
      initialValues.t_buku_order_detber?.forEach((itemBerkas) => {
        // itemBerkas['t_ppjk_id'] = itemBerkas['id']
        detailArrBerkas.value = [itemBerkas, ...detailArrBerkas.value];
      });

    } catch (err) {
      isBadForm.value = true;
      swal.fire({
        icon: 'error',
        text: err,
        allowOutsideClick: false,
        confirmButtonText: 'Kembali'
      }).then(() => {
        router.back();
      });
    }

    isRequesting.value = false;
  }

  for (const key in initialValues) {
    values[key] = initialValues[key];
  }
});

function onBack() {
  if (route.query.view_gaji) {
    router.replace('/t_info_gaji')
  } else if (route.query.view_gaji_final) {
    router.replace('/t_info_gaji')
  } else {
    router.replace('/' + modulPath)
  }
  return
}

async function onSave() {
  const details = [...detailArr.value];
  const detailsAju = [...detailArrAju.value];
  const detailsBerkas = [...detailArrBerkas.value];

  // Mengecek apakah ada detail yang belum diisi
  if (details.length === 0) {
    swal.fire({
      title: 'Peringatan',
      icon: 'warning',
      text: 'Detail belum diisi, silahkan isi terlebih dahulu!'
    });
    return;
  }

  for (const item of details) {
    console.log(item)
    if (item.no_prefix && item.no_prefix.length > 4) {
      await swal.fire({
        title: 'Peringatan',
        icon: 'warning',
        text: 'No. Prefix maksimal hanya 4 karakter saja'
      });
      return;
    }
  }

  for (const item of details) {
    console.log(item)
    if (item.no_prefix && item.no_prefix.length > 4) {
      await swal.fire({
        title: 'Peringatan',
        icon: 'warning',
        text: 'No. Prefix maksimal hanya 4 karakter saja'
      });
      return;
    }

    if (item.no_suffix && item.no_suffix.length > 7) {
      await swal.fire({
        title: 'Peringatan',
        icon: 'warning',
        text: 'No. Suffix maksimal hanya 7 karakter saja'
      });
      return;
    }
  }

  try {
    const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`;
    isRequesting.value = true;

    values.dispensasi_closing_cont = values.dispensasi_closing_cont ? 1 : 0
    values.dispensasi_closing_doc = values.dispensasi_closing_doc ? 1 : 0

    const detailsWithSeq = details.map((detail, index) => ({ ...detail, seq: index + 1 }));
    values.t_buku_order_d_npwp = detailsWithSeq.map(detail => ({ ...detail }));

    const detailsWithSeqAju = detailsAju.map((detail, index) => ({ ...detail, seq: index + 1 }));
    values.t_buku_order_d_aju = detailsWithSeqAju.map(detail => ({ ...detail }));

    const detailsWithSeqBerkas = detailsBerkas.map((detail, index) => ({ ...detail, seq: index + 1 }));
    values.t_buku_order_detber = detailsWithSeqBerkas.map(detail => ({ ...detail }));

    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(Object.assign({}, values, {
        t_buku_order_d_npwp: values.t_buku_order_d_npwp
      }))
    });
    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json();
        formErrors.value = responseJson.errors || {};
        throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Oops, sesuatu yang salah terjadi. Coba kembali nanti.");
      } else {
        throw ("Oops, sesuatu yang salah terjadi. Coba kembali nanti.");
      }
    }
    router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())));
  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'warning',
      text: err
    });
  }
  isRequesting.value = false;
}

//  @else----------------------- LANDING

async function onPreview(rows = []) {
  const ids = [...new Set(rows.map(r => r.id).filter(Boolean))]

  if (!ids.length) {
    await swal.fire({
      icon: 'warning',
      text: 'Tidak ada item yang dipilih untuk dicetak'
    })
    return
  }

  isRequesting.value = true

  try {
    const previewUrl = `${store.server.url_backend}/web/biaya_tagihan_p?${ids.map(i => `id[]=${i}`).join('&')}`
    window.open(previewUrl, '_blank')

    router.replace('/' + modulPath)
  } catch (err) {
    console.error(err)
    isBadForm.value = true
    await swal.fire({
      icon: 'error',
      text: err?.message || err
    })
  } finally {
    isRequesting.value = false
  }
}


const filterButton = ref(null);

function filterShowData(status) {
  filterButton.value = filterButton.value === status ? null : status;
  landing.api.params.where = filterButton.value !== null ? `this.status='${filterButton.value}'` : null;
  apiTable.value.reload();
}

const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) => row.status === 'DRAFT',
      // show: () => store.user.data.username==='developer',
      click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Hapus Data Terpilih?',
          confirmButtonText: 'Yes',
          showDenyButton: true,
        }).then(async (result) => {
          if (result.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${row.id}`
              isRequesting.value = true
              const res = await fetch(dataURL, {
                method: 'DELETE',
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                }
              })
              if (!res.ok) {
                const resultJson = await res.json()
                throw (resultJson.message || "Failed when trying to remove data")
              }
              apiTable.value.reload()
              // const resultJson = await res.json()
            } catch (err) {
              isBadForm.value = true
              swal.fire({
                icon: 'error',
                text: err
              })
            }
            isRequesting.value = false
          }
        })
      }
    },
    {
      icon: 'eye',
      title: "Read",
      class: 'bg-green-600 text-light-100',
      // show: (row) => (currentMenu?.can_read)||store.user.data.username==='developer',
      click(row) {
        router.push(`${route.path}/${row.id}?` + tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      show: (row) => row.status === 'DRAFT',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&` + tsId)
      }
    },
    {
      icon: 'copy',
      title: "Copy",
      class: 'bg-gray-600 text-light-100',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Copy&` + tsId)
      }
    },
    {
      icon: 'money-bill-1',
      title: "Cetak Biaya Tagihan",
      class: 'bg-purple-600 text-light-100',
      show: (row) => row.status === 'POST',
      async click(row) {
        try {
          const dataURL = `${store.server.url_backend}/operation/t_buku_order/print_biaya?id=${row.id}`;
          isRequesting.value = true;
          const response = await fetch(dataURL, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
          });

          if (!response.ok) {
            const responseJson = await response.json();
            if ([400, 422, 500].includes(response.status)) {
              formErrors.value = responseJson.errors || {};
              throw new Error(responseJson?.message + " " + responseJson?.data?.errorText || "Failed when trying to post data");
            } else {
              throw new Error("Failed when trying to print data");
            }
          }

          const responseJson = await response.json();
          swal.fire({
            icon: 'success',
            text: responseJson?.message || 'PRINTED'
          });
          window.open(`${store.server.url_backend}/web/biaya_tagihan?export=pdf&size=a4&orientation=potrait&id=${row.id}`)
        } catch (err) {
          isBadForm.value = true;
          swal.fire({
            icon: 'error',
            iconColor: '#1469AE',
            confirmButtonColor: '#1469AE',
            text: err.message
          });
        } finally {
          isRequesting.value = false;
          apiTable.value.reload();
        }
      }
    },
    {
      icon: 'location-arrow',
      title: "Post Data",
      class: 'bg-rose-700 rounded-lg text-white',
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
              const dataURL = `${store.server.url_backend}/operation/t_buku_order/post`
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
                  throw (responseJson.message || "Failed when trying to post data")
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
      icon: 'print',
      title: "Cetak",
      class: 'bg-amber-600 text-light-100',
      show: (row) => row.status === 'DRAFT',
      async click(row) {
        try {
          const dataURL = `${store.server.url_backend}/operation/t_buku_order/print?id=${row.id}`;
          isRequesting.value = true;
          const response = await fetch(dataURL, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
          });

          if (!response.ok) {
            const responseJson = await response.json();
            if ([400, 422, 500].includes(response.status)) {
              formErrors.value = responseJson.errors || {};
              throw new Error(responseJson?.message + " " + responseJson?.data?.errorText || "Failed when trying to post data");
            } else {
              throw new Error("Failed when trying to print data");
            }
          }

          const responseJson = await response.json();
          swal.fire({
            icon: 'success',
            text: responseJson?.message || 'PRINTED'
          });
          window.open(`${store.server.url_backend}/web/buku_order?export=pdf&size=b5&orientation=potrait&id=${row.id}`)
        } catch (err) {
          isBadForm.value = true;
          swal.fire({
            icon: 'error',
            iconColor: '#1469AE',
            confirmButtonColor: '#1469AE',
            text: err.message
          });
        } finally {
          isRequesting.value = false;
          apiTable.value.reload();
        }
      }
    },
  ],
  api: {
    url: `${store.server.url_backend}/operation/${VendpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield: 'this.tgl, this.no_buku_order, this.no_invoice, this.no_bl, this.nama_kapal, this.jenis_barang, this.tujuan_asal, this.nama_pelayaran, this.kode_pelayaran_id, this.pelabuhan_id, this.m_customer_kode, this.m_customer_nama_perusahaan, this.t_no_aju, this.t_ppjk_no_peb_pib, this.no_eir, this.prefix, this.sufix'
    },
    onsuccess(response) {
      console.log("response" + response);
      response.page = response.current_page
      response.hasNext = response.has_next
      return response
    }
  },
  columns: [{
    headerName: 'No',
    valueGetter: (params) => params.node.rowIndex + 1,
    width: 60,
    sortable: true,
    resizable: true,
    filter: true,
    cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
  },
  {
    field: 'no_buku_order',
    headerName: 'Nomor Order',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'm_customer_kode',
    headerName: 'Kode Customer',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'm_customer_nama_perusahaan',
    headerName: 'Customer',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'tgl',
    headerName: 'Tanggal',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-center']
  },
  {
    field: 'voyage',
    headerName: 'Voyage',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-center']
  },
  {
    // field: 'status',
    headerName: 'Status',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: (params) => {
      return params.data['status'] == 1
        ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 'POST' ? `<span class="text-yellow-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
          : (params.data['status'] == 'DRAFT' ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'PRINTED' ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : (params.data['status'] == 'CLOSED' ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Status Tidak Terdaftar</span>`))))
    }
  }
  ]
})

onActivated(() => {
  //  reload table api landing
  if (apiTable.value) {
    if (route.query.reload) {
      apiTable.value.reload()
    }
  }
})

//  @endif -------------------------------------------------END
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))