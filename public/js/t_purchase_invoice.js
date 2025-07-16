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
const tsId = `ts=` + (Date.parse(new Date()))

const isApproval = route.query.is_approval;

// ENDPOINT API
const endpointApi = 't_purchase_invoice'
onBeforeMount(() => {
  document.title = 'Transaction Purchase Invoice'
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
    field: 'no_draft',
    headerName: 'No. Draft',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'no_pi',
    headerName: 'No. PI',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'tanggal',
    headerName: 'Tanggal PI',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'm_supplier.nama',
    headerName: 'Supplier',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1.5,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 't_lpb.tanggal_sj_supplier',
    headerName: 'Tgl Jatuh Tempo',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 't_lpb.no_lpb',
    headerName: 'No. LPB',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'no_faktur_pajak.no_faktur_pajak',
    headerName: 'No. Faktur',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 't_lpb.no_sj_supplier',
    headerName: 'No. SJ',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'grand_total',
    headerName: 'Grand Total',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1.5,
    cellClass: ['border-r', '!border-gray-200', 'justify-start'],
    valueFormatter: (params) => {
      // Format the value as Rupiah
      if (params.value) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(params.value);
      }
      return params.value;
    }
  },
  {
    field: 'Status',
    headerName: 'Status',
    filter: true,
    filter: 'ColFilter',
    // resizable: true,
    // valueGetter: (p) => p.node.data['status'].toLowerCase()==='active'? 'Aktif':'Tidak Aktif',
    sortable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: (params) => {
      return params.data['status'] == 1
        ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 'DRAFT' ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
          : (params.data['status'] == 'POST' ? `<span class="text-amber-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'IN APPROVAL' ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : (params.data['status'] == 'IN PROCESS' ? `<span class="text-yellow-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                : (params.data['status'] == 'COMPLETED' ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                  : (params.data['status'] == 'CANCEL' ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
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
      click: row => router.push(`${route.path}/${row.id}?${tsId}`),
    },
    {
      title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Edit&${tsId}`),
      show: (row) => row.status === 'DRAFT',
    },
    {
      title: 'Copy', icon: 'copy', class: 'bg-gray-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Copy&${tsId}`),
    },
    // {
    //   icon: 'location-arrow',
    //   title: "Post Data",
    //   class: 'bg-rose-700 rounded-lg text-white',
    //   show: (row) => row.status === 'DRAFT',
    //   async click(row) {
    //     swal.fire({
    //       icon: 'warning',
    //       text: 'Post Data?',
    //       iconColor: '#1469AE',
    //       confirmButtonColor: '#1469AE',

    //       showDenyButton: true
    //     }).then(async (res) => {
    //       if (res.isConfirmed) {
    //         try {
    //           const dataURL = `${store.server.url_backend}/operation/t_purchase_invoice/post`
    //           isRequesting.value = true
    //           const res = await fetch(dataURL, {
    //             method: 'POST',
    //             headers: {
    //               'Content-Type': 'Application/json',
    //               Authorization: `${store.user.token_type} ${store.user.token}`
    //             },
    //             body: JSON.stringify({ id: row.id })
    //           })
    //           if (!res.ok) {
    //             if ([400, 422, 500].includes(res.status)) {
    //               const responseJson = await res.json()
    //               formErrors.value = responseJson.errors || {}
    //               throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to post data")
    //             } else {
    //               throw ("Failed when trying to post data")
    //             }
    //           }
    //           const responseJson = await res.json()
    //           swal.fire({
    //             icon: 'success',
    //             text: responseJson.message
    //           })
    //           // const resultJson = await res.json()
    //         } catch (err) {
    //           isBadForm.value = true
    //           swal.fire({
    //             icon: 'error',
    //             iconColor: '#1469AE',
    //             confirmButtonColor: '#1469AE',
    //             text: err
    //           })
    //         }
    //         isRequesting.value = false

    //         apiTable.value.reload()
    //       }
    //     })
    //   }
    // },
    {
      icon: 'location-arrow',
      title: "Send Approval",
      class: 'bg-rose-700 text-light-100',
      show: (row) => row.status === 'DRAFT' || row.status === 'REVISED' || row.status === 'POST',
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

const allPphOptions = ref([]);
const allPpnOptions = ref([]);

const getPphOption = async () => {
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
    where: `this.group='JENIS PPH'`
  }).then(res => {
    console.log(res.data);
    if (res.data.length !== 0) {
      console.log(res.data);
      allPphOptions.value = res.data;
    }
  })
}

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

onMounted(() => { window.addEventListener('keydown', handleKeyDown); });
onBeforeUnmount(() => { window.removeEventListener('keydown', handleKeyDown) });

// FORM DATA
let default_value = {
  data: { status: 'DRAFT', persen_ppn: 0, persen_pph: 0, total_amount: 0, dpp: 0, total_ppn: 0, total_pph: 0, grand_total: 0, no_draft: 'Generate by System', no_pi: 'Generate by System' },
  detail: []
}

const data = reactive({ ...default_value.data });
const detail = reactive({ data: [...default_value.detail] });

const initArr = {
  catatan: ''
}
const detailArr = reactive([])

// GET DATA FROM API
onBeforeMount(async () => {
  getPphOption();
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
    await fetchData(dataURL, { join: true, transform: false }).then((res) => {
      default_value.data = res.data;
      for (const key in res.data) {
        data[key] = res.data[key];
      }
      data["tipe_po"] = res.data["t_po.tipe"];
      data["tanggal_lpb"] = res.data["t_lpb.tanggal_lpb"];
      if (actionText.value?.toLowerCase() === 'copy') {
        delete data.no_draft
        delete data.no_pi
        data.status = 'DRAFT'
        data.tanggal = getCurrentDateFormatted()
        data.tanggal_lpb = getCurrentDateFormatted()
      }
    });

    const uniqueDetailIds = [...new Set(data.t_purchase_invoice_d.map(pid => pid["t_po_detail_id"]))];

    for (const podetailid of uniqueDetailIds) {
      const queryString = new URLSearchParams({ 
        simplest: true,
        where: `this.id = ${podetailid}`,
        scopes: 'GetDetail'
      }).toString();
      const response = await fetch(`${store.server.url_backend}/operation/t_purchase_order_d?${queryString}`, { headers });
      const resData = await response.json();
      const result = resData.data.map(dt => ({
          t_no_po: dt['no_po'],
          t_po_id: dt['t_po_id'],
          t_po_detail_id: dt.id,
          m_item_id: dt['m_item.id'],
          kode: dt.kode_item,
          nama_item: dt.nama_item,
          tipe_item: dt['m_item.tipe_item'],
          quantity: dt.quantity,
          harga: parseInt(parseFloat(dt.harga)),
          satuan: 'Lembar',
          disc1: dt['disc1'],
          disc2: dt['disc2'],
          disc_amt: dt['disc_amt'],
          catatan: dt.catatan,
        }));
        detailArr.push(...result);
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


// const pphChanged = async (id) => {
//   const headers = {
//     'Content-Type': 'application/json',
//     Authorization: `${store.user.token_type} ${store.user.token}`,
//   };

//   const queryString = new URLSearchParams({ simplest: true, where: `this.group='JENIS PPH' AND this.id=${id}` }).toString();
//   const response = await fetch(`${store.server.url_backend}/operation/m_general?${queryString}`, { headers });

//   const resData = await response.json();
//   data['persen_pph'] = resData.data[0].kode;
//   console.log("allPphOptions", resData.data[0].kode);
// }

// ADD & DELETE DETAIL
const addDetailArr = (params) => {
  detailArr.push(...params);
  // console.log(detailArr);
}

const poChanged = async (id) => {
  const headers = {
    'Content-Type': 'application/json',
    Authorization: `${store.user.token_type} ${store.user.token}`,
  };

  const queryString = new URLSearchParams({ 
    simplest: true, 
    where: `this.t_lpb_id=${id}`
    
  }).toString();
  const response = await fetch(`${store.server.url_backend}/operation/t_lpb_d?${queryString}`, { headers });

  const resData = await response.json();
  detailArr.splice(0, 100);
  const result = resData.data.map(dt => {
    return {
      t_pi_id: data.id || null,
      t_po_id: dt.t_no_po_id,
      t_no_po: data.no_po,
      t_po_id: data.t_po_id,
      t_po_detail_id: dt.t_po_d_id,
      m_item_id: dt['m_item.id'],
      kode: dt['m_item.kode'],
      nama_item: dt['m_item.nama_item'],
      tipe_item: dt['m_item.tipe_item'],
      quantity: dt.qty,
      harga: parseInt(parseFloat(dt['t_po_d.harga'])),
      satuan: 'Lembar',
      disc1: parseFloat(dt['t_po_d.disc1']),
      disc2: parseFloat(dt['t_po_d.disc2']),
      disc_amt: parseFloat(dt['t_po_d.disc_amt']),
      catatan: dt.catatan,
    }
  })

  console.log("detailArr", resData.data);
  detailArr.push(...result);
}



const delDetailArr = (index) => {
  detailArr.splice(index, 1);
}

const deleteDetailArrAll = () => {
  swal.fire({
    icon: 'warning', text: 'Hapus semua detail data?', showDenyButton: true,
  }).then((res) => {
    if (res.isConfirmed) {
      detailArr = [];
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

    // data.tipe_pembayaran_id = 0;
    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify({
        ...data,
        t_purchase_invoice_d: detailArr,
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

watch(() => [detailArr, data.jenis_ppn, data.persen_pph], () => {
  data.total_amount = 0;
  data.total_disc_amount = 0;
  for (let idx = 0; idx < detailArr.length; idx++) {
    detailArr[idx].total_amount = Number(detailArr[idx].quantity) * Number(detailArr[idx].harga);
    data.total_amount += detailArr[idx].total_amount;

    // if (detailArr[idx].disc1 > 100) detailArr[idx].disc1 = 100;
    // if (detailArr[idx].disc2 > 100) detailArr[idx].disc2 = 100;
    // if (detailArr[idx].quantity <= 0) detailArr[idx].quantity = 1;
    // const count_amt_disc1 = detailArr[idx].harga * (detailArr[idx].disc1 / 100);
    // detailArr[idx].disc_amt = (detailArr[idx].harga - count_amt_disc1) * (detailArr[idx].disc2 / 100) + count_amt_disc1;
    detailArr[idx].total_disc = detailArr[idx].disc_amt * detailArr[idx].quantity;
    data.total_disc_amount += detailArr[idx].total_disc;
  }

  // data.dpp = data.total_amount - data.total_disc_amount;
  // data.total_ppn = data.dpp * (data.persen_ppn / 100);
  // data.total_pph = data.dpp * (data.persen_pph / 100);
  // data.jumlah = data.dpp + data.total_ppn + data.total_pph;
  // data.grand_total = data.dpp + data.total_ppn + data.total_pph;

  data.total_pph = data.dpp * (data.persen_pph / 100);
  const ppnidx = allPpnOptions.value.findIndex(pre => pre.id == data.jenis_ppn);
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
  data.jumlah = data.dpp + data.total_ppn + data.total_pph;
  data.grand_total = data.dpp + data.total_ppn + data.total_pph;
}, { deep: true })


// watch(() => [data.persen_ppn, data.persen_pph], () => {
//   data.dpp = data.total_amount - data.total_disc_amount;
//     data.total_ppn = data.dpp * (data.persen_ppn / 100);
//     data.total_pph = data.dpp * (data.persen_pph / 100);
//   data.jumlah = data.dpp + data.total_ppn + data.total_pph;
//   data.grand_total = data.dpp + data.total_ppn + data.total_pph;
// });


watch(() => [data.jenis_ppn, data.persen_pph], () => {

  data.total_pph = data.dpp * (data.persen_pph / 100);
  const ppnidx = allPpnOptions.value.findIndex(pre => pre.id == data.jenis_ppn);
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
  data.jumlah = data.dpp + data.total_ppn + data.total_pph;
  data.grand_total = data.dpp + data.total_ppn + data.total_pph;

}, { deep: true })



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

async function post() {
  swal.fire({
    icon: 'warning',
    text: 'Post?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',
    showDenyButton: true,
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const isCreating = ['Create', 'Copy'].includes(actionText.value);
        const dataURLSimpan = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : '/' + route.params.id}`;
        isRequesting.value = true;

        // Simpan data utama
        const resSimpan = await fetch(dataURLSimpan, {
          method: isCreating ? 'POST' : 'PUT',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`,
          },
          body: JSON.stringify({
            ...data,
            t_purchase_invoice_d: detailArr,
          }),
        });

        if (!resSimpan.ok) {
          const responseJson = await resSimpan.json();
          formErrors.value = responseJson.errors || {};
          throw new Error(responseJson.message || "Failed when trying to save data");
        }

        const responseJson = await resSimpan.json();
        const savedId = responseJson.id; // or responseJson.data.id depending on the structure
        console.log("Saved ID:", savedId);

        // Proses posting data
        const dataURL = `${store.server.url_backend}/operation/t_purchase_invoice/post`;
        isRequesting.value = true;
        const resPost = await fetch(dataURL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`,
          },
          body: JSON.stringify({ id: isCreating ? savedId : data.id }),
        });

        if (!resPost.ok) {
          if ([400, 422, 500].includes(resPost.status)) {
            const responseJson = await resPost.json();
            formErrors.value = responseJson.errors || {};
            throw new Error(responseJson.message + " " + (responseJson.data?.errorText || "Failed when trying to post"));
          } else {
            throw new Error("Failed when trying to post");
          }
        }

        const resultJson = await resPost.json();
        swal.fire({
          icon: 'success',
          text: resultJson.message || "Data posted successfully",
        });
      } catch (err) {
        isBadForm.value = true;
        swal.fire({
          icon: 'error',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',
          text: err.message || "An unexpected error occurred",
        });
      } finally {
        isRequesting.value = false;
        router.replace('/' + modulPath);
      }
    }
  });
}

// async function post() {
//   swal.fire({
//     icon: 'warning',
//     text: 'Post?',
//     iconColor: '#1469AE',
//     confirmButtonColor: '#1469AE',

//     showDenyButton: true
//   }).then(async (res) => {
//     if (res.isConfirmed) {
//       try {
//         const isCreating = ['Create', 'Copy'].includes(actionText.value);
//         const dataURLSimpan = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : '/' + route.params.id}`;
//         isRequesting.value = true;

//         const resSimpan = await fetch(dataURLSimpan, {
//           method: isCreating ? 'POST' : 'PUT',
//           headers: {
//             'Content-Type': 'application/json',
//             Authorization: `${store.user.token_type} ${store.user.token}`,
//           },
//           body: JSON.stringify({
//             ...data,
//             t_purchase_invoice_d: detailArr,
//           }),
//         });

//         if (!resSimpan.ok) {
//           const responseJson = await resSimpan.json();
//           formErrors.value = responseJson.errors || {};
//           swal.fire({ icon: 'error', text: responseJson.message || "Failed when trying to post data" });
//         }
//         else {
//           const responseJson = await resSimpan.json();
//           const savedId = responseJson.id; // or responseJson.data.id depending on the structure
//           console.log("Saved ID:", savedId);
//           const dataURL = `${store.server.url_backend}/operation/t_purchase_invoice/post`
//           isRequesting.value = true
//           const res = await fetch(dataURL, {
//             method: 'POST',
//             headers: {
//               'Content-Type': 'Application/json',
//               Authorization: `${store.user.token_type} ${store.user.token}`
//             },
//             body: JSON.stringify({ id: isCreating ? savedId : data.id })
//           })
//           if (!res.ok) {
//             if ([400, 422, 500].includes(res.status)) {
//               const responseJson = await res.json()
//               formErrors.value = responseJson.errors || {}
//               throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to post")
//             } else {
//               throw ("Failed when trying to post")
//             }
//           }
//           swal.fire({
//             icon: 'success',
//             text: responseJson.message
//           })
//         }


//         // const resultJson = await res.json()
//       } catch (err) {
//         isBadForm.value = true
//         swal.fire({
//           icon: 'error',
//           iconColor: '#1469AE',
//           confirmButtonColor: '#1469AE',
//           text: err
//         })
//       }
//       isRequesting.value = false;
//       router.replace(tsId);
//     }
//   })
// }
//  @endif | --- END --- |
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))