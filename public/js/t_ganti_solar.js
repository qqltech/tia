import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

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
const tsId = `ts=`+(Date.parse(new Date()))

// ------------------------------ PERSIAPAN
const endpointApi = '/t_ganti_solar'
onBeforeMount(()=>{
  document.title = 'Transaksi Ganti Solar'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []

const values = reactive({
  is_active: true,
  status: 'DRAFT',
  tgl: new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(new Date()),
  nominal: 0,
  premi: 0,
  sangu: 0,
  catatan: 'Tidak Ada Catatan'
});



async function solar(t_spk_angkutan_id) {
  console.log(t_spk_angkutan_id);
    if (!t_spk_angkutan_id || !t_spk_angkutan_id.id) {
    values.tipe = null;
    values.no_container_1 = null;
    values.no_container_2 = null;
    values.supir = null;
    values.dari = null;
    values.ke = null;
    values.sektor = null;
    values.sangu = 0;
    values.premi = 0;
    values.catatan = 'Tidak Ada Catatan'; 
    return;
  }
  try {
    // Get data General Kontainer
    const dataURL = `${store.server.url_backend}/operation/t_spk_angkutan/${t_spk_angkutan_id.id}`;
    const params = {
      join: true,
      transform: true
    };
    const fixedParams = new URLSearchParams(params);
    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });

    if (!res.ok) throw new Error("Gagal saat mencoba membaca data");
    const resultJson = await res.json();
    const initialValues = resultJson.data;
    console.log('data', initialValues);


    values.tipe = initialValues.tipe_spk || ''; 
    values.no_container_1 = `${initialValues['t_detail_npwp_container_1.no_prefix'] || ''} - ${initialValues['t_detail_npwp_container_1.no_suffix'] || ''}`;
    values.no_container_2 = `${initialValues['t_detail_npwp_container_2.no_prefix'] || ''} - ${initialValues['t_detail_npwp_container_2.no_suffix'] || ''}`;
    values.supir = initialValues.supir || ''; 
    values.dari = initialValues.dari || ''; 
    values.ke = initialValues.ke || ''; 
    values.sektor = initialValues.sektor1 || ''; 
    values.sangu = initialValues.sangu || 0; 
    values.premi = initialValues.premi || 0; 
    values.catatan = initialValues.catatan || 'Tidak Ada Catatan'; 

  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error',
      text: err.message || "Terjadi kesalahan.",
      allowOutsideClick: false,
      confirmButtonText: 'Kembali',
    }).then(() => {
      router.back();
    });
  } finally {
    isRequesting.value = false;
  }
}

onBeforeMount(async () => {
  if (isRead) {
    // READ DATA
    try {
      const editedId = route.params.id;
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`;
      isRequesting.value = true;

      const params = { join: false, transform: false };
      const fixedParams = new URLSearchParams(params);
      const res = await fetch(dataURL + '?' + fixedParams, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      });
      
      if (!res.ok) throw new Error("Failed when trying to read data");
      const resultJson = await res.json();
      const initialValues = resultJson.data;

      for (const key in initialValues) {
        values[key] = initialValues[key];
      }

      await new Promise(resolve => setTimeout(resolve, 500));

      if (initialValues.t_spk_angkutan_id) {
        await solar({ id: initialValues.t_spk_angkutan_id });
      }

    } catch (err) {
      isBadForm.value = true;
      swal.fire({
        icon: 'error',
        text: err.message || "Terjadi kesalahan.",
        allowOutsideClick: false,
        confirmButtonText: 'Kembali',
      }).then(() => {
        router.back();
      });
    } finally {
      isRequesting.value = false;
    }
  }
});

watchEffect(() => {

  values.nominal = values.sangu - values.premi;
});


function onBack() {
  let isChanged = false
  for (const key in initialValues) {
    if (values[key] !== initialValues[key]) {
      isChanged = true
      break;
    }
  }

  if (!isChanged) {
    router.replace('/' + modulPath)
    return
  }

  swal.fire({
    icon: 'warning',
    text: 'Buang semua perubahan dan kembali ke list data?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      router.replace('/' + modulPath)
    }
  })
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
  //values.tags = JSON.stringify(values.tags)
    values.is_active = (values.is_active === true) ? 1 : 0;
      try {
        const isCreating = ['Create','Copy','Tambah'].includes(actionText.value)
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
        router.replace('/' + modulPath + '?reload='+(Date.parse(new Date())))
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
        router.push(`${route.path}/${row.id}?`+tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      // show: () => store.user.data.direktorat==='ADMIN INSTANSI',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&`+tsId)
      }
    },
    {
      icon: 'copy',
      title: "Copy",
      class: 'bg-gray-600 text-light-100',
      // show: () => store.user.data.direktorat==='ADMIN INSTANSI',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Copy&`+tsId)
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
      simplest: true,
      searchfield:'this.id, this.kode, this.deskripsi, this.group, this.is_active',
    },
    onsuccess(response) {
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
    headerName: 'No Order Angkutan',
    field: 'no_angkutan',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
    headerName: 'Tanggal',
    field: 'tgl',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
    headerName: 'Nominal',
    field: 'nominal',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200']
  },  
  {
    headerName: 'Catatan',
    field: 'catatan',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
  headerName: 'Status', 
  field: 'is_active', 
  flex: 1, 
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  sortable: true, 
  filter: 'ColFilter', 
  cellRenderer: ({ value }) =>
  value === true
  ? '<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Active</span>'
  : '<span class="text-red-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Inactive</span>'
  }
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
watchEffect(()=>store.commit('set', ['isRequesting', isRequesting.value]))