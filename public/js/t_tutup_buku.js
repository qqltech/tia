import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated, watch } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const actionText = ref(route.params.id === 'create' ? 'Tambah' : route.query.action)
const isReadData = ref(route.query.action === 'Read')
const disableGroup = ref(route.params.id === 'create' ? false : true)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const formErrors = ref({})
const tableKey = ref(0)
const tsId = `ts=` + (Date.parse(new Date()))
const showModalDetail = ref(false);
const selectedModalTitle = ref('');
const selectedModalData = ref([]);
const detailCoaArr = ref([])
const activeTab = ref('outstanding')
const hasGenerated = ref(false)

// ------------------------------ PERSIAPAN
const endpointApi = 't_tutup_buku'
onBeforeMount(() => {
  document.title = 'Tutup Buku'
})

let values = reactive({})

onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeyDown);
})

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
})

watch(
  () => [values.m_bu_id, values.periode_tahun],
  () => {
    hasGenerated.value = false;
    detailArr.value = [];
    detailCoaArr.value = [];
  }
);

const handleKeyDown = (event) => {
  if (event?.ctrlKey && event?.key === 's') {
    event.preventDefault();
    onSave();
  }
}

function formatDate(date) {
  const d = new Date(date)

  const day = String(d.getDate()).padStart(2, '0')
  const month = String(d.getMonth() + 1).padStart(2, '0')
  const year = d.getFullYear()

  return `${day}/${month}/${year}`
}

let initialValues = {}
const changedValues = []

const defaultValues = async () => {
  if (localStorage.getItem('respo')) {
    const respoValues = await JSON.parse(localStorage.getItem('respo'))
    values.m_bu_id = respoValues.m_bu_id
  }
  isOpen.value = true
  values.grup = 'TAHUNAN'
  values.periode_tahun = new Date().getFullYear().toString()
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
          detailArr.value = []
          for (const key in values) {
            delete values[key]
          }
          seq_ = 0
          tableKey.value++
          defaultValues()
        }
      }
    })
  } else {
    detailArr.value = []
    for (const key in values) {
      delete values[key]
    }
    seq_ = 0
    tableKey.value++
    defaultValues()
  }
}

const detailArr = ref([])
let seq_ = 0

const onRetotal = (dArr) => {
  if (dArr) {
    detailArr.value = dArr
  }
}

function autoGenerate() {
  if (!values.m_bu_id || !values.periode_tahun) {
    swal.fire({ icon: 'warning', text: 'Lengkapi filter Business Unit dan Tahun terlebih dahulu!' });
    return;
  }

  swal.fire({
    icon: 'question',
    text: 'Tampilkan data Outstanding dan kalkulasi Saldo COA tahun ini?',
    showDenyButton: true,
    confirmButtonText: 'Tampilkan Data',
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        isRequesting.value = true;
        const dataURL = `${store.server.url_backend}/operation/t_tutup_buku/get_outstanding`;

        const payload = {
          m_bu_id: values.m_bu_id,
          periode_tahun: values.periode_tahun
        };

        const response = await fetch(dataURL, {
          method: 'POST',
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}` },
          body: JSON.stringify(payload)
        });

        if (!response.ok) {
          const err = await response.json();
          throw new Error(err.message || "Gagal mengambil data");
        }

        const resultJson = await response.json();

        if (resultJson.data) {
          let outstandingData = [];
          if (resultJson.data.outstanding) {
            outstandingData = resultJson.data.outstanding;
          } else if (Array.isArray(resultJson.data.data)) {
            outstandingData = resultJson.data.data;
          } else if (Array.isArray(resultJson.data)) {
            outstandingData = resultJson.data;
          }

          detailArr.value = outstandingData;
          detailCoaArr.value = resultJson.data.saldo_coa || [];

          onRetotal(detailArr.value);
        }

        if (detailArr.value.length === 0) {
          swal.fire({ icon: 'success', text: 'Semua transaksi sudah clear! Anda dapat melakukan Closing.' });
          activeTab.value = 'coa';
        } else {
          // --- BAGIAN YANG DIUBAH ---
          swal.fire({ icon: 'warning', text: `Terdapat ${detailArr.value.length} menu yang transaksinya belum final!` });
          activeTab.value = 'outstanding';
        }

        hasGenerated.value = true;

      } catch (err) {
        swal.fire({ icon: 'error', text: err.message || err });
      } finally {
        isRequesting.value = false;
      }
    }
  });
}

function openDetailModal(item) {
  selectedModalTitle.value = item.nama_transaksi;
  selectedModalData.value = (item.details || []).sort((a, b) => b.id - a.id);
  showModalDetail.value = true;
}

watchEffect(() => {
  store.commit('set', ['isRequesting', isRequesting.value])
})

const isOpen = ref(false)

onBeforeMount(async () => {
  await defaultValues()
  onReset()
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = { transform: false }
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
      initialValues['status.value2'] = initialValues['status.value2']
      if (actionText.value?.toLowerCase() === 'copy') {
        delete initialValues.uid
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

async function onSave() {
  try {
    if (!values.periode_tahun) {
      swal.fire({
        icon: 'warning',
        text: `Tahun belum diisi`
      })
      return
    }

    if (!hasGenerated.value) {
      swal.fire({
        icon: 'error',
        text: `Silakan klik tombol "Show Data" terlebih dahulu untuk mengecek Outstanding dan mengkalkulasi Saldo COA!`
      });
      return;
    }

    if (detailArr.value.length > 0) {
      swal.fire({
        icon: 'error',
        text: `Tidak dapat melakukan Closing! Selesaikan dulu transaksi yang Outstanding.`
      });
      return;
    }

    const tahunStr = values.periode_tahun;
    const bulanStr = "01"; 

    const openDateStr = `${tahunStr}-${bulanStr}-01`;
    values.open_date = openDateStr;
    values.open_time = `${openDateStr} 00:00:00`;

    const now = new Date();
    const cYear = now.getFullYear();
    const cMonth = String(now.getMonth() + 1).padStart(2, '0');
    const cDay = String(now.getDate()).padStart(2, '0');
    const cHour = String(now.getHours()).padStart(2, '0');
    const cMin = String(now.getMinutes()).padStart(2, '0');
    const cSec = String(now.getSeconds()).padStart(2, '0');

    const closeDateStr = `${cYear}-${cMonth}-${cDay}`;
    values.close_date = closeDateStr;
    values.close_time = `${closeDateStr} ${cHour}:${cMin}:${cSec}`;

    values.detail_coa = detailCoaArr.value;
    values.periode = `${values.periode_tahun}`;
    values.m_menu_id = null;
    values.modul = null;

    const isEdit = ['Edit'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isEdit ? ('/' + route.params.id) : ''}`;
    isRequesting.value = true;
    values.is_active = values.is_active ? 1 : 0

    const res = await fetch(dataURL, {
      method: isEdit ? 'PUT' : 'POST',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(values)
    });

    if (!res.ok) {
      const responseJson = await res.json().catch(() => ({}));

      if ([400, 422].includes(res.status)) {
        formErrors.value = responseJson.errors || {};
      }

      let errorMsg = responseJson.message || "Oops, sesuatu yang salah terjadi. Coba kembali nanti.";

      if (responseJson.errors && Object.keys(responseJson.errors).length > 0) {
        errorMsg = Object.values(responseJson.errors)[0][0];
      }

      throw errorMsg;
    }

    swal.fire({
      icon: 'success',
      text: 'Closing Berhasil Disimpan!'
    });
    onReset(false)
    formErrors.value = {}

  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'warning',
      text: err.message || err
    });
  } finally {
    isRequesting.value = false;
  }
}

watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))