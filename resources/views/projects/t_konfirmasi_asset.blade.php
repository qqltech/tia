@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">Konfirmasi Asset</h1>
  </div>
  <div class="flex justify-between items-center px-4 py-1">

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
        <h1 class="text-20px font-bold">Form Transaksi Asset</h1>
        <p class="text-gray-100">Untuk mengatur Konfirmasi Transaksi Asset</p>
      </div>
    </div>
  </div>
  <!-- HEADER END -->

  <!-- FORM START -->
  <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row p-4 gap-3">
    <div class=" w-full !mt-3">
      <FieldPopup class="!mt-0" displayField="no_lpb" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.t_lpb_id" @input="(v)=>values.t_lpb_id=v" :errorText="formErrors.t_lpb_id?'failed':''"
        :hints="formErrors.t_lpb_id" placeholder="Pilih LPB" label="Nomor LPB" :check='false' @update:valueFull="(dt)=>{
            values.amt=dt['t_po.total_amount']
            values.tgl_asset=dt['tanggal_lpb']
            $log(dt)
            }" :api="{
            url: `${store.server.url_backend}/operation/t_lpb`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:true,
              transform: true,
              //scopes:'GetAmount',
              searchfield:'this.no_lpb , this.tanggal_lpb , this.no_sj_supplier , this.tanggal_sj_supplier',
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
            field: 'no_lpb',
            headerName: 'Nomor LPB',
            sortable: true, 
            resizable: true, 
            filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            
          },
          {
            flex: 1,
             field: 'tanggal_lpb',
            headerName: 'Tanggal LPB',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'no_sj_supplier',
            headerName: 'Nomor SJ Supplier',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'tanggal_sj_supplier',
            headerName: 'Tanggal SJ Supplier',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'm_supplier.nama',
            headerName: 'Nama Supplier',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'm_supplier.negara',
            headerName: 'Negara Supplier',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          ]" />
    </div>
    <div class="w-full !mt-3 flex space-x-3">
      <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.kode" :errorText="formErrors.kode?'failed':''"
        @input="v=>values.kode=v" :hints="formErrors.kode" placeholder="Kode Asset" label="Kode Asset" :check="false" />
    </div>
    <div class=" w-full !mt-3">
      <FieldPopup class="!mt-0" displayField="nama_item" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.m_item_id" @input="(v)=>values.m_item_id=v" :errorText="formErrors.m_item_id?'failed':''"
        :hints="formErrors.m_item_id" placeholder="Pilih Asset" label="Nama Asset" :check='false' @update:valueFull="(dt)=>{
              values.kode=dt['kode']
                }" :api="{
            url: `${store.server.url_backend}/operation/m_item`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              searchfield:'this.no_lpb , this.tanggal_lpb , this.no_sj_supplier , this.tanggal_sj_supplier',
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
            field: 'kode',
            headerName: 'Kode Asset',
            sortable: true, 
            resizable: true, 
            filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            
          },
          {
            flex: 1,
             field: 'nama_item',
            headerName: 'Nama Asset',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'tipe_item',
            headerName: 'Tipe Item',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          ]" />
    </div>
    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" displayField="nama" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.pic" @input="(v)=>values.pic=v" :errorText="formErrors.pic?'failed':''" :hints="formErrors.pic"
        placeholder="Pilih PIC" label="PIC" :check='false' :api="{
            url: `${store.server.url_backend}/operation/m_kary`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              searchfield:'this.no_lpb , this.tanggal_lpb , this.no_sj_supplier , this.tanggal_sj_supplier',
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
            field: 'nip',
            headerName: 'NIP',
            sortable: true, 
            resizable: true, 
            filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            
          },
          {
            flex: 1,
             field: 'nama',
            headerName: 'Nama',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          {
            flex: 1,
             field: 'no_id',
            headerName: 'Nomor ID',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            resizable: true, 
            filter: false,
          },
          ]" />
    </div>
    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" displayField="nama" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.kategori_id" @input="(v)=>values.kategori_id=v" :errorText="formErrors.kategori_id?'failed':''"
        :hints="formErrors.kategori_id" placeholder="Pilih Kategori" label="Kategori" :check='false' :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
               where:`this.group='KATEGORI ASSET'`,
              searchfield:'this.no_lpb , this.tanggal_lpb , this.no_sj_supplier , this.tanggal_sj_supplier',
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
            field: 'kode',
            headerName: 'Kode',
            sortable: true, 
            resizable: true, 
            filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            
          },
          {
            flex: 1,
             field: 'deskripsi',
            headerName: 'Deskripsi',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            
            resizable: true, 
            filter: false,
          },
          ]" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.tgl_asset"
        :errorText="formErrors.tgl_asset?'failed':''" @input="v=>values.tgl_asset=v" :hints="formErrors.tgl_asset"
        type="date" placeholder="Tanggal LPB" label="Tanggal Asset" :check="false" />
    </div>

    <div class="w-full !mt-3 flex space-x-3">
      <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.amt" :errorText="formErrors.amt?'failed':''"
        @input="v=>values.amt=v" :hints="formErrors.amt" placeholder="Harga Perolehan" label="Harga Perolehan"
        :check="false" />
    </div>

    <div class="w-full !mt-3 flex space-x-3">
      <FieldNumber class="!mt-0 w-[70%]" :bind="{ readonly: !actionText }" :value="values.masa_manfaat"
        :errorText="formErrors.masa_manfaat?'failed':''" @input="v=>values.masa_manfaat=v"
        :hints="formErrors.masa_manfaat" placeholder="Masukan Masa Manfaat" label="Masa Manfaat" :check="false" />
      <span class="!mt-0 text-xl font-bold">BLN</span>
    </div>

    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.tgl_awal"
        :errorText="formErrors.tgl_awal?'failed':''" @input="v=>values.tgl_awal=v" :hints="formErrors.tgl_awal"
        type="date" placeholder="Tanggal Awal Susut" label="Tanggal Awal Susut" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.tgl_akhir"
        :errorText="formErrors.tgl_akhir?'failed':''" @input="v=>values.tgl_akhir=v" :hints="formErrors.tgl_akhir"
        type="date" placeholder="Tanggal Akhir Susut" label="Tanggal Akhir Susut" :check="false" />
    </div>

    <div class="w-full !mt-3 flex space-x-3">
      <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.nilai_susut"
        :errorText="formErrors.nilai_susut?'failed':''" @input="v=>values.nilai_susut=v" :hints="formErrors.nilai_susut"
        placeholder="Nilai Penyusutan" label="Nilai Penyusutan" :check="false" />
    </div>

    <div class="w-full !mt-3 flex space-x-3">
      <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.akum_susut"
        :errorText="formErrors.akum_susut?'failed':''" @input="v=>values.akum_susut=v" :hints="formErrors.akum_susut"
        placeholder="Akumulasi Penyusutan" label="Akumulasi Penyusutan" :check="false" />
    </div>

    <div class="w-full !mt-3 flex space-x-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.nilai_min"
        :errorText="formErrors.nilai_min?'failed':''" @input="v=>values.nilai_min=v" :hints="formErrors.nilai_min"
        placeholder="Nilai Minimal" label="Nilai Minimal" :check="false" />
    </div>

    <div class="w-full !mt-3 flex space-x-3">
      <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.nilai_buku"
        :errorText="formErrors.nilai_buku?'failed':''" @input="v=>values.nilai_buku=v" :hints="formErrors.nilai_buku"
        placeholder="Nilai Buku" label="Nilai Buku" :check="false" />
    </div>


    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" displayField="nama_coa" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.m_perkiraan_asset_id" @input="(v)=>values.m_perkiraan_asset_id=v"
        :errorText="formErrors.m_perkiraan_asset_id?'failed':''" :hints="formErrors.m_perkiraan_asset_id"
        placeholder="Perkiraan Asset" label="Perkiraan Asset" :check='false' :api="{
            url: `${store.server.url_backend}/operation/m_coa`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              join:false,
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
      <FieldPopup class="!mt-0" displayField="nama_coa" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.m_perkiraan_akun_penyusutan" @input="(v)=>values.m_perkiraan_akun_penyusutan=v"
        :errorText="formErrors.m_perkiraan_akun_penyusutan?'failed':''" :hints="formErrors.m_perkiraan_akun_penyusutan"
        placeholder="Pilih Perkiraan Akun Penyusutan" label="Perkiraan Akun Penyusutan" :check='false' :api="{
            url: `${store.server.url_backend}/operation/m_coa`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              join:false,
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
      <FieldPopup class="!mt-0" displayField="nama_coa" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.m_perkiraan_by_akun_penyusutan" @input="(v)=>values.m_perkiraan_by_akun_penyusutan=v"
        :errorText="formErrors.m_perkiraan_by_akun_penyusutan?'failed':''" :hints="formErrors.m_perkiraan_by_akun_penyusutan"
        placeholder="Perkiraan By Akun Penyusutan" label="Perkiraan By Akun Penyusutan" :check='false' :api="{
            url: `${store.server.url_backend}/operation/m_coa`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              join:false,
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

    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" type="textarea" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        label="Catatan" placeholder="Catatan" :check="false" />
    </div>

    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, clearable:true }" :value="values.status"
        @input="v=>values.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status" valueField="id"
        displayField="key" :options="[{'id' : 'DRAFT' , 'key' : 'DRAFT'},
      {'id' : 'Posted', 'key' : 'Posted'},
      {'id' : 'APPROVAL', 'key' : 'APPROVAL'},
      {'id' : 'APPROVED', 'key' : 'APPROVED'}, 
      {'id' : 'COMPLETED', 'key' : 'COMPLETED'}, 
      {'id' : 'CLOSED', 'key' : 'CLOSED'}, 
      {'id' : 'CANCEL', 'key' : 'CANCEL'}, 
      {'id' : 'REJECTED', 'key' : 'REJECTED'},
      {'id' : 'REVISED', 'key' : 'REVISED'}]" fa-icon="sort-desc" placeholder="Pilih Status" label="Status"
        :check="false" />
    </div>

  </div>
  <!-- FORM END -->
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
    <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click="onSave"
      >
        <icon fa="save" />
        Simpan
      </button>
  </div>
  <hr>

  <!-- DETAIL -->
  <!-- detail -->
  <div class="p-4">
    <button v-show="actionText" @click="addDetail" type="button" class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
          <icon fa="plus" />
          Add to List
        </button>

    <div class="mt-4" style="overflow-x: auto; border: 1px solid #CACACA;">
      <table class="w-[120%] table-auto border border-[#CACACA]">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[1%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Tanggal Sebelum
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nilai Akun Sebelum
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nilai Buku Sebelum
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nilai Penyusutan
            </td>

            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nilai Akun Setelah
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nilai Buku Setelah
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Status
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] w-[5%]">
              Action
            </td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.tgl_penyusutan" @input="v=>item.tgl_penyusutan=v" type="date" :check="false"
                :errorText="formErrors.tgl_penyusutan?'failed':''" :hints="formErrors.tgl_penyusutan" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" :check="false" class="w-full py-2 !mt-0"
                :value="item.nilai_akun_sebelum" @input="v=>item.nilai_akun_sebelum=v"
                :errorText="formErrors.nilai_akun_sebelum?'failed':''" :hints="formErrors.nilai_akun_sebelum" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" :check="false" class="w-full py-2 !mt-0"
                :value="item.nilai_buku_sebelum" @input="v=>item.nilai_buku_sebelum=v"
                :errorText="formErrors.nilai_buku_sebelum?'failed':''" :hints="formErrors.nilai_buku_sebelum" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" :check="false" class="w-full py-2 !mt-0"
                :value="item.nilai_penyusutan" @input="v=>item.nilai_penyusutan=v"
                :errorText="formErrors.nilai_penyusutan?'failed':''" :hints="formErrors.nilai_penyusutan" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" :check="false" class="w-full py-2 !mt-0"
                :value="item.nilai_akun_setelah" @input="v=>item.nilai_akun_setelah=v"
                :errorText="formErrors.nilai_akun_setelah?'failed':''" :hints="formErrors.nilai_akun_setelah" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" :check="false" class="w-full py-2 !mt-0"
                :value="item.nilai_buku_setelah" @input="v=>item.nilai_buku_setelah=v"
                :errorText="formErrors.nilai_buku_setelah?'failed':''" :hints="formErrors.nilai_buku_setelah" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldSelect :bind="{ disabled: !actionText, clearable:false }" :check="false" class="w-full py-2 !mt-0"
                :value="item.status" @input="v=>item.status=v" :errorText="formErrors.status?'failed':''"
                valueField="key" displayField="key" :options="['NEW' , 'OLD']" :hints="formErrors.status" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <div class="flex justify-center">
                <button type="button" @click="removeItem(i)" :disabled="!actionText" title="Hapus">
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

@endverbatim
@endif