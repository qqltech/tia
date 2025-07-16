@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">Internal Usage</h1>
  </div>
  <div class="flex justify-between items-center px-4 py-1">

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
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.no_pemakaian"
        :errorText="formErrors.no_pemakaian?'failed':''" @input="v=>values.no_pemakaian=v"
        :hints="formErrors.no_pemakaian" placeholder="No. Pemakaian Stok" label="No. Pemakaian Stok" :check="false" />
    </div>

    <!-- Status Coloumn -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full !mt-3" :value="values.status"
        @input="v=>values.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status"
        valueField="id" displayField="key" :options="[{'id' : 1 , 'key' : 'Active'},{'id': 0, 'key' : 'InActive'}]"
        placeholder="Pilih Status" label="Status" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText, disabled: !actionText }" :value="values.date"
        :errorText="formErrors.date?'failed':''" @input="v=>values.date=v" :hints="formErrors.date" type="date"
        placeholder="Tanggal" label="Tanggal" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        placeholder="Catatan" label="Catatan" :check="false" />
    </div>

  </div>

  <!-- DETAIL -->

  <div class="p-4">
    <button v-show="actionText" @click="addDetail" type="button" class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
          <icon fa="plus" />
          Add to List
        </button>

    <div class="mt-4" style=" border: 1px solid #CACACA;">
      <table class="w-[100%] table-auto border border-[#CACACA]">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[1%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[25%] border bg-[#f8f8f8] border-[#CACACA]">
              Item
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Item Detail
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
              <FieldSelect :bind="{ disabled: !actionText, readonly: !actionText }" class="w-full !mt-3"
                :value="item.m_item_id" @input="v=>item.m_item_id=v" :errorText="formErrors.m_item_id?'failed':''"
                :hints="formErrors.m_item_id" valueField="id" displayField="nama_item" @update:valueFull="(dt)=>{
                  item.is_bundling = !!dt.is_bundling
                }" :api="{
                        url: `${store.server.url_backend}/operation/m_item`,
                        headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                        params: {
                          simplest: true,
                          selectfield: 'this.nama_item, this.id, this.is_bundling'
                        }
                    }" 
                    placeholder="Pilih salah satu item" label="" :check="true" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX v-show="item.is_bundling" class="w-full !mt-3" :bind="{ readonly: true }" :value="'-'"
                :errorText="formErrors.m_item_d_text?'failed':''" @input="v=>item.m_item_d_text=v"
                :hints="formErrors.m_item_d_text" placeholder="" label="" :check="false" />

              <FieldPopup v-show="!item.is_bundling" class="w-full !mt-3" :bind="{ readonly: !actionText }"
                :value="item.m_item_d_id" @input="(v)=>item.m_item_d_id=v"
                :errorText="formErrors.m_item_d_id?'failed':''" :hints="formErrors.m_item_d_id" valueField="id"
                displayField="no_lpb" @update:valueFull="(dt)=>{
                  item.satuan_id = dt['m_item.uom_id']
                }" :api="{
                  url: `${store.server.url_backend}/operation/m_item_d`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    simplest:true,
                    searchfield: 'this.no_lpb, this.date, this.catatan, this.used',
                    onsuccess:(response)=> {
                      response.page = response.current_page
                      response.hasNext = response.has_next
                      return response
                    }
                  }
                }" placeholder="Pilih salah satu detail" label="" :check="false" :columns="[{
                  headerName: 'No',
                  valueGetter:(p)=>p.node.rowIndex + 1,
                  width: 60,
                  sortable: false, resizable: false, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                  flex: 1,
                  field: 'no_lpb',
                  headerName:  'No. LPB',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  cellClass: ['border-r', '!border-gray-200', 'justify-center']
                },
                {
                  flex: 1,
                  field: 'catatan',
                  headerName:  'Catatan',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  cellClass: ['border-r', '!border-gray-200', 'justify-center']
                },
                {
                  flex: 1,
                  field: 'used',
                  headerName:  'Used',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
                  valueGetter: (params) => params.data.used ? 'Yes' : 'No'
                }
                ]" />

            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, clearable:false }" :value="item.satuan_id"
                @input="v=>item.satuan_id=v" :errorText="formErrors.satuan_id?'failed':''" :hints="formErrors.satuan_id"
                valueField="id" displayField="deskripsi" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      where: `this.group = 'UOM'`
                    }
                }" placeholder="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber class="w-full !mt-3" :bind="{ disabled: !actionText, readonly: !actionText }"
                :value="item.stock || 100" @input="v=>item.stock=v" :errorText="formErrors.stock?'failed':''"
                :hints="formErrors.stock" placeholder="Pilih Stock" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber class="w-full !mt-3" :bind="{ disabled: !actionText, readonly: !actionText }"
                :value="item.usage" @input="v=>item.usage=v" :errorText="formErrors.usage?'failed':''"
                :hints="formErrors.usage" placeholder="Pilih Usage" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX class="w-full !mt-3" :bind="{ disabled: !actionText, readonly: !actionText }"
                :value="item.catatan" @input="v=>item.catatan=v" :errorText="formErrors.catatan?'failed':''"
                :hints="formErrors.catatan" placeholder="Pilih Catatan" label="" :check="false" />
            </td>
            <td v-show="actionText" class="p-2 border border-[#CACACA]">
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
  <!-- FORM END -->
  <div class="flex flex-row items-center justify-end space-x-2 p-2">
    <i v-show="actionText" class="text-gray-500 text-[12px] mr-4">Pastikan data terisi dengan benar!</i>
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

</div>

@endverbatim
@endif