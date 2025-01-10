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
const endpointApi = 'm_customer_d_address'
onBeforeMount(() => {
    document.title = 'Master Lokasi Stuffing'
})

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
            searchfield: 'm_customer.nama_perusahaan, this.lokasi_stuff, this.alamat',
        },
        onsuccess(response) {
          console.log(response);
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
            headerName: 'Customer', field: 'm_customer.nama_perusahaan', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
            sortable: true, filter: 'ColFilter'
        },
        {
            headerName: 'Lokasi Stuffing', field: 'lokasi_stuff', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
            sortable: true, filter: 'ColFilter'
        },
        {
            headerName: 'Alamat', field: 'alamat', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
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
        {
            title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
            click: row => window.open(`m_customer/${row.m_customer_id}?${tsId}`, '_blank')
        },
        {
            title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100',
            click: row => window.open(`m_customer/${row.m_customer_id}?action=Edit&${tsId}`, '_blank')
        },
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

watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))