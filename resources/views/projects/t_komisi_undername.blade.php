@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">KOMISI UNDERNAME</h1>
  </div>
  <div class="flex justify-between items-center px-4 py-1">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-gray-600 text-white hover:bg-gray-600' 
        : 'border border-gray-600 text-gray-600 bg-white hover:bg-gray-600 hover:text-white'" class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
          DRAFT
        </button>
        <div class="flex my-auto h-4 w-px bg-[#6E91D1]"></div>
        <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-amber-600 text-white hover:bg-amber-600' 
        : 'border border-amber-600 text-amber-600 bg-white hover:bg-amber-600 hover:text-white'" class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
          POST
        </button>
        <div class="flex my-auto h-4 w-px bg-[#6E91D1]"></div>
        <button @click="filterShowData('COMPLETED')" :class="filterButton === 'COMPLETED' ? 'bg-pink-600 text-white hover:bg-pink-600' 
        : 'border border-pink-600 text-pink-600 bg-white hover:bg-pink-600 hover:text-white'" class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
          COMPLETED
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
        <h1 class="text-20px font-bold">Form Komisi Undername</h1>
        <p class="text-gray-100">Untuk mengatur informasi komisi undername pada sistem</p>
      </div>
    </div>
  </div>
  <!-- HEADER END -->

  <!-- FORM START -->
  <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row p-4 gap-3">
    <div class=" w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ disabled: true, readonly: true }" :value="values.no_komisi_undername"
        :errorText="formErrors.no_komisi_undername?'failed':''" @input="v=>values.no_komisi_undername=v"
        :hints="formErrors.no_komisi_undername" placeholder="No. Komisi Undername" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: true, readonly: true, clearable: false }" :value="values.status_id"
        :errorText="formErrors.status_id ? 'failed' : ''" @input="v => values.status_id = v"
        :hints="formErrors.status_id" valueField="id" displayField="key" :options="[
              { 'id': 'DRAFT', 'key': 'DRAFT' },
              { 'id': 'POST', 'key': 'POST' },
              { 'id': 'COMPLETE', 'key': 'COMPLETE' }
        ]" label="Status" placeholder="Status" :check="true" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ disabled: true, readonly: true }" :value="values.tanggal"
        :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v" :hints="formErrors.tanggal" type="date"
        placeholder="Tanggal" :check="false" />
    </div>
    <div v-if="actionEditTanggal" class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText && (!actionEditTanggal || values.is_edit_tanggal == true) }"
        :value="values.tanggal_pelunasan" :errorText="formErrors.tanggal_pelunasan?'failed':''"
        @input="v=>values.tanggal_pelunasan=v" :hints="formErrors.tanggal_pelunasan" type="date"
        placeholder="Tanggal Pelunasan" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, clearable:true }" :value="values.tipe_komisi" @input="v=>{
          if(v){
            values.tipe_komisi=v
          }else{
            values.tipe_komisi=null
            t_buku_order_id = null
            values.customer_id = null
            values.aju_id = null
            values.pib_id = null
            values.tgl_pib = null
            values.nilai_invoice = null
            values.kurs = null
          }
        }" :errorText="formErrors.tipe_komisi?'failed':''" :hints="formErrors.tipe_komisi" valueField="id"
        displayField="key" :options="[
              { 'id': 'QQ', 'key': 'QQ' },
              { 'id': 'NON QQ', 'key': 'NON QQ' }
        ]" placeholder="Pilih Salah Satu" :check="true" />
    </div>
    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" :bind="{ readonly: !values.tipe_komisi || !actionText }" :value="values.t_buku_order_id"
        @input="v=>{
          if(v){
            values.t_buku_order_id=v
          }else{
            values.t_buku_order_id=null
            values.customer_id = null
            values.aju_id = null
            values.pib_id = null
            values.tgl_pib = null
            values.nilai_invoice = null
            values.kurs = null
          }
        }" @update:valueFull="(dt)=>{
          values.customer_id = dt['m_customer.id']
          values.aju_id = dt['relation_ppjk']?.[0]?.no_aju
          values.pib_id = dt['relation_ppjk']?.[0]?.no_peb_pib
          values.tgl_pib = dt['relation_ppjk']?.[0]?.tanggal_peb_pib
          values.persentase = parseFloat(dt.persentase)
          values.tarif_komisi = parseFloat(dt.tarif_komisi)
        }" :errorText="formErrors.t_buku_order_id?'failed':''" :hints="formErrors.t_buku_order_id" valueField="id"
        displayField="no_buku_order" :api="{
          url: `${store.server.url_backend}/operation/t_buku_order`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            tipe_tarif: `${values.tipe_komisi}`,
            //customer_id: `${values.customer_id}`,
            nilai_invoice: `${values.nilai_invoice}`,
            scopes: 'WithDetailAju,GetPersentase',
            searchfield: 'this.no_buku_order, m_customer.kode, m_customer.nama_perusahaan'
          },
          onsuccess(response) {
            response.page = response.current_page
            response.hasNext = response.has_next
            return response
          }
        }" placeholder="No. Order" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'no_buku_order',
          headerName:  'Nomor Order',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'm_customer.kode',
          headerName:  'Nomor Order',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'm_customer.nama_perusahaan',
          headerName:  'Customer',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        }
        ]" />
    </div>
    <div class="flex items-center w-full !mt-3 space-x-2">
      <FieldPopup class="!mt-0" :bind="{ readonly: true }" :value="values.customer_id"
        @input="(v)=>values.customer_id=v" :errorText="formErrors.customer_id?'failed':''"
        :hints="formErrors.customer_id" valueField="id" displayField="nama_perusahaan" :api="{
          url: `${store.server.url_backend}/operation/m_customer`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
          }
        }" placeholder="Nama Customer" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ disabled: true, readonly: true }" :value="values.aju_id"
        :errorText="formErrors.aju_id?'failed':''" @input="v=>values.aju_id=v" :hints="formErrors.aju_id"
        placeholder="No. Aju" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ disabled: true, readonly: true }" :value="values.pib_id"
        :errorText="formErrors.pib_id?'failed':''" @input="v=>values.pib_id=v" :hints="formErrors.pib_id"
        placeholder="PEB/PIB" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ disabled: true, readonly: true }" :value="values.tgl_pib"
        :errorText="formErrors.tgl_pib?'failed':''" @input="v=>values.tgl_pib=v" :hints="formErrors.tgl_pib"
        placeholder="Tanggal PEB/PIB" type='date' :check="false" />
    </div>
    <div class="w-full flex !mt-3">
      <FieldNumber class="w-[35%] !mt-0" :bind="{ readonly: (!values.t_buku_order_id) || !actionText }"
        :value="values.nilai_invoice" :errorText="formErrors.nilai_invoice?'failed':''"
        @input="v=>values.nilai_invoice=v" :hints="formErrors.nilai_invoice" placeholder="Nilai Invoice"
        :check="false" />

      <button class="border-1 text-xs rounded w-[45px] col-span-2 ml-0 h-[34px] text-gray-400 bg-gray-100">USD</button>

      <FieldNumber class="w-full pl-2 !mt-0" :bind="{ readonly: (!values.nilai_invoice) || !actionText }"
        :value="values.kurs" :errorText="formErrors.kurs?'failed':''" @input="v=>values.kurs=v" :hints="formErrors.kurs"
        placeholder="Kurs" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldNumber class="!mt-0" :bind="{ disabled: true, readonly: true }" :value="nilaiPabean"
        :errorText="formErrors.nilai_pabean?'failed':''" @input="v=>values.nilai_pabean=v"
        :hints="formErrors.nilai_pabean" placeholder="Nilai Pabean" :check="false" />
    </div>
    <div class="w-full flex !mt-3">
      <FieldNumber class="w-[30%] !mt-0" :bind="{ disabled: true, readonly: true }" :value="values.persentase"
        :errorText="formErrors.persentase?'failed':''" @input="v=>values.persentase=v" :hints="formErrors.persentase"
        placeholder="Persentase" :check="false" />

      <button class="border-1 text-xs rounded w-[45px] col-span-2 ml-0 h-[34px] text-gray-400 bg-gray-100">%</button>

      <FieldNumber class="pl-2 !mt-0" :bind="{ disabled: true, readonly: true }" :value="TotalPajakKomisi"
        :errorText="formErrors.nilai_pajak_komisi?'failed':''" @input="v=>values.nilai_pajak_komisi=v"
        :hints="formErrors.nilai_pajak_komisi" placeholder="Nilai Pajak Komisi" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldNumber class="!mt-0" :bind="{ disabled: true, readonly: true }" :value="values.tarif_komisi"
        :errorText="formErrors.tarif_komisi?'failed':''" @input="v=>values.tarif_komisi=v"
        :hints="formErrors.tarif_komisi" placeholder="Tarif Komisi" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText}" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        placeholder="Keterangan" type="textarea" :check="false" />
    </div>
    <div v-if="actionText"></div>
    <div class="flex flex-row items-center justify-between !mt-3 space-x-2">
      <div class="flex flex-col text-xl font-semibold">
        <span>Total Komisi</span>
        <span class="text-2xl font-bold" :value="TotalKomisi"> {{ formatCurrency(values.total_komisi) }}</span>
      </div>
    </div>
  </div>
  <!-- FORM END -->
  <hr v-show="actionText" class="<md:col-span-1 col-span-3">
  <div class="flex flex-row items-center justify-end space-x-2 p-2">
    <i v-show="actionText || actionEditTanggal" class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
    <button
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        @click="onReset(true)"
        v-show="actionText || actionEditTanggal"
      >
        <icon fa="times" />
        Reset
    </button>
    <button
        class="bg-amber-400 text-white font-semibold hover:bg-amber-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        @click="onSave(false)"
        v-show="actionEditTanggal"
      >
        <icon fa="paper-plane" />
        Complete
    </button>
    <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        @click="onSave(true)"
        v-show="actionText || actionEditTanggal"
      >
        <icon fa="save" />
        Simpan
      </button>
  </div>
</div>

@endverbatim
@endif