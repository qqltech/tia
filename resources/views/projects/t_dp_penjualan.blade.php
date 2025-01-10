<!-- LANDING -->
@if(!$req->has('id'))

@verbatim
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex flex-col justify-center w-full px-2.5 py-1">
    <div class="flex justify-between items-center gap-2">
      <div class="flex gap-2 pb-3">
        <p class="py-2">Filter Status :</p>
        <div class="flex items-center gap-2">
          <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-gray-600 text-white hover:bg-gray-600' 
          : 'border border-gray-600 text-gray-600 bg-white hover:bg-gray-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          DRAFT
        </button>
          <div class="h-4 w-px bg-gray-300"></div>
          <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-amber-600 text-white hover:bg-amber-600' 
          : 'border border-amber-600 text-amber-600 bg-white hover:bg-amber-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          POST
          </button>
          <div class="h-4 w-px bg-gray-300"></div>
          <!-- <button @click="filterShowData('PRINTED')" :class="filterButton === 'PRINTED' ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          PRINTED
        </button> -->
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

  @endverbatim
  @else

  <!-- CONTENT -->
  @verbatim
  <div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
    <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
      <div class="flex items-center">
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
        <div>
          <h1 class="text-20px font-bold">Form Down Payment Penjualan</h1>
          <p class="text-gray-100">Header Down Payment Penjualan</p>
        </div>
      </div>
    </div>
    <!-- HEADER END -->

    <!-- FORM START -->
    <div class="grid <md:grid-cols-1 grid-cols-2 grid-flow-row p-4 gap-3">
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.no_draft"
          :errorText="formErrors.no_draft?'failed':''" @input="v=>values.no_draft=v" :hints="formErrors.no_draft"
          label="No.Draft" placeholder="No Draft" :check="false" />
      </div>
      
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.no_dp"
          :errorText="formErrors.no_dp?'failed':''" @input="v=>values.no_dp=v"
          :hints="formErrors.no_dp" placeholder="No. DP Penjualan" :check="false" />
      </div>
      
      <div class="w-full !mt-3">
        <FieldPopup class="!mt-0" :bind="{ readonly: !actionText }" :value="values.t_buku_order_id" @input="v=>{
          if(v){
            values.t_buku_order_id=v
          }else{
            values.t_buku_order_id=null
          }
          
        }" :errorText="formErrors.t_buku_order_id?'failed':''" :hints="formErrors.t_buku_order_id" valueField="id" displayField="no_buku_order" 
        @update:valueFull= "(data)=>{
          values.m_customer_id = data['m_customer.id']
        }"
        :api="{
              url: `${store.server.url_backend}/operation/t_buku_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                //where: `this.status='POST'`
              }
            }" placeholder="No Order" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'no_buku_order',
              headerName:  'No Order',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'm_customer.nama_perusahaan',
              headerName:  'Nama Customer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            }
            ]" />
      </div>
      <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tgl_dp"
        :errorText="formErrors.tgl_dp?'failed':''" @input="v=>values.tgl_dp=v" :hints="formErrors.tgl_dp" :check="false"
        type="date" label="Tanggal DP" placeholder="Pilih Tanggal" />
      </div>
      <div>
      
      <FieldSelect class=" w-full !mt-3" :bind="{ disabled: !actionText }" :value="values.tipe_dp_id"
        @input="v=>values.tipe_dp_id=v" :errorText="formErrors.tipe_dp_id?'failed':''" :hints="formErrors.tipe_dp_id"
        valueField="id" displayField="deskripsi" :api="{
                url: `${store.server.url_backend}/operation/m_general`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  where : `this.group='TIPE PEMBAYARAN' and this.is_active = 'true'`
                }
            }" fa-icon="caret-down" label="" placeholder="Pilih Tipe DP" :check="false" />
      </div>

      <div>
      <FieldNumber class=" w-full !mt-3" :bind="{ readonly: !actionText }" :value="values.total_amount"
          @input="(v)=>values.total_amount=v" :errorText="formErrors.total_amount?'failed':''" :hints="formErrors.total_amount" label="Tarif DP" placeholder="Masukkan Tarif DP"
          :check="false" />
      </div>
      
      
      <div class="w-full !mt-3">
        <FieldSelect class="!mt-0" :bind="{ disabled: true, clearable:true  }" :value="values.status"
          :errorText="formErrors.status ? 'failed' : ''" @input="v => values.status = v" :hints="formErrors.status"
          valueField="id" displayField="key" :options="[
              { 'id': 'DRAFT', 'key': 'DRAFT' },
              { 'id': 'POST', 'key': 'POST' },
              //{ 'id': 'PRINTED', 'key': 'PRINTED' },
            ]" label="Status" placeholder="Status" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.catatan"
          :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
          type="textarea" placeholder="Catatan" :check="false" />
      </div>

      <div class="w-full !mt-3 visibility: hidden">\
        <FieldNumber
        class="!mt-0"
          :bind="{ readonly: !actionText }"
          :value="values.m_customer_id" @input="(v)=>values.m_customer_id=v"
          :errorText="formErrors.m_customer_id?'failed':''" 
          :hints="formErrors.m_customer_id"
          placeholder="Customer"  :check="false"
        />
        
        <!-- <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.catatan"
          :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
          type="textarea" placeholder="Customer" :check="false" /> -->
      </div>
    </div>
    <!-- FORM END -->
    <hr>
    <div class="flex flex-row items-center justify-end space-x-2 p-2" v-show="actionText || actionEditBerkas">
      <i class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
      <button
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        @click="onReset(true)"
      >
        <icon fa="times" />
        Reset
      </button>
      <button class="text-sm rounded-md py-2 px-3 text-white bg-yellow-600 hover:bg-yellow-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="onSave(true)">
            <icon fa="paper-plane" />
            <span>Post</span>
      </button>
      <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        @click="onSave(false)"
      >
        <icon fa="save" />
        Simpan
      </button>
    </div>
  </div>

  @endverbatim
  @endif