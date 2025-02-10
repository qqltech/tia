import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated } from 'vue'

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
const tsId = `ts=` + (Date.parse(new Date()))
const modalOpen = ref(false)
// Detail AJU
const detailArrAju = ref([])
const activeTabIndex = ref(0)
// ------------------------------ PERSIAPAN
const endpointApi = 't_tagihan'
onBeforeMount(() => {
  document.title = 'Transaksi Tagihan'
})

//  @if( $id )------------------- JS CONTENT ! PENTING JANGAN DIHAPUS
// HOT KEY
onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
})
onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeyDown);
})

const handleKeyDown = (event) => {
  if (event?.ctrlKey && event?.key === 's') {
    event.preventDefault();
    onSave();
  }
}

function closeModal(i) {
  modalOpen.value = false
}




const values = reactive({
  status: "DRAFT",
  total_jasa_cont_ppjk: 0,
  total_lain2_ppn: 0,
  total_ppn: 0,
  total_jasa_angkutan: 0,
  total_lain_non_ppn: 0,
  grand_total: 0,
  tarif_dp : 0,
  tgl: new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(new Date())
});



const defaultValues = () => {
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount);
}

// LOGIC DETAIL
let initialValues = {};
const changedValues = [];
const detailArr = ref([]);
const addDetail = () => {
  const tempItem = {
  };
  detailArr.value = [...detailArr.value, tempItem];
};

// Detail Tarif jasa
const detailArr1 = ref([]);
const addDetail1 = () => {
  const tempItem = {
    tarif: 0,
    satuan: 0,
    is_ppn: false,
  };
  detailArr1.value = [...detailArr1.value, tempItem];
};

const detailArr2 = ref([]);
const addDetail2 = () => {
  const tempItem = {

  };
  detailArr2.value = [...detailArr2.value, tempItem];
};


// detail tarif lain-lain
const detailArr3 = ref([]);
const addDetail3 = () => {
  const tempItem = {
    is_ppn: false,
    nominal: 0,
    tarif_realisasi: 0,
    qty: 0
  };
  detailArr3.value = [...detailArr3.value, tempItem];
};

const removeDetail = (index) => {
  detailArr3.value.splice(index, 1)
}





const detailArrOpen = ref([]);
const addDetailOpen = (detail) => {
  const tempItem = {

    ...detail
  };
  detailArrOpen.value = [...detailArrOpen.value, tempItem];
};
function openDetail(id) {
  detailArrOpen.value = detailArr.value.filter(dt => dt.id === id)[0].value.m_tarif_d_kontainer;
  modalOpen.value = true;
}

async function buku(no_buku_order) {
  if (!no_buku_order || !no_buku_order.id) {
    // Reset detail arrays
    detailArr.value = [];
    detailArr1.value = [];
    detailArr2.value = [];
    detailArr3.value = [];
    detailArrOpen.value = [];
    detailArrAju.value = [];
    
    // Reset values
    values.customer = '';
    values.ppn = 0;
    values.total_amount = 0;
    values.grand_total_amount = 0;
    values.grand_total_nota_rampung = 0;
    values.total_jasa_cont_ppjk=0;
    values.total_lain2_ppn=0;
    values.total_jasa_angkutan=0;
    values.total_lain_non_ppn=0;
    values.grand_total=0;
    values.total_setelah_ppn=0;
    values.total_ppn=0;

    return;
  }

  try {
    // Fetch General Container Data
    const dataURL1 = `${store.server.url_backend}/operation/m_general`;
    isRequesting.value = true;
    const params1 = {
      where: `this.group='UKURAN KONTAINER'`,
      transform: false,
    };
    const fixedParams1 = new URLSearchParams(params1);
    const res1 = await fetch(dataURL1 + '?' + fixedParams1, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });
    if (!res1.ok) throw new Error("Gagal saat mencoba membaca data");
    const resultJson1 = await res1.json();
    const deskripsiKeys = Array.isArray(resultJson1.data) ? resultJson1.data.map(item => item.deskripsi) : [];
    const dataURL = `${store.server.url_backend}/operation/t_buku_order/${no_buku_order.id}`;
    const params = {
      join: true,
      view_tarif: true,
      transform: false,
      scopes: 'withDetailAju',
    };
    const fixedParams = new URLSearchParams(params);
    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });
    if (!res.ok) throw new Error("Gagal saat mencoba membaca data");
    const resultJson = await res.json();
    const initialValues = resultJson.data;
    console.log('TARIF DP', initialValues.tarif_dp);
    detailArr.value = [];
    detailArr1.value = [];
    detailArr2.value = [];
    detailArr3.value = [];
    detailArrOpen.value = [];
    detailArrAju.value = [];

    initialValues.relation_ppjk?.forEach((itemAju) => {
      itemAju['t_ppjk_id'] = itemAju['id'];
      itemAju['peb_pib'] = itemAju['no_peb_pib'];
      itemAju['no_ppjk'] = itemAju['no_aju'];
      detailArrAju.value = [itemAju, ...detailArrAju.value];
    });

    values.total_tarif_dp = initialValues?.tarif_dp?.total_amount || 0;
    const tipe_kontainer = initialValues.tipe || 0;
    if (Array.isArray(initialValues['t_buku_order_d_npwp'])) {
      initialValues['t_buku_order_d_npwp'].forEach((detail) => {
        detail.no_buku_order = initialValues.id;
        detail.tipe = tipe_kontainer;
        detailArr.value.push(detail);

        if (Array.isArray(detail.tarif) && detail.tarif.length > 0) {
          detail.tarif.forEach((tarif) => {
            if (Array.isArray(tarif.jasa)) {
              tarif.jasa.forEach((jasa) => {
                jasa.satuan = jasa.satuan || 0;
                jasa.tarif = jasa.tarif === undefined || jasa.tarif === null ? 0 : parseFloat(jasa.tarif);

                const existingIndex = detailArr1.value.findIndex(item => item.id === jasa.id);
                if (existingIndex > -1) {
                  detailArr1.value[existingIndex] = jasa;
                } else {
                  detailArr1.value.push(jasa);
                  console.log('DATA JASA', detailArr1.value);
                }
              });
            }
          });
        }
      });
    }
    const uniqueItems = new Map(); 
    initialValues.t_buku_order_d_npwp.forEach(item => {
      item.tarif.forEach(tarifItem => {
        tarifItem.lain.forEach(lainItem => {
          const ubahNominal = parseFloat(lainItem.nominal).toFixed(0);
          lainItem.keterangan = lainItem.deskripsi;
          lainItem.nominal = ubahNominal;
          lainItem.tarif_realisasi = ubahNominal;
          if (!uniqueItems.has(lainItem.id)) {
            uniqueItems.set(lainItem.id, lainItem);
          }
        });
      });
    });
    detailArr3.value = Array.from(uniqueItems.values());
    values.customer = initialValues.m_customer_id || '';
    values.grand_total_nota_rampung = initialValues.grand_total_nota_rampung || '';

    if (Array.isArray(initialValues['t_buku_order_d_aju'])) {
      initialValues['t_buku_order_d_aju'].forEach((detail) => {
        detailArr2.value.push(detail);
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





async function generateTotal() {
  if (!values.no_buku_order) {
    swal.fire({
      icon: 'warning',
      text: 'Pilih No Buku Order terlebih dahulu!',
      confirmButtonText: 'OK',
    });
    return;
  }
  try {
    const grandTotalNotaRampung = values.grand_total_nota_rampung || 0;
    const payload = {
      detailArr: detailArr.value,
      detailArr1: detailArr1.value,
      detailArr2: detailArrAju.value,
      detailArr3: detailArr3.value,
      t_buku_order_id: values.no_buku_order,
      tarif_coo: values.tarif_coo || 0,
      tarif_ppjk: values.tarif_ppjk || 0,
      ppn: values.ppn || false,
      total_tarif_dp: values.total_tarif_dp || 0,
      grand_total_nota_rampung: grandTotalNotaRampung
    };
    console.log(payload)

    const dataURL = `${store.server.url_backend}/operation/${endpointApi}/calculate_tagihan`;
    const res = await fetch(dataURL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify(payload),
    });
    if (!res.ok) throw new Error('Failed to generate total.');
    const hasil = await res.json();
    console.log(hasil)

    values.grand_total = hasil.grand_total || 0;
    values.total_jasa_cont_ppjk = hasil.total_jasa_cont_ppjk || 0;
    values.total_lain2_ppn = hasil.total_lain2_ppn || 0;
    values.total_ppn = hasil.total_ppn || 0;
    values.total_jasa_angkutan = hasil.total_jasa_angkutan || 0;
    values.total_lain_non_ppn = hasil.total_lain_non_ppn || 0;

    swal.fire({
      icon: 'success',
      text: 'Total Berhasil Di Generated',
      confirmButtonText: 'OK',
    });
  } catch (err) {
    console.error(err);
    swal.fire({
      icon: 'error',
      text: err.message || 'An error occurred while generating total.',
      confirmButtonText: 'OK',
    });
  }
}


onBeforeMount(async () => {
  if (isRead) {
    try {
      const editedId = route.params.id;
      const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${editedId}`;
      isRequesting.value = true;
      const params = { join: true, transform: false };
      const fixedParams = new URLSearchParams(params);
      const res = await fetch(dataURL + '?' + fixedParams, {
        headers: {
          'Content-Type': 'application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`,
        },
      });
      if (!res.ok) throw new Error("Failed when trying to read data");
      const resultJson = await res.json();
      initialValues = resultJson.data;

      if (actionText.value?.toLowerCase() === 'copy') {
        delete initialValues.status;
        delete initialValues.tgl;
      }
      if (actionText.value?.toLowerCase() === 'edit') {
        delete initialValues.tgl;
        initialValues.tgl = new Date().toLocaleDateString('id-ID', { month: '2-digit', day: '2-digit', year: 'numeric' });
      }
      for (const key in initialValues) {
        values[key] = initialValues[key];
      }
      console.log("TARIF TAGIHAN", initialValues.t_tagihan_d_lain)

      await new Promise(resolve => setTimeout(resolve, 500));
      await buku({ id: initialValues.no_buku_order });
      detailArr3.value = initialValues.t_tagihan_d_lain || [];
    } catch (err) {
      isBadForm.value = true;
      swal.fire({
        icon: 'error',
        text: err.message || 'An error occurred while reading data',
        allowOutsideClick: false,
        confirmButtonText: 'Kembali',
      }).then(() => {
        router.back();
      });
    } finally {
      isRequesting.value = false;
    }
  } else {
    defaultValues();
  }
});

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
          for (const key in values) {
            delete values[key]
          }
          defaultValues()
        }
      }
    })
  }

  setTimeout(() => {
    defaultValues()
  }, 100)
}
async function onSave(isPost = false) {
  try {
    values.t_tagihan_d_npwp = detailArr.value;
    values.t_tagihan_d_tarif = detailArr1.value;
    values.t_tagihan_d_lain = detailArr3.value;

    const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? isPost ? '?post=true' : '' : isPost ? '/' + route.params.id + '?post=true' : '/' + route.params.id}`;
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
  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'warning',
      text: err
    });
  }
  isRequesting.value = false;
}



// FUNGSI BUTTON KEMBALI
function onBack() {
  swal.fire({
    title: 'Warning!',
    icon: 'warning',
    text: 'Apakah anda yakin ingin kembali ?',
    showCancelButton: true,
    confirmButtonText: 'Ya',
    cancelButtonText: 'Tidak',
    reverseButtons: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d'
  }).then((result) => {
    if (result.isConfirmed) {
      router.replace('/' + modulPath);
    }
  });
}

//  @else----------------------- LANDING
const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) => row.status?.toUpperCase() == 'DRAFT',
      click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Hapus Data Terpilih?',
          confirmButtonText: 'Yes',
          showDenyButton: true,
        }).then(async (result) => {
          if (result.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/${endpointApi}/${row.id}`
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
              const resultJson = await res.json()
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
        router.push(`${route.path}/${row.id}?` + tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      show: (row) => row.status?.toUpperCase() == 'DRAFT',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&` + tsId)
      }
    },
    {
      icon: 'copy',
      title: "Copy",
      class: 'bg-gray-600 text-light-100',

      click(row) {
        router.push(`${route.path}/${row.id}?action=Copy&` + tsId)
      }
    },
    {
      icon: 'print',
      title: "Cetak",
      class: 'bg-gray-600 text-light-100',
      click(row) {
        window.open(`${store.server.url_backend}/web/report_tagihan?export=pdf&size=a4&orientation=potrait&group=SATUAN%20JASA&id=${row.id}`)
      }
    },
    {
      icon: 'table',
      title: "Unduh Excel",
      class: 'bg-gray-600 text-light-100',
      click(row) {
        window.open(`${store.server.url_backend}/web/surat_jalan?export=excel&size=a4&orientation=potrait&group=SATUAN%20JASA&id=${row.id}`)
      }
    },
    {
      icon: 'location-arrow',
      title: "Post Data",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status?.toUpperCase() === 'DRAFT',
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
              const dataURL = `${store.server.url_backend}/operation/t_tagihan/post`
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
    url: `${store.server.url_backend}/operation/${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield: 'this.no_draft, this.no_tagihan, no_buku_order.no_buku_order, customer.nama_perusahaan, this.catatan'
    },
    onsuccess(response) {
      response.page = response.current_page
      response.hasNext = response.has_next
      return response
    }
  },
  columns: [{
    headerName: 'No',
    valueGetter: (params) => params.node.rowIndex + 1,
    width: 60,
    sortable: true,
    resizable: true,
    filter: true,
    cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
  },
  {
    headerName: 'No.Draft',
    field: 'no_draft',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'No. Tagihan',
    field: 'no_tagihan',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'No. Buku Order',
    field: 'no_buku_order.no_buku_order',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Customer',
    field: 'customer.nama_perusahaan',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Tanggal',
    field: 'tgl',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  // {
  //   headerName: 'No.Faktur Pajak',
  //   field: 'no_faktur_pajak.no_faktur_pajak',
  //   filter: true,
  //   sortable: true,
  //   flex: 1,
  //   filter: 'ColFilter',
  //   resizable: true,
  //   wrapText: true,
  //   cellClass: ['border-r', '!border-gray-200', 'justify-start']
  // },
  {
    headerName: 'Total',
    field: 'grand_total',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start'],
    valueFormatter: (params) => {

      if (params.value) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(params.value);
      }
      return params.value;
    }
  },
  {
    headerName: 'Catatan',
    field: 'catatan',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    wrapText: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    // field: 'status',
    headerName: 'Status',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true, wrapText: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: (params) => {
      return params.data['status'] == 1
        ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 'POST' ? `<span class="text-amber-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
          : (params.data['status'] == 'DRAFT' ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'completed' ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : (params.data['status'] == 11 ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                : (params.data['status'] == 21 ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                  : (params.data['status'] == 5 ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                    : (params.data['status'] == 6 ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                      : (params.data['status'] == 7 ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                        : (params.data['status'] == 9 ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                          : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Status Tidak Terdaftar</span>`))))))))
        )
    }
  },
  ]
})

const filterButton = ref(null);
function filterShowData(status) {
  filterButton.value = filterButton.value === status ? null : status;
  landing.api.params.where = filterButton.value !== null ? `this.status='${filterButton.value}'` : null;
  apiTable.value.reload();
}

onActivated(() => {
  //  reload table api landing
  if (apiTable.value) {
    if (route.query.reload) {
      apiTable.value.reload()
    }
  }
})


// reload web jika tidak ingin manual ctrl + R
onMounted(() => {
  if (apiTable.value) {
    setTimeout(() => {
      apiTable.value.reload();
    }, 250);
  }
})


//  @endif -------------------------------------------------END
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))