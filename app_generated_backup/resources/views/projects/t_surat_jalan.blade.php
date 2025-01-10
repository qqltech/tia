<!-- LANDING -->
@if(!$req->has('id'))

@verbatim
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex flex-col justify-center w-full px-2.5 py-1">
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
          label="" placeholder="No Draft" :check="false" />
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
        <FieldX class="!mt-0" :bind="{ readonly: true}" :value="values.tanggal"
          :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v" :hints="formErrors.tanggal"
          placeholder="Tanggal" :check="false" type="date" />
      </div>
      <div class="w-full !mt-3">
        <FieldPopup class="!mt-0" :bind="{ readonly: !actionText }" :value="values.t_buku_order_id"
          @input="(v)=>values.t_buku_order_id=v" :errorText="formErrors.t_buku_order_id?'failed':''"
          :hints="formErrors.t_buku_order_id" @update:valueFull="(dt) => {
              $log(dt)
              values.tanggal = dt.tgl
              values.tipe_surat_jalan = dt.tipe_order
              values.pelabuhan = dt.nama_pelabuhan
              values.kapal = dt.nama_kapal
              values.tipe_kontainer = dt.tipe_kontainer
            }" valueField="id" displayField="no_buku_order" :api="{
              url: `${store.server.url_backend}/operation/t_buku_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
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
              field: 'm_customer.nama_perusahaan',
              headerName:  'Nama Customer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            }
            ]" />
      </div>
      <div class="w-full !mt-3">
        <FieldPopup class="!mt-0" :bind="{ readonly: !actionText }" :value="values.t_spk_angkutan_id"
          @input="(v)=>values.t_spk_angkutan_id=v" :errorText="formErrors.t_spk_angkutan_id?'failed':''"
          :hints="formErrors.t_spk_angkutan_id" valueField="id" displayField="no_spk" @update:valueFull="(dt) => {
            values.tipe_kontainer = dt.deskripsi
            }" :api="{
              url: `${store.server.url_backend}/operation/t_spk_angkutan`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where: `this.status = 'APPROVED' AND this.t_buku_order_1_id = ${values.t_buku_order_id}`, 
                scopes: 'Tipe'
              }
            }" placeholder="No SPK" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'no_spk',
              headerName:  'No. SPK',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'tipe_spk',
              headerName:  'Tipe SPK',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'supir.nama',
              headerName:  'Supir',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'sektor',
              headerName:  'Sektor',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            }
            ]" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: !actionText}" :value="values.tanggal_berangkat"
          :errorText="formErrors.tanggal_berangkat?'failed':''" @input="v=>values.tanggal_berangkat=v"
          :hints="formErrors.tanggal_berangkat" placeholder="Tanggal Berangkat" :check="false" type="date" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.tipe_surat_jalan"
          @input="v=>values.tipe_surat_jalan=v" :errorText="formErrors.tipe_surat_jalan?'failed':''"
          :hints="formErrors.tipe_surat_jalan" placeholder="Tipe Surat Jalan" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.pelabuhan" @input="v=>values.pelabuhan=v"
          :errorText="formErrors.pelabuhan?'failed':''" :hints="formErrors.pelabuhan" placeholder="Pelabuhan"
          :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldPopup :bind="{ readonly: !actionText }" :value="values.m_lokasistuffing_id"
          @input="(v) => values.m_lokasistuffing_id = String(v)" :errorText="formErrors.m_lokasistuffing_id?'failed':''"
          :hints="formErrors.m_lokasistuffing_id" valueField="id" displayField="nama_lokasi" :api="{
              url: `${store.server.url_backend}/operation/m_lokasistuffing`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
              }
            }" label="" placeholder="Pilih Depo" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'nama_lokasi',
              headerName:  'Nama',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'alamat',
              headerName:  'Alamat',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'catatan',
              headerName:  'Catatan',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            ]" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.kapal" @input="v=>values.kapal=v"
          :errorText="formErrors.kapal?'failed':''" :hints="formErrors.kapal" placeholder="Kapal" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.tipe_kontainer"
          @input="v=>values.tipe_kontainer=v" :errorText="formErrors.tipe_kontainer?'failed':''"
          :hints="formErrors.tipe_kontainer" placeholder="Tipe Kontainer" :check="false" />
      </div>
      <div class="w-full !mt-3">
      <FieldUpload :bind="{ readonly: !actionText }" class="!mt-0" :value="values.foto_berkas"
        @input="(v)=>values.foto_berkas=v" :maxSize="10"
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
           }" :hints="formErrors.foto_berkas" :errorText="formErrors.foto_berkas?'failed':''" label="" placeholder="Foto Berkas"
        accept="image/*" :check="false" />
      </div>
      <div class="w-full !mt-3">
        <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.catatan"
          :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
          type="textarea" placeholder="Catatan" :check="false" />
      </div>
    </div>
    <!-- FORM END -->
    <hr>
    <div class="flex flex-row items-center justify-end space-x-2 p-2">
      <i class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
      <button
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click="onReset(true)"
      >
        <icon fa="times" />
        Reset
      </button>
      <button class="text-sm rounded-md py-2 px-3 text-white bg-yellow-600 hover:bg-yellow-700 flex gap-x-1 items-center
        transition-colors duration-300" v-show="actionText" @click="onSave(true)">
            <icon fa="paper-plane" />
            <span>Post</span>
      </button>
      <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click="onSave(false)"
      >
        <icon fa="save" />
        Simpan
      </button>
    </div>
  </div>

  @endverbatim
  @endif