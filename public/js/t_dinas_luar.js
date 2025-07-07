//   javascript

import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated, computed } from 'vue'
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
const formErrors = ref({})
const activeTabIndex = ref(0)
const tsId = `ts=` + (Date.parse(new Date()))
const previewSrc = ref(null) // Add previewSrc

// ------------------------------ PERSIAPAN
const endpointApi = '/t_dinas_luar'
const defaultHeaders = {
  'Content-Type': 'application/json',
  Authorization: `${store.user.token_type} ${store.user.token}`
}

onBeforeMount(() => {
  document.title = 'Dinas Luar'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
// HOT KEY
onMounted(()=>{
  window.addEventListener('keydown', handleKeyDown);
})
onBeforeUnmount(()=>{
  window.removeEventListener('keydown', handleKeyDown);
})
const handleKeyDown = (event) => {
  if (event?.ctrlKey && event?.key === 's') {
    event.preventDefault(); 
    onSave();
  }
}


const formatRupiah = (number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
  }).format(number);
};

let initialValues = {}
const values = reactive({
 total_amt : 0 ,
 status : 'DRAFT',
})

async function supir(supir_id) {

  console.log(supir_id);
detailArr.value = [];
  if (!supir_id || !supir_id.id) {
    return; 
  }

  try {

    const dataURL = `https://server.qqltech.com:7017/operation/m_kary/${supir_id.id}`;
    const params = { 
      view_buku_order_on_spk_angkutan: true,
      transform: false,
    };
    const fixedParams = new URLSearchParams(params);

    // Make the API request
    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });

    if (!res.ok) throw new Error("Gagal saat mencoba membaca data");

    // Parse the JSON response
    const resultJson = await res.json();
    const initialValues = resultJson.data;
    console.log('SPK ANGKUTAN', initialValues);
    if (Array.isArray(initialValues['buku_order'])) {

      initialValues['buku_order'].forEach((detail) => {
        detail.no_order = detail.no_buku_order; 
        detailArr.value.push(detail); 
      });
    }
  } catch (err) {

    isBadForm.value = true;
    swal.fire({
      icon: 'error',
      text: err.message || "Terjadi kesalahan.",
      allowOutsideClick: false,
      confirmButtonText: 'Kembali',
    }).then(() => {
      router.back(); 
    });
  } finally {
    isRequesting.value = false;
  }
}



onBeforeMount(async () => {
  if (isRead) {
    // READ DATA
    try {
      const editedId = route.params.id;
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`;
      isRequesting.value = true;

      const params = { transform: false, detail: true };
      const fixedParams = new URLSearchParams(params);
      const res = await fetch(dataURL + '?' + fixedParams, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`,
        },
      });

      if (!res.ok) throw new Error("Failed when trying to read data");
      const resultJson = await res.json();
      initialValues = resultJson.data;

      detailArr.value = initialValues['t_dinas_luar_d'] || [];
      // if (detailArr.value.length === 0) {
      //   addItem(); 
      // }

    } catch (err) {
      isBadForm.value = true;
      swal.fire({
        icon: 'error',
        text: err.message || "An error occurred.",
        allowOutsideClick: false,
        confirmButtonText: 'Kembali',
      }).then(() => {
        router.back();
      });
    }
    isRequesting.value = false;
  } 
  for (const key in initialValues) {
    values[key] = initialValues[key];
  }
});

// LOGIC DETAIL 
const detailArr = ref([]);
const selectedItems = ref([]);
const addItem = () => {
  detailArr.value.push({
    // MAPPING DISINI 
    nominal : 0 ,
  });
};
// Logic Delete Jembot
const removeItem = (index) => {
  const item = detailArr.value[index];
  const canRemove = item.nominal <= 0 && !item.catatan;
  if (canRemove) {
    detailArr.value.splice(index, 1);
    selectedItems.value = selectedItems.value
      .filter(i => i !== index)
      .map(i => (i > index ? i - 1 : i));
    return;
  }
  swal.fire({
    title: 'Apakah Anda yakin?',
    text: `Apakah Anda yakin ingin menghapus item no ${index + 1}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      detailArr.value.splice(index, 1);
      selectedItems.value = selectedItems.value
        .filter(i => i !== index)
        .map(i => (i > index ? i - 1 : i));
      swal.fire('Dihapus!', 'Item telah dihapus.', 'success');
    }
  }).catch(console.error);
};
const removeSelectedDetails = () => {
  swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Apakah Anda yakin ingin menghapus item yang dipilih?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      selectedItems.value.slice().sort((a, b) => b - a).forEach(index => {
        if (index >= 0 && index < detailArr.value.length) {
          detailArr.value.splice(index, 1);
        }
      });
      selectedItems.value = [];
      swal.fire('Dihapus!', 'Item yang dipilih telah dihapus.', 'success');
    }
  }).catch(console.error);
};

watchEffect(() => {
  values.total_amt = detailArr.value.reduce((total, item) => total + item.nominal, 0);
});

function onBack() {
  let isChanged = false
  for (const key in initialValues) {
    if (values[key] !== initialValues[key]) {
      isChanged = true
      break;
    }
  }
  if (!isChanged) {
    router.replace('/' + modulPath)
    return
  }
  router.replace('/' + modulPath)
}

async function onSave(isPost=false) {
  try {
    const result = await swal.fire({
      title: 'Konfirmasi Simpan',
      text: 'Apakah data yang Anda simpan sudah benar?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Oke',
      cancelButtonText: 'Belum'
    });
    if (result.isConfirmed) {
      // Logic Detail save
      values.t_dinas_luar_d = detailArr.value;

      const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
      const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? isPost ? '?post=true' : '' : isPost ? '/' + route.params.id + '?post=true' :'/' + route.params.id}`;
      isRequesting.value = true;
      const res = await fetch(dataURL, {
        method: isCreating ? 'POST' : 'PUT',
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        body: JSON.stringify(values)
      });
      if (!res.ok) {
        if ([400, 422].includes(res.status)) {
          const responseJson = await res.json();
          formErrors.value = responseJson.errors || {};
          throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Oops, sesuatu yang salah terjadi. Coba kembali nanti.");
        } else {
          throw ("Oops, sesuatu yang salah terjadi. Coba kembali nanti.");
        }
      }
      router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())));
    }
  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'warning',
      text: err
    });
  } finally {
    isRequesting.value = false;
  }
}
// Logic On Reset
const onReset = (confirm = false) => {
  const isFormEmpty = 
                      !values.no_dinas_luar && 
                      !values.total_amt && 
                      !values.supir_id && 
                      detailArr.value.length === 0;

  if (isFormEmpty) {
    swal.fire({
      title: 'Form Kosong',
      text: 'Form ini sudah kosong. Tidak perlu mereset.',
      icon: 'info',
      confirmButtonText: 'OK'
    });
    return;
  }
  if (confirm) {
    swal.fire({
      title: 'Apakah Anda yakin?',
      text: 'Apakah Anda yakin ingin mereset form ini? Semua data akan hilang!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Reset!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        resetForm();
      }
    }).catch((error) => {
      console.error('Error during the Swal promise:', error);
    });
  } else {
    resetForm();
  }
};
const resetForm = () => {
  values.total_amt = 0;

  values.no_dinas_luar = '';
  values.supir_id = '';
  values.status = 'DRAFT';
  detailArr.value = [];
  selectedItems.value = [];
};


//  @else----------------------- LANDING
const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) =>  row.status?.toUpperCase() ==='DRAFT',
      click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Hapus Data Terpilih?',
          confirmButtonText: 'Yes',
          showDenyButton: true,
        }).then(async (result) => {
          if (result.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation${endpointApi}/${row.id}`
              isRequesting.value = true
              const res = await fetch(dataURL, {
                method: 'DELETE',
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                }
              })
              if (!res.ok) {
                const resultJson = await res.json()
                throw (resultJson.message || "Failed when trying to remove data")
              }
              apiTable.value.reload()
              // const resultJson = await res.json()
            } catch (err) {
              isBadForm.value = true
              swal.fire({
                icon: 'error',
                text: err
              })
            }
            isRequesting.value = false
          }
        })
      }
    },
    {
      icon: 'eye',
      title: "Read",
      class: 'bg-green-600 text-light-100',
      click(row) {
        router.push(`${route.path}/${row.id}?`+tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      show: (row) => row.status?.toUpperCase() == 'DRAFT',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&`+tsId)
      }
    },
    // {
    //   icon: 'copy',
    //   title: "Copy",
    //   class: 'bg-gray-600 text-light-100',
      
    //   click(row) {
    //     router.push(`${route.path}/${row.id}?action=Copy&`+tsId)
    //   }
    // },
    // {
    //   icon: 'print',
    //   title: "Cetak",
    //   class: 'bg-gray-600 text-light-100',
    //   click(row) {
    //     window.open(`${store.server.url_backend}/web/surat_jalan?export=pdf&size=a4&orientation=potrait&group=SATUAN%20JASA&id=${row.id}`)
    //   }
    // },
    // {
    //   icon: 'table',
    //   title: "Unduh Excel",
    //   class: 'bg-gray-600 text-light-100',
    //   click(row) {
    //     window.open(`${store.server.url_backend}/web/surat_jalan?export=excel&size=a4&orientation=potrait&group=SATUAN%20JASA&id=${row.id}`)
    //   }
    // },
        {
      icon: 'location-arrow',
      title: "Post Data",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status?.toUpperCase() === 'DRAFT' ,
      async click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Post Data?',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',

          showDenyButton: true
        }).then(async (res) => {
          if (res.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/t_dinas_luar/post`
              isRequesting.value = true
              const res = await fetch(dataURL, {
                method: 'POST',
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                body: JSON.stringify({ id: row.id })
              })
              if (!res.ok) {
                if ([400, 422].includes(res.status)) {
                  const responseJson = await res.json()
                  formErrors.value = responseJson.errors || {}
                  throw new Error(responseJson.message || "Failed when trying to post data")
                } else {
                  throw new Error("Failed when trying to post data")
                }
              }
              apiTable.value.reload()
              // const resultJson = await res.json()
            } catch (err) {
              isBadForm.value = true
              swal.fire({
                icon: 'error',
                text: err
              })
            }
            isRequesting.value = false
          }
        })
      }
    },
  ],
  api: {
    url: `${store.server.url_backend}/operation${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      // searchfield:'this.no_draft, this.no_nota_rampung, this.customer, this.pelabuhan, this.status',
    },
    onsuccess(response) {
      response.page = response.current_page
      response.hasNext = response.has_next
      return response
    }
  },
  columns: [
    {
      headerName: 'No',
      valueGetter: (params) => params.node.rowIndex + 1,
      width: 60,
      sortable: true,
      resizable: true,
      filter: true,
      cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']

    },
    {
      field: 'no_dinas_luar',
      headerName: 'No.Dinas Luar',
      filter: true,
      sortable: true,
      flex: 1,
      filter: 'ColFilter',
      resizable: true,
      cellClass: ['border-r', '!border-gray-200']
    },
    {
      field: 'tanggal',
      headerName: 'Tanggal',
      filter: true,
      sortable: true,
      flex: 1,
      filter: 'ColFilter',
      resizable: true,
      cellClass: ['border-r', '!border-gray-200']
    },
    {
      field: 'supir.nama',
      headerName: 'Nama Sopir',
      filter: true,
      sortable: true,
      flex: 1,
      filter: 'ColFilter',
      resizable: true,
      cellClass: ['border-r', '!border-gray-200' , 'justify-end']
    },
    {
      field: 'total_amt',
      headerName: 'Jumlah',
      filter: true,
      sortable: true,
      filter: 'ColFilter',
      resizable: true,
      flex: 1,
      cellClass: ['border-r', '!border-gray-200'],
      valueFormatter: params => {
    // Format number to Rupiah currency
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(params.value);
  },
    },
  {
    // field: 'status',
    headerName: 'Status',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText:true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ( params ) => {
      return params.data['status'] == 1
        ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 'POST' ? `<span class="text-amber-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 'DRAFT' ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 'completed' ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 11 ?  `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 21 ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 5 ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>` 
        : (params.data['status'] == 6 ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>` 
        : (params.data['status'] == 7 ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 9 ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Status Tidak Terdaftar</span>`))))))))
        )
    }
  }
  ]
})

onActivated(() => {
  //  reload table api landing
  if (apiTable.value) {
    if (route.query.reload) {
      apiTable.value.reload()
    }
  }
})

onMounted(()=> {
  if (apiTable.value) {
      setTimeout(() => {
    apiTable.value.reload();
  }, 200);
  }
})

const filterButton = ref(null);
function filterShowData(status) {
  filterButton.value = filterButton.value === status ? null : status;
  landing.api.params.where = filterButton.value !== null ? `this.status='${filterButton.value}'` : null;
  apiTable.value.reload();
}

//  @endif -------------------------------------------------END
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))
