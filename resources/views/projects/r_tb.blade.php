@php
$data = \DB::select("SELECT * FROM r_gl ORDER BY date DESC");
$grand_debet = 0;
$grand_credit = 0;
@endphp
@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm <md:w-full w-full bg-white">
      <div class="bg-gray-500 text-white rounded-t-md py-2 px-4">
        <div class="flex items-center">
          <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
            @click="onBack" />
          <div>
            <h1 class="text-20px font-bold mb-4 mt-4">Laporan Trial Balance</h1>
          </div>
        </div>
      </div>
      <hr>
      <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
        <!-- START COLUMN -->
        <div>
          <FieldSelect :bind="{ readonly: !actionText, clearable: false }" class="w-full !mt-3"
            :value="values.tipe_report" :errorText="formErrors.tipe_report ? 'failed' : ''"
            @input="v => values.tipe_report = v" :hints="formErrors.tipe_report" :check="false" label="Tipe Laporan"
            :options="['Trial Balance', 'Laba Rugi', 'Neraca']" placeholder="Pilih Tipe Laporan" valueField="key"
            displayField="key" />
        </div>
        <div class="grid grid-cols-1 gap-1">
          <div class="w-full" style="margin-top:-14px">
            <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
            <input
              type="month"
              v-model="values.selected_month"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
              :class="{ 'border-red-500': formErrors.selected_month }"
              placeholder="Pilih Periode"
              @click="$refs.monthPicker?.showPicker()"
              ref="monthPicker">
            <div v-if="formErrors.selected_month" class="text-xs text-red-500 mt-1">{{ formErrors.selected_month }}
            </div>
          </div>
        </div>
        <!-- <div>
            <FieldSelect
              :bind="{ disabled: false, clearable: true, multiple: true }"
              class="w-full !mt-3"
              :value="values.warehouse_ids"
              @input="v => {
                if (Array.isArray(v) && v.length > 0) {
                  values.warehouse_ids = v;
                } else {
                  values.warehouse_ids = null;
                }
              }"
              :errorText="formErrors.warehouse_ids ? 'failed' : ''" 
              :hints="formErrors.warehouse_ids"
              valueField="id" 
              displayField="value1"
              :api="{
                url: `${store.server.url_backend}/operation/m_gen`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  where : `this.group='GUDANG'`
                }
              }"
              placeholder="Pilih Gudang" 
              label="Gudang" 
              :check="false"
            />
          </div> -->
        <!-- <div>
            <FieldSelect
              :bind="{ disabled: false, clearable: true, multiple: true }" class="w-full !mt-3"
              :value="values.type_id" 
              @input="v => {
                if (Array.isArray(v) && v.length > 0) {
                  values.type_id = v;
                } else {
                  values.type_id = null;
                }
              }"
              :errorText="formErrors.type_id ? 'failed' : ''" 
              :hints="formErrors.type_id"
              valueField="id" displayField="value1"
              :api="{
                url: `${store.server.url_backend}/operation/m_gen`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest: true,
                  transform: false,
                  join: false,
                  where: `this.is_active=true AND this.group='TIPE ITEM'`,
                }
              }"
              placeholder="Pilih Tipe Item" label="Tipe Item" :check="false"
            />
          </div> -->
        <!-- <div>
            <FieldSelect
              :api="{
                  url:  `${store.server.url_backend}/operation/m_supp`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest:true,
                    //where: `this.type_id=${values.type_id??'this.type_id'}`,
                    searchfield: 'this.name',
                  }
                }"

              displayField="name"
              valueField="id"
              :bind="{ readonly: false }"
              :value="values.m_supp_id" @input="(v)=>values.m_supp_id=v"
              @update:valueFull="(v)=>{ 
              }"
              :errorText="formErrors.m_supp_id?'failed':''"  class="w-full !mt-3"
              :hints="formErrors.m_supp_id" placeholder="Pilih Supplier" label="Supplier"
              :check="false" 
              :columns="[{
                  headerName: 'No',
                  valueGetter:(p)=>p.node.rowIndex + 1,
                  width: 60,
                  sortable: false, resizable: false, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                  flex: 1,
                  headerName: 'Nama Supplier',
                  field: 'name',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['border-r', '!border-gray-200', 'justify-start']
                },
              ]"/>      
          </div> -->
        <!-- <div>
            <FieldSelect
              :options="['TERKIRIM', 'BELUM TERKIRIM', 'SUDAH TAGIHAN']"
              :bind="{ readonly: false, clearable:false }"
              :value="values.status" @input="(v)=>values.status=v"
              @update:valueFull="(v)=>{ 
              }"
              :errorText="formErrors.status?'failed':''"  class="w-full !mt-3"
              :hints="formErrors.status" placeholder="Pilih Status" label="Status"
              :check="false" />      
          </div> -->
        <!-- <div>
            <FieldSelect
              :bind="{ disabled: false, clearable: true, multiple: true }" 
              class="w-full !mt-3"
              :value="values.kat_item_ids"
              @input="v => {
                if (Array.isArray(v) && v.length > 0) {
                  values.kat_item_ids = v;
                } else {
                  values.kat_item_ids = null;
                }
              }"
              :errorText="formErrors.kat_item_ids ? 'failed' : ''" 
              :hints="formErrors.kat_item_ids"
              valueField="id" 
              displayField="name"
              :api="{
                url: `${store.server.url_backend}/operation/m_cat`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  where: `this.is_active='true'`
                }
              }"
              placeholder="Pilih Kategori" 
              label="Kategori" 
              :check="false"
            />
          </div>
          <div>
            <FieldPopup
              :api="{
                  url:  `${store.server.url_backend}/operation/m_item`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest:true,
                    where: `this.type_id=${values.type_id??'this.type_id'}`,
                    searchfield: 'this.name_short,this.name_long,this.code,m_cat1.name,type.value1',
                  }
                }"

              displayField="name_short"
              valueField="id"
              :bind="{ readonly: false }"
              :value="values.m_item_id" @input="(v)=>values.m_item_id=v"
              @update:valueFull="(v)=>{ 
              }"
              :errorText="formErrors.m_item_id?'failed':''"  class="w-full !mt-3"
              :hints="formErrors.m_item_id" placeholder="Pilih Item" label="Item"
              :check="false" 
              :columns="[{
                  headerName: 'No',
                  valueGetter:(p)=>p.node.rowIndex + 1,
                  width: 60,
                  sortable: false, resizable: false, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                  flex: 1,
                  headerName: 'Kode',
                  field: 'code',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['border-r', '!border-gray-200', 'justify-start']
                },
                {
                  flex: 1,
                  headerName: 'Nama Pendek',
                  field: 'name_short',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['border-r', '!border-gray-200', 'justify-start']
                },
                {
                  flex: 1,
                  headerName: 'Nama Panjang',
                  field: 'name_long',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['border-r', '!border-gray-200', 'justify-start']
                },
                {
                  flex: 1,
                  headerName: 'Tipe Item',
                  field: 'type.value1',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['border-r', '!border-gray-200', 'justify-start']
                },
                {
                  flex: 1,
                  headerName: 'Group',
                  field: 'm_cat1.name',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['border-r', '!border-gray-200', 'justify-start']
                }
              ]"/>      
          </div> -->
        <div>
          <FieldSelect :bind="{ readonly: !actionText, clearable: false }" class="w-full !mt-3" :value="values.tipe"
            :errorText="formErrors.tipe ? 'failed' : ''" @input="v => values.tipe = v" :hints="formErrors.tipe"
            :check="false" label="Tipe Export" :options="['Excel','PDF','HTML']" placeholder="Pilih Tipe Export"
            valueField="key" displayField="key" />
        </div>

      </div>

      <div class="flex flex-row items-center justify-end space-x-2 p-2">
        <button
          class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"

          @click="onGenerate">
          Lihat Laporan
        </button>
      </div>
      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
      <div class="overflow-x-auto my-4 px-4 w-[100%]" v-show="exportHtml">
        <hr>
        <div id="exportTable" class="w-full mt-6">
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endverbatim