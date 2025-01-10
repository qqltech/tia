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
const previewSrc = ref(null) 

// const currency = ref(null)
// const kurs = ref(null)

// ------------------------------ PERSIAPAN
const endpointApi = '/t_nota_rampung'
onBeforeMount(()=>{
  document.title = 'Nota Rampung'
})

//  @if( $id )------------------- JS CONTENT ! PENTING JANGAN DIHAPUS
const values = reactive({
  grand_total : 0 ,
  status : "DRAFT" ,
  tanggal : new Date().toLocaleDateString('id-ID', { month: '2-digit', day: '2-digit', year: 'numeric' })
})


let tempfoto = ''
const previewImage = (filePath) => {
  previewSrc.value = filePath;
};

const closePreview = () => {
  previewSrc.value = null;
};

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




// detail 
let initialValues = {}
const changedValues = []
const detailArr = ref([]);
const addDetail = () => {
  const tempItem = {
   
  };
  detailArr.value = [...detailArr.value, tempItem];
};

const changePrimaryDetail = (index) => {
  detailArr.value?.forEach((item,i)=>{

  })
}
const onDetailAdd = (el) => {
  el.forEach(row => {
    console.log('check',row)
    row.no_kontainer = `${row.no_prefix}-${row.no_suffix}`;
    row.spek_kont = `${row['ukuran.deskripsi']}-${row['jenis.deskripsi']}`
    row.t_buku_order_d_npwp_id = row.id
    detailArr.value.push(row);
  });
};
const removeDetail = (index) => {
  detailArr.value.splice(index, 1)
  // detailArr.value = detailArr.value.filter((e,i) => i !== index)
  // console.log(detailArr.value)
}


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
      // console.log(initialValues, "<<<<<")
      if(actionText.value?.toLowerCase() === 'copy'){
        delete initialValues.status,
        delete initialValues.tanggal;
      }
      if(actionText.value?.toLowerCase() === 'edit'){
        delete initialValues.tanggal;
      }
      initialValues.t_nota_rampung_d?.forEach((item) => {
        detailArr.value = [item, ...detailArr.value];
      });
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



const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount);
}

function onBack() {
    router.replace('/' + modulPath)
}



async function hitung() {
        try {
        values.t_nota_rampung_d = detailArr.value;
        const dataURL = `${store.server.url_backend}/operation${endpointApi}/calculate`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method:'POST' ,
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify(values)
        })
        if (!res.ok) throw new Error('Failed to generate total.');
    const hasil = await res.json();
    console.log(hasil)
    values.grand_total = hasil.grand_total || 0;
    // values.grand_total_amount = hasil.total_setelah_ppn || 0;
    // values.total_kontainer = hasil.total_kontainer || 0;
    //   values.total_lain = hasil.total_lain  || 0 ;
    //     values.total_ppn = hasil.total_ppn || 0 ;

          swal.fire({
            icon: 'success',
            text: 'Total Berhasil Di Generated',
            confirmButtonText: 'OK',
          });

      } catch (err) {
        isBadForm.value = true
        swal.fire({
          icon: 'error',
          text: err
        })
      }
      isRequesting.value = false
}
  

async function onSave() {
  try {
        values.t_nota_rampung_d = detailArr.value;
        console.log('cek detail',values.t_nota_rampung_d)
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


async function onSaveAndPost() {
  
      try {
        values.t_nota_rampung_d = detailArr.value;
        const isCreating = ['Create','Copy','Tambah'].includes(actionText.value)
        if (!isCreating) {
          values.tanggal = new Date().toISOString().split('T')[0]
        }
        const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`
        isRequesting.value = true

        const saveRes = await fetch(dataURL, {
          method: isCreating ? 'POST' : 'PUT',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify(values)
        })

        if (!saveRes.ok) {
        if ([400, 422].includes(saveRes.status)) {
          const responseJson = await saveRes.json();
          formErrors.value = responseJson.errors || {};
          throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Oops, sesuatu yang salah terjadi. Coba kembali nanti.");
        } else {
          throw ("Oops, sesuatu yang salah terjadi. Coba kembali nanti.");
        }
      }

      // Cek SAVED DATA nya
      const savedData = await saveRes.json();
      // Ambil ID saved nya untuk di POST
      const savedId = savedData.id; 
      

        const postURL = `${store.server.url_backend}/operation/t_nota_rampung/post`;
        const postRes = await fetch(postURL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          // Kirim ID nya yang ingin di post
          body: JSON.stringify({ id: savedId })
        });

        if (!postRes.ok) {
          throw ("Oops, something went wrong while posting the data.");
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
    // {
    //   icon: 'print',
    //   title: "Cetak",
    //   class: 'bg-gray-600 text-light-100',
    //   click(row) {
    //     window.open(`${store.server.url_backend}/web/surat_jalan?export=pdf&size=a4&orientation=potrait&group=SATUAN%20JASA&id=${row.id}`)
    //   }
    // },
    // {
    //   icon: 'table',
    //   title: "Unduh Excel",
    //   class: 'bg-gray-600 text-light-100',
    //   click(row) {
    //     window.open(`${store.server.url_backend}/web/surat_jalan?export=excel&size=a4&orientation=potrait&group=SATUAN%20JASA&id=${row.id}`)
    //   }
    // },
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