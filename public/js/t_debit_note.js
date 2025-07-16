import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated, computed } from 'vue'

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
let isFinish = ref(false)

// ------------------------------ PERSIAPAN
const endpointApi = '/t_debit_note'
onBeforeMount(() => {
  document.title = is_approval ? 'Approval Debit Note' : 'Transaksi Debit Note'
})


//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
const activeTab = ref(1)
let initialValues = {}
const changedValues = []

const values = reactive({
  // tipe_debit_note: 317,
  tipe_cn: 'PIUTANG',
  total_debit_note: 0,
  status: 'DRAFT',
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
        dataURLAprv = `${store.server.url_backend}/operation/t_debit_note/detail?id=${route.params.id}`
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
        console.log('Approval Data', resultTrxJson)

        values.interval = resultJson?.data.approval
        values.approval = resultJson?.data.approval
        values.trx = resultJson?.data.trx
        values.datalog = resultJson?.data.approval_log
        initialValues = resultTrxJson.data

        initialValues.tipe_cn = initialValues['tipe_debit_note.deskripsi4']

        detailArr.value = initialValues.t_debit_note_d.map((dt) => ({
          ...dt,
          no_pi: dt['t_purchase_invoice.no_pi'] || dt['t_tagihan.no_tagihan'],
          tgl_pi: dt['t_purchase_invoice.tanggal'] || dt['t_tagihan.tgl'],
          nilai_hutang: parseFloat(dt['t_purchase_invoice.grand_total'] || dt['t_tagihan.grand_total_amount'] || 0),
          bayar: parseFloat((dt['t_purchase_invoice.grand_total'] || dt['t_tagihan.grand_total_amount'] || 0) - (dt['t_purchase_invoice.utang'] || dt['t_tagihan.piutang'] || 0)),
          sisa_hutang: parseFloat(dt['t_purchase_invoice.utang'] ?? dt['t_tagihan.piutang'] ?? 0),
          currency: 'IDR',
          rate: 1,
          sub_total_amount: dt['sub_total_amount'],
          catatan: dt['catatan']
        }))

        const allSubDebitNotes = initialValues.t_debit_note_d.flatMap(item => item.t_sub_debit_note);

        subDetail.value = allSubDebitNotes.map((dt2) => ({
          ...dt2,
          m_coa_id: dt2['m_coa.id'],
          amount: dt2['amount'],
          tipe_perkiraan: dt2['m_coa.tipe_perkiraan']
        }))

        // logic finish & Approved data
        isApproved.value = resultTrxJson?.data?.status == 'APPROVED' ? true : false

        //         if (Array.isArray(initialValues['t_pembayaran_hutang_d'])) {
        //   initialValues['t_pembayaran_hutang_d'].forEach((detail) => {
        //     detailArr.value.push(detail);
        //   });
        // }

      } else {
        const editedId = route.params.id;
        // Construct the data URL
        const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`;
        isRequesting.value = true;

        const params = { join: true, transform: false, order_type: "ASC" };
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

        if (actionText.value?.toLowerCase() === 'copy') {
          initialValues.status = 'DRAFT';
          initialValues.no_draft = null;
          initialValues.no_debit_note = null;
        }
        initialValues.tipe_cn = initialValues['tipe_debit_note.deskripsi4']

        detailArr.value = initialValues.t_debit_note_d.map((dt) => ({
          ...dt,
          no_pi: dt['t_purchase_invoice.no_pi'] || dt['t_tagihan.no_tagihan'],
          tgl_pi: dt['t_purchase_invoice.tanggal'] || dt['t_tagihan.tgl'],
          nilai_hutang: parseFloat(dt['t_purchase_invoice.grand_total'] || dt['t_tagihan.grand_total_amount'] || 0),
          bayar: parseFloat((dt['t_purchase_invoice.grand_total'] || dt['t_tagihan.grand_total_amount'] || 0) - (dt['t_purchase_invoice.utang'] || dt['t_tagihan.piutang'] || 0)),
          sisa_hutang: parseFloat(dt['t_purchase_invoice.utang'] ?? dt['t_tagihan.piutang'] ?? 0),
          currency: 'IDR',
          rate: 1,
          sub_total_amount: dt['sub_total_amount'],
          catatan: dt['catatan']
        }))

        const allSubDebitNotes = initialValues.t_debit_note_d.flatMap(item => item.t_sub_debit_note);

        subDetail.value = allSubDebitNotes.map((dt2) => ({
          ...dt2,
          m_coa_id: dt2['m_coa.nama_coa'],
          amount: dt2['amount'],
          tipe_perkiraan: dt2['m_coa.tipe_perkiraan']
        }))

      }
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


// DETAIL
const detailArr = ref([]);

const onDetailAdd = (e) => {
  e.forEach((row, index) => {
    row.no_urut = detailArr.value.length + 1;

    if (values.tipe_cn === 'HUTANG') {
      row.t_purchase_invoice_id = row.id;
      row.no_pi = row.no_pi || row.no_tagihan;
      row.tgl_pi = row.tanggal || row.tgl;
    } else if (values.tipe_cn === 'PIUTANG') {
      row.t_tagihan_id = row.id;
      row.no_pi = row.no_tagihan || row.no_pi;
      row.tgl_pi = row.tgl || row.tanggal;
    } else {
      row.t_purchase_invoice_id = null;
      row.t_tagihan_id = null;
      row.no_pi = row.no_pi || row.no_tagihan;
      row.tgl_pi = row.tanggal || row.tgl;
    }

    row.nilai_hutang = row.grand_total || row.grand_total_amount;

    row.sisa_hutang = row.utang ?? row.piutang ?? 0;
    row.currency = 'IDR';
    row.rate = 1;
    row.catatan = row.catatan ?? '';
    row.t_sub_debit_note = [];
    detailArr.value.push(row);
  })
}

//DETAIL 2
const onAddSubDetail = (e) => {
  detailArr.value.forEach(dt => {
    if(dt.id === values.ref_det_id){
      e.forEach(newE => {
        if (values.tipe_cn === 'HUTANG'){
          dt.t_sub_debit_note.push({
          ...newE,
          t_purchase_invoice_id: dt.t_purchase_invoice_id,
          m_coa_id: newE.id,
          tipe_perkiraan: newE.tipe_perkiraan,
          amount: 0,
          catatan: null
        });
        }
        else if(values.tipe_cn === 'PIUTANG'){
          dt.t_sub_debit_note.push({
          ...newE,
          t_tagihan_id: dt.t_tagihan_id,
          m_coa_id: newE.id,
          tipe_perkiraan: newE.tipe_perkiraan,
          amount: 0,
          catatan: null
        });
        }
        
        console.log(newE,'newE')
      })
    }
    console.log(dt,'dt onaddsubdetail')
  });
}

const compDetailArr = computed(()=>{
  let data = [];
  detailArr.value.forEach(dt=>{
    data.push(dt);
  })

  return data;
})

const subDetail = computed(()=>{
  let data = [];
  detailArr.value.forEach(dt => {
    dt.t_sub_debit_note.forEach(det => {
      data.push(det);
    })
  });
  return data;
})

// //DETAIL 2
// const detailArr2 = ref([]);

// const onDetailAdd2 = (e) => {
//   e.forEach((row, index) => {
//     row.no_urut = detailArr2.value.length + 1;
//     row.t_debit_note_d_id = values.no_tagihan;
//     row.m_coa_id = row.id
//     row.nama_coa = row.id
//     row.perkiraan_debit = row['tipe_perkiraan.id']
//     row.amount = 0
//     row.t_tagihan_id = values.tagihan_id
//     row.t_purchase_invoice_id = values.invoice_id
//     row.catatan = null
//     detailArr2.value.push(row);
//   });
// };

const totalDebitNote = computed(() => {
  let total = 0;
  detailArr.value.forEach(dt => {
    total = total + (dt.sub_total_amount ?? 0)
  })
  return total;
});

const subTotal2 = (item) => {
  const total = item.t_sub_debit_note.reduce((acc, dt) => acc + (dt.amount ?? 0), 0);
  item.sub_total_amount = total;
  return total;
};

// const SubTotal = computed(() => {
//   detailArr.value.forEach(dt => {
//     let total = 0;
//     const cn_d_id = dt['id'];
//     detailArr2.value.forEach(dt2 => {
//       if (cn_d_id == dt2['t_debit_note_d_id']) {
//         total = total + (dt2['amount'] || 0);
//       }
//     })
//     dt.sub_total_amount = (total || 0);
//     return (total || 0);
//   });
//   console.log(detailArr2, 'detailarr2 aaa')
// });

watchEffect(() => {
  // Reset total values\
  detailArr.value.forEach((item) => {
    item.bayar = item.nilai_hutang - item.sisa_hutang
  });
});

const removeDetail = async (index) => {
  const result = await swal.fire({
    icon: 'warning',
    text: 'Hapus Data Terpilih?',
    confirmButtonText: 'Yes',
    showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  // Get the id of the item being removed
  const removedItemId = detailArr.value[index]?.id;

  detailArr.value = detailArr.value.filter((item, i) => (i !== index));

  // Remove items from detailArr2 where t_debit_note_d_id matches the removed item's 
  subDetail.value = subDetail.value.filter(item => item.t_debit_note_d_id !== removedItemId);
}

const removeDetail2 = async (index) => {
  const result = await swal.fire({
    icon: 'warning',
    text: 'Hapus Data Terpilih?',
    confirmButtonText: 'Yes',
    showDenyButton: true,
  });
  if (!result.isConfirmed) return;
  subDetail.value = subDetail.value.filter((item, i) => (i !== index));
}

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
    // values.t_debit_note_d = detailArr;

    values.t_debit_note_d = detailArr.value.map(item => ({
      ...item,
      t_sub_debit_note: item.t_sub_debit_note.map((dt, index) => ({
        ...dt, no_urut: index + 1
      }))
    }));

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
      const postRes = await fetch(`${store.server.url_backend}/operation/t_debit_note/post`, {
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
        const dataURL = `${store.server.url_backend}/operation/t_debit_note/progress`
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
      show: (row) => row.status?.toUpperCase() === 'DRAFT',
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
    // {
    //   icon: 'copy',
    //   title: "Copy",
    //   class: 'bg-gray-600 text-light-100',

    //   click(row) {
    //     router.push(`${route.path}/${row.id}?action=Copy&` + tsId)
    //   }
    // },
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
              const dataURL = `${store.server.url_backend}/operation${endpointApi}/post`
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
      show: (row) => row.status === 'POST' || row.status?.toUpperCase() === 'REVISED',
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
              const dataURL = `${store.server.url_backend}/operation/t_debit_note/send_approval`
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
      searchfield: 'no_debit_note, pic.nama, m_perkiraan_akun_penyusutan.nama_coa',
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
    headerName: 'No Draft',
    field: 'no_draft',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'No Debit Note',
    field: 'no_debit_note',
    filter: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'Tanggal Penerimaan',
    field: 'tanggal',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'Customer',
    field: 'customer.nama_perusahaan',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'Supplier',
    field: 'supplier.nama',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },
  {
    headerName: 'Total Amount',
    field: 'total_debit_note',
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
    headerName: 'Tipe Debit Note',
    field: 'tipe_debit_note.deskripsi',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200']
  },

  {
    headerName: 'Catatan',
    field: 'catatan',
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
    // resizable: true,
    // wrapText: true,
    filter: 'ColFilter',
    cellRenderer: (params) => {
      return params.data['status'] == 1
        ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
        : (params.data['status'] == 'DRAFT' ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
          : (params.data['status'] == 'POST' ? `<span class="text-yellow-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'IN APPROVAL' ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : (params.data['status'] == 'APPROVED' ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                : (params.data['status'] == 'REVISED' ? `<span class="text-amber-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                  : (params.data['status'] == 'REJECTED' ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                    : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`)))))
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