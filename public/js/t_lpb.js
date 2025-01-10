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
const tableKey = ref(0)
// ------------------------------ PERSIAPAN
const endpointApi = 't_lpb'
onBeforeMount(()=>{
  document.title = 'Transaksi LPB'
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
  // console.log(event)
  if (event?.ctrlKey && event?.key === 's') {
    event.preventDefault(); // Prevent the default behavior (e.g., saving the page)
    onSave();
  }
}

let initialValues = {}
const changedValues = []

let values = reactive({})

const detailArr = ref([])

async function addAllDetail(data) {

  if (values.t_po_id == undefined || values.t_po_id == null) {
      swal.fire({
          title: 'Peringatan',
          icon: 'warning',
          text: 'Silahkan pilih nomor PO terlebih dahulu!'
      });
      return;
  }

  detailArr.value = [];
  try {
    // Membangun parameter query string
    const params = {
      simplest: false,
      join: true,
      useBundling: true,
      where: values.t_po_id ? `this.t_purchase_order_id=${values.t_po_id}` : null,
    };
    
    // Konversi params menjadi query string dan tambahkan ke URL
    const queryString = new URLSearchParams(
      Object.fromEntries(Object.entries(params).filter(([_, v]) => v != null))
    ).toString();
    
    const dataURL = `${store.server.url_backend}/operation/t_purchase_order_d?${queryString}`;

    const res = await fetch(dataURL, {
      method: 'GET',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      }
    });

    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json();
        formErrors.value = responseJson.errors || {};
        throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to fetch data");
      } else {
        throw ("Failed when trying to fetch data");
      }
    }

    const result = await res.json();
    const resultData = result.data;
    if (!resultData?.length) {
      swal.fire({
        icon: 'warning',
        text: "Tidak ditemukan data detail untuk nomor PO tersebut, silahkan pilih nomor PO lainnya"
      });
    }

    console.log(resultData)

    resultData.forEach((item) => {
      item['kode'] = item['m_item.kode']
      item['nama'] = item['m_item.nama_item']
      item['harga'] = item['harga']
      item['t_po_d_id'] = item['clone_id']
      item['bundling'] = item['is_bundling'] == true ? 'Ya' : 'Tidak'
      detailArr.value.push(item);
    });

  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error',
      text: err
    });
  }
}



const removeDetail = (index) => {
  detailArr.value.splice(index,1)
}

// DEFAULT VALUE BEFORE MOUNT --UBAH DISINI
const defaultValues = ()=>{
  values.status = 'DRAFT'
  values.tanggal_lpb = new Date().toLocaleDateString('en-GB')
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
        detailArr.value = []
        const newValues = {
          t_po_id: '',
          tanggal_lpb: '',
          no_sj_supplier: '',
          tanggal_sj_supplier: '',
          m_supplier_id: '',
          catatan: '',
        };
        
        for (const key in newValues) {
          if (newValues.hasOwnProperty(key)) {
            values[key] = newValues[key];
          }
        }
      }
    })
  }
  
  setTimeout(()=>{
    defaultValues() 
  }, 100)
}

onBeforeMount(async () => {
  onReset();
  if (isRead) {
    try {
      const editedId = route.params.id;
      const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${editedId}`;
      isRequesting.value = true;

      const params = {transform: false};
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

      if (actionText.value?.toLowerCase() === 'copy' && initialValues.uid) {
        delete initialValues.uid;
      }
      
      // Menambahkan Data Ke Array
      initialValues.t_lpb_d?.forEach((item) => {
        if (actionText.value?.toLowerCase() === 'copy') {
          delete item.uid;
          initialValues.status = 'DRAFT'
          initialValues.no_lpb = null
        }
        
        item['quantity'] = item['t_po_d.quantity']
        item['kode'] = item['m_item.kode']
        item['nama'] = item['m_item.nama_item']
        item['is_bundling'] = item['t_po_d.is_bundling']
        item['bundling'] = item['t_po_d.is_bundling'] == true ? 'Ya' : 'Tidak'
        detailArr.value = [item, ...detailArr.value];
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
  } else if(route.query.view_gaji_final){
    router.replace('/t_info_gaji')
  }else{
    router.replace('/' + modulPath)
  }
  return
}


async function onSave() {
    const details = [...detailArr.value];

    // Mengecek apakah ada detail yang belum diisi
    if (details.length === 0) {
        swal.fire({
            title: 'Peringatan',
            icon: 'warning',
            text: 'Detail belum diisi, silahkan isi terlebih dahulu!'
        });
        return;
    }

    try {
        const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
        const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`;
        isRequesting.value = true;

        values.pph = values.pph ? 1 : 0
        
        const detailsWithSeq = details.map((detail, index) => ({ ...detail, seq: index + 1 }));

        values.t_lpb_d = detailsWithSeq.map(detail => ({ ...detail}));

        const res = await fetch(dataURL, {
            method: isCreating ? 'POST' : 'PUT',
            headers: {
                'Content-Type': 'Application/json',
                Authorization: `${store.user.token_type} ${store.user.token}`
            },
            body: JSON.stringify(Object.assign({}, values, {
                t_lpb_d: values.t_lpb_d
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

const activeBtn = ref()

function filterShowData(params) {
  if (activeBtn.value === params) {
    activeBtn.value = null
  } else {
    activeBtn.value = params
  }
  if (params) {
    landing.api.params.where = `this.status='${params}'`
  }
  if (activeBtn.value == null) {
    // clear params filter
    landing.api.params.where = null
  }

  apiTable.value.reload()
}

const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) => row.status==='DRAFT',
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
      show: (row) => row.status == 'DRAFT',
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
      icon: 'location-arrow',
      title: "Post Data",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status==='DRAFT',
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
              const dataURL = `${store.server.url_backend}/operation/t_lpb/post`
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
                  throw (responseJson.message+ " "+responseJson.data.errorText || "Failed when trying to post data")
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
  api: {
    url: `${store.server.url_backend}/operation/${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield: 'this.no_lpb, this.tanggal_lpb, m_supplier.nama, t_po.no_po, this.no_sj_supplier, this.tanggal_sj_supplier, this.catatan, this.status'
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
    headerName:'No. LPB',
    field: 'no_lpb',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName:'Tgl LPB',
    field: 'tanggal_lpb',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center']
  },
  {
    headerName:'No. PO',
    field: 't_po.no_po',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName:'Supplier',
    field: 'm_supplier.nama',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName:'No. SJ Supplier',
    field: 'no_sj_supplier',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName:'Tgl SJ Supplier',
    field: 'tanggal_sj_supplier',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center']
  },
  {
    headerName:'Catatan',
    field: 'catatan',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    wrapText:true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
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

// const filterButton = ref(null);
// function filterShowData(status) {
//   filterButton.value = filterButton.value === status ? null : status;
//   landing.api.params.where = filterButton.value !== null ? `this.status='${filterButton.value}'` : null;
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

//  @endif -------------------------------------------------END
watchEffect(()=>store.commit('set', ['isRequesting', isRequesting.value]))