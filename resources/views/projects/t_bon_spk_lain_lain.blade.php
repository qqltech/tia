<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">BON SPK LAIN-LAIN</h1>
  </div>
  <div class="flex justify-between items-center px-4 py-1">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-gray-600 text-white hover:gray-600-600' 
          : 'border border-gray-600 text-gray-600 bg-white hover:bg-gray-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          DRAFT
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-amber-600 text-white hover:bg-amber-600' 
          : 'border border-amber-600 text-amber-600 bg-white hover:bg-amber-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          POST
        </button>
      </div>
    </div>


    <!-- ACTION BUTTON -->
    <div class="flex items-center gap-x-4">
      <RouterLink :to="$route.path + '/create?' + (Date.parse(new Date()))" class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white text-sm rounded py-1 px-2.5
      transition-colors duration-300">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>

  <!-- TABLE -->
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions" class="max-h-[450px] pt-2 !px-4 
  !pb-8">
    <template #header>
      <div class="pb-13 h-full"></div>
    </template>
  </TableApi>
</div>
@else

<!-- FORM DATA -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md pt-2 pb-3 px-4">
    <div class="flex items-center gap-2">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
      <div class="flex flex-col py-1 gap-1">
        <h1 class="text-lg font-bold leading-none">Form Bon SPK Lain-lain</h1>
        <p class="text-gray-100 leading-none">Transaction Bon SPK Lain-lain</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 items-start">
    <!-- col-span-2 -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_draft" @input="v=>values.no_draft=v"
        :errorText="formErrors.no_draft?'failed':''" :hints="formErrors.no_draft" label="No. Draft"
        placeholder="Auto Generate" :check="false" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ readonly: true, disabled: true, clearable:false }"
        :value="values.status" @input="v=>values.status=v" :errorText="formErrors.status?'failed':''"
        :hints="formErrors.status" valueField="id" displayField="key" :options="[{'id' : 'DRAFT' , 'key' : 'DRAFT'},
      {'id' : 'POSTED' , 'key' : 'POSTED'},
      {'id' : 'IN PROCESS' , 'key' : 'IN PROCESS'},
      {'id' : 'COMPLETE' , 'key' : 'COMPLETE'}]" placeholder="Status" label="Status" :check="true" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true, disabled: true }" class="w-full !mt-3" :value="values.no_bsg"
        @input="v=>values.no_bsg=v" :errorText="formErrors.no_bsg?'failed':''" :hints="formErrors.no_bsg"
        placeholder="Auto Generate" label="Nomor BSG" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" class="w-full !mt-3" :value="values.tanggal"
        @input="v=>values.tanggal=v" :errorText="formErrors.tanggal?'failed':''" :hints="formErrors.tanggal" type="date"
        placeholder="Tanggal BSG" :check="false" />
    </div>
    <div>
      <FieldPopup :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.t_spk_lain_lain_id" @input="v=>{
          if(v){
            values.t_spk_lain_lain_id=v
          }else{
            values.t_spk_lain_lain_id=null
          }
          values.genzet=null
          values.no_order=null
          values.customer=null
        }" :errorText="formErrors.t_spk_lain_lain_id?'failed':''" :hints="formErrors.t_spk_lain_lain_id"
        valueField="id" displayField="no_spk" @update:valueFull="(dt) => {
              $log(dt)
              values.genzet = dt['genzet.nama']
              values.no_order = dt['t_buku_order.no_buku_order']
              values.customer = dt['m_customer.kode']
            }" @update:valueFull="detailBon" :api="{
          url:  `${store.server.url_backend}/operation/t_spk_lain`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            searchfield: 'this.no_draft, this.no_spk, this.tanggal, genzet.nama, t_buku_order.no_buku_order'
          },
          onsuccess : (response) => {
            response.page = response.current_page
            response.hasNext = response.has_next
            return response
          }
        }" placeholder="Pilih No. SPK Lain-Lain" label="No. SPK Lain-Lain" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'no_draft',
          headerName:  'No. Draft',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'no_spk',
          headerName:  'No. SPK Lain-Lain',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'tanggal',
          headerName:  'Tanggal SPK',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'genzet.nama',
          headerName:  'Genzet',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 't_buku_order.no_buku_order',
          headerName:  'No. Order',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'm_customer.kode',
          headerName:  'Kode Customer',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        ]" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.genzet" @input="v=>values.genzet=v"
        :errorText="formErrors.genzet?'failed':''" :hints="formErrors.genzet" placeholder="Genzet" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_order" @input="v=>values.no_order=v"
        :errorText="formErrors.no_order?'failed':''" :hints="formErrors.no_order" placeholder="No. Order"
        :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.customer" @input="v=>values.customer=v"
        :errorText="formErrors.customer?'failed':''" :hints="formErrors.customer" placeholder="Customer"
        :check="false" />
    </div>
    <div>
      <FieldPopup :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.operator"
        @input="(v)=>values.operator=v" :errorText="formErrors.operator?'failed':''" :hints="formErrors.operator"
        valueField="id" displayField="nama" :api="{
          url:  `${store.server.url_backend}/operation/m_kary`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            getNoContainer:true,
            searchfield: 'bu.nama, this.nama, this.nip, this.no_id, this.divisi'
          },
          onsuccess(response) {
            response.page = response.current_page
            response.hasNext = response.has_next
            return response
          }
        }" placeholder="Operator" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'bu.nama',
          headerName: 'Business Unit',
          sortable: true, resizable: true, filter: false, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'NIP',
          field: 'nip',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-center',],
          sortable: true, resizable: true, filter: false, filter: 'ColFilter',
        },
        {
          headerName: 'Nama',
          field: 'nama',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-center',],
          sortable: true, resizable: true, filter: false, filter: 'ColFilter',
        },
        {
          headerName: 'No. ID/KTP',
          field: 'no_id',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-center',],
          sortable: true, resizable: true, filter: false, filter: 'ColFilter',
        },
        {
          headerName: 'Divisi',
          field: 'divisi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-center',],
          sortable: true, resizable: true, filter: false, filter: 'ColFilter',
        },
        ]" />
    </div>
  </div>
  <hr />

  <!-- START DETAIL -->
  <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[15%] border bg-[#f8f8f8] border-[#CACACA]">
              Sektor</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[15%] border bg-[#f8f8f8] border-[#CACACA]">
              Sangu</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[15%] border bg-[#f8f8f8] border-[#CACACA]">
              Tambahan</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[15%] border bg-[#f8f8f8] border-[#CACACA]">
              Total Bon</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[15%] border bg-[#f8f8f8] border-[#CACACA]">
              Tagihan</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[30%] border bg-[#f8f8f8] border-[#CACACA]">
              Catatan</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="i" v-show="detailArr.length > 0">
            <td class="p-1 text-center border border-[#CACACA]">
              {{ i+1 }}.
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldSelect class="m-0" :bind="{ disabled: true, readonly:true, clearable:false }" :value="item.sektor"
                @input="v=>item.sektor=v" :errorText="formErrors.sektor?'failed':''" :hints="formErrors.sektor"
                valueField="id" displayField="deskripsi" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                    }
                }" placeholder="" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: !actionText }" class="m-0" :value="item.sangu" @input="v=>item.sangu=v"
                :hints="formErrors.sangu" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: !actionText }" class="m-0" :value="item.tambahan"
                @input="v=>item.tambahan=v" :hints="formErrors.tambahan" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: true }" class="m-0" :value="countTotalBon(item.sangu, item.tambahan, item.bon)"
                @input="v=>item.bon=v" :hints="formErrors.bon" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: !actionText }" class="m-0" :value="item.tagihan"
                @input="v=>item.tagihan=v" :hints="formErrors.tagihan" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">{{item.catatan}} </td>
          </tr>
          <tr v-show="detailArr.length <= 0" class="text-center">
            <td colspan="15" class="py-[20px]">
              No data to show
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- END DETAIL -->

  <!-- Detail Keterangan -->
  <div class="w-full flex justify-center col-span-2">
    <div class="w-md">
      <div class="grid grid-cols-2 gap-x-2 ">
        <label class="!mt-4 !ml-3 text-xl font-bold">Total Bon</label>
        <FieldNumber class="w-full content-center !mt-3" :bind="{ readonly: true }" :value="HitungBon || 0"
          @input="(v)=>values.total_bon=v" :errorText="formErrors.total_bon?'failed':''" :hints="formErrors.total_bon"
          placeholder="0.00" label="" :check="false" />
      </div>
      <div class="grid grid-cols-2 gap-x-2 mb-4">
        <label class="!mt-4 !ml-3 text-xl font-bold">Total Tagihan</label>
        <FieldNumber class="w-full content-center !mt-3" :bind="{ readonly: true }" :value="HitungTagihan || 0"
          @input="(v)=>values.total_tagihan=v" :errorText="formErrors.total_tagihan?'failed':''" :hints="formErrors.total_tagihan"
          placeholder="0.00" label="" :check="false" />
      </div>
    </div>
  </div>
  <!-- End Detail Keterangan -->

  <!-- ACTION BUTTON FORM -->
  <hr>
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