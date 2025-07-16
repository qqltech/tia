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
  const tsId = `ts=`+(Date.parse(new Date()))

  // ------------------------------ PERSIAPAN
  onBeforeMount( async ()=>{
    if(localStorage.getItem('respo')){
      const respoValues =  await JSON.parse(localStorage.getItem('respo'))
      values.comp_id = respoValues.m_comp_id
      values.sub_comp_id = respoValues.m_subcomp_id
      values.branch_id = respoValues.m_branch_id
    }
    document.title = 'Laporan Premi'
  })

  //  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
  
  //  @else----------------------- LANDING
  let initialValues = {}
  const changedValues = []
  const checkedState = ref()

  const values = reactive({
    tipe: 'PDF',
    periode_awal: new Date().toLocaleDateString('en-GB'),
    periode_akhir: new Date().toLocaleDateString('en-GB')
  })

  const onGenerate = async () => {
    if(values.tipe === null){
      swal.fire({
        icon: 'error',
        text: 'Harap Memilih Tipe Eksport Dahulu!',
      })
      return
    }
    if(!values.periode_awal || !values.periode_akhir){
      swal.fire({
        icon: 'error',
        text: 'Harap Memilih Periode Dahulu!',
      })
      return
    }
    if(!values.supir_id){
      swal.fire({
        icon: 'error',
        text: 'Harap Memilih Supir Dahulu!',
      })
      return
    }
    const tempGet = []
    isRequesting.value = true

    if(values.tipe){
      if(values.tipe?.toLowerCase() === 'excel'){
        tempGet.push(`export=xls`)
      }else if(values.tipe?.toLowerCase() === 'pdf'){
        tempGet.push(`export=pdf`)
      }
    }
    if(values.periode_awal){
      let tempYear = values.periode_awal.split('/')[2]
      let tempMonth = values.periode_awal.split('/')[1]
      let tempDay = values.periode_awal.split('/')[0]
      tempGet.push(`periode_awal=${tempYear}-${tempMonth}-${tempDay}`)
    }
    if(values.periode_akhir){
      let tempYear2 = values.periode_akhir.split('/')[2]
      let tempMonth2 = values.periode_akhir.split('/')[1]
      let tempDay2 = values.periode_akhir.split('/')[0]
      tempGet.push(`periode_akhir=${tempYear2}-${tempMonth2}-${tempDay2}`)
    }
    if(values.supir_id){
      tempGet.push(`supir_id=${values.supir_id}`)
    }

    const paramsGet = tempGet.join("&")

    let urlPath = '/web/laporan_premi';

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
                firstSpanElement.style.fontSize = '22px';
            }
            const tableElement = tempDiv.querySelector('table');
            if (tableElement) {
                tableElement.style.fontSize = '14px';
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
  watchEffect(()=>store.commit('set', ['isRequesting', isRequesting.value]))