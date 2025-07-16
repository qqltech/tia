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
const endpointApi = 'm_role'
onBeforeMount(() => {
    document.title = 'Master Role'
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
            searchfield: 'this.nama',
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
            headerName: 'Nama', field: 'name', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start',],
            sortable: true, filter: 'ColFilter',
        },
        {
            headerName: 'Super Admin', field: 'is_superadmin', cellClass: ['border-r', '!border-gray-200', 'justify-center'],
            sortable: true, cellRenderer: ({ value }) =>
                value === true
                    ? '<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Yes</span>'
                    : '<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">No</span>'
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
        { title: 'Hapus', icon: 'trash', class: 'bg-red-600 text-light-100', click: deleteData },
        {
            title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
            click: row => router.push(`${route.path}/${row.id}?${tsId}`)
        },
        {
            title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100',
            click: row => router.push(`${route.path}/${row.id}?action=Edit&${tsId}`)
        },
        {
            title: 'Copy', icon: 'copy', class: 'bg-gray-600 text-light-100',
            click: row => router.push(`${route.path}/${row.id}?action=Copy&${tsId}`)
        },
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
        });

        // FETCH DETAIL DATA 
        await fetchData(`${store.server.url_backend}/operation/m_role_d`, {
            where: `this.m_role_id=${editedId}`, order_by: "created_at", order_type: "ASC"
        }).then((res) => {
          default_value.detail = res.data.map(item => (
             {...item, modul: item['m_menu.modul'], submodul: item['m_menu.submodul'], menu: item['m_menu.menu'] }
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
    detail.data = [...detail.data, ...rows];
}

const deleteDetail = (rows) => {
    detail.data = detail.data.filter((det) => (det['m_menu_id'] !== rows['m_menu_id']));
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
            for (const key in data){
              data[key] = default_value.data[key];
            }
            detail.data = default_value.detail.map(item => ({ ...item }));
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
                m_role_d: detail.data.map((dt)=> ({
                  ...dt,
                  can_read: dt.can_read ? '1' : '0',
                  can_create: dt.can_create ? '1' : '0',
                  can_update: dt.can_update ? '1' : '0',
                  can_delete: dt.can_delete ? '1' : '0',
                  can_verify: dt.can_verify ? '1' : '0'
                })),
                is_active: (data.is_active) ? '1' : '0',
                is_superadmin: (data.is_superadmin) ? '1' : '0',
            }),
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