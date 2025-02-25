@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="p-2">
    <h1 class="text-xl font-semibold">GENERAL</h1>
  </div>
  <div class="flex justify-between items-center px-2.5 py-1">

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
        <h1 class="text-20px font-bold">Form General</h1>
        <p class="text-gray-100">Untuk mengatur informasi general pada sistem</p>
      </div>
    </div>
  </div>
  <!-- HEADER END -->

  <!-- FORM START -->
  <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row p-4 gap-3">
    <div class=" w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.kode"
        :errorText="formErrors.kode?'failed':''" @input="v=>values.kode=v" :hints="formErrors.kode" placeholder="Kode"
        :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.group"
        :errorText="formErrors.group?'failed':''" @input="v=>values.group=v" :hints="formErrors.group"
        placeholder="Group" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.deskripsi"
        :errorText="formErrors.deskripsi?'failed':''" @input="v=>values.deskripsi=v" :hints="formErrors.deskripsi"
        type="textarea" placeholder="Deskripsi" :check="false" />
    </div>
    <div class="w-full !mt-3">
          <FieldX
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.deskripsi2" :errorText="formErrors.deskripsi2?'failed':''"
            @input="v=>values.deskripsi2=v"
            :hints="formErrors.deskripsi2" 
            type="textarea"
            placeholder="Deskripsi 2" :check="false"
          />
    </div>
    <div class="w-full !mt-3">
          <FieldX
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.deskripsi3" :errorText="formErrors.deskripsi3?'failed':''"
            @input="v=>values.deskripsi3=v"
            :hints="formErrors.deskripsi3" 
            type="textarea"
            placeholder="Deskripsi 3" :check="false"
          />
    </div>
    <div class="w-full !mt-3">
          <FieldX
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.deskripsi4" :errorText="formErrors.deskripsi4?'failed':''"
            @input="v=>values.deskripsi4=v"
            :hints="formErrors.deskripsi4" 
            type="textarea"
            placeholder="Deskripsi 4" :check="false"
          />
    </div>
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


  </div>
  <!-- FORM END -->
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