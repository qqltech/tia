import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

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
const exportHtml = ref(false)
const formErrors = ref({})
const activeTabIndex = ref(0)
const tsId = `ts=` + (Date.parse(new Date()))
const isPublic = ref(route.query.view === 'public');

const activeIndex = ref(0);
const is_ok = ref(false);

const defaultThermal = reactive({});
const thermal = reactive({});
const endpointList = ref([]);
const initialCustomField = ref(``);
const customField = ref(``);

function setActiveIndex(index) {
  if (activeIndex.value === index) {
    activeIndex.value = false;
  } else {
    activeIndex.value = index;
  }
}

function saveLocalStorage(key, value) {
  localStorage.setItem('thermal_interface', thermal.interface);
  localStorage.setItem('thermal_port', thermal.port);
  localStorage.setItem('thermal_url', thermal.url);
  swal.fire({
    icon: 'success',
    text: "Pengaturan disimpan"
  });
}

onBeforeMount(async () => {
  isRequesting.value = true;
  document.title = 'Thermal Printer';

  const initThermal = {
    interface: 'POS-80',
    port: '9000',
    url: '/print/template'
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
  if (localStorage.getItem('thermal_url')) {
    thermal.url = localStorage.getItem('thermal_url')
  }

  await getEndpoint();

  const defaultCustomExp = `{
  "data" : [
    { "type": "alignCenter" }, { "type": "setTextDoubleHeight" }, { "type": "println", "value": "SPK ANGKUTAN" },
    { "type": "setTextNormal" }, { "type": "alignLeft" }, { "type": "newLine" },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Tanggal In : 31/08/2024", "align": "LEFT", "cols": 24 },
        { "text": "Tanggal Out : 12/09/2024", "align": "RIGHT", "cols": 24 }
      ]
    },
    { "type": "drawLine" },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Order 1", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "019-IMP-II-25 / E /JSTND", "align": "LEFT", "cols": 36 }
      ]
    },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Order 2", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "011-EXPS-I-25 / F /A", "align": "LEFT", "cols": 36 }
      ]
    },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "No. SPK", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "SPK0137", "align": "LEFT", "cols": 11 },
        { "text": "", "align": "CENTER", "cols": 1 },
        { "text": "Pagi/Sore", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "PAGI / SIANG", "align": "LEFT", "cols": 12 }
      ]
    },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Head", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "JOS1", "align": "LEFT", "cols": 11 },
        { "text": "", "align": "CENTER", "cols": 1 },
        { "text": "Chasis 1", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "JOS1", "align": "LEFT", "cols": 12 }
      ]
    },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Supir", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "M Rifai", "align": "LEFT", "cols": 11 },
        { "text": "", "align": "CENTER", "cols": 1 },
        { "text": "Chasis 2", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "JS6", "align": "LEFT", "cols": 12 }
      ]
    },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Trip", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "PPE", "align": "LEFT", "cols": 36 }
      ]
    },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Sektor", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "YOGYA", "align": "LEFT", "cols": 11 },
        { "text": "", "align": "CENTER", "cols": 1 },
        { "text": "Container", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "20 Ft", "align": "LEFT", "cols": 12 }
      ]
    },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Dari", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "Jakarta", "align": "LEFT", "cols": 11 },
        { "text": "", "align": "CENTER", "cols": 1 },
        { "text": "Ke", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "Surabaya", "align": "LEFT", "cols": 12 }
      ]
    },
    { "type": "drawLine" },
    { "type": "setTextDoubleHeight" },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Sangu", "align": "LEFT", "cols": 5 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "Rp. 800.000", "align": "LEFT", "cols": 40 }
      ]
    },
    { "type": "setTextNormal" },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Tambahan Biaya Lain-lain", "align": "LEFT", "cols": 24 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "Dummy data coba 2", "align": "LEFT", "cols": 21 }
      ]
    },
    { "type": "drawLine" },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "No.", "align": "LEFT", "cols": 4 },
        { "text": "Keterangan", "align": "LEFT", "cols": 29 },
        { "text": "Jumlah", "align": "RIGHT", "cols": 15 }
      ]
    },
    { "type": "drawLine" },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "1", "align": "LEFT", "cols": 4 },
        { "text": "Uang bensin", "align": "LEFT", "cols": 29 },
        { "text": "Rp. 400.000", "align": "RIGHT", "cols": 15 }
      ]
    },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "2", "align": "LEFT", "cols": 4 },
        { "text": "Uang makan", "align": "LEFT", "cols": 29 },
        { "text": "Rp. 200.000", "align": "RIGHT", "cols": 15 }
      ]
    },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "3", "align": "LEFT", "cols": 4 },
        { "text": "Uang mokel", "align": "LEFT", "cols": 29 },
        { "text": "Rp. 20.002.500", "align": "RIGHT", "cols": 15 }
      ]
    },
    { "type": "drawLine" },
    { "type": "setTextDoubleHeight" },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Total", "align": "LEFT", "cols": 5 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "Rp. 20.602.500", "align": "RIGHT", "cols": 40 }
      ]
    },
    { "type": "setTextNormal" },
    { "type": "drawLine" },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Terbilang", "align": "LEFT", "cols": 9 },
        { "text": " : ", "align": "CENTER", "cols": 3 },
        { "text": "Dua puluh juta enam ratus dua ribu lima ratus rupiah", "align": "LEFT", "cols": 36 }
      ]
    },
    { "type": "drawLine" },
    { "type": "println", "value": "Mengetahui," },
    { "type": "newLine" },
    { 
      "type": "tableCustom",
      "value": [
        { "text": "Admin / Kasir", "align": "CENTER", "cols": 16 },
        { "text": "Sopir", "align": "CENTER", "cols": 16 },
        { "text": "Pengebon", "align": "CENTER", "cols": 16 }
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
        { "text": "(Rifyal Anwar)", "align": "CENTER", "cols": 16 },
        { "text": "(Arjuna Wira)", "align": "CENTER", "cols": 16 },
        { "text": "(Dovan Edo)", "align": "CENTER", "cols": 16 }
      ]
    },
    { "type": "newLine" },
    { "type": "println", "value": "Dicetak pada tanggal : 19/03/2025, 00:20:53" },
    { "type": "println", "value": "Operator : DEWI-PC # dewi" },
    { "type": "cut" }
  ]
}`;

  initialCustomField.value = defaultCustomExp;
  customField.value = defaultCustomExp;
})

onMounted(async () => {
  await tesKoneksi(true);
})

async function getEndpoint(disabled = false) {
  isRequesting.value = true;
  try {
    const response = await fetch(`http://localhost:${thermal.port}/endpoint`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const result = await response.json();
    endpointList.value = result;
  } catch (error) {
    console.log(error);
  }
}

function resetThermal() {
  swal.fire({
    icon: 'warning',
    text: 'Semua konfigurasi akan diatur ulang. Apakah anda yakin?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      for (const key in defaultThermal) {
        console.log(defaultThermal[key]);
        thermal[key] = defaultThermal[key]
      }
    }
  })
}

async function actionCustomPrint() {
  isRequesting.value = true;
  let url = `http://localhost:${thermal.port}/print/custom`;
  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: customField.value
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const result = await response.json();
    let showText = "Dynamic Custom";
    swal.fire({
      icon: 'success',
      text: `${showText} berhasil`
    });
    is_ok.value = true;

  } catch (error) {
    console.log(error);
    swal.fire({
      icon: 'error',
      text: `${showText} gagal, periksa kembali pengaturan atau perangkat anda!`
    });
    is_ok.value = false;
  } finally {
    isRequesting.value = false;
  }
}

async function tesKoneksi(disabled = false) {
  isRequesting.value = true;
  try {
    const response = await fetch(`http://localhost:${thermal.port}/test${disabled ? '?disabled=true' : ''}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(thermal)
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const result = await response.json();
    if (!disabled) {
      swal.fire({
        icon: 'success',
        text: "Tes koneksi berhasil"
      });
    }
    is_ok.value = true;

  } catch (error) {
    console.log(error);
    if (!disabled) {
      swal.fire({
        icon: 'error',
        text: "Tes koneksi gagal, periksa kembali pengaturan atau perangkat anda!"
      });
    }
    is_ok.value = false;
  } finally {
    isRequesting.value = false;
  }
}

async function tesPrint() {
  isRequesting.value = true;
  let url = `http://localhost:${thermal.port}${thermal.url}`;
  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(thermal)
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const result = await response.json();
    let showText = "Print";
    swal.fire({
      icon: 'success',
      text: `${showText} berhasil`
    });
    is_ok.value = true;

  } catch (error) {
    console.log(error);
    swal.fire({
      icon: 'error',
      text: `${showText} gagal, periksa kembali pengaturan atau perangkat anda!`
    });
    is_ok.value = false;
  } finally {
    isRequesting.value = false;
  }
}
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))