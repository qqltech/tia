@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true)" :class="filterButton === true ? 'bg-green-600 text-white hover:bg-green-600' 
        : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'" class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
          Active
        </button>
        <div class="flex my-auto h-4 w-px bg-[#6E91D1]"></div>
        <button @click="filterShowData(false)" :class="filterButton === false ? 'bg-red-600 text-white hover:bg-red-600' 
        : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'" class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
          InActive
        </button>
      </div>
    </div>

    <!-- ACTION BUTTON -->
    <div class="flex items-center gap-x-4">
      <RouterLink :to="$route.path + '/create?' + (Date.parse(new Date()))" class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white 
                        text-sm rounded-md py-1 px-2.5 transition-colors duration-300">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>

  <!-- TABLE -->
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px] pt-2 !px-4 !pb-8">
    <template #header>
      <div class="pb-13 h-full"></div>
    </template>
  </TableApi>
</div>


</div>
@else

<!-- CONTENT -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Internal Usage</h1>
        <p class="text-gray-100">Untuk mengatur Konfirmasi Internal Usage</p>
      </div>
    </div>
  </div>
  <!-- HEADER END -->

  <!-- FORM START -->
  <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row p-4 gap-3">
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.no_pemakaian_stok"
        :errorText="formErrors.no_pemakaian_stok?'failed':''" @input="v=>values.no_pemakaian_stok=v" :hints="formErrors.no_pemakaian_stok"
        placeholder="No. Pemakaian Stok" label="No. Pemakaian Stok" :check="false" />
    </div>

    <!-- <div class=" w-full !mt-3">
      <FieldPopup class="!mt-0"
       displayField="no_lpb" valueField="id" :bind="{ readonly: !actionText }" 
            :value="values.t_lpb_id"
            @input="(v)=>values.t_lpb_id=v"
            :errorText="formErrors.t_lpb_id?'failed':''" :hints="formErrors.t_lpb_id"
            placeholder="Pilih LPB" label="Nomor LPB" :check='false'
            :api="{
            url: `${store.server.url_backend}/operation/t_lpb`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              searchfield:'this.no_lpb , this.tanggal_lpb , this.no_sj_supplier , this.tanggal_sj_supplier',
            },
          }"

             :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'no_lpb',
            headerName: 'Nomor LPB',
            sortable: true, 
            resizable: true, 
            filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            
          },
          {
            flex: 1,
             field: 'tanggal_lpb',
            headerName: 'Tanggal LPB',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'no_sj_supplier',
            headerName: 'Nomor SJ Supplier',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'tanggal_sj_supplier',
            headerName: 'Tanggal SJ Supplier',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'm_supplier.nama',
            headerName: 'Nama Supplier',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'm_supplier.negara',
            headerName: 'Negara Supplier',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          ]" />
    </div> -->

    <!-- Status Coloumn -->
    <div>
      <FieldX class="w-full !mt-3" :bind="{ disabled: true, clearable:true }" :value="values.status"
        @input="v=>values.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status"
        placeholder="Pilih Status" label="Status" :check="false" />
    </div>

    <!-- <div class="w-full !mt-3">
      <FieldPopup class="!mt-0"
       displayField="no_lpb" valueField="id" :bind="{ readonly: !actionText }" 
            :value="values.pic"
            @input="(v)=>values.pic=v"
            :errorText="formErrors.pic?'failed':''" :hints="formErrors.pic"
            placeholder="Pilih PIC" label="PIC" :check='false'
            :api="{
            url: `${store.server.url_backend}/operation/m_kary`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              searchfield:'this.no_lpb , this.tanggal_lpb , this.no_sj_supplier , this.tanggal_sj_supplier',
            },
          }"

             :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'nip',
            headerName: 'NIP',
            sortable: true, 
            resizable: true, 
            filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            
          },
          {
            flex: 1,
             field: 'nama',
            headerName: 'Nama',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'no_id',
            headerName: 'Nomor ID',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          ]" />
    </div> -->

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.tanggal"
        :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v" :hints="formErrors.tanggal"
        type="date" placeholder="Tanggal" label="Tanggal" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        placeholder="Catatan" label="Catatan" :check="false" />
    </div>

    <!-- <div class="w-full !mt-3 flex space-x-3">
          <FieldNumber
            class="!mt-0 w-[70%]"
            :bind="{ readonly: !actionText }" 
            :value="values.masa_manfaat" :errorText="formErrors.masa_manfaat?'failed':''"
            @input="v=>values.masa_manfaat=v"
            :hints="formErrors.masa_manfaat" 
            placeholder="Masukan Masa Manfaat" label="Masa Manfaat":check="false"
          />
          <span class="!mt-0 text-xl font-bold">BLN</span>
    </div>
    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0"
       displayField="no_lpb" valueField="id" :bind="{ readonly: !actionText }" 
            :value="values.m_perkiraan_akun_penyusutan"
            @input="(v)=>values.m_perkiraan_akun_penyusutan=v"
            :errorText="formErrors.m_perkiraan_akun_penyusutan?'failed':''" :hints="formErrors.m_perkiraan_akun_penyusutan"
            placeholder="Pilih Perkiraan Akun Penyusutan" label="Perkiraan Akun Penyusutan" :check='false'
            :api="{
            url: `${store.server.url_backend}/operation/m_coa`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              searchfield:'this.no_lpb , this.tanggal_lpb , this.no_sj_supplier , this.tanggal_sj_supplier',
            },
          }"

             :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'nip',
            headerName: 'NIP',
            sortable: true, 
            resizable: true, 
            filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            
          },
          {
            flex: 1,
             field: 'nama',
            headerName: 'Nama',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'no_id',
            headerName: 'Nomor ID',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          ]" />
    </div> -->

  </div>
  <!-- FORM END -->
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
    <hr>

    <!-- DETAIL -->

    <div class="p-4">
      <button v-show="actionText" @click="addDetail" type="button" class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
          <icon fa="plus" />
          Add to List
        </button>

      <div class="mt-4" style="overflow-x: auto; border: 1px solid #CACACA;">
        <table class="w-[120%] table-auto border border-[#CACACA]">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[1%] border bg-[#f8f8f8] border-[#CACACA]">
                No.
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Item
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Satuan
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Stok
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Usage
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
                <FieldSelect :bind="{ disabled: false, clearable:true }" class="w-full !mt-3" :value="values.m_item_id"
                  @input="v=>values.m_item_id=v" :errorText="formErrors.m_item_id?'failed':''" :hints="formErrors.m_item_id"
                  valueField="id" displayField="nama_item" :api="{
                        url: `${store.server.url_backend}/operation/m_item`,
                        headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                        params: {
                          searchfield: 'this.nama_item'
                        }
                    }" placeholder="Item" label="Item" :check="true" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX class="w-full !mt-3" :bind="{ disabled: false, clearable:true }" :value="values.satuan"
                  @input="v=>values.satuan=v" :errorText="formErrors.satuan?'failed':''" :hints="formErrors.satuan"
                  placeholder="Pilih Satuan" label="Satuan" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX class="w-full !mt-3" :bind="{ disabled: false, clearable:true }" :value="values.stock"
                  @input="v=>values.stock=v" :errorText="formErrors.stock?'failed':''" :hints="formErrors.stock"
                  placeholder="Pilih Stock" label="Stock" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX class="w-full !mt-3" :bind="{ disabled: false, clearable:true }" :value="values.usage"
                  @input="v=>values.usage=v" :errorText="formErrors.usage?'failed':''" :hints="formErrors.usage"
                  placeholder="Pilih Usage" label="Usage" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX class="w-full !mt-3" :bind="{ disabled: false, clearable:true }" :value="values.catatan"
                  @input="v=>values.catatan=v" :errorText="formErrors.catatan?'failed':''" :hints="formErrors.catatan"
                  placeholder="Pilih Catatan" label="Catatan" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div class="flex justify-center">
                  <button type="button" @click="removeItem(i)" :disabled="!actionText" title="Hapus">
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

</div>

@endverbatim
@endif