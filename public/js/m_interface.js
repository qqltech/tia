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
const tsId = `ts=` + (Date.parse(new Date()))
// ------------------------------ PERSIAPAN
const endpointApi = '/m_interface'
onBeforeMount(() => {
  document.title = 'Master interface'
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
  if (event?.ctrlKey && event?.key === 's') {
    event.preventDefault(); // Prevent the default behavior (e.g., saving the page)
    onSave();
  }
}

let initialValues = {}
const changedValues = []

let values = reactive({
})

//LOGIC Table Detail
const detailArr = ref([]);
const selectedItems = ref([]);
const addItem = () => {
  detailArr.value.push({
  });
};
const removeDetail = (index) => {
  detailArr.value.splice(index, 1);
  selectedItems.value = selectedItems.value.filter(i => i !== index);
};
const removeSelectedDetails = () => {
  selectedItems.value.sort((a, b) => b - a).forEach(index => {
    detailArr.value.splice(index, 1);
  });
  selectedItems.value = [];
};

// BEFOREMOUNT
onBeforeMount(async () => {
  if (isRead) {
    // READ DATA
    try {
      const editedId = route.params.id;
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`;
      isRequesting.value = true;

      const params = { transform: false, detail: true };
      const fixedParams = new URLSearchParams(params);
      const res = await fetch(dataURL + '?' + fixedParams, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`,
        },
      });

      if (!res.ok) throw new Error("Failed when trying to read data");
      const resultJson = await res.json();
      initialValues = resultJson.data;

      detailArr.value = initialValues['m_interface_d'] || [];

    } catch (err) {
      isBadForm.value = true;
      swal.fire({
        icon: 'error',
        text: err.message || "An error occurred.",
        allowOutsideClick: false,
        confirmButtonText: 'Kembali',
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

async function onSave() {
  try {
    const result = await swal.fire({
      title: 'Konfirmasi Simpan',
      text: 'Apakah data yang Anda simpan sudah benar?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Oke',
      cancelButtonText: 'Belum'
    });
    if (result.isConfirmed) {
      // Logic Detail save
      values.m_interface_d = detailArr.value;

      const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
      const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`;
      isRequesting.value = true;
      values.is_active = values.is_active ? 1 : 0
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
      router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())));
    }
  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'warning',
      text: err
    });
  } finally {
    isRequesting.value = false;
  }
}
function onBack() {
  router.replace('/' + modulPath)
}
const onReset = async (alert = false) => {
  if (alert) {
    swal.fire({
      icon: 'warning',
      text: 'Anda yakin akan mereset data ini?',
      showDenyButton: true
    }).then((res) => {
      if (res.isConfirmed) {
        if (isRead) {
          for (const key in initialValues) {
            values[key] = initialValues[key];
          }
        } else {
          for (const key in values) {
            delete values[key];
          }
        }
      }
    });
  } else {
    if (isRead) {
      for (const key in initialValues) {
        values[key] = initialValues[key];
      }
    } else {
      for (const key in values) {
        delete values[key];
      }
    }
  }
};


//  @else----------------------- LANDING
const activeBtn = ref()

function filterShowData(params, noBtn) {
  if (activeBtn.value === noBtn) {
    activeBtn.value = null
  } else {
    activeBtn.value = noBtn
  }
  if (params) {
    landing.api.params.where = `this.is_active=true`
  } else if (activeBtn.value == null) {
    // clear params filter
    landing.api.params.where = null
  } else {
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
      // show: (row) => (currentMenu?.can_read)||store.user.data.username==='developer',
      click(row) {
        router.push(`${route.path}/${row.id}?` + tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      // show: (row) => (currentMenu?.can_update)||store.user.data.username==='developer',
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
      searchfield: 'divisi.deskripsi , tipe.deskripsi , variable.deskripsi , grp.deskripsi',
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
    headerName: 'DIVISI',
    field: 'divisi.deskripsi',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'TIPE',
    field: 'tipe.deskripsi',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },

  {
    headerName: 'VARIABLE',
    field: 'variable.deskripsi',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
   {
    headerName: 'GROUP',
    field: 'grp.deskripsi',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },

  {
    headerName: 'Status', flex: 1, field: 'is_active', cellClass: ['border-r', '!border-gray-200', 'justify-center'],
    sortable: true, cellRenderer: ({ value }) =>
      value === true
        ? '<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Active</span>'
        : '<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">InActive</span>'
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