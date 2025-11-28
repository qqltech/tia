<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-gray-500">
  <div class="pl-2.5 pt-2 pb-2">
    <h1 class="text-xl font-semibold">ITEM</h1>
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
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali" @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Item</h1>
        <p class="text-gray-100">Master Item</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
        :value="values.kode" :errorText="formErrors.kode?'failed':''"
        @input="v=>values.kode=v" :hints="formErrors.kode"
        label="Kode"
        placeholder="Auto Generate Kode Item"
        :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full !mt-3"
        :value="values.tanggal" :errorText="formErrors.tanggal?'failed':''"
        @input="v=>values.tanggal=v" :hints="formErrors.tanggal"
        :check="false"
        type="date"
        label="Tanggal"
        placeholder="Pilih Tanggal" />
    </div>
    <div>
      <FieldSelect
        :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
        :value="values.tipe_item" @input="v=>{
            if(v){
              values.tipe_item=v
            }else{
              values.tipe_item=null
            }
          }"
        :errorText="formErrors.tipe_item?'failed':''"
        :hints="formErrors.tipe_item"
        valueField="deskripsi" displayField="deskripsi"
        :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:`this.is_active=true and this.group='TIPE BARANG'`

              }
          }"
        placeholder="Pilih Tipe Item" label="Tipe Item" :check="true" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
        :value="values.nama_item" :errorText="formErrors.nama_item?'failed':''"
        @input="v=>values.nama_item=v" :hints="formErrors.nama_item"
        :check="false"
        type="textarea"
        label="Nama Item"
        placeholder="Nama Item" />
    </div>
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
        :value="values.uom_id" @input="v=>{
            if(v){
              values.uom_id=v
            }else{
              values.uom_id=null
            }
          }" :errorText="formErrors.uom_id?'failed':''" :hints="formErrors.uom_id"
        valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:`this.is_active=true and this.group='UOM'`

              }
          }" placeholder="Pilih UOM" label="UOM" :check="true" />
    </div>
    <!-- <div>
      <FieldX :bind="{ disabled: true, readonly: true }" class="w-full !mt-3"
      :value="!values.is_bundling
        ? (computedQuantity !== undefined ? computedQuantity.toString() : '0')
        : (values.qty_stock.toString())"
        :check="false"
        label="Quantity"
        placeholder="Quantity" />
    </div> -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
        :value="values.is_bundling" :errorText="formErrors.is_bundling?'failed':''"
        @input="v=>values.is_bundling=v" :hints="formErrors.is_bundling"
        label="Quantity"
        placeholder="Quantity"
        :check="false" />
    </div>
    <div>
      <FieldSelect
        :bind="{ disabled: !actionText, clearable:false }" class="w-full !mt-3"
        :value="values.is_active" @input="v=>values.is_active=v"
        :errorText="formErrors.is_active?'failed':''"
        :hints="formErrors.is_active"
        valueField="id" displayField="key"
        :options="[{'id' : 1 , 'key' : 'Active'},{'id': 0, 'key' : 'InActive'}]"
        placeholder="Pilih Status" label="Status" :check="false" />
    </div>
    <div>
      <label for="b2b_for_click" class="block mb-1 text-sm font-medium text-gray-700">
        Is Bundling
      </label>
      <div class="flex w-40 items-center space-x-2">
        <div class="flex-auto">
          <i class="text-red-500">TIDAK</i>
        </div>
        <div class="flex-auto">
          <input
            id="b2b_for_click"
            type="checkbox"
            role="switch"
            class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
            :disabled="!actionText"
            v-model="values.is_bundling"
            @input="changeIsBundling" />
        </div>
        <div class="flex-auto">
          <i class="text-green-500">IYA</i>
        </div>
      </div>
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>

  <!-- detail -->
  <div class="p-4" v-if="!values.is_bundling">

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
              NO LPB
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Catatan
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Used
            </td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true, clearable:false }"
                class="w-full py-2 !mt-0" :value="item.no_lpb" @input="v=>item.no_lpb=v"
                :errorText="formErrors.no_lpb?'failed':''" :hints="formErrors.no_lpb" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true, clearable:false }"
                class="w-full py-2 !mt-0" :value="item.catatan" @input="v=>item.catatan=v"
                :errorText="formErrors.catatan?'failed':''" :hints="formErrors.catatan" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true, clearable:false }"
                class="w-full py-2 !mt-0" :value="item.used ? 'Yes' : 'No'" @input="v=>item.used=v"
                :errorText="formErrors.used?'failed':''" :hints="formErrors.used" :check="false" />
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
      @click="onReset(true)">
      <icon fa="times" />
      Reset
    </button>
    <button
      class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
      v-show="actionText"
      @click="onSave">
      <icon fa="save" />
      Simpan
    </button>
  </div>
</div>
@endverbatim
@endif