<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">SPK ANGKUTAN</h1>
  </div>
  <div class="flex justify-between items-center px-4 py-1">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-gray-600 text-white hover:bg-gray-600' 
          : 'border border-gray-600 text-gray-600 bg-white hover:bg-gray-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          DRAFT
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('IN APPROVAL')" :class="filterButton === 'IN APPROVAL' ? 'bg-blue-600 text-white hover:bg-blue-600' 
          : 'border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          IN APPROVAL
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('APPROVED')" :class="filterButton === 'APPROVED' ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          APPROVED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('REVISED')" :class="filterButton === 'REVISED' ? 'bg-purple-600 text-white hover:bg-purple-600' 
          : 'border border-purple-600 text-purple-600 bg-white hover:bg-purple-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          REVISED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('REJECTED')" :class="filterButton === 'REJECTED' ? 'bg-red-600 text-white hover:bg-red-600' 
          : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          REJECTED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('CANCEL')" :class="filterButton === 'CANCEL' ? 'bg-orange-600 text-white hover:bg-orange-600' 
          : 'border border-orange-600 text-orange-600 bg-white hover:bg-orange-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          CANCEL
        </button>
      </div>
    </div>

    <!-- ACTION BUTTON -->
    <div class="flex items-center gap-x-2">
      <RouterLink :to="'/thermal_printer?view=public'" class="border border-gray-600 
      text-gray-600 bg-white hover:bg-gray-600 hover:text-white text-sm rounded py-1 px-2.5
      transition-colors duration-300">
        Pengaturan Thermal
      </RouterLink>
      <RouterLink :to="$route.path + '/create?' + (Date.parse(new Date()))" class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white text-sm rounded py-1 px-2.5
      transition-colors duration-300">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>

  <!-- TABLE -->
  <TableApi ref='apiTable' :api="table.api" :columns="table.columns" :actions="table.actions" class="max-h-[500px] pt-2 !px-4 
  !pb-8">
    <template #header>
      <div class="pb-13 h-full"></div>
    </template>
  </TableApi>
</div>
@else

<!-- FORM DATA -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md pt-2 pb-3 px-4">
    <div class="flex items-center gap-2">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
      <div class="flex flex-col py-1 gap-1">
        <h1 class="text-lg font-bold leading-none">Form SPK Angkutan</h1>
        <p class="text-gray-100 leading-none">Transaction SPK Angkutan</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 items-start">
    <!-- col-span-2 -->
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.tipe_spk"
        @input="v=>data.tipe_spk=v" :errorText="formErrors.tipe_spk?'failed':''" :hints="formErrors.tipe_spk"
        valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='TIPE SPK'`
              }
          }" label="Tipe SPK" placeholder="Pilih Tipe SPK" fa-icon="sort-desc" :check="false" />
    </div>

    <div>
      <FieldX :bind="{ readonly: true, disabled: true}" class="w-full !mt-3" :value="data.tanggal_spk"
        :errorText="formErrors.tanggal_spk?'failed' :''" @input="v=>data.tanggal_spk=v" :hints="formErrors.tanggal_spk"
        :check="false" type="date" label="Tanggal SPK" placeholder="Pilih Tanggal SPK" />
    </div>

    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_spk" @input="v=>data.no_spk=v"
        :errorText="formErrors.no_spk?'failed':''" :hints="formErrors.no_spk" placeholder="No. SPK" :check="false" />
    </div>

    <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start">
      <FieldX :bind="{ readonly: !actionText, disabled: !actionText}" class="w-full !mt-3" :value="data.tanggal_out"
        :errorText="formErrors.tanggal_out?'failed' :''" @input="v=>data.tanggal_out=v" :hints="formErrors.tanggal_out"
        :check="false" type="date" label="Tanggal Out" placeholder="Pilih Tanggal Out" />
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true, readonly:!actionText }"
        :value="data.waktu_out" @input="v=>data.waktu_out=v" :errorText="formErrors.waktu_out?'failed':''"
        :hints="formErrors.waktu_out" valueField="id" displayField="deskripsi" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              transform:false,
              join:false,
              where:`this.group='WAKTUOUT'`,
            }
        }" placeholder="Pilih Waktu Out" fa-icon="sort-desc" label="Waktu Out" :check="false" />

    </div>
    <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start">
      <FieldX :bind="{ readonly: !actionText}" class="w-full !mt-3" :value="data.tanggal_in"
        :errorText="formErrors.tanggal_in?'failed' :''" @input="v=>data.tanggal_in=v" :hints="formErrors.tanggal_in"
        :check="false" type="date" label="Tanggal In" placeholder="Pilih Tanggal In" />
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true, readonly:!actionText }"
        :value="data.waktu_in" @input="v=>data.waktu_in=v" :errorText="formErrors.waktu_in?'failed':''"
        :hints="formErrors.waktu_in" valueField="id" displayField="deskripsi" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              transform:false,
              join:false,
              where:`this.group='WAKTUIN'`,
            }
        }" placeholder="Pilih Waktu In" fa-icon="sort-desc" label="Waktu In" :check="false" />
    </div>

    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.depo"
        @input="v=>data.depo=v" :errorText="formErrors.depo?'failed':''" :hints="formErrors.depo" valueField="id"
        displayField="kode" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='DEPO'`
              }
          }" label="Depo" placeholder="Pilih Depo" fa-icon="sort-desc" :check="false" />
    </div>



    <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start">
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/m_general`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest:false,
          where:`this.group='HEAD' and this.is_active = true`,
          // transform:false,
          // join:true,
          // override:true,
          // where:`this.is_active=true`,
          searchfield:'this.kode, this.deskripsi',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili' 
        },
        onsuccess: (response) => {
          response.page = response.current_page
          response.hasNext = response.has_next
          return response;
        }
      }" displayField="kode" valueField="id" :bind="{ readonly: !actionText }" :value="data.head"
        @input="(v)=>data.head=v" @update:valueFull="(response)=>{
        $log(response);
      }" :errorText="formErrors.head?'failed':''" class="w-full !mt-3" :hints="formErrors.head" placeholder="Head"
        :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'kode',
          headerName: 'Kode',
          sortable: true, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'Deskripsi',
          field: 'deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
        
      ]" />

      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/m_kary`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest:false,
          // transform:false,
          // join:true,
          // override:true,
          where:`this.is_active=true`,
          searchfield:'this.id, this.nip, this.nama',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili' 
        },
        onsuccess: (response) => {
          response.page = response.current_page
          response.hasNext = response.has_next
          return response;
        }
      }" displayField="nama" valueField="id" :bind="{ readonly: !actionText }" :value="data.supir"
        @input="(v)=>data.supir=v" @update:valueFull="(response)=>{
        $log(response);
      }" :errorText="formErrors.supir?'failed':''" class="w-full !mt-3" :hints="formErrors.supir" placeholder="Supir"
        :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: 'ColFilter',
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'nip',
          headerName: 'NIP',
          sortable: true, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'Nama',
          field: 'nama',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
        
      ]" />
    </div>

    <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start">
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.sektor1"
        @input="v=>data.sektor1=v" :errorText="formErrors.sektor1?'failed':''" :hints="formErrors.sektor1"
        valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='SEKTOR'`,
                paginate: 1000
              }
          }" label="Sektor 1" placeholder="Pilih Sektor 1" fa-icon="sort-desc" :check="false" />


      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true, hidden:true}"
        :value="data.sektor2" @input="v=>data.sektor2=v" :errorText="formErrors.sektor2?'failed':''"
        :hints="formErrors.sektor2" valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='SEKTOR'`
              }
          }" label="Sektor 2" placeholder="Pilih Sektor 2" fa-icon="" :check="false" />
    </div>

    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.trip_id"
        @input="v=>data.trip_id=v" :errorText="formErrors.trip_id?'failed':''" :hints="formErrors.trip" valueField="id"
        displayField="kode" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='TRIP SPK ANGKUTAN'`
              }
          }" label="Trip" placeholder="Pilih trip" fa-icon="sort-desc" :check="false" />
    </div>

    <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start">
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.chasis"
        @input="v=>data.chasis=v" :errorText="formErrors.chasis?'failed':''" :hints="formErrors.chasis" valueField="id"
        displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='CHASIS'`
              }
          }" label="Chasis 1" placeholder="Pilih Chasis 1" fa-icon="sort-desc" :check="false" />

      <FieldSelect class="w-full !mt-3" :bind="{ disabled: is_key_isi_container_1 || !actionText, clearable:true }"
        :value="data.isi_container_1" @input="v=>data.isi_container_1=v"
        :errorText="formErrors.isi_container_1?'failed':''" :hints="formErrors.isi_container_1" valueField="id"
        displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='ISI CONTAINER'`
              }
          }" label="Isi Container 1" fa-icon="sort-desc" placeholder="Pilih Isi Container 1" :check="false" />
    </div>
    <div></div>
    <div></div>


    <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start">
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/t_buku_order_d_npwp`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          transform: true,
          getCustomer:true,
          useSPKVal: true,
          //scopes:'getCodeCustomer',
          simplest:false,
          where:`this.id!=${data.t_detail_npwp_container_2_id ? data.t_detail_npwp_container_2_id : 0}`,
          searchfield: 't_buku_order.no_buku_order, this.no_prefix, this.no_suffix, ukuran.deskripsi, jenis.deskripsi'
        },
        onsuccess: (response) => {
          response.page = response.current_page
          response.hasNext = response.has_next
          return response;
        }
      }" displayField="t_buku_order.no_buku_order" valueField="id" :bind="{ readonly: !actionText }"
        :value="data.t_detail_npwp_container_1_id" @input="(v)=>{
          selectBukuOrder1(v);
          }" @update:valueFull="(response)=>{
          updateBukuOrder1(response);
      }" :errorText="formErrors.t_detail_npwp_container_1_id?'failed':''"
        :hints="formErrors.t_detail_npwp_container_1_id" placeholder="No. Order 1" :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: 'ColFilter',
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 't_buku_order.no_buku_order',
          headerName: 'No. Order',
          sortable: true, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'No. Prefix',
          field: 'no_prefix',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
        {
          headerName: 'No. Suffix',
          field: 'no_suffix',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
        {
          headerName: 'Ukuran',
          field: 'ukuran.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
        {
          headerName: 'Jenis',
          field: 'jenis.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
        {
          headerName: 'Kode Customer',
          field: 't_buku_order.m_customer_kode',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        }
      ]" />
      <FieldSelect class="w-full !mt-3" :bind="{ disabled:true, readonly:true }" :value="data.nama_customer"
        @input="v=>data.nama_customer=v" :errorText="formErrors.nama_customer?'failed':''"
        :hints="formErrors.nama_customer" valueField="id" displayField="kode" :api="{
              url: `${store.server.url_backend}/operation/m_customer`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true
              }
          }" label="Kode Customer" fa-icon="" placeholder="" :check="false" />
    </div>

    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
      <FieldX :bind="{ readonly: !actionText && (!actionSingleEdit || data.is_con_edit == true) }" class="w-full !mt-3"
        :value="data.no_prefix_1" @input="v=>data.no_prefix_1=v" :errorText="formErrors.no_prefix_1?'failed':''"
        :hints="formErrors.no_prefix_1" placeholder="No. Prefix 1" :check="false" />
      <FieldX :bind="{ readonly: !actionText && (!actionSingleEdit || data.is_con_edit == true) }" class="w-full !mt-3"
        :value="data.no_suffix_1" @input="v=>data.no_suffix_1=v" :errorText="formErrors.no_suffix_1?'failed':''"
        :hints="formErrors.no_suffix_1" placeholder="No. Suffix 1" :check="false" />
    </div>

    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="data.ukuran_container_1"
        @input="v=>data.ukuran_container_1=v" :errorText="formErrors.ukuran_container_1?'failed':''"
        :hints="formErrors.ukuran_container_1" label="Ukuran Container 1" placeholder="Ukuran Container 1"
        :check="false" />

      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.jenis_container_1"
        @input="v=>data.jenis_container_1=v" :errorText="formErrors.jenis_container_1?'failed':''"
        :hints="formErrors.jenis_container_1" label="Jenis Container 1" placeholder="Jenis Container 1"
        :check="false" />
    </div>


    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.chasis2"
        @input="v=>data.chasis2=v" :errorText="formErrors.chasis2?'failed':''" :hints="formErrors.chasis2"
        valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='CHASIS'`
              }
          }" label="Chasis 2" placeholder="Pilih Chasis 2" fa-icon="sort-desc" :check="false" />

      <FieldSelect class="w-full !mt-3" :bind="{ disabled: is_key_isi_container_2 || !actionText, clearable:true }"
        :value="data.isi_container_2" @input="v=>data.isi_container_2=v"
        :errorText="formErrors.isi_container_2?'failed':''" :hints="formErrors.isi_container_2" valueField="id"
        displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='ISI CONTAINER'`
              }
          }" label="Isi Container 2" fa-icon="sort-desc" placeholder="Pilih Isi Container 2" :check="false" />
    </div>
    <div></div>
    <div></div>


    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/t_buku_order_d_npwp`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          transform: true,
          getCustomer:true,
          useSPKVal: true,
          //scopes:'getCodeCustomer',
          simplest:false,
          where:`this.id!=${data.t_detail_npwp_container_1_id ? data.t_detail_npwp_container_1_id: 0 }`,
          searchfield: 't_buku_order.no_buku_order, this.no_prefix, this.no_suffix, ukuran.deskripsi, jenis.deskripsi'
        },
        onsuccess: (response) => {
          response.page = response.current_page
          response.hasNext = response.has_next
          return response;
        }
      }" displayField="t_buku_order.no_buku_order" valueField="id" :bind="{ readonly: !actionText }"
        :value="data.t_detail_npwp_container_2_id" @input="(v)=>{
          selectBukuOrder2(v);
          }" @update:valueFull="(response)=>{
          updateBukuOrder2(response);
      }" :errorText="formErrors.t_detail_npwp_container_2_id?'failed':''"
        :hints="formErrors.t_detail_npwp_container_2_id" placeholder="No. Order 2" :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: 'ColFilter',
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 't_buku_order.no_buku_order',
          headerName: 'No. Order',
          sortable: true, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'No. Prefix',
          field: 'no_prefix',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
        {
          headerName: 'No. Suffix',
          field: 'no_suffix',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
        {
          headerName: 'Ukuran',
          field: 'ukuran.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
        {
          headerName: 'Jenis',
          field: 'jenis.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
        {
          headerName: 'Kode Customer',
          field: 't_buku_order.m_customer_kode',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        }
      ]" />
      <FieldSelect class="w-full !mt-3" :bind="{ disabled:true, readonly:true }" :value="data.nama_customer_2"
        @input="v=>data.nama_customer_2=v" :errorText="formErrors.nama_customer_2?'failed':''"
        :hints="formErrors.nama_customer_2" valueField="id" displayField="kode" :api="{
              url: `${store.server.url_backend}/operation/m_customer`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
              }
          }" label="Nama Customer" fa-icon="" placeholder="" :check="false" />
    </div>

    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
      <FieldX :bind="{ readonly: !actionText && (!actionSingleEdit || data.is_con_edit == true) }" class="w-full !mt-3"
        :value="data.no_prefix_2" @input="v=>data.no_prefix_2=v" :errorText="formErrors.no_prefix_2?'failed':''"
        :hints="formErrors.no_prefix_2" placeholder="No. Prefix 2" :check="false" />
      <FieldX :bind="{ readonly: !actionText && (!actionSingleEdit || data.is_con_edit == true)   }"
        class="w-full !mt-3" :value="data.no_suffix_2" @input="v=>data.no_suffix_2=v"
        :errorText="formErrors.no_suffix_2?'failed':''" :hints="formErrors.no_suffix_2" placeholder="No. Suffix 2"
        :check="false" />
    </div>

    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="data.ukuran_container_2"
        @input="v=>data.ukuran_container_2=v" :errorText="formErrors.ukuran_container_2?'failed':''"
        :hints="formErrors.ukuran_container_2" label="Ukuran Container 2" placeholder="Ukuran Container 2"
        :check="false" />

      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.jenis_container_2"
        @input="v=>data.jenis_container_2=v" :errorText="formErrors.jenis_container_2?'failed':''"
        :hints="formErrors.jenis_container_2" label="Jenis Container 2" placeholder="Jenis Container 2"
        :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.dari" @input="v=>data.dari=v"
        :errorText="formErrors.dari?'failed':''" :hints="formErrors.dari" placeholder="Dari" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.ke" @input="v=>data.ke=v"
        :errorText="formErrors.ke?'failed':''" :hints="formErrors.ke" placeholder="Ke" :check="false" />
    </div>

    <div>
      <FieldNumber type="number" :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.sangu"
        @input="v=>data.sangu=v" :errorText="formErrors.sangu?'failed':''" :hints="formErrors.sangu" label="Sangu"
        placeholder="Sangu" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>data.catatan=v" :hints="formErrors.catatan"
        :check="false" type="textarea" label="Catatan" placeholder="Catatan" />
    </div>

    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, clearable:true }" :value="data.status"
        @input="v=>data.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status" valueField="id"
        displayField="key" :options="[{'id' : 'DRAFT' , 'key' : 'DRAFT'},
      {'id' : 'Posted', 'key' : 'Posted'},
      {'id' : 'APPROVAL', 'key' : 'APPROVAL'},
      {'id' : 'APPROVED', 'key' : 'APPROVED'}, 
      {'id' : 'COMPLETED', 'key' : 'COMPLETED'}, 
      {'id' : 'CLOSED', 'key' : 'CLOSED'}, 
      {'id' : 'CANCEL', 'key' : 'CANCEL'}, 
      {'id' : 'REJECTED', 'key' : 'REJECTED'},
      {'id' : 'REVISED', 'key' : 'REVISED'}]" fa-icon="sort-desc" placeholder="Pilih Status" label="Status"
        :check="false" />
    </div>
    <div>
      <p type="number" class="w-full !mt-3" style="font-size: 20px; font-weight: bold;">Total Sangu : Rp. {{
        data.total_sangu_tampil }}</p>
    </div>
  </div>
  <hr />

  <!-- START TABLE DETAIL -->
  <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <button v-show="actionText" class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300" @click="addDetailBon">
        <icon fa="plus" size="sm" />
        <span>Add To List</span>
      </button>
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Keterangan</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nominal</td>
            <td v-show="actionText"
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              Action</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="i" class="border-t" v-show="detailArr.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i+1 }}.
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              <FieldX :bind="{ readonly: !actionText }" :value="detailArr[i].keterangan"
                :errorText="formErrors.keterangan?'failed':''" @input="v=>detailArr[i].keterangan=v"
                :hints="formErrors.keterangan" label="" placeholder="Keterangan" :check="false" />
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              <FieldNumber type="number" :bind="{ readonly: !actionText }" :value="detailArr[i].nominal"
                :errorText="formErrors.nominal?'failed':''" @input="v=>detailArr[i].nominal=v"
                :hints="formErrors.nominal" label="" placeholder="Nominal" :check="false" />
            </td>
            <td v-show="actionText" class="p-2 border border-[#CACACA]">
              <div class="flex justify-center">
                <button type="button" @click="delDetailBon(i)" :disabled="!actionText">
                  <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
          <tr v-show="detailArr.length <= 0" class="text-center">
            <td colspan="15" class="py-[20px]">
              No data to show
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- END TABLE DETAIL -->
  <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start justify-center w-md mb-3">
    <label class="!mt-4 !ml-3"> Total Bon Tambahan : </label>
    <FieldNumber type="number" :bind="{ readonly: true }" class="w-full content-center !mt-3"
      :value="data.total_bon_tambahan" @input="v=>data.total_bon_tambahan=v"
      :errorText="formErrors.total_bon_tambahan?'failed':''" :hints="formErrors.total_bon_tambahan"
      placeholder="Total Bon Tambahan" label="" :check="false" />
  </div>
  <!-- ACTION BUTTON FORM -->
  <hr v-show="actionText" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="actionText || actionSingleEdit">
    <i class="text-gray-500 text-[12px] mr-4">Tekan CTRL + S untuk shortcut Save Data</i>
    <button class="text-sm rounded py-2 px-2.5 text-white bg-red-600 hover:bg-red-700 flex gap-x-1 
        items-center transition-colors duration-300" @click="onReset(true)">
      <icon fa="times" />
      <span>Reset</span>
    </button>
    <button class="text-sm rounded py-2 px-2.5 text-white bg-green-600 hover:bg-green-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="onSave">
      <icon fa="save" />
      <span>Simpan</span>
    </button>
    <button v-if="(((actionText=='Edit' || actionText=='Create' || actionText=='Copy') && (data.status=='DRAFT' || data.status=='REVISED')))" class="text-sm rounded py-2 px-2.5 text-white bg-purple-600 hover:bg-purple-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="sendApproval">
      <icon fa="location-arrow" />
      <span>Send Approval</span>
    </button>
  </div>


  <hr v-show="isApproval" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="isApproval">
    <button class="text-sm rounded py-2 px-2.5 text-white  bg-green-600 hover:bg-green-700 flex gap-x-1 items-center 
        transition-colors duration-300" @click="progress('APPROVED')">
      <!-- <icon fa="times" /> -->
      <span>Approve</span>
    </button>
    <button class="text-sm rounded py-2 px-2.5 text-white bg-orange-600 hover:bg-orange-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="progress('REVISED')">
      <!-- <icon fa="save" /> -->
      <span>Revise</span>
    </button>
    <button class="text-sm rounded py-2 px-2.5 text-white  bg-red-600 hover:bg-red-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="progress('REJECTED')">
      <!-- <icon fa="save" /> -->
      <span>Reject</span>
    </button>
  </div>
</div>

@endverbatim
@endif