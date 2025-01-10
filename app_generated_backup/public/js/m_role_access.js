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
const endpointApi = 'm_role_access'
onBeforeMount(() => {
    document.title = 'Master Role Akses'
})

// @if( !$id ) | --- LANDING TABLE --- |

// TABLE
const table = reactive({
    api: {
        url: `${store.server.url_backend}/operation/default_users`,
        headers: {
            'Content-Type': 'application/json',
            authorization: `${store.user.token_type} ${store.user.token}`,
        },
        params: {
            simplest: true,
            searchfield: 'this.nama, this.username, this.email',
        },
        onsuccess(response) {
            return { ...response, page: response.current_page, hasNext: response.has_next };
        },
    },
    columns: [
        {
            headerName: 'No', valueGetter: ({ node }) => node.rowIndex + 1, width: 60, sortable: false,
            cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
        },
        {
            headerName: 'User', field: 'name', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start',],
            sortable: true, filter: 'ColFilter',
        },
        {
            headerName: 'Username', field: 'username', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start',],
            sortable: true, filter: 'ColFilter',
        },
        {
            headerName: 'Email', field: 'email', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start',],
            sortable: true, filter: 'ColFilter',
        },
        {
            headerName: 'Status', field: 'is_active', cellClass: ['border-r', '!border-gray-200', 'justify-center'],
            sortable: true, cellRenderer: ({ value }) =>
                value === true
                    ? '<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Active</span>'
                    : '<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">InActive</span>'
        }
    ],
    actions: [
        // { title: 'Hapus', icon: 'trash', class: 'bg-red-600 text-light-100', click: deleteData },
        {
            title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
            click: row => router.push(`${route.path}/${row.id}?${tsId}`)
        },
        {
            title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100',
            click: row => router.push(`${route.path}/${row.id}?action=Edit&${tsId}`)
        },
        // {
        //     title: 'Copy', icon: 'copy', class: 'bg-gray-600 text-light-100',
        //     click: row => router.push(`${route.path}/${row.id}?action=Copy&${tsId}`)
        // },
    ],
});

// FILTER
const filterButton = ref(null);
function filterShowData(params) {
    filterButton.value = filterButton.value === params ? null : params;
    table.api.params.where = filterButton.value !== null ? `this.is_active=${filterButton.value}` : null;
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

onMounted(() => { window.addEventListener('keydown', handleKeyDown) });
onBeforeUnmount(() => { window.removeEventListener('keydown', handleKeyDown) });

// FORM DATA
let default_value = {
  data: { is_active: true, is_superadmin: false }, 
  detail: []
}

const data = reactive({ ...default_value.data });
const detail = reactive({ data: [...default_value.detail] });

// GET DATA FROM API
onBeforeMount(async () => {
    if (!isRead) return;

    try {
        const editedId = route.params.id;
        const dataURL = `${store.server.url_backend}/operation/default_users/${editedId}`;
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
        });

        // FETCH DETAIL DATA 
        await fetchData(`${store.server.url_backend}/operation/m_role_access`, {
            scopes: "GetRoleAccess", user_id: editedId, order_by: "created_at", order_type: "ASC"
        }).then((res) => {
          default_value.detail = res.data.map(item => (
             {...item, 
              m_role_id: item.relation_role.id,
              role: item.relation_role.name,
              superadmin: item.relation_role.is_superadmin ? 'Yes' : 'No',
              is_superadmin: item.relation_role.is_superadmin
             }
          ));
          detail.data = default_value.detail.map(item => ({ ...item }));
        });

    } catch (err) {
        isBadForm.value = true;
        swal.fire({
            icon: 'error', text: err, allowOutsideClick: false, confirmButtonText: 'Kembali',
        }).then(() => { router.back() });
    } finally {
        isRequesting.value = false;
    }
});


// ADD & DELETE DETAIL
const addDetail = (rows) => {
  const superadmin = rows.filter((dt) => (dt.is_superadmin));

  if(superadmin.length >= 1){
    swal.fire({
    icon: 'warning', text: 'Menambahkan Role Superadmin, role yang lain akan dihapus oleh sistem', showDenyButton: true,

  }).then((res) => {
    if (res.isConfirmed) {
      detail.data = [...superadmin];
    }
  })
  } else {
    const superadmin_detail = detail.data.filter((dt) => dt.is_superadmin);
    if(superadmin_detail.length >= 1){
      swal.fire({
      icon: 'warning', text: 'Role Superadmin sudah ada. Hapus Superadmin dan tambahkan role baru?', showDenyButton: true,
      }).then((res) => {
        if (res.isConfirmed) {
          detail.data = [...detail.data, ...rows].filter((dt) => (!dt.is_superadmin));
        }
  })
    } else {
      detail.data = [...detail.data, ...rows].filter((dt) => (!dt.is_superadmin));
    }
  }
}

const deleteDetail = (rows) => {
    detail.data = detail.data.filter((det) => (det['m_role_id'] !== rows['m_role_id']));
}

const deleteDetailAll = () => {
  swal.fire({
    icon: 'warning', text: 'Hapus semua detail data?', showDenyButton: true,
  }).then((res) => {
    if (res.isConfirmed) {
      detail.data = [];
    }
  })
}

// ACTION BUTTON
function onReset() {
    swal.fire({
        icon: 'warning', text: 'Reset semua data?',
        showDenyButton: true
    }).then((res) => {
        if (res.isConfirmed) {
            detail.data = default_value.detail.map(item => ({ ...item }));
        }
    })
}

function onBack() {
    router.replace('/' + modulPath)
}

async function onSave() {
    data.detail = detail.data;

    const result = await swal.fire({
        icon: 'warning', text: 'Simpan data?', showDenyButton: true,
    });

    if (!result.isConfirmed) return;


    try {
        const isCreating = ['Create', 'Copy'].includes(actionText.value);
        const dataURL = `${store.server.url_backend}/operation/${endpointApi}/saveRole`;
        isRequesting.value = true;

        const res = await fetch(dataURL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Authorization: `${store.user.token_type} ${store.user.token}`,
            },
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

//  @endif | --- END --- |
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))