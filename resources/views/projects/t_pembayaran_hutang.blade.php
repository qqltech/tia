@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-green-600 hover:blue-600-600' 
          : 'border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          DRAFT
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-amber-600 hover:bg-amber-600' 
          : 'border border-amber-600 text-amber-600 bg-white hover:bg-amber-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          POST
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('IN APPROVAL')" :class="filterButton === 'IN APPROVAL' ? 'bg-sky-600 text-white hover:bg-sky-600' 
          : 'border border-sky-600 text-sky-600 bg-white hover:bg-sky-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          IN APPROVAL
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('APPROVED')" :class="filterButton === 'APPROVED' ? 'bg-blue-600 hover:bg-blue-600' 
          : 'border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          APPROVED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('COMPLETE')" :class="filterButton === 'COMPLETE' ? 'bg-green-600 hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          COMPLETE
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('REJECTED')" :class="filterButton === 'REJECTED' ? 'bg-red-600 hover:bg-red-600' 
          : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          REJECTED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('REVISED')" :class="filterButton === 'REVISED' ? 'bg-red-600 hover:bg-red-600' 
          : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          REVISED
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
        <h1 class="text-20px font-bold">Form Transaksi Pembayaran Hutang</h1>
        <p class="text-gray-100">Form untuk Transaksi Pembayaran Hutang</p>
      </div>
    </div>
  </div>
  <!-- HEADER END -->

  <!-- FORM START -->
  <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row p-4 gap-3">
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>values.no_draft=v" :hints="formErrors.no_draft"
        placeholder="No Draft" label="No Draft" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.no_pembayaran"
        :errorText="formErrors.no_pembayaran?'failed':''" @input="v=>values.no_pembayaran=v"
        :hints="formErrors.no_pembayaran" placeholder="No Pembayaran" label="No Pembayaran" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.tanggal" type="date"
        :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v" :hints="formErrors.tanggal"
        placeholder="Tanggal" label="Tanggal" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.tanggal_pembayaran" type="date"
        :errorText="formErrors.tanggal_pembayaran?'failed':''" @input="v=>values.tanggal_pembayaran=v"
        :hints="formErrors.tanggal_pembayaran" placeholder="Tanggal Pembayaran" label="Tanggal Pembayaran"
        :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, readonly: !actionText }" displayField="deskripsi"
        valueField="id" :value="values.tipe_pembayaran_id" @input="(v) => values.tipe_pembayaran_id = v"
        :errorText="formErrors.tipe_pembayaran_id ? 'failed' : ''" :hints="formErrors.tipe_pembayaran_id"
        placeholder="Tipe Pembayaran" label="Tipe Pembayaran" :check="false" @update:valueFull="(response)=>{
          $log(response)
          values.tipe_pembayaran_deskripsi = response.deskripsi
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

    <div class="w-full !mt-3" v-if="values.tipe_pembayaran_deskripsi === 'TRANSFER'">
      <FieldPopup class="!mt-0" :bind="{ readonly: values.tipe_pembayaran_deskripsi !== 'TRANSFER' || !actionText }"
        :value="values.m_akun_bank_id" @input="(v) => values.m_akun_bank_id = v"
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


    <div class="w-full !mt-3 hidden">
      <FieldNumber class="!mt-0" :bind="{ readonly: true}" type="number" :value="values.total_amt"
        :errorText="formErrors.total_amt?'failed':''" @input="v=>values.total_amt=v" :hints="formErrors.total_amt"
        placeholder="Total Amount" label="Total Amount" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" displayField="nama_coa" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.m_akun_pembayaran_id" @input="(v)=>values.m_akun_pembayaran_id=v"
        :errorText="formErrors.m_akun_pembayaran_id?'failed':''" :hints="formErrors.m_akun_pembayaran_id"
        placeholder="Pilih Akun Pembayaran" label="Akun Pembayaran" :check='false' :api="{
            url: `${store.server.url_backend}/operation/m_coa`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              join:true,
              simplest:false,
              searchfield:'this.nama_coa , this.nomor',
            },
          }" :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
             field: 'nama_coa',
            headerName: 'Nama',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'nomor',
            headerName: 'Nomor ID',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          ]" />
    </div>


    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" displayField="no_rph" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.t_rencana_pembayaran_hutang_id" @input="(v)=>values.t_rencana_pembayaran_hutang_id=v"
        :errorText="formErrors.t_rencana_pembayaran_hutang_id?'failed':''"
        :hints="formErrors.t_rencana_pembayaran_hutang_id" placeholder="Pilih Rencana Pembayaran Hutang"
        label="Rencana Pembayaran Hutang" :check='false' @update:valueFull="rencana_PH" :api="{
            url: `${store.server.url_backend}/operation/t_rencana_pembayaran_hutang`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              join:true,
              simplest:false,
              searchfield:'this.no_rph , this.no_draft , this.tgl , this.total_pi , this.total_bayar',
            },
          }" :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
             field: 'no_draft',
            headerName: 'No Draft',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'no_rph',
            headerName: 'No Rph',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'tgl',
            headerName: 'Tanggal',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          
          {
            flex: 1,
             field: 'total_pi',
            headerName: 'Total Purchase Invoice',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'total_bayar',
            headerName: 'Total Bayar',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          ]" />
    </div>

    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" displayField="nama_supplier" valueField="m_supplier_id"
        :bind="{ readonly: !actionText}" :value="values.supplier_id"
        @input="(v)=>values.supplier_id=v" :errorText="formErrors.supplier_id?'failed':''"
        :hints="formErrors.supplier_id" placeholder="Pilih Supplier" label="Supplier" :check='false' :api="{
            url: `${store.server.url_backend}/operation/m_supplier`,
            headers: {  
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              join:true,
              simplest:false,
              //where:`this.t_rencana_pembayaran_hutang_id = ${values.t_rencana_pembayaran_hutang_id}`,
              //scopes: 'GetRph',
              //rp_hutang_id: `${values.t_rencana_pembayaran_hutang_id}`,
              searchfield:'this.nama_coa , this.nomor',
            },
          }" :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'nama_supplier',
            headerName: 'Nama Supplier',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'alamat',
            headerName: 'Alamat',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'negara',
            headerName: 'Negara',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'kode',
            headerName: 'Kode',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          ]" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.keterangan" type="textarea"
        :errorText="formErrors.keterangan?'failed':''" @input="v=>values.keterangan=v" :hints="formErrors.keterangan"
        placeholder="Tuliskan Keteragan" label="Keteragan" :check="false" />
    </div>
    <div> </div>
    <div> </div>

    <div class="w-full !mt-3">
      <input
      type="checkbox"
      id="include_pph"
      v-model="values.include_pph"
      true-value="1"
      false-value="0"
      ref="includePPH"
      :disabled="!actionText"
      :class="!actionText ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'"
    />
      <label for="include_pph" class="ml-2">Include PPH</label>
    </div>

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ disabled: true , readonly: true }" :value="values.status"
        :errorText="formErrors.status?'failed':''" @input="v=>values.status=v" :hints="formErrors.status"
        placeholder="Status" label="Status" :check="false" />
    </div>


  </div>

  <div class="justify-items-start text-xl ml-10">
    <h1 class="font-semibold italic">Total Dibayar = <soan class="text-red-500">{{ formatRupiah(values.total_amt)
        }}</span> </h1>
  </div>

  <!-- FORM END -->
  <div class="flex flex-row items-center justify-end space-x-2 p-2">
    <i class="text-gray-500 text-[12px]" v-show="actionText">Pastikan Data Sudah Benar !</i>
    <button
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click="onReset(true)"
    >
        <icon fa="times" />
        Reset
    </button>

    <button
        class="bg-yellow-600 text-white font-semibold hover:bg-yellow-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click="onSave('POST')"
    >
        <icon fa="save" />
        Simpan Dan Post
    </button>

    <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText && values.status !== 'REVISED'"
        @click="onSave('DRAFT')"
    >
        <icon fa="save" />
        Simpan
    </button>
  </div>

  <hr>
  <!-- TAB DETAIL -->
  <div class="flex">
    <button
                  class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
                  :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 0}"
                  @click="activeTabIndex = 0"
                >
                  Detail PI
        </button>
    <button
                  class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
                  :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 1}"
                  @click="activeTabIndex = 1"
                >
                  Detail RPH
        </button>
  </div>

  <!-- TAB 1 -->
  <div class="p-4 " v-if="activeTabIndex === 0">
    <div class="border border-2 border-dashed p-4 rounded-2xl">
      <div class="<md:col-span-1 col-span-3 grid <md:grid-cols-1 grid-cols-3 gap-2">
        <ButtonMultiSelect title="Add to list" @add="onDetailAdd" :api="{
                  url: `${store.server.url_backend}/operation/t_purchase_invoice`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: { 
                    scopes: 'Detail',
                    where: `this.m_supplier_id = ${values.supplier_id ?? 0}  `, 
                    searchfield:'this.no_pi , this.tanggal , t_lpb.tanggal_sj_supplier , this.grand_total',
                  },
                  onsuccess:(response)=>{
                    $log('dataLpb',response)
                    response.data = [...response.data].map((dt)=>{
                      return dt
                    })
                    response.page = response.current_page
                    response.hasNext = response.has_next
                    return response
                  }
                }" :columns="[{
                  checkboxSelection: true,
                  headerCheckboxSelection: true,
                  headerName: 'No',
                  valueGetter:p=>'',
                  width:60,
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50', '!border-gray-200']
                },
                {
                  flex: 1,
                  headerName:'No. Purchase Invoice',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  field: 'no_pi',
                  cellClass: ['justify-center','!border-gray-200']
                },
                {
                  flex: 1,
                  headerName:'Tgl. Purchase Invoice',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  field: 'tanggal',
                  cellClass: ['justify-center','!border-gray-200']
                },
                {
                  flex: 1,
                  field: 't_lpb.tanggal_sj_supplier',
                  headerName: 'Tanggal Jatuh Tempo',
                  cellClass: ['justify-center', 'border-r', '!border-gray-200',],
                  sortable: true,
                  resizable: true, 
                  filter: false,
                },
                {
                  flex: 1,
                  headerName:'Nilai Hutang',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  field: 'grand_total',
                  cellClass: ['justify-center','!border-gray-200']
                }
                ]">
          <div
            class="bg-blue-600 text-white font-semibold 
                  hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5 mt-3">
            <icon fa="plus" size="sm mr-0.5" /> Add to list
          </div>
        </ButtonMultiSelect>

      </div>
      <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
          <table class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
            <thead>
              <tr class="border">
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[2%] border bg-[#f8f8f8] border-[#CACACA]">
                  No.</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  No. PI</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Tgl. PI</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Tgl. JT</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Nilai Hutang</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Dibayar</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Sisa Hutang</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Total Bayar</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Catatan</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[2%] border bg-[#f8f8f8] border-[#CACACA]">
                  Action</td>
              </tr>
            </thead>
            <tbody>
              <tr v-if="detailArr.length === 0" class="text-center">
                <td colspan="9" class="py-[20px] justify-center items-center">No data to show</td>
              </tr>
              <tr v-else v-for="(item, index) in detailArr" :key="index" class="border">
                <td class="p-2 text-center border border-[#CACACA]">{{ index + 1 }}</td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldX class="!mt-0" :bind="{ readonly: true }" :value="item.no_pi" @input="(v)=>item.no_pi=v"
                    :errorText="formErrors.no_pi?'failed':''" :hints="formErrors.no_pi" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldX class="!mt-0" :bind="{ readonly: true }" :value="item.tgl_pi" @input="(v)=>item.tgl_pi=v"
                    :errorText="formErrors.tgl_pi?'failed':''" :hints="formErrors.tgl_pi" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldX class="!mt-0" :bind="{ readonly: true }" :value="item.tgl_jt" @input="(v)=>item.tgl_jt=v"
                    :errorText="formErrors.tgl_jt?'failed':''" :hints="formErrors.tgl_jt" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldNumber class="!mt-0" :bind="{ readonly: true }" :value="item.nilai_hutang"
                    @input="(v)=>item.nilai_hutang=v" :errorText="formErrors.nilai_hutang?'failed':''"
                    :hints="formErrors.nilai_hutang" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldNumber class="!mt-0" :bind="{ readonly: !actionText }" :value="item.bayar"
                    @input="(v)=>item.bayar=v" :errorText="formErrors.bayar?'failed':''" :hints="formErrors.bayar"
                    :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldNumber class="!mt-0" :bind="{ readonly: true }" :value="item.sisa_hutang"
                    @input="(v)=>item.sisa_hutang=v" :errorText="formErrors.sisa_hutang?'failed':''"
                    :hints="formErrors.sisa_hutang" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldNumber :bind="{ readonly: true }" :value="item.total_bayar" @input="(v)=>item.total_bayar=v"
                    :errorText="formErrors.total_bayar?'failed':''" :hints="formErrors.total_bayar"
                    placeholder="Total Bayar" label="" :check="false" />
                </td>
                <td class="p-2 border border-[#CACACA] text-center max-w-52 truncate">
                  <FieldX :bind="{ readonly: !actionText }" :value="item.keterangan"
                    :errorText="formErrors.keterangan?'failed':''" @input="v=>item.keterangan=v"
                    :hints="formErrors.keterangan" placeholder="Catatan" label="" :check="false" />
                </td>
                <td class="p-2 border border-[#CACACA] text-center">
                  <button type="button" @click="removeDetail(index)" :disabled="!actionText">
                        <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                        </svg>
                      </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- TAB 2 -->
  <div class="p-4 " v-if="activeTabIndex === 1">
    <div class="border border-2 border-dashed p-4 rounded-2xl">
      <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
          <table class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
            <thead>
              <tr class="border">
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[2%] border bg-[#f8f8f8] border-[#CACACA]">
                  No.</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  No.Referensi</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Supplier</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Tgl.JT</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Jumlah </td>
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
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[2%] border bg-[#f8f8f8] border-[#CACACA]">
                  Action</td>
              </tr>
            </thead>
            <tbody>
              <tr v-if="detailArr1.length === 0" class="text-center">
                <td colspan="12" class="py-[20px] justify-center items-center">No data to show</td>
              </tr>
              <tr v-else v-for="(item, index) in detailArr1" :key="index" class="border">
                <td class="p-2 text-center border border-[#CACACA]">{{ index + 1 }}</td>

                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldX class="!mt-0" :bind="{ readonly: true }" :value="item.no_referensi"
                    @input="(v)=>item.no_referensi=v" :errorText="formErrors.no_referensi?'failed':''"
                    :hints="formErrors.no_referensi" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldX class="!mt-0" :bind="{ readonly: true }" :value="item.supplier" @input="(v)=>item.supplier=v"
                    :errorText="formErrors.supplier?'failed':''" :hints="formErrors.supplier" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldX class="!mt-0" :bind="{ readonly: true }" :value="item.tgl_jt" @input="(v)=>item.tgl_jt=v"
                    :errorText="formErrors.tgl_jt?'failed':''" :hints="formErrors.tgl_jt" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldNumber class="!mt-0" :bind="{ readonly: true }" :value="item.jumlah" @input="(v)=>item.jumlah=v"
                    :errorText="formErrors.jumlah?'failed':''" :hints="formErrors.jumlah" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldNumber class="!mt-0" :bind="{ readonly: true}" :value="item.jumlah_bayar"
                    @input="(v)=>item.jumlah_bayar=v" :errorText="formErrors.jumlah_bayar?'failed':''"
                    :hints="formErrors.jumlah_bayar" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldX class="!mt-0" :bind="{ readonly: true }" :value="item.keterangan"
                    @input="(v)=>item.keterangan=v" :errorText="formErrors.keterangan?'failed':''"
                    :hints="formErrors.keterangan" placeholder="Keteragan" :check="false" />
                </td>
                <td class="p-2 border border-[#CACACA] text-center max-w-52 truncate">
                  <FieldX :bind="{ readonly: true}" :value="item.tgl_realisasi"
                    :errorText="formErrors.tgl_realisasi?'failed':''" @input="v=>item.tgl_realisasi=v"
                    :hints="formErrors.tgl_realisasi" placeholder="Tanggal Realisasi" label="" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldNumber :bind="{ readonly: true }" :value="item.jumlah_realisasi"
                    @input="(v)=>item.jumlah_realisasi=v" :errorText="formErrors.jumlah_realisasi?'failed':''"
                    :hints="formErrors.jumlah_realisasi" placeholder="Jumlah Realisasi" label="" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldX :bind="{ readonly: true }" :value="item.cara_bayar" @input="(v)=>item.cara_bayar=v"
                    :errorText="formErrors.cara_bayar?'failed':''" :hints="formErrors.cara_bayar"
                    placeholder="Cara Bayar" label="" :check="false" />
                </td>
                <td class="p-2 text-center border border-[#CACACA]">
                  <FieldX :bind="{ readonly: true }" :value="item.status" @input="(v)=>item.status=v"
                    :errorText="formErrors.status?'failed':''" :hints="formErrors.status" placeholder="Status" label=""
                    :check="false" />
                </td>

                <td class="p-2 border border-[#CACACA] text-center">
                  <button type="button" @click="hapusDetail(index)" :disabled="!actionText">
                        <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                        </svg>
                      </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- END TABLE -->

  <hr v-show="is_approval" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="is_approval">
    <i class="text-gray-500 text-[12px] mr-4">Tekan CTRL + S untuk shortcut Save Data</i>
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