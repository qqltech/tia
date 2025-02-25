<!-- LANDING -->
@if(!$req->has('id'))

@verbatim
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">SURAT JALAN / PENGANTAR</h1>
  </div>
  <div class="flex flex-col justify-center w-full px-4 py-1">
    <div class="flex justify-between items-center gap-2">
      <div class="flex gap-2 pb-3">
        <p class="py-2">Filter Status :</p>
        <div class="flex items-center gap-2">
          <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-gray-600 text-white hover:bg-gray-600' 
          : 'border border-gray-600 text-gray-600 bg-white hover:bg-gray-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          DRAFT
        </button>
          <div class="h-4 w-px bg-gray-300"></div>
          <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-amber-600 text-white hover:bg-amber-600' 
          : 'border border-amber-600 text-amber-600 bg-white hover:bg-amber-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          POST
          </button>
          <div class="h-4 w-px bg-gray-300"></div>
          <button @click="filterShowData('PRINTED')" :class="filterButton === 'PRINTED' ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          PRINTED
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

  @endverbatim
  @else

  <!-- CONTENT -->
  @verbatim
  <div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
    <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
      <div class="flex items-center">
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
        <div>
          <h1 class="text-20px font-bold">Form Surat Jalan / Pengantar</h1>
          <p class="text-gray-100">Header Surat Jalan</p>
        </div>
      </div>
    </div>
    <!-- HEADER END -->

    <!-- FORM START -->
    <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row p-4 gap-3">
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.no_draft"
          :errorText="formErrors.no_draft?'failed':''" @input="v=>values.no_draft=v" :hints="formErrors.no_draft"
          label="No.Draft" placeholder="No Draft" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldSelect class="!mt-0" :bind="{ disabled: true, clearable:true  }" :value="values.status"
          :errorText="formErrors.status ? 'failed' : ''" @input="v => values.status = v" :hints="formErrors.status"
          valueField="id" displayField="key" :options="[
              { 'id': 'DRAFT', 'key': 'DRAFT' },
              { 'id': 'POST', 'key': 'POST' },
              { 'id': 'PRINTED', 'key': 'PRINTED' },
            ]" label="Status" placeholder="Status" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.no_surat_jalan"
          :errorText="formErrors.no_surat_jalan?'failed':''" @input="v=>values.no_surat_jalan=v"
          :hints="formErrors.no_surat_jalan" placeholder="No. SJ" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0"
          :bind="{ disabled: !actionText  || actionEditBerkas == 'EditBerkas' ,readonly: !actionText  ||  actionEditBerkas == 'EditBerkas'}"
          :value="values.tanggal" :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v"
          :hints="formErrors.tanggal" placeholder="Tanggal" :check="false" type="date" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0"
          :bind="{ disabled: !actionText || actionEditBerkas == 'EditBerkas' ,readonly: !actionText || actionEditBerkas == 'EditBerkas'}"
          :value="values.tanggal_berangkat" :errorText="formErrors.tanggal_berangkat?'failed':''"
          @input="v=>values.tanggal_berangkat=v" :hints="formErrors.tanggal_berangkat" placeholder="Tanggal Berangkat"
          :check="false" type="date" />
      </div>
      <div class="w-full !mt-3">
        <FieldPopup class="!mt-0" :bind="{ readonly: !actionText }" :value="values.t_buku_order_id" @input="v=>{
          if(v){
            values.t_buku_order_id=v
          }else{
            values.t_buku_order_id=null
          }
          values.t_buku_order_d_npwp_id=null
          values.ukuran_kontainer=null
          values.jenis_kontainer=null
          values.tipe_surat_jalan=null
          values.pelabuhan=null
          values.kapal=null
        }" :errorText="formErrors.t_buku_order_id?'failed':''" :hints="formErrors.t_buku_order_id" @update:valueFull="(dt) => {
              $log(dt)
              values.tanggal = dt.tgl
              values.tipe_surat_jalan = dt.tipe_order
              values.pelabuhan = dt['pelabuhan.kode']
              values.kapal = dt.nama_kapal
              values.tipe_kontainer = dt.tipe_kontainer
            }" valueField="id" displayField="no_buku_order" :api="{
              url: `${store.server.url_backend}/operation/t_buku_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                searchfield: 'this.no_buku_order, m_customer.nama_perusahaan'
              },
              onsuccess: response=> {
                response.page = response.current_page
                response.hasNext = response.has_next
                return response
            },
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
              field: 'm_customer.nama_perusahaan',
              headerName:  'Nama Customer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            }
            ]" />
      </div>
      <div class="w-full !mt-3">
        <FieldPopup class="!mt-0" :bind="{ readonly: !actionText }" :value="values.t_buku_order_d_npwp_id" @input="v=>{
          if(v){
            values.t_buku_order_d_npwp_id=v
          }else{
            values.t_buku_order_d_npwp_id=null
          }
          values.ukuran_kontainer=null
          values.jenis_kontainer=null
          values.depo=null
        }" :errorText="formErrors.t_buku_order_d_npwp_id?'failed':''" :hints="formErrors.t_buku_order_d_npwp_id"
          @update:valueFull="(dt) => {
            values.ukuran_kontainer = dt['ukuran.deskripsi']
            values.jenis_kontainer = dt['jenis.deskripsi']
            values.depo = dt['depo.kode']
          }" valueField="id" displayField="no_cont" :api="{
              url: `${store.server.url_backend}/operation/t_buku_order_d_npwp`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                where: `this.t_buku_order_id=${values.t_buku_order_id??0}`,
                no_cont: true
              },
            }" placeholder="No Container" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'no_cont',
              headerName:  'No Kontainer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'ukuran.deskripsi',
              headerName:  'Ukuran Kontainer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'jenis.deskripsi',
              headerName:  'Jenis Kontainer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            }
            ]" />
      </div>
      <div class="w-full !mt-3">
        <FieldSelect class="!mt-0" :bind="{ disabled: true, clearable:false }" :value="values.ukuran_kontainer"
          @input="v=>values.ukuran_kontainer=v" :errorText="formErrors.ukuran_kontainer?'failed':''"
          :hints="formErrors.ukuran_kontainer" placeholder="Ukuran Kontainer" label="Ukuran Kontainer" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.jenis_kontainer"
          :errorText="formErrors.jenis_kontainer?'failed':''" @input="v=>values.jenis_kontainer=v"
          :hints="formErrors.jenis_kontainer" placeholder="Jenis Kontainer" label="Jenis Kontainer" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.tipe_surat_jalan"
          @input="v=>values.tipe_surat_jalan=v" :errorText="formErrors.tipe_surat_jalan?'failed':''"
          :hints="formErrors.tipe_surat_jalan" placeholder="Tipe Surat Jalan" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.pelabuhan"
          :errorText="formErrors.pelabuhan?'failed':''" @input="v=>values.pelabuhan=v" :hints="formErrors.pelabuhan"
          placeholder="Pelabuhan" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.kapal" @input="v=>values.kapal=v"
          :errorText="formErrors.kapal?'failed':''" :hints="formErrors.kapal" placeholder="Kapal" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, clearable:true }" :value="values.jenis_sj"
          @input="v=>values.jenis_sj=v" :errorText="formErrors.jenis_sj?'failed':''" :hints="formErrors.jenis_sj"
          valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where: `this.group='ISI CONTAINER'`
              }
          }" placeholder="Isi Kontainer" label="Isi Kontainer" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: !actionText && (!actionEditBerkas || values.is_edit_berkas == true) }"
          :value="values.lokasi_stuffing" @input="v=>values.lokasi_stuffing=v"
          :errorText="formErrors.lokasi_stuffing?'failed':''" :hints="formErrors.lokasi_stuffing"
          placeholder="Lokasi Stuffing" type="textarea" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.depo" @input="v=>values.depo=v"
          :errorText="formErrors.depo?'failed':''" :hints="formErrors.depo" placeholder="Depo" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.nw" @input="v=>values.nw=v"
          :errorText="formErrors.nw?'failed':''" :hints="formErrors.nw" placeholder="Masukkan NW" placeholder="NW"
          :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.gw" @input="v=>values.gw=v"
          :errorText="formErrors.gw?'failed':''" :hints="formErrors.gw" placeholder="Masukkan GW" placeholder="GW"
          :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.no_seal" @input="v=>values.no_seal=v"
          :errorText="formErrors.no_seal?'failed':''" :hints="formErrors.no_seal" placeholder="Masukkan No. Seal"
          placeholder="No. Seal" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.tare" @input="v=>values.tare=v"
          :errorText="formErrors.tare?'failed':''" :hints="formErrors.tare" placeholder="Masukkan TARE"
          placeholder="TARE" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldUpload :bind="{ readonly: !actionText && (!actionEditBerkas || values.is_edit_berkas == true) }"
          class="!mt-0" :value="values.foto_berkas" @input="(v)=>values.foto_berkas=v" :maxSize="10"
          :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
            url: `${store.server.url_backend}/operation${endpointApi}/upload`,
            headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { field: 'foto_berkas' },
            onsuccess: response=> {
              return response
            },
            onerror:(error)=>{
              swal.fire({ icon: 'error', text: error });
            },
           }" :hints="formErrors.foto_berkas" :errorText="formErrors.foto_berkas?'failed':''" label="Foto Berkas"
          placeholder="Foto Berkas" accept="image/*" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldUpload :bind="{ readonly: !actionText && (!actionEditBerkas || values.is_edit_berkas == true) }"
          class="!mt-0" :value="values.foto_surat_jalan" @input="(v)=>values.foto_surat_jalan=v" :maxSize="10"
          :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
            url: `${store.server.url_backend}/operation${endpointApi}/upload`,
            headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { field: 'foto_surat_jalan' },
            onsuccess: response=> {
              return response
            },
            onerror:(error)=>{
              swal.fire({ icon: 'error', text: error });
            },
           }" :hints="formErrors.foto_surat_jalan" :errorText="formErrors.foto_surat_jalan?'failed':''"
          label="Foto Berkas" placeholder="Upload file (jpg, png, pdf, xls, doc, etc.)"
          accept=".jpg,.jpeg,.png,.gif,.pdf,.xls,.xlsx,.doc,.docx,.csv" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.catatan"
          :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
          type="textarea" placeholder="Catatan" :check="false" />
      </div>
    </div>
    <!-- FORM END -->
    <hr>
    <div class="flex flex-row items-center justify-end space-x-2 p-2" v-show="actionText || actionEditBerkas">
      <i class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
      <button
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        @click="onReset(true)"
      >
        <icon fa="times" />
        Reset
      </button>
      <button class="text-sm rounded-md py-2 px-3 text-white bg-yellow-600 hover:bg-yellow-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="onSave(true)">
            <icon fa="paper-plane" />
            <span>Post</span>
      </button>
      <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        @click="onSave(false)"
      >
        <icon fa="save" />
        Simpan
      </button>
    </div>
  </div>

  @endverbatim
  @endif