import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')
const isRequesting = ref(false)
let dataDocs = reactive({items:[]})

const copyToClipboard = (text)=>{
  const el = document.createElement('textarea');
  el.value = text;
  document.body.appendChild(el);
  el.select();
  document.execCommand('copy');
  document.body.removeChild(el);
  swal.fire({
    icon: 'success',
    text: 'Teks sudah tersalin ke clipboard'
  })
}

onBeforeMount(()=>{
  document.title = 'Dokumentasi API'
  getData()
})

const getData = async ()=>{
  try {
    const dataURL = `${store.server.url_backend}/operation/api_docs/get_docs`
    isRequesting.value = true

    const res = await fetch(dataURL, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
    })
    if (!res.ok) throw new Error("Failed when trying to read data")
    const resultJson = await res.json()
    dataDocs.items = resultJson
    console.log(dataDocs)
  } catch (err) {
    swal.fire({
      icon: 'warning',
      text: err
     
    })
  }
  isRequesting.value = false
}

const check = async (idx)=>{
  try {
    var url = dataDocs.items[idx]?.endpoint ?? ''
    const type = dataDocs.items[idx]?.type ?? 'GET'
  
    const dataURL = `${store.server.url_backend}/operation/${url}`
    isRequesting.value = true

    if(type == 'POST'){
      const body = dataDocs.items[idx]?.body ?? {}
      var res = await fetch(dataURL, {
        method: type,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        }, 
        body: JSON.stringify(JSON.parse(body))
      })
    }else{
      const params = JSON.parse(dataDocs.items[idx]?.body ?? {})

      url = new URL(dataURL);
      Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));
      console.log(url.toString())
      var res = await fetch(url.toString(), {
        method: type,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      })
    }
    const resultJson = await res.json()
    if (!res.ok) {
      swal.fire({
        icon: 'warning',
        text: `Code:${resultJson.code} Msg: ${resultJson.message}`
      });
      isRequesting.value = false
      return 
    }
    dataDocs.items[idx]['trial'] = resultJson
  } catch (err) {
    swal.fire({
      icon: 'warning',
      text: err?.message ?? 'Sesuatu yang salah terjadi :)'
     
    })
  }
  isRequesting.value = false
}

const deleteTrial = (idx)=>{
  delete dataDocs.items[idx]['trial']
}

const view = (idx)=>{
  let last = dataDocs.items[idx]?.open ?? false

  dataDocs.items[idx]['open'] = !last
}