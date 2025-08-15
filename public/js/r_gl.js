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

// ------------------------------ PERSIAPAN
onBeforeMount(async () => {
  if (localStorage.getItem('respo')) {
    const respoValues = await JSON.parse(localStorage.getItem('respo'))
    values.m_business_unit_id = respoValues.m_business_unit_id
  }
  document.title = 'Laporan General Ledger'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS

//  @else----------------------- LANDING
let initialValues = {}
const changedValues = []
const checkedState = ref()

const values = reactive({
  tipe: 'HTML',
  tipe_report: 'General Ledger',
  periode_awal: new Date().toLocaleDateString('en-GB'),
  periode_akhir: new Date().toLocaleDateString('en-GB')
})

const onGenerate = async () => {

  // if (values.tipe === null) {
  //   swal.fire({
  //     icon: 'error',
  //     text: 'Harap Memilih Tipe Eksport Dahulu!',
  //   })
  //   return
  // }

  // if (!values.m_business_unit_id) {
  //   swal.fire({
  //     icon: 'error',
  //     text: 'Harap Memilih Business Unitnya Terlebih Dahulu!',
  //   })
  //   return
  // }

  // if (values.tipe_report === 'General Journal') {
  //   if (!values.periode_awal || !values.periode_akhir) {
  //     swal.fire({
  //       icon: 'error',
  //       text: 'Harap Memilih Periode Awal dan Akhir Dahulu!',
  //     })
  //     return
  //   }
  //   const from = parseDMY(values.periode_awal);
  //   const to = parseDMY(values.periode_akhir);
  //   if (to < from) {
  //     swal.fire({
  //       icon: 'error',
  //       text: 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.',
  //     })
  //     return
  //   }
  // } else {
  //   if (!values.select_month) {
  //     swal.fire({
  //       icon: 'error',
  //       text: 'Harap Memilih Bulan Dahulu!',
  //     })
  //     return
  //   }
  // }
  // if(!values.m_business_unit_id){
  //   swal.fire({
  //     icon: 'error',
  //     text: 'Harap Memilih Bussiness Unit Dahulu!',
  //   })
  //   return
  // }
  const tempGet = []
  isRequesting.value = true

  if (values.tipe) {
    if (values.tipe?.toLowerCase() === 'excel') {
      tempGet.push(`export=xls`)
    } else if (values.tipe?.toLowerCase() === 'pdf') {
      tempGet.push(`export=pdf`)
      tempGet.push(`orientation=landscape`)
    }
  } const formatDate = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  };

  if (values.tipe_report === 'General Journal') {
    if (values.periode_awal && values.periode_akhir) {
      let [day, month, year] = values.periode_awal.split('/');
      tempGet.push(`periode_awal=${year}-${month}-${day}`);

      let [day2, month2, year2] = values.periode_akhir.split('/');
      tempGet.push(`periode_akhir=${year2}-${month2}-${day2}`);
    }
  } else {
    if (values.select_month) {
      const selectedDate = new Date(values.select_month + '-01');
      const firstDay = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
      const lastDay = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0);

      tempGet.push(`periode_awal=${formatDate(firstDay)}`);
      tempGet.push(`periode_akhir=${formatDate(lastDay)}`);
    }
  }

  // if(values.periode_awal){
  //   let tempYear = values.periode_awal.split('/')[2]
  //   let tempMonth = values.periode_awal.split('/')[1]
  //   let tempDay = values.periode_awal.split('/')[0]
  //   tempGet.push(`periode_awal=${tempYear}-${tempMonth}-${tempDay}`)
  // }
  // if(values.periode_akhir){
  //   let tempYear2 = values.periode_akhir.split('/')[2]
  //   let tempMonth2 = values.periode_akhir.split('/')[1]
  //   let tempDay2 = values.periode_akhir.split('/')[0]
  //   tempGet.push(`periode_akhir=${tempYear2}-${tempMonth2}-${tempDay2}`)
  // }
  if (values.m_business_unit_id) {
    tempGet.push(`m_business_unit_id=${values.m_business_unit_id}`)
  }

  const paramsGet = tempGet.join("&")

  let urlPath = '';
  
  if (values.tipe_report === 'General Journal') {
    urlPath = '/web/r_gj';
  } else if (values.tipe_report === 'General Ledger') {
    urlPath = '/web/r_gl';
  }


  // let urlPath = '/web/r_gl';

  if (values.tipe?.toLowerCase() !== 'html') {
    exportHtml.value = false;
    window.open(`${store.server.url_backend}${urlPath}` + '?' + paramsGet);
  } else {
    await fetch(`${store.server.url_backend}${urlPath}` + '?' + paramsGet, {
      headers: {
        'Content-Type': 'html',
      },
    })
      .then(response => response.text())
      .then(html => {
        exportHtml.value = true;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const firstSpanElement = tempDiv.querySelector('span:first-of-type');
        if (firstSpanElement) {
          firstSpanElement.style.fontSize = '10px';
        }
        const tableElement = tempDiv.querySelector('table');
        if (tableElement) {
          tableElement.style.fontSize = '10px';
        }
        const targetDiv = document.getElementById('exportTable');
        targetDiv.innerHTML = '';
        targetDiv.appendChild(tempDiv);
      })
      .catch(error => {
        swal.fire({
          icon: 'error',
          text: error,
        });
      });
  }


  isRequesting.value = false
}

//  @endif -------------------------------------------------END
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))