import { useRouter, useRoute, RouterLink } from 'vue-router'
import { watch, ref, computed, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const actionText = ref(route.params.id === 'create'? 'Create': route.query.action !== 'EditTanggal' ? route.query.action : '');
const actionEditTanggal = ref(route.query.action === 'EditTanggal' ? 'EditTanggal' : false)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const formErrors = ref({})
const tsId = `ts=` + (Date.parse(new Date()))

// ------------------------------ PERSIAPAN
const endpointApi = '/t_komisi_undername'
onBeforeMount(() => {
  document.title = 'Transaksi Komisi Undername'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []

const values = reactive({
  status_id: 'DRAFT',
  nilai_invoice: 0,
  kurs: 0,
  nilai_pabean: 0,
  persentase: 0,
  nilai_pajak_komisi: 0,
  tarif_komisi: 0,
  total_komisi: 0,
  is_edit_tanggal: false,
})

// HOT KEY (CTRL+S)
const handleKeyDown = (event) => {
  if (event?.ctrlKey && event?.key === 's' && (actionText.value || actionEditTanggal.value)) {
    event.preventDefault();
    onSave();
  }
}

const nilaiPabean = computed(() => {
  const pabean = (values.nilai_invoice || 0) * (values.kurs || 0)
  values.nilai_pabean = pabean
  return pabean
})

const TotalKomisi = computed(() => {
  const TK = (values.nilai_pajak_komisi || 0) + (values.tarif_komisi || 0)
  values.total_komisi = TK
  return TK
})

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount);
}

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);

  const today = new Date();
  // Format tanggal sesuai dengan "dd-mm-yyyy"
  const day = String(today.getDate()).padStart(2, '0');
  const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
  const year = today.getFullYear();
  const formattedDate = `${day}/${month}/${year}`;
  values.tanggal = formattedDate;
});

//Perhitungan nilai_invoice
const data_komisi = computed(() => ({
  tipe_tarif: `${values.tipe_tarif}`,
  customer_id: `${values.customer_id}` || null,
  nilai_invoice: `${values.nilai_invoice}`,
}));

watch(data_komisi, async (newVal, oldVal) => {
  try {

    const response = await fetch(`${store.server.url_backend}/operation/t_buku_order/GetPersen`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(newVal),
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const responseJson = await response.json();
    const get_data = responseJson.data;

    if (!get_data) {
      console.warn(
        `Tarif komisi untuk customer dengan ID ${newVal.customer_id} tidak ditemukan. Mohon cek kembali.`
      );
      return;
    }

    // Set data persentase jika data ditemukan
    values.persentase = parseFloat(get_data.persentase) || 0;
  } catch (error) {
    console.error('Terjadi kesalahan saat mengambil data komisi:', error);
  }
});

const TotalPajakKomisi = computed(() => {
  const TPK = (values.nilai_pabean || 0) * (values.persentase / 100 || 0)
  values.nilai_pajak_komisi = TPK
  return TPK
})


onBeforeMount(async () => {

  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = { join: true, transform: true }
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
      initialValues.status_id = initialValues.status_id

      if (actionText.value?.toLowerCase() === 'copy') {
        delete initialValues.kode
      }

      const secondURL = `${store.server.url_backend}/operation/t_buku_order/${initialValues.t_buku_order_id}`;
      const secondParams = {
        tipe_tarif: `${initialValues.tipe_komisi}`,
        // customer_id: `${initialValues.customer_id}`,
        nilai_invoice: `${initialValues.nilai_invoice}`,
        scopes: 'WithDetailAju,GetPersentase',
      };
      const secondFixedParams = new URLSearchParams(secondParams);
      const secondRes = await fetch(secondURL + '?' + secondFixedParams, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`,
        },
      });
      if (!secondRes.ok) throw new Error("Failed when trying to fetch second data");

      const secondResultJson = await secondRes.json();
      initialValues.aju_id = secondResultJson.data['relation_ppjk']?.[0]?.no_aju
      initialValues.pib_id = secondResultJson.data['relation_ppjk']?.[0]?.no_peb_pib
      initialValues.tgl_pib = secondResultJson.data['relation_ppjk']?.[0]?.tanggal_peb_pib
      console.log('Second Fetch Result:', secondResultJson);

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
  } else {
    values.status_id = 'DRAFT'
  }

  for (const key in initialValues) {
    values[key] = initialValues[key]
  }
})

function onBack() {
  router.replace('/' + modulPath)
  return
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
        const newValues = {
          kode_tarif_komisi_undername: '',
          m_cust_id: '',
          tipe_tarif: '',
          tarif_komisi: '',
          keterangan: ''
        };

        for (const key in newValues) {
          if (newValues.hasOwnProperty(key)) {
            values[key] = newValues[key];
          }
        }
      }
    })
  }
}

async function onSave(isSave = false) {
  // Tentukan URL berdasarkan apakah kita mengklik 'Complete' atau 'Save'
  const isComplete = !isSave; // 'Complete' jika isSave false, 'Save' jika isSave true
  
  // Jika klik 'Complete'
  if (isComplete) {
    const result = await swal.fire({
      icon: 'warning', text: `Complete data?`, showDenyButton: true,
    });

    if (!result.isConfirmed) return;

    try {
      const dataURL = `${store.server.url_backend}/operation/t_komisi_undername/complete`;
      isRequesting.value = true;

      const res = await fetch(dataURL, {
        method: 'POST',
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        body: JSON.stringify({ id: values.id })
      });

      if (!res.ok) {
        if ([400, 422, 500].includes(res.status)) {
          const responseJson = await res.json();
          formErrors.value = responseJson.errors || {};
          throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to post data");
        } else {
          throw ("Failed when trying to post data");
        }
      }

      router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())));

      const responseJson = await res.json();
      swal.fire({
        icon: 'success',
        text: responseJson.messag + ' Data Completed'
      });z
    } catch (err) {
      isBadForm.value = true;
      swal.fire({
        icon: 'error',
        text: err
      });
    }
    isRequesting.value = false;
    apiTable.value.reload();
  } else {
    // Jika klik 'Save'
    const result = await swal.fire({
      icon: 'warning', text: `Save data?`, showDenyButton: true,
    });

    if (!result.isConfirmed) return;

    try {
      const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
      const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? isSave ? '?post=true' : '' : isSave ? '/' + route.params.id + '?post=true' : '/' + route.params.id}`;
      isRequesting.value = true;

      // Assign status_id to values before sending the request
      values.status_id = values.status_id;

      if (actionEditTanggal.value == 'EditTanggal') values.is_edit_tanggal = true;

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
          throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data");
        } else {
          throw ("Failed when trying to post data");
        }
      }

      router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())));
    } catch (err) {
      isBadForm.value = true;
      swal.fire({
        icon: 'error',
        text: err
      });
    }
    isRequesting.value = false;
  }
}

//  @else----------------------- LANDING


const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) => row['status_id'] == 'DRAFT',
      click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Hapus Data Terpilih?',
          confirmButtonText: 'Yes',
          showDenyButton: true,
        }).then(async (result) => {
          if (result.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation${endpointApi}/${row.id}`
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
      // show: (row) => (currentMenu?.can_read)||store.user.data.username==='developer',
      click(row) {
        router.push(`${route.path}/${row.id}?` + tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      show: (row) => row['status_id'] === 'DRAFT',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&` + tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      show: (row) => row['status_id'] == 'POST' && row.is_edit_tanggal != true,
      click: row => router.push(`${route.path}/${row.id}?action=EditTanggal&${tsId}`),
    },
    {
      icon: 'location-arrow',
      title: "Post Data",
      class: 'bg-rose-700 text-white',
      show: (row) => row['status_id'] == 'DRAFT',
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
              const dataURL = `${store.server.url_backend}/operation/t_komisi_undername/post`
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
      icon: 'copy',
      title: "Copy",
      class: 'bg-gray-600 text-light-100',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Copy&` + tsId)
      }
    },
    {
      icon: 'paper-plane',
      title: "Finish Data",
      class: 'bg-amber-400 text-white',
      show: (row) => row['status_id'] == 'POST',
      async click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Finish Data?',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',
          showDenyButton: true
        }).then(async (res) => {
          if (res.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/t_komisi_undername/complete`
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
  api: {
    url: `${store.server.url_backend}/operation${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield: 'this.no_komisi_undername, customer.nama_perusahaan, this.tipe_komisi, this.tarif_komisi, this.catatan, this.status_id'
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
    field: 'no_komisi_undername',
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
    field: 'customer.nama_perusahaan',
    headerName: 'Customer',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start'],
  },
  {
    field: 'tipe_komisi',
    headerName: 'Tipe Tarif',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'tarif_komisi',
    headerName: 'Tarif Komisi',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-start'],
    valueFormatter: (params) => {

      if (params.value) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(params.value);
      }
      return params.value;
    }
  },
  {
    field: 'catatan',
    headerName: 'Keterangan',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
      headerName: 'Status',
      field: 'status_id',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      resizable: true,
      wrapText: true,
      filter: 'ColFilter',
      cellRenderer: (params) => {
        return params.data['status_id'] == 1
          ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status_id']?.toUpperCase()}</span>`
          : (params.data['status_id'] == 'DRAFT' ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status_id']?.toUpperCase()}</span>`
            : (params.data['status_id'] == 'POST' ? `<span class="text-yellow-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status_id']?.toUpperCase()}</span>`
                : (params.data['status_id'] == 'COMPLETED' ? `<span class="text-pink-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status_id']?.toUpperCase()}</span>`
                  : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status_id']?.toUpperCase()}</span>`))
          )
      }
    }
  ]
})


const filterButton = ref(null);

function filterShowData(params) {
  filterButton.value = filterButton.value === params ? null : params;
  landing.api.params.where = filterButton.value !== null ? `this.status_id='${filterButton.value}'` : null;
  apiTable.value.reload();
}

onActivated(() => {
  // Reload table api landing
  if (apiTable.value) {
    if (route.query.reload) {
      apiTable.value.reload();
    }
  }
});

//  @endif -------------------------------------------------END
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))