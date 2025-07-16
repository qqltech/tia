<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
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
      <!-- <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink> -->
      <button class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white text-sm rounded py-1 px-2.5
      transition-colors duration-300" @click="isModalOpen=true">Create New</button>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px]">
    <!-- <template #header>
    </template> -->
  </TableApi>
</div>
<div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
  <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Pilih Tipe BKM</h2>
      <hr>
    </div>
    <div class="p-1 ">
      <div class=" flex justify-center">
        <button @click="setTipe('BKM')"
          class="hover:bg-blue-100 text-blue-500 font-semibold w-50 h-50 mx-5 text-center rounded-lg"
          :class="tipe === 'BKM' ? 'bg-blue-200': 'bg-blue-50'">
          <div class="h-40 w-full flex items-center justify-center">
            <icon fa="box-open" class="text-8xl" />
          </div>
          <div
            class="bg-blue-500 hover:bg-blue-600 text-white text-2xl font-semibold h-10 w-full rounded-b-lg flex items-center justify-center">
            <p>BKM</p>
          </div>
        </button>
        <button @click="setTipe('BKM Non Order')"
          class="hover:bg-blue-100 text-blue-500 font-semibold w-50 h-50 mx-5 text-center rounded-lg"
          :class="tipe === 'BKM Non Order' ? 'bg-blue-200': 'bg-blue-50'"
          >
          <div class="h-40 w-full flex items-center justify-center">
            <icon fa="boxes-stacked" class="text-8xl" />
          </div>
          <div
            class="bg-blue-500 hover:bg-blue-600 text-white text-2xl font-semibold h-10 w-full rounded-b-lg flex items-center justify-center">
            <p>BKM Non Order</p>
          </div>
        </button>
      </div>
    </div>
    <div class="flex justify-end pt-4">
      <RouterLink :to="`${$route.path}/create?${Date.parse(new Date())}&tipe=${tipe}`"
        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 mr-4">
        Create</RouterLink>
      <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600" @click="isModalOpen=false; setTipe('')">Cancel</button>
    </div>
  </div>
</div>
@else

<!-- CONTENT -->
@verbatim
<div v-if="values.tipe=='BKM'" class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">BKM</h1>
        <p class="text-gray-100">Transaksi BKM</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no" :errorText="formErrors.no?'failed':''"
        @input="v=>values.no=v" :hints="formErrors.no" label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no" :errorText="formErrors.no?'failed':''"
        @input="v=>values.no=v" :hints="formErrors.no" label="No. BKM" placeholder="No. BKM" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tanggal"
        :errorText="formErrors.tanggal?'failed':''" :hints="formErrors.tanggal" :check="false" type="date"
        label="Tgl BKM" placeholder="Pilih Tgl BKM" />
    </div>
    <div>
      <FieldPopup label="No. Buku Order" class="w-full !mt-3" valueField="id" displayField="shortname"
        :value="values.t_po_id" @input="(v)=>values.t_po_id=v" :api="{
              url: `${store.server.url_backend}/operation/t_po`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                selectfield: 'id,name,code,shortname',
                searchfield: 'this.code, this.name,this.shortname'
              }
            }" placeholder="Pilih No. Buku Order" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'code',
              headerName:  'Kode',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'shortname',
              headerName:  'Nama Pendek',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'name',
              headerName:  'Nama Item',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
    </div>
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3" :value="values.tipe_id"
        @input="v=>{
            if(v){
              values.tipe_id=v
            }else{
              values.tipe_id=null
            }
          }" :errorText="formErrors.tipe_id?'failed':''" :hints="formErrors.tipe_id" valueField="id"
        displayField="name" :api="{
              url: `${store.server.url_backend}/operation/m_tipe`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:'this.is_active=true'
              }
          }" placeholder="Pilih Tipe BKM" label="Tipe BKM" :check="true" />
    </div>
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3" :value="values.tipe_id"
        @input="v=>{
            if(v){
              values.tipe_id=v
            }else{
              values.tipe_id=null
            }
          }" :errorText="formErrors.tipe_id?'failed':''" :hints="formErrors.tipe_id" valueField="id"
        displayField="name" :api="{
              url: `${store.server.url_backend}/operation/m_tipe`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:'this.is_active=true'
              }
          }" placeholder="Pilih Akun Pembayaran" label="Akun Pembayaran" :check="true" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="values.supplier"
        :errorText="formErrors.supplier?'failed':''" @input="v=>values.supplier=v" :hints="formErrors.supplier"
        label="Total Amt" placeholder="Total Amt" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" type="textarea" :value="values.note"
        :errorText="formErrors.note?'failed':''" @input="v=>values.note=v" :hints="formErrors.note" label="Catatan"
        placeholder="Catatan" :check="false" />
    </div>
    <div>
      <FieldSelect :bind="{ disabled: true, clearable:true }" class="w-full !mt-3" :value="values.status_id" @input="v=>{
            if(v){
              values.status_id=v
            }else{
              values.status_id=null
            }
          }" :errorText="formErrors.status_id?'failed':''" :hints="formErrors.status_id" valueField="id"
        displayField="value1" :api="{
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
          }" placeholder="Status" label="Status" :check="false" />
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
              Kode AKun
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nama Akun
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nominal
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Detail
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
              <FieldX :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0" :value="item.kode"
                @input="v=>item.kode=v" :errorText="formErrors.kode?'failed':''" :hints="formErrors.kode" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0" :value="item.nama"
                @input="v=>item.nama=v" :errorText="formErrors.nama?'failed':''" :hints="formErrors.nama" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.nominal" @input="v=>item.nominal=v" :errorText="formErrors.nominal?'failed':''"
                :hints="formErrors.nominal" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0" :value="item.note"
                @input="v=>item.note=v" :errorText="formErrors.note?'failed':''" :hints="formErrors.note"
                type="textarea" />
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
<div v-else-if="values.tipe=='BKM Non Order'"
  class="fl ex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">BKM Non Order</h1>
        <p class="text-gray-100">Transaksi BKM Non Order</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no" :errorText="formErrors.no?'failed':''"
        @input="v=>values.no=v" :hints="formErrors.no" label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no" :errorText="formErrors.no?'failed':''"
        @input="v=>values.no=v" :hints="formErrors.no" label="No. BKM" placeholder="No. BKM" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ disabled: true, readonly: true }" class="w-full !mt-3 pointer-events-none"
        :value="values.tanggal" :errorText="formErrors.tanggal?'failed':''" :hints="formErrors.tanggal" :check="false"
        type="date" label="Tgl BKM" placeholder="Pilih Tgl BKM" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_bkm_non_order"
        :errorText="formErrors.no_bkm_non_order?'failed':''" @input="v=>values.no_bkm_non_order=v"
        :hints="formErrors.no_bkm_non_order" label="BKM (Non Buku Order)" placeholder="BKM (Non Buku Order)"
        :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_reference"
        :errorText="formErrors.no_reference?'failed':''" @input="v=>values.no_reference=v"
        :hints="formErrors.no_reference" label="No. Reference" placeholder="No. Reference" :check="false" />
    </div>
    <div>
      <FieldSelect class="fa fa-angle-down" :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
        :value="values.tipe_id" @input="v=>{
            if(v){
              values.tipe_id=v
            }else{
              values.tipe_id=null
            }
          }" :errorText="formErrors.tipe_id?'failed':''" :hints="formErrors.tipe_id" valueField="id"
        displayField="name" :api="{
              url: `${store.server.url_backend}/operation/m_tipe`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:'this.is_active=true'
              }
          }" placeholder="Pilih Akun Pembayaran" fa-icon="sort-desc" label="Akun Pembayaran" :check="true" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="values.total_amt"
        :errorText="formErrors.total_amt?'failed':''" @input="v=>values.total_amt=v" :hints="formErrors.total_amt"
        label="Total Amt" placeholder="Total Amt" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" type="textarea" :value="values.keterangan"
        :errorText="formErrors.keterangan?'failed':''" @input="v=>values.keterangan=v" :hints="formErrors.keterangan"
        label="Keterangan" placeholder="Keterangan" :check="false" />
    </div>
    <div>
      <FieldSelect class="fa fa-angle-down" :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
        :value="values.tipe_id" @input="v=>{
            if(v){
              values.tipe_id=v
            }else{
              values.tipe_id=null
            }
          }" :errorText="formErrors.tipe_id?'failed':''" :hints="formErrors.tipe_id" valueField="id"
        displayField="name" :api="{
              url: `${store.server.url_backend}/operation/m_tipe`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:'this.is_active=true'
              }
          }" placeholder="Pilih Tipe Buku Order" fa-icon="sort-desc" label="Tipe Buku Order" :check="true" />
    </div>
    <div>
      <FieldSelect :bind="{ disabled: true, clearable:true }" class="w-full !mt-3" :value="values.status_id" @input="v=>{
            if(v){
              values.status_id=v
            }else{
              values.status_id=null
            }
          }" :errorText="formErrors.status_id?'failed':''" :hints="formErrors.status_id" valueField="id"
        displayField="value1" :api="{
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
          }" placeholder="Status" label="Status" :check="false" />
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
              Kode Akun
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nama Akun
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nominal
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Detail
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
              <FieldX :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0" :value="item.kode"
                @input="v=>item.kode=v" :errorText="formErrors.kode?'failed':''" :hints="formErrors.kode" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0" :value="item.nama"
                @input="v=>item.nama=v" :errorText="formErrors.nama?'failed':''" :hints="formErrors.nama" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.nominal" @input="v=>item.nominal=v" :errorText="formErrors.nominal?'failed':''"
                :hints="formErrors.nominal" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0" :value="item.note"
                @input="v=>item.note=v" :errorText="formErrors.note?'failed':''" :hints="formErrors.note"
                type="textarea" />
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