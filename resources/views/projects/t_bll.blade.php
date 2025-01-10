<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-2 pb-0 mb-0">
    <h1 class="text-xl font-semibold">BLL</h1>
  </div>
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          DRAFT
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('IN APPROVAL')" :class="filterButton === 'IN APPROVAL' ? 'bg-sky-600 text-white hover:bg-sky-600' 
          : 'border border-sky-600 text-sky-600 bg-white hover:bg-sky-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          IN APPROVAL
        </button>
        <!-- <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('In Process')" :class="filterButton === 'In Process' ? 'bg-yellow-600 text-white hover:bg-yellow-600' 
          : 'border border-yellow-600 text-yellow-600 bg-white hover:bg-yellow-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          In Process
        </button> -->
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('APPROVED')" :class="filterButton === 'APPROVED' ? 'bg-purple-600 text-white hover:bg-purple-600' 
          : 'border border-purple-600 text-purple-600 bg-white hover:bg-purple-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          APPROVED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('REVISED')" :class="filterButton === 'REVISED' ? 'bg-yellow-600 text-white hover:bg-yellow-600' 
          : 'border border-yellow-600 text-yellow-600 bg-white hover:bg-yellow-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          REVISED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('REJECTED')" :class="filterButton === 'REJECTED' ? 'bg-red-600 text-white hover:bg-red-600' 
          : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          REJECTED
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
        <h1 class="text-lg font-bold leading-none">Form BLL</h1>
        <p class="text-gray-100 leading-none">Transaction BLL</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 items-start">
    <!-- START COLUMN -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>data.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_bll"
        :errorText="formErrors.no_bll?'failed':''" @input="v=>data.no_bll=v" :hints="formErrors.no_bll" label="No. BLL"
        placeholder="No. BLL" :check="false" />
    </div>
    <div>
      <FieldX :bind="{readonly: !actionText, disabled: !actionText, clearable:false }" class="w-full !mt-3" :value="data.tanggal"
        :errorText="formErrors.tanggal?'failed':''" :hints="formErrors.tanggal" :check="false" type="date"
        label="Tgl BLL" placeholder="Pilih Tgl BLL" />
    </div>
    <div>
      <FieldSelect :bind="{ disabled: true, clearable: false }" class="w-full !mt-3" :value="data.tipe_bll"
        @input="v=>data.tipe_bll=v" :errorText="formErrors.tipe_bll?'failed':''" :hints="formErrors.tipe_bll"
        valueField="id" displayField="key" :options="['Kas','Non Kas']" placeholder="Tipe BKK" :check="false"
        fa-icon="sort-desc" />
    </div>
    <div>
      <FieldPopup label="No. Buku Order" :bind="{ disabled: !actionText, readonly: !actionText }" class="w-full !mt-3" valueField="id" displayField="no_invoice"
        :value="data.t_buku_order_id" @input="(v)=>data.t_buku_order_id=v"
        :errorText="formErrors.t_buku_order_id?'failed':''" :hints="formErrors.t_buku_order_id" :api="{
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
              headerName:  'NO. Buku Order',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'no_invoice',
              headerName:  'NO. Invoice',
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
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
        :value="data.m_akun_pembayaran_id" @input="v=>{
            if(v){
              data.m_akun_pembayaran_id=v
            }else{
              data.m_akun_pembayaran_id=null
            }
          }" :errorText="formErrors.m_akun_pembayaran_id?'failed':''" :hints="formErrors.m_akun_pembayaran_id"
        valueField="id" displayField="nama_coa" :api="{
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
        :errorText="formErrors.keterangan?'failed':''" @input="v=>data.keterangan=v" :hints="formErrors.keterangan"
        label="Catatan" placeholder="Keterangan" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.status"
        :errorText="formErrors.status?'failed':''" @input="v=>data.status=v" :hints="formErrors.status" label="Status"
        placeholder="Status" :check="false" />
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>
  <div class="grid grid-cols-4 place-content-center place-items-center w-[calc(80%)] min-w-220 items-center">
    <button v-for="(item, i) in coaList" :key="i" v-show="coaList.length > 0"
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_coa_id === item.id) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(item.id)">
        {{item.nama_coa}}
    </button>
    <!-- <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_coa_id === 1) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(1)">
        Tipe Perkiraan 1
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_coa_id === 2) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(2)">
        Tipe Perkiraan 2
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_coa_id === 3) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(3)">
        Tipe Perkiraan 3
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_coa_id === 4) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(4)">
        Tipe Perkiraan 4
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_coa_id === 5) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(5)">
        Tipe Perkiraan 5
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_coa_id === 6) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(6)">
        Tipe Perkiraan 6
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_coa_id === 7) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(7)">
        Tipe Perkiraan 7
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_coa_id === 8) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(8)">
        Tipe Perkiraan 8
    </button> -->
  </div>
  <hr />
  <!-- detail -->

  <!-- START TABLE DETAIL -->

  <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <!-- <ButtonMultiSelect @add="addDetailArr" :api="{
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
                  t_bkk_id: data.id || null,
                  m_coa_id: dt.id,
                  nomor: dt.nomor,
                  nama_coa: dt.nama_coa,
                  kategori: dt.['kategori.deskripsi'],
                  jenis: dt.['jenis.deskripsi'],
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
          headerName: 'Nomor Coa',
          field: 'nomor',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Nama Coa',
          field: 'nama_coa',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Kategori',
          field: 'kategori',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Jenis',
          field: 'jenis',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, ]">
        <div class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300">
          <icon fa="plus" size="sm" />
          <span>Add To List</span>
        </div>
      </ButtonMultiSelect> -->
      <button class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300" @click="setDetail">
          <icon fa="plus" size="sm" />
          <span>Add To List</span>
      </button>
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Kode Akun
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
              <p class="text-black leading-none">{{ detailArr[i].nomor }}</p>
            </td>
            <td class="p-2 border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].nama_coa }}</p>
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.nominal" @input="v=>item.nominal=v" :errorText="formErrors.nominal?'failed':''"
                :hints="formErrors.nominal" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldX type="textarea" :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].keterangan"
                :errorText="formErrors.keterangan?'failed':''" @input="v=>detailArr[i].keterangan=v"
                :hints="formErrors.keterangan" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
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

  <!-- <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2">
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
              response.data = [...response.data].map((dt) => {
                return {
                  t_bll_id: data.id || 0,
                  m_coa_id: dt.id,
                  nomor: dt.nomor,
                  nama_coa: dt.nama_coa,
                  jenis: dt['jenis.deskripsi'],
                  kategori: dt['kategori.deskripsi'],
                  tipe_perkiraan: dt['tipe_perkiraan.deskripsi'],
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
          headerName: 'No. Akun',
          field: 'nomor',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Nama Akun',
          field: 'nama_coa',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Jenis',
          field: 'jenis',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Kategori',
          field: 'kategori',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        },
        {
          pinned: false,
          headerName: 'Tipe Perkiraan',
          field: 'tipe_perkiraan',
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
              <p class="text-black leading-none">{{ detailArr[i].nomor }}</p>
            </td>
            <td class="p-2 border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].nama_coa }}</p>
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.nominal" @input="v=>item.nominal=v" :errorText="formErrors.nominal?'failed':''"
                :hints="formErrors.nominal" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldX type="textarea" :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].keterangan"
                :errorText="formErrors.keterangan?'failed':''" @input="v=>detailArr[i].keterangan=v"
                :hints="formErrors.keterangan" :check="false" />
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
  </div> -->
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
    <button v-show="(((actionText=='Edit' || actionText=='Create'|| actionText=='Copy') && (data.status=='DRAFT' || data.status=='REVISED')))" class="text-sm rounded py-2 px-2.5 text-white bg-purple-600 hover:bg-purple-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="sendApproval">
      <icon fa="location-arrow" />
      <span>send Approval</span>
    </button>
  </div>
  <hr v-show="isApproval" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="isApproval">
    <button class="text-sm rounded py-2 px-2.5 text-white  bg-green-600 hover:bg-green-700 flex gap-x-1 items-center 
        transition-colors duration-300" @click="progress('APPROVED')">
      <!-- <icon fa="times" /> -->
      <span>Approve</span>
    </button>
    <button class="text-sm rounded py-2 px-2.5 text-white bg-orange-600 hover:bg-orange-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="progress('REVISED')">
      <!-- <icon fa="save" /> -->
      <span>Revise</span>
    </button>
    <button class="text-sm rounded py-2 px-2.5 text-white  bg-red-600 hover:bg-red-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="progress('REJECTED')">
      <!-- <icon fa="save" /> -->
      <span>Reject</span>
    </button>
  </div>
</div>

@endverbatim
@endif