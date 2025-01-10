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
        <h1 class="text-lg font-bold leading-none">Form Rencana Pembayaran Hutang</h1>
        <p class="text-gray-100 leading-none">Transaction Rencana Pembayaran Hutang</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 items-start">
    <!-- col-span-2 -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>data.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_rph"
        :errorText="formErrors.no_rph?'failed':''" @input="v=>data.no_rph=v" :hints="formErrors.no_rph" label="No. RPH"
        placeholder="No. RPH" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.status"
        :errorText="formErrors.status?'failed':''" @input="v=>data.status=v" :hints="formErrors.status" label="Status"
        placeholder="Status" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ disabled: true, readonly: true }" class="w-full !mt-3 pointer-events-none" :value="data.tanggal"
        :errorText="formErrors.tanggal?'failed':''" :hints="formErrors.tanggal" :check="false" type="date"
        label="Tanggal" placeholder="Tanggal" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.total_pi"
        :errorText="formErrors.total_pi?'failed':''" @input="v=>data.total_pi=v" :hints="formErrors.total_pi"
        label="Total PI" placeholder="Total PI" :check="false" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="data.total_bayar"
        :errorText="formErrors.total_bayar?'failed':''" @input="v=>data.total_bayar=v" :hints="formErrors.total_bayar"
        label="Total Bayar" placeholder="Total Bayar" :check="false" />
    </div>
    <div>
      <FieldX type="textarea" :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>data.catatan=v" :hints="formErrors.catatan"
        label="Catatan" placeholder="Masukan Catatan" :check="false" />
    </div>


  </div>
  <hr />
  <!-- START TABLE DETAIL -->

  <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <div>
        <FieldPopup class="w-full !mt-3 mb-3 w-[calc(50%)]" :api="{
        url: `${store.server.url_backend}/operation/m_supplier`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest:false,
          transform:false,
          join:false,
          // override:true,
          // where:`this.is_active=true`,
          // searchfield:'this.no_id, this.nip, this.nama, this.alamat_domisili',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili' 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="nama" valueField="id" :bind="{ readonly: !actionText }" :value="data.m_supplier_id"
          @input="(v)=>data.m_supplier_id=v" @update:valueFull="(response)=>{
        $log(response);
      }" :errorText="formErrors.m_supplier_id?'failed':''" class="w-full !mt-3" :hints="formErrors.m_supplier_id"
          placeholder="Pilih Supplier" :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'kode',
          headerName: 'Kode',
          sortable: true, resizable: true, filter: false,
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'Nama',
          field: 'nama',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
      ]" />
      </div>
      <ButtonMultiSelect @add="addDetailArr" :api="{
        url: `${store.server.url_backend}/operation/t_tagihan`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: true,
            join: true, 
            searchfield: 'this.no_draft, this.no_tagihan, this.no_buku_order, this.grand_total_amount',
            notin: `this.id: ${detailArr.map((det)=> (det.t_tagihan_id))}`
            },
            onsuccess: (response) => {
              $log(response.data[0]['m_item.id'])
              response.data = [...response.data].map((dt) => {
                return {
                  t_rencana_pembayaran_hutang_id: data.id || null,
                  t_tagihan_id: dt.id,
                  no_tagihan: dt.no_tagihan,
                  no_buku_order: dt.['no_buku_order.no_buku_order'],
                  tipe_tagihan: dt.tipe_tagihan,
                  grand_total_amount: dt['grand_total_amount'],
                  m_item_id: dt.['m_item.id'],
                  kode: dt.['m_item.kode'],
                  nama_item: dt.['m_item.nama_item'],
                  tipe_item: dt.['m_item.tipe_item'],
                  quantity: dt.quantity,
                  harga: dt.harga,
                  satuan: 'Lembar',
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
          headerName: 'No. Tagihan',
          field: 'no_tagihan',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'No. Buku Order',
          field: 'no_buku_order',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Tipe Tagihan',
          field: 'tipe_tagihan',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Total',
          field: 'grand_total_amount',
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
              No.</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              No. Referensi</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Supplier</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Tgl Jatuh Tempo</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Jumlah</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Jumlah Bayar</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Keterangan</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Tgl Realisasi</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Jumlah Realisasi</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Cara Bayar</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Status</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              Action</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="i" v-show="detailArr.length > 0">
            <td class="p-1 text-center border border-[#CACACA]">
              {{ i+1 }}.
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].no_tagihan }}</p>
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].kode }}</p>
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].nama_item }}</p>
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].quantity }}</p>
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].nominal"
                :errorText="formErrors.nominal?'failed':''" @input="v=>detailArr[i].nominal=v"
                :hints="formErrors.nominal" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldX type="textarea" :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].keterangan"
                :errorText="formErrors.keterangan?'failed':''" @input="v=>detailArr[i].keterangan=v"
                :hints="formErrors.keterangan" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].total_amount }}</p>
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