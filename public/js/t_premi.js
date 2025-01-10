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

const isApproval = route.query.is_approval

// ENDPOINT API
const endpointApi = 't_premi'
onBeforeMount(() => {
  document.title = 'Transaction Premi'
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
      simplest: false,
      searchfield: 'this.id,',
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
      headerName: 'No. Premi',
      field: 'no_premi',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Tanggal',
      field: 'tgl',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Total Nominal',
      field: 'total_premi',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
      // cellRenderer: (params) =>{
      //   params.data.
      // }
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
      title: 'Hapus', icon: 'trash', class: 'bg-red-600 text-light-100', click: deleteData,
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
      click: row => router.push(`${route.path}/${row.id}?action=Copy&${tsId}`),
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

    {
      icon: 'location-arrow',
      title: "Send for approval",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status === 'POST' || row.status === 'REVISED',
      async click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Send for approval?',
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

onMounted(() => { window.addEventListener('keydown', handleKeyDown) });
onBeforeUnmount(() => { window.removeEventListener('keydown', handleKeyDown) });

// FORM DATA
let default_value = {
  data: { status: 'DRAFT', no_draft: 'Generate by System', no_premi: 'Generate by System', total_bon_tambahan: 0 },
  detail: []
}

const data = reactive({ ...default_value.data });

const initArr = {
  t_premi_id: data.id || 0,
  keterangan: ''
}
const detailArr = reactive([])


// GET DATA FROM API
onBeforeMount(async () => {
  if (actionText.value === 'Create' || data.status === 'DRAFT') {
    data.tgl = getCurrentDateFormatted();
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
      // default_value.data = res.data;
      detailArr.push(...res.data.t_premi_d);
      for (const key in res.data) {
        data[key] = res.data[key];
      }

      if (actionText.value === 'Copy') {

        data.no_draft = default_value.data.no_draft;
        data.no_premi = default_value.data.no_premi;
        data.status = default_value.data.status;
        data.tgl = getCurrentDateFormatted();
      }
    });

    const dataURLSPK = `${store.server.url_backend}/operation/t_spk_angkutan/${data.t_spk_angkutan_id}`;
    await fetchData(dataURLSPK, { join: true, transform: false }).then((res) => {
      // default_value.data = res.data;
      if (res.data) {
        getDetailNPWPContainer(res.data['t_detail_npwp_container_1_id'], res.data['t_detail_npwp_container_2_id']);
        data.no_container = res.data['no_container_1'];
        data.no_angkutan = res.data['t_buku_order_1.angkutan'];
        data.tanggal_out = res.data.tanggal_out;
        data.waktu_out = res.data.waktu_out;
        data.no_bon_sementara = res.data.no_bon_sementara;
        data.tanggal_bon = res.data.tanggal_bon;

        data.tanggal_in = res.data.tanggal_in;
        data.waktu_in = res.data.waktu_in;
        data.m_karyawan_id = res.data['supir.id'];
        data.chasis = res.data.chasis;
        data.total_sangu = res.data.sangu;
        data.sektor = res.data['sektor1.deskripsi'];
        data.ke = res.data.ke;
        data.dari = res.data.dari;
      }
      else {
        data.no_container = '';
        data.no_order = '';
        data.no_angkutan = '';
        data.tanggal_out = '';
        data.waktu_out = '';
        data.no_bon_sementara = '';
        data.tanggal_bon = '';

        data.tanggal_in = '';
        data.waktu_in = '';
        data.ukuran_container = '';
        data.m_karyawan_id = '';
        data.chasis = '';
        data.total_sangu = '';
        data.sektor = '';
        data.ke = '';
        data.dari = '';
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


// ADD & DELETE DETAIL
const addDetail = () => {
  detailArr.push({ ...initArr });
}

const delDetail = (index) => {
  detailArr.splice(index, 1);
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
      for (const key in data) {
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
  console.log(data, detailArr);
  console.log('ini panjangggg', detailArr.length);
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
        t_premi_d: detailArr,
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

const getTarifPremi = async (t_1_id, t_2_id) => {
  console.log("AAAAAAA  AAAAAAA", t_1_id, t_2_id)
  const headers = {
    'Content-Type': 'application/json',
    Authorization: `${store.user.token_type} ${store.user.token}`,
  };

  const dataURL = `${store.server.url_backend}/operation/m_tarif_premi/get_tarif_premi`;
  const params = {
    get_tarif_premi: true,
    spk_id: data.t_spk_angkutan_id
  };

  const fetchData = async (url, params = {}) => {
    const queryString = new URLSearchParams(params).toString();
    const response = await fetch(`${url}?${queryString}`, { headers });
    return response.json();
  };

  // FETCH HEADER DATA
  await fetchData(dataURL, params).then((res) => {
    console.log('tarifff', res.premi);
    if (res.premi) data.tarif_premi = res.premi;
    else data.tarif_premi = '';
  });

}

const getDetailNPWPContainer = async (t_1_id, t_2_id) => {
  console.log("AAAAAAAAAAAAAA", t_1_id, t_2_id)
  if (t_1_id) {
    const url1 = `${store.server.url_backend}/operation/t_buku_order_d_npwp/${t_1_id}`
    const res1 = await fetch(url1, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      params: {
        join: true,
        // where: `t_purchase_order_id=${id}`
      },
    })
    if (!res1.ok) throw new Error("Failed when trying to read data")
    const result1 = await res1.json();
    data.ukuran_container = (result1.data['ukuran.deskripsi'] ?? '-') + ', ';
    data.no_order = (result1.data['t_buku_order.no_buku_order'] ?? '-') + ', ';
    data.no_angkutan = (result1.data['t_buku_order.angkutan'] ?? '-') + ', ';
  }
  else {
    data.ukuran_container = '-, ';
    data.no_order = '-, ';
    data.no_angkutan = '-, ';

  }
  if (t_2_id) {
    const url2 = `${store.server.url_backend}/operation/t_buku_order_d_npwp/${t_2_id}`;
    const res2 = await fetch(url2, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      params: {
        join: true,
        // where: `t_purchase_order_id=${id}`
      },
    })
    if (!res2.ok) throw new Error("Failed when trying to read data")
    const result2 = await res2.json();
    data.ukuran_container += (result2.data['ukuran.deskripsi'] ?? '-');
    data.no_order += (result2.data['t_buku_order.no_buku_order'] ?? '-');
    data.no_angkutan += (result2.data['t_buku_order.angkutan'] ?? '-');
  }
  else {
    data.ukuran_container += '-';
    data.no_order += '-';
    data.no_angkutan += '-';

  }

}


watch(() => [detailArr, data.tarif_premi, data.tol, data.total_sangu, data.hutang_dibayar], () => {
  console.log('ini panjangggg', detailArr.length);
  data.total_premi = 0;
  for (let idx = 0; idx < detailArr.length; idx++) {
    if (detailArr[idx].nominal) data.total_premi += Number(detailArr[idx].nominal);
  }
  if (data.tarif_premi) data.total_premi += Number(data.tarif_premi);
  if (data.tol) data.total_premi += Number(data.tol);
  if (data.total_sangu) data.total_premi -= Number(data.total_sangu);
  if (data.hutang_dibayar) data.total_premi -= Number(data.hutang_dibayar);
}, { deep: true })

// watch([() => data.tarif_premi, () => data.tol], () => {
//   data.total_premi = 0;
//   for (let idx = 0; idx < detailArr.length; idx++) {
//     if (detailArr[idx].nominal) data.total_premi += Number(detailArr[idx].nominal);
//   }
//   if (data.tarif_premi) data.total_premi += data.tarif_premi;
//   if (data.tol) data.total_premi += data.tol;
// });
// // watch([() => data.total_bon_tambahan, () => data.sangu], () => {
// //   data.total_sangu = Number(data.total_bon_tambahan) + Number(data.sangu);

// // });

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
            t_premi_d: detailArr,
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
        console.error('Error occurred:', err);
        isBadForm.value = true;
        swal.fire({
          icon: 'error',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',
          text: err.message || "An unexpected error occurred"
        });
      } finally {
        isRequesting.value = false;
        router.replace('/' + modulPath);
      }
    }
  });
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