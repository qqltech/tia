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
const tsId = `ts=`+(Date.parse(new Date()))

const currency = ref(null)
const kurs = ref(null)

// ------------------------------ PERSIAPAN
const endpointApi = '/t_nota_rampung'
onBeforeMount(()=>{
  document.title = 'Nota Rampung'
})

//  @if( $id )------------------- JS CONTENT ! PENTING JANGAN DIHAPUS



// HOT KEY
onMounted(()=>{
  window.addEventListener('keydown', handleKeyDown);
})
onBeforeUnmount(()=>{
  window.removeEventListener('keydown', handleKeyDown);
})

const handleKeyDown = (event) => {
  if (event?.ctrlKey && event?.key === 's') {
    event.preventDefault(); 
    onSave();
  }
}

let initialValues = {}
const changedValues = []


onBeforeMount(async () => {
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
      isRequesting.value = true
     
      const params = { join: true, transform: false }
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
      if(actionText.value?.toLowerCase() === 'copy'){
        delete initialValues.status,
        delete initialValues.tanggal;
      }
      if(actionText.value?.toLowerCase() === 'edit'){
        delete initialValues.tanggal;
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
  if(values['m_dir.nama']){
    values.direktorat = values['m_dir.nama']
  }
})

const values = reactive({
  status : "DRAFT" ,
  tanggal : new Date().toLocaleDateString('id-ID', { month: '2-digit', day: '2-digit', year: 'numeric' })
})


watchEffect(() => {
  if (values.currency === 'Rupiah') {
    values.kurs = 1;
  }
})

function onReset() {
  swal.fire({
    icon: 'warning',
    text: 'Anda yakin akan mereset data ini?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      const newValues = {};
      for (const key in values) {
        if (values.hasOwnProperty(key)) {
          newValues[key] = ''; 
        }
      }
      for (const key in newValues) {
        if (newValues.hasOwnProperty(key)) {
          values[key] = newValues[key];
        }
      }
    }
  })
}

function onBack() {
    router.replace('/' + modulPath)
}

async function onSave() {
  //values.tags = JSON.stringify(values.tags)
      try {
        const isCreating = ['Create','Copy','Tambah'].includes(actionText.value)
        if (!isCreating) {
          values.tanggal = new Date().toISOString().split('T')[0]
        }
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
      show: (row) =>  row.status?.toUpperCase() ==='DRAFT',
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
      click(row) {
        router.push(`${route.path}/${row.id}?`+tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      show: (row) => row.status?.toUpperCase() == 'DRAFT',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&`+tsId)
      }
    },
    {
      icon: 'copy',
      title: "Copy",
      class: 'bg-gray-600 text-light-100',
      
      click(row) {
        router.push(`${route.path}/${row.id}?action=Copy&`+tsId)
      }
    },
    {
      icon: 'print',
      title: "Cetak",
      class: 'bg-gray-600 text-light-100',
      click(row) {
        window.open(`${store.server.url_backend}/web/surat_jalan?export=pdf&size=a4&orientation=potrait&group=SATUAN%20JASA&id=${row.id}`)
      }
    },
    {
      icon: 'table',
      title: "Unduh Excel",
      class: 'bg-gray-600 text-light-100',
      click(row) {
        window.open(`${store.server.url_backend}/web/surat_jalan?export=excel&size=a4&orientation=potrait&group=SATUAN%20JASA&id=${row.id}`)
      }
    },
        {
      icon: 'location-arrow',
      title: "Post Data",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status?.toUpperCase() === 'DRAFT' ,
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
              const dataURL = `${store.server.url_backend}/operation/t_nota_rampung/post`
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
                if ([400, 422].includes(res.status)) {
                  const responseJson = await res.json()
                  formErrors.value = responseJson.errors || {}
                  throw new Error(responseJson.message || "Failed when trying to post data")
                } else {
                  throw new Error("Failed when trying to post data")
                }
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
  ],
  api: {
    url: `${store.server.url_backend}/operation${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield:'this.no_draft, this.no_nota_rampung, this.customer, this.pelabuhan, this.status',
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
    headerName: 'No.Draft',
    field: 'no_draft',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
    headerName: 'No.Nota Rampung',
    field: 'no_nota_rampung',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
    headerName: 'Customer',
    field: 'customer',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
    headerName: 'Pelabuhan',
    field: 'pelabuhan',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
    headerName: 'Tipe Container',
    field: 'tipe1',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
    headerName: 'No. Container',
    field: 'nomor_container',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
    // field: 'status',
    headerName: 'Status',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText:true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ( params ) => {
      return params.data['status'] == 1
        ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 'POST' ? `<span class="text-amber-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 'DRAFT' ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 'completed' ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 11 ?  `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 21 ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 5 ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>` 
        : (params.data['status'] == 6 ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>` 
        : (params.data['status'] == 7 ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 9 ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Status Tidak Terdaftar</span>`))))))))
        )
    }
  }
  ]
})

console.log(landing, "<<<<");

const filterButton = ref(null);
function filterShowData(status) {
  filterButton.value = filterButton.value === status ? null : status;
  landing.api.params.where = filterButton.value !== null ? `this.status='${filterButton.value}'` : null;
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