@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="p-3">
    <h1 class="text-xl font-semibold">Menu</h1>
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
        <h1 class="text-20px font-bold">Form Menu</h1>
        <p class="text-gray-100">Untuk mengatur informasi menu pada sistem</p>
      </div>
    </div>
  </div>
  <!-- HEADER END -->

  <!-- FORM START -->
  <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row p-4 gap-3">
    <div class=" w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.modul"
        :errorText="formErrors.modul?'failed':''" @input="v=>values.modul=v" :hints="formErrors.modul" placeholder="Modul"
        :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.submodul"
        :errorText="formErrors.submodul?'failed':''" @input="v=>values.submodul=v" :hints="formErrors.submodul"
        placeholder="Sub Modul" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.menu"
        :errorText="formErrors.menu?'failed':''" @input="v=>values.menu=v" :hints="formErrors.menu"
        placeholder="Nama Menu" :check="false" />
    </div>
    <div class="w-full !mt-3">
          <FieldX
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.path" :errorText="formErrors.path?'failed':''"
            @input="v=>values.path=v"
            :hints="formErrors.path" 
            placeholder="Masukkan Path /m_menu" label="Path" :check="false"
          />
    </div>
    <div class="w-full !mt-3">
          <FieldX
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.endpoint" :errorText="formErrors.endpoint?'failed':''"
            @input="v=>values.endpoint=v"
            :hints="formErrors.endpoint" 
            placeholder="Masukkan Endpoint /m_menu" label="Endpoint" :check="false"
          />
    </div>
    <div class="w-full !mt-3">
          <FieldX
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.icon" :errorText="formErrors.icon?'failed':''"
            @input="v=>values.icon=v"
            :hints="formErrors.icon" 
            placeholder="Bookmark" :check="false"
          />
    </div>
    <div class="w-full !mt-3">
          <FieldX
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.sequence" :errorText="formErrors.sequence?'failed':''"
            @input="v=>values.sequence=v"
            :hints="formErrors.sequence" 
            placeholder="Sequence" :check="false"
          />
    </div>
        <div class="w-full !mt-3">
          <FieldX
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.description" :errorText="formErrors.description?'failed':''"
            @input="v=>values.description=v"
            :hints="formErrors.description" 
            type="textarea" placeholder="Masukkan Deskripsi" label="Deskripsi" :check="false"
          />
    </div>
    <div></div>
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