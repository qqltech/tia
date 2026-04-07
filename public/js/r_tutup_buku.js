import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated, computed, watch } from 'vue'

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
  document.title = 'Tutup Buku'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS

//  @else----------------------- LANDING
let initialValues = {}
const changedValues = []
const checkedState = ref()

const values = reactive({
  tipe: 'HTML',
  tipe_report: 'Summary',
  periode_from: new Date().toLocaleDateString('en-GB'),
  periode_to: new Date().toLocaleDateString('en-GB')
})

const allmodul = ref([])
const allmenu = ref([])
const filteredmenu = ref([])

onMounted(async () => {
  try {
    const response = await fetch(
      `${store.server.url_backend}/operation/m_menu?simplest=true&paginate=9999999999999`,
      {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        }
      }
    )

    const result = await response.json()
    allmenu.value = result.data

    // DISTINCT modul
    const seen = new Set()
    allmodul.value = result.data.filter(item => {
      if (seen.has(item.modul)) return false
      seen.add(item.modul)
      return true
    })

  } catch (error) {
    console.error('Gagal ambil menu:', error)
  }
})

watch(() => values.m_modul_id, (newVal) => {

  values.m_menu_id = null

  if (!newVal) {
    filteredmenu.value = []
    return
  }

  filteredmenu.value = allmenu.value.filter(
    item => item.id === newVal || item.modul === allmodul.value.find(m => m.id === newVal)?.modul
  )
})

const onGenerate = async () => {
 
}

//  @endif -------------------------------------------------END
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))