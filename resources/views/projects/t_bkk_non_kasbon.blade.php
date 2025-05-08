<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
   <div class="pl-4 pt-2">
    <h1 class="text-xl font-semibold">BKK (Non Kasbon)</h1>
  </div>
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-gray-600 text-white hover:bg-gray-600' 
          : 'border border-gray-600 text-gray-600 bg-white hover:bg-gray-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          DRAFT
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('IN APPROVAL')" :class="filterButton === 'IN APPROVAL' ? 'bg-sky-600 text-white hover:bg-sky-600' 
          : 'border border-sky-600 text-sky-600 bg-white hover:bg-sky-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          IN APPROVAL
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('APPROVED')" :class="filterButton === 'APPROVED' ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          APPROVED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('PRINTED')" :class="filterButton === 'PRINTED' ? 'bg-purple-600 text-white hover:bg-purple-600' 
          : 'border border-purple-600 text-purple-600 bg-white hover:bg-purple-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          PRINTED
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
        <h1 class="text-lg font-bold leading-none">Form BKK Non Kasbon</h1>
        <p class="text-gray-100 leading-none">Transaction BKK Non Kasbon</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
        :value="data.m_business_unit_id" @input="v=>{
            if(v){
              data.m_business_unit_id=v
            }else{
              data.m_business_unit_id=null
            }
          }" :errorText="formErrors.m_business_unit_id?'failed':''" :hints="formErrors.m_business_unit_id"
        valueField="id" displayField="nama" :api="{
              url: `${store.server.url_backend}/operation/m_business_unit`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:`this.is_active=true`

              }
          }" placeholder="Pilih Business Unit" label="Business Unit" :check="true" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>data.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_bkk"
        :errorText="formErrors.no_bkk?'failed':''" @input="v=>data.no_bkk=v" :hints="formErrors.no_bkk" label="No. BKK"
        placeholder="No. BKK" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText, disabled: !actionText, clearable:false }" class="w-1/2 !mt-3"
        :value="data.tanggal" :errorText="formErrors.tanggal?'failed':''" @input="v=>data.tanggal=v"
        :hints="formErrors.tanggal" :check="false" type="date" label="Tgl BKK" placeholder="Pilih Tgl BKK" />
    </div>
    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" :bind="{ readonly: !actionText }" :value="data.t_buku_order_id" @input="v=>{
          if(v){
            data.t_buku_order_id=v
          }else{
            data.t_buku_order_id=null
          }
        }" :errorText="formErrors.t_buku_order_id?'failed':''" :hints="formErrors.t_buku_order_id" @update:valueFull="(dt) => {
              $log(dt)
            }" valueField="id" displayField="no_buku_order" :api="{
              url: `${store.server.url_backend}/operation/t_buku_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                //where: `this.status='POST'`
                searchfield:'this.no_buku_order, this.jenis_barang, m_customer.nama_perusahaan',
              }
            }" placeholder="No Order" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'no_buku_order',
              headerName:  'No Order',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'jenis_barang',
              headerName:  'Jenis Barang',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            
            {
              flex: 1,
              field: 'm_customer.nama_perusahaan',
              headerName:  'Nama Customer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            }
            ]" />
    </div>

    <div>
      <FieldX :bind="{ readonly: !actionText, disabled: !actionText, clearable:false }" class="w-full !mt-3"
        :value="data.nama_penerima" :errorText="formErrors.nama_penerima?'failed':''" @input="v=>data.nama_penerima=v"
        :hints="formErrors.nama_penerima" :check="false" label="Nama Penerima" placeholder="Nama Penerima" />
    </div>

    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, readonly: !actionText }" displayField="deskripsi"
        valueField="id" :value="data.tipe_pembayaran" @input="(v) => data.tipe_pembayaran = v"
        :errorText="formErrors.tipe_pembayaran ? 'failed' : ''" :hints="formErrors.tipe_pembayaran"
        placeholder="Tipe Pembayaran" label="Tipe Pembayaran" :check="false" @update:valueFull="(response)=>{
          $log(response)
          data.tipe_pembayaran_deskripsi = response.deskripsi
        }" :api="{
      url: `${store.server.url_backend}/operation/m_general`,
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      params: {
        join: false,
        simplest: false,
        selectfield: 'this.id, this.deskripsi',
        where: `this.is_active=true and this.group='TIPE PEMBAYARAN'`
      },
    }" />
    </div>

    <div class="w-full !mt-3" v-if="data.tipe_pembayaran_deskripsi == 'TRANSFER'">
      <FieldPopup class="!mt-0" :bind="{ readonly: data.tipe_pembayaran_deskripsi !== 'TRANSFER' || !actionText }"
        :value="data.m_akun_bank_id" @input="(v) => data.m_akun_bank_id = v"
        :errorText="formErrors.m_akun_bank_id ? 'failed' : ''" :hints="formErrors.m_akun_bank_id" valueField="id"
        displayField="nama_coa" :api="{
      url: `${store.server.url_backend}/operation/m_coa`,
      headers: {
        'Content-Type': 'Application/json', 
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      params: {
        simplest: true,
        where: `kategori.deskripsi='MODAL'`,
         searchfield: `this.nama_coa, this.nomor`
      },
      onsuccess: (response) => {
        response.page = response.current_page;
        response.hasNext = response.has_next;
        return response;
      }
    }" placeholder="Pilih Akun Bank" label="Akun Bank" fa-icon="" :check="false" :columns="[
      {
        headerName: 'No',
        valueGetter: (p) => p.node.rowIndex + 1,
        width: 60,
        sortable: false,
        resizable: false,
        filter: false,
        cellClass: ['justify-center', 'bg-gray-50']
      },
      {
        flex: 1,
        field: 'nama_coa',
        headerName: 'Nama',
        cellClass: ['justify-center', 'border-r', '!border-gray-200'],
        sortable: true,
        resizable: true,
        filter: false,
      },
      {
        flex: 1,
        field: 'nomor',
        headerName: 'Nomor ID',
        cellClass: ['justify-center', 'border-r', '!border-gray-200'],
        sortable: true,
        resizable: true,
        filter: false,
      },
    ]" />
    </div>

    <div>
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/m_coa`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
            searchfield: 'this.nomor, this.nama_coa, kategori.deskripsi, this.jenis, this.id',
            where: `this.is_active = true AND kategori.deskripsi = 'MODAL'`
        },
        onsuccess(response) {
          response.page = response.current_page;
          response.hasNext = response.has_next;
          return response;
        }
      }" displayField="nama_coa" valueField="id" :bind="{ readonly: !actionText }" :value="data.m_akun_pembayaran_id"
        @input="(v)=>data.m_akun_pembayaran_id=v" @update:valueFull="(response)=>{
        $log(response);
      }" :errorText="formErrors.m_akun_pembayaran_id?'failed':''" class="w-full !mt-3"
        :hints="formErrors.m_akun_pembayaran_id" placeholder="Pilih Akun Pembayaran" :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: 'ColFilter',
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'nomor',
          headerName: 'No. COA',
          sortable: true, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'Nama COA',
          field: 'nama_coa',
          flex: 2,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
        {
          headerName: 'Kategori',
          field: 'kategori.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
        {
          headerName: 'Jenis',
          field: 'jenis.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: 'ColFilter',
        },
      ]" />
    </div>
    <!-- <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.no_reference"
        :errorText="formErrors.no_reference?'failed':''" @input="v=>data.no_reference=v"
        :hints="formErrors.no_reference" label="No. Reference" placeholder="No. Reference" :check="false" />
    </div> -->
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="data.total_amt"
        :errorText="formErrors.total_amt?'failed':''" @input="v=>data.total_amt=v" :hints="formErrors.total_amt"
        label="Total Amt" placeholder="Total Amt" :check="false" />
    </div>

    <div>
      <FieldSelect :bind="{ disabled: true, clearable: false }" class="w-full !mt-3" :value="data.tipe_bkk"
        @input="v=>data.tipe_bkk=v" :errorText="formErrors.tipe_bkk?'failed':''" :hints="formErrors.tipe_bkk"
        valueField="id" displayField="key" :options="['Buku Order','Non Buku Order']" placeholder="Tipe BKK"
        :check="false" fa-icon="sort-desc" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.status"
        :errorText="formErrors.status?'failed':''" @input="v=>data.status=v" :hints="formErrors.status" label="Status"
        placeholder="Status" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" type="textarea" :value="data.keterangan"
        :errorText="formErrors.keterangan?'failed':''" @input="v=>data.keterangan=v" :hints="formErrors.keterangan"
        label="Keterangan" placeholder="Keterangan" :check="false" />
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>
  <!-- <div class="grid grid-cols-4 place-content-center place-items-center w-[calc(80%)] min-w-220 items-center">
    <button v-for="(item, i) in coaList" :key="i" v-show="coaList.length > 0"
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_coa_id === item.id) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(item.id)">
        {{item.nama_coa}}
    </button>
  </div> -->
  <!-- <div class="grid grid-cols-4 place-content-center place-items-center w-[calc(80%)] min-w-220 items-center">
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
  </div> -->
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
            searchfield: 'this.nomor, this.nama_coa, kategori.deskripsi, this.jenis, this.id',
            where: `this.is_active=true`,
            //notin: `this.id: ${detailArr.map((det)=> (det.m_coa_id))}`
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
              response.page = response.current_page
              response.hasNext = response.has_next
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
          flex: 2
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
        <div v-show="actionText" class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
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
              Nomor Coa
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nama Coa
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nominal
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Detail
            </td>
            <td v-show="actionText"
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
                :hints="formErrors.nominal" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldX type="textarea" :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].keterangan"
                :errorText="formErrors.keterangan?'failed':''" @input="v=>detailArr[i].keterangan=v"
                :hints="formErrors.keterangan" :check="false" />
            </td>
            <td v-show="actionText" class="p-2 border border-[#CACACA]">
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
    <button v-show="(((actionText=='Edit' || actionText=='Create'|| actionText=='Copy') && (data.status=='DRAFT' || data.status=='REVISED')))" class="text-sm rounded py-2 px-2.5 text-white bg-purple-600 hover:bg-purple-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="sendApproval">
      <icon fa="location-arrow" />
      <span>Send Approval</span>
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