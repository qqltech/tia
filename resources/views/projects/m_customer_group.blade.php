<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">CUSTOMER GROUP</h1>
  </div>
  <div class="flex justify-between items-center px-4 py-1">

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
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali" @click="onBack"/>
        <div>
          <h1 class="text-20px font-bold">Form Customer Group</h1>
          <p class="text-gray-100">Master Customer Group</p>
        </div>
      </div>
    </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row gap-x-4 gap-y-4 mb-5 p-4">
        <div class="w-full !mt-3">
        <FieldX 
          class="!mt-0"
          :bind="{ readonly: true }" 
          :value="values.kode" :errorText="formErrors.kode?'failed':''"
          @input="v=>values.kode=v" 
          :hints="formErrors.kode" 
          placeholder="Kode" 
          :check="false"
        />
        </div>
        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.nama" :errorText="formErrors.nama?'failed':''"
            @input="v=>values.nama=v" 
            :hints="formErrors.nama" 
            placeholder="Nama" 
            :check="false"
          />
        </div>
        <div class="w-full !mt-3">
          <FieldNumber
            class="!mt-0"
            :bind="{ readonly: !actionText }"
            :value="values.kredit_limit" @input="(v)=>values.kredit_limit=v"
            :errorText="formErrors.kredit_limit?'failed':''" 
            :hints="formErrors.kredit_limit"
            placeholder="Kredit Limit"
            :check="false"
          />
        </div>
        <div class="w-full !mt-3">
          <FieldNumber
            class="!mt-0"
            :bind="{ readonly: true }"
            :value="sisaKredit" @input="(v)=>values.sisa_kredit=v"
            :errorText="formErrors.sisa_kredit?'failed':''" 
            :hints="formErrors.sisa_kredit"
            placeholder="Sisa Kredit" 
            :check="false"
          />
        </div>
        <div class="w-full !mt-3">
          <FieldNumber
            class="!mt-0"
            :bind="{ readonly: true }"
            :value="totalKreditTerpakai" @input="(v)=>values.total_kredit=v"
            :errorText="formErrors.total_kredit?'failed':''" 
            :hints="formErrors.total_kredit"
            placeholder="Total Kredit Limit Terpakai" 
            :check="false"
          />
        </div>
        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.catatan" :errorText="formErrors.catatan?'failed':''"
            @input="v=>values.catatan=v" 
            :hints="formErrors.catatan" 
            type="textarea"
            placeholder="Catatan" :check="false"
          />
        </div>
        <div class="w-full flex flex-col gap-2 pt-2 ml-1">
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
        
      
      <!-- START TABLE DETAIL -->
      <hr class="<md:col-span-1 col-span-3">
      <div class="<md:col-span-1 col-span-3 pl-4 pr-4">
        <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3 mt-4">
          <table class="w-full overflow-x-auto table-auto border border-[#CACACA] pt-4">
            <thead>
              <tr class="border">
                <td class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No</td>
                <td class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Kode Customer</td>
                <td class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Nama Customer</td>
                <td class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Kredit Limit Terpakai</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in detailArr" :key="item.id" class="border-t">
                <td class="p-2 text-center border border-[#CACACA]">{{ index + 1 }}</td>
                <td class="p-2 text-center border border-[#CACACA]">{{ item.kode }}</td>
                <td class="p-2 text-center border border-[#CACACA]">{{ item.nama_perusahaan }}</td>
                <td class="p-2 text-center border border-[#CACACA]">{{ item.coa_piutang}}</td>
              </tr>
              <tr v-if="detailArr.length === 0" class="text-center">
                <td colspan="5" class="py-[20px]">No data to show</td>
              </tr>
            </tbody>
          </table>
        </div>
      <!-- END TABLE DETAIL -->

      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
      <hr>
      <div class="flex flex-row items-center justify-end space-x-2 p-2">
        <i v-show="actionText" class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
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