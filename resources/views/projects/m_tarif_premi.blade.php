<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-2.5 pt-2 pb-2">
    <h1 class="text-xl font-semibold">TARIF PREMI</h1>
  </div>
  <div class="flex justify-between items-center px-2.5 py-1">
    <div class="flex items-center gap-x-4">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true,1)" :class="activeBtn === 1?'bg-green-600 text-white hover:bg-green-400':'border border-green-600 text-green-600 bg-white  hover:bg-green-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Active</button>
        <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
        <button @click="filterShowData(false,2)" :class="activeBtn === 2?'bg-red-600 text-white hover:bg-red-400':'border border-red-600 text-red-600 bg-white  hover:bg-red-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">InActive</button>
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
        <h1 class="text-20px font-bold">Form Tarif Premi</h1>
        <p class="text-gray-100">Form unutk Pengisian Tarif Premi</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <!-- No Tarif Premi Column -->
    <div>
      <FieldSelect :bind="{ disabled: true }" class="w-full !mt-3 " :value="values.no_tarif_premi"
        :errorText="formErrors.no_tarif_premi?'failed':''" @input="v=>values.no_tarif_premi=v"
        :hints="formErrors.no_tarif_premi" label="No. Tarif Premi" placeholder="No. Tarif Premi" :check="false" />
    </div>

    <!-- Sektor Coloumn -->
    <div class=" w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, clearable:true }" :value="values.sektor_id"
        @input="v=>values.sektor_id=v" :errorText="formErrors.sektor_id?'failed':''" :hints="formErrors.sektor_id"
        valueField="id" displayField="deskripsi" :api="{
                url: `${store.server.url_backend}/operation/m_general`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest: true,
                  where:`this.group='SEKTOR'`
                }
            }" label="Tipe Sektor" placeholder="Pilih Tipe Sektor" fa-icon="sort-desc" :check="false" />
    </div>
    <!-- Tipe Kontainer Coloumn -->
    <div class=" w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, clearable:true }" :value="values.tipe_kontainer"
        @input="v=>values.tipe_kontainer=v" :errorText="formErrors.tipe_kontainer?'failed':''"
        :hints="formErrors.tipe_kontainer" valueField="id" displayField="deskripsi" :api="{
                url: `${store.server.url_backend}/operation/m_general`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest: true,
                  where:`this.group='JENIS KONTAINER'`
                }
            }" label="Jenis Kontainer" placeholder="Pilih Jenis Kontainer" fa-icon="sort-desc" :check="false" />
    </div>

    <!-- m no head -->
    <div class=" w-full !mt-3">
      <FieldPopup class="!mt-0"
        :bind="{ readonly: !actionText }"
        :value="values.no_head" @input="(v)=>values.no_head=v"
        :errorText="formErrors.no_head?'failed':''" 
        :hints="formErrors.no_head" 
        valueField="id" displayField="deskripsi"
        :api="{
          url: `${store.server.url_backend}/operation/m_general`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            where:`this.group = 'HEAD'`
          }
        }"
        label="No Head" placeholder="Pilih No Head" :check="false" 
        :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'kode',
          headerName:  'No Head',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'deskripsi',
          headerName:  'Deskripsi',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        ]"
      />
      
      <!-- <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, clearable:true }" :value="values.m_spk_angkutan_id"
        @input="v=>values.m_spk_angkutan_id=v" :errorText="formErrors.m_spk_angkutan_id?'failed':''"
        :hints="formErrors.m_spk_angkutan_id" valueField="id" displayField="no_spk" :api="{
                url: `${store.server.url_backend}/operation/m_gen`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest: true,
                }
            }" label="No Spk Angkutan" placeholder="Pilih No Spk Angkutan" fa-icon="sort-desc" :check="false" /> -->
    </div>

    <!-- Trip Coloumn -->
    <div>
<FieldSelect
        class="w-full !mt-3"
        :bind="{ disabled: !actionText, clearable:false }"
        :value="values.trip" @input="v=>values.trip=v"
        :errorText="formErrors.trip?'failed':''" 
        :hints="formErrors.trip"
        valueField="id" displayField="deskripsi"
        :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              transform:false,
              join:false,
              where:`this.group='TRIP SPK ANGKUTAN'`
            }
        }"
        label="Trip" placeholder="Trip" fa-icon="sort-desc" :check="false"
      />

      <!-- <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.trip"
        :errorText="formErrors.trip?'failed':''" @input="v=>values.trip=v" :hints="formErrors.trip"
        label="Trip" placeholder="Trip" :check="false" /> -->
    </div>


    <!-- Ukuran Kontainer Coloumn -->
    <div>
      <FieldSelect
        class="w-full !mt-3"
        :bind="{ disabled: !actionText, clearable:false }"
        :value="values.ukuran_container" @input="v=>values.ukuran_container=v"
        :errorText="formErrors.ukuran_container?'failed':''" 
        :hints="formErrors.ukuran_container"
        valueField="id" displayField="deskripsi"
        :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              transform:false,
              join:false,
              where:`this.group='UKURAN KONTAINER'`
            }
        }"
        placeholder="Ukuran Kontainer" fa-icon="sort-desc" :check="false"
      />
      
      <!-- <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.premi"
        :errorText="formErrors.premi?'failed':''" @input="v=>values.premi=v" :hints="formErrors.premi" label="Premi"
        placeholder="Tuliskan Premi" :check="false" /> -->
    </div>

    <!-- Tagihan Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tagihan"
        :errorText="formErrors.tagihan?'failed':''" @input="v=>values.tagihan=v" :hints="formErrors.tagihan"
        label="Tagihan" placeholder="Masukan Tagihan" :check="false" />
    </div>

    <!-- Premi Coloumn -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.premi"
        :errorText="formErrors.premi?'failed':''" @input="v=>values.premi=v"
        :hints="formErrors.premi" label="Premi" placeholder="Masukkan Premi" :check="false"
        type="textarea" />
    </div>

    <!-- Status Column -->


    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>
  <!-- Status -->
  <div class="flex flex-col gap-2 p-4">
    <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer font-semibold"
            for="is_active_for_click"
            >Status :</label>
    <div class="flex w-40">
      <div class="flex-auto">
        <i class="text-red-500">InActive</i>
      </div>
      <div class="flex-auto">
        <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                role="switch"
                id="is_active_for_click"
                :disabled="!actionText"
                v-model="values.is_active"
                />
      </div>
      <div class="flex-auto">
        <i class="text-green-500">Active</i>
      </div>
    </div>
  </div>
  <hr>
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