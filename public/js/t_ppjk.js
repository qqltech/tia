import { useRouter, useRoute, RouterLink } from 'vue-router'
import { watch, ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated } from 'vue'

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
const endpointApi = 't_ppjk'
onBeforeMount(() => {
  document.title = 'Transaction PPJK'
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
      searchfield: `this.id, this.no_draft, no_ppjk.no_aju, t_buku_order.no_buku_order, m_customer.nama_perusahaan, 
                         this.no_npwp, this.no_peb_pib, this.no_sppb, this.status `,
    },
    onsuccess(response) {
      return { ...response, page: response.current_page, hasNext: response.has_next };
    },
  },
  columns: [
    {
      headerName: 'No', valueGetter: ({ node }) => node.rowIndex + 1, width: 60,
      cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
    },
    {
      headerName: 'No Draft', field: 'no_draft', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
      sortable: true, filter: 'ColFilter'
    },
    {
      headerName: 'No PPJK', field: 'no_ppjk.no_aju', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
      sortable: true, filter: 'ColFilter'
    },
    {
      headerName: 'No Buku Order', field: 't_buku_order.no_buku_order', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-center'],
      sortable: true, filter: 'ColFilter'
    },
    {
      headerName: 'Customer', field: 'm_customer.nama_perusahaan', flex: 1.5, cellClass: ['border-r', '!border-gray-200', 'justify-center'],
      sortable: true, filter: 'ColFilter'
    },
    {
      headerName: 'No NPWP', field: 'no_npwp', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-center'],
      sortable: true, filter: 'ColFilter'
    },
    {
      headerName: 'No PEB / PIB', field: 'no_peb_pib', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-center'],
      sortable: true, filter: 'ColFilter'
    },
    {
      headerName: 'No SPPB', field: 'no_sppb', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-center'],
      sortable: true, filter: 'ColFilter'
    },
    {
      headerName: 'Status', field: 'status', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
      sortable: true, filter: 'ColFilter', cellRenderer: ({ value }) => {
        return value === 'DRAFT'
          ? `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">DRAFT</span>`
          : `<span class="text-yellow-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">POST</span>`
      }
    },
  ],
  actions: [
    { title: 'Hapus', icon: 'trash', class: 'bg-red-600 text-light-100', click: deleteData, show: value => value.status === 'DRAFT' },
    {
      title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?${tsId}`)
    },
    {
      title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100', show: value => value.status === 'DRAFT',
      click: row => router.push(`${route.path}/${row.id}?action=Edit&${tsId}`)
    },
    {
      title: 'Copy', icon: 'copy', class: 'bg-gray-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Copy&${tsId}`)
    },
    { title: 'Post', show: (row) => row.status?.toUpperCase() === 'DRAFT', icon: 'paper-plane', class: 'bg-yellow-600 text-light-100', click: onPost },
  ],
});

// POST DATA

async function onPost(row) {
  const result = await swal.fire({
    icon: 'warning',
    text: 'Post Data Terpilih?',
    confirmButtonText: 'Yes',
    showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  try {
    isRequesting.value = true;

    const res = await fetch(`${store.server.url_backend}/operation/${endpointApi}/post?id=${row.id}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });

    if (!res.ok) {
      const resultJson = await res.json();
      throw new Error(resultJson.message || 'Failed when trying to post data');
    }
    apiTable.value.reload();
  } catch (err) {
    isBadForm.value = true;
    swal.fire({ icon: 'error', text: err.message });
  } finally {
    isRequesting.value = false;
  }
}

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

onMounted(() => {
  // window.addEventListener('keydown', handleKeyDown);

  const today = new Date();
  // Format tanggal sesuai dengan "dd-mm-yyyy"
  const day = String(today.getDate()).padStart(2, '0');
  const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
  const year = today.getFullYear();
  const formattedDate = `${day}/${month}/${year}`;
  data.tanggal = formattedDate;
})
onBeforeUnmount(() => { window.removeEventListener('keydown', handleKeyDown) });

// FORM DATA
let default_value = {
  data: { id: null, status: 'DRAFT' },
  currency: 'IDR',
  nilai_kurs: 1
}

const data = reactive({ ...default_value.data });

// FETCH ACTION
const headers = {
  'Content-Type': 'application/json',
  Authorization: `${store.user.token_type} ${store.user.token}`,
};

const fetchData = async (url, params = {}) => {
  const queryString = new URLSearchParams(params).toString();
  const response = await fetch(`${url}?${queryString}`, { headers });
  return response.json();
};

// GET DATA FROM API
onBeforeMount(async () => {
  if (actionText.value === 'create' || data.status === 'DRAFT') {
  }

  if (!isRead) return;

  try {
    isRequesting.value = true;

    // FETCH HEADER DATA
    const editedId = route.params.id;
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${editedId}`;
    await fetchData(dataURL, { join: false, transform: false, }).then(res => {
      default_value.data = res.data;
      console.log(res.data,'timothy')
      
      for (const key in res.data) {
        data[key] = res.data[key];
      }
      // data.t_buku_order_id = res.data.
    });

  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error', text: err, allowOutsideClick: false, confirmButtonText: 'Kembali',
    }).then(() => { router.back() });
  } finally {
    isRequesting.value = false;

    if (actionText.value === 'Copy') {
      data.id = null;
      data.status = 'DRAFT';
    }

    if (data.status !== 'DRAFT') {
      actionText.value = false
    }
  }
});

// GET NPWP CUSTOMER
async function getNpwpCustomer(id) {
  const dataURL = `${store.server.url_backend}/operation/m_customer_d_npwp`;
  await fetchData(dataURL, {
    where: `this.m_customer_id=${id} AND this.is_active=${true}`
  }).then(res => {
    if (res.data.length !== 0) {
      data.no_npwp = res.data[0].no_npwp;
    }
  })
}

// ACTION BUTTON
function onReset() {
  swal.fire({
    icon: 'warning', text: 'Reset this form data?',
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

async function onSave(isPost = false) {
  const result = await swal.fire({
    icon: 'warning', text: `${isPost ? 'Post ' : 'Simpan'} data?`, showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  try {
    const isCreating = ['Create', 'Copy'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? isPost ? '?post=true' : '' : isPost ? '/' + route.params.id + '?post=true' : '/' + route.params.id}`;
    isRequesting.value = true;

    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: headers,
      body: JSON.stringify(data),
    });

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

// const getCurrentDateFormatted = () => {
//   const date = new Date();
//   const day = String(date.getDate()).padStart(2, '0');
//   const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
//   const year = date.getFullYear();
//   return `${day}/${month}/${year}`;
// };

//  @endif | --- END --- |
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))