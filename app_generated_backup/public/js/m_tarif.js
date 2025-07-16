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
// ------------------------------ PERSIAPAN
const endpointApi = 'm_tarif'
onBeforeMount(()=>{
  document.title = 'Master Tarif'
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
    event.preventDefault(); // Prevent the default behavior (e.g., saving the page)
    onSave();
  }
}

let initialValues = {}
const changedValues = []

let values = reactive({
  is_active : true,
  tarif_sewa_diskon : 0
})

const activeTabIndex = ref(0)

// DEFAULT VALUE BEFORE MOUNT --UBAH DISINI
const defaultValues = ()=>{
  values.is_active = true
}
const onReset = async (alert = false) => {
  let next = false
  if(alert){
    swal.fire({
      icon: 'warning',
      text: 'Anda yakin akan mereset data ini?',
      showDenyButton: true
    }).then((res) => {
      if (res.isConfirmed) {
        if(isRead){
          for (const key in initialValues) {
            values[key] = initialValues[key]
          }
        }else{
          for (const key in values) {
            delete values[key]
          }
          defaultValues()
        }
      }
    })
  }
  
  setTimeout(()=>{
    defaultValues() 
  }, 100)
}

//PARAMETER JENIS TARIF
const parameterValue = ref()

onBeforeMount(async () => {
  onReset()
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${editedId}`
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
        delete initialValues.uid
      }
      
      detailArr.value = initialValues.m_tarif_d_jasa.map(det => (
        {
          ...det, kode: det['m_jasa.kode'], nama_jasa: det['m_jasa.nama_jasa']
        }
      ));
      console.log(initialValues.m_tarif_d_jasa)
      detailArrKon.value = initialValues.m_tarif_d_kontainer;

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
  if(actionText.value==='Edit'){
    values.password=''
  }
})


function onBack() {
    router.replace('/' + modulPath)
}
// Table Detail Jasa
const detailArr = ref([]);
const addDetail = () => {
  const tempItem = {
  }
  detailArr.value = [...detailArr.value, tempItem]
}

const onDetailAdd = (e) =>{
  e.forEach(row=>{
    row.m_jasa_id = row.id || null
    detailArr.value.push(row)
  })
}

const removeDetail = (index) => {
  detailArr.value.splice(index,1)
}
// End Table Detail Jasa

// Table Detail Kontainer
const initArrKon = {
  harga_tarif: '',
    tarif: '',
    is_edit: true
}
const detailArrKon = ref([])

const addDetailKon = () => {
  console.log(detailArrKon.value)
  // const initArrKon = { is_active: true, is_edit: false };
  detailArrKon.value.push({...initArrKon});
}

const delDetailKon = async (index) =>{
  const result = await swal.fire({
        icon: 'warning',
        text: 'Hapus Data Terpilih?',
        confirmButtonText: 'Yes',
        showDenyButton: true,
    });

    if (!result.isConfirmed) return;

  detailArrKon.value = detailArrKon.value.filter((item, i) => (i !== index));
}
// End Table Detail Kontainer

async function onSave() {
    values.m_tarif_d_jasa = detailArr.value.map(det=>({
      ...det, ppn: det.ppn ? 1 : 0
    }));
    values.m_tarif_d_kontainer = detailArrKon.value; 
    values.is_active = (values.is_active === true) ? 1 : 0;

    const result = await swal.fire({
        icon: 'warning', text: 'Simpan data?', showDenyButton: true,
    });
    if (!result.isConfirmed) return;

    try {
        const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
        const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : '/' + route.params.id}`;
        isRequesting.value = true;

        const res = await fetch(dataURL, {
            method: isCreating ? 'POST' : 'PUT',
            headers: {
                'Content-Type': 'application/json',
                Authorization: `${store.user.token_type} ${store.user.token}`,
            },
            body: JSON.stringify(values),
        });

        if (!res.ok) {
            const responseJson = await res.json();
            formErrors.value = responseJson.errors || {};
            throw new Error(responseJson.message || "Failed when trying to post data");
        }

        router.replace(`/${modulPath}?reload=${Date.now()}`);
    } catch (err) {
        isBadForm.value = true;
        swal.fire({ icon: 'error', text: err });
    } finally {
        isRequesting.value = false;
    }
}

//  @else----------------------- LANDING
const activeBtn = ref()

function filterShowData(params,noBtn){
  if(activeBtn.value === noBtn){
    activeBtn.value = null
  }else{
    activeBtn.value = noBtn
  }
  if(params){
    landing.api.params.where = `this.is_active=true`
  }else if(activeBtn.value == null){
    // clear params filter
    landing.api.params.where = null
  }else{
    landing.api.params.where = `this.is_active=false`
  }

  apiTable.value.reload()
}

const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
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
        router.push(`${route.path}/${row.id}?`+tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      // show: (row) => (currentMenu?.can_update)||store.user.data.username==='developer',
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
    }
  ],
  api: {
    url: `${store.server.url_backend}/operation/${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield: 'this.id, this.no_tarif, m_customer.nama_perusahaan, m_customer.kota'
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
    headerName:'Kode Tarif',
    field: 'no_tarif',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName:'Customer',
    field: 'm_customer.nama_perusahaan',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName:'Lokasi Stuffing',
    field: 'm_customer.kota',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
  headerName: 'Status', field: 'is_active', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  sortable: true, filter: 'ColFilter', cellRenderer: ({ value }) =>
  value === true
  ? '<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Active</span>'
  : '<span class="text-red-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Inactive</span>'
  }
  ]
})
// sampai sini

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