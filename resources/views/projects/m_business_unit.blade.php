<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2">
    <h1 class="text-xl font-semibold">BUSINESS UNIT</h1>
  </div>
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true)" :class="filterButton === true ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          Active
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData(false)" :class="filterButton === false ? 'bg-red-600 text-white hover:bg-red-600' 
          : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          InActive
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
  <TableApi ref='apiTable' :api="table.api" :columns="table.columns" :actions="table.actions"
    class="max-h-[500px] pt-2 !px-4 !pb-8">
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
        <h1 class="text-lg font-bold leading-none">Form Business Unit</h1>
        <p class="text-gray-100 leading-none">Atur berbagai menu untuk apps</p>
      </div>
    </div>
  </div>

  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 sticky">
    <div class="relative">
      <label v-show="data.kode === '' || !data.kode" class="absolute top-2 left-1 left text-gray-600 text-xs font-semibold" >Kode</label>
      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.kode" :errorText="formErrors.kode?'failed':''"
        @input="v=>data.kode=v" :hints="formErrors.kode" label="Kode" placeholder='Auto Generate by System'
        :check="false" />
    </div>
    <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.nama" :errorText="formErrors.nama?'failed':''"
      @input="v=>data.nama=v" :hints="formErrors.nama" placeholder="Nama" :check="false" />
    <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.npwp" :errorText="formErrors.npwp?'failed':''"
      @input="v=>data.npwp=v" :hints="formErrors.npwp" placeholder="NPWP" :check="false" type="number" />
    <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.alamat"
      :errorText="formErrors.alamat?'failed':''" @input="v=>data.alamat=v" :hints="formErrors.alamat"
      placeholder="Alamat" :check="false" />

    <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="pt-1" :value="data.provinsi"
      @input="v=>data.provinsi=v" :errorText="formErrors.provinsi?'failed':''" :hints="formErrors.provinsi"
      valueField="key" displayField="key" :api="{
                url: 'https://backend.qqltech.com/kodepos/region/provinsi',
                onsuccess:function(responseJson){
                  return { data: responseJson }
                }
            }" @update:valueFull="() => {
            data.kota = null
            data.kecamatan = null
          }" placeholder="Provinsi" :check="false" fa-icon="sort-desc" />

    <FieldSelect :bind="{ disabled: (!data.provinsi) || !actionText, clearable:false }" class="pt-1" :value="data.kota"
      @input="v=>data.kota=v" :errorText="formErrors.kota?'failed':''" :hints="formErrors.kota" valueField="key"
      displayField="key" :api="{
                url: 'https://backend.qqltech.com/kodepos/region/kota',
                params:{
                  provinsi: data.provinsi
                },
                onsuccess:function(responseJson){
                  return { data: responseJson }
                }
            }" @update:valueFull="() => {
            data.kecamatan = null
          }" placeholder="Kota / Kabupaten" :check="false" fa-icon="sort-desc" />

    <FieldSelect :bind="{ disabled: (!data.kota) || !actionText, clearable:false }" class="pt-1" :value="data.kecamatan"
      @input="v=>data.kecamatan=v" :errorText="formErrors.kecamatan?'failed':''" :hints="formErrors.kecamatan"
      valueField="key" displayField="key" :api="{
                url: 'https://backend.qqltech.com/kodepos/region/kecamatan',
                params:{
                  kota: data.kota
                },
                onsuccess:function(responseJson){
                  return { data: responseJson }
                }
            }" placeholder="Kecamatan" :check="false" fa-icon="sort-desc" />

    <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.kodepos"
      :errorText="formErrors.kodepos?'failed':''" @input="v=>data.kodepos=v" :hints="formErrors.kodepos"
      placeholder="Kode Pos" :check="false" type="number" />
    <div class="relative">
      <div class="top-0 left-0 absolute w-full">
        <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.catatan"
          :errorText="formErrors.catatan?'failed':''" @input="v=>data.catatan=v" :hints="formErrors.catatan"
          placeholder="Catatan" :check="false" type="textarea" />
      </div>
    </div>

    <!-- CHECKBOX COMPONENT -->
    <div class="flex flex-col gap-2 pt-2 ml-1">
      <label class="text-gray-600 text-xs font-semibold">Status</label>
      <div class="flex gap-2">
        <div class="relative">
          <input class="relative h-[16px] w-7 p-px appearance-none rounded-full bg-white border disabled:!cursor-default
            hover:cursor-pointer after:content-[''] after:h-[10.5px] after:w-[10.5px] after:rounded-full after:border-none
            after:absolute after:mt-[0.9px] focus:outline-none after:ml-[0.95px] checked:after:right-[1.25px] disabled:opacity-75
            after:bg-red-600 border-red-600 checked:after:bg-green-600 checked:border-green-600"
            type="checkbox" role="switch" :disabled="!actionText" v-model="data.is_active" />
        </div>
        <div :class="(data.is_active ? 'text-green-600' : 'text-red-600') + ' text-xs'">
          {{data.is_active ? 'Active' : 'InActive' }}
        </div>
      </div>
    </div>
    <!-- END CHECKBOX COMPONENT -->
  </div>

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
</div>
@endverbatim
@endif