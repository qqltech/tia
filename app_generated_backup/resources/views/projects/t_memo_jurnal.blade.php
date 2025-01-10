<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-gray-500">
  <div class="flex justify-between items-center px-2.5 py-1">
    <div class="flex items-center gap-x-4">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true,1)" :class="activeBtn === 1?'bg-green-600 text-white hover:bg-green-400':'border border-green-600 text-green-600 bg-white  hover:bg-green-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Active</button>
        <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
        <button @click="filterShowData(false,2)" :class="activeBtn === 2?'bg-red-600 text-white hover:bg-red-400':'border border-red-600 text-red-600 bg-white  hover:bg-red-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Inactive</button>
      </div>
    </div>
    <div>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))" class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions" class="max-h-[450px]">
    <!-- <template #header>
    </template> -->
  </TableApi>
</div>
@else

<!-- CONTENT -->
@verbatim
  <div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
    <div class="bg-gray-500 text-white rounded-t-md py-2 px-4">
      <div class="flex items-center">
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali" @click="onBack"/>
        <div>
          <h1 class="text-20px font-bold">Form Memo Jurnal</h1>
          <p class="text-gray-100">Transaksi Memo Jurnal</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-2 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.no" :errorText="formErrors.no?'failed':''"
          @input="v=>values.no=v" :hints="formErrors.no" 
          label="No. Memo"
          placeholder="No. Memo"
          :check="false"
        />
      </div>
      <div>
         <FieldSelect
          :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
          :value="values.divisi_id"  @input="v=>{
            if(v){
              values.divisi_id=v
            }else{
              values.divisi_id=null
            }
          }"
          :errorText="formErrors.divisi_id?'failed':''" 
          :hints="formErrors.divisi_id"
          valueField="id" displayField="name"
          :api="{
              url: `${store.server.url_backend}/operation/m_tipe`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:'this.is_active=true'
              }
          }"
          placeholder="Pilih Divisi" label="Divisi" :check="true"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.end_date" :errorText="formErrors.end_date?'failed':''"
          @input="v=>checkTglEdDate(v)"  :hints="formErrors.end_date" 
          :check="false"
          type="date"
          label="Tanggal"
          placeholder="Pilih Tanggal"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.catatan" :errorText="formErrors.catatan?'failed':''"
          @input="v=>values.catatan=v" :hints="formErrors.catatan" 
          :check="false"
          label="Catatan"
          type="textarea"
          placeholder="Catatan"
        />
      </div>
      <div>  
        <FieldSelect
          :bind="{ disabled: true, clearable:true }" class="w-full !mt-3"
          :value="values.status_id"  @input="v=>{
            if(v){
              values.status_id=v
            }else{
              values.status_id=null
            }
          }"
          :errorText="formErrors.status_id?'failed':''" 
          :hints="formErrors.status_id"
          valueField="id" displayField="value1"
          :api="{
              url: `${store.server.url_backend}/operation/m_gen`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                scopes:'filterByGroup',
                where:'this.is_active=true',
                group:'STATUS TRANSAKSI'
              }
          }"
          placeholder="Status" label="Status" :check="false"
        />
      </div>
      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
    </div>

    <!-- detail -->
    <div class="p-4">
      <button v-show="actionText" @click="addDetail" type="button" class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
          <icon fa="plus" />
          Add to List
        </button>

      <div class="mt-4">
        <table class="w-full overflow-x-auto table-auto border border-[#CACACA]">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                No.
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Perkiraan
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Debet
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Credit
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Catatan
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] w-[5%]">
                Action
              </td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length > 0">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: !actionText, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.cost_type_id" @input="v=>item.cost_type_id=v"
                  :errorText="formErrors.cost_type_id?'failed':''" :hints="formErrors.cost_type_id" valueField="id"
                  displayField="value1" :api="{          
                      url: `${store.server.url_backend}/operation/m_gen`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        simplest:true,
                        scopes: 'filterByGroup',
                        group: 'acc cost type',
                        where : `this.key1='DIRECTLABOR' AND this.is_active='true'`,
                      }
                  }" placeholder="Pilih Jenis Angkutan" label="" :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
                  :value="values.Stapel" :errorText="formErrors.Stapel?'failed':''"
                  @input="v=>values.Stapel=v" :hints="formErrors.Stapel" 
                  :check="false"
                  label="Angka Keluar"
                  placeholder="Angka Keluar"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
                  :value="values.Stapel" :errorText="formErrors.Stapel?'failed':''"
                  @input="v=>values.Stapel=v" :hints="formErrors.Stapel" 
                  :check="false"
                  label="Debet"
                  placeholder="Debet"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
                  :value="values.Stapel" :errorText="formErrors.Stapel?'failed':''"
                  @input="v=>values.Stapel=v" :hints="formErrors.Stapel" 
                  :check="false"
                  label="Credit"
                  placeholder="Credit"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div class="flex justify-center">
                  <button type="button" @click="removeDetail(i)" :disabled="!actionText" title="Hapus">
                      <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                      </svg>
                    </button>
                </div>

              </td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="7" class="py-[20px]">
                No data to show
              </td>
            </tr>
          </tbody>
        </table>
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