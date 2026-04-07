import { useRouter, useRoute, RouterLink } from 'vue-router'
import { computed, ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, watch, onActivated } from 'vue'

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
let modalOpenHistory = ref(false)
let dataHistoryDataItem = reactive({ items: [] })
const formErrors = ref({})
const formErrorsNpwp = ref({})
const formErrorsAddr = ref({})
const formErrorsName = ref([])
const tsId = `ts=` + (Date.parse(new Date()))
// ------------------------------ PERSIAPAN
const endpointApi = 'v_stock_item'
onBeforeMount(() => {
  document.title = 'Laporan Stock Item'
})

const warehouse_id = ref();

const wh_id = computed(() => {
  return warehouse_id;
});

function formatDate(dateString) {
  const date = new Date(dateString);

  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();

  return `${day}/${month}/${year}`;
}

function formatRupiah(value) {
  if (value === null || value === undefined || value === '') return '-';

  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
}

const openHistory = (data) => {
  dataHistoryDataItem.items = []
  modalOpenHistory.value = true
  loadHistory(data)
}

function closeHistory(i) {
  dataHistoryDataItem.items = []
  modalOpenHistory.value = false
}

async function loadHistory(data) {
  try {
    isRequesting.value = true;

    dataHistoryDataItem.nama_item = data?.nama_item;
    const id = data.id;

    const params = { where: `this.m_item_id=${id}`, scopes: 'WithHeader' };
    const fixedParams = new URLSearchParams(params);

    const url = `${store.server.url_backend}/operation/r_stock_d?${fixedParams}`;

    const res = await fetch(url, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
    });

    if (!res.ok) throw new Error("Failed when trying to read data");

    const result = await res.json();
    const rawItems = JSON.parse(JSON.stringify(result.data));

    let lastHargaSakMasuk = null;
    const processed = [];

    for (const item of rawItems) {
      const detailData = await getDetail(item.referensi_id, item.referensi_table);

      // DATE FIX
      const dateStr = detailData?.date ?? item.date;
      item.real_date = parseDMY(dateStr);

      item.no_trx = detailData?.no_trx ?? null;

      const tipe = item.tipe_transaksi?.toUpperCase();

      if (tipe === "MASUK") {
        lastHargaSakMasuk = item.harga_sak ?? lastHargaSakMasuk;
      }

      if (tipe === "KELUAR") {
        item.harga_sak_from_masuk = lastHargaSakMasuk ?? null;
      }

      processed.push(item);
    }

    // SORT DESC by real_date
    dataHistoryDataItem.items = processed.sort((a, b) => {
      return b.real_date - a.real_date;
    });

  } catch (e) {
    console.error(e);
  } finally {
    isRequesting.value = false;
  }
}

const getDetail = async (id, table) => {
  try {
    if (!id) return
    const dataURL = `${store.server.url_backend}/operation/${table}/${id}`
    const params = { simplest: true }
    const fixedParams = new URLSearchParams(params)

    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    })
    if (!res.ok) throw new Error("Failed when trying to read data")

    const resultJson = await res.json()
    return {
      no_trx: (resultJson.data?.no ?? resultJson.data?.no_pen ?? resultJson.data?.no_sj) ?? null,
    }
  } catch (err) {
    swal.fire({
      icon: 'warning',
      text: `Error fetching m_item data: ${err}`,
      allowOutsideClick: false,
    })
  }
}

function parseDMY(str) {
  if (!str) return null;
  const [d, m, y] = str.split("/");
  return new Date(+y, +m - 1, +d);
}

const landing = reactive({
  actions: [],
  api: {
    url: `${store.server.url_backend}/operation/${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      scopes: 'GetRstock',
      // warehouse_id: wh_id,
      simplest: true,
      order_by: 'm_item_id',
      order_type: 'desc',
      searchfield: 'this.id, this.kode, this.nama_barang, this.warehouse_name, this.satuan_nama, this.qty_stock'
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
    field: 'kode',
    headerName: 'Kode',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'nama_item',
    headerName: 'Nama Barang',
    filter: true,
    sortable: true,
    flex: 2,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'tipe_item',
    headerName: 'Tipe Item',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'uom_name',
    headerName: 'Satuan',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-center']
  },
  {
    field: 'qty_stock',
    headerName: 'QTY Stock',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-end']
  },
  {
    field: 'catatan',
    headerName: 'Catatan',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'is_active',
    headerName: 'Status',
    filter: true,
    filter: 'ColFilter',
    // resizable: true,
    // valueGetter: (p) => p.node.data['status'].toLowerCase()==='active'? 'Aktif':'Tidak Aktif',
    sortable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ({ value }) => {
      return value === true
        ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Active</span>`
        : `<span class="text-red-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">InActive</span>`
    }
  },
  {
    headerName: 'History Item',
    flex: 1,
    cellRenderer: 'ButtonGrid',
    cellRendererParams: {
      showValue: true,
      icon: 'eye',
      class: 'btn-text-info',
      click: (app) => {
        if (app && app.params) {
          const row = app.params.node.data
          openHistory(app.params.node.data)
        }
      }
    },
    sortable: false, resizable: true, filter: false,
    cellClass: ['justify-center']
  }
  ]
})

const filterButton = ref(null);

function filterShowData(params) {
  filterButton.value = filterButton.value === params ? null : params;
  landing.api.params.where = filterButton.value !== null ? `this.is_active=${filterButton.value}` : null;
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

watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))