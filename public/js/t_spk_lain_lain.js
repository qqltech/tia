import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const actionText = ref(route.params.id === 'create' ? 'Create' : route.query.action)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const formErrors = ref({})
const tsId = `ts=` + (Date.parse(new Date()))

// ENDPOINT API
const endpointApi = 't_spk_lain'
onBeforeMount(() => {
  document.title = 'Transaction SPK Lain-lain'
})

// @if( !$id ) | --- LANDING TABLE --- | 
// TABLE
const table = reactive({
  api: {
    url: `${store.server.url_backend}/operation/${endpointApi}`,
    headers: {
      'Content-Type': 'application/json',
      authorization: `${store.user.token_type} ${store.user.token}`,
    },
    params: {
      simplest: true,
      searchfield: 'this.kode, lokasi_stuffing.nama_lokasi, this.tarif_genzet, this.kilometer, this.catatan, this.status',
    },
    onsuccess(response) {
      return { ...response, page: response.current_page, hasNext: response.has_next };
    },
  },
  columns: [
    {
      headerName: 'No',
      valueGetter: ({ node }) => node.rowIndex + 1,
      width: 60,
      sortable: false,
      cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
    },
    {
      headerName: 'No. Draft',
      field: 'no_draft',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'No. SPK Lain-Lain',
      field: 'no_spk',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },

    {
      headerName: 'Tanggal SPKL',
      field: 'tanggal',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Genzet',
      field: 'genzet.nama',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'No. Order',
      field: 't_buku_order.no_buku_order',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Customer',
      field: 'm_customer.kode',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Catatan',
      field: 'catatan',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Status',
      field: 'status',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
      cellRenderer: (params) => {
        return params.data['status'] == 1
          ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
          : (params.data['status'] == 'DRAFT' ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'POST' ? `<span class="text-yellow-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : (params.data['status'] == 'IN PROCESS' ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                : (params.data['status'] == 'COMPLETED' ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                  : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`)))
          )
      }
    },
  ],
  actions: [
    {
      title: 'Hapus', icon: 'trash', class: 'bg-red-600 text-light-100',
      show: (row) => row.status === 'DRAFT',
      click: deleteData
    },
    {
      title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?${tsId}`)
    },
    {
      title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100',
      show: (row) => row.status === 'DRAFT',
      click: row => router.push(`${route.path}/${row.id}?action=Edit&${tsId}`)
    },
    {
      title: 'Copy', icon: 'copy', class: 'bg-gray-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Copy&${tsId}`)
    },
    {
      icon: 'location-arrow',
      title: "Post Data",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status === 'DRAFT',
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
              const dataURL = `${store.server.url_backend}/operation/t_spk_lain/post`
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
                  throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to post data")
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
});

// DELETE DATA
async function deleteData(row) {
  const result = await swal.fire({
    icon: 'warning',
    text: 'Hapus Data Terpilih?',
    confirmButtonText: 'Yes',
    showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  try {
    isRequesting.value = true;

    const res = await fetch(`${store.server.url_backend}/operation/${endpointApi}/${row.id}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });

    if (!res.ok) {
      const resultJson = await res.json();
      throw new Error(resultJson.message || 'Failed when trying to remove data');
    }

    apiTable.value.reload();
  } catch (err) {
    isBadForm.value = true;
    swal.fire({ icon: 'error', text: err.message });
  } finally {
    isRequesting.value = false;
  }
}

// FILTER
const filterButton = ref(null);
function filterShowData(params) {
  filterButton.value = filterButton.value === params ? null : params;
  table.api.params.where = filterButton.value !== null ? `this.status='${filterButton.value}'` : null;
  apiTable.value.reload();
}

onActivated(() => {
  if (apiTable.value && route.query.reload) {
    apiTable.value.reload();
  }
});


// @else | --- FORM DATA --- |

// HOT KEY (CTRL+S)
const handleKeyDown = (event) => {
  if (event?.ctrlKey && event?.key === 's' && actionText.value) {
    event.preventDefault();
    onSave();
  }
}

onMounted(() => { window.addEventListener('keydown', handleKeyDown) 
  const today = new Date();
  // Format tanggal sesuai dengan "dd-mm-yyyy"
  const day = String(today.getDate()).padStart(2, '0');
  const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
  const year = today.getFullYear();
  const formattedDate = `${day}/${month}/${year}`;
  data.keluar_lokasi_tanggal = formattedDate;
  data.tiba_lokasi_tanggal = formattedDate;
});
onBeforeUnmount(() => { window.removeEventListener('keydown', handleKeyDown) });

// FORM DATA
let default_value = {
  data: { status: 'DRAFT' },
}

const data = reactive({ ...default_value.data });

const detailArr = ref([]);

const addDetail = (e) => {
  e.forEach(row => {
    row.m_general_id = row.id || null;
    row.sektor = row.deskripsi
    row.catatan = null
    detailArr.value.push(row)
  })
}

const delDetailArr = (index) => {
  detailArr.splice(index, 1);
}

// GET DATA FROM API
onBeforeMount(async () => {
  if (!isRead) return;

  try {
    const editedId = route.params.id;
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${editedId}`;
    isRequesting.value = true;

    const headers = {
      'Content-Type': 'application/json',
      Authorization: `${store.user.token_type} ${store.user.token}`,
    };

    const fetchData = async (url, params = {}) => {
      const queryString = new URLSearchParams(params).toString();
      const response = await fetch(`${url}?${queryString}`, { headers });
      return response.json();
    };

    // FETCH HEADER DATA
    await fetchData(dataURL, { join: false, transform: false }).then((res) => {
      default_value.data = res.data;
      for (const key in res.data) {
        data[key] = res.data[key];
      }
      if (actionText.value == "Copy") {
        data.status = "DRAFT";
        default_value.status = "DRAFT"
      }
    });

    console.log(data.t_spk_lain_d)
    detailArr.value = data.t_spk_lain_d.map((dt) => ({
          ...dt, sektor: dt.deskripsi
    }))

  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error', text: err, allowOutsideClick: false, confirmButtonText: 'Kembali',
    }).then(() => { router.back() });
  } finally {
    isRequesting.value = false;
  }
});

// ACTION BUTTON
function onReset() {
  swal.fire({
    icon: 'warning', text: 'Reset semua data?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      for (const key in data) {
        data[key] = default_value.data[key];
      }
    }
  })
}

function onBack() {
  router.replace('/' + modulPath)
}

async function onSave() {
  const result = await swal.fire({
    icon: 'warning', text: 'Simpan data?', showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  try {
    const isCreating = ['Create', 'Copy'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : '/' + route.params.id}`;
    isRequesting.value = true;
    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify({
        ...data,
      }),
    });

    data.t_spk_lain_d = detailArr.value;

    if (!res.ok) {
      const responseJson = await res.json();
      formErrors.value = responseJson.errors || {};
      swal.fire({ icon: 'error', text: responseJson.message || "Failed when trying to post data" });
    } else {
      router.replace(`/${modulPath}?reload=${Date.now()}`);
    }

  } catch (err) {
    isBadForm.value = true;
    swal.fire({ icon: 'error', text: err });
  } finally {
    isRequesting.value = false;
  }
}

async function inProcess() {
  swal.fire({
    icon: 'warning',
    text: 'In Process?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',

    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const dataURL = `${store.server.url_backend}/operation/t_spk_lain/inProcess`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: 'POST',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify({ id: data.id })
        })
        if (!res.ok) {
          if ([400, 422, 500].includes(res.status)) {
            const responseJson = await res.json()
            formErrors.value = responseJson.errors || {}
            throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to In Process")
          } else {
            throw ("Failed when trying to in Process")
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
      isRequesting.value = false;
      router.replace('/' + modulPath);
    }
  })
}


async function complete() {
  swal.fire({
    icon: 'warning',
    text: 'Complete?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',

    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const dataURL = `${store.server.url_backend}/operation/t_spk_lain/complete`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: 'POST',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify({ id: data.id })
        })
        if (!res.ok) {
          if ([400, 422, 500].includes(res.status)) {
            const responseJson = await res.json()
            formErrors.value = responseJson.errors || {}
            throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to Complete")
          } else {
            throw ("Failed when trying to Complete")
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
      isRequesting.value = false;
      router.replace('/' + modulPath);
    }
  })
}
//  @endif | --- END --- |
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))