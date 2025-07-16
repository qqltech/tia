import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated, watch } from 'vue'

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

// ------------------------------ PERSIAPAN
const endpointApi = '/t_confirm_asset'
onBeforeMount(() => {
  document.title = 'Transaksi Konfirmasi Asset'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []

const values = reactive({
  is_active: true,
  tgl_awal: new Date().toLocaleDateString('en-US')
})

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

const detailArr = ref([])

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

async function onSave() {
  values.is_active = (values.is_active === true) ? 1 : 0;
  try {
    values.t_confirm_asset_d = detailArr

    // Add this to ensure kategori_id is properly passed
    if (values.kategori && typeof values.kategori === 'object') {
      values.kategori_id = values.kategori.id
    }

    const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value)
    const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`
    isRequesting.value = true
    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',

      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(values)

    })

    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json()
        formErrors.value = responseJson.errors || {}
        throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data")
      } else {
        throw ("Failed when trying to post data")
      }
    }
    router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())))
  } catch (err) {
    isBadForm.value = true
    swal.fire({
      icon: 'error',
      text: err
    })
  }
  isRequesting.value = false
}

//  @else----------------------- LANDING
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

const filterButton = ref(null);

function filterShowData(params) {
  filterButton.value = filterButton.value === params ? null : params;
  landing.api.params.where = filterButton.value !== null ? `this.status=${filterButton.value}` : null;
  apiTable.value.reload();
}


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