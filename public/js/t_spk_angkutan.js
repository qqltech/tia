import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated, watch } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const actionText = ref(route.params.id === 'create' ? 'Create' : route.query.action != 'EditContainer' ? route.query.action : false)
const actionSingleEdit = ref(route.query.action === 'EditContainer' ? 'EditContainer' : false)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const formErrors = ref({})
const tsId = `ts=` + (Date.parse(new Date()))

const isApproval = route.query.is_approval;
// console.log(store.user.data['name'],'user aaaa');
// ENDPOINT API
const endpointApi = 't_spk_angkutan'
onBeforeMount(() => {
  document.title = 'Transaction SPK Angkutan'
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
      searchfield: 'this.no_spk, tipe_spk.deskripsi,t_detail_npwp_container_1.no_buku_order,t_detail_npwp_container_2.no_buku_order, supir.nama, sektor1.deskripsi, this.total_sangu',
      getNoBukuOrder:true
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
      headerName: 'No. SPK',
      field: 'no_spk',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Tipe SPK',
      field: 'tipe_spk.deskripsi',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'No. Order 1',
      field: 't_detail_npwp_container_1.no_buku_order',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'No. Order 2',
      field: 't_detail_npwp_container_2.no_buku_order',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Supir',
      field: 'supir.nama',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Sektor',
      field: 'sektor1.deskripsi',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Sangu',
      field: 'total_sangu',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
      cellRenderer: (params) => {
        return params.data['total_sangu'] ? params.data['total_sangu'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") : '';
      }
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
          : (params.data['status'] == 'DRAFT' ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'POST' ? `<span class="text-yellow-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : (params.data['status'] == 'IN APPROVAL' ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                : (params.data['status'] == 'APPROVED' ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                  : (params.data['status'] == 'REVISED' ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                    : (params.data['status'] == 'REJECT' ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                      : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`)))))
          )
      }
    },
  ],
  actions: [
    {
      title: 'Hapus', icon: 'trash', class: 'bg-red-600 text-light-100', click: deleteData,
      show: (row) => row.status === 'DRAFT'
    },
    {
      title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?${tsId}`)
    },
    {
      title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Edit&${tsId}`),
      show: (row) => row.status === 'DRAFT' || row.status === 'REVISED'
    },
    {
      title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=EditContainer&${tsId}`),
      show: (row) => row.status == 'APPROVED' && row.is_con_edit != true
    },
    {
      title: 'Copy', icon: 'copy', class: 'bg-gray-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Copy&${tsId}`)
    },

    // {
    //   title: 'askdjlksad', icon: 'copy', class: 'bg-gray-600 text-light-100',
    //   async click(row) {
    //     apiTable.value.reload()
    //   }
    // },
    {
      icon: 'location-arrow',
      title: "Send Approval",
      class: 'bg-rose-700 rounded-lg text-white',
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
    {
      icon: 'print',
      title: "Cetak",
      class: 'bg-amber-600 text-light-100',
      show: (row) => row['status'] == 'APPROVED' || row['status'] == 'PRINTED',
      async click(row) {
        try {
          const dataURL = `${store.server.url_backend}/operation/t_spk_angkutan/print?id=${row.id}`;
          isRequesting.value = true;
          const response = await fetch(dataURL, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
          });

          if (!response.ok) {
            const responseJson = await response.json();
            if ([400, 422, 500].includes(response.status)) {
              formErrors.value = responseJson.errors || {};
              throw new Error(responseJson?.message + " " + responseJson?.data?.errorText || "Failed when trying to post data");
            } else {
              throw new Error("Failed when trying to print data");
            }
          }

          const responseJson = await response.json();
          swal.fire({
            icon: 'success',
            text: responseJson?.message || 'PRINTED'
          });
          window.open(`${store.server.url_backend}/web/spk_angkutan?id=${row.id}`)
        } catch (err) {
          isBadForm.value = true;
          swal.fire({
            icon: 'error',
            iconColor: '#1469AE',
            confirmButtonColor: '#1469AE',
            text: err.message
          });
        } finally {
          isRequesting.value = false;
          apiTable.value.reload();
        }
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
  if (event?.ctrlKey && event?.key === 's' && (actionText.value || actionSingleEdit.value)) {
    event.preventDefault();
    onSave();
  }
}

onMounted(() => { window.addEventListener('keydown', handleKeyDown) });
onBeforeUnmount(() => { window.removeEventListener('keydown', handleKeyDown) });

// FORM DATA
let default_value = {
  data: { status: 'DRAFT', is_con_edit: false, total_bon_tambahan: 0 },
  detail: []
}

const data = reactive({ ...default_value.data });

const dataOrderId = reactive([]);

const initArr = {
  keterangan: ''
}
const detailArr = reactive([])


// GET DATA FROM API
onBeforeMount(async () => {
  if (actionText.value === 'Create' || data.status === 'DRAFT') {
    data.tanggal_spk = getCurrentDateFormatted();
  }


  const headers = {
    'Content-Type': 'application/json',
    Authorization: `${store.user.token_type} ${store.user.token}`,
  };

  const fetchData = async (url, params = {}) => {
    const queryString = new URLSearchParams(params).toString();
    const response = await fetch(`${url}?${queryString}`, { headers });
    return response.json();
  };

  const dataOrder = `${store.server.url_backend}/operation/${endpointApi}`;
  await fetchData(dataOrder, { join: false, transform: false }).then((res) => {
    // default_value.data = res.data;

    dataOrderId.push(...res.data.map(dt => dt.t_detail_npwp_container_1_id).filter(dt2 => dt2 && dt2 != data.t_detail_npwp_container_1_id && dt2 != data.t_detail_npwp_container_2_id));
    dataOrderId.push(...res.data.map(dt => dt.t_detail_npwp_container_2_id).filter(dt2 => dt2 && dt2 != data.t_detail_npwp_container_1_id && dt2 != data.t_detail_npwp_container_2_id));

    console.log("dataOrderId", dataOrderId);

  });


  if (!isRead) return;

  try {
    let trx_id;

    const headers = {
      'Content-Type': 'application/json',
      Authorization: `${store.user.token_type} ${store.user.token}`,
    };
    const fetchData = async (url, params = {}) => {
      const queryString = new URLSearchParams(params).toString();
      const response = await fetch(`${url}?${queryString}`, { headers });
      return response.json();
    };

    if (route.query.is_approval) {
      const dataApprovalURL = `${store.server.url_backend}/operation/generate_approval/${route.params.id}`;
      isRequesting.value = true;

      // FETCH HEADER DATA
      await fetchData(dataApprovalURL, { join: true, transform: false }).then((res) => {
        trx_id = res.data.trx_id;
        console.log(res, trx_id);
      });
    }

    const editedId = route.params.id;
    const dataURL = trx_id ? `${store.server.url_backend}/operation/${endpointApi}/${trx_id}` : `${store.server.url_backend}/operation/${endpointApi}/${editedId}`;
    isRequesting.value = true;
    console.log(trx_id)



    // FETCH HEADER DATA
    await fetchData(dataURL, { join: false, transform: false }).then((res) => {
      // default_value.data = res.data;
      detailArr.push(...res.data.t_spk_bon_detail);
      for (const key in res.data) {
        data[key] = res.data[key];
      }
      //data.is_con_edit = false;
      if (actionText.value === 'Copy') {
        data.status = default_value.data.status;
        data.is_con_edit = default_value.data.is_con_edit;
        data.no_spk = null;

      }
    });


    const dataURLcontainer = `${store.server.url_backend}/operation/t_buku_order_d_npwp`;
    await fetchData(dataURLcontainer, {
      where: `this.id=${data.t_detail_npwp_container_1_id}`
    }).then(res => {
      if (res.data.length !== 0) {
        data.ukuran_container_1 = res.data[0]['ukuran.deskripsi'];
        data.jenis_container_1 = res.data[0]['jenis.deskripsi'];
        data.tipe_container_1 = res.data[0]['tipe.deskripsi'];
        data.no_prefix_1 = res.data[0]['no_prefix'];
        data.no_suffix_1 = res.data[0]['no_suffix'];
      }
    })
    await fetchData(dataURLcontainer, {
      where: `this.id=${data.t_detail_npwp_container_2_id}`
    }).then(res => {
      if (res.data.length !== 0) {
        data.ukuran_container_2 = res.data[0]['ukuran.deskripsi'];
        data.jenis_container_2 = res.data[0]['jenis.deskripsi'];
        data.tipe_container_2 = res.data[0]['tipe.deskripsi'];
        data.no_prefix_2 = res.data[0]['no_prefix'];
        data.no_suffix_2 = res.data[0]['no_suffix'];
      }
    })



    // FETCH DETAIL DATA 
    // await fetchData(`${store.server.url_backend}/operation/t_spk_d_tambahan`, {
    //   where: `this.t_po_id=${editedId}`, order_by: "created_at", order_type: "ASC"
    // }).then((res) => {
    //   default_value.detail = res.data.map(item => (
    //     { ...item, item: item['t_spk_d_tambahan.item'] }
    //   ));
    //   detail.data = default_value.detail.map(item => ({ ...item }));
    // });

    console.log("AAAAAAA", actionSingleEdit.value, route.query.action)
    console.log("AAAAAAA", !actionText.value && (!actionSingleEdit.value || data.is_con_edit == true))
  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error', text: err, allowOutsideClick: false, confirmButtonText: 'Kembali',
    }).then(() => { router.back() });
  } finally {
    isRequesting.value = false;
  }
  console.log(data.t_detail_npwp_container_1_id, 'kiw kiw')
});


// ADD & DELETE DETAIL
const addDetailBon = () => {
  detailArr.push({ ...initArr });
}

const delDetailBon = (index) => {
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
  // console.log(data, detailArr);
  // console.log('ini panjangggg', detailArr.length);

  const result = await swal.fire({
    icon: 'warning', text: 'Simpan data?', showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  try {
    let next = true
    if(!data.waktu_out){
      swal.fire({
        icon: 'warning',
        text: `Waktu Out harus diisi`
      })
      next = false
      return
    }
    if(!data.waktu_in){
      swal.fire({
        icon: 'warning',
        text: `Waktu In harus diisi`
      })
      next = false
      return
    }


    const isCreating = ['Create', 'Copy'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : '/' + route.params.id}`;
    isRequesting.value = true;

    if (actionSingleEdit.value == 'EditContainer') data.is_con_edit = true;

    data.no_container_1 = data.no_prefix_1 + '-' + data.no_suffix_1;
    data.no_container_2 = data.no_prefix_2 + '-' + data.no_suffix_2;

    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify({
        ...data,
        t_spk_bon_detail: detailArr,
      }),
    });

    const dataURLDNPWP1 = `${store.server.url_backend}/operation/t_buku_order_d_npwp/${data.t_buku_order_1_id}`;
    isRequesting.value = true;
    const resDNPWP1 = await fetch(dataURLDNPWP1, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify({
        no_prefix: data.no_prefix_1,
        no_suffix: data.no_suffix_1,
      }),
    });

    const dataURLDNPWP2 = `${store.server.url_backend}/operation/t_buku_order_d_npwp/${data.t_buku_order_2_id}`;
    isRequesting.value = true;
    const resDNPWP2 = await fetch(dataURLDNPWP2, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify({
        no_prefix: data.no_prefix_2,
        no_suffix: data.no_suffix_2,
      }),
    });





    if (!res.ok) {
      const responseJson = await res.json();
      formErrors.value = responseJson.errors || {};
      if (responseJson.message === "create data failed") {
        swal.fire({ icon: 'error', text: responseJson.errors[0] || "Failed when trying to post data" });
      } else {
        swal.fire({ icon: 'error', text: responseJson.message || "Failed when trying to post data" });
      }
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
  console.log('ini panjangggg', detailArr.length);
  data.total_bon_tambahan = 0;
  for (let idx = 0; idx < detailArr.length; idx++) {
    if (detailArr[idx].nominal) data.total_bon_tambahan += Number(detailArr[idx].nominal);
  }
}, { deep: true })

watch([() => data.total_bon_tambahan, () => data.sangu], () => {
  data.total_sangu = Number(data.total_bon_tambahan) + Number(data.sangu);
});

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
            t_spk_bon_detail: detailArr,
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

async function progress(status) {
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
          body: JSON.stringify({ id: route.params.id, type: status, note: 'aaaa' })
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
      router.replace('/' + 'notifikasi');
    }
  })
}

const is_key_isi_container_1 = ref(false)
const is_key_isi_container_2 = ref(false)

watch(() => [data.isi_container_1, data.isi_container_2, data.trip_id], () => {
  // && data.trip_id != 310
  if (data.isi_container_1 == 249) {
    data.isi_container_2 = 248;
    is_key_isi_container_2.value = true;
    console.log("aodhfaoshfosdfh")

  }
  else {
    // data.isi_container_2 = 248;
    is_key_isi_container_2.value = false;
    console.log("aksdaksdasd")

  }
  if (data.isi_container_2 == 249) {
    data.isi_container_1 = 248;
    is_key_isi_container_1.value = true;
  }
  else {
    is_key_isi_container_1.value = false;
  }
  console.log(data.isi_container_1, data.isi_container_2, data.trip_id)
});


watch(() => [data.total_sangu], () => {
  data.total_sangu_tampil = data.total_sangu.toLocaleString('id-ID');
});
//  @endif | --- END --- |
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))