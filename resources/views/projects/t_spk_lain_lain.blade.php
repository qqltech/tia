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
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.no_spk" @input="v=>data.no_spk=v"
        :errorText="formErrors.no_spk?'failed':''" :hints="formErrors.no_spk" placeholder="Nomor SPK" :check="false" />
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
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.tarif_genzet"
        @input="v=>data.tarif_genzet=v" :errorText="formErrors.tarif_genzet?'failed':''"
        :hints="formErrors.tarif_genzet" label="Tarif Genzet" placeholder="Tarif Genzet" :check="false" />
    </div>
    <!-- <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.jenis_spk"
        @input="v=>data.jenis_spk=v" :errorText="formErrors.jenis_spk?'failed':''" :hints="formErrors.jenis_spk"
        valueField="id" displayField="key" :options="[{'id' : 'SPK A', 'key' : 'SPK A'},
      {'id' : 'SPK B', 'key' : 'SPK B'},
      {'id' : 'SPK C', 'key' : 'SPK C'},
      {'id' : 'SPK D', 'key' : 'SPK D'},
      {'id' : 'SPK E', 'key' : 'SPK E'}]" fa-icon="sort-desc" placeholder="Pilih Jenis SPK" label="Jenis SPK" :check="false" />
    </div> -->
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.jenis"
        @input="v=>data.jenis=v" :errorText="formErrors.jenis?'failed':''" :hints="formErrors.jenis" valueField="id"
        displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='Jenis SPK'`
              }
          }" label="Jenis SPK" placeholder="Pilih Jenis SPK" fa-icon="sort-desc" :check="false" />
    </div>
    <!-- <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.lokasi_stuffing"
        @input="v=>data.lokasi_stuffing=v" :errorText="formErrors.lokasi_stuffing?'failed':''"
        :hints="formErrors.lokasi_stuffing" valueField="id" displayField="key" :options="[{'id' : 'Surabaya A', 'key' : 'Surabaya A'},
      {'id' : 'Surabaya B', 'key' : 'Surabaya B'},
      {'id' : 'Surabaya C', 'key' : 'Surabaya C'},
      {'id' : 'Surabaya D', 'key' : 'Surabaya D'},
      {'id' : 'Surabaya E', 'key' : 'Surabaya E'}]" placeholder="Pilih Jenis SPK" label="Jenis SPK" :check="false" />
    </div> -->
    <div>
      <FieldNumber type="number" :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.sangu_tia"
        @input="v=>data.sangu_tia=v" :errorText="formErrors.sangu_tia?'failed':''" :hints="formErrors.sangu_tia"
        label="Sangu Tia" placeholder="Sangu Tia" :check="false" />
    </div>
    <div>
      <FieldNumber type="number" :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.sangu_free"
        @input="v=>data.sangu_free=v" :errorText="formErrors.sangu_free?'failed':''" :hints="formErrors.sangu_free"
        label="Biaya Lain-Lain" placeholder="Biaya Lain-Lain" :check="false" />
    </div>
    <!-- <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3" :value="data.lokasi_stuffing"
        @input="v=>{
        if(v){
          data.lokasi_stuffing=v
        }else{
          data.lokasi_stuffing=null
        }
      }" :errorText="formErrors.lokasi_stuffing?'failed':''" :hints="formErrors.lokasi_stuffing" valueField="id"
        displayField="nama_lokasi" :api="{
          url: `${store.server.url_backend}/operation/m_lokasistuffing`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            transform:false,
            join:false,

          }
      }" placeholder="Pilih Lokasi Stuffing" fa-icon="sort-desc" label="Lokasi Stuffing" :check="true" />
    </div> -->
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.lokasi_stuffing"
        @input="v=>data.lokasi_stuffing=v" :errorText="formErrors.lokasi_stuffing?'failed':''"
        :hints="formErrors.lokasi_stuffing" placeholder="Lokasi Stuffing" :check="false" />
    </div>
    <!-- <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.lokasi_stuffing"
        @input="v=>data.lokasi_stuffing=v" :errorText="formErrors.lokasi_stuffing?'failed':''" :hints="formErrors.lokasi_stuffing"
        valueField="id" displayField="key" :options="[{'id' : 'Surabaya', 'key' : 'Surabaya'},
      {'id' : 'Sidoarjo', 'key' : 'Sidoarjo'},
      {'id' : 'Gresik', 'key' : 'Gresik'},
      {'id' : 'Nganjuk', 'key' : 'Nganjuk'},
      {'id' : 'dll', 'key' : 'dll'}]" placeholder="Pilih Lokasi Stuffing" fa-icon="sort-desc" label="Lokasi Stuffing" :check="false" />
    </div> -->
    <!-- <div>
      <FieldX type="number" :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.tarif_genzet"
        @input="v=>data.tarif_genzet=v" :errorText="formErrors.tarif_genzet?'failed':''"
        :hints="formErrors.tarif_genzet" label="Tarif Genzet" placeholder="Tarif Genzet" :check="false" />
    </div> -->
    <div>
      <FieldNumber type="number" :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.ganti_solar_sangu"
        @input="v=>data.ganti_solar_sangu=v" :errorText="formErrors.ganti_solar_sangu?'failed':''"
        :hints="formErrors.ganti_solar_sangu" label="Ganti Solar Sangu" placeholder="Ganti Solar Sangu"
        :check="false" />
    </div>
    <div>
      <FieldNumber type="number" :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.ganti_solar_tag"
        @input="v=>data.ganti_solar_tag=v" :errorText="formErrors.ganti_solar_tag?'failed':''"
        :hints="formErrors.ganti_solar_tag" label="Ganti Solar Tag" placeholder="Ganti Solar Tag" :check="false" />
    </div>
    <div>
      <FieldNumber type="number" :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.kilometer"
        @input="v=>data.kilometer=v" :errorText="formErrors.kilometer?'failed':''" :hints="formErrors.kilometer"
        label="Kilometer" placeholder="Masukan kilometer" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>data.catatan=v" :hints="formErrors.catatan"
        :check="false" type="textarea" label="Catatan" placeholder="Catatan" />
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