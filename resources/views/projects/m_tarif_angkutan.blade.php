<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">TARIF ANGKUTAN</h1>
  </div>
  <div class="flex justify-between items-center px-4 py-1">
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
        <h1 class="text-20px font-bold">Master Tarif Angkutan</h1>
        <p class="text-gray-100">Form Pengisian Tarif Angkutan</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-2 gap-2 gap-x-10 ">
    <!-- START COLUMN -->

    <!-- Kode Coloumn -->
    <div>
      <FieldX :bind="{ disabled: !actionText, readonly: !actionText }" class="w-full !mt-3" :value="values.kode"
        :errorText="formErrors.kode?'failed':''" @input="v=>values.kode=v" :hints="formErrors.kode" label="Kode"
        placeholder="Kode" :check="false" />
    </div>

    <div></div>

    <!-- Kode Supplier Coloumn -->
    <div class="flex">
      <FieldPopup class="!mt-3 w-full" :api="{
        url: `${store.server.url_backend}/operation/m_supplier`,
        headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
        params: {
          simplest:true,
          searchfield: 'this.kode , this.nama , this.is_active',
          where: 'this.is_active = true'
          },
        }" displayField="kode" valueField="id" :bind="{ readonly: !actionText }" :value="values.m_supplier_id"
        @input="(v)=>values.m_supplier_id=v" @update:valueFull="(data)=>{
          if (data) {
            values.nama = data.nama
          } else {
            values.nama = ''; 
          }
          return response;
        }" :errorText="formErrors.m_supplier_id?'failed':''" class="w-full !mt-3" :hints="formErrors.m_supplier_id"
        placeholder="Pilih Supplier" label="Kode Supplier" :check='false' :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'kode',
            headerName: 'KODE',
            sortable: false, resizable: true, filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          },
          {
            flex: 1,
             field: 'nama',
            headerName: 'NAMA SUPPLIER',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: false, resizable: true, filter: false,
          },
          {
            flex: 1,
            field: 'is_active',
            headerName: 'STATUS',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            sortable: false, resizable: true, filter: false,
            valueFormatter: (params) => {
            return params.value ? 'Aktif' : 'Nonaktif';
            }
          },
          ]" />
      <span class="text-red-500"> * </span>
    </div>

    <!-- Nama Supplier Coloumn -->
    <div>
      <FieldX :bind="{ disabled: true, readonly: true }" class="w-full !mt-3" :value="values.nama"
        @update:value="v => values.nama = v" :errorText="formErrors.nama ? 'failed' : ''" :hints="formErrors.nama"
        placeholder="Nama" label="Nama Supplier" :check="false" />
    </div>

    <!-- Tipe Container Coloumn -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, readonly: !actionText }" class="w-full !mt-3" :value="values.ukuran"
        @update:value="v => values.ukuran = v" :errorText="formErrors.ukuran ? 'failed' : ''" :hints="formErrors.ukuran"
        valueField="id" displayField="deskripsi" placeholder="Ukuran" label="Ukuran" :check="true" @update:valueFull="(data)=>{
        $log(data)
        if (data) {
          values.sektor = data['m_general.id'];
        } else {
          values.sektor = ''; 
          
        }
          return response;
        }" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                //tambahkan Params where ke group jenis kontainer , 
                where:
                `this.group = 'UKURAN KONTAINER' AND this.is_active = 'true' `,
                searchfield: 'this.deskripsi'
              },
            }" />
    </div>

    <!-- Tipe Container Coloumn -->
    <!-- <div>
      <FieldSelect :bind="{ disabled: !actionText, readonly: !actionText }" class="w-full !mt-3" :value="values.jenis"
        @update:value="v => values.jenis = v" :errorText="formErrors.jenis ? 'failed' : ''" :hints="formErrors.jenis"
        valueField="id" displayField="deskripsi" placeholder="Jenis" label="Jenis" :check="true" @update:valueFull="(data)=>{
        $log(data)
        if (data) {
          values.sektor = data['m_general.id'];
        } else {
          values.sektor = ''; 
          
        }
          return response;
        }" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                //tambahkan Params where ke group jenis kontainer , 
                where:
                `this.group = 'JENIS KONTAINER' AND this.is_active = 'true' `,
                searchfield: 'this.deskripsi'
              },
            }" />
    </div> -->

    <!-- Sektor Coloumn -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, readonly: !actionText }" class="w-full !mt-3" :value="values.sektor"
        @update:value="v => values.sektor = v" :errorText="formErrors.sektor ? 'failed' : ''" :hints="formErrors.sektor"
        valueField="id" displayField="deskripsi" placeholder="Sektor" label="Sektor" :check="true" @update:valueFull="(data)=>{
        $log(data)
        if (data) {
          values.sektor = data['m_general.id'];
        } else {
          values.sektor = ''; 
          
        }
          return response;
        }" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                //tambahkan Params where ke group jenis kontainer , 
                where:
                `this.group = 'SEKTOR' `,
                searchfield: 'this.deskripsi'
              },
            }" />
    </div>

    <!-- Tarif Coloumn -->
    <div class="flex items-center gap-2">
      <FieldNumber :bind="{ readonly: !actionText }" class="w-[85%] !mt-3" :value="values.tarif"
        :errorText="formErrors.tarif?'failed':''" @input="v=>values.tarif=v" :hints="formErrors.tarif" label="Tarif"
        placeholder="Tarif Angkutan" :check="false" />
      <button class="border-1 rounded w-[60px] !mt-3 ml-0 h-[34px] text-gray-400 bg-gray-100">/ CONT</button>
    </div>

    <!-- Tarif Stapel Coloumn -->
    <div class="flex items-center gap-2">
      <FieldNumber :bind="{ readonly: !actionText }" class="w-[85%] !mt-3" :value="values.tarif_stapel"
        :errorText="formErrors.tarif_stapel?'failed':''" @input="v=>values.tarif_stapel=v"
        :hints="formErrors.tarif_stapel" label="Tarif Stapel" placeholder="Tarif Stapel" :check="false" />
      <button class="border-1 rounded w-[60px] !mt-3 ml-0 h-[34px] text-gray-400 bg-gray-100">/ HARI</button>
    </div>

    <div class="flex items-center gap-2">
      <FieldNumber :bind="{ readonly: !actionText }" class="w-[85%] !mt-3" :value="values.ganti_solar_muter"
        :errorText="formErrors.ganti_solar_muter?'failed':''" @input="v=>values.ganti_solar_muter=v"
        :hints="formErrors.ganti_solar_muter" label="Ganti Solar Muter" placeholder="Masukkan ganti solar muter"
        :check="false" />
      <button class="border-1 rounded w-[60px] !mt-3 ml-0 h-[34px] text-gray-400 bg-gray-100">/ CONT</button>
    </div>

    <div class="flex items-center gap-2">
      <FieldNumber :bind="{ readonly: !actionText }" class="w-[85%] !mt-3" :value="values.ganti_solar_lain"
        :errorText="formErrors.ganti_solar_lain?'failed':''" @input="v=>values.ganti_solar_lain=v"
        :hints="formErrors.ganti_solar_lain" label="Ganti Solar Lain" placeholder="Masukkan ganti solar lain"
        :check="false" />
      <button class="border-1 rounded w-[60px] !mt-3 ml-0 h-[34px] text-gray-400 bg-gray-100">/ CONT</button>
    </div>

    <div class="flex items-center gap-2">
      <FieldNumber :bind="{ readonly: !actionText }" class="w-[85%] !mt-3" :value="values.atur_stapel_1"
        :errorText="formErrors.atur_stapel_1?'failed':''" @input="v=>values.atur_stapel_1=v"
        :hints="formErrors.atur_stapel_1" label="Atur Stapel 1" placeholder="Masukkan atur stapel 1" :check="false" />
      <button class="border-1 rounded w-[60px] !mt-3 ml-0 h-[34px] text-gray-400 bg-gray-100">/ HARI</button>
    </div>

    <div class="flex items-center gap-2">
      <FieldNumber :bind="{ readonly: !actionText }" class="w-[85%] !mt-3" :value="values.atur_stapel_2"
        :errorText="formErrors.atur_stapel_2?'failed':''" @input="v=>values.atur_stapel_2=v"
        :hints="formErrors.atur_stapel_2" label="Atur Stapel 2" placeholder="Masukkan atur stapel 2" :check="false" />
      <button class="border-1 rounded w-[60px] !mt-3 ml-0 h-[34px] text-gray-400 bg-gray-100">/ HARI</button>
    </div>

    <div class="flex items-center gap-2">
      <FieldNumber :bind="{ readonly: !actionText }" class="w-[85%] !mt-3" :value="values.tarif_pengawalan"
        :errorText="formErrors.tarif_pengawalan?'failed':''" @input="v=>values.tarif_pengawalan=v"
        :hints="formErrors.tarif_pengawalan" label="Tarif Pengawalan" placeholder="Masukkan tarif pengawalan" :check="false" />
      <button class="border-1 rounded w-[60px] !mt-3 ml-0 h-[34px] text-gray-400 bg-gray-100">/ CONT</button>
    </div>

    <!-- Kena Pajak Column -->
    <div class="grid grid-cols-3 gap-y-2 gap-x-2 items-start">

      <FieldSelect :bind="{ disabled: !actionText, readonly: !actionText}" class="flex-auto w-full !mt-3"
        :value="values.ppn_id" :errorText="formErrors.ppn_id?'failed' :''" @input="v=>{
          if(v){
            values.ppn_id=v
          }else{
            values.ppn_id=null
          }
          values.persen_pajak=null
        }" :hints="formErrors.ppn_id" :check="true" valueField="id" displayField="deskripsi" placeholder="PPN"
        label="PPN" :check="false" @update:valueFull="(data)=>{
        $log(data)
        if (data) {
          values.sektor = data['m_general.id'];
          values.persen_pajak = data['deskripsi2'];
        } else {
          values.sektor = ''; 
          values.persen_pajak = '';
        }
          return response;
        }" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                where:
                `this.group = 'JENIS PPN' AND this.is_active = 'true' `,
                searchfield: 'this.deskripsi',
                selectfield: 'this.deskripsi,this.deskripsi2,this.id'
              },
            }" />

      <FieldSelect :bind="{ disabled: !actionText, readonly: !actionText}" class="flex-auto w-full !mt-3"
        :value="values.jenis_pajak" :errorText="formErrors.jenis_pajak?'failed' :''" @input="v=>{
          if(v){
            values.jenis_pajak=v
          }else{
            values.jenis_pajak=null
          }
          values.persen_pajak=null
        }" :hints="formErrors.jenis_pajak" :check="true" valueField="id" displayField="deskripsi" placeholder="PPH"
        label="PPH" :check="false" @update:valueFull="(data)=>{
        $log(data)
        if (data) {
          values.sektor = data['m_general.id'];
          values.persen_pajak = data['deskripsi2'];
        } else {
          values.sektor = ''; 
          values.persen_pajak = '';
        }
          return response;
        }" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                where:
                `this.group = 'JENIS PPH' AND this.is_active = 'true' `,
                searchfield: 'this.deskripsi',
                selectfield: 'this.deskripsi,this.deskripsi2,this.id'
              },
            }" />

      <FieldNumber class="flex-auto w-full !mt-3" :bind="{ readonly: true }" :value="values.persen_pajak"
        @input="v=>values.persen_pajak=v" :errorText="formErrors.persen_pajak?'failed':''"
        :hints="formErrors.persen_pajak" valueField="id" displayField="key" placeholder="Persen Pajak"
        label="Persen Pajak" :check="false" />
    </div>

    <div class="flex items-center gap-2">
      <FieldNumber :bind="{ readonly: !actionText }" class="w-[85%] !mt-3" :value="values.tambahan_lain_1"
        :errorText="formErrors.tambahan_lain_1?'failed':''" @input="v=>values.tambahan_lain_1=v"
        :hints="formErrors.tambahan_lain_1" label="Tambahan Lain 1" placeholder="Masukkan tambahan lain 1" :check="false" />
      <button class="border-1 rounded w-[60px] !mt-3 ml-0 h-[34px] text-gray-400 bg-gray-100">/ CONT</button>
    </div>

    <div class="flex items-center gap-2">
      <FieldNumber :bind="{ readonly: !actionText }" class="w-[85%] !mt-3" :value="values.tambahan_lain_2"
        :errorText="formErrors.tambahan_lain_2?'failed':''" @input="v=>values.tambahan_lain_2=v"
        :hints="formErrors.tambahan_lain_2" label="Tambahan Lain 2" placeholder="Masukkan tambahan lain 2" :check="false" />
      <button class="border-1 rounded w-[60px] !mt-3 ml-0 h-[34px] text-gray-400 bg-gray-100">/ CONT</button>
    </div>

    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        :check="false" label="Catatan" placeholder="Catatan" type="textarea" />
    </div>

    <div class="flex flex-col gap-2 pt-2 ml-1">
      <label class="text-gray-600 text-xs font-semibold">Status</label>
      <div class="flex gap-2">
        <div class="relative">
          <input class="relative h-[16px] w-7 p-px appearance-none rounded-full bg-white border disabled:!cursor-default
            hover:cursor-pointer after:content-[''] after:h-[10.5px] after:w-[10.5px] after:rounded-full after:border-none
            after:absolute after:mt-[0.9px] focus:outline-none after:ml-[0.95px] checked:after:right-[1.25px] disabled:opacity-75
            after:bg-red-600 border-red-600 checked:after:bg-green-600 checked:border-green-600"
            type="checkbox" role="switch" :disabled="!actionText" v-model="values.is_active" />
        </div>
        <div :class="(values.is_active ? 'text-green-600' : 'text-red-600') + ' text-xs'">
          {{values.is_active ? 'Active' : 'InActive' }}
        </div>
      </div>
    </div>

    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>


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