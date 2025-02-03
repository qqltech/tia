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
const endpointApi = 'm_faktur_pajak'
onBeforeMount(() => {
  document.title = 'Faktur Pajak'
})

let initialValues = {}
// @if( !$id ) | --- LANDING TABLE --- |

// TABLE
const landing = reactive({
  api: {
    url: `${store.server.url_backend}/operation/m_faktur_pajak_d`,
    headers: {
      'Content-Type': 'application/json',
      authorization: `${store.user.token_type} ${store.user.token}`,
    },
    params: {
      scopes: 'NoFaktur',
      simplest: true,
      searchfield: `m_faktur_pajak.start_date, m_faktur_pajak.end_date, this.no_faktur_pajak`
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
      headerName: 'Tanggal Awal', field: 'start_date', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
      sortable: true, filter: 'ColFilter'
    },
    {
      headerName: 'Tanggal Akhir', field: 'end_date', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
      sortable: true, filter: 'ColFilter'
    },
    {
      headerName: 'No. Faktur Pajak', field: 'no_faktur_pajak', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
      sortable: true, filter: 'ColFilter'
    }
  ],
  actions: [{
    title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
    click: row => router.push(`${route.path}/${row.m_faktur_pajak_id}?${tsId}`)
  }
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
  table.api.params.where = filterButton.value !== null ? `this.status=${filterButton.value}` : null;
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
    data.prefix = resData.data.prefix;
    data.no_awal = resData.data.no_awal;
    data.no_akhir = resData.data.no_akhir;
    data.tgl_pembuatan = resData.data.tgl_pembuatan;
    data.start_date = resData.data.start_date;
    data.end_date = resData.data.end_date;


    initialValues = resData.data
    console.log(initialValues)
    initialValues.m_faktur_pajak_d?.forEach((items) => {
      if (actionText.value?.toLowerCase() === 'copy' && items.uid) {
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

function generateDetail() {
  if (!data.prefix || !data.no_awal || !data.no_akhir) {
    swal.fire({
      icon: 'warning',
      title: 'Oops..',
      text: 'Harap mengisi semua data yang diperlukan.',
      confirmButtonText: 'OK'
    });
    return;
  }

  if (parseFloat(data.no_awal) >= parseFloat(data.no_akhir)) {
    alert('DATA AWAL HARUS LEBIH KECIL DARI DATA AKHIR');
    return;
  }

  const batchSize = 1 || 10 || 100; // Ukuran batch
  let currentNum = parseFloat(data.no_awal);
  const endNum = parseFloat(data.no_akhir);
  const newData = [];
  detailArr.value = []; // Menginisialisasi detailArr

  // Tampilan pop-up loading
  swal.fire({
    title: 'Sedang memproses...',
    text: 'Mohon tunggu, data sedang di-generate.',
    allowOutsideClick: false,
    showConfirmButton: false,
    didOpen: () => {
      swal.showLoading(); // Tampilan animasi loading
    }
  });

  function processBatch() {
    const batch = [];
    for (let i = 0; i < batchSize && currentNum <= endNum; i++) {
      batch.push({
        no_faktur_pajak: `${data.prefix}${currentNum}`,
        t_nota_id: 1,
        is_active: 'OPEN',
      });
      currentNum++;
    }

    detailArr.value = [...detailArr.value, ...batch];

    if (currentNum <= endNum) {
      setTimeout(processBatch, 0); // Proses batch berikutnya
    } else {
      is_generate.value = 1; // Tanda bahwa proses selesai

      // Tutup pop-up loading dan tampilan pesan sukses
      swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: `Data berhasil di-generate hingga ${data.no_akhir}.`,
        confirmButtonText: 'OK'
      });
    }
  }

  processBatch();
}



// ACTION BUTTON
const onReset = async (alert = false) => {
  let next = false
  if (alert) {
    swal.fire({
      icon: 'warning',
      text: 'Anda yakin akan mereset data ini?',
      showDenyButton: true
    }).then((res) => {
      if (res.isConfirmed) {
        const newValues = {
          prefix: '',
          no_awal: '',
          no_akhir: '',
          start_date: '',
          end_date: ''
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

  setTimeout(() => {
    // defaultValues() 
  }, 100)
}

function onBack() {
  router.replace('/' + modulPath)
}

async function onPost() {

}

async function onSave() {
  // Pastikan data['m_faktur_pajak_d'] mengacu pada detailArr._value
  data['m_faktur_pajak_d'] = detailArr._value;

  // Ubah nilai 'OPEN' menjadi 1
  data['m_faktur_pajak_d'].forEach(item => {
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
    detailArr.value = data['m_faktur_pajak_d'];

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