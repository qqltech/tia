<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true)" :class="filterButton === true ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          Active
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData(false)" :class="filterButton === false ? 'bg-red-600 text-white hover:bg-red-600' 
          : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          InActive
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
  <TableApi ref='apiTable' :api="table.api" :columns="table.columns" :actions="table.actions"
    class="max-h-[500px] pt-2 !px-4 !pb-8">
    <template #header>
      <div class="pb-13 h-full"></div>
    </template>
  </TableApi>
</div>
@else

<!-- FORM DATA -->
@verbatim
<div class="md:w-full pb-4">
  <div class="flex flex-col rounded-md shadow-md md:w-full bg-white">
    <div class="bg-blue-500 text-white rounded-t-md pt-2 pb-3 px-4">
      <div class="flex items-center gap-2">
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
        <div class="flex flex-col py-1 gap-1">
          <h1 class="text-lg font-bold leading-none">Form Karyawan</h1>
          <p class="text-gray-100 leading-none">Atur berbagai menu untuk apps</p>
        </div>
      </div>
    </div>

    <!-- HEADER -->
    <div class="pt-2 pb-10 px-4 grid grid-cols-3 gap-y-2 gap-x-4 sticky">
      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.nip" :errorText="formErrors.nip?'failed':''"
        @input="v=>data.nip=v" :hints="formErrors.nip" placeholder='NIP' :check="false" type="number" />
      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.nama" :errorText="formErrors.nama?'failed':''"
        @input="v=>data.nama=v" :hints="formErrors.nama" placeholder="Nama" :check="false" />
      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.no_id"
        :errorText="formErrors.no_id?'failed':''" @input="v=>data.no_id=v" :hints="formErrors.no_id"
        placeholder="No ID / KTP" :check="false" type="number" />

      <FieldSelect class="pt-1" :bind="{ disabled: !actionText, clearable:false }" :value="data.divisi"
        @input="v=>data.divisi=v" :errorText="formErrors.divisi?'failed':''" :hints="formErrors.divisi"
        valueField="deskripsi" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                notin: `this.is_active: false`,
                where: `this.group='DIVISI KARYAWAN'`
              }
          }" placeholder="Divisi" :check="false" fa-icon="sort-desc" />

      <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="pt-1" :value="data.jenis_kelamin"
        @input="v=>data.jenis_kelamin=v" :errorText="formErrors.jenis_kelamin?'failed':''"
        :hints="formErrors.jenis_kelamin" valueField="id" displayField="key" :options="['Laki Laki','Perempuan']"
        placeholder="Jenis Kelamin" :check="false" fa-icon="sort-desc" />
      <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="pt-1" :value="data.agama"
        @input="v=>data.agama=v" :errorText="formErrors.agama?'failed':''" :hints="formErrors.agama" valueField="id"
        displayField="key" :options="['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu']"
        placeholder="Agama" :check="false" fa-icon="sort-desc" />
      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.alamat_domisili"
        :errorText="formErrors.alamat_domisili?'failed':''" @input="v=>data.alamat_domisili=v"
        :hints="formErrors.alamat_domisili" placeholder="Alamat Domisili" :check="false" />
      <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="pt-1" :value="data.kota_domisili"
        @input="v=>data.kota_domisili=v" :errorText="formErrors.kota_domisili?'failed':''"
        :hints="formErrors.kota_domisili" valueField="key" displayField="key" :api="{
                  url: 'https://backend.qqltech.com/kodepos/region/kota',
                  onsuccess:function(responseJson){
                    return { data: responseJson }
                  }
              }" placeholder="Kota / Kabupaten Domisili" :check="false " @update:valueFull="() => {
                data.kecamatan = null
              }" fa-icon="sort-desc" />
      <FieldSelect :bind="{ disabled: (!data.kota_domisili) || !actionText, clearable:false }" class="pt-1"
        :value="data.kecamatan" @input="v=>data.kecamatan=v" :errorText="formErrors.kecamatan?'failed':''"
        :hints="formErrors.kecamatan" valueField="key" displayField="key" :api="{
                url: 'https://backend.qqltech.com/kodepos/region/kecamatan',
                params:{
                  kota: data.kota_domisili
                },
                onsuccess:function(responseJson){
                  return { data: responseJson }
                }
            }" placeholder="Kecamatan Domisili" :check="false" fa-icon="sort-desc" />
      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.alamat_ktp"
        :errorText="formErrors.alamat_ktp?'failed':''" @input="v=>data.alamat_ktp=v" :hints="formErrors.alamat_ktp"
        placeholder="Alamat KTP" :check="false" />
      <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="pt-1" :value="data.kota_ktp"
        @input="v=>data.kota_ktp=v" :errorText="formErrors.kota_ktp?'failed':''" :hints="formErrors.kota_ktp"
        valueField="key" displayField="key" :api="{
                url: 'https://backend.qqltech.com/kodepos/region/kota',
                onsuccess:function(responseJson){
                  return { data: responseJson }
                }
            }" placeholder="Kota / Kabupaten KTP" :check="false" fa-icon="sort-desc" />
      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.rt" :errorText="formErrors.rt?'failed':''"
        @input="v=>data.rt=v" :hints="formErrors.rt" placeholder="RT" :check="false" type="number" />
      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.rw" :errorText="formErrors.rw?'failed':''"
        @input="v=>data.rw=v" :hints="formErrors.rw" placeholder="RW" :check="false" type="number" />
      <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="pt-1" :value="data.status_perkawinan"
        @input="v=>data.status_perkawinan=v" :errorText="formErrors.status_perkawinan?'failed':''"
        :hints="formErrors.status_perkawinan" valueField="id" displayField="key"
        :options="['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']" placeholder="Status Perkawinan" :check="false"
        fa-icon="sort-desc" />
      <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="pt-1" :value="data.kota_lahir"
        @input="v=>data.kota_lahir=v" :errorText="formErrors.kota_lahir?'failed':''" :hints="formErrors.kota_lahir"
        valueField="key" displayField="key" :api="{
                url: 'https://backend.qqltech.com/kodepos/region/kota',
                onsuccess:function(responseJson){
                  return { data: responseJson }
                }
            }" placeholder="Kota Lahir" :check="false" fa-icon="sort-desc" />
      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.tgl_lahir"
        :errorText="formErrors.tgl_lahir?'failed':''" @input="v=>data.tgl_lahir=v" :hints="formErrors.tgl_lahir"
        placeholder="Tanggal Lahir" :check="false" :type="actionText ? 'date' : 'text'" />
      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.tgl_mulai"
        :errorText="formErrors.tgl_mulai?'failed':''" @input="v=>data.tgl_mulai=v" :hints="formErrors.tgl_mulai"
        placeholder="Tanggal Mulai" :check="false" :type="actionText ? 'date' : 'text'" />


      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.email"
        :errorText="formErrors.email?'failed':''" @input="v => validateEmail(v, 'email')" :hints="formErrors.email"
        placeholder="Email" :check="false" type="email" />


      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.no_tlp"
        :errorText="formErrors.no_tlp?'failed':''" @input="v=>data.no_tlp=v" :hints="formErrors.no_tlp"
        placeholder="Phone" :check="false" type="number" />

      <FieldSelect class="pt-1" :bind="{ disabled: !actionText, clearable:false }" :value="data.bank_id"
        @input="v=>data.bank_id=v" :errorText="formErrors.bank_id?'failed':''" :hints="formErrors.bank_id"
        valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                notin: `this.is_active: false`,
                where: `this.group='BANK'`
              }
          }" placeholder="Bank" :check="false" fa-icon="sort-desc" />

      <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.no_rek"
        :errorText="formErrors.no_rek?'failed':''" @input="v=>data.no_rek=v" :hints="formErrors.no_rek"
        placeholder="No Rekening" :check="false" type="number" />


      <FieldUpload :bind="{ readonly: !actionText }" class="pt-1" :value="data.foto_kary" @input="(v)=>data.foto_kary=v"
        :maxSize="10" :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
            url: `${store.server.url_backend}/operation/${endpointApi}/upload`,
            headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { field: 'foto_kary' },
            onsuccess: response=> {
              return response
            },
            onerror:(error)=>{
              swal.fire({ icon: 'error', text: error });
            },
           }" :hints="formErrors.foto_kary" :errorText="formErrors.foto_kary?'failed':''" placeholder="Foto Karyawan"
        accept="image/*" :check="false" />

      <FieldUpload :bind="{ readonly: !actionText }" class="pt-1" :value="data.foto_ktp" @input="(v)=>data.foto_ktp=v"
        :maxSize="10" :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
            url: `${store.server.url_backend}/operation/${endpointApi}/upload`,
            headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { field: 'foto_ktp' },
            onsuccess: response=> {
              return response
            },
            onerror:(error)=>{
              swal.fire({ icon: 'error', text: error });
            },
           }" :hints="formErrors.foto_ktp" :errorText="formErrors.foto_ktp?'failed':''" placeholder="Foto KTP"
        accept="image/*" :check="false" />

      <FieldUpload :bind="{ readonly: !actionText }" class="pt-1" :value="data.foto_kk" @input="(v)=>data.foto_kk=v"
        :maxSize="10" :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
            url: `${store.server.url_backend}/operation/${endpointApi}/upload`,
            headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { field: 'foto_kk' },
            onsuccess: response=> {
              return response
            },
            onerror:(error)=>{
              swal.fire({ icon: 'error', text: error });
            },
           }" :hints="formErrors.foto_kk" :errorText="formErrors.foto_kk?'failed':''" placeholder="Foto Kartu Keluarga"
        accept="image/*" :check="false" />

      <FieldUpload :bind="{ readonly: !actionText }" class="pt-1" :value="data.foto_bpjs_ks"
        @input="(v)=>data.foto_bpjs_ks=v" :maxSize="10"
        :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
            url: `${store.server.url_backend}/operation/${endpointApi}/upload`,
            headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { field: 'foto_bpjs_ks' },
            onsuccess: response=> {
              return response
            },
            onerror:(error)=>{
              swal.fire({ icon: 'error', text: error });
            },
           }" :hints="formErrors.foto_bpjs_ks" :errorText="formErrors.foto_bpjs_ks?'failed':''"
        placeholder="Foto BPJS Kesehatan" accept="image/*" :check="false" />

      <FieldUpload :bind="{ readonly: !actionText }" class="pt-1" :value="data.foto_bpjs_ktj"
        @input="(v)=>data.foto_bpjs_ktj=v" :maxSize="10"
        :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
            url: `${store.server.url_backend}/operation/${endpointApi}/upload`,
            headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { field: 'foto_bpjs_ktj' },
            onsuccess: response=> {
              return response
            },
            onerror:(error)=>{
              swal.fire({ icon: 'error', text: error });
            },
           }" :hints="formErrors.foto_bpjs_ktj" :errorText="formErrors.foto_bpjs_ktj?'failed':''"
        placeholder="Foto BPJS Ketenagakerjaan" accept="image/*" :check="false" />

      <div class="relative">
        <div class="top-0 left-0 absolute w-full">
          <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.catatan"
            :errorText="formErrors.catatan?'failed':''" @input="v=>data.catatan=v" :hints="formErrors.catatan"
            placeholder="Catatan" :check="false" type="textarea" />
        </div>
      </div>

      <!-- CHECKBOX COMPONENT -->
      <div class="flex flex-col gap-2 pt-2 ml-1">
        <label class="text-gray-600 text-xs font-semibold">Status</label>
        <div class="flex gap-2">
          <div class="relative">
            <input class="relative h-[16px] w-7 p-px appearance-none rounded-full bg-white border disabled:!cursor-default 
            hover:cursor-pointer after:content-[''] after:h-[10.5px] after:w-[10.5px] after:rounded-full after:border-none
            after:absolute after:mt-[0.9px] focus:outline-none after:ml-[0.95px] checked:after:right-[1.25px] disabled:opacity-75
            after:bg-red-600 border-red-600 checked:after:bg-green-600 checked:border-green-600"
            type="checkbox" role="switch" :disabled="!actionText" v-model="data.is_active" />
          </div>
          <div :class="(data.is_active ? 'text-green-600' : 'text-red-600') + ' text-xs'">
            {{data.is_active ? 'Active' : 'InActive' }}
          </div>
        </div>
      </div>
      <!-- END CHECKBOX COMPONENT -->
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
    </div>
  </div>
</div>
@endverbatim
@endif