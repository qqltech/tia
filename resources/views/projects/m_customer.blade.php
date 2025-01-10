<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true)" :class="filterButton === true ? 'bg-green-600 text-white hover:bg-green-600' 
        : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'" class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
          Active
        </button>
        <div class="flex my-auto h-4 w-px bg-[#6E91D1]"></div>
        <button @click="filterShowData(false)" :class="filterButton === false ? 'bg-red-600 text-white hover:bg-red-600' 
        : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'" class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
          InActive
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
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Customer</h1>
        <p class="text-gray-100">Master Customer</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldPopup class="w-full !mt-3" :api="{
            url: `${store.server.url_backend}/operation/m_customer_group`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              where:`this.is_active = '1'`
            },
            onsuccess: (response) => {
              response.page = response.current_page
              response.hasNext = response.has_next
              return response;
            }
          }" displayField="nama" valueField="id" :bind="{ readonly: !actionText }" :value="values.m_customer_group_id"
        @input="(v)=>values.m_customer_group_id=v" @update:valueFull="(response)=>{
          values.m_customer_group_id = response.id;
          $log(response);
        }" :errorText="formErrors.m_customer_group_id?'failed':''" class="w-full !mt-3"
        :hints="formErrors.m_customer_group_id" placeholder="Pilih Customer Group" :check='false' :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'nama',
            headerName: 'Nama',
            sortable: false, resizable: true, filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          }
          ]" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="values.is_active"
        @input="v=>values.is_active=v" :errorText="formErrors.is_active?'failed':''" :hints="formErrors.is_active"
        valueField="id" displayField="key" :options="[{'id' : 1 , 'key' : 'Active'},{'id': 0, 'key' : 'InActive'}]"
        placeholder="Pilih Status" label="Status" :check="true" />
    </div>
    <div>
      <FieldX class="w-full !mt-3" :bind="{ readonly: !actionText }" :value="values.kode"
        :errorText="formErrors.kode?'failed':''" @input="v=>values.kode=v" :hints="formErrors.kode" placeholder="Kode"
        :check="false" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }"
        :value="values.jenis_perusahaan" @input="v=>values.jenis_perusahaan=v"
        :errorText="formErrors.jenis_perusahaan?'failed':''" :hints="formErrors.jenis_perusahaan" valueField="deskripsi"
        displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='JENIS PERUSAHAAN'`
              }
          }" label="Jenis Perusahaan" placeholder="Pilih Jenis Perusahaan" :check="true" />
    </div>
    <div>
      <FieldX class="w-full !mt-3" :bind="{ readonly: !actionText }" :value="values.nama_perusahaan"
        :errorText="formErrors.nama_perusahaan?'failed':''" @input="v=>{
            values.nama_perusahaan=v.toUpperCase()
            }" :hints="formErrors.nama_perusahaan" :check="false" label="Nama Perusahaan"
        placeholder="Masukan Nama Perusahaan" />
    </div>
    <div>
      <FieldX class="w-full !mt-3" :bind="{ readonly: !actionText }" :value="values.alamat"
        :errorText="formErrors.alamat?'failed':''" @input="v=>values.alamat=v" :hints="formErrors.alamat" :check="false"
        type="textarea" placeholder="Alamat" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="values.kota"
        @input="v=>values.kota=v" :errorText="formErrors.kota?'failed':''" :hints="formErrors.kota" valueField="key"
        displayField="key" :api="{
            url: 'https://backend.qqltech.com/kodepos/region/kota',
              onsuccess:function(responseJson){
                return { data: responseJson }
              }
        }" placeholder="Pilih Kota" :check="true" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled:(!values.kota) || !actionText, clearable:true }"
        :value="values.kecamatan" @input="v=>values.kecamatan=v" :errorText="formErrors.kecamatan?'failed':''"
        :hints="formErrors.kecamatan" valueField="key" displayField="key" :api="{
              url: 'https://backend.qqltech.com/kodepos/region/kecamatan',
              params:{
                  kota: values.kota
                },
                onsuccess:function(responseJson){
                  return { data: responseJson }
              }
          }" placeholder="Pilih Kecamatan" :check="true" />
    </div>
    <div>
      <FieldX class="w-full !mt-3" :bind="{ readonly: !actionText }" :value="values.kodepos"
        :errorText="formErrors.kodepos?'failed':''" @input="v=>values.kodepos=v" :hints="formErrors.kodepos"
        :check="false" label="Kode Pos" placeholder="Masukan Kode Pos" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="values.top"
        @input="v=>values.top=v" :errorText="formErrors.top?'failed':''" :hints="formErrors.top" valueField="deskripsi"
        displayField="deskripsi" :api="{          
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='TOP'`
              }
          }" placeholder="Pilih TOP (Hari)" label="TOP (Hari)" :check="true" />
    </div>
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3" :value="values.taxable"
        @input="v=>values.taxable=v" :errorText="formErrors.taxable?'failed':''" :hints="formErrors.taxable"
        valueField="id" displayField="key" :options="[{'id' : 1 , 'key' : 'Ya'},{'id': 0, 'key' : 'Tidak'}]"
        label="Taxable" placeholder="Pilih Taxable" :check="true" />
    </div>
    <div>
      <FieldNumber class="w-full !mt-3" :bind="{ readonly: !actionText }" :value="values.tolerance"
        @input="(v)=>values.tolerance=v" :errorText="formErrors.tolerance?'failed':''" :hints="formErrors.tolerance"
        label="Tolerance (Hari)" placeholder="Masukkan tolerance (Hari)" :check="false" />
    </div>
    <div v-if="store.user.role === 'accounting'">
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:false }" :value="values.coa_piutang"
        @input="v=>values.coa_piutang=v" :errorText="formErrors.coa_piutang?'failed':''" :hints="formErrors.coa_piutang"
        valueField="id" displayField="nama_coa" :api="{
              url:`${store.server.url_backend}/operation/m_coa`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false
              }
          }" placeholder="Pilih Salah Satu COA" label="Perkiraan Piutang" :check="true" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_tlp1"
        :errorText="formErrors.no_tlp1?'failed':''" @input="v=>values.no_tlp1=v" :hints="formErrors.no_tlp1"
        :check="false" label="No. Telp 1" placeholder="Masukan No. Telp 1" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_tlp2"
        :errorText="formErrors.no_tlp2?'failed':''" @input="v=>values.no_tlp2=v" :hints="formErrors.no_tlp2"
        :check="false" label="No. Telp 2" placeholder="Masukan No. Telp 2" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_tlp3"
        :errorText="formErrors.no_tlp3?'failed':''" @input="v=>values.no_tlp3=v" :hints="formErrors.no_tlp3"
        :check="false" label="No. Telp 3" placeholder="Masukan No. Telp 3" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.fax1"
        :errorText="formErrors.fax1?'failed':''" @input="v=>values.fax1=v" :hints="formErrors.fax1" :check="false"
        label="Fax 1" placeholder="Masukan Fax 1" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.fax2"
        :errorText="formErrors.fax2?'failed':''" @input="v=>values.fax2=v" :hints="formErrors.fax2" :check="false"
        label="Fax 2" placeholder="Masukan Fax 2" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.email"
        :errorText="formErrors.email?'failed':''" @input="v=>{
            validateEmail(v)}" :hints="formErrors.email" :check="false" type="email" label="Email"
        placeholder="Masukan Email" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.website"
        :errorText="formErrors.website?'failed':''" @input="v=>values.website=v" :hints="formErrors.website"
        :check="false" label="Website" placeholder="Masukan Website" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.cp1"
        :errorText="formErrors.cp1?'failed':''" @input="v=>values.cp1=v" :hints="formErrors.cp1" :check="false"
        label="Contact Person 1" placeholder="Masukan Contact Person 1" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="values.jabatan1"
        @input="v=>values.jabatan1=v" :errorText="formErrors.jabatan1?'failed':''" :hints="formErrors.jabatan1"
        valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where : `this.group='JABATAN'`
              }
          }" placeholder="Pilih Jabatan CP 1" :check="true" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_tlp_cp1"
        :errorText="formErrors.no_tlp_cp1?'failed':''" @input="v=>values.no_tlp_cp1=v" :hints="formErrors.no_tlp_cp1"
        :check="false" label="No. Telp CP 1" placeholder="Masukan No. Telp CP 1" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.email_cp1"
        :errorText="formErrors.email_cp1?'failed':''" @input="v=>{
            validateEmail2(v)}" :hints="formErrors.email_cp1" :check="false" label="Email CP 1"
        placeholder="Masukan Email CP 1" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.cp2"
        :errorText="formErrors.cp2?'failed':''" @input="v=>values.cp2=v" :hints="formErrors.cp2" :check="false"
        label="Contact Person 2" placeholder="Masukan Contact Person 2" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="values.jabatan2"
        @input="v=>values.jabatan2=v" :errorText="formErrors.jabatan2?'failed':''" :hints="formErrors.jabatan2"
        valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where : `this.group='JABATAN'`
              }
          }" label="" placeholder="Pilih Jabatan CP 2" :check="true" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_tlp_cp2"
        :errorText="formErrors.no_tlp_cp2?'failed':''" @input="v=>values.no_tlp_cp2=v" :hints="formErrors.no_tlp_cp2"
        :check="false" label="No. Telp CP 2" placeholder="Masukan No. Telp CP 2" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.email_cp2"
        :errorText="formErrors.email_cp2?'failed':''" @input="v=>{
            validateEmail3(v)}" :hints="formErrors.email_cp2" :check="false" label="Email CP 2"
        placeholder="Masukan Email CP 2" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        :check="false" type="textarea" label="Catatan" placeholder="Catatan" />
    </div>

    <FieldGeo :bind="{ readonly: !actionText }" @input="(v)=>maps=v" class="w-full !mt-3"
      :center="[-7.3244677, 112.7550714]" :errorText="formErrors.maps?'failed':''" :hints="formErrors.maps"
      geostring="POINT(112.7550714 -7.3244677)" :value="maps" placeholder="Maps" :check="false" />

    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.latitude"
        :errorText="formErrors.latitude?'failed':''" @input="v=>values.latitude=v" :hints="formErrors.latitude"
        :check="false" label="Latitude" placeholder="Latitude" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.longtitude"
        :errorText="formErrors.longtitude?'failed':''" @input="v=>values.longtitude=v" :hints="formErrors.longtitude"
        :check="false" label="Longtitude" placeholder="Longtitude" />
    </div>
    <div class="flex space-x-3 !mt-4 text-blue-600">
      <label class="col-start text-black gap-3" for="CustomStupleBox">Custom Stuple</label>
      <input type="checkbox" id="CustomStupleBox" v-model="values.custom_stuple" style="width: 20px; height: 20px;">
      <label for="CustomStupleBox">Ya</label>
    </div>

    <!-- START TABLE DETAIL NPWP -->
    <hr class="<md:col-span-1 col-span-3">

    <div class="flex items-stretch lg:w-[40%] text-sm overflow-x-auto <md:col-span-1 col-span-3">
      <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 0}"
            @click="activeTabIndex = 0"
          >
            Detail NPWP
          </button>
      <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 1}"
            @click="activeTabIndex = 1"
          >
            Detail Address
          </button>
      <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 2}"
            @click="activeTabIndex = 2"
          >
            Detail Nama
          </button>
    </div>

    <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 " v-if="activeTabIndex === 0">
      <div>
        <FieldX :bind="{ readonly: isDisabled }" class="w-full !mt-3" :value="valuesNpwp.no_npwp"
          :errorText="formErrorsNpwp.no_npwp ? 'failed' : ''" @input="v => valuesNpwp.no_npwp = v"
          :hints="formErrorsNpwp.no_npwp" placeholder="Masukan No. NPWP" label="No. NPWP" :check="false" />
      </div>
      <div>
        <FieldX :bind="{ readonly: isDisabled }" class="w-full !mt-3" :value="valuesNpwp.nama_npwp"
          :errorText="formErrorsNpwp.nama_npwp ? 'failed' : ''" @input="v => valuesNpwp.nama_npwp = v"
          :hints="formErrorsNpwp.nama_npwp" placeholder="Masukan Nama NPWP" label="Nama NPWP" :check="false" />
      </div>
      <div>
        <FieldX :bind="{ readonly: isDisabled }" class="w-full !mt-3" :value="valuesNpwp.alamat"
          :errorText="formErrorsNpwp.alamat ? 'failed' : ''" @input="v => valuesNpwp.alamat = v"
          :hints="formErrorsNpwp.alamat" type="textarea" placeholder="Masukan Alamat NPWP" label="Alamat NPWP"
          :check="false" />
      </div>
      <div>
        <FieldX :bind="{ readonly: isDisabled }" class="w-full !mt-3" :value="valuesNpwp.catatan"
          :errorText="formErrorsNpwp.catatan ? 'failed' : ''" @input="v => valuesNpwp.catatan = v"
          :hints="formErrorsNpwp.catatan" type="textarea" placeholder="Masukan Catatan" label="Catatan"
          :check="false" />
        <div class="!mt-2">
          <button :disabled="!actionText" @click="addDetail" type="button" class="bg-blue-600 text-white font-semibold 
            hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5 mt-3">
              <icon fa="plus" size="sm mr-0.5"/>
              Add to List
            </button>
        </div>
      </div>
      <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
        <table class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                No.</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Nama NPWP</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                No. NPWP</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Default</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Status</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                Action</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArr" :key="i" class="border-t" v-if="detailArr.length > 0">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="p-2 text-center border border-[#CACACA]" v-if="!item.is_edit">
                {{item.nama_npwp?? '-'}}
              </td>
              <td class="p-2 text-center border border-[#CACACA]" v-if="item.is_edit">
                <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="item.nama_npwp"
                  @input="v=>item.nama_npwp=v" placeholder="Masukan Nama NPWP" label="Nama NPWP" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]" v-if="!item.is_edit">
                {{item.no_npwp?? '-'}}
              </td>
              <td class="p-2 text-center border border-[#CACACA]" v-if="item.is_edit">
                <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="item.no_npwp"
                  @input="v=>item.no_npwp=v" placeholder="Masukan No. NPWP" label="No. NPWP" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <input
                      type="checkbox"
                      class="h-5 w-5 text-blue-500 rounded"
                      v-model="item.default"
                    >
              </td>
              <td class="p-2 text-center border border-[#CACACA]" v-if="!item.is_edit">
                <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-0"
                  :value="item.is_active" @input="v=>item.is_active=v" valueField="id" displayField="key"
                  :options="[{'id' : 1 , 'key' : 'Active'},{'id': 0, 'key' : 'InActive'}]" placeholder="Pilih Status"
                  label="" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <div class="flex justify-center space-x-2">
                  <button type="button" @click="cancelDetail(item,i)" :disabled="!actionText" v-if="item.is_edit">
                        <icon fa="times" class="text-red-600"/>
                      </button>
                  <button type="button" @click="removeDetail(i)" :disabled="!actionText" v-if="!item.is_edit">
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
    <!-- END OF DETAIL NPWP -->

    <!-- START Detail Address Table -->
    <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2 " v-if="activeTabIndex === 1">
      <div class="!mb-2">
        <button :disabled="!actionText" @click="addDetailAddr" type="button" class="bg-blue-600 text-white font-semibold 
            hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
              <icon fa="plus" size="sm mr-0.5"/>
              Add to List
        </button>
      </div>
      <div class="overflow-x-auto <md:col-span-1 col-span-3">
        <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                No.</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Lokasi Stuffing</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Alamat</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Status</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                Action</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArrAddr" :key="i" class="border-t" v-if="detailArrAddr.length > 0">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" :value="item.lokasi_stuff"
                  :errorText="formErrors.lokasi_stuff?'failed':''" @input="v=>item.lokasi_stuff=v"
                  :hints="formErrors.lokasi_stuff" label="" placeholder="Nama Lokasi Stuffing" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" :value="item.alamat"
                  :errorText="formErrors.alamat?'failed':''" @input="v=>item.alamat=v" :hints="formErrors.alamat"
                  label="" placeholder="Masukkan Alamat" :check="false" />
              </td>
              <td class="p-2 text-center font-semibold border border-[#CACACA]" v-if="!item.is_edit">
                <button
                      :class="['status-toggle px-2 inline-flex text-xs leading-5 font-semibold rounded-full', item.is_active ? 'bg-green-600 text-white' : 'bg-red-600 text-white']"
                      @click="toggleStatus(i)">
                      {{ item.is_active ? 'Active' : 'Inactive' }}
                    </button>
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div class="flex justify-center">
                  <button type="button" @click="delDetailAddr(i)" :disabled="!actionText">
                      <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                      </svg>
                    </button>
                </div>
              </td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="15" class="py-[20px]">
                No data to show
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- END TABLE DETAIL ADDRESS -->

    <!-- START DETAIL NAMA -->
    <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2" v-if="activeTabIndex === 2">
      <div class="overflow-x-auto <md:col-span-1 col-span-3">
        <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                No.</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Nama Customer</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                No. Telp Customer</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Email Customer</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Jabatan</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                Action</td>
            </tr>
          </thead>
          <tbody>
            <tr v-show="values.cp1">
              <td class="p-2 text-center border border-[#CACACA]">
                1.
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" :value="values.cp1" :errorText="formErrors.cp1?'failed':''"
                  @input="v=>values.cp1=v" :hints="formErrors.cp1" label="" placeholder="Masukkan Nama Customer"
                  :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" :value="values.no_tlp_cp1"
                  :errorText="formErrors.no_tlp_cp1?'failed':''" @input="v=>values.no_tlp_cp1=v"
                  :hints="formErrors.no_tlp_cp1" label="" placeholder="Masukkan No. Telp CP 1" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" :value="values.email_cp1"
                  :errorText="formErrors.email_cp1?'failed':''" @input="v=>values.email_cp1=v"
                  :hints="formErrors.email_cp1" label="" placeholder="Masukkan Email Customer" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: !actionText, clearable:false }" :value="values.jabatan1"
                  @input="v=>values.jabatan1=v" :errorText="formErrors.jabatan1?'failed':''"
                  :hints="formErrors.jabatan1" valueField="id" displayField="deskripsi" :api="{
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            where : `this.group='JABATAN'`
                          }
                      }" label="" placeholder="Pilih Jabatan" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div class="flex justify-center">
                  <button type="button" @click="removeDetailName1" :disabled="!actionText">
                      <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                      </svg>
                    </button>
                </div>
              </td>
            </tr>
            <tr v-show="values.cp2">
              <td class="p-2 text-center border border-[#CACACA]">
                2.
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" :value="values.cp2" :errorText="formErrors.cp2?'failed':''"
                  @input="v=>values.cp2=v" :hints="formErrors.cp2" label="" placeholder="Masukkan Nama Customer"
                  :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" :value="values.no_tlp_cp2"
                  :errorText="formErrors.no_tlp_cp2?'failed':''" @input="v=>values.no_tlp_cp2=v"
                  :hints="formErrors.no_tlp_cp2" label="" placeholder="Masukkan No. Telp CP 2" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" :value="values.email_cp2"
                  :errorText="formErrors.email_cp2?'failed':''" @input="v=>values.email_cp2=v"
                  :hints="formErrors.email_cp2" label="" placeholder="Masukkan Email Customer" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: !actionText, clearable:false }" :value="values.jabatan2"
                  @input="v=>values.jabatan2=v" :errorText="formErrors.jabatan2?'failed':''"
                  :hints="formErrors.jabatan2" valueField="id" displayField="deskripsi" :api="{
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            where : `this.group='JABATAN'`
                          }
                      }" label="" placeholder="Pilih Jabatan" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div class="flex justify-center">
                  <button type="button" @click="removeDetailName2" :disabled="!actionText">
                      <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                      </svg>
                    </button>
                </div>
              </td>
            </tr>
            <tr v-for="(item, i) in detailArrName" :key="i" class="border-t" v-show="detailArrName.length > 0">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + (values.cp1 && values.cp2 ? 3 : values.cp1 || values.cp2 ? 2 : 1) }}.
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" :value="detailArrName[i].nama"
                  :errorText="formErrors[i]?.nama?'failed':''" @input="v=>detailArrName[i].nama=v"
                  :hints="formErrors[i]?.nama" label="" placeholder="Masukkan Nama Customer" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" :value="detailArrName[i].no_tlp"
                  :errorText="formErrors[i]?.no_tlp?'failed':''" @input="v=>detailArrName[i].no_tlp=v"
                  :hints="formErrors[i]?.no_tlp" label="" placeholder="Masukkan No. Telp CP" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" :value="detailArrName[i].email"
                  :errorText="formErrorsName[i]?.email?'failed':''" @input="v=>{ 
                        detailArrName[i].email=v;
                        if(validEmail(v)){
                          formErrorsName[i].email = false;
                        } else {
                          formErrorsName[i].email = true;
                        }
                      }" :hints="formErrorsName[i]?.email" label="" placeholder="Masukkan Email Customer"
                  :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: !actionText, clearable:false }" :value="detailArrName[i].jabatan"
                  @input="v=>detailArrName[i].jabatan=v" :errorText="formErrorsName[i]?.jabatan?'failed':''"
                  :hints="formErrorsName[i]?.jabatan" valueField="id" displayField="deskripsi" :api="{
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            where : `this.group='JABATAN'`
                          }
                      }" label="" placeholder="Pilih Jabatan" :check="true" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div class="flex justify-center">
                  <button type="button" @click="delDetailName(i)" :disabled="!actionText">
                      <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                      </svg>
                    </button>
                </div>
              </td>
            </tr>
            <tr v-show="detailArrName.length <= 0 && !values.cp1 && !values.cp2" class="text-center">
              <td colspan="15" class="py-[20px]">
                No data to show
              </td>
            </tr>
            <tr class=" border-t border-gray-200">
              <td>
                <button class="w-full flex justify-center items-center text-blue-600 font-semibold transition-transform 
                    duration-300 transform 
                    hover:-translate-y-0.5 p-1.5"
                    @click="addDetailName">+</button>
              </td>
              <td colspan="2" class="border-l border-gray-200"></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>
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
    <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click="onSave"
      >
        <icon fa="save" />
        Simpan
      </button>
  </div>
</div>
@endverbatim
@endif