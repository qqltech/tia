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
const idMulti = ref([])
const isApproval = route.query.is_approval;

let coaListEks = reactive([]);
let coaListImp = reactive([]);

const tipe_order_id = ref('');
const tipe_order = ref('');
const tipe_kategori_id = ref('');
const isModalOpen = ref(false);

// ENDPOINT API
const endpointApi = 't_bon_dinas_luar'
onBeforeMount(() => {
  document.title = 'Transaction Bon Dinas Luar'
})


// @if( !$id ) | --- LANDING TABLE --- |
const onDetailAdd = (e) => {
  const newIds = e.map(row => row.id);
  idMulti.value = [...new Set([...idMulti.value, ...newIds])]

  multiPost()
  console.log('INI MULTI ID (no duplicate) :', idMulti.value);
}

async function multiPost() {
  const result = await swal.fire({
    icon: 'warning',
    text: 'Send Multi Post?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',
    showDenyButton: true
  })

  if (!result.isConfirmed) return

  try {
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}/multiple_post`
    isRequesting.value = true

    const response = await fetch(dataURL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify({ items: idMulti.value })
    })

    const responseJson = await response.json()

    if (!response.ok) {
      const message = responseJson.message || 'Failed to Send Post'
      const errorText = responseJson.data?.errorText || ''
      formErrors.value = responseJson.errors || {}
      throw `${message} ${errorText}`
    }

    await swal.fire({
      icon: 'success',
      text: responseJson.message || 'Post successful'
    })
    
    idMulti.value = []
    router.replace('/' + modulPath)

  } catch (err) {
    isBadForm.value = true
    await swal.fire({
      icon: 'error',
      iconColor: '#1469AE',
      confirmButtonColor: '#1469AE',
      text: err.toString()
    })
  } finally {
    isRequesting.value = false
  }
}

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
      searchfield: 'this.id, this.no_draft, this.total_amt, this.no_bon_dinas_luar, this.catatan',
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
    headerName: 'No. Bon Dinas Luar',
    field: 'no_bon_dinas_luar',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-center']
  },
  {
    headerName: 'Tanggal Bon Dinas Luar',
    field: 'tanggal',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-center']
  },
  {
    headerName: 'Nominal',
    field: 'total_amt',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-end'],
    valueFormatter: (params) => {
      const value = Number(params.value) || 0;
      return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
    }
  },
  {
    headerName: 'Catatan',
    field: 'catatan',
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
          : (params.data['status'] == 'POST' ? `<span class="text-amber-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'PRINTED' ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`)))
    }
  },
  ],
  actions: [
    { title: 'Hapus', icon: 'trash', class: 'bg-red-600 text-light-100', show: (row) => row.status === 'DRAFT', click: deleteData },
    {
      title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?${tsId}`)
    },
    {
      title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100',
      show: (row) => row.status === 'DRAFT',
      click: row => router.push(`${route.path}/${row.id}?action=Edit&${tsId}`)
    },
    {
      title: 'Copy', icon: 'copy', class: 'bg-gray-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Copy&${tsId}`)
    },
    {
      icon: 'location-arrow',
      title: "Post Data",
      class: 'bg-rose-700  text-white',
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
      icon: 'rocket',
      title: "Cetak Thermal",
      class: 'bg-violet-600 text-light-100',
      show: (row) => (row['status'] == 'POST' || row['status'] == 'APPROVED'),
      async click(row) {
        try {
          console.log(row.id, 'id Bon print')
          await tesPrint(row.id);
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
    {
      icon: 'location-arrow',
      title: "Send for approval",
      class: 'bg-rose-700 text-white',
      show: (row) => row.status === 'PRINTED',
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
                  throw ("Failed when trying to approval data")
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

const defaultThermal = reactive({});
const thermal = reactive({});

onBeforeMount(async () => {

  const initThermal = {
    interface: 'POS-80',
    port: '9000',
    url: '/print/custom'
  }

  for (const key in initThermal) {
    defaultThermal[key] = initThermal[key]
    thermal[key] = initThermal[key]
  }

  if (localStorage.getItem('thermal_interface')) {
    thermal.interface = localStorage.getItem('thermal_interface')
  }
  if (localStorage.getItem('thermal_port')) {
    thermal.port = localStorage.getItem('thermal_port')
  }
})

function terbilang(number) {
  const huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
  let temp = '';

  if (number < 12) {
    temp = huruf[number];
  } else if (number < 20) {
    temp = terbilang(number - 10) + ' Belas';
  } else if (number < 100) {
    temp = terbilang(Math.floor(number / 10)) + ' Puluh ' + terbilang(number % 10);
  } else if (number < 200) {
    temp = 'Seratus ' + terbilang(number - 100);
  } else if (number < 1000) {
    temp = terbilang(Math.floor(number / 100)) + ' Ratus ' + terbilang(number % 100);
  } else if (number < 2000) {
    temp = 'Seribu ' + terbilang(number - 1000);
  } else if (number < 1000000) {
    temp = terbilang(Math.floor(number / 1000)) + ' Ribu ' + terbilang(number % 1000);
  } else if (number < 1000000000) {
    temp = terbilang(Math.floor(number / 1000000)) + ' Juta ' + terbilang(number % 1000000);
  }

  return temp.trim();
}

function getCurrentDateTime() {
  const now = new Date();

  const day = String(now.getDate()).padStart(2, '0');
  const month = String(now.getMonth() + 1).padStart(2, '0'); // Januari = 0
  const year = now.getFullYear();

  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  const seconds = String(now.getSeconds()).padStart(2, '0');

  return `${day}/${month}/${year}, ${hours}:${minutes}:${seconds}`;
}

function number_format(number, decimals = 0, decPoint = ',', thousandsSep = '.') {
  if (isNaN(number) || number === null) return '0';

  const fixedNumber = Number(number).toFixed(decimals);
  const parts = fixedNumber.split('.');

  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSep);

  return parts.length > 1 ? parts.join(decPoint) : parts[0];
}

async function tesPrint(bon_dinas_luar_id) {
  isRequesting.value = true;

  let bon_dl_data = [];
  let fieldData1 = null;
  let fieldData2 = null;  // Ensure fieldData is initialized properly

  try {
    // Fetch print data
    const URLdata = `${store.server.url_backend}/operation/t_bon_dinas_luar/updatePrintData`;
    const parameter = {
      join: true,
      transform: true,
      t_bon_dinas_luar_id: `${bon_dinas_luar_id}`,
    };
    const fixParams = new URLSearchParams(parameter);

    const response = await fetch(`${URLdata}?${fixParams}`, {
      method: "GET", // Explicitly define method
      headers: {
        "Content-Type": "application/json",
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });

    if (!response.ok) throw new Error("Gagal saat mencoba membaca data");

    // Fetch print data
    const dataURL = `${store.server.url_backend}/operation/t_bon_dinas_luar/getPrintData`;
    const params = {
      join: true,
      transform: true,
      t_bon_dinas_luar_id: `${bon_dinas_luar_id}`,
    };
    const fixedParams = new URLSearchParams(params);


    const res = await fetch(`${dataURL}?${fixedParams}`, {
      method: "GET", // Explicitly define method
      headers: {
        "Content-Type": "application/json",
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });


    if (!res.ok) throw new Error("Gagal saat mencoba membaca data");

    const resultJson = await res.json();
    bon_dl_data = resultJson;

    // Prepare fieldData after fetching bon_dl_data
    const bonData = bon_dl_data?.data ?? {};

    console.log(bonData, 'ini respon')

    //Fieldata 1
    fieldData1 = {
      interface: thermal.interface,
      data: [
        { "type": "alignCenter" }, { "type": "setTextDoubleHeight" }, { "type": "println", "value": "BON DINAS LUAR" },
        { "type": "setTextNormal" }, { "type": "alignLeft" }, { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": `Tanggal : ${bonData?.bdl_tanggal}`, "align": "LEFT", "cols": 24 }
          ]
        },
        { "type": "drawLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Tipe Ord", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${bonData?.deskripsi}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Tipe Kat", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${bonData?.nama_coa}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No. BDL", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${bonData?.no_bon_dinas_luar}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Karyawan", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${bonData?.nip}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Nama Supp", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${bonData?.nama}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Catatan", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${bonData?.bdl_catatan}`, "align": "LEFT", "cols": 36 }
          ]
        },
        { "type": "drawLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No.", "align": "LEFT", "cols": 4 },
            { "text": "No Order", "align": "LEFT", "cols": 10 },
            { "text": "Ket", "align": "CENTER", "cols": 16 },
            { "text": "Sub Total", "align": "RIGHT", "cols": 16 }
          ]
        },
        { "type": "drawLine" },
        ...bonData?.t_bon_dinas_luar_d?.map((dt, idx) => ({
          "type": "tableCustom",
          "value": [
            { "text": idx + 1, "align": "LEFT", "cols": 4 },
            { "text": dt?.no_buku_order, "align": "LEFT", "cols": 10 },
            { "text": dt?.keterangan, "align": "CENTER", "cols": 16 },
            { "text": `Rp ${number_format(parseFloat(dt?.sub_total), 0, ',', '.')}`, "align": "RIGHT", "cols": 16 }
          ]
        })),
        { "type": "drawLine" },
        { "type": "setTextNormal" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Total Amount :", "align": "LEFT", "cols": 15 },
            { "text": " ", "align": "CENTER", "cols": 3 },
            { "text": `Rp ${number_format(parseFloat(bonData?.bdl_total_amt), 0, ',', '.')}`, "align": "LEFT", "cols": 21 }
          ]
        },
        { "type": "drawLine" },
        { "type": "println", "value": "Mengetahui," },
        { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Karyawan", "align": "CENTER", "cols": 15 },
            { "text": "Kasir", "align": "CENTER", "cols": 16 },
            { "text": "Supplier", "align": "CENTER", "cols": 22 }
          ]
        },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": `(${bonData?.nip})`, "align": "CENTER", "cols": 15 },
            { "text": `(${bonData?.user_print?.name})`, "align": "CENTER", "cols": 16 },
            { "text": `(${bonData?.nama})`, "align": "CENTER", "cols": 22 }
          ]
        },
        { "type": "newLine" },
        { "type": "println", "value": `Dicetak pada tanggal : ${getCurrentDateTime()}` },
        { "type": "println", "value": `Operator : ${bonData?.user_print?.name}-PC # ${bonData?.user_print?.nip}` },
        { "type": "println", "value": `Sudah di print : ${bonData?.jumlah_print}x` },
        { "type": "newLine" },
        { "type": "newLine" },
        {
          "type": "tableCustom", "value": [
            { "text": "", "align": "RIGHT", "cols": 16 },
            { "text": "", "align": "RIGHT", "cols": 13 },
            { "text": "RANGKAP 1", "align": "RIGHT", "cols": 16 }
          ]
        },
        { "type": "cut" }
      ]
    }

    //Fieldata 2
    fieldData2 = {
      interface: thermal.interface,
      data: [
        { "type": "alignCenter" }, { "type": "setTextDoubleHeight" }, { "type": "println", "value": "BON DINAS LUAR" },
        { "type": "setTextNormal" }, { "type": "alignLeft" }, { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": `Tanggal : ${bonData?.bdl_tanggal}`, "align": "LEFT", "cols": 24 }
          ]
        },
        { "type": "drawLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Tipe Ord", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${bonData?.deskripsi}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Tipe Kat", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${bonData?.nama_coa}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No. BDL", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${bonData?.no_bon_dinas_luar}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Karyawan", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${bonData?.nip}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Nama Supp", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${bonData?.nama}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Catatan", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${bonData?.bdl_catatan}`, "align": "LEFT", "cols": 36 }
          ]
        },
        { "type": "drawLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No.", "align": "LEFT", "cols": 4 },
            { "text": "No Order", "align": "LEFT", "cols": 10 },
            { "text": "Ket", "align": "CENTER", "cols": 16 },
            { "text": "Sub Total", "align": "RIGHT", "cols": 16 }
          ]
        },
        { "type": "drawLine" },
        ...bonData?.t_bon_dinas_luar_d?.map((dt, idx) => ({
          "type": "tableCustom",
          "value": [
            { "text": idx + 1, "align": "LEFT", "cols": 4 },
            { "text": dt?.no_buku_order, "align": "LEFT", "cols": 10 },
            { "text": dt?.keterangan, "align": "CENTER", "cols": 16 },
            { "text": `Rp ${number_format(parseFloat(dt?.sub_total), 0, ',', '.')}`, "align": "RIGHT", "cols": 16 }
          ]
        })),
        { "type": "drawLine" },
        { "type": "setTextNormal" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Total Amount :", "align": "LEFT", "cols": 15 },
            { "text": " ", "align": "CENTER", "cols": 3 },
            { "text": `Rp ${number_format(parseFloat(bonData?.bdl_total_amt), 0, ',', '.')}`, "align": "LEFT", "cols": 21 }
          ]
        },
        { "type": "drawLine" },
        { "type": "println", "value": "Mengetahui," },
        { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Karyawan", "align": "CENTER", "cols": 15 },
            { "text": "Kasir", "align": "CENTER", "cols": 16 },
            { "text": "Supplier", "align": "CENTER", "cols": 22 }
          ]
        },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": `(${bonData?.nip})`, "align": "CENTER", "cols": 15 },
            { "text": `(${bonData?.user_print?.name})`, "align": "CENTER", "cols": 16 },
            { "text": `(${bonData?.nama})`, "align": "CENTER", "cols": 22 }
          ]
        },
        { "type": "newLine" },
        { "type": "println", "value": `Dicetak pada tanggal : ${getCurrentDateTime()}` },
        { "type": "println", "value": `Operator : ${bonData?.user_print?.name}-PC # ${bonData?.user_print?.nip}` },
        { "type": "println", "value": `Sudah di print : ${bonData?.jumlah_print}x` },
        { "type": "newLine" },
        { "type": "newLine" },
        {
          "type": "tableCustom", "value": [
            { "text": "", "align": "RIGHT", "cols": 16 },
            { "text": "", "align": "RIGHT", "cols": 13 },
            { "text": "RANGKAP 2", "align": "RIGHT", "cols": 16 }
          ]
        },
        { "type": "cut" }
      ]
    }

  } catch (err) {
    console.error("Error fetching print data:", err);
    isBadForm.value = true;
    swal.fire({
      icon: "error",
      text: err.message || "Terjadi kesalahan.",
      allowOutsideClick: false,
      confirmButtonText: "Kembali",
    });
    isRequesting.value = false;
    return; // Stop execution if fetching data fails
  }

  try {
    const printURL = `http://localhost:${thermal.port}${thermal.url}`;
    const printOptions1 = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(fieldData1),
    };

    const printOptions2 = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(fieldData2),
    };

    // Cetak dua kali
    const response1 = await fetch(printURL, printOptions1);
    if (!response1.ok) throw new Error(`Cetakan pertama gagal: ${response1.status}`);

    const response2 = await fetch(printURL, printOptions2);
    if (!response2.ok) throw new Error(`Cetakan kedua gagal: ${response2.status}`);

    swal.fire({
      icon: "success",
      text: "Print berhasil!",
    });

  } catch (error) {
    console.error("Print request error:", error);
    swal.fire({
      icon: "error",
      text: "Print gagal, periksa kembali pengaturan atau perangkat Anda!",
    });

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


onBeforeMount(async () => {
  const dataURL = `${store.server.url_backend}/operation/m_coa`;
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
  await fetchData(dataURL, {
    simplest: true,
    transform: false,
    join: false,
    where: 'this.is_active=true AND id IN (99, 114, 111, 101, 121, 209, 336, 337, 125, 193)'
  }).then((res) => {
    coaListEks.push(...res.data);
  });
  console.log(coaListEks);

  await fetchData(dataURL, {
    simplest: true,
    transform: false,
    join: false,
    where: 'this.is_active=true AND id IN (180, 169, 209)'
  }).then((res) => {
    coaListImp.push(...res.data);
  });
  console.log(coaListImp);
})


const openModal = (to_id, to) => {
  tipe_order_id.value = to_id;
  tipe_order.value = to;
  isModalOpen.value = true;
  console.log("ASAAAAAAAAAAa", tipe_order_id.value, tipe_order.value)
}

const setTipeKategori = (tk_id) => {
  tipe_kategori_id.value = tk_id;
  console.log("RRRRRRR", tk_id, tipe_kategori_id.value)
}

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
  data: { status: 'DRAFT', no_draft: 'Generate by System', no_bkk: 'Generate by System', tipe_order_id: route.query.tipe_order_id, tipe_kategori_id: route.query.tipe_kategori_id },
  detail: []
}

const data = reactive({ ...default_value.data });
const detail = reactive({ data: [...default_value.detail] });

const initArr = {
  catatan: ''
}
const detailArr = reactive([]);

// GET DATA FROM API
onBeforeMount(async () => {
  if (actionText.value === 'Create' || data.status === 'DRAFT') {
    data.tanggal = getCurrentDateFormatted();
  }

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
      await fetchData(dataApprovalURL, { join: false, transform: false }).then((res) => {
        trx_id = res.data.trx_id;
        console.log(res, trx_id);
      });
    }

    const editedId = route.params.id;
    const dataURL = trx_id ? `${store.server.url_backend}/operation/${endpointApi}/${trx_id}` : `${store.server.url_backend}/operation/${endpointApi}/${editedId}`;
    isRequesting.value = true;

    // FETCH HEADER DATA
    await fetchData(dataURL, { join: true, transform: false }).then((res) => {
      // default_value.data = res.data;
      detailArr.push(...res.data.t_bon_dinas_luar_d);
      for (const key in res.data) {
        data[key] = res.data[key];
      }
      if (actionText.value == 'Copy') {
        data.no_draft = default_value.data.no_draft;
        data.no_bkk = default_value.data.no_bkk;
        data.status = default_value.data.status;
        data.tanggal = getCurrentDateFormatted();
        data.jumlah_print = 0;
        data.is_printed = 0;
      }
    });
    for (let idx = 0; idx < detailArr.length; idx++) {
      detailArr[idx].nomor = detailArr[idx]['m_coa.nomor'];
      detailArr[idx].nama_coa = detailArr[idx]['m_coa.nama_coa'];
      detailArr[idx].no_buku_order = detailArr[idx]['t_buku_order.no_buku_order'];
    }

    // detailArr.value = data.t_bon_dinas_luar_d.map((dt) => ({
    //   ...dt,
    //   no_buku_order: dt['t_buku_order.no_buku_order'],
    // }));

    // console.log(detailArr.value, 'hasil mapping detailArr');

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
        if (isCreating) {
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
              t_bon_dinas_luar_d: detailArr,
            }),
          });
          if (res.ok) {
            const responseData = await res.json(); // Parse the JSON body
            console.log('Response Data:', responseData);

            // Access the id from the parsed response
            data.id = responseData.id;
          } else {
            console.error('Failed to fetch:', res.status, res.statusText);
          }
        }
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
      router.replace('/' + modulPath);
    }
  })
}


watch(() => detailArr, () => {
  data.total_amt = 0;
  for (let idx = 0; idx < detailArr.length; idx++) {
    if (detailArr[idx].sub_total != undefined) {
      data.total_amt += Number(detailArr[idx].sub_total)
    }
  }

}, { deep: true })

function setPerkiraan(id) {
  if (actionText.value) {
    data.m_coa_id = id;
  }
}

// function setDetail() {
//   if (data.m_coa_id && actionText.value) {
//     const idx = coaListEks.findIndex(cl => cl.id == data.m_coa_id);
//     detailArr.splice(0, 100);
//     const coa = {
//       t_bon_dinas_luar_id: data.id || 0,
//       m_coa_id: coaListEks[idx].id,
//       nomor: coaListEks[idx].nomor,
//       nama_coa: coaListEks[idx].nama_coa,
//     }
//     detailArr.push(coa);

//   }
// }


async function onSave() {
  const result = await swal.fire({
    icon: 'warning', text: 'Simpan data?', showDenyButton: true,
  });

  // data.tipe_bkk = 'Non Kasbon';
  // data.m_coa_id = 0;

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
        t_bon_dinas_luar_d: detailArr,
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




const getCurrentDateFormatted = () => {
  const date = new Date();
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};
//  @endif | --- END --- |
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))