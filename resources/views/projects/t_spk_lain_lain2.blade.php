<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-green-600 text-white hover:blue-600-600' 
          : 'border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          DRAFT
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-amber-600 text-white hover:bg-amber-600' 
          : 'border border-amber-600 text-amber-600 bg-white hover:bg-amber-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          POST
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('IN PROCESS')" :class="filterButton === 'IN PROCESS' ? 'bg-purple-600 text-white hover:bg-purple-600' 
          : 'border border-purple-600 text-purple-600 bg-white hover:bg-purple-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          IN PROCESS
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('COMPLETE')" :class="filterButton === 'COMPLETE' ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          COMPLETE
        </button>
      </div>
    </div>

    <!-- ACTION BUTTON -->
    <div class="flex items-center gap-x-4">
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
        <h1 class="text-lg font-bold leading-none">Form SPK Lain-lain</h1>
        <p class="text-gray-100 leading-none">Transaction SPK Lain-lain</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 items-start">
    <!-- col-span-2 -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.kode" @input="v=>data.kode=v"
        :errorText="formErrors.kode?'failed':''" :hints="formErrors.kode" label="kode" placeholder="Kode"
        :check="false" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ readonly: true, disabled: true, clearable:true }" :value="data.status"
        @input="v=>data.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status" valueField="id"
        displayField="key" :options="[{'id' : 'DRAFT' , 'key' : 'DRAFT'},
      {'id' : 'POSTED' , 'key' : 'POSTED'},
      {'id' : 'IN PROCESS' , 'key' : 'IN PROCESS'},
      {'id' : 'COMPLETE' , 'key' : 'COMPLETE'}]" placeholder="Pilih Status" fa-icon="sort-desc" label="Status"
        :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.no_spk" @input="v=>data.no_spk=v"
        :errorText="formErrors.no_spk?'failed':''" :hints="formErrors.no_spk" placeholder="Nomor SPK Lain-Lain"
        :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.tanggal_spk"
        @input="v=>data.tanggal_spk=v" :errorText="formErrors.tanggal_spk?'failed':''" :hints="formErrors.tanggal_spk"
        type="date" placeholder="Tanggal SPK Lain-Lain" :check="false" />
    </div>
    <div>
      <FieldPopup :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.genzet"
        @input="(v)=>data.genzet=v" :errorText="formErrors.genzet?'failed':''" :hints="formErrors.genzet"
        valueField="id" displayField="key" :api="{
          url:  `${store.server.url_backend}/operation/genzet`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
          }
        }" placeholder="Genzet" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'columnname',
          headerName:  'Label Header Name',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        }]" />
    </div>
    <div>
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/t_buku_order_d_npwp`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest:false,
          // transform:false,
          // join:true,
          // override:true,
          // where:`this.is_active=true`,
          // searchfield:'this.no_id, this.nip, this.nama, this.alamat_domisili',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili' 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="t_buku_order.no_buku_order" valueField="id" :bind="{ readonly: !actionText }"
        :value="data.buku_order_id" @input="(v)=>data.buku_order_id=v" @update:valueFull="(response)=>{
        if(response == null) {
          data.ukuran_container_1 = '';
          data.tipe_container_1 = ''
          data.t_buku_order_1_id = '';
        } else {
          data.ukuran_container_1=response['ukuran.deskripsi'];
          data.tipe_container_1=response['tipe.deskripsi'];
          data.t_buku_order_1_id = response.t_buku_order_id;
        }
      }" :errorText="formErrors.buku_order_id?'failed':''" class="w-full !mt-3" :hints="formErrors.buku_order_id"
        placeholder="No. Order 1" :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 't_buku_order.no_buku_order',
          headerName: 'No. Order',
          sortable: true, resizable: true, filter: false,
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'No. Prefix',
          field: 'no_prefix',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'No. Suffix',
          field: 'no_suffix',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Ukuran',
          field: 'ukuran',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Tipe',
          field: 'tipe.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'jenis',
          field: 'jenis.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
      ]" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.nama_customer"
        @input="v=>data.nama_customer=v" :errorText="formErrors.nama_customer?'failed':''"
        :hints="formErrors.nama_customer" label="Nama Customer" placeholder="Nama Customer" :check="false" />
    </div>
    <div>
      <FieldPopup :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.no_container"
        @input="(v)=>data.no_container=v" :errorText="formErrors.no_container?'failed':''"
        :hints="formErrors.no_container" valueField="id" displayField="key" :api="{
          url:  `${store.server.url_backend}/operation/container`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
          }
        }" placeholder="No. Container" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'columnname',
          headerName:  'Label Header Name',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        }]" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.ukuran" @input="v=>data.ukuran=v"
        :errorText="formErrors.ukuran?'failed':''" :hints="formErrors.ukuran" label="Ukuran" placeholder="Ukuran"
        :check="false" />
    </div>
    <div>
      <FieldPopup :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.sektor"
        @input="(v)=>data.sektor=v" :errorText="formErrors.sektor?'failed':''" :hints="formErrors.sektor"
        valueField="id" displayField="key" :api="{
          url:  `${store.server.url_backend}/operation/sektor`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
          }
        }" placeholder="Sektor" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'columnname',
          headerName:  'Label Header Name',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        }]" />
    </div>
    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.keluar_lokasi"
        @input="v=>data.keluar_lokasi=v" :errorText="formErrors.keluar_lokasi?'failed':''"
        :hints="formErrors.keluar_lokasi" type="date" placeholder="Keluar Lokasi" :check="false" />

      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.time" @input="v=>data.time=v"
        :errorText="formErrors.time?'failed':''" :hints="formErrors.time" type="time" placeholder="Jam Keluar"
        :check="false" />
    </div>
    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.tiba_lokasi"
        @input="v=>data.tiba_lokasi=v" :errorText="formErrors.tiba_lokasi?'failed':''" :hints="formErrors.tiba_lokasi"
        type="date" placeholder="Tiba Lokasi" :check="false" />

      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.jam_tiba" @input="v=>data.jam_tiba=v"
        :errorText="formErrors.jam_tiba?'failed':''" :hints="formErrors.jam_tiba" type="time" placeholder="Jam Tiba"
        :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.lokasi_stuffing"
        @input="v=>data.lokasi_stuffing=v" :errorText="formErrors.lokasi_stuffing?'failed':''"
        :hints="formErrors.lokasi_stuffing" label="Lokasi Stuffing" placeholder="Lokasi Stuffing" type="textarea"
        :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.catatan"
        @input="v=>data.catatan=v" :errorText="formErrors.catatan?'failed':''"
        :hints="formErrors.catatan" label="Catatan" placeholder="Catatan" type="textarea"
        :check="false" />
    </div>
  </div>
  <hr />
  <!-- ACTION BUTTON FORM -->
  <hr v-show="actionText" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="actionText">
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
  </div>
  <hr v-show="!actionText" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="(!actionText && data.status=='POST')">
    <button class="text-sm rounded py-2 px-2.5 text-white bg-purple-600 hover:bg-purple-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="inProcess">
      <icon fa="save" />
      <span>In Process</span>
    </button>
  </div>
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4"
    v-show="(!actionText && data.status=='IN PROCESS')">
    <button class="text-sm rounded py-2 px-2.5 text-white bg-green-600 hover:bg-green-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="complete">
      <icon fa="save" />
      <span>COMPLETE</span>
    </button>
  </div>
</div>

@endverbatim
@endif