@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[670px] border-t-10 border-blue-500 dark:bg-black">
  <div class="flex justify-between items-center p-2">
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


  </div>
  <hr>

  <!-- TABLE -->
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[550px] pt-2 !px-4 !pb-8">
    <template #header>
      <!-- ACTION BUTTON -->
      <div class="flex items-center gap-x-4">
        <RouterLink :to="$route.path + '/create?' + (Date.parse(new Date()))" class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white 
                        text-sm rounded-md py-1 px-2.5 transition-colors duration-300">
          Create New
        </RouterLink>
      </div>
    </template>
  </TableApi>
</div>

</div>
@else

@verbatim
<div class="flex flex-col gap-y-3 rounded-t-md bg-white">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-blue-300" title="Kembali" @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Supplier</h1>
        <p class="text-gray-100">Untuk mengatur Supplier</p>
      </div>
    </div>
  </div>
  <div class="flex gap-x-4 px-2 p-4">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">

      <div class="grid <md:grid-cols-1 grid-cols-3 gap-2">
        <!-- START COLUMN -->
        <div class="flex">
          <FieldX :bind="{ readonly: true }" class="w-full  !mt-3" :value="values.kode"
            :errorText="formErrors.kode?'failed':''" @input="v=>values.kode=v" :hints="formErrors.kode" :check="false"
            label="Kode" placeholder="kode" />
        </div>
        <div class="flex">
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.nama"
            :errorText="formErrors.nama?'failed':''" @input="v=>values.nama=v" :hints="formErrors.nama" :check="false"
            label="NAMA" placeholder="Masukan Nama PT" />
          <span class="text-red-500"> * </span>
        </div>
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="w-full !mt-3" :value="values.tipe_id"
            @input="v => values.tipe_id = v" :errorText="formErrors.tipe_id ? 'failed' : ''" :hints="formErrors.tipe_id"
            valueField="id" displayField="deskripsi"  :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='TIPE SUPPLIER'`
              }
          }" @update:valueFull="val => { 
            $log(val)
                    if (val.text === 'Lokal') {
                      values.negara = 'Indonesia';
                    } else {
                      values.negara = '';
                    }
    }" placeholder="Tipe Supplier" fa-icon="sort-desc" label="Tipe Supplier" :check="false" />
        </div>
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="w-full !mt-3" :value="values.jenis_id"
            @input="v => values.jenis_id = v" :errorText="formErrors.jenis_id ? 'failed' : ''" :hints="formErrors.jenis_id"
              valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='JENIS SUPPLIER'`
              }
          }" placeholder="Jenis" fa-icon="sort-desc" label="Jenis Supplier" :check="false" />
        </div>
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="w-full  !mt-3" :value="values.pajak"
            @input="v=>values.pajak=v" :errorText="formErrors.pajak?'failed':''" :hints="formErrors.pajak"
            valueField="deskripsi" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='PAJAK'`
              }
          }" placeholder="Pilih Tipe Pajak" fa-icon="sort-desc" label="Tipe Pajak" :check="false" />
        </div>
        <div class="flex">
          <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="w-full  !mt-3" :value="values.top"
            @input="v=>values.top=v" :errorText="formErrors.top?'failed':''" :hints="formErrors.top" valueField="id"
            displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='TOP'`
              }
          }" placeholder="Pilih  ToP" fa-icon="sort-desc" label="Tipe top" :check="false" />
          <span class="text-red-500"> * </span>
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.nik"
            :errorText="formErrors.nik?'failed':''" @input="v=>values.nik=v" :hints="formErrors.nik" :check="false"
            type="number" label="NIK" placeholder="Tuliskan NIK" />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.npwp"
            :errorText="formErrors.npwp?'failed':''" @input="v=>values.npwp=v" :hints="formErrors.npwp" :check="false"
            label="NPWP" placeholder="Tuliskan NPWP" />
        </div>
        <div class="flex">
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.alamat"
            :errorText="formErrors.alamat?'failed':''" @input="v=>values.alamat=v" :hints="formErrors.alamat"
            :check="false" type="textarea" label="Alamat" placeholder="Masukan Alamat" />
          <span class="text-red-500"> * </span>
        </div>

        <div>
          <FieldSelect :bind="{ disabled: true, clearable: false }" class="w-full  !mt-3" :value="values.link_b2b"
            @input="v=>values.link_b2b=v" :errorText="formErrors.link_b2b?'failed':''" :hints="formErrors.link_b2b"
            valueField="id" displayField="key" :options="[
      {'id' : '1' , 'key' : 'B2B 1'}, 
      {'id': '0', 'key' : 'B2B 2'}
      ]" placeholder="LINK B2B" fa-icon="sort-desc" label="LINK B2B" :check="false" />
        </div>

        <div class="flex">
          <FieldSelect :bind="{ disabled: values.tipe === 'Lokal' || !actionText, clearable: false }"
            class="w-full !mt-3" :value="values.negara" @update:valueFull="objVal => { 
              values.provinsi = ''; 
              values.kota = ''; 
              values.kecamatan = ''; 
            }" @input="v => values.negara = v" :errorText="formErrors.negara ? 'failed' : ''"
            :hints="formErrors.negara" valueField="name" displayField="name" :options="negara_data" placeholder="Negara"
            label="Negara" fa-icon="sort-desc" :check="false" />
          <span class="text-red-500"> * </span>
        </div>
        <!-- NATIONAL-->
<div v-show="values.negara === 'Indonesia'">
  <FieldSelect 
    :bind="{ disbaled: !actionText  , clearable: false }" 
    class="w-full !mt-3" 
    :value="values.provinsi" 
    @input="v => values.provinsi = v" 
    @update:valueFull="objVal => {  
              values.kota = ''; 
              values.kecamatan = ''; 
            }"
    label="Provinsi"
    placeholder="Pilih Provinsi" 
    :api="{
      url: 'https://backend.qqltech.com/kodepos/region/provinsi',
      headers: {},
      params: {
        search: '',
        searchfield: 'name',
        selectfield: 'name,id',
        paginate: 35
      },
      onsuccess: function(responseJson) {
        return {
          data: responseJson,
          page: 1,
          hasNext: false
        }
      }
    }" 
    :check="false"
  />
</div>
<div v-show="values.negara === 'Indonesia'">
  <FieldSelect 
    :bind="{ disbaled: !actionText , clearable: false  }" 
    class="w-full !mt-3" 
    :value="values.kota" 
    @input="v => values.kota = v" 
    @update:valueFull="(objVal) => {
      if (!objVal) {
        values.kecamatan = '';
      }
    }"
    label="Kota"
    placeholder="Pilih Kota"
    :api="{
      url: 'https://backend.qqltech.com/kodepos/region/kabupaten-kota',
      headers: {},
      params: {
        provinsi: values.provinsi,
        search: '',
        searchfield: 'name',
        selectfield: 'name,id',
        paginate: 35
      },
      onsuccess: function(responseJson) {
        return {
          data: responseJson,
          page: 1,
          hasNext: false
        }
      }
    }" 
    :check="false" 
  />
</div>
<div v-show="values.negara === 'Indonesia'">
  <FieldSelect 
    :bind="{ disbaled: !actionText , clearable: false  }" 
    class="w-full !mt-3" 
    :value="values.kecamatan" 
    @input="v => values.kecamatan = v"
    label="Kecamatan"
    placeholder="Pilih Kecamatan"
    :api="{
      url: 'https://backend.qqltech.com/kodepos/region/kecamatan',
      headers: {},
      params: {
        kota: values.kota,
        search: '',
        searchfield: 'name',
        selectfield: 'name,id',
        paginate: 35
      },
      onsuccess: function(responseJson) {
        return {
          data: values.kota ? responseJson : [],
          page: 1,
          hasNext: false
        }
      }
    }" 
    :check="false" 
  />
</div>


        <!-- INTERNATIONAL-->
        <div class="flex" v-if="values.tipe_id === 295">
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.provinsi"
            :errorText="formErrors.provinsi?'failed':''" @input="v=>values.provinsi=v" :hints="formErrors.provinsi"
            :check="false" label="Provinsi" placeholder="Masukan Provinsi" />
          <span class="text-red-500"> * </span>
        </div>

        <div class="flex" v-if="values.tipe_id === 295">
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.kota"
            :errorText="formErrors.kota?'failed':''" @input="v=>values.kota=v" :hints="formErrors.kota" :check="false"
            label="City" placeholder="Masukan Kota" />
          <span class="text-red-500"> * </span>
        </div>

        <div class="flex" v-if="values.tipe_id === 295">
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.kecamatan"
            :errorText="formErrors.kecamatan?'failed':''" @input="v=>values.kecamatan=v" :hints="formErrors.kecamatan"
            :check="false" label="Kecamatan" placeholder="Masukan Kecamatan" />
          <span class="text-red-500"> * </span>
        </div>
        <!-- END NATIONAL -->

        <div class="flex">
          <FieldPopup class="!mt-3 w-full" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              where:`this.group='BANK'`,
              searchfield: 'this.kode , this.deskripsi'
            },
          }" displayField="deskripsi" valueField="id" :bind="{ readonly: !actionText }" :value="values.bank"
            @input="(v)=>values.bank=v" @update:valueFull="(data)=>{
            if (data && data.kode) {
              values.kode_bank = data.kode;
            } else {
              values.kode_bank = ''; 
            }
          return response;
        }" :errorText="formErrors.bank?'failed':''" class="w-full !mt-3" :hints="formErrors.bank"
            placeholder="Pilih Bank" label="Bank" :check='false' :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'kode',
            headerName: 'KODE BANK',
            sortable: false, resizable: true, filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          },
          {
            flex: 1,
             field: 'deskripsi',
            headerName: 'BANK',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: true,
            sort: 'asc',
            resizable: true, 
            filter: false,
          },
          ]" />
          <span class="text-red-500"> * </span>
        </div>

        <div class="flex">
          <FieldX :bind="{ readonly: true }" class="w-full  !mt-3" :value="values.kode_bank"
            :errorText="formErrors.kode_bank ? 'failed' : ''" 
            @input="v => values.kode_bank = v"
            :hints="formErrors.kode_bank" 
            :check="false" type="number" 
            label="Kode Bank"
            placeholder="Tuliskan Kode Bank" />
          <span class="text-red-500"> * </span>
        </div>


        <div class="flex">
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.no_rekening"
            :errorText="formErrors.no_rekening?'failed':''" @input="v=>values.no_rekening=v"
            :hints="formErrors.no_rekening" :check="false" label="No Rekening" placeholder="No Rekening" />
          <span class="text-red-500"> * </span>
        </div>

        <div class="flex">
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.nama_rekening"
            :errorText="formErrors.nama_rekening?'failed':''" @input="v=>values.nama_rekening=v"
            :hints="formErrors.nama_rekening" :check="false" label="Nama Rekening" placeholder="Nama Rekening" />
          <span class="text-red-500"> * </span>
        </div>

        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.no_telp1"
            :errorText="formErrors.no_telp1?'failed':''" @input="v=>values.no_telp1=v" :hints="formErrors.no_telp1"
            :check="false" type="number" label="Telepon 1" placeholder="Masukan No Telepon 1" />
        </div>

        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.no_telp2"
            :errorText="formErrors.no_telp2?'failed':''" @input="v=>values.no_telp2=v" :hints="formErrors.no_telp2"
            :check="false" type="number" label="Telepon 2" placeholder="Masukan No Telepon 2" />
        </div>

        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.email"
            :errorText="formErrors.email?'failed':''" @input="v => validateEmail(v, 'email')" :hints="formErrors.email"
            :check="false" label="Email" placeholder="Email" />
        </div>



        <!-- END COLUMN -->
        <!-- ACTION BUTTON START -->
      </div>
      <!-- Status -->
      <div class="flex my-5 space-x-5">
        <div class="flex flex-col gap-2">
          <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer font-semibold"
            for="pph_for_click"
            >PPH :</label>
          <div class="flex w-40">
            <div class="flex-auto">
              <i class="text-red-500">TIDAK</i>
            </div>
            <div class="flex-auto">
              <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                role="switch"
                id="pph_for_click"
                :disabled="!actionText"
                v-model="values.pph"
                />
            </div>
            <div class="flex-auto">
              <i class="text-green-500">IYA</i>
            </div>
          </div>
        </div>
        <div class="flex flex-col gap-2">
          <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer font-semibold"
            for="b2b_for_click"
            >B2B :</label>
          <div class="flex w-40">
            <div class="flex-auto">
              <i class="text-red-500">TIDAK</i>
            </div>
            <div class="flex-auto">
              <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                role="switch"
                id="b2b_for_click"
                :disabled="!actionText"
                v-model="values.b2b"
                />
            </div>
            <div class="flex-auto">
              <i class="text-green-500">IYA</i>
            </div>
          </div>
        </div>
      </div>

      <div class="pt-5">
        <span class="font-semibold text-xl"> CONTACT PERSON 1 </span>
        <hr class=" border-gray-300 w-1/4">
        <div class="grid grid-cols-3 gap-3 !mt-5">

          <div>
            <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.contact_person1"
              :errorText="formErrors.contact_person1?'failed':''" @input="v=>values.contact_person1=v"
              :hints="formErrors.contact_person1" :check="false" label="Nama" placeholder="Tuliskan Nama" />
          </div>
          <div>
            <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.no_telp_contact_person1"
              :errorText="formErrors.no_telp_contact_person1?'failed':''" @input="v=>values.no_telp_contact_person1=v"
              :hints="formErrors.no_telp_contact_person1" :check="false" type="number" label="No.Telp"
              placeholder="Masukan No Telp " />
          </div>
          <div>
            <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.email_contact_person1"
              :errorText="formErrors.email_contact_person1?'failed':''"
              @input="v => validateEmail(v, 'email_contact_person1')" :hints="formErrors.email_contact_person1"
              :check="false" label="Email" placeholder="Masukan Email" />
          </div>
        </div>
      </div>

      <div class="pt-5">
        <span class="font-semibold text-xl"> CONTACT PERSON 2 </span>
        <hr class=" border-gray-300 w-1/4">
        <div class="grid grid-cols-3 gap-3 !mt-5">
          <div>
            <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.contact_person2"
              :errorText="formErrors.contact_person2?'failed':''" @input="v=>values.contact_person2=v"
              :hints="formErrors.contact_person2" :check="false" label="Nama" placeholder="Nama" />
          </div>
          <div>
            <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.no_telp_contact_person2"
              :errorText="formErrors.no_telp_contact_person2?'failed':''" @input="v=>values.no_telp_contact_person2=v"
              :hints="formErrors.no_telp_contact_person2" :check="false" type="number" label="No.Telp"
              placeholder="Masukan No.Telp" />
          </div>
          <div>
            <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.email_contact_person2"
              :errorText="formErrors.email_contact_person2?'failed':''"
              @input="v => validateEmail(v, 'email_contact_person2')" :hints="formErrors.email_contact_person2"
              :check="false" label="Email" placeholder="Masukan Email" />
          </div>
        </div>

        <div class="pt-4">
          <span class="font-semibold text-xl"> Catatan </span>
          <hr class=" border-gray-300 w-1/4">
          <div>
            <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-5" :value="values.catatan"
              :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
              :check="false" type="textarea" label="" placeholder="Tuliskan Catatan" />
          </div>
        </div>



        <div class="flex flex-col gap-2 mt-10">
          <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer font-semibold"
            for="is_active_for_click"
            >Status :</label>
          <div class="flex w-40">
            <div class="flex-auto">
              <i class="text-red-500">InActive</i>
            </div>
            <div class="flex-auto">
              <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                role="switch"
                id="is_active_for_click"
                :disabled="!actionText"
                v-model="values.is_active"
                />
            </div>
            <div class="flex-auto">
              <i class="text-green-500">Active</i>
            </div>
          </div>
        </div>




        <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
          <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Kembali
          </button>
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Simpan
          </button>
        </div>
      </div>
    </div>
  </div>
  @endverbatim
  @endif