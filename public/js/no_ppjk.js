import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated } from 'vue'

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

// ENDPOINT API
const endpointApi = 'm_generate_no_aju'
onBeforeMount(() => {
    document.title = 'Nomor PPJK'
})

let initialValues = {}
// @if( !$id ) | --- LANDING TABLE --- |

// TABLE
const landing = reactive({
    api: {
        url: `${store.server.url_backend}/operation/m_generate_no_aju_d`,
        headers: {
            'Content-Type': 'application/json',
            authorization: `${store.user.token_type} ${store.user.token}`,
        },
        params: {
            scopes: 'NoPPJK',
            simplest: true,
            searchfield: `m_generate_no_aju.tgl_pembuatan, this.no_aju, m_generate_no_aju.tipe `
        },
        onsuccess(response) {
            return { ...response, page: response.current_page, hasNext: response.has_next };
        },
    },
    columns: [
        {
            headerName: 'No', valueGetter: ({ node }) => node.rowIndex + 1, width: 60,
            cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
        },
        {
            headerName: 'Tanggal Pembuatan', field: 'tgl_pembuatan', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
            sortable: true, filter: 'ColFilter'
        },
        {
            headerName: 'No. PPJK', field: 'no_aju', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
            sortable: true, filter: 'ColFilter'
        },
        {
            headerName: 'Tipe PPJK', field: 'tipe', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
            sortable: true, filter: 'ColFilter', cellRenderer: ({ value }) =>
            value === 'IMPORT'

            ? '<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">IMPORT</span>'
            : '<span class="text-red-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">EXPORT</span>'
        }
    ],
    actions: [{
            title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
            click: row => router.push(`${route.path}/${row.m_generate_no_aju_id}?${tsId}`)
        },
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

// FILTER
const filterButton = ref(null);

function filterShowData(params) {
    filterButton.value = filterButton.value === params ? null : params;
    landing.api.params.where = filterButton.value !== null ? `m_generate_no_aju.tipe='${filterButton.value}'` : null;
    apiTable.value.reload();
}

onActivated(() => {
    if (apiTable.value && route.query.reload) {
        apiTable.value.reload();
    }
});


// @else | --- FORM DATA --- |

// HOT KEY (CTRL+S)
const handleKeyDown = (event) => {
    if (event?.ctrlKey && event?.key === 's' && actionText.value) {
        event.preventDefault();
        onSave();
    }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
  
  const today = new Date();
  // Format tanggal sesuai dengan "dd-mm-yyyy"
  const day = String(today.getDate()).padStart(2, '0');
  const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
  const year = today.getFullYear();
  const formattedDate = `${day}/${month}/${year}`;
  data.tgl_pembuatan = formattedDate; 
});

onBeforeUnmount(() => { window.removeEventListener('keydown', handleKeyDown) });

// FORM DATA
const data = reactive({
  is_active: 1
});

const detailArr = ref([]);
const is_generate = ref(false);

// GET DATA FROM API
onBeforeMount(async () => {
    if (!isRead) return;

    try {
        const editedId = route.params.id;
        const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${editedId}`;
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

        const resData = await fetchData(dataURL, { join: false, transform: false });
        data.tipe = resData.data.tipe;
        data.kode = resData.data.kode;
        data.no_awal = resData.data.no_awal;
        data.no_akhir = resData.data.no_akhir;
        data.periode = resData.data.periode;
        data.tgl_pembuatan = resData.data.tgl_pembuatan;
        data.tahun = resData.data.tahun;
        data.bulan = resData.data.bulan;

        
        initialValues = resData.data
        console.log(initialValues)
        initialValues.m_generate_no_aju_d?.forEach((items)=>{  
          if(actionText.value?.toLowerCase() === 'copy' && items.uid){
            delete items.uid
          }    
          items.is_active = items.is_active ? 'OPEN' : 'CLOSE'
          // items.is_primary = items.is_primary ? 1 : 0
          detailArr.value = [items, ...detailArr.value]
        })

    } catch (err) {
        isBadForm.value = true;
        swal.fire({
            icon: 'error', text: err, allowOutsideClick: false, confirmButtonText: 'Kembali',
        }).then(() => { router.back() });
    } finally {
        isRequesting.value = false;
    }
});

function generateDetail(){
  if (!data.tipe || !data.kode || !data.tahun || !data.bulan || !data.no_awal || !data.no_akhir) {
  swal.fire({
    icon: 'warning',
    title: 'Oops..',
    text: 'Harap mengisi semua data yang diperlukan.',
    confirmButtonText: 'OK'
  });
  return;
}

  // console.log("ch1 ",data.no_awal)
  // console.log("ch2 ",data.no_akhir)

  // console.log("chm1 ",parseFloat(data.no_awal))
  // console.log("chm2 ",parseFloat(data.no_akhir))

  if (parseFloat(data.no_awal) >= parseFloat(data.no_akhir)){
    alert('DATA AWAL HARUS LEBIH KECIL DARI DATA AKHIR')
    return;
  }

  let current_num = data.no_awal

  const newData = Array.apply(null, Array(data.no_akhir - data.no_awal + 1)).map(() => {
    const formattedNum = String(current_num).padStart(6, '0');
    const genData =  {no_aju: `${data.kode}-${data.tahun}${data.bulan}xx-${formattedNum}`, is_active: 'OPEN'}
    current_num++;
    return genData;
  })
  
  
  detailArr.value = newData;
  is_generate.value = 1;
  
}

// ACTION BUTTON
const onReset = async (alert = false) => {
  let next = false
  if(alert){
    swal.fire({
      icon: 'warning',
      text: 'Anda yakin akan mereset data ini?',
      showDenyButton: true
    }).then((res) => {
      if (res.isConfirmed) {
        const newValues = {
          tipe: '',
          periode: '',
          kode: '',
          no_awal: '',
          no_akhir: ''
        };
        
      detailArr.value = [];
        
        for (const key in newValues) {
          if (newValues.hasOwnProperty(key)) {
            data[key] = newValues[key];
          }
        }
        is_generate.value = false; 
      }
    })
  }
  
  setTimeout(()=>{
    // defaultValues() 
  }, 100)
}


function onBack() {
    router.replace('/' + modulPath)
}

async function onPost(){
  
}

async function onSave() {
  // Pastikan data['m_faktur_pajak_d'] mengacu pada detailArr._value
  data['m_generate_no_aju_d'] = detailArr.value;

  // Ubah nilai 'OPEN' menjadi 1
  data['m_generate_no_aju_d'].forEach(item => {
    if (item.is_active === 'OPEN') {
      item.is_active = 1;
    } else {
      item.is_active = 0;
    }
    
  });

  // Debug log untuk memeriksa data sebelum dikirim
  console.log('Data sebelum dikirim:', JSON.stringify(data, null, 2));

  try {
    // Konversi no_awal dan no_akhir ke string jika ada
    data.no_awal = data.no_awal?.toString();
    data.no_akhir = data.no_akhir?.toString();

    const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`;

    // Set isRequesting menjadi true untuk menandakan request sedang berlangsung
    isRequesting.value = true;

    // Konversi data.is_active ke 1 atau 0
    data.is_active = data.is_active ? 1 : 0;

    // // Ubah status dari 'OPEN' menjadi 1
    // if (data.status === 'OPEN') {
    //   data.status = 1;
    // }


    // Simpan perubahan data detailArr
    detailArr.value = data['m_generate_no_aju_d'];

    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(data)
    });

    // Debug log untuk memeriksa response dari server
    console.log('Response dari server:', res);

    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json();
        formErrors.value = responseJson.errors || {};
        throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Oops, sesuatu yang salah terjadi. Coba kembali nanti.");
      } else {
        throw ("Oops, sesuatu yang salah terjadi. Coba kembali nanti.");
      }
    }

    // Redirect ke modulPath setelah berhasil menyimpan
    router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())));
  } catch (err) {
    // Tampilkan pesan error jika terjadi kesalahan
    isBadForm.value = true;
    swal.fire({
      icon: 'warning',
      text: err
    });
  }

  // Set isRequesting menjadi false setelah request selesai
  isRequesting.value = false;
}



//  @endif | --- END --- |
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))