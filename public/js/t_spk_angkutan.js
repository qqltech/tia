import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, onBeforeUnmount, watchEffect, onActivated, watch } from 'vue'
const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')
const isRead = route.params.id && route.params.id !== 'create'
const actionText = ref(route.params.id === 'create' ? 'Create' : route.query.action != 'EditContainer' ? route.query.action : false)
const actionSingleEdit = ref(route.query.action === 'EditContainer' ? 'EditContainer' : false)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const formErrors = ref({})
const tsId = `ts=` + (Date.parse(new Date()))
const isApproval = route.query.is_approval;
// console.log(store.user.data['id'],'user aaaa');
// ENDPOINT API
const endpointApi = 't_spk_angkutan'
onBeforeMount(() => {
  document.title = 'Transaction SPK Angkutan'
})

// @if( !$id ) | --- LANDING TABLE --- |

// TABLE
const table = reactive({
  api: {
    url: `${store.server.url_backend}/operation/${endpointApi}`,
    headers: {
      'Content-Type': 'application/json',
      authorization: `${store.user.token_type} ${store.user.token}`,
    },
    params: {
      // simplest: true,
      getNoBukuOrder: true,
      searchfield: 'this.no_spk, tipe_spk.deskripsi, supir.nama, sektor1.deskripsi, this.total_sangu, t_buku_order_1.no_buku_order, t_buku_order_2.no_buku_order, this.catatan',
    },
    onsuccess(response) {
      return { ...response, page: response.current_page, hasNext: response.has_next };
    },
  },
  columns: [
    {
      headerName: 'No',
      valueGetter: ({ node }) => node.rowIndex + 1,
      width: 60,
      sortable: false,
      cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
    },
    {
      headerName: 'No. SPK',
      field: 'no_spk',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Tipe SPK',
      field: 'tipe_spk.deskripsi',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'No. Order 1',
      field: 't_detail_npwp_container_1.no_buku_order',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'No. Order 2',
      field: 't_detail_npwp_container_2.no_buku_order',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Supir',
      field: 'supir.nama',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Sektor',
      field: 'sektor1.deskripsi',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
    },
    {
      headerName: 'Sangu',
      field: 'total_sangu',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-end',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
      cellRenderer: (params) => {
        return params.data['total_sangu']
          ? 'Rp ' + params.data['total_sangu']
            .toString()
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".")
          : '';
      }
    },
    {
      headerName: 'Catatan',
      field: 'catatan',
      flex: 1,
      cellClass: ['border-r', '!border-gray-200', 'justify-start',],
      sortable: true,
      // resizable: true,
      // wrapText: true,
      filter: 'ColFilter',
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
          : (params.data['status'] == 'DRAFT' ? `<span class="text-gray-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
            : (params.data['status'] == 'POST' ? `<span class="text-yellow-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
              : (params.data['status'] == 'IN APPROVAL' ? `<span class="text-blue-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                : (params.data['status'] == 'APPROVED' ? `<span class="text-green-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                  : (params.data['status'] == 'REVISED' ? `<span class="text-purple-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                    : (params.data['status'] == 'REJECTED' ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                      : (params.data['status'] == 'CANCEL' ? `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`
                        : `<span class="text-red-600 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${params.data['status']?.toUpperCase()}</span>`))))))
            )
      }
    },
  ],
  actions: [
    {
      title: 'Hapus', icon: 'trash', class: 'bg-red-600 text-light-100', click: deleteData,
      show: (row) => row.status === 'DRAFT'
    },
    {
      title: 'Read', icon: 'eye', class: 'bg-green-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?${tsId}`)
    },
    {
      title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Edit&${tsId}`),
      show: (row) => row.status === 'DRAFT' || row.status === 'REVISED'
    },
    {
      title: 'Edit', icon: 'edit', class: 'bg-blue-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=EditContainer&${tsId}`),
      show: (row) => row.status == 'APPROVED' && row.is_con_edit != true
    },
    {
      title: 'Copy', icon: 'copy', class: 'bg-gray-600 text-light-100',
      click: row => router.push(`${route.path}/${row.id}?action=Copy&${tsId}`)
    },

    {
      icon: 'ban',
      title: "Cancel Data",
      class: 'bg-orange-700 rounded-lg text-white',
      // show: (row) => row['status']?.toUpperCase() == 'APPROVED' && store.user.data.tipe?.toUpperCase() === 'SUPER ADMIN',
      async click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Yakin untuk cancel data?',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',
          showDenyButton: true
        }).then(async (res) => {
          if (res.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/${endpointApi}/cancel?id=${row.id}`
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
                  throw (responseJson?.message + " " + responseJson?.data?.errorText || "Failed when trying to cancel data")
                } else {
                  throw ("Failed when trying to cancel data")
                }
              }
              // const responseJson = await res?.json()
              swal.fire({
                icon: 'success',
                text: "Data Berhasil di Cancel!"
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
    {
      icon: 'location-arrow',
      title: "Send Approval",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status === 'DRAFT' || row.status === 'REVISED' || row.status === 'PRINTED',
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
              const dataURL = `${store.server.url_backend}/operation/${endpointApi}/send_approval`
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
    {
      icon: 'print',
      title: "Cetak",
      class: 'bg-amber-600 text-light-100',
      show: (row) => (row['status'] == 'APPROVED' && row['is_printed'] == false),
      async click(row) {
        try {
          const dataURL = `${store.server.url_backend}/operation/t_spk_angkutan/print?id=${row.id}`;
          isRequesting.value = true;
          const response = await fetch(dataURL, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
          });

          if (!response.ok) {
            const responseJson = await response.json();
            if ([400, 422, 500].includes(response.status)) {
              formErrors.value = responseJson.errors || {};
              throw new Error(responseJson?.message + " " + responseJson?.data?.errorText || "Failed when trying to post data");
            } else {
              throw new Error("Failed when trying to print data");
            }
          }

          const responseJson = await response.json();
          swal.fire({
            icon: 'success',
            text: responseJson?.message || 'PRINTED'
          });
          window.open(`${store.server.url_backend}/web/spk_angkutan?id=${row.id}`)
        } catch (err) {
          isBadForm.value = true;
          swal.fire({
            icon: 'error',
            iconColor: '#1469AE',
            confirmButtonColor: '#1469AE',
            text: err.message
          });
        } finally {
          isRequesting.value = false;
          apiTable.value.reload();
        }
      }
    },
    {
      icon: 'rocket',
      title: "Cetak",
      class: 'bg-violet-600 text-light-100',
      show: (row) => (row['status'] == 'APPROVED' && row['is_printed'] == false),
      async click(row) {
        try {
          console.log(row.id, 'id spk print')
          await tesPrint(row.id);
        } catch (err) {
          isBadForm.value = true;
          swal.fire({
            icon: 'error',
            iconColor: '#1469AE',
            confirmButtonColor: '#1469AE',
            text: err.message
          });
        } finally {
          isRequesting.value = false;
          apiTable.value.reload();
        }
      }
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

const defaultThermal = reactive({});
const thermal = reactive({});

onBeforeMount(async () => {

  const initThermal = {
    interface: 'POS-80',
    port: '9000',
    url: '/print/custom'
  }

  for (const key in initThermal) {
    defaultThermal[key] = initThermal[key]
    thermal[key] = initThermal[key]
  }

  if (localStorage.getItem('thermal_interface')) {
    thermal.interface = localStorage.getItem('thermal_interface')
  }
  if (localStorage.getItem('thermal_port')) {
    thermal.port = localStorage.getItem('thermal_port')
  }
})

function terbilang(number) {
  const huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
  let temp = '';

  if (number < 12) {
    temp = huruf[number];
  } else if (number < 20) {
    temp = terbilang(number - 10) + ' Belas';
  } else if (number < 100) {
    temp = terbilang(Math.floor(number / 10)) + ' Puluh ' + terbilang(number % 10);
  } else if (number < 200) {
    temp = 'Seratus ' + terbilang(number - 100);
  } else if (number < 1000) {
    temp = terbilang(Math.floor(number / 100)) + ' Ratus ' + terbilang(number % 100);
  } else if (number < 2000) {
    temp = 'Seribu ' + terbilang(number - 1000);
  } else if (number < 1000000) {
    temp = terbilang(Math.floor(number / 1000)) + ' Ribu ' + terbilang(number % 1000);
  } else if (number < 1000000000) {
    temp = terbilang(Math.floor(number / 1000000)) + ' Juta ' + terbilang(number % 1000000);
  }

  return temp.trim();
}

function getCurrentDateTime() {
  const now = new Date();

  const day = String(now.getDate()).padStart(2, '0');
  const month = String(now.getMonth() + 1).padStart(2, '0'); // Januari = 0
  const year = now.getFullYear();

  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  const seconds = String(now.getSeconds()).padStart(2, '0');

  return `${day}/${month}/${year}, ${hours}:${minutes}:${seconds}`;
}

function number_format(number, decimals = 0, decPoint = ',', thousandsSep = '.') {
  if (isNaN(number) || number === null) return '0';

  const fixedNumber = Number(number).toFixed(decimals);
  const parts = fixedNumber.split('.');

  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSep);

  return parts.length > 1 ? parts.join(decPoint) : parts[0];
}

async function tesPrint(spk_angkutan_id) {
  isRequesting.value = true;

  let spk_data = [];
  let fieldData1 = null;
  let fieldData2 = null; // Ensure fieldData is initialized properly

  try {
    // Fetch print data
    const URLdata = `${store.server.url_backend}/operation/t_spk_angkutan/updatePrintData`;
    const parameter = {
      join: true,
      transform: true,
      t_spk_id: `${spk_angkutan_id}`,
    };
    const fixParams = new URLSearchParams(parameter);

    const response = await fetch(`${URLdata}?${fixParams}`, {
      method: "GET", // Explicitly define method
      headers: {
        "Content-Type": "application/json",
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });

    if (!response.ok) throw new Error("Gagal saat mencoba membaca data");

    // Fetch print data
    const dataURL = `${store.server.url_backend}/operation/t_spk_angkutan/getPrintData`;
    const params = {
      join: true,
      transform: true,
      t_spk_id: `${spk_angkutan_id}`,
    };
    const fixedParams = new URLSearchParams(params);

    const res = await fetch(`${dataURL}?${fixedParams}`, {
      method: "GET", // Explicitly define method
      headers: {
        "Content-Type": "application/json",
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });

    if (!res.ok) throw new Error("Gagal saat mencoba membaca data");

    const resultJson = await res.json();
    spk_data = resultJson;

    // Prepare fieldData after fetching spk_data
    const spkData = spk_data?.data[0] ?? {};

    //Field Data 1
    fieldData1 = {
      interface: thermal.interface,
      data: [
        { "type": "alignCenter" }, { "type": "setTextDoubleHeight" }, { "type": "println", "value": "SPK ANGKUTAN" },
        { "type": "setTextNormal" }, { "type": "alignLeft" }, { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": `Tanggal In : ${spkData?.tanggal_in}`, "align": "LEFT", "cols": 24 },
            { "text": `Tanggal Out : ${spkData?.tanggal_out}`, "align": "RIGHT", "cols": 24 }
          ]
        },
        { "type": "drawLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Order 1", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.no_buku_order} / ${spkData?.isi_container_1_deskripsi[0]} /${spkData?.customer_kode}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Order 2", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.no_buku_order2} / ${spkData?.isi_container_2_deskripsi[0]} /${spkData?.customer_kode2}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No. SPK", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.no_spk}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Pagi/Sore", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.waktu_out_deskripsi} / ${spkData?.waktu_in_deskripsi}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Head", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.head_kode}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Chasis 1", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.chasis1_kode}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Supir", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.supir_nip}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Chasis 2", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.chasis2_kode}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Trip", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.trip_kode}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Sektor", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.sektor1_deskripsi}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Container", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.ukuran1_deskripsi} Ft`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Dari", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.dari}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Ke", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.ke}`, "align": "LEFT", "cols": 12 }
          ]
        },
        { "type": "drawLine" },
        { "type": "setTextDoubleHeight" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Sangu", "align": "LEFT", "cols": 5 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `Rp ${number_format(parseFloat(spkData?.sangu), 0, ',', '.')}`, "align": "LEFT", "cols": 40 }
          ]
        },
        { "type": "setTextNormal" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Tambahan Biaya Lain-lain", "align": "LEFT", "cols": 24 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.catatan}`, "align": "LEFT", "cols": 21 }
          ]
        },
        { "type": "drawLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No.", "align": "LEFT", "cols": 4 },
            { "text": "Keterangan", "align": "LEFT", "cols": 29 },
            { "text": "Jumlah", "align": "RIGHT", "cols": 15 }
          ]
        },
        { "type": "drawLine" },
        ...spk_data?.nospkd?.map((dt, idx) => ({
          "type": "tableCustom",
          "value": [
            { "text": idx + 1, "align": "LEFT", "cols": 4 },
            { "text": dt?.keterangan, "align": "LEFT", "cols": 29 },
            { "text": `Rp ${number_format(parseFloat(dt?.nominal), 0, ',', '.')}`, "align": "RIGHT", "cols": 15 }
          ]
        })),
        { "type": "drawLine" },
        { "type": "setTextDoubleHeight" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Total", "align": "LEFT", "cols": 5 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `Rp ${number_format(parseFloat(spkData?.total_sangu), 0, ',', '.')}`, "align": "RIGHT", "cols": 40 }
          ]
        },
        { "type": "setTextNormal" },
        { "type": "drawLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Terbilang", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${terbilang(parseFloat(spkData?.total_sangu))} Rupiah`, "align": "LEFT", "cols": 36 }
          ]
        },
        { "type": "drawLine" },
        { "type": "println", "value": "Mengetahui," },
        { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Admin / Kasir", "align": "CENTER", "cols": 15 },
            { "text": "Sopir", "align": "CENTER", "cols": 16 },
            { "text": "Pengebon", "align": "CENTER", "cols": 17 }
          ]
        },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "(Kusmiati)", "align": "CENTER", "cols": 16 },
            { "text": `(${spkData?.supir_nip})`, "align": "CENTER", "cols": 16 },
            { "text": "(Budi)", "align": "CENTER", "cols": 16 }
          ]
        },
        { "type": "newLine" },
        { "type": "println", "value": `Dicetak pada tanggal : ${getCurrentDateTime()}` },
        { "type": "println", "value": `Operator : ${spk_data?.user_print?.name}-PC # ${spk_data?.user_print?.nip}` },
        { "type": "println", "value": `Sudah di print : ${spkData?.jumlah_print}x` },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        {
          "type": "tableCustom", "value": [
            { "text": "", "align": "RIGHT", "cols": 16 },
            { "text": "", "align": "RIGHT", "cols": 13 },
            { "text": "RANGKAP 1", "align": "RIGHT", "cols": 16 }
          ]
        },
        { "type": "cut" }
      ]
    }

    //Field Data 2
    fieldData2 = {
      interface: thermal.interface,
      data: [
        { "type": "alignCenter" }, { "type": "setTextDoubleHeight" }, { "type": "println", "value": "SPK ANGKUTAN" },
        { "type": "setTextNormal" }, { "type": "alignLeft" }, { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": `Tanggal In : ${spkData?.tanggal_in}`, "align": "LEFT", "cols": 24 },
            { "text": `Tanggal Out : ${spkData?.tanggal_out}`, "align": "RIGHT", "cols": 24 }
          ]
        },
        { "type": "drawLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Order 1", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.no_buku_order} / ${spkData?.isi_container_1_deskripsi[0]} /${spkData?.customer_kode}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Order 2", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.no_buku_order2} / ${spkData?.isi_container_2_deskripsi[0]} /${spkData?.customer_kode2}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No. SPK", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.no_spk}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Pagi/Sore", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.waktu_out_deskripsi} / ${spkData?.waktu_in_deskripsi}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Head", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.head_kode}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Chasis 1", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.chasis1_kode}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Supir", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.supir_nip}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Chasis 2", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.chasis2_kode}`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Trip", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.trip_kode}`, "align": "LEFT", "cols": 36 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Sektor", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.sektor1_deskripsi}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Container", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.ukuran1_deskripsi} Ft`, "align": "LEFT", "cols": 12 }
          ]
        },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Dari", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.dari}`, "align": "LEFT", "cols": 11 },
            { "text": "", "align": "CENTER", "cols": 1 },
            { "text": "Ke", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.ke}`, "align": "LEFT", "cols": 12 }
          ]
        },
        { "type": "drawLine" },
        { "type": "setTextDoubleHeight" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Sangu", "align": "LEFT", "cols": 5 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `Rp ${number_format(parseFloat(spkData?.sangu), 0, ',', '.')}`, "align": "LEFT", "cols": 40 }
          ]
        },
        { "type": "setTextNormal" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Tambahan Biaya Lain-lain", "align": "LEFT", "cols": 24 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${spkData?.catatan}`, "align": "LEFT", "cols": 21 }
          ]
        },
        { "type": "drawLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "No.", "align": "LEFT", "cols": 4 },
            { "text": "Keterangan", "align": "LEFT", "cols": 29 },
            { "text": "Jumlah", "align": "RIGHT", "cols": 15 }
          ]
        },
        { "type": "drawLine" },
        ...spk_data?.nospkd?.map((dt, idx) => ({
          "type": "tableCustom",
          "value": [
            { "text": idx + 1, "align": "LEFT", "cols": 4 },
            { "text": dt?.keterangan, "align": "LEFT", "cols": 29 },
            { "text": `Rp ${number_format(parseFloat(dt?.nominal), 0, ',', '.')}`, "align": "RIGHT", "cols": 15 }
          ]
        })),
        { "type": "drawLine" },
        { "type": "setTextDoubleHeight" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Total", "align": "LEFT", "cols": 5 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `Rp ${number_format(parseFloat(spkData?.total_sangu), 0, ',', '.')}`, "align": "RIGHT", "cols": 40 }
          ]
        },
        { "type": "setTextNormal" },
        { "type": "drawLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Terbilang", "align": "LEFT", "cols": 9 },
            { "text": " : ", "align": "CENTER", "cols": 3 },
            { "text": `${terbilang(parseFloat(spkData?.total_sangu))} Rupiah`, "align": "LEFT", "cols": 36 }
          ]
        },
        { "type": "drawLine" },
        { "type": "println", "value": "Mengetahui," },
        { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "Admin / Kasir", "align": "CENTER", "cols": 15 },
            { "text": "Sopir", "align": "CENTER", "cols": 16 },
            { "text": "Pengebon", "align": "CENTER", "cols": 17 }
          ]
        },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        {
          "type": "tableCustom",
          "value": [
            { "text": "(Kusmiati)", "align": "CENTER", "cols": 16 },
            { "text": `(${spkData?.supir_nip})`, "align": "CENTER", "cols": 16 },
            { "text": "(Budi)", "align": "CENTER", "cols": 16 }
          ]
        },
        { "type": "newLine" },
        { "type": "println", "value": `Dicetak pada tanggal : ${getCurrentDateTime()}` },
        { "type": "println", "value": `Operator : ${spk_data?.user_print?.name}-PC # ${spk_data?.user_print?.nip}` },
        { "type": "println", "value": `Sudah di print : ${spkData?.jumlah_print}x` },
        { "type": "newLine" },
        { "type": "newLine" },
        { "type": "newLine" },
        {
          "type": "tableCustom", "value": [
            { "text": "", "align": "RIGHT", "cols": 16 },
            { "text": "", "align": "RIGHT", "cols": 13 },
            { "text": "RANGKAP 2", "align": "RIGHT", "cols": 16 }
          ]
        },
        { "type": "cut" }
      ]
    }


  } catch (err) {
    console.error("Error fetching print data:", err);
    isBadForm.value = true;
    swal.fire({
      icon: "error",
      text: err.message || "Terjadi kesalahan.",
      allowOutsideClick: false,
      confirmButtonText: "Kembali",
    });
    isRequesting.value = false;
    return; // Stop execution if fetching data fails
  }

  try {
    const printURL = `http://localhost:${thermal.port}${thermal.url}`;
    const printOptions1 = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(fieldData1),
    };
    const printOptions2 = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(fieldData2),
    };

    // Cetak dua kali
    const response1 = await fetch(printURL, printOptions1);
    if (!response1.ok) throw new Error(`Cetakan pertama gagal: ${response1.status}`);

    const response2 = await fetch(printURL, printOptions2);
    if (!response2.ok) throw new Error(`Cetakan kedua gagal: ${response2.status}`);

    swal.fire({
      icon: "success",
      text: "Print berhasil!",
    });

  } catch (error) {
    console.error("Print request error:", error);
    swal.fire({
      icon: "error",
      text: "Print gagal, periksa kembali pengaturan atau perangkat Anda!",
    });

  } finally {
    isRequesting.value = false;
  }
}

// FILTER
const filterButton = ref(null);
function filterShowData(params) {
  filterButton.value = filterButton.value === params ? null : params;
  table.api.params.where = filterButton.value !== null ? `this.status='${filterButton.value}'` : null;
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
  if (event?.ctrlKey && event?.key === 's' && (actionText.value || actionSingleEdit.value)) {
    event.preventDefault();
    onSave();
  }
}

onMounted(() => { window.addEventListener('keydown', handleKeyDown) });
onBeforeUnmount(() => { window.removeEventListener('keydown', handleKeyDown) });

// FORM DATA
let default_value = {
  data: {
    status: 'DRAFT',
    is_con_edit: false,
    total_bon_tambahan: 0
  },
  detail: [],
  t_spk_bon_detail: []
}


const data = reactive({ ...default_value.data });

const dataOrderId = reactive([]);

const initArr = {
  keterangan: '',
  nominal: ''
}

const detailArr = reactive([])


// GET DATA FROM API
onBeforeMount(async () => {
  if (actionText.value === 'Create' || data.status === 'DRAFT') {
    data.tanggal_spk = getCurrentDateFormatted();
  }


  const headers = {
    'Content-Type': 'application/json',
    Authorization: `${store.user.token_type} ${store.user.token}`,
  };

  const fetchData = async (url, params = {}) => {
    const queryString = new URLSearchParams(params).toString();
    const response = await fetch(`${url}?${queryString}`, { headers });
    return response.json();
  };

  const dataOrder = `${store.server.url_backend}/operation/${endpointApi}`;
  await fetchData(dataOrder, { join: false, transform: false }).then((res) => {
    // default_value.data = res.data;

    dataOrderId.push(...res.data.map(dt => dt.t_detail_npwp_container_1_id).filter(dt2 => dt2 && dt2 != data.t_detail_npwp_container_1_id && dt2 != data.t_detail_npwp_container_2_id));
    dataOrderId.push(...res.data.map(dt => dt.t_detail_npwp_container_2_id).filter(dt2 => dt2 && dt2 != data.t_detail_npwp_container_1_id && dt2 != data.t_detail_npwp_container_2_id));

    console.log("dataOrderId", dataOrderId);

  });


  if (!isRead) return;

  try {
    let trx_id;

    const headers = {
      'Content-Type': 'application/json',
      Authorization: `${store.user.token_type} ${store.user.token}`,
    };
    const fetchData = async (url, params = {}) => {
      const queryString = new URLSearchParams(params).toString();
      const response = await fetch(`${url}?${queryString}`, { headers });
      return response.json();
    };

    if (route.query.is_approval) {
      const dataApprovalURL = `${store.server.url_backend}/operation/generate_approval/${route.params.id}`;
      isRequesting.value = true;

      // FETCH HEADER DATA
      await fetchData(dataApprovalURL, { join: true, transform: false }).then((res) => {
        trx_id = res.data.trx_id;
        console.log(res, trx_id);
      });
    }

    const editedId = route.params.id;
    const dataURL = trx_id ? `${store.server.url_backend}/operation/${endpointApi}/${trx_id}` : `${store.server.url_backend}/operation/${endpointApi}/${editedId}`;
    isRequesting.value = true;
    console.log(trx_id)



    // FETCH HEADER DATA
    await fetchData(dataURL, { join: false, transform: false }).then((res) => {
      // default_value.data = res.data;
      detailArr.push(...res.data.t_spk_bon_detail);
      for (const key in res.data) {
        data[key] = res.data[key];
      }
      //data.is_con_edit = false;
      if (actionText.value === 'Copy') {
        data.status = default_value.data.status;
        data.is_con_edit = default_value.data.is_con_edit;
        data.no_spk = null;
        data.jumlah_print = 0;
        data.is_printed = 0;
      }
    });


    const dataURLcontainer = `${store.server.url_backend}/operation/t_buku_order_d_npwp`;
    await fetchData(dataURLcontainer, {
      where: `this.id=${data.t_detail_npwp_container_1_id}`
    }).then(res => {
      if (res.data.length !== 0) {
        data.ukuran_container_1 = res.data[0]['ukuran.deskripsi'];
        data.jenis_container_1 = res.data[0]['jenis.deskripsi'];
        data.tipe_container_1 = res.data[0]['tipe.deskripsi'];
        data.no_prefix_1 = res.data[0]['no_prefix'];
        data.no_suffix_1 = res.data[0]['no_suffix'];
      }
    })
    await fetchData(dataURLcontainer, {
      where: `this.id=${data.t_detail_npwp_container_2_id}`
    }).then(res => {
      if (res.data.length !== 0) {
        data.ukuran_container_2 = res.data[0]['ukuran.deskripsi'];
        data.jenis_container_2 = res.data[0]['jenis.deskripsi'];
        data.tipe_container_2 = res.data[0]['tipe.deskripsi'];
        data.no_prefix_2 = res.data[0]['no_prefix'];
        data.no_suffix_2 = res.data[0]['no_suffix'];
      }
    })



    // FETCH DETAIL DATA 
    // await fetchData(`${store.server.url_backend}/operation/t_spk_d_tambahan`, {
    //   where: `this.t_po_id=${editedId}`, order_by: "created_at", order_type: "ASC"
    // }).then((res) => {
    //   default_value.detail = res.data.map(item => (
    //     { ...item, item: item['t_spk_d_tambahan.item'] }
    //   ));
    //   detail.data = default_value.detail.map(item => ({ ...item }));
    // });

  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error', text: err, allowOutsideClick: false, confirmButtonText: 'Kembali',
    }).then(() => { router.back() });
  } finally {
    isRequesting.value = false;
  }
  console.log(data.t_detail_npwp_container_1_id, 'kiw kiw')
});


// ADD & DELETE DETAIL
const addDetailBon = () => {
  detailArr.push({ ...initArr });
}

const delDetailBon = (index) => {
  detailArr.splice(index, 1);
}

const deleteDetailAll = () => {
  swal.fire({
    icon: 'warning', text: 'Hapus semua detail data?', showDenyButton: true,
  }).then((res) => {
    if (res.isConfirmed) {
      detail.data = [];
    }
  })
}

const oldBukuOrder1 = ref(null);
const oldBukuOrder2 = ref(null);

function selectBukuOrder1(v) {
  data.t_detail_npwp_container_1_id = v;
  oldBukuOrder1.value = v;
}

function selectBukuOrder2(v) {
  data.t_detail_npwp_container_2_id = v;
  oldBukuOrder2.value = v;
}

function modifyBukuOrder1(response) {
  if (response == null) {
    data.nama_customer = '';
    data.ukuran_container_1 = '';
    data.jenis_container_1 = ''
    data.t_buku_order_1_id = '';
    data.no_container_1 = '';
    data.no_prefix_1 = '';
    data.no_suffix_1 = '';
  } else {
    data.nama_customer = response['t_buku_order.m_customer_id'];
    data.ukuran_container_1 = response['ukuran.deskripsi'];
    data.jenis_container_1 = response['jenis.deskripsi'];
    data.t_buku_order_1_id = response['id'];
    data.no_container_1 = response.no_prefix + '-' + response.no_suffix;
    data.no_prefix_1 = response.no_prefix;
    data.no_suffix_1 = response.no_suffix;
  }
}

function modifyBukuOrder2(response) {
  if (response == null) {
    data.nama_customer_2 = '';
    data.ukuran_container_2 = '';
    data.jenis_container_2 = '';
    data.t_buku_order_2_id = '';
    data.no_container_2 = '';
    data.no_prefix_2 = '';
    data.no_suffix_2 = '';
  } else {
    data.nama_customer_2 = response['t_buku_order.m_customer_id'];
    data.ukuran_container_2 = response['ukuran.deskripsi'];
    data.jenis_container_2 = response['jenis.deskripsi'];
    data.t_buku_order_2_id = response['id'];
    data.no_container_2 = response.no_prefix + '-' + response.no_suffix;
    data.no_prefix_2 = response.no_prefix;
    data.no_suffix_2 = response.no_suffix;
  }
}

function updateBukuOrder1(response) {
  if ((response.in_spk ?? []).length > 0) {
    swal.fire({
      icon: 'warning', text: `Buku Order sudah digunakan di 
      ${response.in_spk.map((dt, i) => dt.no_spk).join(', ')}
      `,
      showDenyButton: true
    }).then((res) => {
      if (res.isConfirmed) {
        selectBukuOrder1(response.id);
        modifyBukuOrder1(response);
      } else {
        selectBukuOrder1(oldBukuOrder1 ?? null);
      }
    })
    console.log(response.in_spk);
  } else {
    modifyBukuOrder1(response);
  }
}

function updateBukuOrder2(response) {
  if ((response.in_spk ?? []).length > 0) {
    swal.fire({
      icon: 'warning', text: `Buku Order sudah digunakan di 
      ${response.in_spk.map((dt, i) => dt.no_spk).join(', ')}
      `,
      showDenyButton: true
    }).then((res) => {
      if (res.isConfirmed) {
        selectBukuOrder2(response.id);
        modifyBukuOrder2(response);
      } else {
        selectBukuOrder2(oldBukuOrder2 ?? null);
      }
    })

  } else {
    modifyBukuOrder2(response);
  }
}

// ACTION BUTTON
function onReset() {
  swal.fire({
    icon: 'warning',
    text: 'Reset semua data?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: 'Batal',
  }).then((result) => {
    if (result.isConfirmed) {
      // Reset data utama
      Object.keys(data).forEach((key) => {
        data[key] = default_value.data[key];
      });

      // Reset detailArr dengan salinan baru agar reaktif
      detailArr.splice(0, detailArr.length, ...default_value.detail.map(item => ({ ...item })));
    }
  });
}


function onBack() {
  router.replace('/' + modulPath)
}

async function onSave() {
  // console.log(data, detailArr);
  // console.log('ini panjangggg', detailArr.length);

  const result = await swal.fire({
    icon: 'warning', text: 'Simpan data?', showDenyButton: true,
  });

  if (!result.isConfirmed) return;

  try {
    let next = true

    // if (!data.waktu_out) {
    //   swal.fire({
    //     icon: 'warning',
    //     text: `Waktu Out harus diisi`
    //   })
    //   next = false
    //   return
    // }
    // if (!data.waktu_in) {
    //   swal.fire({
    //     icon: 'warning',
    //     text: `Waktu In harus diisi`
    //   })
    //   next = false
    //   return
    // }


    const isCreating = ['Create', 'Copy'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : '/' + route.params.id}`;
    isRequesting.value = true;

    if (actionSingleEdit.value == 'EditContainer') data.is_con_edit = true;

    data.no_container_1 = data.no_prefix_1 + '-' + data.no_suffix_1;
    data.no_container_2 = data.no_prefix_2 + '-' + data.no_suffix_2;

    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify({
        ...data,
        t_spk_bon_detail: detailArr,
      }),
    });

    const dataURLDNPWP1 = `${store.server.url_backend}/operation/t_buku_order_d_npwp/${data.t_buku_order_1_id}`;
    isRequesting.value = true;
    const resDNPWP1 = await fetch(dataURLDNPWP1, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify({
        no_prefix: data.no_prefix_1,
        no_suffix: data.no_suffix_1,
      }),
    });

    const dataURLDNPWP2 = `${store.server.url_backend}/operation/t_buku_order_d_npwp/${data.t_buku_order_2_id}`;
    isRequesting.value = true;
    const resDNPWP2 = await fetch(dataURLDNPWP2, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify({
        no_prefix: data.no_prefix_2,
        no_suffix: data.no_suffix_2,
      }),
    });

    if (!res.ok) {
      const responseJson = await res.json();
      formErrors.value = responseJson.errors || {};
      if (responseJson.message === "create data failed") {
        swal.fire({ icon: 'error', text: responseJson.errors[0] || "Failed when trying to post data" });
      } else {
        swal.fire({ icon: 'error', text: responseJson.message || "Failed when trying to post data" });
      }
    } else {
      router.replace(`/${modulPath}?reload=${Date.now()}`);
    }

  } catch (err) {
    isBadForm.value = true;
    swal.fire({ icon: 'error', text: err });
  } finally {
    isRequesting.value = false;
  }
}

watch(() => detailArr, () => {
  data.total_bon_tambahan = 0;
  for (let idx = 0; idx < detailArr.length; idx++) {
    if (detailArr[idx].nominal) data.total_bon_tambahan += Number(detailArr[idx].nominal);
  }
}, { deep: true })

watch([() => data.total_bon_tambahan, () => data.sangu], () => {
  data.total_sangu = Number(data.total_bon_tambahan) + Number(data.sangu);
});

const getCurrentDateFormatted = () => {
  const date = new Date();
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};
async function sendApproval() {
  swal.fire({
    icon: 'warning',
    text: 'Send Approval?',
    iconColor: '#1469AE',
    confirmButtonColor: '#1469AE',

    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const isCreating = ['Create', 'Copy'].includes(actionText.value);
        const dataURLSimpan = `${store.server.url_backend}/operation/${endpointApi}${isCreating ? '' : '/' + route.params.id}`;
        isRequesting.value = true;

        const resSimpan = await fetch(dataURLSimpan, {
          method: isCreating ? 'POST' : 'PUT',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`,
          },
          body: JSON.stringify({
            ...data,
            t_spk_bon_detail: detailArr,
          }),
        }).then(async response => {
          // if (!response.ok) {
          //   throw new Error(`HTTP error! status: ${response.status}`);
          // }
          if (!response.ok) {
            const responseJson = await resSimpan.json();
            formErrors.value = responseJson.errors || {};
            swal.fire({ icon: 'error', text: responseJson.message || "Failed when trying to post data" });
          }
          return response.json(); // Parse JSON from the response body
        }).then(async data => {
          console.log('Parsed response JSON:', data);
          data.id = data.id;
          const dataURL = `${store.server.url_backend}/operation/${endpointApi}/send_approval`
          isRequesting.value = true
          const res = await fetch(dataURL, {
            method: 'POST',
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            body: JSON.stringify({ id: data.id })
          })
          if (!res.ok) {
            if ([400, 422, 500].includes(res.status)) {
              const responseJson = await res.json()
              formErrors.value = responseJson.errors || {}
              throw (responseJson.message + " " + responseJson.data.errorText || "Failed when trying to Send Approval")
            } else {
              throw ("Failed when trying to Send Approval")
            }
          }
          const responseJson = await res.json()
          swal.fire({
            icon: 'success',
            text: responseJson.message
          })
        });
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
        const dataURL = `${store.server.url_backend}/operation/${endpointApi}/progress`
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
      router.replace('/' + 'notifikasi');
    }
  })
}

const is_key_isi_container_1 = ref(false)
const is_key_isi_container_2 = ref(false)

watch(() => [data.isi_container_1, data.isi_container_2, data.trip_id], () => {
  // && data.trip_id != 310
  if (data.isi_container_1 == 249) {
    data.isi_container_2 = 248;
    is_key_isi_container_2.value = true;
    console.log("aodhfaoshfosdfh")

  }
  else {
    // data.isi_container_2 = 248;
    is_key_isi_container_2.value = false;
    console.log("aksdaksdasd")

  }
  if (data.isi_container_2 == 249) {
    data.isi_container_1 = 248;
    is_key_isi_container_1.value = true;
  }
  else {
    is_key_isi_container_1.value = false;
  }
  console.log(data.isi_container_1, data.isi_container_2, data.trip_id)
});


watch(() => [data.total_sangu], () => {
  data.total_sangu_tampil = data.total_sangu.toLocaleString('id-ID');
});
//  @endif | --- END --- |
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))