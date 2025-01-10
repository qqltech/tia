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
const tableKey = ref(0)
const tsId = `ts=`+(Date.parse(new Date()))

// ------------------------------ PERSIAPAN
const endpointApi = 't_memo_jurnal'
onBeforeMount(()=>{
  document.title = 'Memo Journal',
  values.status = 'DRAFT',
  values.tanggal_memo = new Date().toLocaleDateString('en-GB')
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
  console.log(event)
  if (event?.ctrlKey && event?.key === 's') {
    event.preventDefault(); // Prevent the default behavior (e.g., saving the page)
    onSave();
  }
}
let initialValues = {}
const changedValues = []

let values = reactive({})


// DETAIL

const detailArr = ref([])

const removeDetail = (index) => {
  detailArr.value.splice(index,1)
 // detailArr.value = detailArr.value.filter((e, idx) => idx != index)
}

function onDetailAdd(rows) {
  // const data = [...detailArr.value]
  detailArr.value = detailArr.value.concat(rows)
  tableKey.value++
}

onBeforeMount(async () => {
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
      initialValues.t_memo_jurnal_d?.forEach((item) => {
        if (actionText.value?.toLowerCase() === 'copy') {
          delete item.uid;
          initialValues.status = 'DRAFT'
          initialValues.no_draft = null
        }
        
        item['nama_coa'] = item['m_coa.nama_coa']
        detailArr.value = [item, ...detailArr.value]
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

function onReset() {
  swal.fire({
    icon: 'warning',
    text: 'Anda yakin akan mereset data ini?',
    showDenyButton: true
  }).then((res) => {
  
    if (res.isConfirmed) {
      const newValues = {
        divisi: '',
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

function onBack() {
    router.replace('/' + modulPath)
}

async function onSave() {
    const details = [...detailArr.value];
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

        values.t_memo_jurnal_d = detailsWithSeq.map(detail => ({ ...detail}));

        const res = await fetch(dataURL, {
            method: isCreating ? 'POST' : 'PUT',
            headers: {
                'Content-Type': 'Application/json',
                Authorization: `${store.user.token_type} ${store.user.token}`
            },
            body: JSON.stringify(Object.assign({}, values, {
                t_memo_jurnal_d: values.t_memo_jurnal_d
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
      show: (row) => row.status==='DRAFT',
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
              const dataURL = `${store.server.url_backend}/operation/t_memo_jurnal/post`
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
      searchfield:'this.catatan, this.tanggal_memo, this.no_memo, divisi.deskripsi',
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
    headerName: 'No. Memo',
    field: 'no_memo',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
    headerName: 'Tgl. Memo',
    field: 'tanggal_memo',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
    headerName: 'Divisi',
    field: 'divisi.deskripsi',
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
    // field: 'status',
    headerName: 'Status',
    filter: true,
    sortable: false,
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
  },
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
watchEffect(()=>store.commit('set', ['isRequesting', isRequesting.value]))