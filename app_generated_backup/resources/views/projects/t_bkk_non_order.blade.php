<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          Posted
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('In Approval')" :class="filterButton === 'In Approval' ? 'bg-sky-600 text-white hover:bg-sky-600' 
          : 'border border-sky-600 text-sky-600 bg-white hover:bg-sky-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          In Approval
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('In Process')" :class="filterButton === 'In Process' ? 'bg-yellow-600 text-white hover:bg-yellow-600' 
          : 'border border-yellow-600 text-yellow-600 bg-white hover:bg-yellow-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          In Process
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('Complete')" :class="filterButton === 'Complete' ? 'bg-purple-600 text-white hover:bg-purple-600' 
          : 'border border-purple-600 text-purple-600 bg-white hover:bg-purple-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          Complete
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('Cancel')" :class="filterButton === 'Cancel' ? 'bg-red-600 text-white hover:bg-red-600' 
          : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          Cancel
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
  <TableApi ref='apiTable' :api="table.api" :columns="table.columns" :actions="table.actions" class="max-h-[500px] pt-2 !px-4 
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
        <h1 class="text-lg font-bold leading-none">Form BKK Non Order</h1>
        <p class="text-gray-100 leading-none">Transaction BKK Non Order</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_draft" :errorText="formErrors.no_draft?'failed':''"
        @input="v=>data.no_draft=v" :hints="formErrors.no_draft" label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_kk" :errorText="formErrors.no_kk?'failed':''"
        @input="v=>data.no_kk=v" :hints="formErrors.no_kk" label="No. BKK" placeholder="No. BKK" :check="false" />
    </div>
    <div>
      <FieldX :bind="{readonly: true, disabled: true }" class="w-full !mt-3" :value="data.tanggal"
        :errorText="formErrors.tanggal?'failed':''" :hints="formErrors.tanggal" :check="false" type="date"
        label="Tgl BKK" placeholder="Pilih Tgl BKK" />
    </div>
    <div>
      <FieldPopup label="No. Buku Order" class="w-full !mt-3" valueField="id" displayField="no_invoice"
        :value="data.t_po_id" @input="(v)=>data.t_po_id=v" :api="{
              url: `${store.server.url_backend}/operation/t_buku_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                selectfield: 'id,no_invoice,tipe_order,jenis_barang,sektor',
                searchfield: 'this.no_invoice, this.tipe_order, this.jenis_barang,this.sektor'
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
              field: 'no_invoice',
              headerName:  'N0. Invoice',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'tipe_order',
              headerName:  'Tipe Order',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'jenis_barang',
              headerName:  'Jenis Barang',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'sektor',
              headerName:  'Sektor',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
    </div>
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3" :value="data.tipe_id" @input="v=>{
            if(v){
              data.tipe_id=v
            }else{
              data.tipe_id=null
            }
          }" :errorText="formErrors.tipe_id?'failed':''" :hints="formErrors.tipe_id" valueField="id"
        displayField="name" :api="{
              url: `${store.server.url_backend}/operation/m_coa`,
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
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="data.total_amt"
        :errorText="formErrors.total_amt?'failed':''" @input="v=>data.total_amt=v" :hints="formErrors.total_amt"
        label="Total Amt" placeholder="Total Amt" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" type="textarea" :value="data.keterangan"
        :errorText="formErrors.keterangan?'failed':''" @input="v=>data.keterangan=v" :hints="formErrors.keterangan" label="Catatan"
        placeholder="Keterangan" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.status"
        :errorText="formErrors.status?'failed':''" @input="v=>data.status=v" :hints="formErrors.status"
        label="Status" placeholder="Status" :check="false" />
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>
    <div class="grid grid-cols-4 place-content-center place-items-center w-[calc(80%)] min-w-220 items-center">
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_perkiraan_id === 1) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(1)">
        Tipe Perkiraan 1
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_perkiraan_id === 2) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(2)">
        Tipe Perkiraan 2
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_perkiraan_id === 3) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(3)">
        Tipe Perkiraan 3
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_perkiraan_id === 4) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(4)">
        Tipe Perkiraan 4
    </button>
  </div>
  <hr />
 <!-- detail -->

  <!-- START TABLE DETAIL -->

  <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <ButtonMultiSelect @add="addDetailArr" :api="{
        url: `${store.server.url_backend}/operation/m_coa`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: true, 
            searchfield: 'this.kategori, this.debit_kredit',
            notin: `this.id: ${detailArr.map((det)=> (det.m_coa_id))}`
            },
            onsuccess: (response) => {
              $log(response.data[0]['m_item.id'])
              response.data = [...response.data].map((dt) => {
                return {
                  t_bkk_non_order_id: data.id || null,
                  kode_akun: dt.kode_akun,
                  nama_akun: dt.['nama_akun'],
                  catatan: '',
                }
              })
            return response
          }
        }" :columns="[{
          checkboxSelection: true,
          headerCheckboxSelection: true,
          headerName: 'No',
          valueGetter: (params) => '',
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        }, 
        {
          pinned: false,
          headerName: 'No. PO',
          field: 't_no_po',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Kode Item',
          field: 'kode',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Nama Item',
          field: 'nama_item',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Tipe Item',
          field: 'tipe_item',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, ]">
        <div class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300">
          <icon fa="plus" size="sm" />
          <span>Add To List</span>
        </div>
      </ButtonMultiSelect>
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
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
          <tr v-for="(item, i) in detailArr" :key="i" v-show="detailArr.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <td class="p-2 border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].kode }}</p>
            </td>
            <td class="p-2 border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].nama }}</p>
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.nominal" @input="v=>item.nominal=v" :errorText="formErrors.nominal?'failed':''"
                :hints="formErrors.nominal" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldX type="textarea" :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].catatan"
                :errorText="formErrors.catatan?'failed':''" @input="v=>detailArr[i].catatan=v"
                :hints="formErrors.catatan" :check="false" />
            </td>
            <td class="p-1 border border-[#CACACA] ">
              <div class="flex justify-center">
                <RouterLink :to="'/m_item'"
                  class="bg-blue-500 hover:bg-blue-600 text-white font-semibold w-18 h-6 py-5 text-center items-center justify-center rounded-md flex items-center justify-center">
                  <icon fa="eye" />
                </RouterLink>
              </div>
            </td>
            <td class="p-1 border border-[#CACACA] ">
              <div class="flex justify-center">
                <RouterLink :to="'/m_item'"
                  class="bg-blue-500 hover:bg-blue-600 text-white font-semibold w-18 h-6 py-5 text-center items-center justify-center rounded-md flex items-center justify-center">
                  <icon fa="eye" />
                </RouterLink>
              </div>
            </td>
            <td class="p-1 border border-[#CACACA]">
              <div class="flex justify-center">
                <button type="button" @click="delDetailArr(i)" :disabled="!actionText">
                  <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                  </svg>
                </button>
              </div>
            </td>
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
  <!-- END TABLE DETAIL -->
  <!-- ACTION BUTTON FORM -->
  <hr v-show="actionText" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="actionText">
    <i class="text-gray-500 text-[12px] mr-4">Tekan CTRL + S untuk shortcut Save Data</i>
    <button class="text-sm rounded py-2 px-2.5 text-white bg-red-600 hover:bg-red-700 flex gap-x-1 
        items-center transition-colors duration-300" @click="onReset(true)">
      <icon fa="times" />
      <span>Reset</span>
    </button>
    <button class="text-sm rounded py-2 px-2.5 text-white bg-green-600 hover:bg-green-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="onSave">
      <icon fa="save" />
      <span>Simpan</span>
    </button>
  </div>
</div>

@endverbatim
@endif