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
        <button @click="filterShowData('IN APPROVAL')" :class="filterButton === 'IN APPROVAL' ? 'bg-purple-600 text-white hover:bg-purple-600' 
          : 'border border-purple-600 text-purple-600 bg-white hover:bg-purple-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          IN APPROVAL
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('APPROVED')" :class="filterButton === 'APPROVED' ? 'bg-green-600 hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          APPROVED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('REVISED')" :class="filterButton === 'REVISED' ? 'bg-amber-600 hover:bg-amber-600' 
          : 'border border-amber-600 text-amber-600 bg-white hover:bg-amber-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          REVISED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('REJECTED')" :class="filterButton === 'REJECTED' ? 'bg-red-600 hover:bg-red-600' 
          : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          REJECTED
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
        <h1 class="text-20px font-bold">Form Transaksi Credit Note</h1>
        <p class="text-gray-100">Form untuk Transaksi Credit Note</p>
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
      <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.no_credit_note"
        :errorText="formErrors.no_credit_note?'failed':''" @input="v=>values.no_credit_note=v"
        :hints="formErrors.no_credit_note" placeholder="No Credit Note" label="No Credit Note" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.tanggal" type="date"
        :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v" :hints="formErrors.tanggal"
        placeholder="Tanggal" label="Tanggal" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, readonly: !actionText , clearable: false }"
        displayField="deskripsi" 
        @update:valueFull="(data)=>{
         values.supplier_id = null;
         values.customer_id = null;
         values.perkiraan_credit =  null;
         detailArr = [];
         subDetail = [];
         values.tipe_cn = data['deskripsi4'];
         {{$log(data,'data cn')}}
         //detailArr2 = [];
        }" valueField="id" :value="values.tipe_credit_note" @input="(v)=>values.tipe_credit_note=v"
        :errorText="formErrors.tipe_credit_note?'failed':''" :hints="formErrors.tipe_credit_note"
        placeholder="Tipe Credit Note" label="Tipe Credit Note" :check='false' :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              join:true,
              simplest:true,
              where:`this.is_active=true and this.group='TIPE CREDIT NOTE'`,
              selectfield: `this.id, this.deskripsi, this.deskripsi4`
            },
          }" />
    </div>

    <div class="w-full !mt-3">
      <!-- HUTANG  -->
      <FieldPopup v-show="values.tipe_cn === 'HUTANG'" class="!mt-0" displayField="nama" valueField="id"
        @update:valueFull="(data)=>{
         detailArr = [];
         subDetail = [];
         //detailArr2 = [];
         values.perkiraan_credit = null;
        }" :bind="{ readonly: !actionText }" :value="values.supplier_id" @input="(v)=>values.supplier_id=v"
        :errorText="formErrors.supplier_id?'failed':''" :hints="formErrors.supplier_id" placeholder="Pilih Supplier"
        label="Supplier" :check='false' :api="{
            url: `${store.server.url_backend}/operation/m_supplier`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              join:true,
              simplest:false,
              where: `this.is_active = true`,
              searchfield:'this.nama , this.alamat, this.negara, this.kode',
            },
            onsuccess:(response)=>{
              response.page = response.current_page
              response.hasNext = response.has_next
              return response
            }
          }" :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
             field: 'nama',
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

      <!-- PIUTANG / TAGIHAN -->
      <FieldPopup v-show="values.tipe_cn === 'PIUTANG'" class="!mt-0" displayField="nama_perusahaan" valueField="id"
        :bind="{ readonly: !actionText }" @update:valueFull="(data)=>{
         detailArr = [];
         subDetail = [];
         //detailArr2 = [];
         values.perkiraan_credit = null;
        }" :value="values.customer_id" @input="(v)=>values.customer_id=v"
        :errorText="formErrors.customer_id?'failed':''" :hints="formErrors.customer_id" placeholder="Pilih Customer"
        label="Customer" :check='false' :api="{
            url: `${store.server.url_backend}/operation/m_customer`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              searchfield:'this.nama_perusahaan , this.jenis_perusahaan, this.kode, this.alamat',
              scopes: 'CustomerActive'
            },
            onsuccess:(response)=>{
              {{$log(response,'siu')}}
              response.page = response.current_page
              response.hasNext = response.has_next
              return response
            }
          }" :columns="[{
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
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'nama_perusahaan',
            headerName: 'Nama Perusahaan',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'jenis_perusahaan',
            headerName: 'Jenis Perusahaan',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'alamat',
            headerName: 'Alamat Perusahaan',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          ]" />
    </div>

    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" displayField="nama_coa" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.perkiraan_credit" @input="(v)=>values.perkiraan_credit=v"
        :errorText="formErrors.perkiraan_credit?'failed':''" :hints="formErrors.perkiraan_credit"
        placeholder="Pilih Perkiraan Credit" label="Perkiraan Credit" :check='false' :api="{
            url: `${store.server.url_backend}/operation/m_coa`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              join:true,
              simplest:false,
              searchfield:'this.nama_coa , this.nomor',
              where:`this.is_active = true`
            },
            onsuccess:(response)=>{
              $log(response,'yoooo')
              response.page = response.current_page
              response.hasNext = response.has_next
              return response
            }
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

    <div class="w-full !mt-3 ">
      <FieldNumber class="!mt-0" :bind="{ readonly: true, disabled: true }" type="number" :value="totalCreditNote"
        @input="(v)=>values.total_credit_note=v" :errorText="formErrors.total_credit_note?'failed':''"
        :hints="formErrors.total_credit_note" :check="false" placeholder="Total Credit Note"
        label="Total Credit Note" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.catatan" type="textarea"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        placeholder="Catatan" label="Catatan" :check="false" />
    </div>

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ disabled: true , readonly: true }" :value="values.status"
        :errorText="formErrors.status?'failed':''" @input="v=>values.status=v" :hints="formErrors.status"
        placeholder="Status" label="Status" :check="false" />
    </div>
  </div>

  <hr>

  <!-- detail -->
  <!-- START TABLE -->
  <!-- BUTA V-TAB DISINI  -->
  <div class="p-4 space-x-5 text-sm text-white">
    <button v-if="values.tipe_credit_note == 314"
    :class="['p-2 duration-300', activeTab === 1 ? 'bg-blue-700 rounded-xl' : 'bg-blue-500 hover:bg-blue-300 rounded-2xl']"
    @click="activeTab = 1"
  >
    Detail Invoice
  </button>

    <button v-else
    :class="['p-2 duration-300', activeTab === 1 ? 'bg-blue-700 rounded-xl' : 'bg-blue-500 hover:bg-blue-300 rounded-2xl']"
    @click="activeTab = 1"
  >
    Detail Tagihan
  </button>

    <button
    :class="['p-2 duration-300', activeTab === 2 ? 'bg-blue-700 rounded-xl' : 'bg-blue-500 hover:bg-blue-300 rounded-2xl']"
    @click="activeTab = 2"
  >
    Sub Detail Note
  </button>
  </div>

  <!-- TAB 1 -->

  <div v-show="activeTab === 1">
    <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2">

      <!-- PIUTANG / TAGIHAN -->
      <ButtonMultiSelect v-show="values.tipe_cn === 'PIUTANG' && actionText" title="Add to list" @add="onDetailAdd" :api="{
            url: `${store.server.url_backend}/operation/t_tagihan`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { 
              where: `this.customer = ${values.customer_id} and this.status = 'POST'` , 
              searchfield:'this.no_tagihan, this.tgl, this.grand_total_amount',
              notin: detailArr.length>0?`this.id:${detailArr.map(dt=>dt.t_tagihan_id).join(',')}`:null
            },
            onsuccess:(response)=>{
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
            headerName:'No. Tagihan',
            sortable: false, resizable: true, filter: 'ColFilter',
            field: 'no_tagihan',
            cellClass: ['justify-center','!border-gray-200']
          },
          {
            flex: 1,
            headerName:'Tgl. Tagihan',
            sortable: false, resizable: true, filter: 'ColFilter',
            field: 'tgl',
            cellClass: ['justify-center','!border-gray-200']
          },
          {
            flex: 1,
            headerName:'Grand Total',
            sortable: false, resizable: true, filter: 'ColFilter',
            field: 'grand_total_amount',
            cellClass: ['justify-center','!border-gray-200']
          }
          ]">
        <div class="bg-blue-600 text-white font-semibold 
            hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5 mt-3">
          <icon fa="plus" size="sm mr-0.5" /> Add to list
        </div>
      </ButtonMultiSelect>

      <!-- PURCHASE INVOICE / HUTANG -->
      <ButtonMultiSelect v-show="values.tipe_cn == 'HUTANG' && actionText" title="Add to list" @add="onDetailAdd" :api="{
            url: `${store.server.url_backend}/operation/t_purchase_invoice`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { 
              //scopes: 'Detail',
              where: `this.m_supplier_id = ${values.supplier_id}`, //belum di where dimana status APPROVED
              searchfield:'this.no_pi, this.tanggal, this.grand_total',
              notin: detailArr.length>0?`this.id:${detailArr.map(dt=>dt.t_purchase_invoice_id).join(',')}`:null
            },
            onsuccess:(response)=>{
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
            headerName:'Nilai Hutang',
            sortable: false, resizable: true, filter: 'ColFilter',
            field: 'grand_total',
            cellClass: ['justify-center','!border-gray-200']
          }
          ]">
        <div class="bg-blue-600 text-white font-semibold 
            hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5 mt-3">
          <icon fa="plus" size="sm mr-0.5" /> Add to list
        </div>
      </ButtonMultiSelect>

    </div>
    <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
      <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
        <!-- HUTANG  -->
        <table v-if="values.tipe_cn == 'HUTANG'"
          class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[2%] border bg-[#f8f8f8] border-[#CACACA]">
                No.</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                No. Invoice</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Tgl. Invoice</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Inv. Amt</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Inv. Paid</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Sisa Inv.</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Currency</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Rate</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Sub Total Credit</td>
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
              <td class="p-2 text-center border border-[#CACACA]">{{ index+1 }}</td>

              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX class="!mt-0" :bind="{ readonly: true }" :value="item.no_pi" @input="(v)=>item.no_pi=v"
                  :errorText="formErrors.no_pi?'failed':''" :hints="formErrors.no_pi" :check="false" />
              </td>

              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX class="!mt-0" :bind="{ readonly: true }" :value="item.tgl_pi" @input="(v)=>item.tgl_pi=v"
                  :errorText="formErrors.tgl_pi?'failed':''" :hints="formErrors.tgl_pi" :check="false" />
              </td>

              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber class="!mt-0" :bind="{ readonly: true }" :value="item.nilai_hutang"
                  @input="(v)=>item.nilai_hutang=v" :errorText="formErrors.nilai_hutang?'failed':''"
                  :hints="formErrors.nilai_hutang" :check="false" />
              </td>

              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber class="!mt-0" :bind="{ readonly: true }" :value="item.bayar" @input="(v)=>item.bayar=v"
                  :errorText="formErrors.bayar?'failed':''" :hints="formErrors.bayar" :check="false" />
              </td>

              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber class="!mt-0" :bind="{ readonly: true }" :value="item.sisa_hutang"
                  @input="(v)=>item.sisa_hutang=v" :errorText="formErrors.sisa_hutang?'failed':''"
                  :hints="formErrors.sisa_hutang" :check="false" />
              </td>

              <td class="p-2 border border-[#CACACA] text-center max-w-52">
                <FieldX :bind="{ readonly: true }" :value="item.currency" :errorText="formErrors.currency?'failed':''"
                  @input="v=>item.currency=v" :hints="formErrors.currency" placeholder="Catatan" label=""
                  :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: true }" :value="item.rate" @input="(v)=>item.rate=v"
                  :errorText="formErrors.rate?'failed':''" :hints="formErrors.rate" placeholder="Rate" label=""
                  :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: true, disabled: true }" :value="subTotal2(item)"
                  @input="item.sub_total_amount" :errorText="formErrors.sub_total_amount?'failed':''"
                  :hints="formErrors.sub_total_amount" placeholder="Sub Total Credit" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA] text-center max-w-52 truncate">
                <FieldX :bind="{ readonly: true }" :value="item.catatan" :errorText="formErrors.catatan?'failed':''"
                  @input="v=>item.catatan=v" :hints="formErrors.catatan" placeholder="Catatan" label=""
                  :check="false" />
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

        <!-- TAGIHAN  -->
        <table v-else class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[2%] border bg-[#f8f8f8] border-[#CACACA]">
                No.</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                No. Tagihan</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Tgl. Tagihan</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Tagihan Amt</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Tagihan Paid</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Sisa Tagihan</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Currency</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Rate</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Sub Total Credit</td>
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
              <td class="p-2 text-center border border-[#CACACA]">{{ index+1 }}</td>

              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX class="!mt-0" :bind="{ readonly: true }" :value="item.no_pi" @input="(v)=>item.no_pi=v"
                  :errorText="formErrors.no_pi?'failed':''" :hints="formErrors.no_pi" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX class="!mt-0" :bind="{ readonly: true }" :value="item.tgl_pi" @input="(v)=>item.tgl_pi=v"
                  :errorText="formErrors.tgl_pi?'failed':''" :hints="formErrors.tgl_pi" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber class="!mt-0" :bind="{ readonly: true }" :value="item.nilai_hutang"
                  @input="(v)=>item.nilai_hutang=v" :errorText="formErrors.nilai_hutang?'failed':''"
                  :hints="formErrors.nilai_hutang" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber class="!mt-0" :bind="{ readonly: true }" :value="item.bayar" @input="(v)=>item.bayar=v"
                  :errorText="formErrors.bayar?'failed':''" :hints="formErrors.bayar" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber class="!mt-0" :bind="{ readonly: true }" :value="item.sisa_hutang"
                  @input="(v)=>item.sisa_hutang=v" :errorText="formErrors.sisa_hutang?'failed':''"
                  :hints="formErrors.sisa_hutang" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA] text-center max-w-52">
                <FieldX :bind="{ readonly: true }" :value="item.currency" :errorText="formErrors.currency?'failed':''"
                  @input="v=>item.currency=v" :hints="formErrors.currency" placeholder="Catatan" label=""
                  :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: true }" :value="item.rate" @input="(v)=>item.rate=v"
                  :errorText="formErrors.rate?'failed':''" :hints="formErrors.rate" placeholder="Rate" label=""
                  :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber class="!mt-0" :bind="{ readonly: true, disabled: true }" :value="subTotal2(item)"
                  @input="item.sub_total_amount" :errorText="formErrors.sub_total_amount?'failed':''"
                  :hints="formErrors.sub_total_amount" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA] text-center max-w-52 truncate">
                <FieldX :bind="{ readonly: true }" :value="item.catatan" :errorText="formErrors.catatan?'failed':''"
                  @input="v=>item.catatan=v" :hints="formErrors.catatan" placeholder="Catatan" label=""
                  :check="false" />
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



  <!-- TAB 2 Content -->
  <div class="p-4" v-show="activeTab === 2">

    <!-- HUTANG  -->
    <div v-show="values.tipe_cn == 'HUTANG'" class="p-4 flex flex-row justify-center items-center w-[35%]">
      <label class="font-semibold text-sm w-1/2">No Invoice</label>
      <FieldSelect
        :bind="{ disabled: !actionText, clearable:false }"
        :value="values.ref_det_id" @input="v=>values.ref_det_id=v"
        :errorText="formErrors.ref_det_id?'failed':''" 
        :hints="formErrors.ref_det_id"
        valueField="id" displayField="no_pi"
        :options="compDetailArr"
        placeholder="Pilih No Invoice" label="" :check="false"
      />
      <!-- <FieldPopup class="!mt-0" displayField="t_purchase_invoice.no_pi" valueField="id"
        :bind="{ readonly: !actionText }" :value="values.no_tagihan" @input="(v)=>values.no_tagihan=v"
        :errorText="formErrors.no_tagihan?'failed':''" :hints="formErrors.no_tagihan" placeholder="No Invoice" label=""
        @update:valueFull="(dt)=>{
          values.invoice_id = dt['t_purchase_invoice.id']
        }"
        :check='false' :api="{
            url: `${store.server.url_backend}/operation/t_credit_note_d`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              join:true,
              simplest:false,
              searchfield:'this.nama , this.alamat, this.negara, this.kode',
              scopes:'GetData',
              id_param:`${route.params.id}`,
              order_by: 'no_urut',
		          order_type: 'ASC'
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
             field: 't_purchase_invoice.no_pi',
            headerName: 'No Invoice',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 't_purchase_invoice.tanggal',
            headerName: 'Tanggal',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },

          ]" /> -->
    </div>

    <!-- PIUTANG / TAGIHAN  -->
    <div v-show="values.tipe_cn === 'PIUTANG'" class="p-4 flex flex-row  justify-center items-center w-[35%]">
      <label class="font-semibold text-sm w-1/2">No Tagihan</label>
      <FieldSelect
        :bind="{ disabled: !actionText, clearable:false }"
        :value="values.ref_det_id" @input="v=>values.ref_det_id=v"
        :errorText="formErrors.ref_det_id?'failed':''" 
        :hints="formErrors.ref_det_id"
        valueField="id" displayField="no_pi"
        :options="compDetailArr"
        placeholder="Pilih No Tagihan" label="" :check="false"
      />
      
      <!-- <FieldPopup class="!mt-0" displayField="t_tagihan.no_tagihan" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.no_tagihan" @input="(v)=>values.no_tagihan=v" :errorText="formErrors.no_tagihan?'failed':''"
        :hints="formErrors.no_tagihan" placeholder="No Tagihan" label="" :check='false' 
        @update:valueFull="(dt)=>{
          values.tagihan_id = dt['t_tagihan.id']
        }" :api="{
            url: `${store.server.url_backend}/operation/t_credit_note_d`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              join:true,
              simplest:false,
              searchfield:'this.nama , this.alamat, this.negara, this.kode',
              scopes:'GetData',
              id_param:`${route.params.id}`,
              order_by: 'no_urut',
		          order_type: 'ASC'
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
             field: 't_tagihan.no_tagihan',
            headerName: 'No Tagihan',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 't_tagihan.tgl',
            headerName: 'Tanggal',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },

          ]" /> -->
    </div>
    <div class="">
      <ButtonMultiSelect v-if = "actionText" title="Add to list" @add="onAddSubDetail" 
      :api="{
            url: `${store.server.url_backend}/operation/m_coa`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { 
              where:`this.is_active = true and this.debit_kredit = 'KREDIT'`
            },
            onsuccess:(response)=>{
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
            headerName:'Nama CoA',
            sortable: false, resizable: true, filter: 'ColFilter',
            field: 'nama_coa',
            cellClass: ['justify-center','!border-gray-200']
          },
          {
            flex: 1,
            headerName:'Debit/Kredit',
            sortable: false, resizable: true, filter: 'ColFilter',
            field: 'debit_kredit',
            cellClass: ['justify-center','!border-gray-200']
          },
          {
            flex: 1,
            headerName:'Tipe Perkiraan Credit',
            sortable: false, resizable: true, filter: 'ColFilter',
            field: 'tipe_perkiraan.deskripsi',
            cellClass: ['justify-center','!border-gray-200']
          },

          ]">
        <div class="bg-blue-600 text-white font-semibold 
            hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5 mt-3">
          <icon fa="plus" size="sm mr-0.5" /> Add to list
        </div>
      </ButtonMultiSelect>

    </div>
    <div class="">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
      <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
        <table class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[2%] border bg-[#f8f8f8] border-[#CACACA]">
                No.</td>
              <td
                v-if = "values.tipe_cn === 'PIUTANG'" class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                No. Tagihan</td>
              <td
                v-if = "values.tipe_cn === 'HUTANG'" class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                No. Invoice</td>  
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Nama CoA</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Perkiraan Kredit</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Amount</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Catatan</td>

              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[2%] border bg-[#f8f8f8] border-[#CACACA]">
                Action</td>
            </tr>
          </thead>
          <tbody>
            <tr v-if="subDetail.length === 0" class="text-center">
              <td colspan="9" class="py-[20px] justify-center items-center">No data to show</td>
            </tr>
            <tr v-else v-for="(item, index) in subDetail" :key="index" class="border">
              <td class="p-2 text-center border border-[#CACACA]">{{ index+1}}</td>
              <td class="p-2 text-center border border-[#CACACA]">
                <!-- PIUTANG / TAGIHAN  -->
                <!-- {{item.no_pi}} -->
                <FieldSelect v-if = "values.tipe_cn == 'PIUTANG'"
                class="!mt-0" :bind="{ disabled: true, clearable:false, readonly: true }"
                  :value="item.t_tagihan_id" @input="v=>item.t_tagihan_id=v"
                  :errorText="formErrors.t_tagihan_id?'failed':''" :hints="formErrors.t_tagihan_id" valueField="id"
                  displayField="no_tagihan" :api="{
                        url: `${store.server.url_backend}/operation/t_tagihan`,
                        headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                        params: {
                        },
                    }" placeholder="" label="" :check="false" />
                
                 <!-- HUTANG / INVOICE -->
                <!-- {{item.no_pi}} -->
                 <FieldSelect v-if = "values.tipe_cn == 'HUTANG'"
                  class="!mt-0" :bind="{ disabled: true, clearable:false, readonly: true }"
                  :value="item.t_purchase_invoice_id" @input="v=>item.t_purchase_invoice_id=v"
                  :errorText="formErrors.t_purchase_invoice_id?'failed':''" :hints="formErrors.t_purchase_invoice_id" valueField="id"
                  displayField="no_pi" :api="{
                        url: `${store.server.url_backend}/operation/t_purchase_invoice`,
                        headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                        params: {
                        },
                    }" placeholder="" label="" :check="false" />

              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <!-- {{item.nama_coa}} -->
                <FieldSelect class="!mt-0" :bind="{ disabled: true, clearable:false, readonly: true }"
                  :value="item.m_coa_id" @input="v=>item.m_coa_id=v" :errorText="formErrors.m_coa_id?'failed':''"
                  :hints="formErrors.m_coa_id" valueField="id" displayField="nama_coa" :api="{
                        url: `${store.server.url_backend}/operation/m_coa`,
                        headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                        params: {
                          simplest:true,
                          transform:false,
                          join:false
                        }
                    }" placeholder="" label="" :check="false" />

              </td>

              <td class="p-2 text-center border border-[#CACACA]">
                <FieldSelect class="!mt-0" :bind="{ disabled: true, clearable:false, readonly: true }"
                  :value="item.tipe_perkiraan" @input="v=>item.tipe_perkiraan=v"
                  :errorText="formErrors.tipe_perkiraan?'failed':''" :hints="formErrors.tipe_perkiraan"
                  valueField="id" displayField="deskripsi" :api="{
                        url: `${store.server.url_backend}/operation/m_general`,
                        headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                        params: {
                          simplest:true,
                          transform:false,
                          join:false
                        }
                    }" placeholder="" label="" :check="false" />
                <!-- {{item.perkiraan_credit}} -->
              </td>

              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber class="!mt-0" :bind="{ readonly: !actionText, disabled: !actionText }" :value="item.amount"
                  @input="(v)=>item.amount=v" :errorText="formErrors.amount?'failed':''" :hints="formErrors.amount"
                  :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA] text-center max-w-52 truncate">
                <FieldX :bind="{ readonly: !actionText }" :value="item.catatan"
                  :errorText="formErrors.catatan?'failed':''" @input="v=>item.catatan=v" :hints="formErrors.catatan"
                  placeholder="Catatan" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA] text-center">
                <button type="button" @click="removeDetail2(index)" :disabled="!actionText">
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
  <!-- END TABLE -->

  <div class="flex flex-row items-center justify-end space-x-2 p-2">
    <i class="text-gray-500 text-[12px]" v-show="actionText">Pastikan Data Sudah Benar !</i>
    <button
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        @click="onBack"
    >
        Kembali
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