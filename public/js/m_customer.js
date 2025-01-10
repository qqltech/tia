import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, computed, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, watch, onActivated } from 'vue'

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
const formErrorsNpwp = ref({})
const formErrorsAddr = ref({})
const formErrorsName = ref([])
const tsId = `ts=`+(Date.parse(new Date()))
// ------------------------------ PERSIAPAN
const endpointApi = 'm_customer'
onBeforeMount(()=>{
  document.title = 'Master Customer'
})

//  @if( $id )------------------- JS CONTENT ! PENTING JANGAN DIHAPUS

// HOT KEY
onMounted(()=> {
  if (apiTable.value) {
      setTimeout(() => {
    apiTable.value.reload();
  }, 500);
  }
})

onBeforeUnmount(()=>{
  window.removeEventListener('keydown', handleKeyDown);
})

const handleKeyDown = (event) => {
  // console.log(event)
  if (event?.ctrlKey && event?.key === 's') {
    event.preventDefault(); // Prevent the default behavior (e.g., saving the page)
    onSave();
  }
}

let initialValues = {}
const changedValues = []

let values = reactive({
})

const validEmail = (v) => (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v))

const isValidEmail=ref(true)
function validateEmail(v) {
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v))
  {
    formErrors.value["email"] = null
    isValidEmail.value=true
  } else{
    formErrors.value["email"] = ['Please enter a valid email address']
    isValidEmail.value = false
  } 
  values.email=v
}

const isValidEmail2=ref(true)
function validateEmail2(v) {
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v))
  {
    formErrors.value["email_cp1"] = null
    isValidEmail2.value=true
  } else{
    formErrors.value["email_cp1"] = ['Please enter a valid email address']
    isValidEmail2.value = false
  } 
  values.email_cp1=v
}

const isValidEmail3=ref(true)
function validateEmail3(v) {
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v))
  {
    formErrors.value["email_cp2"] = null
    isValidEmail3.value=true
  } else{
    formErrors.value["email_cp2"] = ['Please enter a valid email address']
    isValidEmail3.value = false
  } 
  values.email_cp2=v
}


const isValidEmail4=ref(true)
function validateEmail4(v) {
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v))
  {
    formErrorsAddr.value["email"] = null
    isValidEmail4.value=true
  } else{
    formErrorsAddr.value["email"] = ['Please enter a valid email address']
    isValidEmail4.value = false
  } 
  valuesAddr.email=v
}


const activeTabIndex = ref(0)

// DEFAULT VALUE BEFORE MOUNT --UBAH DISINI
const defaultValues = ()=>{
  values.is_active = 1
  values.taxable = 1
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
        detailArr.value=[]  
        detailArrAddr.value=[]
        
        initialValues.m_cust_d_npwp?.forEach((items)=>{  
          if(actionText.value?.toLowerCase() === 'copy' && items.uid){
            delete items.uid
          }    
          items.is_active = items.is_active ? 1 : 0
          items.is_primary = items.is_primary ? 1 : 0
          detailArr.value = [items, ...detailArr.value]
        })
        initialValues.m_cust_d_addr?.forEach((items)=>{  
          if(actionText.value?.toLowerCase() === 'copy' && items.uid){
            delete items.uid
          }    
          items.provine_name = items['province.value1']
          items.city_name = items['city.value1']
          items.is_active = items.is_active ? 1 : 0
          items.is_primary = items.is_primary ? 1 : 0
          detailArrAddr.value = [items, ...detailArrAddr.value]
        })
        }else{
          detailArr.value=[]
          detailArrAddr.value=[]
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

const valuesNpwp = reactive({
  no_npwp: null,
  nama_npwp: null,
  alamat: null,
  is_active: 1,
  default: 0
})

const onDetailAddAddr = (e) => {
  e.forEach(row=>{
    detailArrAddr.value.push(row)
  })
}


const valuesAddr = reactive({
  lokasistuffing: null,
  addr: null,
  is_active: 1
})
// Table Detail
const detailArr = ref([])

const isDisabled = computed(() => detailArr.value.length > 0)

const addDetail = () => {
  if (isDisabled.value) {
    swal.fire({
      icon: 'warning',
      text: 'Data sudah ditambahkan, Anda tidak bisa menambahkan lagi.'
    })
    return
  }
  
  var tempObj = {}
  for (const key in valuesNpwp) {
    if (valuesNpwp[key] === null) {
      tempObj[key] = ['Bidang ini wajib diisi']
    }
  }
  if (Object.keys(tempObj).length >= 1) {
    console.log(valuesNpwp)
    formErrorsNpwp.value = tempObj
    swal.fire({
      icon: 'error',
      text: 'Masih ada field yang belum terisi'
    })
    return
  }
  
  const tempItem = { ...valuesNpwp }
  detailArr.value = [...detailArr.value, { ...tempItem }]
  
  // Clear values and errors
  Object.keys(valuesNpwp).forEach(key => valuesNpwp[key] = null)
  formErrorsNpwp.value = {}
  valuesNpwp.is_active = 1
  

  swal.fire({
    icon: 'success',
    text: 'Data berhasil ditambahkan!'
  })
}



const cancelDetail = (item,index) => {
  const isEdit = item.is_edit
  item.is_edit = false
  detailArr.value[index] = { ...tempItemDet.value }
}


const tempItemDet = ref(null)
const editDetail = (item,index) => {
  const isEdit = item.is_edit
  item.is_edit = !isEdit
  tempItemDet.value = { ...item }
  detailArr.value[index].is_edit=!isEdit;
  if(item.is_active){
    item.is_active=1
  }else{
    item.is_active=2
  }
  if(item.is_primary){
    item.is_primary=1
  }else{
    item.is_primary=2
  }
}
const saveChange = (item,index) => {
  var invalid = false;
  if(item.num===null){
    invalid=true;
    swal.fire({
      icon: 'error',
      text: 'Field No. NPWP belum terisi'
    })
    return
  }

  if(item.name===null){
    invalid=true;
    swal.fire({
      icon: 'error',
      text: 'Field Nama NPWP belum terisi'
    })
    return
  }

  if(item.addr===null){
    invalid=true;
    swal.fire({
      icon: 'error',
      text: 'Field Alamat NPWP belum terisi'
    })
    return
  }

  if(item.note===null){
    invalid=true;
    swal.fire({
      icon: 'error',
      text: 'Field Catatan belum terisi'
    })
    return
  }

  if(item.is_active===null){
    invalid=true;
    swal.fire({
      icon: 'error',
      text: 'Field Status belum terisi'
    })
    return
  }else{
    item.is_active=(item.is_active===1?true:false)
  }

  if(invalid===false){    
    const isEdit = item.is_edit
    item.is_edit = !isEdit
    detailArr.value[index].is_edit=!isEdit;
  }

}

// const initDetailAddr = {}
const initArrAddr = {
  nama_lokasi: '',
    alamat: '',
    is_active: true,
    is_edit: true
}
const detailArrAddr = ref([])
const addDetailAddr = () => {
  // console.log(initArrAddr)
  const initArrAddr = { is_active: true, is_edit: false };
  detailArrAddr.value.push({...initArrAddr});
}

const delDetailAddr = async (index) =>{
  const result = await swal.fire({
        icon: 'warning',
        text: 'Hapus Data Terpilih?',
        confirmButtonText: 'Yes',
        showDenyButton: true,
    });

    if (!result.isConfirmed) return;

  detailArrAddr.value = detailArrAddr.value.filter((item, i) => (i !== index));
}

const toggleStatus = (index) => {
      // Pastikan index valid dan item pada index tersebut ada
      if (detailArrAddr.value && detailArrAddr.value[index]) {
        // Mengubah nilai is_active menjadi kebalikannya
        detailArrAddr.value[index].is_active = !detailArrAddr.value[index].is_active;
      } else {
        console.error('Invalid index or item not found.');
      }
    };

const initArrName = {
  nama_customer: ''
}
const detailArrName = ref([])

const addDetailName= () => {
  detailArrName.value.push({...initArrName});
  formErrorsName.value.push({email: false})
}

const delDetailName = async (index) =>{
  const result = await swal.fire({
        icon: 'warning',
        text: 'Hapus Data Terpilih?',
        confirmButtonText: 'Yes',
        showDenyButton: true,
    });

    if (!result.isConfirmed) return;

  detailArrName.value = detailArrName.value.filter((item, i) => (i !== index));
}

const cancelDetailAddr = (item,index) => {
  const isEdit = item.is_edit
  item.is_edit = false
  detailArrAddr.value[index] = { ...tempItemAddr.value }
}


const tempItemAddr = ref(null)
const editDetailAddr = (item,index) => {
  const isEdit = item.is_edit
  item.is_edit = !isEdit
  tempItemAddr.value = { ...item }
  detailArrAddr.value[index].is_edit=!isEdit;
  if(item.is_active){
    item.is_active=1
  }else{
    item.is_active=2
  }
  // if(item.is_primary){
  //   item.is_primary=1
  // }else{
  //   item.is_primary=2
  // }
}
const saveChangeAddr = (item,index) => {
  var invalid = false;
  if(item.type===null){
    invalid=true;
    swal.fire({
      icon: 'error',
      text: 'Field Type belum terisi'
    })
    return
  }

  if(item.nama_npwp===null){
    invalid=true;
    swal.fire({
      icon: 'error',
      text: 'Field Nama belum terisi'
    })
    return
  }

  if(item.alamat===null){
    invalid=true;
    swal.fire({
      icon: 'error',
      text: 'Field Alamat belum terisi'
    })
    return
  }

  if(item.catatan===null){
    invalid=true;
    swal.fire({
      icon: 'error',
      text: 'Field Catatan belum terisi'
    })
    return
  }

  if(item.is_active===null){
    invalid=true;
    swal.fire({
      icon: 'error',
      text: 'Field Status belum terisi'
    })
    return
  }else{
    item.is_active=(item.is_active===1?true:false)
  }

  if(invalid===false){    
    const isEdit = item.is_edit
    item.is_edit = !isEdit
    detailArrAddr.value[index].is_edit=!isEdit;
  }

}


const removeDetail = async (index) => {
  const result = await swal.fire({
        icon: 'warning',
        text: 'Hapus Data Terpilih?',
        confirmButtonText: 'Yes',
        showDenyButton: true,
    });

    if (!result.isConfirmed) return;
  
  detailArr.value.splice(index,1)
  // detailArr.value = detailArr.value.filter((e) => e.__id != index.__id)
}

const removeDetailName1 = async (index) => {
  const result = await swal.fire({
        icon: 'warning',
        text: 'Hapus Data Terpilih?',
        confirmButtonText: 'Yes',
        showDenyButton: true,
    });

    if (!result.isConfirmed) return;
  
  values.cp1 = null
  // detailArr.value = detailArr.value.filter((e) => e.__id != index.__id)
}

const removeDetailName2 = async (index) => {
  const result = await swal.fire({
        icon: 'warning',
        text: 'Hapus Data Terpilih?',
        confirmButtonText: 'Yes',
        showDenyButton: true,
    });

    if (!result.isConfirmed) return;
  
  values.cp2 = null
  // detailArr.value = detailArr.value.filter((e) => e.__id != index.__id)
}
// End Table Detail

const stuffing_data = ref([]);

onBeforeMount(async () => {
  // await fetch(`${store.server.url_backend}/operation/m_lokasistuffing`, {
  //       headers: {
  //         'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`
  //       },
  //     }).then((res)=> res.json()).then((json) => {
  //       stuffing_data.value = json;
  //     })

  onReset()
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = { transform: false }
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
      initialValues.tolerance = ""+initialValues.tolerance
      initialValues.taxable=initialValues.taxable?1:0
      initialValues.is_active=initialValues.is_active?1:0
      if(actionText.value?.toLowerCase() === 'copy' && initialValues.uid){
        delete initialValues.uid
      }
      
      if(actionText.value?.toLowerCase() === 'copy'){
        delete initialValues.kode
      }

      // Menambahkan Data Ke Array
      initialValues.m_customer_d_npwp?.forEach((items)=>{  
        if(actionText.value?.toLowerCase() === 'copy' && items.uid){
          delete items.uid
        }    
        items.is_active = items.is_active ? 1 : 0
        // items.is_primary = items.is_primary ? 1 : 0
        detailArr.value = [items, ...detailArr.value]
      })
      detailArrName.value = initialValues.m_customer_d_nama;
      detailArrName.value.map(()=>{
        formErrorsName.value.push({email: false});
      })
      detailArrAddr.value = initialValues.m_customer_d_address.map((dt)=> ({
        ...dt, is_active: dt['is_active'], alamat: dt['alamat'], lokasi_stuff: dt['lokasi_stuff']
      }))
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
  // values.m_lokasi_stuffing_id = initialValues['m_lokasi_stuffing.id']
})

function onBack() {
  if (route.query.view_gaji) {
    router.replace('/t_info_gaji')
  } else if(route.query.view_gaji_final){
    router.replace('/t_info_gaji')
  }else{
    router.replace('/' + modulPath)
  }
  return
}

const maps = ref();

watch(maps, (val)=>{
  const coords = val.slice(6, -1).split(' ');
  values.longtitude = coords[0];
  values.latitude = coords[1];
})


async function onSave() {

    try {
    
      values.top = parseInt(values.top);

      if (!values.id) {
        console.error("m_customer_id is required");
    } else {
        // Mapping over detailArrAddr to add m_customer_id
        values.m_customer_d_address = detailArrAddr.value.map((dt) => ({
            ...dt,
            m_customer_id: values.id,
            is_active: dt.is_active === true ? 1 : 0
        }));
        console.log(values.m_customer_d_address);

        // Mapping over detailArrName to add m_customer_id
        values.m_customer_d_nama = detailArrName.value.map((dt) => ({
            ...dt,
            m_customer_id: values.id
        }));

        // Mapping over detailArr to add m_customer_id
        values.m_customer_d_npwp = detailArr.value.map((dt) => ({
            ...dt,
            m_customer_id: values.id
        }));
    }
    
      
      values.tolerance = parseInt(values.tolerance)
      // Inti onSave
      const isCreating = ['Create','Copy','Tambah'].includes(actionText.value);
      const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`;
      isRequesting.value = true;
      values.is_active = values.is_active ? 1 : 0
      values.default = values.default ? 1 : 0
      const res = await fetch(dataURL, {
        method: isCreating ? 'POST' : 'PUT',
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
      router.replace('/' + modulPath + '?reload='+(Date.parse(new Date())));
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
      searchfield: 'this.id, this.kode, this.nama_perusahaan, this.alamat, this.no_tlp1, this.is_active'
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
    field: 'kode',
    headerName: 'Kode',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'nama_perusahaan',
    headerName: 'Nama Customer',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'alamat',
    headerName: 'Alamat',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    autoHeight: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'no_tlp1',
    headerName: 'No. Telp 1',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, 
    wrapText:true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'is_active',
    headerName: 'Status',
    filter: true,
    filter: 'ColFilter',
    // resizable: true,
    // valueGetter: (p) => p.node.data['status'].toLowerCase()==='active'? 'Aktif':'Tidak Aktif',
    sortable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ({ value }) => {
      return value === true
        ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Active</span>`
        : `<span class="text-red-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Inactive</span>`
    }
  },]
})

const filterButton = ref(null);

function filterShowData(params) {
    filterButton.value = filterButton.value === params ? null : params;
    landing.api.params.where = filterButton.value !== null ? `this.is_active=${filterButton.value}` : null;
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