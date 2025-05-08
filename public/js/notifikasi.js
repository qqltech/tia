import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

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
const currentUser = store.user.data
// ------------------------------ PERSIAPAN

const endpointApi = '/generate_approval/outstanding'
onBeforeMount(()=>{
  document.title = 'Notification'
})

let initialValues = {}
const changedValues = []

let dataLanding = reactive({items:[]})
const values = reactive({})
let params = { join: false, transform: false, single:true }

onBeforeMount(async () => {  
})
async function loadTable() {
  try {
      // reset landing
      dataLanding.items = []

      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}`
      isRequesting.value = true

      const fixedParams = new URLSearchParams(params)
      const res = await fetch(dataURL + '?' + fixedParams, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      })
      if (!res.ok) throw new Error("Failed when trying to read data")
      const resultJson = await res.json()
      let data = resultJson.data

      const groupedData = data.reduce((result, item) => {
        const key = item.trx_name;
        if (!result[key]) {
            result[key] = [];
        }
        result[key].push(item);
        return result;
      }, {});

      data = groupedData
      let counter = 0
      for(const item in data){
        let active = false
        if(counter === 0) active = true
        dataLanding.items.push({
          'active' : active,
          'name' : item,
          'data' : data[item],
          'selectedIds': [],
          'trx_table': data[item][0]?.trx_table
        })
        counter++
      }
      
    } catch (err) {
      isBadForm.value = true
      swal.fire({
        icon: 'error',
        text: err,
        allowOutsideClick: false,
        confirmButtonText: 'Kembali',
      }).then(() => {
        
      })
    }
    isRequesting.value = false
    // console.log(dataLanding.items)
}

function toggleSelectAll(item) {
  if (item.selectedIds.length === item.data.length) {
    item.selectedIds = [];
  } else {
    item.selectedIds = item.data.map(d => ({
      id: d.id,
      trx_table: item.trx_table
    }))
  }
}

function toggleSelectRow(item, id) {
  const index = item.selectedIds.findIndex(d => d.id === id);
  if (index > -1) {
    item.selectedIds.splice(index, 1);
  } else {
    item.selectedIds.push({
      id,
      trx_table: item.trx_table
    });
  }
}


async function approveSelected(item,status) {
  swal.fire({
    icon: 'warning',
    text: status == 'APPROVED' ? 'Approve?' : status == 'REJECTED' ? 'Reject?' : 'Revise?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',

    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      if (!item.selectedIds?.length) return;

      const trxTable = item.selectedIds[0]?.trx_table;
      if (!trxTable) return;

      const payload = item.selectedIds.map(d => ({ ...d, type:status, note:'aaaa' }));
      try {
        
        const dataURL = `${store.server.url_backend}/operation/${trxTable}/multi_progress`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: 'POST',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify({ items: payload })
        });

        if (!res.ok) {
          if ([400, 422, 500].includes(res.status)) {
            const responseJson = await res.json()
            // formErrors.value = responseJson.errors || {}
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

        item.selectedIds = [];
        await loadTable()

      } catch (err) {
        isBadForm.value = true
        swal.fire({
          icon: 'error',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',
          text: err
        })
      }
      item.selectedIds = [];
      isRequesting.value = false;
      }
  })
  
}


function openClose(i) {
  const checkIsOpen = dataLanding.items[i].active ? true : false
  dataLanding.items[i].active = !checkIsOpen
}

function search() {
  params = {
    ...params,
    search: values.search
  }
  console.log(params)
  loadTable()
}

function paginate() {
  params = {
    ...params,
    paginate: values.paginate
  }
  loadTable()
}

function detail(i, idx) {
  const dt =  dataLanding.items[i]['data'][idx]
  console.log(dt)
  const url = (dt.form_name ?? dt.trx_table)+`/${dt.id}?is_approval=true`
  return router.replace('/' + url)
}