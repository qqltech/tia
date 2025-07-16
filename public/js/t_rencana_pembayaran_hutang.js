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

// ENDPOINT API
const endpointApi = 't_rencana_pembayaran_hutang'
onBeforeMount(() => {
  document.title = 'Transaction Rencana Pembayaran Hutang'
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
    field: 'no_rph',
    headerName: 'No. RPH',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'tgl',
    headerName: 'Tanggal',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'total_bayar',
    headerName: 'Total Amount',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
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
          : (params.data['status'] == 'POST' ? `<span class="text-yellow-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'APPROVAL' ? `<span class="text-sky-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : (params.data['status'] == 'IN PROCESS' ? `<span class="text-yellow-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                : (params.data['status'] == 'COMPLETED' ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                  : (params.data['status'] == 'CANCEL' ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                    : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`))))))
    }
  },
  ],
  actions: [
    { title: 'Hapus', icon: 'trash', show: row => row.status == 'DRAFT', class: 'bg-red-600 text-light-100', click: deleteData },
    {
      title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?${tsId}`)
    },
    {
      title: 'Edit', icon: 'edit', show: row => row.status == 'DRAFT', class: 'bg-blue-600 text-light-100',
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
              const dataURL = `${store.server.url_backend}/operation/${endpointApi}/post`
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
    // {
    //   icon: 'location-arrow',
    //   title: "Send for approval",
    //   class: 'bg-rose-700 rounded-lg text-white',
    //   show: (row) => row.status === 'POST',
    //   async click(row) {
    //     swal.fire({
    //       icon: 'warning',
    //       text: 'Send for approval?',
    //       iconColor: '#1469AE',
    //       confirmButtonColor: '#1469AE',

    //       showDenyButton: true
    //     }).then(async (res) => {
    //       if (res.isConfirmed) {
    //         try {
    //           const dataURL = `${store.server.url_backend}/operation/t_spk_angkutan/approval`
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
    //               throw ("Failed when trying to approval data")
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
    // }
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

const allPphOptions = reactive([]);
onMounted(() => { window.addEventListener('keydown', handleKeyDown); });
onBeforeUnmount(() => { window.removeEventListener('keydown', handleKeyDown) });

// FORM DATA
let default_value = {
  data: { status: 'DRAFT', no_draft: 'Generate by System', no_rph: 'Generate by System' },
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
  if (actionText.value === 'Create' || data.status === 'DRAFT') {
    data.tanggal = getCurrentDateFormatted();
  }

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
    let detail_d;
    // FETCH HEADER DATA
    await fetchData(dataURL, { join: true, transform: false }).then((res) => {
      default_value.data = res.data;
      for (const key in res.data) {
        data[key] = res.data[key];
      }
      if (actionText == 'Copy') {
        data.alasan_revisi = '';
        data.no_draft = '';
        data.no_po = '';
        data.tanggal = getCurrentDateFormatted();
      }

      data.top = res.data['m_supplier.top'];
      // console.log(res.data['m_supplier.top'], "AAAAAAAAAAAAAAAA")
      detail_d = res.data.t_rencana_pembayaran_hutang_d;
    });
    detailArr.push(...detail_d);
    for (let idx = 0; idx < detail_d.length; idx++) {

      detailArr[idx].nama_supplier = detail_d[idx]['m_supplier.nama'];
      detailArr[idx].top = detail_d[idx]['m_supplier.top'];
      if (detail_d[idx].t_jurnal_angkutan_id) {

        detailArr[idx].no_referensi = detail_d[idx]['t_jurnal_angkutan.no_jurnal'];
        detailArr[idx].tipe_tagihan = 'Jurnal Angkutan';
        detailArr[idx].grand_total = detail_d[idx]['t_jurnal_angkutan.grand_total'];

        let [day, month, year] = detail_d[idx]['t_jurnal_angkutan.tgl'].split("/").map(Number);
        let dueDate = new Date(year, month - 1, day);
        detailArr[idx].tanggal = dueDate;


        // dueDate.setDate(dueDate.getDate() + Number(data.top));
        //     let dayf = dueDate.getDate().toString().padStart(2, '0');
        //     let monthf = (dueDate.getMonth() + 1).toString().padStart(2, '0');
        //     let yearf = dueDate.getFullYear();
        //     let formattedDate = `${dayf}/${monthf}/${yearf}`;
        // console.log("TANGGAL", detailArr[idx].tanggal, dueDate, data.top, formattedDate);

        //     detailArr[idx].tgl_jatuh_tempo = formattedDate;
        const ja_id = detail_d[idx].t_jurnal_angkutan_id;
        const dataURL = `${store.server.url_backend}/operation/t_jurnal_angkutan_id/${ja_id}`;
        // FETCH HEADER DATA
        await fetchData(dataURL, { join: true, transform: false }).then((res) => {
          if (res.data) {
            detailArr[idx].tgl_realisasi = res.data['t_pembayaran_hutang.tanggal_pembayaran'];

            detailArr[idx].jml_realisasi = res.data.total_bayar;
            detailArr[idx].cara_bayar_id = res.data['t_pembayaran_hutang.tipe_pembayaran_id'];
            detailArr[idx].status = res.data.sisa_hutang == 0 ? 'Lunas' : 'Belum Lunas';
          }
        });
        if (detailArr[idx].cara_bayar_id) {
          const dataURLgen = `${store.server.url_backend}/operation/m_general/${detailArr[idx].cara_bayar_id}`;
          // FETCH HEADER DATA
          await fetchData(dataURLgen, { join: true, transform: false }).then((res) => {
            detailArr[idx].cara_bayar = res.data['deskripsi'];
          });
        }
      }
      else if (detail_d[idx].t_purchase_invoice_id) {
        detailArr[idx].no_referensi = detail_d[idx]['t_purchase_invoice.no_pi'];
        detailArr[idx].tipe_tagihan = 'Purchase';
        detailArr[idx].grand_total = detail_d[idx]['t_purchase_invoice.grand_total'];

        let [day, month, year] = detail_d[idx]['t_purchase_invoice.tanggal'].split("/").map(Number);
        let dueDate = new Date(year, month - 1, day);
        detailArr[idx].tanggal = dueDate;
        // detailArr[idx].tgl_jatuh_tempo = detail_d[idx]['purchase_invoice.tanggal'];

        const pi_id = detail_d[idx].t_purchase_invoice_id;
        const dataURL = `${store.server.url_backend}/operation/t_pembayaran_hutang_d`;
        // FETCH HEADER DATA
        await fetchData(dataURL, { join: true, transform: false, where: `t_purchase_invoice.id = ${pi_id}`}).then((res) => {
          if (res.data[0]) {
            detailArr[idx].tgl_realisasi = res.data[0]['t_pembayaran_hutang.tanggal_pembayaran'];
            console.log(res, 'test')
            detailArr[idx].jml_realisasi = res.data[0].total_bayar;
            detailArr[idx].cara_bayar_id = res.data[0]['t_pembayaran_hutang.tipe_pembayaran_id'];
            detailArr[idx].status = res.data[0].sisa_hutang == 0 ? 'Lunas' : 'Belum Lunas';
          }
        });
        if (detailArr[idx].cara_bayar_id) {
          const dataURLgen = `${store.server.url_backend}/operation/m_general/${detailArr[idx].cara_bayar_id}`;
          // FETCH HEADER DATA
          await fetchData(dataURLgen, { join: true, transform: false }).then((res) => {
            detailArr[idx].cara_bayar = res.data['deskripsi'];
          });
        }

      }
    }

    // for (let idx = 0; idx < detailArr.length; idx++) {
    //   const dataURLcontainer = `${store.server.url_backend}/operation/t`;
    //   await fetchData(dataURLcontainer, {
    //     where: `this.id=${detailArr[idx].t_id}`
    //   }).then(res => {
    //     console.log(res.data[0].kode);
    //     if (res.data.length !== 0) {
    //       detailArr[idx].kode = res.data[0].kode;
    //       detailArr[idx].nama_item = res.data[0].nama_item;
    //       detailArr[idx].satuan = 'Lembar';
    //     }
    //   })
    // }

  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error', text: err, allowOutsideClick: false, confirmButtonText: 'Kembali',
    }).then(() => { router.back() });
  } finally {
    isRequesting.value = false;
  }



});


const pphChanged = async (id) => {
  const headers = {
    'Content-Type': 'application/json',
    Authorization: `${store.user.token_type} ${store.user.token}`,
  };

  const queryString = new URLSearchParams({ simplest: true, where: `this.group='JENIS PPH' AND this.id=${id}` }).toString();
  const response = await fetch(`${store.server.url_backend}/operation/m_general?${queryString}`, { headers });

  const resData = await response.json();
  data['persen_pph'] = resData.data[0].kode;
  console.log("allPphOptions", resData.data[0].kode);
}

// ADD & DELETE DETAIL
const addDetailArr = (params) => {
  detailArr.push(...params);
  // console.log(detailArr);
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

async function onSave(type = 'save') {
  const result = await swal.fire({
    icon: 'warning', text: type == 'post' ? 'Simpan dan Post data?' : 'Simpan data?', showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  try {
    const isCreating = ['Create', 'Copy'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : '/' + route.params.id}`;
    isRequesting.value = true;

    if (type == 'post') {
      data.status = "POST";
    }
    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify({
        ...data,
        t_rencana_pembayaran_hutang_d: detailArr,
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
  data.total_bayar = 0;
  data.total_pi = 0;
  for (let idx = 0; idx < detailArr.length; idx++) {
    data.total_pi += Number(detailArr[idx].grand_total);
    data.total_bayar += Number(detailArr[idx].jumlah_bayar);
    console.log(detailArr[idx].tanggal, detailArr[idx].no_pi, "AAAAAAAAA")
    if (!detailArr[idx].tgl_jatuh_tempo && detailArr[idx].tanggal) {
      let dueDate = new Date(detailArr[idx].tanggal);
      dueDate.setDate(dueDate.getDate() + Number(detailArr[idx].top));
      let dayf = dueDate.getDate().toString().padStart(2, '0');
      let monthf = (dueDate.getMonth() + 1).toString().padStart(2, '0');
      let yearf = dueDate.getFullYear();

      let formattedDate = `${dayf}/${monthf}/${yearf}`;
      detailArr[idx].tgl_jatuh_tempo = formattedDate;

      // console.log("TANGGAL", detailArr[idx].tanggal, dueDate, detailArr[idx].top);
    }
  }

}, { deep: true })

// watch(() => data.m_supplier_id, () => {
//   for (let idx = 0; idx < detailArr.length; idx++) {
//     let [day, month, year] = detailArr[idx].tanggal.split("/").map(Number);
//     let dueDate = new Date(year, month - 1, day);
//     dueDate.setDate(dueDate.getDate() + Number(data.top));

//     let dayf = dueDate.getDate().toString().padStart(2, '0');
//     let monthf = (dueDate.getMonth() + 1).toString().padStart(2, '0');
//     let yearf = dueDate.getFullYear();

//     let formattedDate = `${dayf}/${monthf}/${yearf}`;
//     detailArr[idx].tgl_jatuh_tempo = formattedDate;
//     console.log("TANGGAL", detailArr[idx].tanggal, dueDate, data.top);
//   }

// })



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
//  @endif | --- END --- |
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))