<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-2.5 pt-2 pb-2">
    <h1 class="text-xl font-semibold">LPB</h1>
  </div>
  <div class="flex justify-between items-center px-2.5 py-1">
    <!-- FILTER -->
        <!-- FILTER -->
        <div class="flex items-center gap-x-2">
            <p>Filter Status :</p>
            <div class="flex gap-x-2">
                <button @click="filterShowData('DRAFT')" :class="activeBtn?.toUpperCase() === 'DRAFT'?'bg-gray-600 font-semibold !text-white hover:bg-gray-400':'border border-gray-600 text-gray-600 bg-white  hover:bg-gray-600 hover:text-white'" class="duration-300 transform transition hover:-translate-y-0.5 rounded-md py-1 px-2">DRAFT</button>
                <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
                <button @click="filterShowData('POST')" :class="activeBtn?.toUpperCase() === 'POST'?'bg-amber-600 !text-white hover:bg-amber-400':'border border-amber-600 font-semibold bg-white text-amber-600  hover:bg-amber-600 hover:text-white'" class="duration-300 transition transform hover:-translate-y-0.5 rounded-md py-1 px-2">POST</button>
                <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
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
          <h1 class="text-20px font-bold">LPB</h1>
          <p class="text-gray-100">Transaksi LPB</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.no_lpb" :errorText="formErrors.no_lpb?'failed':''"
          @input="v=>values.no_lpb=v" :hints="formErrors.no_lpb" 
          label="No. LPB"
          placeholder="No. LPB"
          :check="false"
        />
      </div>
      <div>
        <FieldPopup 
            :bind="{ readonly: !actionText }"
            label="No. PO"
            class="w-full !mt-3"
            valueField="id" displayField="no_po"
            :value="values.t_po_id" @input="(v)=>values.t_po_id=v"
            @update:valueFull="(v)=>{
              detailArr = []
              if(v){
                values.m_supplier_id=v['m_supplier_id']
              }else{
                values.m_supplier_id=null
              }   
            }"
            :api="{
              url: `${store.server.url_backend}/operation/t_purchase_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where: `this.status='APPROVED'`,
                scopes:'CheckDouble',
                //selectfield: 'id,tanggal,no_po,m_supplier.nama,m_supplier.alamat,m_supplier.kota',
                searchfield: 'this.tanggal, this.no_po,m_supplier.nama,m_supplier.alamat,m_supplier.kota'
              }
            }"
            placeholder="Pilih No. PO"
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
              field: 'no_po',
              headerName:  'No. PO',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'tanggal',
              headerName:  'Tanggal',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'm_supplier.nama',
              headerName:  'Supplier',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'm_supplier.alamat',
              headerName:  'Alamat Supplier',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'm_supplier.kota',
              headerName:  'Kota Supplier',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]"
          />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" class="w-full !mt-3"
          :value="values.tanggal_lpb" :errorText="formErrors.tanggal_lpb?'failed':''"
          @input="v=>values.tanggal_lpb=v"  :hints="formErrors.tanggal_lpb" 
          :check="false"
          type="date"
          label="Tanggal LPB"
          placeholder="Pilih Tanggal LPB"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" class="w-full !mt-3"
          :value="values.no_sj_supplier" :errorText="formErrors.no_sj_supplier?'failed':''"
          @input="v=>values.no_sj_supplier=v" :hints="formErrors.no_sj_supplier" 
          label="No. SJ Supplier"
          placeholder="No. SJ Supplier"
          :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" class="w-full !mt-3"
          :value="values.tanggal_sj_supplier" :errorText="formErrors.tanggal_sj_supplier?'failed':''"
          @input="v=>values.tanggal_sj_supplier=v"  :hints="formErrors.tanggal_sj_supplier" 
          :check="false"
          type="date"
          label="Tanggal SJ Supplier"
          placeholder="Pilih Tanggal SJ Supplier"
        />
      </div>
      <div>
         <FieldSelect
          :bind="{ disabled: true, clearable:true }" class="w-full !mt-3"
          :value="values.m_supplier_id"  @input="v=>{
            if(v){
              values.m_supplier_id=v
            }else{
              values.m_supplier_id=null
            }
          }"
          :errorText="formErrors.m_supplier_id?'failed':''" 
          :hints="formErrors.m_supplier_id"
          valueField="id" displayField="nama"
          :api="{
              url: `${store.server.url_backend}/operation/m_supplier`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                //where:`this.is_active=true and this.group='TIPE KONTAINER'`

              }
          }"
          placeholder="Supplier" label="Supplier" :check="true"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          type="textarea"
          :value="values.catatan" :errorText="formErrors.catatan?'failed':''"
          @input="v=>values.catatan=v" :hints="formErrors.catatan" 
          label="Catatan"
          placeholder="Catatan"
          :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.status" :errorText="formErrors.status?'failed':''"
          @input="v=>values.status=v" :hints="formErrors.status" 
          :check="false"
          label="Status"
          placeholder="Status"
        />
      </div>
      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
    </div>
    
    <!-- detail -->
    <div class="p-4">
      <button v-show="actionText"
          title="Add Detail"
          @click="addAllDetail"
          >
            <div class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
              <icon fa="plus" size="sm mr-0.5"/> Add All Detail
            </div>
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
                Kode Item
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Nama Item
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Qty PO
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Qty
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Harga
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                UoM
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Total Harga
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Catatan
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Bundling?
              </td>
              <td v-show="actionText"
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
                <FieldX :bind="{ readonly: true, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.kode" @input="v=>item.kode=v"
                  :errorText="formErrors.kode?'failed':''" :hints="formErrors.kode" :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{ readonly: true, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.nama" @input="v=>item.nama=v"
                  :errorText="formErrors.nama?'failed':''" :hints="formErrors.nama" :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: true, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.quantity" @input="v=>item.quantity=v"
                  :errorText="formErrors.quantity?'failed':''" :hints="formErrors.quantity" :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: item.is_bundling === true ? false : true, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.qty" @input="v=>item.qty=v"
                  :errorText="formErrors.qty?'failed':''" :hints="formErrors.qty" :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: item.is_bundling === true ? false : true, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.harga" @input="v=>item.harga=v"
                  :errorText="formErrors.harga?'failed':''" :hints="formErrors.harga" :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full py-2 !mt-0" :value="item.uom"
                  @input="v=>{
                      if(v){
                        item.uom=v
                      }else{
                        item.uom=null
                      }
                    }" :errorText="formErrors.uom?'failed':''" :hints="formErrors.uom" valueField="deskripsi"
                  displayField="deskripsi" :api="{
                        url: `${store.server.url_backend}/operation/m_general`,
                        headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                        params: {
                          simplest:true,
                          transform:false,
                          join:false,
                          where:`this.is_active=true and this.group='UOM'`

                        }
                    }" placeholder="Pilih UoM" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: item.is_bundling === true ? false : true, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.qty * item.harga"
                  :errorText="formErrors.harga?'failed':''" :hints="formErrors.harga" :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText, disabled: !actionText, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.catatan" @input="v=>item.catatan=v"
                  :errorText="formErrors.catatan?'failed':''" :hints="formErrors.catatan" type="textarea" :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{ readonly: true, clearable:false }"
                  class="w-full py-2 !mt-0" :value="item.bundling" @input="v=>item.bundling=v"
                  :errorText="formErrors.bundling?'failed':''" :hints="formErrors.bundling"
                  :check="false"
                />
              </td>
              <td v-show="actionText" class="p-2 border border-[#CACACA]">
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

      <hr v-show="actionText">

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