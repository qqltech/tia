import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated, watch } from 'vue'

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
const formErrors = ref({})
const tsId = `ts=` + (Date.parse(new Date()))

const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);

const tipe = ref('')
const isModalOpen = ref(false);

const isApproval = route.query.is_approval;


const setTipe = (val) => {
  tipe.value = val;
}

// ------------------------------ PERSIAPAN
const endpointApi = 't_bkk'
const endpointApiNonOrder = 't_bkk_non_order'
onBeforeMount(() => {
  document.title = 'Transaksi BKK'
})

//  @if( $id )------------------- JS CONTENT ! PENTING JANGAN DIHAPUS

// HOT KEY
onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
})
onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeyDown);
})

const handleKeyDown = (event) => {
  console.log(event)
  if (event?.ctrlKey && event?.key === 's') {
    event.preventDefault();
    onSave();
  }
}

let initialValues = { tipe_bkk: route.query.tipe, status: "DRAFT", t_bkk_d: [] }
const changedValues = []

let values = reactive({})

const detailArr = ref([])


// ADD & DELETE DETAIL
const addDetailArr = (params) => {
  detailArr.value.push(...params);
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
      detailArr = [];
    }
  })
}
// console.log()
// DEFAULT VALUE BEFORE MOUNT --UBAH DISINI
const defaultValues = () => {
  values.status = "DRAFT";
  values.tipe_bkk = route.query.tipe;
  values.m_coa_id = 1;
}

const onReset = async (alert = false) => {
  let next = false
  if (alert) {
    swal.fire({
      icon: 'warning',
      text: 'Anda yakin akan mereset data ini?',
      showDenyButton: true
    }).then((res) => {
      if (res.isConfirmed) {
        if (isRead) {
          for (const key in initialValues) {
            values[key] = initialValues[key]
          }
        } else {
          for (const key in values) {
            delete values[key]
          }
          defaultValues()
        }
      }
    })
  }

  setTimeout(() => {
    defaultValues()
  }, 100)
}

onBeforeMount(async () => {
  onReset()
  if (isRead) {
    //  READ DATA
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
      isRequesting.value = true;


      let dataURL;
      if (trx_id) {
        dataURL = `${store.server.url_backend}/operation/${endpointApi}/${trx_id}`;
      }
      else {
        // if (route.query.tipe == "Buku Order") dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`;
        // else if (route.query.tipe == "Non Buku Order") dataURL = `${store.server.url_backend}/operation/${endpointApiNonOrder}${isCreating ? '' : ('/' + route.params.id)}`;
        if (route.query.tipe == "Buku Order") dataURL = `${store.server.url_backend}/operation/${endpointApi}/${route.params.id}`;
        else if (route.query.tipe == "Non Buku Order") dataURL = `${store.server.url_backend}/operation/${endpointApiNonOrder}/${route.params.id}`;

      }

      console.log(route.query.action, 'asdasd')

      isRequesting.value = true

      console.log(route.params.id, editedId, values.tipe_bkk, route.query.tipe, "AAAAAAAA")
      const params = { join: true, transform: false }
      const fixedParams = new URLSearchParams(params)
      const res = await fetch(dataURL + '?' + fixedParams, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      })
      if (!res.ok) throw new Error("Failed when trying to read data")
      const resultJson = await res.json()
      initialValues = resultJson.data
    } catch (err) {
      isBadForm.value = true
      swal.fire({
        icon: 'error',
        text: err,
        allowOutsideClick: false,
        confirmButtonText: 'Kembali',
      }).then(() => {
        router.back()
      })
    }
    isRequesting.value = false
  }

  for (const key in initialValues) {
    values[key] = initialValues[key]
  }
  detailArr.value = initialValues.t_bkk_d;

  detailArr.value = detailArr.value.map(val => {
    val['nomor'] = val['m_coa.nomor'];
    val['nama_coa'] = val['m_coa.nama_coa'];
    return val;
  })
})

function onBack() {
  if (route.query.view_gaji) {
    router.replace('/t_info_gaji')
  } else if (route.query.view_gaji_final) {
    router.replace('/t_info_gaji')
  } else {
    router.replace('/' + modulPath)
  }
  return
}


async function onSave() {
  //values.tags = JSON.stringify(values.tags)
  try {
    let dataURL;
    console.log("asdasd", values.tipe_bkk, "DDDDDDDDDDDD")
    if (values.tipe_bkk == "Buku Order") dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`;
    else if (values.tipe_bkk == "Non Buku Order") dataURL = `${store.server.url_backend}/operation/${endpointApiNonOrder}${isCreating ? '' : ('/' + route.params.id)}`;
    isRequesting.value = true;
    values.is_active = values.is_active ? 1 : 0
    values.t_bkk_d = detailArr;
    values.t_bkk_non_order_d = detailArr;
    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(values)
    });
    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json();
        formErrors.value = responseJson.errors || {};
        throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Oops, sesuatu yang salah terjadi. Coba kembali nanti.");
      } else {
        throw ("Oops, sesuatu yang salah terjadi. Coba kembali nanti.");
      }
    }
    router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())));
  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'warning',
      text: err
    });
  }
  isRequesting.value = false;
}
watch(() => detailArr, () => {
  values.total_amt = 0;
  console.log(detailArr.value[0].nominal)

  for (let idx = 0; idx < detailArr.value.length; idx++) {
    console.log(detailArr.value[idx].nominal, 'AAAAAAAAA')
    // values.total_amt += Number(detailArr.value[idx].nominal)
    if (detailArr.value[idx].nominal != undefined) {
      values.total_amt += Number(detailArr.value[idx].nominal)
    }
  }

}, { deep: true })

async function progress(status) {

  // if (status == 'REVISED' && (data.alasan_revisi=='' || !data.alasan_revisi)) {
  //   swal.fire({
  //     icon: 'warning',
  //     text: `Isi alasan revisi terlebih dahulu`
  //   })
  //   next = false
  //   return
  // }

  swal.fire({
    icon: 'warning',
    text: status == 'APPROVED' ? 'Approve?' : status == 'REJECTED' ? 'Reject?' : 'Revise?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',

    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        let dataURL;
        if (values.tipe_bkk == "Buku Order") dataURL = `${store.server.url_backend}/operation/${endpointApi}/progress`;
        else if (values.tipe_bkk == "Non Buku Order") dataURL = `${store.server.url_backend}/operation/${endpointApiNonOrder}/progress`;
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: 'POST',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify({ id: route.params.id, type: status, note: 'a' })
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

//  @else----------------------- LANDING




const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) => row.status == 'DRAFT',

      click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Hapus Data Terpilih?',
          confirmButtonText: 'Yes',
          showDenyButton: true,
        }).then(async (result) => {
          if (result.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${row.id}`
              isRequesting.value = true
              const res = await fetch(dataURL, {
                method: 'DELETE',
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                }
              })
              if (!res.ok) {
                const resultJson = await res.json()
                throw (resultJson.message || "Failed when trying to remove data")
              }
              apiTable.value.reload()
              // const resultJson = await res.json()
            } catch (err) {
              isBadForm.value = true
              swal.fire({
                icon: 'error',
                text: err
              })
            }
            isRequesting.value = false
          }
        })
      }
    },
    {
      icon: 'eye',
      title: "Read",
      class: 'bg-green-600 text-light-100',
      click(row) {
        router.push(`${route.path}/${row.id}?tipe=${row.tipe_bkk}&?` + tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      show: (row) => row.status == 'DRAFT' || row.status == 'REVISED',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&tipe=${row.tipe_bkk}&` + tsId)
      }
    },
    {
      icon: 'copy',
      title: "Copy",
      class: 'bg-gray-600 text-light-100',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Copy&tipe=${row.tipe_bkk}&` + tsId)
      }
    },
    {
      icon: 'location-arrow',
      title: "Send Approval",
      class: 'bg-rose-700 text-light-100',
      show: (row) => row.status === 'POST' || row.status === 'DRAFT' || row.status === 'REVISED',
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
              let dataURL;
              if (row.tipe_bkk == "Buku Order") dataURL = `${store.server.url_backend}/operation/${endpointApi}/send_approval`;
              else if (row.tipe_bkk == "Non Buku Order") dataURL = `${store.server.url_backend}/operation/${endpointApiNonOrder}/send_approval`;

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
                  throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to approval data")
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
  api: {
    url: `${store.server.url_backend}/operation/${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield: 'this.no_draft, this.no_bkk, this.tipe_bkk, m_akun_pembayaran.nama, m_coa.nama_coa, this.total_amt, this.status'
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
    headerName: 'No. Draft',
    field: 'no_draft',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'No. BKK',
    field: 'no_bkk',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'BKK Type',
    field: 'tipe_bkk',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Payement Acc.',
    field: 'm_akun_pembayaran.nama',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'User',
    field: 'm_coa.nama_coa',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'BBK Amount',
    field: 'total_amt',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Status',
    field: 'status',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start'],
    cellRenderer: (params) => {
      return params.data['status'] == 1
        ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 'DRAFT' ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
          : (params.data['status'] == 'IN APPROVAL' ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'APPROVAL' ? `<span class="text-sky-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : (params.data['status'] == 'IN PROCESS' ? `<span class="text-yellow-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                : (params.data['status'] == 'COMPLETED' ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                  : (params.data['status'] == 'CANCEL' ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                    : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`))))))
    }
  },
  {
    headerName: 'Catatan',
    field: 'keterangan',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  ]
})

onActivated(() => {
  //  reload table api landing
  if (apiTable.value) {
    if (route.query.reload) {
      apiTable.value.reload()
    }
  }
})

//  @endif -------------------------------------------------END
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))