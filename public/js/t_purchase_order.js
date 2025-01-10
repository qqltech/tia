import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated, watch } from 'vue'

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

const isModalOpen = ref(false);

const isApproval = route.query.is_approval;

const tipe = ref('');
const setTipe = (val) => {
  tipe.value = val;
}

const tsId = `ts=` + (Date.parse(new Date()))

// ENDPOINT API
const endpointApi = 't_purchase_order'
onBeforeMount(() => {
  document.title = 'Transaction Purchase Order'
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
      searchfield: 'this.id, this.no_draft, this.no_po',
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
      headerName: 'No. PO',
      field: 'no_po',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Tanggal',
      field: 'tanggal',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Supplier',
      field: 'm_supplier.nama',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Tipe',
      field: 'tipe',
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
          : (params.data['status'] == 'DRAFT' ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'POST' ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : (params.data['status'] == 'IN APPROVAL' ? `<span class="text-sky-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                : (params.data['status'] == 'REVISED' ? `<span class="text-yellow-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                  : (params.data['status'] == 'APPROVED' ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                    : (params.data['status'] == 'REJECTED' ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                      : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`))))))
      }
    },
  ],
  actions: [
    {
      title: 'Hapus', icon: 'trash', class: 'bg-red-600 text-light-100',
      click: deleteData,
      show: (row) => row.status === 'DRAFT',
    },
    {
      title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?${tsId}`)
    },
    {
      title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Edit&${tsId}`),
      show: (row) => row.status === 'DRAFT' || row.status === 'REVISED',
    },
    {
      title: 'Copy', icon: 'copy', class: 'bg-gray-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Copy&${tsId}`)
    },
    {
      icon: 'location-arrow',
      title: "Send Approval",
      class: 'bg-rose-700 text-light-100',
      show: (row) => row.status === 'DRAFT' || row.status === 'REVISED',
      async click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Send Approval?',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',

          showDenyButton: true
        }).then(async (res) => {
          if (res.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/${endpointApi}/send_approval`
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

onMounted(() => { window.addEventListener('keydown', handleKeyDown) });
onBeforeUnmount(() => { window.removeEventListener('keydown', handleKeyDown) });

// FORM DATA
let default_value = {
  data: { status: 'DRAFT', total_amount: 0, b2b: true, dpp: 0, total_ppn: 0, tipe: route.query.tipe, grand_total: 0, no_draft: 'Generate by System', no_po: 'Generate by System' },
  detail: []
}

const data = reactive({ ...default_value.data });

const detail = reactive({ data: [...default_value.detail] });

const initArr = {
  catatan: ''
}
const detailArr = reactive([])

let modalOpenHistoryItem = ref(false)
let modalOpenHistoryStock = ref(false)
let dataHistoryDataItem = reactive({ items: [] })
let dataHistoryStockItem = reactive({ items: [] })

const openHistoryItem = (data) => {
  console.log(data);
  dataHistoryDataItem.items = []
  modalOpenHistoryItem.value = true
  loadHistoryItem(data)
}

const openHistoryStock = (data) => {
  dataHistoryStockItem.items = []
  modalOpenHistoryStock.value = true
  loadHistoryStock(data)
}

const changeIsBundling = (i) => {
  // detailArr[i].is_bundling = detailArr[i].is_bundling;
  console.log(detailArr[i].is_bundling)
}


function closeModalHistoryItem(i) {
  dataHistoryDataItem.items = []
  dataHistoryDataItem.itemName = null
  dataHistoryDataItem.itemCode = null
  modalOpenHistoryItem.value = false
}

function closeModalHistoryStock(i) {
  dataHistoryStockItem.items = []
  dataHistoryStockItem.itemName = null
  dataHistoryStockItem.itemCode = null
  modalOpenHistoryStock.value = false
}

async function loadHistoryItem(data) {

  dataHistoryDataItem.itemName = data['nama_item']
  dataHistoryDataItem.itemCode = data['kode']
  const item_id = data['m_item_id']
  const url = `${store.server.url_backend}/operation/t_purchase_order_d?where=this.m_item_id=${item_id}`
  const res = await fetch(url, {
    headers: {
      'Content-Type': 'Application/json',
      Authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      where: `m_item_id=${item_id}`
    },
  })
  if (!res.ok) throw new Error("Failed when trying to read data")
  const result = await res.json()
  for (let idx = 0; idx < result.data.length; idx++) {
    console.log(result.data.quantity, result.data.harga)
    result.data[idx].total_amt = result.data[idx].quantity * result.data[idx].harga;
    result.data[idx].total_disc = result.data[idx].quantity * result.data[idx].disc_amt;
  }
  dataHistoryDataItem.items = result.data
  console.log(dataHistoryDataItem.items)
}

async function loadHistoryStock(data) {
  dataHistoryStockItem.itemName = data['nama_item']
  dataHistoryStockItem.itemCode = data['kode']
  const item_id = data['m_item_id']
  // const url = `${store.server.url_backend}/operation/t_pemakaian_stok_d`
  // const res = await fetch(url, {
  //   headers: {
  //     'Content-Type': 'Application/json',
  //     Authorization: `${store.user.token_type} ${store.user.token}`
  //   },
  //   params: {
  //     where: `m_item_id=${item_id}`
  //   },
  // })
  // if (!res.ok) throw new Error("Failed when trying to read data")
  // const result = await res.json()
  // console.log(item_id, "aaaaaa")
  // dataHistoryStockItem.items = result.data








  const headers = {
    'Content-Type': 'application/json',
    Authorization: `${store.user.token_type} ${store.user.token}`,
  };

  const fetchData = async (url, params = {}) => {
    const queryString = new URLSearchParams(params).toString();
    const response = await fetch(`${url}?${queryString}`, { headers });
    return response.json();
  };


  const dataURL = `${store.server.url_backend}/operation/t_pemakaian_stok_d`;

  await fetchData(dataURL, {
    where: `m_item_id=${item_id}`
  }).then(res => {
    console.log(res.data[0].kode);
    if (res.data.length !== 0) {
      console.log(item_id, "aaaaaa")
      dataHistoryStockItem.items = res.data
    }
  })
}

const getPOD = async (id) => {
  const url = `${store.server.url_backend}/operation/${endpointApi}/${id}`
  const res = await fetch(url, {
    headers: {
      'Content-Type': 'Application/json',
      Authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      join: true,
      // where: `t_purchase_order_id=${id}`
    },
  })
  if (!res.ok) throw new Error("Failed when trying to read data")
  const result = await res.json()
  detailArr.splice(0, 100);
  detailArr.push(...result.data.t_purchase_order_d)


  const headers = {
    'Content-Type': 'application/json',
    Authorization: `${store.user.token_type} ${store.user.token}`,
  };

  const fetchData = async (url, params = {}) => {
    const queryString = new URLSearchParams(params).toString();
    const response = await fetch(`${url}?${queryString}`, { headers });
    return response.json();
  };

  for (let idx = 0; idx < detailArr.length; idx++) {
    if (data.tipe == 'Item') {
      const dataURLcontainer = `${store.server.url_backend}/operation/m_item`;
      await fetchData(dataURLcontainer, {
        where: `this.id=${detailArr[idx].m_item_id}`
      }).then(res => {
        console.log(res.data[0].kode);
        if (res.data.length !== 0) {
          detailArr[idx].kode = res.data[0].kode;
          detailArr[idx].nama_item = res.data[0].nama_item;
          detailArr[idx].satuan = 'Pcs';
        }
      })
    }
    else {
      const dataURLcontainer = `${store.server.url_backend}/operation/m_item`;
      await fetchData(dataURLcontainer, {
        where: `this.id=${detailArr[idx].m_item_id}`
      }).then(res => {
        console.log(res.data[0].kode);
        if (res.data.length !== 0) {
          detailArr[idx].kode = res.data[0].kode;
          detailArr[idx].nama_item = res.data[0].nama_item;
          detailArr[idx].satuan = 'Pcs';
        }
      })
    }
  }
}



const allPpnOptions = ref([]);

const getPpnOption = async () => {
  const headers = {
    'Content-Type': 'application/json',
    Authorization: `${store.user.token_type} ${store.user.token}`,
  };

  const fetchData = async (url, params = {}) => {
    const queryString = new URLSearchParams(params).toString();
    const response = await fetch(`${url}?${queryString}`, { headers });
    return response.json();
  };


  const dataURL = `${store.server.url_backend}/operation/m_general`;

  await fetchData(dataURL, {
    where: `this.group='JENIS PPN'`
  }).then(res => {
    console.log(res.data);
    if (res.data.length !== 0) {
      console.log(res.data);
      allPpnOptions.value = res.data;
    }
  })
}



// GET DATA FROM API
onBeforeMount(async () => {
  getPpnOption();
  if (actionText.value === 'Create' || data.status === 'DRAFT') {
    data.tanggal = getCurrentDateFormatted();
  }
  if (!isRead) return;

  try {
    let trx_id;

    if (route.query.is_approval) {
      const dataApprovalURL = `${store.server.url_backend}/operation/generate_approval/${route.params.id}`;
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
      await fetchData(dataApprovalURL, { join: false, transform: false }).then((res) => {
        trx_id = res.data.trx_id;
        console.log(res, trx_id);
      });

    }
    const editedId = route.params.id;
    const dataURL = trx_id ? `${store.server.url_backend}/operation/${endpointApi}/${trx_id}` : `${store.server.url_backend}/operation/${endpointApi}/${editedId}`;
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
      if (actionText.value == 'Copy') {
        data.alasan_revisi = '';
        data.no_draft = 'Generate by System';
        data.no_po = 'Generate by System';
        data.status = 'DRAFT';
        data.tanggal = getCurrentDateFormatted();
      }
      detailArr.push(...res.data.t_purchase_order_d);
    });
    for (let idx = 0; idx < detailArr.length; idx++) {
      if (data.tipe == 'Item') {
        const dataURLcontainer = `${store.server.url_backend}/operation/m_item`;
        await fetchData(dataURLcontainer, {
          where: `this.id=${detailArr[idx].m_item_id}`
        }).then(res => {
          console.log(res.data[0].kode);
          if (res.data.length !== 0) {
            detailArr[idx].kode = res.data[0].kode;
            detailArr[idx].nama_item = res.data[0].nama_item;
            detailArr[idx].satuan = 'Pcs';
          }
        })
      }
      else {
        const dataURLcontainer = `${store.server.url_backend}/operation/m_item`;
        await fetchData(dataURLcontainer, {
          where: `this.id=${detailArr[idx].m_item_id}`
        }).then(res => {
          console.log(res.data[0].kode);
          if (res.data.length !== 0) {
            detailArr[idx].kode = res.data[0].kode;
            detailArr[idx].nama_item = res.data[0].nama_item;
            detailArr[idx].satuan = 'Pcs';
          }
        })
      }
    }

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
const addDetailArr = (params) => {
  detailArr.push(...params);
  console.log(detailArr);
}

const delDetailArr = (index) => {
  detailArr.splice(index, 1);
}

const deleteDetailArrAll = () => {
  swal.fire({
    icon: 'warning', text: 'Hapus semua detail data?', showDenyButton: true,
  }).then((res) => {
    if (res.isConfirmed) {
      detailArr.splice(0, 100)
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
      for (const key in data) {
        data[key] = default_value.data[key];
      }
      detailArr = default_value.detail.map(item => ({ ...item }));
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
        t_purchase_order_d: detailArr,
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

watch(() => detailArr, () => {
  data.total_amount = 0;
  data.total_disc_amount = 0;
  for (let idx = 0; idx < detailArr.length; idx++) {
    detailArr[idx].total_amount = Number(detailArr[idx].quantity) * Number(detailArr[idx].harga);
    data.total_amount += detailArr[idx].total_amount;
    if (detailArr[idx].disc1 > 100) detailArr[idx].disc1 = 100;
    if (detailArr[idx].disc2 > 100) detailArr[idx].disc2 = 100;
    if (detailArr[idx].quantity <= 0) detailArr[idx].quantity = 1;
    const count_amt_disc1 = detailArr[idx].harga * (detailArr[idx].disc1 / 100);
    detailArr[idx].disc_amt = (detailArr[idx].harga - count_amt_disc1) * (detailArr[idx].disc2 / 100) + count_amt_disc1;
    detailArr[idx].total_disc = detailArr[idx].disc_amt * detailArr[idx].quantity;
    data.total_disc_amount += detailArr[idx].total_disc;
  }
  const ppnidx = allPpnOptions.value.findIndex(pre => pre.id == data.ppn);
  data.persen_ppn = allPpnOptions.value[ppnidx].deskripsi2;
  console.log(ppnidx, data.persen_ppn)
  if (allPpnOptions.value[ppnidx].deskripsi == "EXCLUDE") {
    data.dpp = data.total_amount - data.total_disc_amount;
    data.total_ppn = data.dpp * (data.persen_ppn / 100);
  }
  else if (allPpnOptions.value[ppnidx].deskripsi == "INCLUDE") {
    data.dpp = ((data.total_amount - data.total_disc_amount) / (1 + (data.persen_ppn / 100)));
    data.total_ppn = (data.total_amount - data.total_disc_amount) - data.dpp;
  }
  data.grand_total = data.dpp + data.total_ppn;

}, { deep: true })


watch(() => data.ppn, () => {
  const ppnidx = allPpnOptions.value.findIndex(pre => pre.id == data.ppn);
  data.persen_ppn = allPpnOptions.value[ppnidx].deskripsi2;
  console.log(ppnidx, data.persen_ppn)
  if (allPpnOptions.value[ppnidx].deskripsi == "EXCLUDE") {
    data.dpp = data.total_amount - data.total_disc_amount;
    data.total_ppn = data.dpp * (data.persen_ppn / 100);
  }
  else if (allPpnOptions.value[ppnidx].deskripsi == "INCLUDE") {
    data.dpp = ((data.total_amount - data.total_disc_amount) / (1 + (data.persen_ppn / 100)));
    data.total_ppn = (data.total_amount - data.total_disc_amount) - data.dpp;
  }
  data.grand_total = data.dpp + data.total_ppn;

}, { deep: true })


// watch(() => data.ppn, () => {
//   if (data.ppn == "EXCLUDE") {
//     data.dpp = data.total_amount - data.total_disc_amount;
//     data.total_ppn = data.dpp * 0.11;
//   }
//   else if (data.ppn == "INCLUDE") {
//     data.dpp = ((data.total_amount - data.total_disc_amount) / 1.11);
//     data.total_ppn = (data.total_amount - data.total_disc_amount) - data.dpp;
//   }
//   data.grand_total = data.dpp + data.total_ppn;

// }, { deep: true })

// watch(detail, async (newDetail, oldDetail) => {
//   data.total_amount = 0;
//   for (const idx in detail.data) {
//     data.total_amount += detail.data[idx].quantity * 3000;
//     console.log('ini watch', detail.data[idx]);
//   }
//   data.dpp = 0;
//   data.total_ppn = data.total_amount * 0.11 ;
//   data.grand_total = data.total_amount - data.total_ppn ;
// }, { immediate: true })

// watch(data.ppn, async (newDetail, oldDetail) => {
//   data.total_amount = 0;
//   for (const idx in detail.data) {
//     data.total_amount += detail.data[idx].quantity * 3000;
//     console.log('ini watch', detail.data[idx]);
//   }
//   data.dpp = 0;
//   data.total_ppn = data.total_amount * 0 ;
//   data.grand_total = data.total_amount - data.total_ppn ;
// }, { immediate: true })
const getCurrentDateFormatted = () => {
  const date = new Date();
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};

async function sendApproval() {
  swal.fire({
    icon: 'warning',
    text: 'Send Approval?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',

    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const isCreating = ['Create', 'Copy'].includes(actionText.value);
        const dataURLSimpan = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : '/' + route.params.id}`;
        isRequesting.value = true;

        const resSimpan = await fetch(dataURLSimpan, {
          method: isCreating ? 'POST' : 'PUT',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`,
          },
          body: JSON.stringify({
            ...data,
            t_purchase_order_d: detailArr,
          }),
        }).then(async response => {
          // if (!response.ok) {
          //   throw new Error(`HTTP error! status: ${response.status}`);
          // }
          if (!response.ok) {
            const responseJson = await resSimpan.json();
            formErrors.value = responseJson.errors || {};
            swal.fire({ icon: 'error', text: responseJson.message || "Failed when trying to post data" });
          }
          return response.json(); // Parse JSON from the response body
        }).then(async data => {
          console.log('Parsed response JSON:', data);
          data.id = data.id;
          const dataURL = `${store.server.url_backend}/operation/${endpointApi}/send_approval`
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
              throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to Send Approval")
            } else {
              throw ("Failed when trying to Send Approval")
            }
          }
          const responseJson = await res.json()
          swal.fire({
            icon: 'success',
            text: responseJson.message
          })
        });
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
async function progress(status) {

  if (status == 'REVISED' && (data.alasan_revisi == '' || !data.alasan_revisi)) {
    swal.fire({
      icon: 'warning',
      text: `Isi alasan revisi terlebih dahulu`
    })
    next = false
    return
  }

  swal.fire({
    icon: 'warning',
    text: status == 'APPROVED' ? 'Approve?' : status == 'REJECTED' ? 'Reject?' : 'Revise?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',

    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const dataURL = `${store.server.url_backend}/operation/${endpointApi}/progress`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: 'POST',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify({ id: route.params.id, type: status, note: data.alasan_revisi ? data.alasan_revisi : 'a' })
        })
        if (!res.ok) {
          if ([400, 422, 500].includes(res.status)) {
            const responseJson = await res.json()
            formErrors.value = responseJson.errors || {}
            throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to Approved")
          } else {
            throw ("Failed when trying to Approved")
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