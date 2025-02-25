import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const is_approval = route.query.is_approval ? true : false
const actionText = ref(route.params.id === 'create' ? 'Tambah' : route.query.action)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const formErrors = ref({})
const tsId = `ts=` + (Date.parse(new Date()))
let isApproved = ref(false)
const activeTabIndex = ref(0)
let isFinish = ref(false)

// ------------------------------ PERSIAPAN
const endpointApi = '/t_pembayaran_hutang'
onBeforeMount(() => {
  document.title = is_approval ? 'Approval Pembayaran Hutamh' : 'Transaksi Pembayaran Hutang'
})


//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []

const values = reactive({
  total_amt: 0,
  status: 'DRAFT',
 include_pph: 1
})

const formatRupiah = (amount) => {
  const formatter = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  });
  return formatter.format(amount);
};


onBeforeMount(async () => {
  // tampilkan default direktorat dengan store user comp.nama


  if (isRead) {
    //  READ DATA
    try {
      let dataURL = ''
      let dataURLAprv = ''
      let resAprv = ''
      if (route.query.is_approval) {
        dataURLAprv = `${store.server.url_backend}/operation/t_pembayaran_hutang/detail?id=${route.params.id}`
        isRequesting.value = true
        const apiApp = await fetch(dataURLAprv, {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        const resultJson = await apiApp.json()
        console.log(resultJson.data)
        const apiTrx = await fetch(`${store.server.url_backend}/operation${endpointApi}/${resultJson.data.approval.trx_id}`, {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        if (!apiTrx.ok || !apiApp.ok) throw new Error("Failed when trying to read data")
        
        const resultTrxJson = await apiTrx.json()
        console.log('Approval Data',resultTrxJson)
        
        values.interval = resultJson?.data.approval
        values.approval = resultJson?.data.approval
        values.trx = resultJson?.data.trx
        values.datalog = resultJson?.data.approval_log
        initialValues = resultTrxJson.data

        // logic finish & Approved data
        isApproved.value = resultTrxJson?.data?.status == 'APPROVED' ? true : false

              if (Array.isArray(initialValues['t_pembayaran_hutang_d'])) {
        initialValues['t_pembayaran_hutang_d'].forEach((detail) => {
          detailArr.value.push(detail);
        });
      }
      initialValues.include_pph = (initialValues.include_pph === true || initialValues.include_pph === 1) ? 1 : 0;
      
      } else {
      const editedId = route.params.id;
      // Construct the data URL
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`;
      isRequesting.value = true;

      const params = { join: true, transform: false };
      const fixedParams = new URLSearchParams(params);
      const response = await fetch(dataURL + '?' + fixedParams, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      });

      if (!response.ok) throw new Error("Failed when trying to read data");
      const resultJson = await response.json();
      initialValues = resultJson.data;
      console.log(initialValues,'dfdfd')
      console.log(initialValues.t_pembayaran_hutang_d);

      if (Array.isArray(initialValues['t_pembayaran_hutang_d'])) {
        initialValues['t_pembayaran_hutang_d'].forEach((detail) => {
          detailArr.value.push(detail);
        });
      }
      initialValues.include_pph = (initialValues.include_pph === true || initialValues.include_pph === 1) ? 1 : 0;
      initialValues.tipe_pembayaran_deskripsi = initialValues['tipe_pembayaran.deskripsi'];
      }
      

      await new Promise(resolve => setTimeout(resolve, 500));
      await rencana_PH({ id: initialValues.t_rencana_pembayaran_hutang_id });

    } catch (err) {
      isBadForm.value = true
      swal.fire({
        icon: 'error',
        text: err,
        allowOutsideClick: false,
        confirmButtonText: 'Kembali',
      }).then(() => {
        router.back()
      })
    }
    isRequesting.value = false
  }

  for (const key in initialValues) {
    values[key] = initialValues[key]
  }
})


async function rencana_PH(t_rencana_pembayaran_hutang_id) {
  detailArr1.value = [];
  try {
    if (!t_rencana_pembayaran_hutang_id || !t_rencana_pembayaran_hutang_id.id) {
      return;
    }
    const IdData = t_rencana_pembayaran_hutang_id.id;
    console.log(IdData);
    const dataURL = `${store.server.url_backend}/operation/t_rencana_pembayaran_hutang/${IdData}`;
    const params = {
      join: true,
      transform: false,
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

    console.log('dataLengkap RPH Detail', initialValues.t_rencana_pembayaran_hutang_d);

    if (initialValues && initialValues.t_rencana_pembayaran_hutang_d) {
      const mappedDetails = initialValues.t_rencana_pembayaran_hutang_d.map(item => {
        console.log("Mapping item:", item);

        const tanggalInvoice = item['t_purchase_invoice.tanggal'];
        const top = item['m_supplier.top'] || 0;

        // Periksa apakah tanggalInvoice ada dan valid
        let jatuh_Tempo = '';
        if (tanggalInvoice) {
          const [day, month, year] = tanggalInvoice.split('/').map(Number);
          const invoiceDate = new Date(year, month - 1, day);
          const jatuhTempoDate = new Date(invoiceDate);
          jatuhTempoDate.setDate(invoiceDate.getDate() + top);
          jatuh_Tempo = [
            jatuhTempoDate.getDate().toString().padStart(2, '0'),
            (jatuhTempoDate.getMonth() + 1).toString().padStart(2, '0'),
            jatuhTempoDate.getFullYear(),
          ].join('/');
        }

        return {
          id: item.id,
          jumlah_bayar: item.jumlah_bayar || 0,
          tgl_jt: jatuh_Tempo,
          no_referensi: item['t_purchase_invoice.no_pi'],
          jumlah: item['t_purchase_invoice.grand_total'],
          supplier: item['m_supplier.nama'],
        };
      });

      detailArr1.value = [
        ...detailArr1.value,
        ...mappedDetails,
      ];
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
//Detail1
const detailArr1 = ref([]);
const addDetail1 = () => {
  const tempItem = {
  }
  detailArr.value = [...detailArr.value, tempItem]
}
const onDetailAdd1 = (e) => {
  e.forEach(row => {
    jumlah_bayar : 0 
    detailArr1.value.push(row)
  })
}




const hapusDetail = async (index) => {
  try {
    const item = detailArr1.value[index];
    const dataURL = `${store.server.url_backend}/operation/t_rencana_pembayaran_hutang_d/${item.id}`;
    isRequesting.value = true;
    const res = await fetch(dataURL, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });

    if (!res.ok) {
      const resultJson = await res.json();
      throw new Error(resultJson.message || "Gagal menghapus data.");
    }
    const result = await swal.fire({
      icon: 'warning',
      title: 'Hapus Data Database!',
      text: 'Hapus Data Detail Terpilih?',
      confirmButtonText: 'Yes',
      showDenyButton: true,
    });

    if (!result.isConfirmed) return;
    detailArr1.value = detailArr1.value.filter((_, i) => i !== index);
    await swal.fire({
      icon: 'success',
      text: 'Hapus Detail RPH berhasil',
      confirmButtonText: 'OK',
    });
  } catch (err) {
    swal.fire({
      icon: 'error',
      text: err.message || "Terjadi kesalahan saat menghapus atau memuat ulang data.",
      confirmButtonText: 'OK',
    });
  } finally {
    isRequesting.value = false;
  }
};

// DETAIL
const detailArr = ref([]);
const addDetail = () => {
  const tempItem = {
  }
  detailArr.value = [...detailArr.value, tempItem]
}

const onDetailAdd = (e) => {
  e.forEach(row => {
    console.log('TEST', row)
    row.t_purchase_invoice_id = row.id || null
    row.tgl_pi = row.tanggal;
    row.tgl_jt = row['t_lpb.tanggal_sj_supplier']
    row.nilai_hutang = row.grand_total
    row.bayar = 0;
    row.sisa_hutang = row.grand_total
    row.total_bayar = 0;
    row.keterangan = row.catatan
    detailArr.value.push(row)
  })
}

const removeDetail = async (index) => {
  const result = await swal.fire({
    icon: 'warning',
    text: 'Hapus Data Terpilih?',
    confirmButtonText: 'Yes',
    showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  detailArr.value = detailArr.value.filter((item, i) => (i !== index));
}



watchEffect(() => {
  values.total_amt = 0;
  let totalBayarDetailArr1 = 0; 
  if (detailArr1.value.length > 0) {
    detailArr1.value.forEach((item) => {
      totalBayarDetailArr1 += item.jumlah_bayar || 0; 
    });
  }
  detailArr.value.forEach((item) => {
    item.sisa_hutang = item.nilai_hutang - item.bayar; 
    item.total_bayar = item.bayar; 
    values.total_amt += item.bayar; 
  });
  if (detailArr1.value.length > 0) {
    values.total_amt += totalBayarDetailArr1;
  } else {
    values.total_amt += totalBayarDetailArr1; 
  }
});




function onBack() {
  router.replace('/' + modulPath)
}

function onReset() {
  swal.fire({
    icon: 'warning',
    text: 'Reset this form data?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      for (const key in initialValues) {
        values[key] = initialValues[key]
      }
    }
  })
}

async function onSave(status) {
    try {
        values.t_pembayaran_hutang_d = detailArr;
        const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
        const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`;
        isRequesting.value = true;

        if (status === 'POST') {
            const saveRes = await fetch(dataURL, {
                method: isCreating ? 'POST' : 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                },
                body: JSON.stringify(values)
            });

            if (!saveRes.ok) {
                if ([400, 422].includes(saveRes.status)) {
                    const responseJson = await saveRes.json();
                    formErrors.value = responseJson.errors || {};
                    throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to save data");
                } else {
                    throw ("Failed when trying to save data");
                }
            }
            const savedData = await saveRes.json();
            const savedId = savedData.id;
            console.log('id yang di save', savedId);
            const postRes = await fetch(`${store.server.url_backend}/operation/t_pembayaran_hutang/post`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                },
                body: JSON.stringify({ id: savedId, ...values })
            });

            if (!postRes.ok) {
                throw ("Failed when trying to post data");
            }

            swal.fire({
                icon: 'success',
                text: 'Post Data berhasil'
            });
            router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())));

        } else if (status === 'DRAFT') {
            const saveRes = await fetch(dataURL, {
                method: isCreating ? 'POST' : 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                },
                body: JSON.stringify(values)
            });

            if (!saveRes.ok) {
                if ([400, 422].includes(saveRes.status)) {
                    const responseJson = await saveRes.json();
                    formErrors.value = responseJson.errors || {};
                    throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to save data");
                } else {
                    throw ("Failed when trying to save data");
                }
            }

            // Show success alert for save
            swal.fire({
                icon: 'success',
                text: 'Data berhasil disimpan sebagai Draft'
            });

            // Redirect to the homepage or desired page
            router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())));

        }

    } catch (err) {
        isBadForm.value = true;
        swal.fire({
            icon: 'error',
            text: err
        });
    } finally {
        isRequesting.value = false;
    }
}



async function progress(status) {
  swal.fire({
    icon: 'warning',
    text: status == 'APPROVED' ? 'Approve?' : status == 'REJECTED' ? 'Reject?' : 'Revise?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',

    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const dataURL = `${store.server.url_backend}/operation/t_pembayaran_hutang/progress`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: 'POST',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify({ id: route.params.id, type: status, note: 'aaaa' })
        })
        if (!res.ok) {
          if ([400, 422, 500].includes(res.status)) {
            const responseJson = await res.json()
            formErrors.value = responseJson.errors || {}
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
        // const resultJson = await res.json()
      } catch (err) {
        isBadForm.value = true
        swal.fire({
          icon: 'error',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',
          text: err
        })
      }
      isRequesting.value = false;
      router.replace('/' + modulPath);
    }
  })
}

//  @else----------------------- LANDING
const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) => row.status?.toUpperCase() === 'DRAFT' || row.status?.toUpperCase() === 'REVISED',
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
      show: (row) => row.status?.toUpperCase() === 'DRAFT' || row.status?.toUpperCase() === 'REVISED',
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
              const dataURL = `${store.server.url_backend}/operation/t_pembayaran_hutang/post`
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
{
      icon: 'location-arrow',
      title: "Send Approval",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status === 'POST',
      async click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Send Approval?',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',

          showDenyButton: true
        }).then(async (res) => {
          if (res.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/t_pembayaran_hutang/send_approval`
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
                if ([400, 422, 500].includes(res.status)) {
                  const responseJson = await res.json()
                  formErrors.value = responseJson.errors || {}
                  throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to post data")
                } else {
                  throw ("Failed when trying to post data")
                }
              }
              const responseJson = await res.json()
              swal.fire({
                icon: 'success',
                text: responseJson.message
              })
              // const resultJson = await res.json()
            } catch (err) {
              isBadForm.value = true
              swal.fire({
                icon: 'error',
                iconColor: '#1469AE',
                confirmButtonColor: '#1469AE',
                text: err
              })
            }
            isRequesting.value = false

            apiTable.value.reload()
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
      join: true,
      simplest: true,
      searchfield: 'this.id, this.no_draft, this.no_pembayaran, this.tanggal, this.tanggal_pembayaran, this.total_amt, this.keterangan',
    },
    onsuccess(response) {
      response.page = response.current_page;
      response.hasNext = response.has_next;
      return response;
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
    headerName: 'NOMOR DRAFT',
    field: 'no_draft',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'NOMOR PEMBAYARAN',
    field: 'no_pembayaran',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'TANGGAL',
    field: 'tanggal',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'TANGGAL PEMBAYARAN',
    field: 'tanggal_pembayaran',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'TOTAL AMOUNT',
    field: 'total_amt',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', 'justify-end', '!border-gray-200'],
    valueFormatter: (params) => {
      if (params.value != null) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(params.value);
      }
      return params.value;
    }
  },

  {
    headerName: 'CATATAN',
    field: 'keterangan',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
{
      headerName: 'Status',
      field: 'status',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      filter:false,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
      cellRenderer: (params) => {
        return params.data['status'] == 1
          ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
          : (params.data['status'] == 'DRAFT' ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'POST' ? `<span class="text-amber-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : (params.data['status'] == 'IN APPROVAL' ? `<span class="text-sky-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                : (params.data['status'] == 'APPROVED' ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                  : (params.data['status'] == 'REVISED' ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                    : (params.data['status'] == 'REJECTED' ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                    : (params.data['status'] == 'COMPLETED' ? `<span class="text-pink-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                      : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`))))))
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

//  @endif -------------------------------------------------END
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))