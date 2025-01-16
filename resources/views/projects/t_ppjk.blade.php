<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
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
        <!-- <div class="flex my-auto h-4 w-px bg-[#6E91D1]"></div>
                <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-red-600 text-white hover:bg-red-600' 
                        : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'" 
                        class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
                    POST
                </button> -->
      </div>
    </div>

    <!-- ACTION BUTTON -->
    <div class="flex items-center gap-x-4">
      <RouterLink :to="$route.path + '/create?' + (Date.parse(new Date()))" class="border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white 
                        text-sm rounded-md py-1 px-2.5 transition-colors duration-300">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>

  <!-- TABLE -->
  <TableApi ref='apiTable' :api="table.api" :columns="table.columns" :actions="table.actions"
    class="max-h-[450px] pt-2 !px-4 !pb-8">
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
      <div class="flex flex-col">
        <h1 class="text-lg font-bold">Form PPJK</h1>
        <p class="text-gray-100">Atur berbagai menu untuk apps</p>
      </div>
    </div>
  </div>

  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 items-start">
    <div class="relative">
      <label v-show="data.no_draft === '' || !data.no_draft" class="absolute top-2 left-1 left text-gray-600 text-xs font-semibold">
            No. Draft
          </label>
      <FieldX :bind="{ readonly: true || !actionText }" class="pt-1" :value="data.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>data.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft" placeholder='Auto Generate by System' :check="false" />
    </div>
    <FieldSelect :bind="{ disabled: true || !actionText, clearable: false }" class="pt-1" :value="data.status"
      @input="v=>data.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status" valueField="id"
      displayField="key" :options="[
            {'id' : 'DRAFT' , 'key' : 'Draft'}, 
            {'id': 'INPROCESS', 'key' : 'InProcess'},
            {'id': 'POSTED', 'key' : 'Posted'},
          ]" placeholder="Status" :check="false" />

    <FieldPopup class="pt-1" :bind="{ disabled: !actionText, clearable:false }" :value="data.no_ppjk_id"
      @input="v=>data.no_ppjk_id=v" :errorText="formErrors.no_ppjk_id?'failed':''" :hints="formErrors.no_ppjk_id"
      valueField="id" displayField="no_aju" :api="{
              url: `${store.server.url_backend}/operation/m_generate_no_aju_d`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                scopes: 'NoPPJK',
                searchfield: `m_generate_no_aju.tgl_pembuatan, this.no_aju, m_generate_no_aju.tipe`,
                //selectfield:` this.id, head.tgl_pembuatan, this.no_aju, head.tipe`
              },
            onsuccess(response) {
              return { ...response, page: response.current_page, hasNext: response.has_next };
            }
          }" placeholder="No PPJK" :check="false" :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
              headerName: 'Tanggal Pembuatan', field: 'tgl_pembuatan', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
              sortable: true, filter: 'ColFilter'
          },
          {
              headerName: 'No. PPJK', field: 'no_aju', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
              sortable: true, filter: 'ColFilter'
          },
          {
              headerName: 'Tipe PPJK', field: 'tipe', flex: 1, cellClass: ['border-r', '!border-gray-200', 'justify-start'],
              sortable: true, filter: 'ColFilter'
          }]" />
    <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.tanggal"
      :errorText="formErrors.tanggal?'failed':''" @input="v=>data.tanggal=v" :hints="formErrors.tanggal"
      placeholder="Masukkan Tanggal" :check="false" type="date" />
    <FieldPopup :bind="{ readonly: !actionText }" :value="data.t_buku_order_id" @input="v=>{
        if(v){
          data.t_buku_order_id=v
        }
        else{
          data.t_buku_order_id=null
        }
        data.kode_customer=null
        data.no_npwp=null
      }"
      :errorText="formErrors.t_buku_order_id?'failed':''" :hints="formErrors.t_buku_order_id" valueField="id"
      displayField="no_buku_order" 
      @update:valueFull="(dt)=>{
        data.no_npwp = dt.m_customer.m_customer_d_npwp[0].no_npwp
        data.kode_customer = dt.m_customer.kode
      }"
      :api="{
            url: `${store.server.url_backend}/operation/t_buku_order`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              searchfield: `this.no_buku_order, this.tgl`,
              scopes:'GetCustomerNPWP'
              //where:`this.status = 'POST'`
            },
            onsuccess:(response)=>{
              response.page = response.current_page
              response.hasNext = response.has_next
              return response
            }
          }" placeholder="No Buku Order" :check="false" :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'no_buku_order',
            headerName:  'No Buku Order',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          },
          {
            flex: 1,
            field: 'tgl',
            headerName:  'Tanggal',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          }]" @update:valueFull="(res) => {
            if(res === null){
              data.m_customer_id = null;
              data.kode_customer=null;
              data.no_npwp=null;
              
            } else {
              data.m_customer_id = res['m_customer.id'];
              
            }
          }" />

    <FieldPopup :bind="{ readonly: !actionText }" :value="data.m_customer_id" @input="(v)=>data.m_customer_id=v"
      :errorText="formErrors.m_customer_id?'failed':''" :hints="formErrors.m_customer_id" valueField="id"
      displayField="nama_perusahaan" @input="v=>{
          if(v){
            data.m_customer_id=v
          }else{
            data.m_customer_id=null
          }
          data.kode_customer=null
          data.no_npwp=null
        }" @update:valueFull="(dt)=>{
          data.kode_customer = dt.kode
          data.no_npwp = dt.m_customer_d_npwp[0].no_npwp
          }" :api="{
            url: `${store.server.url_backend}/operation/m_customer`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              where:`this.is_active = true`,
              scopes:'GetCustomerNPWP',
              searchfield: `this.nama_perusahaan`
            },
            onsuccess:(response)=>{
              response.page = response.current_page
              response.hasNext = response.has_next
              return response
            }

          }" placeholder="Customer" :check="false" :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'nama_perusahaan',
            headerName:  'Nama Customer',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          }]" />

    <FieldX :bind="{ readonly: true || !actionText }" class="pt-1" :value="data.kode_customer"
      :errorText="formErrors.kode_customer?'failed':''" @input="v=>data.kode_customer=v"
      :hints="formErrors.kode_customer" placeholder="Kode Customer" :check="false" />

    <FieldX :bind="{ readonly: true || !actionText }" class="pt-1" :value="data.no_npwp"
      :errorText="formErrors.no_npwp?'failed':''" @input="v=>data.no_npwp=v" :hints="formErrors.no_npwp"
      placeholder="NPWP" :check="false" />
    <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.no_peb_pib"
      :errorText="formErrors.no_peb_pib?'failed':''" @input="v=>data.no_peb_pib=v" :hints="formErrors.no_peb_pib"
      placeholder="No PEB / PIB" :check="false" type="number" />
    <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.tanggal_peb_pib"
      :errorText="formErrors.tanggal_peb_pib?'failed':''" @input="v=>data.tanggal_peb_pib=v"
      :hints="formErrors.tanggal_peb_pib" placeholder="Tanggal PEB / PIB" :check="false" type="date" />
    <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.no_sppb"
      :errorText="formErrors.no_sppb?'failed':''" @input="v=>data.no_sppb=v" :hints="formErrors.no_sppb"
      placeholder="No SPPB" :check="false" type="number" />
    <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.tanggal_sppb"
      :errorText="formErrors.tanggal_sppb?'failed':''" @input="v=>data.tanggal_sppb=v" :hints="formErrors.tanggal_sppb"
      placeholder="Tanggal SPPB" :check="false" type="date" />
    <FieldNumber class="pt-1" :bind="{ readonly: !actionText }" :value="data.invoice" @input="(v)=>data.invoice=v"
      :errorText="formErrors.invoice?'failed':''" :hints="formErrors.invoice" placeholder="Invoice" :check="false" />
    <FieldNumber class="pt-1" :bind="{ readonly: !actionText }" :value="data.ppn_pib" @input="(v)=>data.ppn_pib=v"
      :errorText="formErrors.ppn_pib?'failed':''" :hints="formErrors.ppn_pib" placeholder="PPN PIB" :check="false" />
    <FieldX :bind="{ readonly: true, disabled:true }" class="pt-1" :value="data.currency"
      :errorText="formErrors.currency?'failed':''" @input="v=>data.currency=v" :hints="formErrors.currency"
      placeholder="Currency" :check="false" />
    <FieldNumber class="pt-1" :bind="{ readonly: true, disabled:true }" :value="data.nilai_kurs"
      @input="(v)=>data.nilai_kurs=v" :errorText="formErrors.nilai_kurs?'failed':''" :hints="formErrors.nilai_kurs"
      placeholder="Nilai Kurs" :check="false" />

    <!-- <FieldX class="pt-1"
          :bind="{ readonly: actionText && !data.currency || data.currency === 'IDR' }" :value="data.nilai_kurs" 
          @input="(v)=>data.nilai_kurs=v"
          :errorText="formErrors.nilai_kurs?'failed':''" :hints="formErrors.nilai_kurs" placeholder="Nilai Kurs" :check="false" type="number"/>
        -->
    <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.catatan"
      :errorText="formErrors.catatan?'failed':''" @input="v=>data.catatan=v" :hints="formErrors.catatan"
      placeholder="Catatan" :check="false" type="textarea" />

  </div>

  <!-- ACTION BUTTON START -->
  <hr v-show="actionText" />
  <div class="flex flex-row items-center justify-end space-x-2 py-2 px-4" v-show="actionText">
    <i class="text-gray-500 text-[12px] mr-4">Tekan CTRL + S untuk shortcut Save Data</i>
    <button class="text-sm rounded-md py-2 px-3 text-white bg-red-600 hover:bg-red-700 flex gap-x-1 
        items-center transition-colors duration-300" @click="onReset(true)">
            <icon fa="times" />
            <span>Reset</span>
        </button>
    <button class="text-sm rounded-md py-2 px-3 text-white bg-yellow-600 hover:bg-yellow-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="onSave(true)">
            <icon fa="paper-plane" />
            <span>Post</span>
        </button>
    <button class="text-sm rounded-md py-2 px-3 text-white bg-green-600 hover:bg-green-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="onSave(false)">
            <icon fa="save" />
            <span>Simpan</span>
        </button>
  </div>
</div>
@endverbatim
@endif