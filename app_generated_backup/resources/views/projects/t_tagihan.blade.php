<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center px-2.5 py-1">
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-green-600 text-white hover:bg-green-600' 
                        : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
                        class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
                    DRAFT
                </button>
        <div class="flex my-auto h-4 w-px bg-[#6E91D1]"></div>
        <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-yellow-600 text-white hover:bg-yellow-600' 
                        : 'border border-yellow-600 text-yellow-600 bg-white hover:bg-yellow-600 hover:text-white'"
                        class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
                    POST
                </button>
      </div>
    </div>
    <div>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px]">
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
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Tagihan</h1>
        <p class="text-gray-100">Transaksi Tagihan</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->

    <!-- No. Draft Coloumn -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3 w-full" :value="values.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>values.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft Coloumn" placeholder="No. Draft Coloumn" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3 w-full" :value="values.no_tagihan"
        :errorText="formErrors.no_tagihan?'failed':''" @input="v=>values.no_tagihan=v" :hints="formErrors.no_tagihan"
        label="No.Tagihan" placeholder="No.Tagihan" :check="false" />
    </div>

    <!-- Date Coloumn -->

    <div>
      <FieldX :bind="{ disabled: true, readonly: true }" class="w-full !mt-3" :value="values.tgl"
        :errorText="formErrors.tgl?'failed':''" @input="updateDate" :hints="formErrors.tgl" :check="false"
        label="Tanggal" placeholder="Pilih Tanggal" />
    </div>

    <!-- Tabel POP UP SEARCH -->
    <!-- No. Buku Order -->
    <div>
      <FieldPopup label="No. Buku Order" :bind="{ readonly: !actionText , clearable: true }" class="w-full !mt-3"
        valueField="id" displayField="no_buku_order" :value="values.no_buku_order" @input="(v)=> values.no_buku_order=v"
        @update:valueFull="buku" :api="{
              url: `${store.server.url_backend}/operation/t_buku_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                view_tarif:true,
                join:true,
                simplest:true,
                searchfield: 'this.tgl , this.no_buku_order , this.jenis_barang'
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
              field: 'id',
              headerName:  'Tanggal',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'tgl',
              headerName:  'Tanggal',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'no_buku_order',
              headerName:  'Nomor Buku Order',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'jenis_barang',
              headerName:  'Jenis Barang',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
    </div>


    <!-- Customer -->
    <div>
      <FieldSelect :bind="{ disabled: true, clearable:true }" class="w-full !mt-3" :value="values.customer"
        @input="v=>values.customer=v" :errorText="formErrors.customer?'failed':''" :hints="formErrors.customer"
        valueField="id" displayField="nama_perusahaan" :api="{
              url: `${store.server.url_backend}/operation/m_customer`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:'this.is_active=true'
              }
          }" placeholder="Customer" label="Customer" :check="true" />
    </div>
    <!-- Type Tagihan Coloumn  -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3" :value="values.tipe_tagihan"
        @input="v=>values.tipe_tagihan=v" :errorText="formErrors.tipe_tagihan?'failed':''"
        :hints="formErrors.tipe_tagihan" valueField="key" displayField="key"
        :options="[{'key' : 'UTANG'},{'key' : 'BAYAR'}]" placeholder="Pilih Tipe Tagihan" label="Tipe Tagihan"
        :check="true" />
    </div>

    <!-- Tax Coloumn -->
    <div class="flex space-x-2">
      <span class="text-lg font-semibold items-center !mt-3">%</span>
      <FieldNumber :bind="{ readonly: !actionText  }" class=" !mt-3 w-full" :value="values.ppn"
        :errorText="formErrors.ppn?'failed':''" @input="v=>values.ppn=v" :hints="formErrors.ppn" label="ppn"
        placeholder="masukan Persentase PPN" :check="false" />

    </div>

    <!-- Total Amount Coloumn -->
    <div class="hidden">
      <div>
        <FieldNumber :bind="{ readonly: true}" class="w-full !mt-3" :value="values.total_amount"
          :errorText="formErrors.total_amount?'failed':''" @input="v=>values.total_amount=v"
          :hints="formErrors.total_amount" label="Total Amount" placeholder="Total Amount" :check="false" />
      </div>
      <!-- Grand Total Amount Coloumn -->
      <div>
        <FieldNumber :bind="{ readonly: true}" class="w-full !mt-3" :value="values.grand_total_amount"
          :errorText="formErrors.grand_total_amount?'failed':''" @input="v=>values.grand_total_amount=v"
          :hints="formErrors.grand_total_amount" label="Grand Total Amount" placeholder="Grand Total Amount"
          :check="false" />
      </div>
      <!-- Grand Total Nota Rampung -->
      <div>
        <FieldNumber :bind="{ readonly: true}" class="w-full !mt-3" :value="values.grand_total_nota_rampung"
          :errorText="formErrors.grand_total_nota_rampung?'failed':''" @input="v=>values.grand_total_nota_rampung=v"
          :hints="formErrors.grand_total_nota_rampung" label="Grand Total nota_rampung"
          placeholder="Grand Total nota_rampung" :check="false" />
      </div>
    </div>


    <!-- No. Faktur Pajak -->
    <div>
      <FieldSelect class="w-full !mt-3" valueField="id" displayField="no_faktur_pajak" :value="values.no_faktur_pajak"
        @input="(v)=>values.no_faktur_pajak=v" :api="{
              url: `${store.server.url_backend}/operation/m_faktur_pajak_d`,
              headers: { 'Content-Type': 'Application/json', 
              Authorization: `${store.user.token_type} ${store.user.token}`},
            }" label="No.Faktur Pajak" placeholder="Pilih No.Faktur Pajak" :check="false" />
    </div>
    <!-- TARIF COO -->
    <div>
        <FieldNumber :bind="{ readonly: !actionText}" class="w-full !mt-3" :value="values.tarif_coo"
          :errorText="formErrors.tarif_coo?'failed':''" @input="v=>values.tarif_coo=v"
          :hints="formErrors.tarif_coo" label="Tarif COO"
          placeholder="Tarif COO" :check="false" />
      </div>
      <!-- TARIF PPJK -->
    <div>
        <FieldNumber :bind="{ readonly: !actionText}" class="w-full !mt-3" :value="values.tarif_ppjk"
          :errorText="formErrors.tarif_ppjk?'failed':''" @input="v=>values.tarif_ppjk=v"
          :hints="formErrors.tarif_ppjk" label="Tarif PPJK"
          placeholder="Tarif PPJK" :check="false" />
      </div>
    <!-- Catatan Coloumn -->
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        label="Catatan" placeholder="Catatan" :check="false" type="textarea" />
    </div>

    <!-- Status Coloumn -->
    <div>
      <FieldX class="w-full !mt-3" :bind="{ disabled: true, clearable:true }" :value="values.status"
        @input="v=>values.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status"
        placeholder="Pilih Status" label="Status" :check="false" />
    </div>

    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>

  <!-- detail -->

  <div class="flex flex-row items-center justify-between space-x-2 p-2">
    <div class="flex flex-col text-xl font-semibold">
      <button @click="generateTotal" v-show="actionText" class="p-1 bg-green-500 text-lg text-white rounded-xl font-semibold">
    Generate total
  </button>
      <span>Total = {{ formatCurrency(values.total_amount) }}</span>
      <span>Total Setelah PPN = {{ formatCurrency(values.grand_total_amount) }}</span>

    </div>


  </div>

  <div class="flex flex-row items-center justify-end space-x-2 p-2">
    <i class="text-gray-500 text-[12px]" v-show="actionText">
    Tekan CTRL + S untuk shortcut Save Data
  </i>
    <button
    class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
    v-show="actionText"
    @click="onReset(true)"
  >
    <icon fa="times" />
    Reset
  </button>

    <button
    v-if="values.status === 'DRAFT'"
    class="text-sm rounded-md py-2 px-3 text-white bg-yellow-600 hover:bg-yellow-700 flex gap-x-1 items-center
    transition-colors duration-300"
    @click="onSave(true)"
  >
    <icon fa="paper-plane" />
    <span>Simpan Dan Post</span>
  </button>

    <button
    class="text-sm rounded-md py-2 px-3 text-white bg-green-600 hover:bg-green-700 flex gap-x-1 items-center
    transition-colors duration-300"
    v-show="actionText"
    @click="onSave(false)"
  >
    <icon fa="save" />
    <span>Simpan</span>
  </button>
  </div>


  <hr>
  <div class="p-1">
    <div class="flex items-stretch lg:w-[40%] text-sm overflow-x-auto <md:col-span-1 col-span-3 p-2">
      <button
        class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-1 hover:border-blue-600 hover:text-blue-600 duration-300"
        :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 0}"
        @click="activeTabIndex = 0"
      >
        Detail NPWP
      </button>

      <button
        class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-1 hover:border-blue-600 hover:text-blue-600 duration-300"
        :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 1}"
        @click="activeTabIndex = 1"
      >
        Tarif Jasa Tia
      </button>

      <button
        class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-1 hover:border-blue-600 hover:text-blue-600 duration-300"
        :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 3}"
        @click="activeTabIndex = 3"
      >
        Tarif Lain-Lain
      </button>
    </div>

    <!-- Detail NPWP -->
    <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 " v-if="activeTabIndex === 0">
      <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
        <table class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                No.
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] w-[15%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                No. Order
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] w-[8%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                No. Prefix
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] w-[8%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                No. Sufix
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] w-[15%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Sektor
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] w-[8%]  text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Ukuran
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px]  w-[8%]  text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Tipe
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Jenis
              </td>
              <!-- <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Info
              </td> -->
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length > 0">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div>
                <FieldPopup label="" placeholder="No. Buku Order"
                  :bind="{ disabled: true, readonly: true , clearable:false }" class="w-full py-2 !mt-0" valueField="id"
                  displayField="no_buku_order" :value="item.t_buku_order_id" @input="v=>item.t_buku_order_id=v" :api="{

              url: `${store.server.url_backend}/operation/t_buku_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                view_tarif:true,
                join:true,
                simplest:true,
                searchfield: 'this.tgl , this.no_buku_order , this.jenis_barang'
              }
            }" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'id',
              headerName:  'Tanggal',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'tgl',
              headerName:  'Tanggal',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'no_buku_order',
              headerName:  'Nomor Buku Order',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'jenis_barang',
              headerName:  'Jenis Barang',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
              </div>
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0" :value="item.no_prefix"
                  :errorText="formErrors.no_prefix?'failed':''" @input="v=>item.no_prefix=v"
                  :hints="formErrors.no_prefix" :check="false" label="" placeholder="No. Prefix" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0" :value="item.no_suffix"
                  :errorText="formErrors.no_suffix?'failed':''" @input="v=>item.no_suffix=v"
                  :hints="formErrors.no_suffix" :check="false" label="" placeholder="No. Sufix" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.sektor"
                  @input="v=>item.sektor=v" :errorText="formErrors.sektor?'failed':''" :hints="formErrors.sektor"
                  valueField="id" displayField="deskripsi" :api="{          
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            transform:false,
                            join:false,
                            where:`this.is_active=true and this.group='SEKTOR'`
                          }
                      }" placeholder="Pilih Sektor" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: true }" class="w-full py-2 !mt-0" :value="item.ukuran"
                  :errorText="formErrors.ukuran?'failed':''" @input="v=>item.ukuran=v" :hints="formErrors.ukuran"
                  :check="false" label="" placeholder="Ukuran" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.tipe"
                  @input="v=>item.tipe=v" :errorText="formErrors.tipe?'failed':''" :hints="formErrors.tipe"
                  valueField="id" displayField="deskripsi" :api="{          
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            transform:false,
                            join:false,
                            where:`this.is_active=true and this.group='TIPE KONTAINER'`
                          }
                      }" placeholder="Pilih Tipe" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.jenis"
                  @input="v=>item.jenis=v" :errorText="formErrors.jenis?'failed':''" :hints="formErrors.jenis"
                  valueField="id" displayField="deskripsi" :api="{          
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            transform:false,
                            join:false,
                            where:`this.is_active=true and this.group='JENIS KONTAINER'`
                          }
                      }" placeholder="Pilih Jenis" label="" :check="false" />
              </td>
              <!-- <td class="border border-[#CACACA]">
                <div class="flex justify-center">
                  <button  @click="openDetail(item.id)" class="rounded-lg flex items-center justify-center">
                      <icon fa="circle-info" size="lg">
                    </button>
                </div>

              </td> -->

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

    <!-- MODAL -->
<!-- <div v-show="modalOpen" class="fixed inset-0 flex items-center justify-center z-50">
  <div class="modal-overlay fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
  <div class="modal-container bg-white w-[70%] mx-auto rounded shadow-lg z-50 overflow-y-auto">
    <div class="modal-content py-4 text-left px-6">

      <div class="modal-header flex items-center justify-between flex-wrap">
        <div class="flex items-center">
          <h3 class="text-xl font-semibold ml-2">Kontainer</h3>
        </div>
      </div>

     
      <div class="modal-body">
 <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
        <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                No.
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Nama Jenis
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Nama Value
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Satuan
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Tarif
              </td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArrOpen" :key="item.id" class="border-t" v-if="detailArrOpen.length > 0">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div>
                <FieldX label="" placeholder="No. Buku Order"
                  :bind="{ disabled: true, readonly: true , clearable:false }" class="w-full !mt-3" valueField="id"
                  displayField="no_buku_order" :value="item.nama_jenis" @input="v=>item.nama_jenis=v"
                  :check="false"
                   />
              </div>
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div>
                <FieldX label="" placeholder="No. Buku Order"
                  :bind="{ disabled: true, readonly: true , clearable:false }" class="w-full !mt-3" valueField="id"
                  displayField="no_buku_order" :value="item.nama_value" @input="v=>item.nama_value=v" 
                  :check="false"/>
              </div>
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div>
                <FieldNumber label="" placeholder="No. Buku Order"
                  :bind="{ disabled: true, readonly: true , clearable:false }" class="w-full !mt-3" valueField="id"
                  displayField="no_buku_order" :value="item.satuan" @input="v=>item.satuan=v" 
                  :check="false"/>
              </div>
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div>
                <FieldNumber label="" placeholder="No. Buku Order"
                  :bind="{ disabled: true, readonly: true , clearable:false }" class="w-full !mt-3" valueField="id"
                  displayField="no_buku_order" :value="item.tarif" @input="v=>item.tarif=v" 
                  :check="false"/>
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

      
      <div class="modal-footer flex justify-center mt-2">
        <button @click="closeModal" class="modal-button bg-red-500 hover:bg-red-600 text-white font-semibold ml-2 px-2 py-1 rounded-sm">
          Tutup
        </button>
      </div>

    </div>
  </div>
</div> -->





    <!-- Detail Jasa TIA UKURAN-->
    <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 " v-if="activeTabIndex === 1">
      <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
        <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                No.
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Kode Jasa
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Nama Jasa
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] w-[10%]">
                Tarif
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Catatan
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                PPN
              </td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArr1" :key="item.id" class="border-t" v-if="detailArr1.length > 0">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{ readonly: true, clearable: false }" class="w-full py-2 !mt-0" :value="item.kode_jasa"
                  @input="v => item.kode_jasa = v" :errorText="formErrors.kode_jasa ? 'failed' : ''"
                  :hints="formErrors.kode_jasa" placeholder="Kode" label="" :check="false" valueField="id"
                  displayField="kode_jasa" :api="{
          url: `${store.server.url_backend}/operation/m_jasa`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest: true,
            transform: false,
            join: false,
          }
        }" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: true, readonly: true, clearable: true }" class="w-full !mt-3"
                  :value="item.m_jasa_id" @input="v => item.m_jasa_id = v"
                  :errorText="formErrors.customer ? 'failed' : ''" :hints="formErrors.customer" valueField="id"
                  displayField="nama_jasa" :api="{
          url: `${store.server.url_backend}/operation/m_jasa`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest: true,
            transform: false,
            join: false,
          }
        }" placeholder="Pilih Jasa" label="" :check="true" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: true, clearable: false }" class="w-full py-2 !mt-0" :value="item.tarif"
                  @input="v => item.tarif = v" :errorText="formErrors.tarif ? 'failed' : ''" :hints="formErrors.tarif"
                  valueField="key" displayField="key" placeholder="Satuan" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{ readonly: true, clearable: false }" class="w-full py-2 !mt-0"
                  :value="item.catatan" @input="v => item.catatan = v" :errorText="formErrors.catatan ? 'failed' : ''"
                  :hints="formErrors.catatan" type="textarea" placeholder="Catatan" label="" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <input
        type="checkbox"
        class="h-5 w-5 text-blue-500 rounded cursor-not-allowed"
        v-model="item.ppn"
        @click.prevent
      >
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
    <!-- Detail Lain-Lain -->
    <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 " v-if="activeTabIndex === 3">
      <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
        <button v-show="actionText" @click="addDetail3" type="button" class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-2">
             <icon fa="plus" />
             Add to List
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
                Nominal
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Keterangan
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Action
              </td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArr3" :key="item.id" class="border-t" v-if="detailArr3.length > 0">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                  :value="item.nominal" @input="v=>item.nominal=v" :errorText="formErrors.nominal?'failed':''"
                  :hints="formErrors.nominal" placeholder="Masukan nominal" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{readonly: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                  :value="item.keterangan" @input="v=>item.keterangan=v" :errorText="formErrors.keterangan?'failed':''"
                  :hints="formErrors.keterangan" placeholder="Keterangan" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div class="flex justify-center">
                  <button type="button" @click="removeDetail(i)" :disabled="!actionText" title="Hapus">
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
  <!-- </div> -->

</div>



@endverbatim
@endif



