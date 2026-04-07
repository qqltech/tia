import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated, watch } from 'vue'

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
const tsId = `ts=` + (Date.parse(new Date()))
const tipe = ref(route.query.isType)

const allowedTipe = [
  'chasis', 'kendaraan', 'inventaris', 'mesin', 'lain'
]

// ------------------------------ PERSIAPAN
const endpointApi = '/t_confirm_asset'
onBeforeMount(() => {
  document.title = 'Transaksi Konfirmasi Asset'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
const detailArr = ref([])

// HOT KEY
onMounted(() => {
  // window.addEventListener('keydown', handleKeyDown);
  const today = new Date();
  // Format tanggal sesuai dengan "dd-mm-yyyy"
  const day = String(today.getDate()).padStart(2, '0');
  const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
  const year = today.getFullYear();
  const formattedDate = `${day}/${month}/${year}`;
  values.tanggal = formattedDate;
  values.tanggal_pakai = formattedDate;

})

onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeyDown);
})

const handleKeyDown = (event) => {
  console.log(event)
  if (event?.ctrlKey && event?.key === 's') {
    event.preventDefault(); // Prevent the default behavior (e.g., saving the page)
    onSave();
  }
}

let initialValues = {}
const changedValues = []

const values = reactive({
  is_active: true,
  tgl_awal: new Date().toLocaleDateString('en-US'),
  tipe: tipe.value,
  tipe_asset:
    tipe.value === 'chasis' ? 'Chasis' :
      tipe.value === 'kendaraan' ? 'Kendaraan' :
        tipe.value === 'inventaris' ? 'Inventaris Kantor' :
          tipe.value === 'mesin' ? 'Asset Mesin' :
            tipe.value === 'lain' ? 'Lain-lain' : '',
  nilai_minimal: 0,
  nilai_penyusutan: 0,
  nilai_buku: 0
})

const assetFieldMap = {
  kendaraan: [
    'jenis_kendaraan_id', 'no_mesin', 'no_rangka', 'nopol', 'no_bpkb',
    'tahun_produksi', 'merk_id', 'jumlah_roda', 'bahan_bakar_id', 'no_urut_kendaraan',
    'jumlah_cylinder', 'warna_id', 'no_faktur', 'tanggal_faktur', 'nama_pemilik'
  ],
  chasis: [
    'dimensi', 'jumlah_ban', 'warna_id'
  ],
  inventaris: [
    'spesifikasi', 'merk_id', 'jenis_inventaris_id'
  ],
  mesin: [
    'no_mesin', 'tipe_mesin_id', 'dimensi', 'nomor_sertifikat', 'tahun_produksi'
  ]
};

watch(
  () => values.t_lpb_id,
  (newVal, oldVal) => {
    if (oldVal && newVal !== oldVal) {
      // Reset Asset jika user mengganti Nomor LPB agar harga tidak salah
      values.m_item_id = null;
      values.kode = null;
      values.name_asset = null;
      values.harga_perolehan = 0;
    }
  }
);

watch(
  () => [values.harga_perolehan, values.masa_manfaat],
  ([harga_perolehan, masa_manfaat]) => {
    if (harga_perolehan && masa_manfaat) {
      values.nilai_penyusutan = harga_perolehan / masa_manfaat;
    } else {
      values.nilai_penyusutan = 0;
    }
  }
);


watch(() => values.harga_perolehan, (newVal) => {
  values.nilai_buku = newVal;
});

function parseDDMMYYYY(dateStr) {
  const [dd, mm, yyyy] = dateStr.split('/');
  return new Date(yyyy, mm - 1, dd); // bulan - 1 karena index dari 0
}

function updateStatusForEach(detailArr, today) {
  if (!today || !Array.isArray(detailArr) || !detailArr.length) return;

  let dateToday;
  if (typeof today === 'string' && today.includes('/')) {
    const [dd, mm, yyyy] = today.split('/');
    dateToday = new Date(yyyy, mm - 1, dd);
  } else {
    dateToday = new Date(today);
  }

  detailArr.forEach(item => {
    if (!item.tanggal_penyusutan) return;

    const dateSusut = parseDDMMYYYY(item.tanggal_penyusutan);

    item.status = dateSusut <= dateToday ? "COMPLETE" : "NEW";
    
  });
}

watch([() => values.tanggal, () => detailArr.value], ([_, newDetailArr]) => {
  // Gunakan tanggal hari ini:
  const today = new Date();
  updateStatusForEach(newDetailArr, today);
}, { immediate: true });


function formatDateToDDMMYYYY(dateStr) {
  const [yyyy, mm, dd] = dateStr.split('-');
  return `${dd}/${mm}/${yyyy}`;
}

function formatCurrency(value) {
  if (value == null || value === '') return '';
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(value);
};

async function generateTotal() {
  // Validasi nilai_minimal tidak lebih besar dari harga_perolehan
  if (
    parseFloat(values.nilai_minimal) > parseFloat(values.harga_perolehan)
  ) {
    swal.fire({
      icon: 'warning',
      title: 'Validasi Gagal',
      text: 'Nilai minimal tidak boleh lebih besar dari harga perolehan.',
      confirmButtonText: 'OK',
    });
    return; // hentikan eksekusi
  }

  // Tampilkan loading popup
  swal.fire({
    title: 'Mohon tunggu...',
    text: 'Sedang memproses generate total',
    allowOutsideClick: false,
    didOpen: () => {
      swal.showLoading();
    }
  });

  // Timer maksimal 3 menit (180000 ms)
  let timeoutHandler = setTimeout(() => {
    swal.close();
    swal.fire({
      icon: 'error',
      title: 'Waktu Habis',
      text: 'Proses generate terlalu lama. Silakan coba lagi.',
      confirmButtonText: 'OK',
    });
  }, 180000);

  try {
    const dataURL = `${store.server.url_backend}/operation${endpointApi}/generateDepreciation`;
    const res = await fetch(dataURL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify(values),
    });

    if (!res.ok) throw new Error('Failed to generate total.');

    const hasil = await res.json();

    const butaMap = hasil.map(itels => ({
      seq: itels.no,
      tanggal_penyusutan: itels.tanggal_penyusutan ? formatDateToDDMMYYYY(itels.tanggal_penyusutan) : null,
      nilai_akun_sebelum_penyusutan: itels.nilai_akun_sebelum,
      nilai_buku_sebelum_penyusutan: itels.nilai_buku_sebelum,
      nilai_penyusutan: itels.nilai_penyusutan,
      nilai_akumulasi_setelah_penyusutan: itels.nilai_akun_setelah,
      nilai_buku_setelah_penyusutan: itels.nilai_buku_setelah,
      status: []
    }));

    detailArr.value = butaMap;

    clearTimeout(timeoutHandler); // Stop timer jika selesai sebelum 3 menit
    swal.close();

    swal.fire({
      icon: 'success',
      text: 'Total Berhasil Di Generated',
      confirmButtonText: 'OK',
    });
  } catch (err) {
    clearTimeout(timeoutHandler); // Stop timer jika error
    swal.close();
    console.error(err);
    swal.fire({
      icon: 'error',
      text: err.message || 'An error occurred while generating total.',
      confirmButtonText: 'OK',
    });
  }
}

onBeforeMount(async () => {
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = { join: false, transform: false }
      const fixedParams = new URLSearchParams(params)
      const res = await fetch(dataURL + '?' + fixedParams, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      })
      if (!res.ok) throw new Error("Failed when trying to read data")
      const resultJson = await res.json()
      initialValues = resultJson.data

      const tipe = initialValues.tipe_asset?.toLowerCase();
      const confirmationKey = `t_confirm_asset_${tipe}`;

      if (initialValues[confirmationKey] && Array.isArray(initialValues[confirmationKey])) {
        initialValues[confirmationKey].forEach((dt) => {
          const fields = assetFieldMap[tipe];
          if (fields) {
            fields.forEach((field) => {
              initialValues[field] = dt[field];
            });
          }
        });
      }

      initialValues.no_draft = null
      // Add this mapping for kategori
      if (initialValues.kategori) {
        initialValues.kategori_id = initialValues.kategori.id
      }

      if (Array.isArray(initialValues.t_confirm_asset_d)) {
        initialValues.t_confirm_asset_d.forEach(item => {
          detailArr.value.push(item)
        })
      }

    } catch (err) {
      isBadForm.value = true
      swal.fire({
        icon: 'error',
        text: err,
        allowOutsideClick: false,
        confirmButtonText: 'Kembali',
      }).then(() => {
        router.back()
      })
    }
    isRequesting.value = false
  }

  for (const key in initialValues) {
    values[key] = initialValues[key]
  }
})

watch(
  () => [values.harga_perolehan, values.masa_manfaat],
  ([harga_perolehan, masa_manfaat]) => {
    console.log("asdassd");
    if (harga_perolehan && masa_manfaat) {
      if (masa_manfaat <= 0) {
        swal.fire({
          icon: 'error',
          text: 'Masa manfaat harus lebih besar dari 0.',
          confirmButtonText: 'OK',
        });
        values.nilai_penyusutan = 0;
      } else {
        values.nilai_penyusutan = harga_perolehan / masa_manfaat;
      }
    } else {
      values.nilai_penyusutan = 0;
    }

  }
);

watch(
  () => [values.tgl_awal, values.masa_manfaat],
  ([tgl_awal, masa_manfaat]) => {
    if (tgl_awal && masa_manfaat) {
      const startDate = new Date(tgl_awal);
      const endDate = new Date(startDate);
      endDate.setMonth(startDate.getMonth() + parseInt(masa_manfaat));
      values.tgl_akhir = `${endDate.getMonth() + 1}/${endDate.getDate()}/${endDate.getFullYear()}`;
      values.status = 'DRAFT';
    }
  }
);

watch(
  () => values.nilai_min,
  (newValue) => {
    // Remove non-numeric characters and convert to number
    if (typeof newValue === 'string') {
      values.nilai_min = parseFloat(newValue.replace(/[^\d]/g, '')) || 0;
    }
  }
);

const determineDateStatus = (tanggalSebelum) => {
  const today = new Date();
  const dateToCheck = new Date(tanggalSebelum);

  const value = dateToCheck < today || dateToCheck.toDateString() === today.toDateString() ? 'OLD' : 'NEW';
  console.log(value);
  return value;
};

const formatRupiah = (number) => {
  const numericValue = Number(number);

  console.log("numericValue" + numericValue);

  if (isNaN(numericValue) || !isFinite(numericValue)) {
    return "Rp 0";
  }

  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(numericValue);
};



const addDetail = async () => {
  try {
    const dataURL = `${store.server.url_backend}/operation${endpointApi}/generateDepreciation`;
    const res = await fetch(dataURL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify(values),
    });

    if (!res.ok) throw new Error('Failed to generate total.');

    const hasil = await res.json();
    const dataArray = Array.isArray(hasil) ? hasil : hasil.data || [];

    // Ensure numeric values are properly parsed
    const butaMap = dataArray.map(itels => ({
      seq: itels.no,
      tgl_penyusutan: itels.tanggal_penyusutan,
      nilai_akun_sebelum: parseFloat(itels.nilai_akun_sebelum || 0),
      nilai_buku_sebelum: parseFloat(itels.nilai_buku_sebelum || 0),
      nilai_penyusutan: parseFloat(itels.nilai_penyusutan || 0),
      nilai_akun_setelah: parseFloat(itels.nilai_akun_setelah || 0),
      nilai_buku_setelah: parseFloat(itels.nilai_buku_setelah || 0),
      status: determineDateStatus(itels.tanggal_penyusutan)
    }));

    detailArr.value = butaMap;

    swal.fire({
      icon: 'success',
      text: 'Total Berhasil Di Generated',
      confirmButtonText: 'OK',
    });
  } catch (err) {
    console.error(err);
    swal.fire({
      icon: 'error',
      text: err.message || 'An error occurred while generating total.',
      confirmButtonText: 'OK',
    });
  }
};

const removeItem = (index) => {
  detailArr.value.splice(index, 1);
};



function onBack() {
  router.replace('/' + modulPath)
}

function onReset() {
  swal.fire({
    icon: 'warning',
    text: 'Reset this form data?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      for (const key in initialValues) {
        values[key] = initialValues[key]
      }
    }
  })
}

// async function onSave() {
//   values.is_active = (values.is_active === true) ? 1 : 0;
//   try {
//     values.t_confirm_asset_d = detailArr

//     // Add this to ensure kategori_id is properly passed
//     if (values.kategori && typeof values.kategori === 'object') {
//       values.kategori_id = values.kategori.id
//     }

//     const type = route.query.isType;

//     const assetDetailMap = {
//       kendaraan: {
//         key: 't_confirm_asset_kendaraan',
//         data: [{
//           jenis_kendaraan_id: values.jenis_kendaraan_id,
//           no_mesin: values.no_mesin,
//           no_rangka: values.no_rangka,
//           nopol: values.nopol,
//           no_bpkb: values.no_bpkb,
//           no_urut_kendaraan: values.no_urut_kendaraan,
//           tahun_produksi: values.tahun_produksi,
//           merk_id: values.merk_id,
//           jumlah_roda: parseInt(values.jumlah_roda),
//           bahan_bakar_id: values.bahan_bakar_id,
//           jumlah_cylinder: parseInt(values.jumlah_cylinder),
//           warna_id: values.warna_id,
//           no_faktur: values.no_faktur,
//           tanggal_faktur: values.tanggal_faktur,
//           nama_pemilik: values.nama_pemilik
//         }]
//       },
//       chasis: {
//         key: 't_confirm_asset_chasis',
//         data: [{
//           dimensi: values.dimensi,
//           jumlah_ban: values.jumlah_ban,
//           warna_id: values.warna_id
//         }]
//       },
//       inventaris: {
//         key: 't_confirm_asset_inventaris',
//         data: [{
//           spesifikasi: values.spesifikasi,
//           merk_id: values.merk_id,
//           jenis_inventaris_id: values.jenis_inventaris_id
//         }]
//       },
//       mesin: {
//         key: 't_confirm_asset_mesin',
//         data: [{
//           no_mesin: values.no_mesin,
//           tipe_mesin_id: values.tipe_mesin_id,
//           dimensi: values.dimensi,
//           nomor_sertifikat: values.nomor_sertifikat,
//           tahun_produksi: values.tahun_produksi
//         }]
//       },
//       // lain: {
//       //   key: 't_confirm_asset_lain',
//       //   data: [{}] // tambahkan field sesuai kebutuhan
//       // }
//     };

//     Object.entries(assetDetailMap).forEach(([t, val]) => {
//       if (t !== type) {
//         delete values[val.key];
//       }
//     });

//     if (assetDetailMap[type]) {
//       values[assetDetailMap[type].key] = assetDetailMap[type].data;
//     }

//     const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value)
//     const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`
//     isRequesting.value = true
//     const res = await fetch(dataURL, {
//       method: isCreating ? 'POST' : 'PUT',

//       headers: {
//         'Content-Type': 'Application/json',
//         Authorization: `${store.user.token_type} ${store.user.token}`
//       },
//       body: JSON.stringify(values)

//     })

//     if (!res.ok) {
//       if ([400, 422].includes(res.status)) {
//         const responseJson = await res.json()
//         formErrors.value = responseJson.errors || {}
//         throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data")
//       } else {
//         throw ("Failed when trying to post data")
//       }
//     }
//     router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())))
//   } catch (err) {
//     isBadForm.value = true
//     swal.fire({
//       icon: 'error',
//       text: err
//     })
//   }
//   isRequesting.value = false
// }

async function onSave() {
  try {
    isRequesting.value = true;
    let next = true;

    if (!values.tanggal) {
      swal.fire({
        icon: 'warning',
        text: `Tanggal harus diisi`
      });
      next = false;
      return;
    }

    if (!next) return;

    values.t_asset_confirmation_detail = detailArr.value;

    const type = route.query.isType;

    const assetDetailMap = {
      kendaraan: {
        key: 't_asset_confirmation_kendaraan',
        data: [{
          jenis_kendaraan_id: values.jenis_kendaraan_id,
          no_mesin: values.no_mesin,
          no_rangka: values.no_rangka,
          nopol: values.nopol,
          no_bpkb: values.no_bpkb,
          no_urut_kendaraan: values.no_urut_kendaraan,
          tahun_produksi: values.tahun_produksi,
          merk_id: values.merk_id,
          jumlah_roda: parseInt(values.jumlah_roda),
          bahan_bakar_id: values.bahan_bakar_id,
          jumlah_cylinder: parseInt(values.jumlah_cylinder),
          warna_id: values.warna_id,
          no_faktur: values.no_faktur,
          tanggal_faktur: values.tanggal_faktur,
          nama_pemilik: values.nama_pemilik
        }]
      },
      bangunan: {
        key: 't_asset_confirmation_bangunan',
        data: [{
          nomor_sertifikat: values.nomor_sertifikat,
          jenis_sertifikat_id: values.jenis_sertifikat_id,
          luas_bangunan: values.luas_bangunan,
          luas_tanah: values.luas_tanah,
          alamat: values.alamat,
          atas_nama: values.atas_nama
        }]
      },
      tabung: {
        key: 't_asset_confirmation_tabung',
        data: [{
          m_tabung_id: values.m_tabung_id
        }]
      },
      chasis: {
        key: 't_asset_confirmation_chasis',
        data: [{
          dimensi: values.dimensi,
          jumlah_ban: values.jumlah_ban,
          warna_id: values.warna_id
        }]
      },
      tanah: {
        key: 't_asset_confirmation_tanah',
        data: [{
          luas_tanah: values.luas_tanah,
          alamat: values.alamat,
          nomor_njop: values.nomor_njop,
          nomor_sertifikat: values.nomor_sertifikat,
          nomor_ajb: values.nomor_ajb,
          atas_nama: values.atas_nama,
          jenis_sertifikat_id: values.jenis_sertifikat_id
        }]
      },
      cradle: {
        key: 't_asset_confirmation_cradle',
        data: [{
          m_cradle_id: values.m_cradle_id
        }]
      },
      inventaris: {
        key: 't_asset_confirmation_inventaris',
        data: [{
          spesifikasi: values.spesifikasi,
          merk_id: values.merk_id,
          jenis_inventaris_id: values.jenis_inventaris_id
        }]
      },
      tangki: {
        key: 't_asset_confirmation_tangki',
        data: [{
          m_isotank_id: values.m_isotank_id,
          made_in: values.made_in,
          dimensi: values.dimensi,
          satuan_pressure_id: values.satuan_pressure_id,
          pressure: values.pressure,
          satuan_temperatur_id: values.satuan_temperatur_id,
          temperatur: values.temperatur
        }]
      },
      mesin: {
        key: 't_asset_confirmation_mesin',
        data: [{
          no_mesin: values.no_mesin,
          tipe_mesin_id: values.tipe_mesin_id,
          dimensi: values.dimensi,
          nomor_sertifikat: values.nomor_sertifikat,
          tahun_produksi: values.tahun_produksi
        }]
      }
    };

    // Hapus semua properti asset spesifik yang tidak sesuai tipe
    Object.entries(assetDetailMap).forEach(([t, val]) => {
      if (t !== type) {
        delete values[val.key];
      }
    });

    // Tambahkan hanya yang sesuai tipe
    if (assetDetailMap[type]) {
      values[assetDetailMap[type].key] = assetDetailMap[type].data;
    }

    // Inti onSave
    const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
    let method = isCreating ? 'POST' : 'PUT';
    let dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`;

    // Jika EditBerkas maka selalu POST ke id yang sama
    if (route.query.action === 'EditBerkas') {
      if (!kendaraanDetailId.value) {
        swal.fire({
          icon: 'error',
          text: 'ID detail kendaraan tidak ditemukan'
        });
        return;
      }

      method = 'POST';
      // masukkan langsung kendaraanDetailId ke dalam url
      dataURL = `${store.server.url_backend}/operation/t_asset_confirmation_kendaraan/${kendaraanDetailId.value}`;

    }


    isRequesting.value = true;
    const res = await fetch(dataURL, {
      method,
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(values)
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

    // ✅ Notifikasi sukses
    await swal.fire({
      icon: 'success',
      text: 'Data berhasil disimpan!'
    });

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
const valLand = reactive({})

function aDay() {
  const today = new Date();
  const year = today.getFullYear();
  const formattedDate = `${year}`;

  return formattedDate
}

onBeforeMount(() => {
  valLand.filter_tahun = aDay()
  filterShowData()
})

function parseTanggalToYMD(tanggal) {
  const [yyyy] = tanggal.split('/');
  return `${yyyy}`;
}

//FILTER
const filterButton = ref(null)

function filterShowData(statusLabel = null, noBtn = null) {
  const statusMap = {
    1: 'DRAFT',
    2: 'POST',
  }

  // Handle klik button
  if (noBtn !== null) {
    if (filterButton.value === noBtn) {
      filterButton.value = null
      statusLabel = null
    } else {
      filterButton.value = noBtn
    }
  } else {
    statusLabel = statusMap[filterButton.value] || null
  }

  const filters = []

  // Filter status
  if (statusLabel) {
    filters.push(`this.status='${statusLabel.toUpperCase()}'`)
  }

  // Filter Tahun
  if (valLand.filter_tahun) {
    filters.push(`EXTRACT(YEAR FROM this.tgl_asset) = ${valLand.filter_tahun}`)
  }

  // Apply ke landing
  landing.api.params.where = filters.length
    ? filters.join(' AND ')
    : null

  apiTable.value.reload()
}

const modalOpenCreate = ref(false)

function openCreatePopUp() {
  modalOpenCreate.value = true
}

function closeCreatePopUp() {
  modalOpenCreate.value = false
}

const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      // show: () => store.user.data.direktorat==='ADMIN INSTANSI',
      click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Hapus Data Terpilih?',
          confirmButtonText: 'Yes',
          showDenyButton: true,
        }).then(async (result) => {
          if (result.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation${endpointApi}/${row.id}`
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
      // show: () => store.user.data.direktorat==='ADMIN INSTANSI',
      click(row) {
        router.push(`${route.path}/${row.id}?` + tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      // show: () => store.user.data.direktorat==='ADMIN INSTANSI',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&` + tsId)
      }
    },
    {
      icon: 'copy',
      title: "Copy",
      class: 'bg-gray-600 text-light-100',
      // show: () => store.user.data.direktorat==='ADMIN INSTANSI',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Copy&` + tsId)
      }
    }
  ],
  api: {
    url: `${store.server.url_backend}/operation${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      join: true,
      simplest: true,
      searchfield: 't_lpb.no_lpb, pic.nama, m_perkiraan_akun_penyusutan.nama_coa',
    },
    onsuccess(response) {
      response.page = response.current_page;
      response.hasNext = response.has_next;
      return response;
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
    headerName: 'NOMOR LPB',
    field: 't_lpb.no_lpb',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'NAMA PIC',
    field: 'pic.nama',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'PERKIRAAN PENYUSUTAN',
    field: 'm_perkiraan_akun_penyusutan.nama_coa',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  ]
})

// const filterButton = ref(null);

// function filterShowData(params) {
//   filterButton.value = filterButton.value === params ? null : params;
//   landing.api.params.where = filterButton.value !== null ? `this.status=${filterButton.value}` : null;
//   apiTable.value.reload();
// }


onActivated(() => {
  //  reload table api landing
  if (apiTable.value) {
    if (route.query.reload) {
      apiTable.value.reload()
    }
  }
})

onMounted(() => {
  getJenisKendaraan()
})

//  @endif -------------------------------------------------END
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))