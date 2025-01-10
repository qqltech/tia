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
    <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
      <div class="flex items-center">
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali" @click="onBack"/>
        <div>
          <h1 class="text-20px font-bold">Form Jurnal Angkutan</h1>
          <p class="text-gray-100">Jurnal Angkutan Header</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
      
      <!-- No. Draft Coloumn -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3 w-1/2"
          :value="values.no_draft" :errorText="formErrors.no_draft?'failed':''"
          @input="v=>values.no_draft=v" :hints="formErrors.no_draft" 
          label="No. Draft"
          placeholder="No. Draft"
          :check="false"
        />
      </div>


      <!-- Date Coloumn -->
      <div>
      <FieldX :bind="{ disabled: true, readonly: true }" class="w-full !mt-3"
          :value="values.tgl"
          :errorText="formErrors.tgl?'failed':''" 
          @input="updateDate" :hints="formErrors.tgl" :check="false"
          label="Tanggal" placeholder="Pilih Tanggal" />
      </div>

      <!-- No. Jurnal Angkutan Coloumn -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.no_jurnal" :errorText="formErrors.no_jurnal?'failed':''"
          @input="v=>values.no_jurnal=v" :hints="formErrors.no_jurnal" 
          label="No. Jurnal Angkutan"
          placeholder="No. Jurnal Angkutan"
          :check="false"
        />
      </div>

      <!-- Nama Supplier Coloumn -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.nama_supplier" :errorText="formErrors.nama_supplier?'failed':''"
          @input="v=>values.nama_supplier=v" :hints="formErrors.nama_supplier" 
          label="Nama Supplier"
          placeholder="Nama Supplier"
          :check="false"
        />
      </div>

      <!-- Kode Supplier Coloumn -->
      <div class="flex">
        <FieldPopup class="!mt-3 w-full" :api="{
          url: `${store.server.url_backend}/operation/m_supplier`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            searchfield: 'this.kode , this.nama , this.is_active'
            },
          }" displayField="kode" valueField="id" :bind="{ readonly: !actionText }" :value="values.m_supplier_id"
          @input="v=>{
            $log(v)
            if(v){
              values.m_supplier_id=v
            }else{
              values.nama_supplier=null;
              values.m_supplier_id=null;
            }
          }"
          @update:valueFull="supplier" :errorText="formErrors.m_supplier_id?'failed':''" class="w-full !mt-3" :hints="formErrors.m_supplier_id"
          placeholder="Pilih Kode Supplier" label="Kode Supplier" :check='false' :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'kode',
              headerName: 'KODE',
              sortable: false, resizable: true, filter: false,
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'nama',
              headerName: 'NAMA SUPPLIER',
              cellClass: ['justify-center', 'border-r', '!border-gray-200',],
              sortable: false, resizable: true, filter: false,
            },
            {
              flex: 1,
              field: 'is_active',
              headerName: 'STATUS',
              cellClass: ['justify-center', 'border-r', '!border-gray-200',],
              sortable: false, resizable: true, filter: false,
              valueFormatter: (params) => {
              return params.value ? 'Aktif' : 'Nonaktif';
              }
            },
            ]" />
        <span class="text-red-500"> * </span>
      </div>
      
      <!-- No. Nota Piutang Coloumn -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.no_nota_piutang" :errorText="formErrors.no_nota_piutang?'failed':''"
          @input="v=>values.no_nota_piutang=v" :hints="formErrors.no_nota_piutang" 
          label="No. Nota Piutang"
          placeholder="No. Nota Piutang"
          :check="false"
        />
      </div>

      <!-- Catatan Coloumn -->
      <div>
        <FieldX :bind="{ readonly: false }" class="w-full !mt-3"
          :value="values.catatan" :errorText="formErrors.catatan?'failed':''"
          @input="v=>values.catatan=v" :hints="formErrors.catatan" 
          label="Catatan"
          placeholder="Catatan"
          :check="false"
          type="textarea"
        />
      </div>

      <!-- Status  -->
      <div class="flex flex-col gap-2 p-4">
      <label class="text-gray-600 text-xs font-semibold">Status</label>
      <div class="flex gap-2">
        <div class="relative">
          <input class="relative h-[16px] w-7 p-px appearance-none rounded-full bg-white border disabled:!cursor-default
            hover:cursor-pointer after:content-[''] after:h-[10.5px] after:w-[10.5px] after:rounded-full after:border-none
            after:absolute after:mt-[0.9px] focus:outline-none after:ml-[0.95px] checked:after:right-[1.25px] disabled:opacity-75
            after:bg-red-600 border-red-600 checked:after:bg-green-600 checked:border-green-600"
            type="checkbox" role="switch" :disabled="!actionText" v-model="values.is_active">
        </div>
        <div :class="(values.is_active ? 'text-green-600' : 'text-red-600') + ' text-xs'">
          {{values.is_active ? 'Active' : 'InActive' }}
        </div>
      </div>
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
                Kode
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Nama Supplier
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Sektor
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Tipe Kontainer
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Jenis Kontainer
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] w-[5%]">
                Ukuran Kontainer
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] w-[5%]">
                Nominal
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
                  class="w-full py-2 !mt-0" :value="item.kode_supplier" @input="v=>item.kode_supplier=v"
                  :errorText="formErrors.kode_supplier?'failed':''" :hints="formErrors.kode_supplier" valueField="id"
                  displayField="deskripsi" :api="{          
                      url: `${store.server.url_backend}/operation/m_supplier`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      
                  }" placeholder="Kode Supplier" label="" :check="false"
                />
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
                  }" placeholder="Pilih Nama Sopir" label="" :check="false"
                />
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
                  }" placeholder="Pilih No. Casis" label="" :check="false"
                />
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
                  }" placeholder="Pilih Rit (Kode Jalan)" label="" :check="false"
                />
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
                        where : `this.key1='OVERHEAD' AND this.is_active='true'`,
                      }
                  }" placeholder="Pilih Rit" label="" :check="false"
                />
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
                        where : `this.key1='OVERHEAD' AND this.is_active='true'`,
                      }
                  }" placeholder="Pilih Rit" label="" :check="false"
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

      <div class="w-full flex justify-center">
    <div class="w-md">
      
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">DPP :</label>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.total_amount" :errorText="formErrors.total_amount?'failed':''"
          @input="v=>values.total_amount=v" :hints="formErrors.total_amount" 
          label="Total Amount"
          placeholder="Total Amount"
          :check="false"
        />
      </div>
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">Total PPN :</label>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.total_amount" :errorText="formErrors.total_amount?'failed':''"
          @input="v=>values.total_amount=v" :hints="formErrors.total_amount" 
          label="Total Amount"
          placeholder="Total Amount"
          :check="false"
        />
      </div>
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-3">
        <label class="!mt-4 !ml-3">Grand Total :</label>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.total_amount" :errorText="formErrors.total_amount?'failed':''"
          @input="v=>values.total_amount=v" :hints="formErrors.total_amount" 
          label="Total Amount"
          placeholder="Total Amount"
          :check="false"
        />
      </div>
    </div>
  </div>

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