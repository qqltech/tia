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
        values.comp_id = respoValues.m_comp_id
        values.sub_comp_id = respoValues.m_subcomp_id
        values.branch_id = respoValues.m_branch_id
    }
    document.title = 'Laporan Trial Balance'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS

//  @else----------------------- LANDING
let initialValues = {}
const changedValues = []
const checkedState = ref()

const values = reactive({
    tipe: 'HTML',
    tipe_report: 'Trial Balance',
    selected_month: new Date().toISOString().slice(0, 7) // Format: YYYY-MM
})

const onGenerate = async () => {
    if (values.tipe === null) {
        swal.fire({
            icon: 'error',
            text: 'Harap Memilih Tipe Eksport Dahulu!',
        })
        return
    }
    if (!values.selected_month) {
        swal.fire({
            icon: 'error',
            text: 'Harap Memilih Periode Dahulu!',
        })
        return
    }
    const tempGet = []
    isRequesting.value = true

    if (values.tipe) {
        if (values.tipe?.toLowerCase() === 'excel') {
            tempGet.push(`export=xls`)
        } else if (values.tipe?.toLowerCase() === 'pdf') {
            tempGet.push(`export=pdf`)
        }
    }

    // Get start and end date of selected month
    const selectedDate = new Date(values.selected_month)
    const firstDay = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1)
    const lastDay = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0)

    const formatDate = (date) => {
        const year = date.getFullYear()
        const month = String(date.getMonth() + 1).padStart(2, '0')
        const day = String(date.getDate()).padStart(2, '0')
        return `${year}-${month}-${day}`
    }

    tempGet.push(`periode_from=${formatDate(firstDay)}`)
    tempGet.push(`periode_to=${formatDate(lastDay)}`)

    if (values.type_id) {
        tempGet.push(`type_id=${values.type_id}`)
    }
    if (values.kat_item_ids) {
        tempGet.push(`cat_id=${values.kat_item_ids}`)
    }
    if (values.warehouse_ids) {
        tempGet.push(`warehouse_ids=${values.warehouse_ids}`)
    }
    if (values.m_item_id) {
        tempGet.push(`m_item_id=${values.m_item_id}`)
    }
    if (values.m_supp_id) {
        tempGet.push(`m_supp_id=${values.m_supp_id}`)
    }
    const paramsGet = tempGet.join("&")

    let urlPath = '';

    if (values.tipe_report === 'Trial Balance') {
        urlPath = '/web/r_tb';
    } else if (values.tipe_report === 'General Ledger') {
        urlPath = '/web/r_gl';
    } else if (values.tipe_report === "Laba Rugi") {
        urlPath = '/web/r_lr'
    } else if (values.tipe_report === "Neraca") {
        urlPath = '/web/r_neraca'
    }

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
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))