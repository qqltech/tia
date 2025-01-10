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
const endpointApi = 'm_jasa'
onBeforeMount(() => {
    document.title = 'Master Jasa'
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
            searchfield: 'this.kode_jasa, this.kode, this.nama_jasa, this.catatan, this.satuan',
        },
        onsuccess(response) {
            response.data = response.data;
            return { ...response, page: response.current_page, hasNext: response.has_next };
        },
    },
    columns: [
        {
            headerName: 'No', valueGetter: ({ node }) => node.rowIndex + 1, width: 60, sortable: false,
            cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
        },
        {
            headerName: 'Kode', field: 'kode', cellClass: ['border-r', '!border-gray-200', 'justify-start'],
            sortable: true, filter: 'ColFilter', flex: 1
        },
        {
            headerName: 'Nama', field: 'nama_jasa', cellClass: ['border-r', '!border-gray-200', 'justify-start'],
            sortable: true, filter: 'ColFilter', flex: 1
        },
        // {
        //     headerName: 'Satuan', field: 'satuan', cellClass: ['border-r', '!border-gray-200', 'justify-start'],
        //     sortable: true, filter: 'ColFilter', flex: 1
        // },
        {
            headerName: 'Catatan', field: 'catatan', flex: 2, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
            sortable: true, filter: 'ColFilter'
        },
        {
            headerName: 'Status', flex: 1, field: 'is_active', cellClass: ['border-r', '!border-gray-200', 'justify-center'],
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
  data: { is_active: true }, 
}
const data = reactive({ ...default_value.data });

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
        icon: 'warning', text: 'Reset this form data?',
        showDenyButton: true
    }).then((res) => {
       if (res.isConfirmed) {
          for (const key in data){
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
    
    if(!data.kode_jasa){
      formErrors.value = { kode_jasa: ['Bidang ini wajib di isi'] };
      swal.fire({ icon: 'error', text: 'Maaf data belum valid, silahkan dikoreksi' || "Failed when trying to post data"});
      return;
    }

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
                ...data, is_active: (data.is_active) ? '1' : '0', 
                satuan: (data?.satuan) ? data.satuan.toString() : null
            }),
        });

        if (!res.ok) {
            const responseJson = await res.json();
            formErrors.value = responseJson.errors || {};
            swal.fire({ icon: 'error', text: responseJson.message || "Failed when trying to post data"});
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