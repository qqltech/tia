import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const actionText = ref(route.params.id === 'create' ? 'Create' : route.query.action != 'EditBerkas' ? route.query.action : false)
const actionEditBerkas = ref(route.query.action === 'EditBerkas' ? 'EditBerkas' : false)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const formErrors = ref({})
const tsId = `ts=` + (Date.parse(new Date()))
const is_approval = route.query.is_approval ? true : false
const is_to_upload = route.query.is_to_upload ? true : false
let isApproved = ref(false)
let modalOpen = ref(false)
let isFinish = ref(false)
let dataLog = reactive({ items: [] })
const tableKey = ref(0)

// ------------------------------ PERSIAPAN
const endpointApi = '/t_surat_jalan'
onBeforeMount(() => {
  document.title = 'Transaksi Surat Jalan'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []

const handleKeyDown = (event) => {
  if (event?.ctrlKey && event?.key === 's' && (actionText.value || actionEditBerkas.value)) {
    event.preventDefault();
    onSave();
  }
}

onMounted(() => {
  // window.addEventListener('keydown', handleKeyDown);

  const today = new Date();
  // Format tanggal sesuai dengan "dd-mm-yyyy"
  const day = String(today.getDate()).padStart(2, '0');
  const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
  const year = today.getFullYear();
  const formattedDate = `${day}/${month}/${year}`;
  values.tanggal = formattedDate;
  values.tanggal_berangkat = formattedDate;
})

const values = reactive({
  status: 'DRAFT',
  is_edit_berkas: false,
})

onBeforeMount(async () => {
  // tampilkan default direktorat dengan store user comp.nama
  values.company = store.user.data?.company

  if (isRead) {
    //  READ DATA
    try {
      let dataURL = ''
      let dataURLAprv = ''
      let resAprv = ''
      if (route.query.is_approval) {
        dataURLAprv = `${store.server.url_backend}/operation/t_surat_jalan/detail?id=${route.params.id}`
        isRequesting.value = true
        const apiApp = await fetch(dataURLAprv, {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        const resultJson = await apiApp.json()
        console.log(resultJson.data)
        const apiTrx = await fetch(`${store.server.url_backend}/operation${endpointApi}/${resultJson.data.approval.trx_id}`, {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        if (!apiTrx.ok || !apiApp.ok) throw new Error("Failed when trying to read data")

      } else {
        const editedId = route.params.id
        dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
        isRequesting.value = true
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

        if (actionText.value === 'Copy') {
          initialValues.status = 'DRAFT';
          initialValues.no_surat_jalan = null;
          initialValues.no_draft = null;
          initialValues.jumlah_print = 0;
          initialValues.is_printed = 0;
        }

        if (initialValues.status !== 'DRAFT') {
          actionText.value = false
        }
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
          values()
        }
      }
    })
  }

  setTimeout(() => {
    values()
  }, 100)
}

async function onSave(isPost = false) {
  //values.tags = JSON.stringify(values.tags)
  const result = await swal.fire({
    icon: 'warning', text: `${isPost ? 'Post ' : 'Simpan'} data?`, showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  try {
    const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value)
    const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? isPost ? '?post=true' : '' : isPost ? '/' + route.params.id + '?post=true' : '/' + route.params.id}`;
    isRequesting.value = true;

    if (actionEditBerkas.value == 'EditBerkas') values.is_edit_berkas = true;

    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',

      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(values)

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

//  @else----------------------- LANDING

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

async function tesPrint(surat_jalan_id) {
  isRequesting.value = true;

  let surat_jalan = [];
  let fieldData1 = null;
  let fieldData2 = null; // Ensure fieldData is initialized properly

  try {
    
    // Fetch print data
    const URLdata = `${store.server.url_backend}/operation/t_surat_jalan/updatePrintData`;
    const parameter = {
      join: true,
      transform: true,
      t_sj_id: `${surat_jalan_id}`,
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
    const dataURL = `${store.server.url_backend}/operation/t_surat_jalan/getPrintData`;
    const params = {
      join: true,
      transform: true,
      t_sj_id: `${surat_jalan_id}`,
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
    surat_jalan = resultJson;

    // Prepare fieldData after fetching surat_jalan
    const suratJalanData = surat_jalan?.data[0] ?? {};

    // Fieldata 1
    fieldData1 = {
      interface: thermal.interface,
      data: [
        { "type": "alignCenter" }, { "type": "setTextDoubleHeight" }, { "type": "println", "value": "SURAT JALAN" },
        { "type": "setTextNormal" }, { "type": "alignLeft" }, { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": `Tgl SJ : ${suratJalanData?.tanggal}`, "align": "LEFT", "cols": 20 },
            { "text": `Tgl Berangkat : ${suratJalanData?.tanggal_berangkat}`, "align": "RIGHT", "cols": 28 }
          ]
        },
        { "type": "drawLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No. Order", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.no_buku_order}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No. SJ", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.no_surat_jalan}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No. Cont", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.no_container}`, "align": "LEFT", "cols": 36 }
          ]
        },
        //Space
        {
          "type": "tableCustom",
          "value": [
            { "text": "", "align": "LEFT", "cols": 9 },
            { "text": "", "align": "CENTER", "cols": 3 },
            { "text": "", "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Jns Cont", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.jenis_container_kode}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Tipe SJ", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.tipe_order_sj}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Pelabuhan", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.pelabuhan_kode}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Kapal", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.nama_kapal}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Isi Cont", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.jenis_sj_deskripsi}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Uk. Cont", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.ukuran_container}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No. Seal", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.no_seal}`, "align": "LEFT", "cols": 36 }
          ]
        },
        //Space
        {
          "type": "tableCustom",
          "value": [
            { "text": "", "align": "LEFT", "cols": 9 },
            { "text": "", "align": "CENTER", "cols": 3 },
            { "text": "", "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Lok Bngkr", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.lokasi_stuffing}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Depo", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.depo_kode}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Temp Cont", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": ``, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "NW", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.nw}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "GW", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.gw}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Catatan", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.catatan}`, "align": "LEFT", "cols": 36 },
          ]
        },
        { "type": "drawLine" },
        { "type": "println", "value": "Mengetahui," },
        { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Penerima", "align": "CENTER", "cols": 14 },
            { "text": "Pembawa", "align": "CENTER", "cols": 18 },
            { "text": "Pengirim", "align": "CENTER", "cols": 17 }
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
            { "text": "(           )", "align": "CENTER", "cols": 15 },
            { "text": "(           )", "align": "CENTER", "cols": 16 },
            { "text": "(           )", "align": "CENTER", "cols": 20 }
          ]
        },
        { "type": "drawLine" },
        { "type": "newLine" },
        { "type": "println", "value": `Dicetak pada tanggal : ${getCurrentDateTime()}` },
        { "type": "println", "value": `Operator : ${surat_jalan?.user_print?.name}-PC # ${surat_jalan?.user_print?.nip}` },
        { "type": "println", "value": `Sudah di print : ${suratJalanData?.jumlah_print}x` },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "tableCustom", "value": [
          { "text": "", "align": "RIGHT", "cols": 16 },
          { "text": "", "align": "RIGHT", "cols": 13 },
          { "text": "RANGKAP 1", "align": "RIGHT", "cols": 16 }
        ] },
        { "type": "cut" }
      ]
    }

    // Fieldata 2
    fieldData2 = {
      interface: thermal.interface,
      data: [
        { "type": "alignCenter" }, { "type": "setTextDoubleHeight" }, { "type": "println", "value": "SURAT JALAN" },
        { "type": "setTextNormal" }, { "type": "alignLeft" }, { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": `Tgl SJ : ${suratJalanData?.tanggal}`, "align": "LEFT", "cols": 20 },
            { "text": `Tgl Berangkat : ${suratJalanData?.tanggal_berangkat}`, "align": "RIGHT", "cols": 28 }
          ]
        },
        { "type": "drawLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No. Order", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.no_buku_order}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No. SJ", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.no_surat_jalan}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No. Cont", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.no_container}`, "align": "LEFT", "cols": 36 }
          ]
        },
        //Space
        {
          "type": "tableCustom",
          "value": [
            { "text": "", "align": "LEFT", "cols": 9 },
            { "text": "", "align": "CENTER", "cols": 3 },
            { "text": "", "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Jns Cont", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.jenis_container_kode}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Tipe SJ", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.tipe_order_sj}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Pelabuhan", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.pelabuhan_kode}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Kapal", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.nama_kapal}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Isi Cont", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.jenis_sj_deskripsi}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Uk. Cont", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.ukuran_container}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No. Seal", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.no_seal}`, "align": "LEFT", "cols": 36 }
          ]
        },
        //Space
        {
          "type": "tableCustom",
          "value": [
            { "text": "", "align": "LEFT", "cols": 9 },
            { "text": "", "align": "CENTER", "cols": 3 },
            { "text": "", "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Lok Stuff", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.lokasi_stuffing}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Depo", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.depo_kode}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Temp Cont", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": ``, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "NW", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.nw}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "GW", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.gw}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Catatan", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${suratJalanData?.catatan}`, "align": "LEFT", "cols": 36 },
          ]
        },
        { "type": "drawLine" },
        { "type": "println", "value": "Mengetahui," },
        { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Penerima", "align": "CENTER", "cols": 14 },
            { "text": "Pembawa", "align": "CENTER", "cols": 18 },
            { "text": "Pengirim", "align": "CENTER", "cols": 17 }
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
            { "text": "(           )", "align": "CENTER", "cols": 15 },
            { "text": "(           )", "align": "CENTER", "cols": 16 },
            { "text": "(           )", "align": "CENTER", "cols": 20 }
          ]
        },
        { "type": "drawLine" },
        { "type": "newLine" },
        { "type": "println", "value": `Dicetak pada tanggal : ${getCurrentDateTime()}` },
        { "type": "println", "value": `Operator : ${surat_jalan?.user_print?.name}-PC # ${surat_jalan?.user_print?.nip}` },
        { "type": "println", "value": `Sudah di print : ${suratJalanData?.jumlah_print}x` },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "tableCustom", "value": [
          { "text": "", "align": "RIGHT", "cols": 16 },
          { "text": "", "align": "RIGHT", "cols": 13 },
          { "text": "RANGKAP 2", "align": "RIGHT", "cols": 16 }
        ] },
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

const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) => row['status'] === 'DRAFT',
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
      click(row) {
        router.push(`${route.path}/${row.id}?` + tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      show: (row) => row['status'] === 'DRAFT',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&` + tsId)
      }
    },
    {
      title: 'Edit',
      icon: 'edit',
      class: 'bg-blue-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=EditBerkas&${tsId}`),
      show: (row) => row.status == 'PRINTED' && row.is_edit_berkas != true
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
      show: (row) => row['status'] === 'DRAFT',
      async click(row) {
        const confirmResult = await swal.fire({
          icon: 'warning',
          text: 'Post Data?',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',
          showDenyButton: true
        });

        if (confirmResult.isConfirmed) {
          try {
            const dataURL = `${store.server.url_backend}/operation/t_surat_jalan/post?id=${row.id}`;
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
                throw new Error("Failed when trying to post data");
              }
            }

            const responseJson = await response.json();
            swal.fire({
              icon: 'success',
              text: responseJson?.message || 'POSTED'
            });

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
    },
    {
      icon: 'print',
      title: "Cetak",
      class: 'bg-amber-600 text-light-100',
      show: (row) => row['status'] !== 'DRAFT',
      async click(row) {
        try {
          const dataURL = `${store.server.url_backend}/operation/t_surat_jalan/print?id=${row.id}`;
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
          window.open(`${store.server.url_backend}/web/surat_jalan?id=${row.id}`)
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
      icon: 'rocket',
      title: "Cetak",
      class: 'bg-violet-600 text-light-100',
      show: (row) => row['status'] == 'APPROVED' || row['status'] == 'PRINTED',
      async click(row) {
        console.log(row.id, 'id sj print')
        await tesPrint(row.id);
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
      searchfield: 'this.id, this.no_draft, this.no_surat_jalan, t_buku_order.no_buku_order, this.tanggal_berangkat, t_buku_order.jenis_barang, this.catatan, this.status',
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
    headerName: 'No Draft',
    field: 'no_draft',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'No Container',
    valueGetter: (params) => {
      return params.data['t_buku_order_d_npwp.no_prefix'] + params.data['t_buku_order_d_npwp.no_suffix']
    },
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'No. SJ',
    field: 'no_surat_jalan',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'No. Order',
    field: 't_buku_order.no_buku_order',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'Tanggal Berangkat',
    field: 'tanggal_berangkat',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-center']
  },
  {
    headerName: 'Jenis Barang',
    field: 't_buku_order.jenis_barang',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1.5,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'Tipe SJ',
    field: 'tipe_surat_jalan',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-center']
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
  },
  {
    headerName: 'Status',
    field: 'status',
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-center'],
    sortable: true,
    filter: 'ColFilter',
    cellRenderer: ({ value }) => {
      let colorClass = '';

      switch (value) {
        case 'DRAFT':
          colorClass = 'text-gray-500';
          break;
        case 'POST':
          colorClass = 'text-amber-500';
          break;
        case 'PRINTED':
          colorClass = 'text-green-500';
          break;
        default:
          colorClass = 'text-gray-500';
      }

      return `<span class="${colorClass} rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${value}</span>`;
    }
  }
  ]
})


const filterButton = ref(null);

function filterShowData(status) {
  filterButton.value = filterButton.value === status ? null : status;

  landing.api.params.where = filterButton.value
    ? `this.status='${filterButton.value}'`
    : null;

  apiTable.value.reload();
}

function buttonClass(status) {
  const isActive = filterButton.value === status;
  const baseClass = 'border text-sm py-1 px-2.5 transition-colors duration-300 rounded';
  const activeClasses = {
    DRAFT: 'bg-gray-600 text-white hover:bg-gray-600',
    POST: 'bg-amber-600 text-white hover:bg-amber-600',
    PRINTED: 'bg-green-600 text-white hover:bg-green-600',
  };
  const inactiveClasses = {
    DRAFT: 'border-gray-600 text-gray-600 bg-white hover:bg-gray-600 hover:text-white',
    POST: 'border-amber-600 text-amber-600 bg-white hover:bg-amber-600 hover:text-white',
    PRINTED: 'border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white',
  };

  return `${baseClass} ${isActive ? activeClasses[status] : inactiveClasses[status]}`;
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