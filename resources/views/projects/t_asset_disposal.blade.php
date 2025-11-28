<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-2.5 pt-2 pb-2">
    <h1 class="text-xl font-semibold">Asset Disposal</h1>
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
          :value="values.no_draft" :errorText="formErrors.no_draft?'failed':''"
          @input="v=>values.no_draft=v" :hints="formErrors.no_draft" 
          label="No. Draft" 
          placeholder="No. Draft"
          :check="false"
        />
      </div>
      <div>
         <FieldSelect
          :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
          :value="values.tipe_disposal"  @input="v=>{
            if(v){
              values.tipe_disposal=v
            }else{
              values.tipe_disposal=null
            }
          }"
          :errorText="formErrors.tipe_disposal?'failed':''" 
          :hints="formErrors.tipe_disposal"
          valueField="name" displayField="name"
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
         <FieldSelect
          :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
          :value="values.category"  @input="v=>{
            if(v){
              values.category=v
            }else{
              values.category=null
            }
          }"
          :errorText="formErrors.category?'failed':''" 
          :hints="formErrors.category"
          valueField="name" displayField="name"
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
          placeholder="Pilih Category" label="Category" :check="true"
        />
      </div>
      <div>
         <FieldSelect
          :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
          :value="values.coa_disposal"  @input="v=>{
            if(v){
              values.coa_disposal=v
            }else{
              values.coa_disposal=null
            }
          }"
          :errorText="formErrors.coa_disposal?'failed':''" 
          :hints="formErrors.coa_disposal"
          valueField="name" displayField="name"
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
          placeholder="Pilih Coa Disposal" label=" Coa Disposal" :check="true"
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
            :errorText="formErrors.m_customer_id?'failed':''"
            :hints="formErrors.m_customer_id"
            :value="values.m_customer_id" @input="(v)=>values.m_customer_id=v"
            :api="{
              url: `${store.server.url_backend}/operation/m_cust`,
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
          :value="values.tanggal" :errorText="formErrors.tanggal?'failed':''"
          @input="v=>checkTglEdDate(v)"  :hints="formErrors.tanggal" 
          :check="false"
          type="date"
          label="Tanggal"
          placeholder="Pilih Tanggal"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.tanggal_disposal" :errorText="formErrors.tanggal_disposal?'failed':''"
          @input="v=>checkTglEdDate(v)"  :hints="formErrors.tanggal_disposal" 
          :check="false"
          type="date"
          label="Tanggal Disposal"
          placeholder="Pilih Tanggal Disposal"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.jatuh_tempo" :errorText="formErrors.jatuh_tempo?'failed':''"
          @input="v=>checkTglEdDate(v)"  :hints="formErrors.jatuh_tempo" 
          :check="false"
          type="date"
          label="Tanggal Jatuh Tempo"
          placeholder="Pilih Tanggal Jatuh Tempo"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.catatan_asset" :errorText="formErrors.catatan_asset?'failed':''"
          @input="v=>checkTglEdDate(v)"  :hints="formErrors.catatan_asset" 
          :check="false"
          type="textarea"
          label="Catatan Asset"
          placeholder="Catatan Asset"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.total_dpp" :errorText="formErrors.total_dpp?'failed':''"
          @input="v=>checkTglEdDate(v)"  :hints="formErrors.total_dpp" 
          :check="false"
          label="Total dpp"
          placeholder="Total dpp"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.total_pajak" :errorText="formErrors.total_pajak?'failed':''"
          @input="v=>checkTglEdDate(v)"  :hints="formErrors.total_pajak" 
          :check="false"
          label="Total Pajak"
          placeholder="Total Pajak"
        />
      </div>
      <div>
        <FieldUpload
          :value="values.berkas_acara" @input="(v)=>values.berkas_acara=v" :maxSize="10"
          :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]"
          :api="{
            url: 'endpoint',
            headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { field: 'berkas_acara' },
            onsuccess: response=>response,
            onerror:(error)=>{},
           }"
           :hints="formErrors.berkas_acara" placeholder="Berkas Acara" label="Berkas Acara" fa-icon="upload"
           accept="*" :check="false"  
        />
        
      </div>
      <div>
        <FieldPopup 
            label="No. Asset"
            class="w-full !mt-3"
            valueField="id" displayField="shortname"
            :errorText="formErrors.m_asset_id?'failed':''"
            :hints="formErrors.m_asset_id"
            :value="values.m_asset_id" @input="(v)=>values.m_asset_id=v"
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
          :value="values.tipe_asset" :errorText="formErrors.tipe_asset?'failed':''"
          @input="v=>values.tipe_asset=v" :hints="formErrors.tipe_asset" 
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
          :value="values.perkiraan_disposal"  @input="v=>{
            if(v){
              values.perkiraan_disposal=v
            }else{
              values.perkiraan_disposal=null
            }
          }"
          :errorText="formErrors.perkiraan_disposal?'failed':''" 
          :hints="formErrors.perkiraan_disposal"
          valueField="value1" displayField="value1"
          :api="{
              url: `${store.server.url_backend}/operation/m_gen`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                scopes:'filterByGroup',
                where:'this.is_active=true',
                group:'PERKIRAAN DISPOSAL'
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
          :value="values.nilai_jual" :errorText="formErrors.nilai_jual?'failed':''"
          @input="v=>values.nilai_jual=v" :hints="formErrors.nilai_jual" 
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
          :value="values.nominal_ppn" :errorText="formErrors.nominal_ppn?'failed':''"
          @input="v=>values.nominal_ppn=v" :hints="formErrors.nominal_ppn" 
          label="PPN (Nominal)"
          placeholder="PPN (Nominal)"
          :check="false"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.no_faktur_pajak" :errorText="formErrors.no_faktur_pajak?'failed':''"
          @input="v=>values.no_faktur_pajak=v" :hints="formErrors.no_faktur_pajak" 
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
          :value="values.catatan" :errorText="formErrors.catatan?'failed':''"
          @input="v=>values.catatan=v" :hints="formErrors.catatan" 
          label="Catatan"
          placeholder="Catatan"
          :check="false"
        />
      </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.status"
        :errorText="formErrors.status?'failed':''" @input="v=>values.status=v" :hints="formErrors.status" :check="false"
        label="Status" placeholder="Status" />
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
        <table class="w-[100%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
            <thead>
              <tr class="border">
                <td
                  class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  No.
                </td>

                <td
                  class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Tanggal Penyusutan</td>

                <td
                  class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Nilai Akun Sblm Penyusutan</td>

                <td
                  class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Nilai Buku Sblm Penyusutan</td>

                <td
                  class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Nilai Penyusutan</td>

                <td
                  class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Nilai Akum Stl Penyusutan</td>
                <td
                  class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Nilai Buku Stl Penyusutan</td>

                <td
                  class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Status</td>
            </thead>
            <tbody>
              <tr v-if="detailArr.length === 0" class="text-center">
                <td colspan="9" class="py-[20px] justify-center items-center">No data to show</td>
              </tr>
              <tr v-for="(item, i) in detailArr" :key="i" class="border-t" v-if="detailArr.length > 0">
                <td class="p-2 text-center border border-[#CACACA]">
                  {{ i + 1 }}.
                </td>
                <td class="p-2 border border-[#CACACA] text-center">
                  {{item['tanggal_penyusutan'] ?? '-'}}
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  Rp{{item['nilai_akun_sebelum_penyusutan'] ? parseFloat(item['nilai_akun_sebelum_penyusutan']).toLocaleString('id'):'-' }}
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  Rp{{item['nilai_buku_sebelum_penyusutan']? parseFloat(item['nilai_buku_sebelum_penyusutan']).toLocaleString('id'):'-'}}
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  Rp{{item['nilai_penyusutan']? parseFloat(item['nilai_penyusutan']).toLocaleString('id'):'-'}}
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  Rp{{item['nilai_akumulasi_setelah_penyusutan']? parseFloat(item['nilai_akumulasi_setelah_penyusutan']).toLocaleString('id'):'-'}}
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  Rp{{item['nilai_buku_setelah_penyusutan']? parseFloat(item['nilai_buku_setelah_penyusutan']).toLocaleString('id'):'-'}}
                </td>
                <td class="border border-[#CACACA]">
                  <FieldX :bind="{ readonly: true ,disabled : true}" class="!mt-0" :value="item.status"
                    @input="v=>item.status=v" :check="false" />
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