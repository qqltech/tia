<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">NO. PPJK</h1>
  </div>
  <div class="flex justify-between items-center px-4 py-1">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('IMPORT')" :class="filterButton === 'IMPORT' ? 'bg-green-600 text-white hover:bg-green-600' 
        : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'" class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
          IMPORT
        </button>
        <div class="flex my-auto h-4 w-px bg-[#6E91D1]"></div>
        <button @click="filterShowData('EXPORT')" :class="filterButton === 'EXPORT' ? 'bg-red-600 text-white hover:bg-red-600' 
        : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'" class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
          EXPORT
        </button>
        <div class="flex my-auto h-4 w-px bg-[#6E91D1]"></div>
        <button @click="filterShowData('LAIN-LAIN')" :class="filterButton === 'LAIN-LAIN' ? 'bg-amber-600 text-white hover:bg-amber-600' 
        : 'border border-amber-600 text-amber-600 bg-white hover:bg-amber-600 hover:text-white'" class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
          LAIN-LAIN
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
          <h1 class="text-20px font-bold">Form Nomor PPJK</h1>
          <p class="text-gray-100">Nomor PPJK Header</p>
        </div>
      </div>
    </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row gap-x-4 gap-y-4 mb-5 p-4">
        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0" 
            :bind="{ readonly: !actionText }" 
            :value="data.periode" :errorText="formErrors.periode?'failed':''" 
            @input="v=>{
              data.tahun = v.split('-')[0];
              data.bulan = v.split('-')[1];
              data.periode=v
              }
              " 
            :hints="formErrors.periode" 
            placeholder="Periode PPJK" 
            :check="false" 
            type="month" 
          />
        </div>
        <div class="w-full !mt-3 pointer-events-none">
          <FieldX 
            class="!mt-0" 
            :bind="{ readonly: true}" 
            :value="data.tgl_pembuatan" :errorText="formErrors.tgl_pembuatan?'failed':''" 
            @input="v=>data.tgl_pembuatan=v" 
            :hints="formErrors.tgl_pembuatan" 
            placeholder="Tanggal Pembuatan" 
            :check="false" 
            type="date" 
          />
        </div>
        <div class="w-full !mt-3">
          <FieldSelect
            class="!mt-0"
            :bind="{ disabled: !actionText, clearable:true }"
            :value="data.tipe" @input="v=>data.tipe=v"
            :errorText="formErrors.tipe?'failed':''" 
            :hints="formErrors.tipe"
            valueField="deskripsi" displayField="deskripsi"
            @update:valueFull="v=>{
              data.kode=v.deskripsi2
            }"
            :api="{
                url: `${store.server.url_backend}/operation/m_general`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  where: `this.group = 'TIPE PPJK'`, 
                  selectfield: `this.id, this.deskripsi, this.deskripsi2`
                }
            }"
            placeholder="Pilih Tipe PPJK" :check="true"
          />
        </div>
        <div class="w-full !mt-3">
          <FieldX class="!mt-0" :bind="{ readonly: data.tipe !== 'LAIN-LAIN' }" 
            :value="data.kode" :errorText="formErrors.kode?'failed':''"
            @input="v=>data.kode=v" :hints="formErrors.kode" 
            placeholder="Prefix" :check="false"
          />
        </div>
        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0"
            :bind="{ readonly: is_generate || !actionText }" 
            :value="data.no_awal" :errorText="formErrors.no_awal?'failed':''"
            @input="v=>data.no_awal=v" :hints="formErrors.no_awal" 
            label="No Awal"
            placeholder="Masukkan No Awal" 
            :check="false" type="number"
          />
        </div>
        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0"
            :bind="{ readonly: is_generate || !actionText }" 
            :value="data.no_akhir" :errorText="formErrors.no_akhir?'failed':''"
            @input="v=>data.no_akhir=v" 
            :hints="formErrors.no_akhir" 
            label="No Akhir"
            placeholder="Masukkan No Akhir" 
            :check="false" type="number"
          />
        </div>
      </div>
     
        
      
      <!-- START TABLE DETAIL -->
      <hr class="<md:col-span-1 col-span-3">
      <div class="<md:col-span-1 col-span-3 pl-4 pr-4">
      <div class="col-span-3 mt-3">
      <button :disabled="is_generate"
        class="bg-blue-600 text-xs text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5
        disabled:hover:translate-y-0 disabled:opacity-75 disabled:hover:bg-blue-600" 
        size="sm mr-0.5"
        v-show="actionText" 
        @click="generateDetail(true)" 
      >Generate Nomor PPJK
      </button>
      </div>
      <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
        <table class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
          <thead>
            <tr class="border">
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No.</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">No PPJK</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Referensi</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Status</td>
              <!-- <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">Action</td> -->
            </tr>
          </thead>
          <tbody>
            <tr v-if="detailArr.length === 0" class="text-center">
              <td colspan="6" class="py-[20px]">No data to show</td>
            </tr>
            <tr v-else v-for="(item, index) in detailArr" :key="index" class="border">
              <td class="p-2 text-center border border-[#CACACA]">{{ index + 1 }}</td>
              <td class="p-2 text-left justify-start border border-[#CACACA]">{{item.no_aju}}</td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX 
                  :bind="{ readonly: true }" 
                  :value="item.referensi" :errorText="formErrors.referensi?'failed':''"
                  @input="v=>item.referensi=v" :hints="formErrors.referensi" 
                  label=""
                  placeholder="Masukkan Referensi" 
                  :check="false"
                />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldSelect 
                  :bind="{ disabled: !actionText || item.referensi !== null, clearable: false}" 
                  :value="item.is_active" :errorText="formErrors.is_active?'failed':''"
                  @input="v=>item.is_active=v" :hints="formErrors.is_active" 
                  valueField="id" displayField="key"
                  :options="[{'id' : 1 , 'key' : 'OPEN'},{'id': 0, 'key' : 'CLOSE'}]"
                  label=""
                  placeholder="Status" :check="true"
                />
              </td>
              <!-- <td class="p-2 border border-[#CACACA] text-center">
                <button type="button" @click="removeDetail(i)" :disabled="!actionText">
                  <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                  </svg>
                </button>
              </td> -->
            </tr>
          </tbody>
        </table>
      </div>

      <!-- END TABLE DETAIL -->

      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
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