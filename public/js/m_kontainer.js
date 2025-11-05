// JANGAN LUPA SELALU BERMAIN CONSOLE LOG !

import { useRouter, useRoute } from 'vue-router';
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated, computed } from 'vue';

// Mendapatkan instance router dan route
const router = useRouter();
const route = useRoute();

// Mengambil store dan swal dari inject
const store = inject('store');
const swal = inject('swal');

// Mendapatkan parameter dan query dari route
const isRead = route.params.id && route.params.id !== 'create';
const actionText = ref(route.params.id === 'create' ? 'Tambah' : route.query.action);
const isBadForm = ref(false);
const isRequesting = ref(false);
const modulPath = route.params.modul;
const currentMenu = store.currentMenu;
const apiTable = ref(null);
const formErrors = ref({});
const tsId = `ts=${Date.parse(new Date())}`; // Memperbaiki sintaks

// ------------------------------ PERSIAPAN
const endpointApi = '/m_general';
onBeforeMount(() => {
  document.title = 'Master Kontainer';
});

function go(group) {
  router.push({
    path: `${route.path}/${group}`,
    query: {
      action: 'Edit',
      scopes: 'EditKontainer',
    },
  });
}

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []

const values = reactive({
  is_active: true,
  kode: 'KONTAINER'
})

onBeforeMount(async () => {

  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}`
      isRequesting.value = true
      // PARAMATER PADA API
      const params = {
        join: true,
        scopes: 'Editkontainer',
        group: route.params.id,
        transform: false
      }
      const fixedParams = new URLSearchParams(params)
      const res = await fetch(dataURL + '?' + fixedParams, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      })
      if (!res.ok) throw new Error("Failed when trying to read data")
      const resultJson = await res.json()
      const initialValues = resultJson.data
      // MAPPING DATA HEADER DISINI
      if (Array.isArray(initialValues) && initialValues.length > 0) {
        values.kode = "KONTAINER"
        values.group = initialValues[0].group;
        values.deskripsi = initialValues[0].deskripsi;
        values.is_active = initialValues[0].is_active;
        let idCounter = 0;
        // MAPPING DATA DETAIL HEADER DISINI
        initialValues.forEach(item => {
        detailArr.value.push({
        deskripsi: item.deskripsi,
        group: item.group,
        id: item.id,
        kode: "KONTAINER",
        is_active: item.is_active
    });
        });
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


// LOGIC TABLE DETAIL DISINI !
const detailArr = ref([]);
const searchTerm = ref("");
 const sortOrder = ref(0);
// LOGIC DETAIL FILTER
 const filteredDetails = computed(() => {
      let details = detailArr.value
        .filter(item => item.deskripsi.toLowerCase().includes(searchTerm.value.toLowerCase()));

      if (sortOrder.value !== 0) {
        details = details.sort((a, b) => {
          if (a.deskripsi < b.deskripsi) return sortOrder.value === 1 ? -1 : 1;
          if (a.deskripsi > b.deskripsi) return sortOrder.value === 1 ? 1 : -1;
          return 0;
        });
      }

      return details.sort((a, b) => {
        if (a.is_active === b.is_active) return 0;
        return a.is_active ? -1 : 1;
      });
    });

    const toggleSort = () => {
      if (sortOrder.value === 0) {
        sortOrder.value = 1;
      } else if (sortOrder.value === 1) {
        sortOrder.value = -1;
      } else {
        sortOrder.value = 0;
      }
    };


// LOGIC ADD DETAIL
const addDetail = () => {
  const tempItem = {
    id: values.id,
    kode: 'KONTAINER',
    group: values.group,
    deskripsi: "",
    is_active: true
  };
  detailArr.value = [tempItem, ...detailArr.value];
};
// LOGIC DELETE
const removeDetail = (detailItem) => {
  if (!detailItem.deskripsi || detailItem.deskripsi.trim() === "") {
    detailItem.is_active = false;
  } else {
    swal.fire({
      icon: 'warning',
      title : 'Menonaktifkan data ini?',
      text: 'Untuk menghapus data anda perlu ke General',
      showDenyButton: true,
      confirmButtonText: 'Yes',
      denyButtonText: 'No'
    }).then((res) => {
      if (res.isConfirmed) {
        detailItem.is_active = false;
      }
    });
  }
};

// FUCTION ONBACK
function onBack() {
 router.replace('/' + modulPath);
    }    


// LOGIC ON SAVE
async function onSave() {
  values.is_active = (values.is_active === true) ? 1 : 0;
  values.detail = detailArr.value;
  if (values.detail.length < 1) {
    swal.fire({
      icon: 'warning',
      text: "Harap tambahkan data Detail terlebih dahulu"
    });
    return;
  }

  try {
    const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation${endpointApi}/saveKontainer`;
    isRequesting.value = true;
    const res = await fetch(dataURL, {
      method: 'POST',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
      body: JSON.stringify(values),
    });

    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json();
        formErrors.value = responseJson.errors || {};
        throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data");
      } else {
        throw ("Failed when trying to post data");
      }
    }
    const simpan = await swal.fire({
      icon: 'success',
      title: 'Data berhasil di simpan!',
      text: 'Apakah anda ingin kembali?',
      showCancelButton: true,
      cancelButtonText: 'Tidak',
      confirmButtonText: 'Ya',
    });
        if (simpan.isConfirmed) {
      router.replace('/' + modulPath);
    }
  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error',
      text: err
    });
  }

  isRequesting.value = false;
}




// LANDING
//  @else----------------------- LANDING
const landing = reactive({
  actions: [
  ],
  api: {
    url: `${store.server.url_backend}/operation${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      where : `this.group = 'JENIS KONTAINER' OR this.group = 'TIPE KONTAINER' OR this.group = 'UKURAN KONTAINER'`,
      searchfield:'this.deskripsi',
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
    headerName: 'Group',
    field: 'group',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-center']
  },
  {
    headerName: 'Deskripsi',
    field: 'deskripsi',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex: 1,
    cellClass: ['border-r', '!border-gray-200', 'justify-center']
  },
  {
  headerName: 'Status', 
  field: 'is_active', 
  flex: 1, 
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  sortable: true, 
  filter: 'ColFilter', 
  cellRenderer: ({ value }) =>
  value === true
  ? '<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Active</span>'
  : '<span class="text-red-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Inactive</span>'
  }

  ]
})


// filter Kontainer
const pilih = ref(''); 
function filterByGroup(group) {
  if (pilih.value === group) {
    pilih.value = '';
    landing.api.params.where = `this.group = 'JENIS KONTAINER' OR this.group = 'TIPE KONTAINER' OR this.group = 'UKURAN KONTAINER' OR this.is_active ='true'`;
  } else {
    pilih.value = group;
    landing.api.params.where = `this.group = '${group}'`;
  }
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