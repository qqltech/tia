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
          <h1 class="text-20px font-bold">Asset Disposal</h1>
          <p class="text-gray-100">Transaksi Asset Disposal</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.no" :errorText="formErrors.no?'failed':''"
          @input="v=>values.no=v" :hints="formErrors.no" 
          label="No. Draft"
          placeholder="No. Draft"
          :check="false"
        />
      </div>
      <div>
         <FieldSelect
          :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
          :value="values.tipe_id"  @input="v=>{
            if(v){
              values.tipe_id=v
            }else{
              values.tipe_id=null
            }
          }"
          :errorText="formErrors.tipe_id?'failed':''" 
          :hints="formErrors.tipe_id"
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
          placeholder="Pilih Tipe" label="Tipe" :check="true"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.no" :errorText="formErrors.no?'failed':''"
          @input="v=>values.no=v" :hints="formErrors.no" 
          label="No. Disposal"
          placeholder="No. Disposal"
          :check="false"
        />
      </div>
      <div>
        <FieldPopup 
            label="Customer"
            class="w-full !mt-3"
            valueField="id" displayField="shortname"
            :value="values.t_po_id" @input="(v)=>values.t_po_id=v"
            :api="{
              url: `${store.server.url_backend}/operation/t_po`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                selectfield: 'id,name,code,shortname',
                searchfield: 'this.code, this.name,this.shortname'
              }
            }"
            placeholder="Pilih Customer"
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
            ]"
          />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.date" :errorText="formErrors.date?'failed':''"
          @input="v=>checkTglEdDate(v)"  :hints="formErrors.date" 
          :check="false"
          type="date"
          label="Tanggal"
          placeholder="Pilih Tanggal"
        />
      </div>
      <div>
        <FieldPopup 
            label="No. Asset"
            class="w-full !mt-3"
            valueField="id" displayField="shortname"
            :value="values.t_po_id" @input="(v)=>values.t_po_id=v"
            :api="{
              url: `${store.server.url_backend}/operation/t_po`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                selectfield: 'id,name,code,shortname',
                searchfield: 'this.code, this.name,this.shortname'
              }
            }"
            placeholder="Pilih No. Asset"
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
            ]"
          />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.no_sj" :errorText="formErrors.no_sj?'failed':''"
          @input="v=>values.no_sj=v" :hints="formErrors.no_sj" 
          label="Jatuh Tempo"
          placeholder="Jatuh Tempo"
          :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="Tipe Asset"
          placeholder="Tipe Asset"
          :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="Deskripsi Asset"
          placeholder="Deskripsi Asset"
          :check="false"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="Harga Perolehan"
          placeholder="Harga Perolehan"
          :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.date" :errorText="formErrors.date?'failed':''"
          @input="v=>checkTglEdDate(v)"  :hints="formErrors.date" 
          :check="false"
          type="date"
          label="Tanggal Asset"
          placeholder="Pilih Tanggal Asset"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="Sisa Penyusutan"
          placeholder="Sisa Penyusutan"
          :check="false"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="Nilai Buku"
          placeholder="Nilai Buku"
          :check="false"
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
          placeholder="Pilih Perkiraan Disposal" label="Perkiraan Disposal" :check="false"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="Nilai Akumulasi Penyusutan"
          placeholder="Nilai Akumulasi Penyusutan"
          :check="false"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="Nilai Jual"
          placeholder="Nilai Jual"
          :check="false"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="Perbedaan Nilai Asset"
          placeholder="Perbedaan Nilai Asset"
          :check="false"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="PPN (%)"
          placeholder="PPN (%)"
          :check="false"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="PPN (Nominal)"
          placeholder="PPN (Nominal)"
          :check="false"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="No. Faktur Pajak"
          placeholder="No. Faktur Pajak"
          :check="false"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="Harga Perolehan"
          placeholder="Harga Perolehan"
          :check="false"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="Total PPN"
          placeholder="Total PPN"
          :check="false"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.supplier" :errorText="formErrors.supplier?'failed':''"
          @input="v=>values.supplier=v" :hints="formErrors.supplier" 
          label="Sisa Penyusutan"
          placeholder="Sisa Penyusutan"
          :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          type="textarea"
          :value="values.note" :errorText="formErrors.note?'failed':''"
          @input="v=>values.note=v" :hints="formErrors.note" 
          label="Catatan"
          placeholder="Catatan"
          :check="false"
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
                Periode
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Penyusutan
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Nilai Buku
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Akumulasi
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Status
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
                <FieldX :bind="{ disabled: !actionText, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.periode" @input="v=>item.periode=v"
                  :errorText="formErrors.periode?'failed':''" :hints="formErrors.periode"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ disabled: !actionText, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.penyusutan" @input="v=>item.penyusutan=v"
                  :errorText="formErrors.penyusutan?'failed':''" :hints="formErrors.penyusutan"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ disabled: !actionText, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.nilai_buku" @input="v=>item.nilai_buku=v"
                  :errorText="formErrors.nilai_buku?'failed':''" :hints="formErrors.nilai_buku"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ disabled: !actionText, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.akumulasi" @input="v=>item.akumulasi=v"
                  :errorText="formErrors.akumulasi?'failed':''" :hints="formErrors.akumulasi"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ disabled: !actionText, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.status" @input="v=>item.status=v"
                  :errorText="formErrors.status?'failed':''" :hints="formErrors.status"
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