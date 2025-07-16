@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px] border-t-10 border-blue-500 ">
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
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions" class="max-h-[450px] pt-2 !px-4 !pb-8">
    <template #header>
      <div class="pb-13 h-full"></div>
    </template>
  </TableApi>
</div>

</div>
@else

@verbatim
<div class="flex flex-col gap-y-3 rounded-t-md bg-white">
      <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
      <div class="flex items-center">
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-blue-300" title="Kembali" @click="onBack"/>
        <div>
          <h1 class="text-20px font-bold">Form Menu</h1>
          <p class="text-gray-100">Untuk mengatur Menu pada Sidebar</p>
        </div>
      </div>
    </div>
  <div class="flex gap-x-4 px-2 p-4">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">

      <div class="grid <md:grid-cols-1 grid-cols-3 gap-2">
        <!-- START COLUMN -->
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.modul" :errorText="formErrors.modul?'failed':''"
              @input="v=>values.modul=v" :hints="formErrors.modul" 
              :check="false"
              label="Modul"
              placeholder="Modul"
          />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.submodul" :errorText="formErrors.submodul?'failed':''"
              @input="v=>values.submodul=v" :hints="formErrors.submodul" 
              :check="false"
              label="submodul"
              placeholder="Submodul"
          />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.menu" :errorText="formErrors.menu?'failed':''"
              @input="v=>values.menu=v" :hints="formErrors.menu" 
              :check="false"
              label="Nama Menu"
              placeholder="Nama Menu"
          />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.path" :errorText="formErrors.path?'failed':''"
              @input="v=>values.path=v" :hints="formErrors.path" 
              :check="false"
              label="Path"
              placeholder="Masukan Path /m_menu"
          />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.endpoint" :errorText="formErrors.endpoint?'failed':''"
              @input="v=>values.endpoint=v" :hints="formErrors.endpoint" 
              :check="false"
              label="Endpoint"
              placeholder="Masukan Endpoint /m_menu"
          />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.icon" :errorText="formErrors.icon?'failed':''"
              @input="v=>values.icon=v" :hints="formErrors.icon" 
              :check="false"
              label="icon"
              placeholder="bookmark"
          />
        </div>
        <div>
          <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.sequence" :errorText="formErrors.sequence?'failed':''"
              @input="v=>values.sequence=v" :hints="formErrors.sequence" 
              :check="false"
            label="Sequence"
              placeholder="Sequence"
          />
        </div>
        <div>

          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.description" :errorText="formErrors.description?'failed':''"
              @input="v=>values.description=v" :hints="formErrors.description" 
              :check="false"
              type="textarea"
              label="Deskripsi"
              placeholder="Masukan Deskripsi"
          />
        </div>
        <div> </div>
        <div class="w-full flex flex-col col-span-3 gap-2 pt-2 ml-1">
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
              <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
          <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Kembali
          </button>
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Simpan
          </button>
        </div>
    </div>
  </div>
</div>
@endverbatim
@endif