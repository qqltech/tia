import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated, computed } from 'vue'

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
const endpointApi = 't_bon_spk_lain'
onBeforeMount(() => {
  document.title = 'Transaction Bon SPK Lain-lain'
})


//  @if( $id )------------------- JS CONTENT ! PENTING JANGAN DIHAPUS


// HOT KEY (CTRL+S)
const handleKeyDown = (event) => {
  if (event?.ctrlKey && event?.key === 's' && actionText.value) {
    event.preventDefault();
    onSave();
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown)
  const today = new Date();
  // Format tanggal sesuai dengan "dd-mm-yyyy"
  const day = String(today.getDate()).padStart(2, '0');
  const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
  const year = today.getFullYear();
  const formattedDate = `${day}/${month}/${year}`;
  values.tanggal = formattedDate;
});
onBeforeUnmount(() => { window.removeEventListener('keydown', handleKeyDown) })

let initialValues = {
  status: 'DRAFT'
}
const changedValues = []

let values = reactive({})

const detailArr = ref([]);

function addDetail(rows) {
  const data = [...detailArr.value]
  rows.forEach(row => {
    row.sektor = row.id || '';
    row.catatan = null;
    data.push(row)
  })
  onRetotal(data)
}

const onRetotal = (dArr) => {
  if (dArr) {
    detailArr.value = dArr
  }
}

async function detailBon(no_spk) {
  if (!no_spk || !no_spk.id) {
    // Reset detail arrays
    detailArr.value = [];
    return;
  }

  try {
    const dataURL = `${store.server.url_backend}/operation/t_spk_lain/${no_spk.id}`;
    const params = {
      // join: true,
      // view_tarif: true,
      // transform: false,
      // getSpkFly:true,
      scopes: 'WithDetail'
    };
    const fixedParams = new URLSearchParams(params);
    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });
    if (!res.ok) throw new Error("Gagal saat mencoba membaca data");
    const resultJson = await res.json();
    const initialValues = resultJson.data;

    detailArr.value = [];

    initialValues.t_spk_lain_d?.forEach((itemBon)=>{
      itemBon.t_spk_lain_d_id = itemBon.id
      itemBon.sektor = itemBon['sektor.id'];
      itemBon.catatan = itemBon['catatan'];
      itemBon.sangu = 0;
      itemBon.tambahan = 0;
      itemBon.tagihan = 0;
      detailArr.value=[itemBon, ...detailArr.value];
    });

  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error',
      text: err.message || "Terjadi kesalahan.",
      allowOutsideClick: false,
      confirmButtonText: 'Kembali',
    }).then(() => {
      router.back();
    });
  } finally {
    isRequesting.value = false;
  }
}

const countTotalBon = (sangu, tambahan, bon) => {
  bon = (sangu || 0) + (tambahan || 0)

  return bon
}

const HitungBon = computed(() => {
  let total = 0;

  detailArr.value.forEach((dt) => {
    total += (dt.sangu || 0) + (dt.tambahan || 0)
  });

  return total
})


const HitungTagihan = computed(() => {
  let total_tagihan = 0;

  detailArr.value.forEach((dt) => {
    total_tagihan += (dt.tagihan || 0) 
  });

  return total_tagihan
})


const delDetailArr = async (index) => {
  const result = await swal.fire({
    icon: 'warning',
    text: 'Hapus Data Terpilih?',
    confirmButtonText: 'Yes',
    showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  detailArr.value = detailArr.value.filter((item, i) => (i !== index));
}


const onReset = async (alert = false) => {
  if (alert) {
    const res = await swal.fire({
      icon: 'warning',
      text: 'Anda yakin akan mereset data ini?',
      showDenyButton: true,
      confirmButtonText: 'Ya',
      denyButtonText: 'Tidak',
    }).then((res) => {
      if (res.isConfirmed) { }
    });
  }

  detailArr.value = [];

  if (isRead) {
    for (const key in initialValues) {
      values[key] = initialValues[key];
    }
  } else {
    for (const key in values) {
      delete values[key];
    }
  }

};


onBeforeMount(async () => {
  onReset()
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = { join: true, transform: false, GetData: true }
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
      // initialValues.kode = resultJson.data['m_customer.kode']
      if (actionText.value?.toLowerCase() === 'copy') {
        delete initialValues.id
        initialValues.no_draft = null;
        initialValues.status = 'DRAFT';
        initialValues.no_bsg = null;
      }
      console.log(initialValues)
      if (initialValues.t_bon_spk_lain_d && Array.isArray(initialValues.t_bon_spk_lain_d)) {
        detailArr.value = initialValues.t_bon_spk_lain_d.map(det => ({
          ...det,
          sektor: det['t_spk_lain_d.sektor'],
          sangu: det['sangu'],
          tambahan: det['tambahan'],
          tagihan: det['tagihan'],
          catatan: det['catatan']
        }));
      }

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
})


function onBack() {
  router.replace('/' + modulPath)
}


const removeDetail = async (index) => {
  const result = await swal.fire({
    icon: 'warning',
    text: 'Hapus Data Terpilih?',
    confirmButtonText: 'Yes',
    showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  detailArr.value = detailArr.value.filter((item, i) => (i !== index));
}


async function onSave() {

  const result = await swal.fire({
    icon: 'warning', text: 'Simpan data?', showDenyButton: true,
  });
  if (!result.isConfirmed) return;

  try {
    let next = true
    const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : '/' + route.params.id}`;
    isRequesting.value = true;
    values.t_bon_spk_lain_d = detailArr.value;


    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify(values),
    });

    const responseJson = await res.json();
    // console.log('API Response:', responseJson);
    // console.log(JSON.stringify(values));
    if (!res.ok) {
      const responseJson = await res.json();
      formErrors.value = responseJson.errors || {};
      throw new Error(responseJson.message || "Failed when trying to post data");
    }

    router.replace(`/${modulPath}?reload=${Date.now()}`);
  } catch (err) {
    isBadForm.value = true;
    swal.fire({ icon: 'error', text: err });
  } finally {
    isRequesting.value = false;
  }
}

async function inProcess() {
  swal.fire({
    icon: 'warning',
    text: 'In Process?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',

    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const dataURL = `${store.server.url_backend}/operation/t_bon_spk_lain/inProcess`
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
            throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to In Process")
          } else {
            throw ("Failed when trying to in Process")
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

async function complete() {
  swal.fire({
    icon: 'warning',
    text: 'Complete?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',

    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const dataURL = `${store.server.url_backend}/operation/t_bon_spk_lain/complete`
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
            throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to Complete")
          } else {
            throw ("Failed when trying to Complete")
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
      show: (row) => row.status === 'DRAFT',
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
      click: row => router.push(`${route.path}/${row.id}?${tsId}`)
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      show: (row) => row.status === 'DRAFT',
      click: row => router.push(`${route.path}/${row.id}?action=Edit&${tsId}`)
    },
    {
      icon: 'copy',
      title: "Copy",
      class: 'bg-gray-600 text-light-100',
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
              const dataURL = `${store.server.url_backend}/operation/t_bon_spk_lain/post`
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
                text: `POST data berhasil!`
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
      show: (row) => row['status'] !== 'DRAFT',
      async click(row) {
        try {
          const dataURL = `${store.server.url_backend}/operation/t_bon_spk_lain/print?id=${row.id}`;
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
          window.open(`${store.server.url_backend}/web/bon_spk_lain_lain?id=${row.id}`)
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
    }
  ],
  api: {
    url: `${store.server.url_backend}/operation/${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield: 'this.no_draft, this.no_spk, this.tanggal, genzet.nama, t_buku_order.no_buku_order, m_customer.kode, this.catatan',
    },
    onsuccess(response) {
      response.page = response.current_page
      response.hasNext = response.has_next
      return response
    }
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
      cellClass: ['border-r', '!border-gray-200', 'justify-center',],
      sortable: true,
      resizable: true,
      wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'No. BSG',
      field: 'no_bsg',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-center',],
      sortable: true,
      resizable: true,
      wrapText: true,
      filter: 'ColFilter',
    },

    {
      headerName: 'Tanggal BSG',
      field: 'tanggal',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-center',],
      sortable: true,
      resizable: true,
      wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'No. SPK',
      field: 't_spk_lain_lain.no_spk',
      filter: true,
      sortable: true,
      filter: 'ColFilter',
      autoSize: true,
      wrapText: true,
      cellClass: ['border-r', '!border-gray-200', 'justify-center']
    },
    {
      headerName: 'Operator',
      field: 'operator.nama',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-center',],
      sortable: true,
      resizable: true,
      wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Sangu',
      field: 'total_bon',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-center',],
      sortable: true,
      resizable: true,
      wrapText: true,
      filter: 'ColFilter',
      valueFormatter: (params) => {
      if (params.value) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(params.value);
      }
      return params.value;
    }
    },
    {
      headerName: 'Status',
      field: 'status',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-center',],
      sortable: true,
      resizable: true,
      wrapText: true,
      filter: 'ColFilter',
      cellRenderer: (params) => {
        return params.data['status'] == 1
          ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
          : (params.data['status'] == 'DRAFT' ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'POST' ? `<span class="text-yellow-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`)
          )
      }
    },
  ],
})

// FILTER
const filterButton = ref(null);

function filterShowData(params) {
  filterButton.value = filterButton.value === params ? null : params;
  landing.api.params.where = filterButton.value !== null ? `this.status='${filterButton.value}'` : null;
  apiTable.value.reload();
}

onActivated(() => {
  if (apiTable.value && route.query.reload) {
    apiTable.value.reload();
  }
});

//  @endif -------------------------------------------------END
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))