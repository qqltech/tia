<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center px-2.5 py-1">
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
    <div>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px]">
    <!-- <template #header>
    </template> -->
  </TableApi>
</div>
@else

<!-- CONTENT -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Master Tarif Nota Rampung</h1>
        <p class="text-gray-100">Form Pengisian Tarif Nota Rampung</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->

    <!-- No Tarif Coloumn -->
    <div>
      <FieldX :bind="{ disabled: !actionText, readonly: true }" class="w-full !mt-3" :value="values.no_tarif"
        :errorText="formErrors.no_tarif?'failed':''" @input="v=>values.no_tarif=v" :hints="formErrors.no_tarif" label="No Tarif"
        placeholder="No Tarif" :check="false" />
    </div>

    <!-- Kode Pelabuhan Coloumn -->
    <div class="flex">
      <FieldPopup class="!mt-3 w-full" :api="{
        url: `${store.server.url_backend}/operation/m_general`,
        headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
        params: {
          simplest:true,
          where: `this.group = 'PELABUHAN' AND this.is_active = true`,
          searchfield: 'this.kode, this.deskripsi'
          },
          onsuccess(response) {
          response.page = response.current_page
          response.hasNext = response.has_next
          return response
        }
        }" displayField="kode" valueField="id" :bind="{ readonly: !actionText }" :value="values.kode_pelabuhan"
        @input="(v)=>values.kode_pelabuhan=v"  @update:valueFull="(data)=>{
          if(data){
            values.nama_pelabuhan = data.deskripsi
          }
          else{
            values.nama_pelabuhan = '';
          }
        }" :errorText="formErrors.kode_pelabuhan?'failed':''" class="w-full !mt-3" :hints="formErrors.kode_pelabuhan"
        placeholder="Pilih Kode Pelabuhan" label="Kode Pelabuhan" :check='false' :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'kode',
            headerName: 'KODE',
            sortable: false, resizable: true, filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          },
          {
            flex: 1,
            field: 'deskripsi',
            headerName: 'NAMA PELABUHAN',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: false, resizable: true, filter: false,
          },
          ]" />
      <span class="text-red-500"> * </span>
    </div>

    <!-- Nama Pelabuhan Coloumn -->
    <div>
      <FieldX :bind="{ disabled: true, readonly: true }" class="w-full !mt-3" :value="values.nama_pelabuhan"
         @update:value="v => values.nama_pelabuhan = v" :errorText="formErrors.nama_pelabuhan ? 'failed' : ''" :hints="formErrors.nama_pelabuhan"
        placeholder="Nama Pelabuhan" label="Nama Pelabuhan" :check="false" />
    </div>

    <!-- Ukuran Container Coloumn -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, readonly: !actionText }" class="w-full !mt-3" :value="values.ukuran_container"
        @input="v=>values.ukuran_container=v" :errorText="formErrors.ukuran_container ? 'failed' : ''"
        :hints="formErrors.ukuran_container" valueField="id" displayField="deskripsi" placeholder="Ukuran Kontainer"
        label="Ukuran Kontainer" :check="false"
        :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                //tambahkan Params where ke group jenis kontainer , 
                where:
                `this.group = 'UKURAN KONTAINER' AND this.is_active = 'true' `,
                searchfield: 'this.deskripsi'
              },
            }" />
    </div>

    <!-- Jenis Container Coloumn -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, readonly: !actionText }" class="w-full !mt-3" :value="values.jenis_container"
        @input="v=>values.jenis_container=v" :errorText="formErrors.jenis_container ? 'failed' : ''"
        :hints="formErrors.jenis_container" valueField="id" displayField="deskripsi" placeholder="Jenis Kontainer"
        label="Jenis Kontainer" :check="false" 
         :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                //tambahkan Params where ke group jenis kontainer , 
                where:
                `this.group = 'JENIS KONTAINER' AND this.is_active = 'true' `,
                searchfield: 'this.deskripsi'
              },
            }" />
    </div>

    <!-- Tipe Tarif Container Coloumn -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, readonly: !actionText }" class="w-full !mt-3" :value="values.tipe_tarif"
        @input="v=>values.tipe_tarif=v" :errorText="formErrors.tipe_tarif ? 'failed' : ''"
        :hints="formErrors.tipe_tarif" valueField="id" displayField="deskripsi" placeholder="Pilih Tipe Tarif"
        label="Tipe Tarif" :check="false" 
         :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                //tambahkan Params where ke group jenis kontainer , 
                where:
                `this.group = 'TIPE TARIF NOTA RAMPUNG' AND this.is_active = 'true' `,
                searchfield: 'this.deskripsi'
              },
            }" />
    </div>

    <!-- Tarif LoLo Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_lolo"
        :errorText="formErrors.tarif_lolo?'failed':''" @input="v=>values.tarif_lolo=v"
        :hints="formErrors.tarif_lolo" label="Tarif LoLo" placeholder="Tarif LoLo" :check="false" />
    </div>

    <!-- Tarif M2 Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_m2"
        :errorText="formErrors.tarif_m2?'failed':''" @input="v=>values.tarif_m2=v"
        :hints="formErrors.tarif_m2" label="Tarif M2" placeholder="Tarif M2" :check="false" />
    </div>

    <!-- Tarif M3 Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_m3"
        :errorText="formErrors.tarif_m3?'failed':''" @input="v=>values.tarif_m3=v"
        :hints="formErrors.tarif_m3" label="Tarif M3" placeholder="Tarif M3" :check="false" />
    </div>
    
    <!-- Tarif M4 Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_m4"
        :errorText="formErrors.tarif_m4?'failed':''" @input="v=>values.tarif_m4=v"
        :hints="formErrors.tarif_m4" label="Tarif M4" placeholder="Tarif M4" :check="false" />
    </div>

    <!-- Tarif M5 Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_m5"
        :errorText="formErrors.tarif_m5?'failed':''" @input="v=>values.tarif_m5=v"
        :hints="formErrors.tarif_m5" label="Tarif M5" placeholder="Tarif M5" :check="false" />
    </div>

    <!-- Tarif OW Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_ow"
        :errorText="formErrors.tarif_ow?'failed':''" @input="v=>values.tarif_ow=v"
        :hints="formErrors.tarif_ow" label="Tarif OW" placeholder="Tarif OW" :check="false" />
    </div>

    <!-- Tarif Plg + Mon Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_plg_mon"
        :errorText="formErrors.tarif_plg_mon?'failed':''" @input="v=>values.tarif_plg_mon=v"
        :hints="formErrors.tarif_plg_mon" label="Tarif Plg + Mon" placeholder="Tarif Plg + Mon" :check="false" />
    </div>

    <!-- Tarif GE Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_ge"
        :errorText="formErrors.tarif_ge?'failed':''" @input="v=>values.tarif_ge=v"
        :hints="formErrors.tarif_ge" label="Tarif GE" placeholder="Tarif GE" :check="false" />
    </div>

    <!-- Tarif Container Doc Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_container_doc"
        :errorText="formErrors.tarif_container_doc?'failed':''" @input="v=>values.tarif_container_doc=v"
        :hints="formErrors.tarif_container_doc" label="Tarif Canc Doc" placeholder="Tarif Canc Doc" :check="false" />
    </div>

    <!-- Tarif STRTP/STUFF Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_strtp_stuff"
        :errorText="formErrors.tarif_strtp_stuff?'failed':''" @input="v=>values.tarif_strtp_stuff=v"
        :hints="formErrors.tarif_strtp_stuff" label="Tarif STRTP/STUFF" placeholder="Tarif STRTP/STUFF" :check="false" />
    </div>

    <!-- Tarif Batal Muat Pindah Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_batal_muat_pindah"
        :errorText="formErrors.tarif_batal_muat_pindah?'failed':''" @input="v=>values.tarif_batal_muat_pindah=v"
        :hints="formErrors.tarif_batal_muat_pindah" label="Tarif Batal Muat Pindah" placeholder="Tarif Batal Muat Pindah" :check="false" />
    </div>

    <!-- Tarif Closing Container Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_closing_container"
        :errorText="formErrors.tarif_closing_container?'failed':''" @input="v=>values.tarif_closing_container=v"
        :hints="formErrors.tarif_closing_container" label="Tarif Closing Container" placeholder="Tarif Closing Container" :check="false" />
    </div>

    <!-- Tarif MOB Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_mob"
        :errorText="formErrors.tarif_mob?'failed':''" @input="v=>values.tarif_mob=v"
        :hints="formErrors.tarif_mob" label="Tarif MOB" placeholder="Tarif MOB" :check="false" />
    </div>

    <!-- Tarif VGM Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_vgm"
        :errorText="formErrors.tarif_vgm?'failed':''" @input="v=>values.tarif_vgm=v"
        :hints="formErrors.tarif_vgm" label="Tarif VGM" placeholder="Tarif VGM" :check="false" />
    </div>

    <!-- Tarif BY ADM NR Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_by_adm_nr"
        :errorText="formErrors.tarif_by_adm_nr?'failed':''" @input="v=>values.tarif_by_adm_nr=v"
        :hints="formErrors.tarif_by_adm_nr" label="Tarif BY ADM NR" placeholder="Tarif BY ADM NR" :check="false" />
    </div>

    <!-- Tarif Materai Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_materai"
        :errorText="formErrors.tarif_materai?'failed':''" @input="v=>values.tarif_materai=v"
        :hints="formErrors.tarif_materai" label="Tarif Materai" placeholder="Tarif Materai" :check="false" />
    </div>

    <!-- Tarif Denda Koreksi Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_denda_koreksi"
        :errorText="formErrors.tarif_denda_koreksi?'failed':''" @input="v=>values.tarif_denda_koreksi=v"
        :hints="formErrors.tarif_denda_koreksi" label="Tarif Denda Koreksi" placeholder="Tarif Denda Koreksi" :check="false" />
    </div>

    <!-- Tarif Denda Sp Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_denda_sp"
        :errorText="formErrors.tarif_denda_sp?'failed':''" @input="v=>values.tarif_denda_sp=v"
        :hints="formErrors.tarif_denda_sp" label="Tarif Denda SP" placeholder="Tarif Denda SP" :check="false" />
    </div>

    <!-- Tarif Behandle Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_behandle"
        :errorText="formErrors.tarif_behandle?'failed':''" @input="v=>values.tarif_behandle=v"
        :hints="formErrors.tarif_behandle" label="Tarif Behandle" placeholder="Tarif Behandle" :check="false" />
    </div>

    <!-- Catatan Column -->
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
      :value="values.catatan" :errorText="formErrors.catatan?'failed':''"
      @input="v=>values.catatan=v" :hints="formErrors.catatan" 
      placeholder="Catatan" label="Catatan" :check="false"
      />
    </div>
    
    <!-- Status Button -->
     <div class="flex flex-col gap-2 pt-2 ml-1">
      <label class="text-gray-600 text-xs font-semibold">Status</label>
      <div class="flex gap-2">
        <div class="relative">
          <input class="relative h-[16px] w-7 p-px appearance-none rounded-full bg-white border disabled:!cursor-default
            hover:cursor-pointer after:content-[''] after:h-[10.5px] after:w-[10.5px] after:rounded-full after:border-none
            after:absolute after:mt-[0.9px] focus:outline-none after:ml-[0.95px] checked:after:right-[1.25px] disabled:opacity-75
            after:bg-red-600 border-red-600 checked:after:bg-green-600 checked:border-green-600" 
            type="checkbox" role="switch" :disabled="!actionText" v-model="values.is_active" />
        </div>
        <div :class="(values.is_active ? 'text-green-600' : 'text-red-600') + ' text-xs'">
          {{values.is_active ? 'Active' : 'InActive' }}
        </div>
      </div>
    </div>

    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>


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
</div>
@endverbatim
@endif