import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, computed, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

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

// ------------------------------ PERSIAPAN
const endpointApi = '/t_pembayaran_piutang'
onBeforeMount(() => {
  document.title = 'Pembayaran Piutang'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []

onMounted(() => {
  // window.addEventListener('keydown', handleKeyDown);
})

const hitungJatuhTempo = (tanggalStr, top) => {
  // Pisahkan string tanggal dengan separator '/'
  const [day, month, year] = tanggalStr.split('/').map(Number);
  
  // Buat objek Date menggunakan nilai yang dipecah
  const tanggal = new Date(year, month - 1, day); // month - 1 karena bulan diindeks dari 0

  // Tambahkan hari ke tanggal
  tanggal.setDate(tanggal.getDate() + top);

  // Format kembali ke "DD/MM/YYYY"
  const newDay = tanggal.getDate().toString().padStart(2, '0');
  const newMonth = (tanggal.getMonth() + 1).toString().padStart(2, '0'); // month + 1 untuk menyesuaikan dengan format manusia
  const newYear = tanggal.getFullYear();

  return `${newDay}/${newMonth}/${newYear}`;
}

const values = reactive({
  status: 'DRAFT',
  total_amt: 0,
  tanggal: '',
  tanggal_pembayaran: ''
})

const detailArr = ref([]);
const addDetail = () => {
  const tempItem = {
  }
  detailArr.value = [...detailArr.value, tempItem]
}

const onDetailAdd = (e) => {
  e.forEach(row => {
    row.t_tagihan_id = row.id || null
    row.bayar = row.grand_total_amount - row.piutang ?? 0;
    row.total_bayar = 0;
    row.tgl_jt = hitungJatuhTempo(row.tgl, row['customer.top']);
    row.catatan = '';
    detailArr.value.push(row)
  })
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

const hitungTotalAmount = () => {
  let total = 0
  detailArr.value.forEach(dt =>{
    total = total + dt.total_bayar
  }) 
  values.total_amt = total
  return total
}

const sisaPiutang = (item) => {
  let sisa_piutang = item.grand_total_amount - item.total_bayar - item.bayar;
  item.sisa_piutang = sisa_piutang;
  return sisa_piutang
}

const totalPPH = computed (() =>{
  let total_pph = 0;

  detailArr.value.forEach((dt) => {
    let nilai_piutang = (dt.grand_total_amount || 0);
    let pph_amt = ((parseFloat(dt.pph_value || 0) / 100) * nilai_piutang);
    total_pph += pph_amt;
  });

  return total_pph;
})

onBeforeMount(async () => {

  if(actionText.value === 'create' || values.status === 'DRAFT'){
    values.tanggal = getCurrentDateFormatted();
  }
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = { }
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
      detailArr.value = initialValues.t_pembayaran_piutang_d.map((dt) => ({
          ...dt,
          no_tagihan: dt['t_tagihan.no_tagihan'],
          tgl: dt['t_tagihan.tgl'],
          tgl_jt: hitungJatuhTempo(dt['t_tagihan.tgl'], initialValues['customer.top']),
          grand_total_amount: parseFloat(dt['t_tagihan.grand_total_amount']),
          pph_value: dt['pph.deskripsi2']
        }))

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
  let isChanged = false
  for (const key in initialValues) {
    if (values[key] !== initialValues[key]) {
      isChanged = true
      break;
    }
  }

  if (!isChanged) {
    router.replace('/' + modulPath)
    return
  }

  swal.fire({
    icon: 'warning',
    text: 'Buang semua perubahan dan kembali ke list data?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      router.replace('/' + modulPath)
    }
  })
}

function onReset() {
  swal.fire({
    icon: 'warning',
    text: 'Reset this form data?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      for (const key in initialValues) {
        values[key] = initialValues[key]
      }
    }
  })
}

async function onSave(withPost = false) {
  //values.tags = JSON.stringify(values.tags)
  try {
    const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value)
    const url_edit = '/' + route.params.id
    const endUrl = isCreating ? '' : url_edit;
    const is_post = withPost ? '?is_post=true' : '';
    const dataURL = `${store.server.url_backend}/operation${endpointApi}${endUrl}${is_post}`;
    isRequesting.value = true
    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify({...values, t_pembayaran_piutang_d: detailArr.value})

    })

    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json()
        formErrors.value = responseJson.errors || {}
        throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data")
      } else {
        throw ("Failed when trying to post data")
      }
    }
    router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())))
  } catch (err) {
    isBadForm.value = true
    swal.fire({
      icon: 'error',
      text: err
    })
  }
  isRequesting.value = false
}

const getCurrentDateFormatted = () => {
  const date = new Date();
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};


//  @else----------------------- LANDING
const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) => row.status !== 'POST',
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
      // show: () => store.user.data.direktorat==='ADMIN INSTANSI',
      click(row) {
        router.push(`${route.path}/${row.id}?` + tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      show: (row) => row.status !== 'POST',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&` + tsId)
      }
    },
    {
      icon: 'copy',
      title: "Copy",
      class: 'bg-gray-600 text-light-100',
      // show: () => store.user.data.direktorat==='ADMIN INSTANSI',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Copy&` + tsId)
      }
    },
    {
      icon: 'print',
      title: "Cetak",
      class: 'bg-amber-600 text-light-100',
      show: (row) => row.status === 'POST',
      click(row) {
        window.open(`${store.server.url_backend}/web/surat_jalan?export=pdf&size=b5&orientation=potrait&id=${row.id}`)
      }
    },
    {
      icon: 'location-arrow',
      title: "Post Data",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status?.toUpperCase() === 'DRAFT',
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
              const dataURL = `${store.server.url_backend}/operation${endpointApi}/post`
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
                if ([400, 422].includes(res.status)) {
                  const responseJson = await res.json()
                  formErrors.value = responseJson.errors || {}
                  throw new Error(responseJson.message || "Failed when trying to post data")
                } else {
                  throw new Error("Failed when trying to post data")
                }
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
  ],
  api: {
    url: `${store.server.url_backend}/operation${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield: 'this.id, this.nama, this.nama_modul, this.submodul, this.path, this.status',
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
    headerName: 'No Pembayaran',
    field: 'no_pembayaran',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'Tanggal Penerimaan',
    field: 'tanggal',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'Customer',
    field: 'customer.nama_perusahaan',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'Tipe Pembayaran',
    field: 'tipe_pembayaran.deskripsi',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'Total Amount',
    field: 'total_amt',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200'],
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
    cellClass: ['border-r', '!border-gray-200', 'justify-center'],
    sortable: true,
    filter: 'ColFilter',
    cellRenderer: ({ value }) =>
      value === 'DRAFT'
        ? '<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">' + value + '</span>'
        : value === null ? `<span class="text-amber-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Belum Dibuat</span>`
          : `<span class="text-amber-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${value}</span>`
  },
  {
    headerName: 'Catatan',
    field: 'catatan',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  }
  ]
})

const filterButton = ref(null);

function filterShowData(params) {
  filterButton.value = filterButton.value === params ? null : params;
  landing.api.params.where = filterButton.value !== null ? `this.status='${filterButton.value}'` : null;
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

//  @endif -------------------------------------------------END
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))