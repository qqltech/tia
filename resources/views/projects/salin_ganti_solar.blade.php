@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true)" :class="filterButton === true ? 'bg-green-600 text-white hover:bg-green-600' 
        : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'" class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
          Active
        </button>
        <div class="flex my-auto h-4 w-px bg-[#6E91D1]"></div>
        <button @click="filterShowData(false)" :class="filterButton === false ? 'bg-red-600 text-white hover:bg-red-600' 
        : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'" class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
          InActive
        </button>
      </div>
    </div>

    <!-- ACTION BUTTON -->
    <div class="flex items-center gap-x-4">
      <RouterLink :to="$route.path + '/create?' + (Date.parse(new Date()))" class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white 
                        text-sm rounded-md py-1 px-2.5 transition-colors duration-300">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>

  <!-- TABLE -->
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px] pt-2 !px-4 !pb-8">
    <template #header>
      <div class="pb-13 h-full"></div>
    </template>
  </TableApi>
</div>


</div>
@else

<!-- CONTENT -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Transaksi Ganti Solar</h1>
        <p class="text-gray-100">Untuk mengatur Pergantian Solar</p>
      </div>
    </div>
  </div>
  <!-- HEADER END -->

  <!-- FORM START -->
  <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row p-4 gap-3">
    <div class=" w-full !mt-3">
      <FieldPopup class="w-full !mt-3" displayField="no_spk" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.t_spk_angkutan_id" @input="(v)=>values.t_spk_angkutan_id=v"
        :errorText="formErrors.t_spk_angkutan_id?'failed':''" :hints="formErrors.t_spk_angkutan_id"
        placeholder="Pilih SPK" label="ORDER ANGKUTAN" :check='false'
       @update:valueFull="solar"
         :api="{
            url: `${store.server.url_backend}/operation/t_spk_angkutan`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              searchfield:'this.no_lpb , this.tanggal_lpb , this.no_sj_supplier , this.tanggal_sj_supplier',
            },
          }" :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'no_spk',
            headerName: 'Nomor SPK',
            sortable: true, 
            resizable: true, 
            filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            
          },
          {
            flex: 1,
             field: 'tanggal_spk',
            headerName: 'Tanggal SPK',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'supir.nama',
            headerName: 'Supir',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },


          ]" />
    </div>



    <!-- Status Coloumn -->
    <div class="w-full !mt-3">
      <FieldX class="w-full !mt-3" :bind="{ disabled: true, clearable:true  ,readonly:true}" :value="values.status"
        @input="v=>values.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status"
        placeholder="Pilih Status" label="Status" :check="false" />
    </div>

       <div class="w-full !mt-3">
      <FieldSelect :bind="{ disabled: true, readonly: true , clearable:false }" class="w-full !mt-3"
        :value="values.tipe" @input="v=>values.tipe=v" :errorText="formErrors.tipe?'failed':''"
        :hints="formErrors.tipe" valueField="id" displayField="deskripsi" :api="{          
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        simplest:true,
                      }
                  }" placeholder="Pilih EKSP / IMP" label="EKS / IMP" :check="false" />
    </div>



    <div class="w-full !mt-3">
      <FieldX class="w-full !mt-3" :bind="{ disabled:true , readonly: true }" :value="values.tgl"
        :errorText="formErrors.tgl?'failed':''" @input="v=>values.tgl=v" :hints="formErrors.tgl"  
        placeholder="Tanggal" label="Tanggal" :check="false" />
    </div>




    <div class="w-full !mt-3">
      <FieldX class="w-full !mt-3" :bind="{ readonly: true }" :value="values.no_container_1"
        :errorText="formErrors.no_container_1?'failed':''" @input="v=>values.no_container_1=v" :hints="formErrors.no_container_1"
        placeholder="Nomor Kontainer 1" label="Nomor Kontainer 1" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="w-full !mt-3" :bind="{ readonly: true }" :value="values.no_container_2"
        :errorText="formErrors.no_container_2?'failed':''" @input="v=>values.no_container_2=v" :hints="formErrors.no_container_2"
        placeholder="Nomor Kontainer 2" label="Nomor Kontainer 2" :check="false" />
    </div>

        <div class="w-full !mt-3">
            <FieldPopup class="w-full !mt-3" displayField="no_prefix" valueField="id" :bind="{ readonly: true ,disabled: true }"
        :value="values.container_1" @input="(v)=>values.container_1=v"
        :errorText="formErrors.container_1?'failed':''" :hints="formErrors.container_1"
        placeholder="Pilih Kontainer" label="Container" :check='false' :api="{
            url: `${store.server.url_backend}/operation/t_buku_order_d_npwp`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              searchfield:'this.no_lpb , this.tanggal_lpb , this.no_sj_supplier , this.tanggal_sj_supplier',
            },
          }" :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'no_prefix',
            headerName: 'Nomor PREFIX',
            sortable: true, 
            resizable: true, 
            filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            
          },
          {
            flex: 1,
             field: 'no_suffix',
            headerName: 'Tanggal SPK',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          ]" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="w-full !mt-3" :bind="{ disabled: true, clearable:true  ,readonly:true}" :value="values.rit"
        @input="v=>values.rit=v" :errorText="formErrors.rit?'failed':''" :hints="formErrors.rit"
        placeholder="Masukan RIT (PPF)" label="RIT (PFF)" :check="false" />
    </div>

    <div class="w-full !mt-3">
            <FieldPopup class="w-full !mt-3" displayField="nama" valueField="id" :bind="{ readonly: true ,disabled: true }"
        :value="values.supir" @input="(v)=>values.supir=v"
        :errorText="formErrors.supir?'failed':''" :hints="formErrors.supir"
        placeholder="Supir" label="Supir" :check='false' :api="{
            url: `${store.server.url_backend}/operation/m_kary`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              searchfield:'this.no_lpb , this.tanggal_lpb , this.no_sj_supplier , this.tanggal_sj_supplier',
            },
          }" :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'no_spk',
            headerName: 'Nomor SPK',
            sortable: true, 
            resizable: true, 
            filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            
          },
          {
            flex: 1,
             field: 'tanggal_spk',
            headerName: 'Tanggal SPK',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'supir.nama',
            headerName: 'Supir',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },


          ]" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="w-full !mt-3" :bind="{ disabled: true, clearable:true  ,readonly:true}" :value="values.dari"
        @input="v=>values.dari=v" :errorText="formErrors.dari?'failed':''" :hints="formErrors.dari"
        placeholder="Dari" label="Dari" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="w-full !mt-3" :bind="{ disabled: true, clearable:true  ,readonly:true}" :value="values.ke"
        @input="v=>values.ke=v" :errorText="formErrors.ke?'failed':''" :hints="formErrors.ke"
        placeholder="Ke" label="Ke" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldSelect :bind="{ disabled: true, readonly: true , clearable:false }" class="w-full !mt-3"
        :value="values.sektor" @input="v=>values.sektor=v" :errorText="formErrors.sektor?'failed':''"
        :hints="formErrors.sektor" valueField="id" displayField="deskripsi" :api="{          
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        simplest:true,
                      }
                  }" placeholder="Pilih Sektor" label=" Sektor" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldNumber type="number" class="w-full !mt-3" :bind="{ disabled: true, clearable:true  ,readonly:true}" :value="values.premi?.toString()"
        @input="v=>values.premi=v" :errorText="formErrors.premi?'failed':''" :hints="formErrors.premi"
        placeholder="Premi" label="Premi" :check="false" />
    </div>
    
    <div class="w-full !mt-3">
      <FieldNumber type="number" class="w-full !mt-3" :bind="{ disabled: true, clearable:true  ,readonly:true}" :value="values.sangu?.toString()"
        @input="v=>values.sangu=v" :errorText="formErrors.sangu?'failed':''" :hints="formErrors.sangu"
        placeholder="Sangu" label="Sangu" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldNumber type="number" class="w-full !mt-3" :bind="{ disabled: true, clearable:true  ,readonly:true}" :value="values.nominal?.toString()"
        @input="v=>values.nominal=v,$log(typeof values.nominal)" :errorText="formErrors.nominal?'failed':''" :hints="formErrors.nominal"
        placeholder="Nominal" label="Nominal" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldX type="textarea" class="w-full !mt-3" :bind="{ disabled: true, clearable:true  ,readonly:true}" :value="values.catatan"
        @input="v=>values.catatan=v" :errorText="formErrors.catatan?'failed':''" :hints="formErrors.catatan"
        placeholder="Catatan" label="Catatan" :check="false" />
    </div>





  </div>
  <!-- FORM END -->
  <div class="flex flex-row items-center justify-end space-x-2 p-2">
    <i class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
    <button
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click="onReset(true)"
      >
        <icon fa="times" />
        Reset
      </button>
    <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click="onSave"
      >
        <icon fa="save" />
        Simpan
      </button>
  </div>
  <hr>



</div>

@endverbatim
@endif