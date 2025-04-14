<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">PREMI</h1>
  </div>
  <div class="flex justify-between items-center px-4 py-1">

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
        <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-amber-600 text-white hover:bg-amber-600' 
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
        <button @click="filterShowData('APPROVED')" :class="filterButton === 'APPROVED' ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          APPROVED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('REVISED')" :class="filterButton === 'REVISED' ? 'bg-purple-600 text-white hover:bg-purple-600' 
          : 'border border-purple-600 text-purple-600 bg-white hover:bg-purple-600 hover:text-white'"
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
        <h1 class="text-lg font-bold leading-none">Form Premi</h1>
        <p class="text-gray-100 leading-none">Transaction Premi</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 items-start">
    <!-- col-span-2 -->
    <!-- <div>
      <FieldX class="w-full !mt-3" :bind="{ readonly: !actionText }" 
        :value="values.no_draft" :errorText="formErrors.no_draft?'failed':''"
        @input="v=>values.no_draft=v" :hints="formErrors.no_draft" 
        placeholder="Generate by System" label="No Draft" :check="false"
      />
    </div> -->
    <!-- <div>
      <FieldX class="w-full !mt-3" :bind="{ readonly: !actionText }" 
        :value="values.no_premi" :errorText="formErrors.no_premi?'failed':''"
        @input="v=>values.no_premi=v" :hints="formErrors.no_premi" 
        placeholder="Generate by System" label="No Premi" :check="false"
      />
    </div> -->
    <div>
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/t_spk_angkutan`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest:false,
          // transform:false,
          // join:true,
          // override:true,
          // where:`this.is_active=true`,
          // searchfield:'this.no_id, this.nip, this.nama, this.alamat_domisili',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili' 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="no_spk" valueField="id" :bind="{ readonly: !actionText }" :value="data.t_spk_angkutan_id"
        @input="(v)=>data.t_spk_angkutan_id=v" :errorText="formErrors.t_spk_angkutan_id?'failed':''"
        :hints="formErrors.t_spk_angkutan_id" @update:valueFull="(res)=>{
          // getTarifPremi(res);
        if(res){
          $log(res)
          getTarifPremi(res.t_detail_npwp_container_1_id, res.t_detail_npwp_container_2_id);
          getDetailNPWPContainer(res.t_detail_npwp_container_1_id, res.t_detail_npwp_container_2_id);
          data.no_container = (res['no_container_1'] ?? '-') +', ' + (res['no_container_2'] ?? '-');
          data.tanggal_out = res.tanggal_out;
          data.waktu_out = res.waktu_out;
          data.no_bon_sementara = res.no_bon_sementara;
          data.tanggal_bon = res.tanggal_bon;
          data.head_deskripsi2 = res['head.deskripsi2']
          data.tanggal_in = res.tanggal_in;
          data.waktu_in = res.waktu_in;
          // data.ukuran_container = (res['t_detail_npwp_container_1.ukuran'] ?? '-')+', '+ (res['t_detail_npwp_container_2.ukuran'] ?? '-');
          data.m_karyawan_id = res['supir.id'];
          data.chasis = res.chasis;
          data.total_sangu = res.sangu;
          data.sektor = (res['sektor1.deskripsi'] ?? '-') +', '+ (res['sektor1.deskripsi'] ?? '-');
          data.ke = res.ke;
          data.dari = res.dari;
        }
        else {
          data.tarif_premi = '';
          data.no_container = '';
          data.no_order = '';
          data.no_angkutan = '';
          data.tanggal_out = '';
          data.waktu_out = '';
          data.no_bon_sementara = '';
          data.tanggal_bon = '';

          data.tanggal_in = '';
          data.waktu_in = '';
          data.ukuran_container = '';
          data.m_karyawan_id = '';
          data.chasis = '';
          data.total_sangu = '';
          data.sektor = '';
          data.ke = '';
          data.dari = '';
        }

      }" :errorText="formErrors.t_detail_npwp_container_1_id?'failed':''" class="w-full !mt-3"
        :hints="formErrors.t_detail_npwp_container_1_id" placeholder="Pilih No SPK" :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'no_spk',
          headerName: 'No. SPK',
          sortable: true, resizable: true, filter: false,
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'Tipe SPK',
          field: 'tipe_spk',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Supir',
          field: 'supir.nama',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Sektor',
          field: 'sektor1.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Sangu',
          field: 'sangu',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
      ]" />
    </div>
    <div>
      <FieldX class="w-full !mt-3" :bind="{ disabled: true, clearable:true }" :value="data.status"
        @input="v=>data.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status"
        placeholder="Pilih Status" label="Status" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-1/2 !mt-3" :value="data.tgl" :errorText="formErrors.tgl?'failed':''"
        @input="v=>data.tgl=v" :hints="formErrors.tgl" :check="false" type="date" label="Tanggal"
        placeholder="Pilih Tanggal" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_container" @input="v=>data.no_container=v"
        :errorText="formErrors.no_container?'failed':''" :hints="formErrors.no_container" label="No. Container"
        placeholder="Masukan No. Container" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_order" @input="v=>data.no_order=v"
        :errorText="formErrors.no_order?'failed':''" :hints="formErrors.no_order" label="No. Order"
        placeholder="Masukan No. Order" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.head_deskripsi2" @input="v=>data.head_deskripsi2=v"
        :errorText="formErrors.head_deskripsi2?'failed':''" :hints="formErrors.head_deskripsi2" label="No. Head"
        placeholder="Masukan No. Head" :check="false" />
    </div>
    <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start">
      <FieldX :bind="{ disabled: true, readonly: true }" class="w-full !mt-3" :value="data.tanggal_out"
        :errorText="formErrors.tanggal_out?'failed' :''" @input="v=>data.tanggal_out=v" :hints="formErrors.tanggal_out"
        :check="false" type="date" label="Tanggal Out" placeholder="Pilih Tanggal Out" />
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, readonly: true }" :value="data.waktu_out"
        @input="v=>data.waktu_out=v" :errorText="formErrors.waktu_out?'failed':''" :hints="formErrors.waktu_out"
        valueField="id" displayField="key" :options="[{'id' : 'Pagi', 'key' : 'Pagi'},
      {'id' : 'Siang', 'key' : 'Siang'},
      {'id' : 'Sore', 'key' : 'Sore'}]" placeholder="Pilih Waktu Out" label="Waktu Out" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.ukuran_container"
        @input="v=>data.ukuran_container=v" :errorText="formErrors.ukuran_container?'failed':''"
        :hints="formErrors.ukuran_container" label="Ukuran Container" placeholder="Masukan Ukuran Container"
        :check="false" />
    </div>
    <div>
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/m_kary`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest:false,
          // transform:false,
          // join:true,
          // override:true,
          // where:`this.is_active=true`,
          // searchfield:'this.no_id, this.nip, this.nama, this.alamat_domisili',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili' 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="nama" valueField="id" :bind="{  disabled: true, readonly: true }" :value="data.m_karyawan_id"
        @input="(v)=>data.m_karyawan_id=v" @update:valueFull="(response)=>{
        $log(response);
      }" :errorText="formErrors.m_karyawan_id?'failed':''" class="w-full !mt-3" :hints="formErrors.m_karyawan_id"
        placeholder="Supir" :check='false' :columns="[
        {
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
          sortable: true, resizable: true, filter: false,
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'Nama',
          field: 'nama',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        
      ]" />
    </div>
    <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start">
      <FieldX :bind="{  disabled: true, readonly: true }" class="w-full !mt-3" :value="data.tanggal_in"
        :errorText="formErrors.tanggal_in?'failed' :''" @input="v=>data.tanggal_in=v" :hints="formErrors.tanggal_in"
        :check="false" type="date" label="Tanggal In" placeholder="Pilih Tanggal In" />
      <FieldSelect class="w-full !mt-3" :bind="{  disabled: true, readonly: true }" :value="data.waktu_in"
        @input="v=>data.waktu_in=v" :errorText="formErrors.waktu_in?'failed':''" :hints="formErrors.waktu_in"
        valueField="id" displayField="key" :options="[{'id' : 'Pagi', 'key' : 'Pagi'},
      {'id' : 'Siang', 'key' : 'Siang'},
      {'id' : 'Sore', 'key' : 'Sore'}]" placeholder="Pilih Waktu In" label="Waktu In" :check="false" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, clearable:true }" :value="data.chasis"
        @input="v=>data.chasis=v" :errorText="formErrors.chasis?'failed':''" :hints="formErrors.chasis" valueField="id"
        displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='CHASIS'`
              }
          }" label="Chasis 1" placeholder="Pilih Chasis 1" fa-icon="sort-desc" :check="false" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="data.total_sangu"
        @input="v=>data.total_sangu=v" :errorText="formErrors.total_sangu?'failed':''" :hints="formErrors.total_sangu"
        placeholder="Total Sangu" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.sektor" @input="v=>data.sektor=v"
        :errorText="formErrors.sektor?'failed':''" :hints="formErrors.sektor" placeholder="Sektor" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.ke" @input="v=>data.ke=v"
        :errorText="formErrors.ke?'failed':''" :hints="formErrors.ke" placeholder="Ke" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.dari" @input="v=>data.dari=v"
        :errorText="formErrors.dari?'failed':''" :hints="formErrors.dari" placeholder="Dari" :check="false" />
    </div>
    <!-- <div>
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/m_tarif_premi`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest:false,
          // transform:false,
          // join:true,
          // override:true,
          // where:`this.is_active=true`,
          // searchfield:'this.no_id, this.nip, this.nama, this.alamat_domisili',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili' 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="premi" valueField="id" :bind="{ readonly: !actionText }" :value="data.m_tarif_premi_id"
        @input="(v)=>data.m_tarif_premi_id=v" @update:valueFull="(dtupt)=>{
        $log(dtupt);
        if(dtupt){
          data.no_premi = dtupt.premi;
        }
        else{
          data.no_premi = 0;
        }
      }" :errorText="formErrors.m_tarif_premi_id?'failed':''" class="w-full !mt-3" :hints="formErrors.m_tarif_premi_id"
        placeholder="Tarif Premi" :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'no_tarif_premi',
          headerName: 'No. Tarif Premi',
          sortable: true, resizable: true, filter: false,
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'Sektor',
          field: 'sektor.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'No. Head',
          field: 'no_head.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Trip',
          field: 'trip',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Premi',
          field: 'premi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
      ]" />
    </div> -->
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.tarif_premi"
        @input="v=>data.tarif_premi=v" :errorText="formErrors.tarif_premi?'failed':''" :hints="formErrors.tarif_premi"
        placeholder="Tarif Premi" label="Tarif Premi" :check="false" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.tol" @input="v=>data.tol=v"
        :errorText="formErrors.tol?'failed':''" :hints="formErrors.tol" placeholder="Tol" label="Tol" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>data.catatan=v" :hints="formErrors.catatan"
        :check="false" type="textarea" label="Catatan" placeholder="Catatan" />
    </div>
  </div>
  <hr />

  <!-- START TABLE DETAIL -->
  <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <button class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300" @click="addDetail">
        <icon fa="plus" size="sm" />
        <span>Add To List</span>
      </button>
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Keterangan</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nominal</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Catatan</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              Action</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="i" class="border-t" v-show="detailArr.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i+1 }}.
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              <FieldX type="textarea" :bind="{ readonly: !actionText }" :value="detailArr[i].keterangan"
                :errorText="formErrors.keterangan?'failed':''" @input="v=>detailArr[i].keterangan=v"
                :hints="formErrors.keterangan" label="" placeholder="Keterangan" :check="false" />
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              <FieldNumber type="number" :bind="{ readonly: !actionText }" :value="detailArr[i].nominal"
                :errorText="formErrors.nominal?'failed':''" @input="v=>detailArr[i].nominal=v"
                :hints="formErrors.nominal" label="" placeholder="Nominal" :check="false" />
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              <FieldX type="textarea" :bind="{ readonly: !actionText }" :value="detailArr[i].catatan"
                :errorText="formErrors.catatan?'failed':''" @input="v=>detailArr[i].catatan=v"
                :hints="formErrors.catatan" label="" placeholder="Catatan" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <div class="flex justify-center">
                <button type="button" @click="delDetail(i)" :disabled="!actionText">
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
  <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start justify-center w-md mb-3">
    <label class="!mt-4 !ml-3"> Total Premi : </label>
    <FieldNumber :bind="{ readonly: true }" class="w-full content-center !mt-3" :value="data.total_premi"
      @input="v=>data.total_premi=v" :errorText="formErrors.total_premi?'failed':''" :hints="formErrors.total_premi"
      :check="false" />
  </div>
  <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start justify-center w-md mb-3">
    <label class="!mt-4 !ml-3"> Hutang Supir : </label>
    <FieldNumber :bind="{ readonly: !actionText }" class="w-full content-center !mt-3" :value="data.hutang_supir"
      @input="v=>data.hutang_supir=v" :errorText="formErrors.hutang_supir?'failed':''" :hints="formErrors.hutang_supir"
      :check="false" />
  </div>
  <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start justify-center w-md mb-3">
    <label class="!mt-4 !ml-3"> Hutang Supir Yang Mau Dibayarkan : </label>
    <FieldNumber :bind="{ readonly: !actionText }" class="w-full content-center !mt-3" :value="data.hutang_dibayar"
      @input="v=>data.hutang_dibayar=v" :errorText="formErrors.hutang_dibayar?'failed':''" :hints="formErrors.hutang_dibayar"
      :check="false" />
  </div>
  <div class="grid grid-cols-2 gap-y-2 gap-x-2 items-start justify-center w-md mb-3">
    <label class="!mt-4 !ml-3"> Total Premi Yang Diterima : </label>
    <FieldNumber :bind="{ readonly: !actionText }" class="w-full content-center !mt-3" :value="data.total_premi_diterima"
      @input="v=>data.total_premi_diterima=v" :errorText="formErrors.total_premi_diterima?'failed':''" :hints="formErrors.total_premi_diterima"
      :check="false" />
  </div>
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
    <button v-if="(((actionText=='Edit' || actionText=='Create' || actionText=='Copy') && (data.status=='DRAFT' || data.status=='REVISED')))" class="text-sm rounded py-2 px-2.5 text-white bg-purple-600 hover:bg-purple-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="sendApproval">
      <icon fa="location-arrow" />
      <span>Send Approval</span>
    </button>
  </div>
  <hr v-show="isApproval" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="isApproval">
    <button class="text-sm rounded py-2 px-2.5 text-white bg-green-600 hover:bg-green-700 flex gap-x-1 items-center 
        transition-colors duration-300" @click="progress('APPROVED')">
      <!-- <icon fa="times" />sq -->
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