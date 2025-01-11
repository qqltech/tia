<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-gray-500">
  <div class="p-2">
    <h1 class="text-xl font-semibold">Buku Order</h1>
  </div>
  <div class="flex justify-between items-center px-2.5 py-1">
    <!-- FILTER -->
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
  <div class="bg-gray-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Buku Order</h1>
        <p class="text-gray-100">Master Buku Order</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_buku_order"
        :errorText="formErrors.no_buku_order?'failed':''" @input="v=>values.no_buku_order=v"
        :hints="formErrors.no_buku_order" label="No. Buku Order" placeholder="No. Buku Order" :check="false" />
    </div>
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable: true }" class="w-full !mt-3" :value="values.tipe_order"
        @input="v => values.tipe_order = v" :errorText="formErrors.tipe_order ? 'failed' : ''"
        :hints="formErrors.tipe_order" valueField="key" displayField="key"
        :options="['EKSPORT', 'EKSPORT S', 'IMPORT', 'LOKAL', 'OL', 'OLS']" placeholder="Pilih Tipe Order"
        label="Tipe Order" :check="true" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true, disabled: true , required: true}" class="w-full !mt-3" :value="values.tgl"
        :errorText="formErrors.tgl?'failed':''" @input="v=>values.tgl=v" :hints="formErrors.tgl" :check="false"
        type="date" label="Tanggal" placeholder="Pilih Tanggal" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.kode_cust"
        :errorText="formErrors.kode_cust?'failed':''" @input="v=>values.kode_cust=v" :hints="formErrors.kode_cust"
        label="Kode Customer" placeholder="Kode Customer" :check="false" />
    </div>
    <div>
      <FieldPopup label="Customer" class="w-full !mt-3" valueField="id" displayField="nama_perusahaan"
        :value="values.m_customer_id" @input="(v)=>values.m_customer_id=v" :api="{
              url: `${store.server.url_backend}/operation/m_customer`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                //selectfield: 'id,name,code,shortname',
                searchfield: 'this.kode, this.nama_perusahaan, this.alamat, this.kota'
              }
            }" placeholder="Pilih Customer" @update:valueFull="(v) => {
              if(v){
                values.kode_cust=v.kode
              }else{
                values.kode_cust=null
              }
            }" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'kode',
              headerName:  'Kode',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'nama_perusahaan',
              headerName:  'Nama',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'alamat',
              headerName:  'Alamat',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'kota',
              headerName:  'Kota',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.jenis_barang"
        :errorText="formErrors.jenis_barang?'failed':''" @input="v=>values.jenis_barang=v"
        :hints="formErrors.jenis_barang" :check="false" label="Jenis Barang" placeholder="Tuliskan Jenis Barang" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tujuan_asal"
        :errorText="formErrors.tujuan_asal?'failed':''" @input="v=>values.tujuan_asal=v" :hints="formErrors.tujuan_asal"
        :check="false" label="Tujuan/Asal" placeholder="Tuliskan Tujuan/Asal" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.moda_transportasi"
        :errorText="formErrors.moda_transportasi?'failed':''" @input="v=>values.moda_transportasi=v"
        :hints="formErrors.moda_transportasi" :check="false" label="Moda Transportasi"
        placeholder="Tuliskan Moda Transportasi" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.coo"
        :errorText="formErrors.coo?'failed':''" @input="v=>values.coo=v" :hints="formErrors.coo" :check="false"
        label="COO" placeholder="Tuliskan COO" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.hc"
        :errorText="formErrors.hc?'failed':''" @input="v=>values.hc=v" :hints="formErrors.hc" :check="false" label="HC"
        placeholder="Tuliskan HC" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full !mt-3" :value="values.tanggal_closing_doc"
        :errorText="formErrors.tanggal_closing_doc?'failed':''" @input="v=>values.tanggal_closing_doc=v"
        :hints="formErrors.tanggal_closing_doc" :check="false" type="date" label="Tanggal Closing Doc"
        placeholder="Pilih Tanggal Closing Doc" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full !mt-3" :value="values.jam_closing_doc"
        :errorText="formErrors.jam_closing_doc?'failed':''" @input="v=>values.jam_closing_doc=v"
        :hints="formErrors.jam_closing_doc" :check="false" type="time" label="Jam Closing Doc"
        placeholder="Pilih Jam Closing Doc" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full !mt-3"
        :value="values.tanggal_closing_cont" :errorText="formErrors.tanggal_closing_cont?'failed':''"
        @input="v=>values.tanggal_closing_cont=v" :hints="formErrors.tanggal_closing_cont" :check="false" type="date"
        label="Tanggal Closing Cont." placeholder="Pilih Tanggal Closing Cont." />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full !mt-3" :value="values.jam_closing_cont"
        :errorText="formErrors.jam_closing_cont?'failed':''" @input="v=>values.jam_closing_cont=v"
        :hints="formErrors.jam_closing_cont" :check="false" type="time" label="Jam Closing Cont."
        placeholder="Pilih Jam Closing Cont." />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_bl"
        :errorText="formErrors.no_bl?'failed':''" @input="v=>values.no_bl=v" :hints="formErrors.no_bl" :check="false"
        label="No. BL" placeholder="Tuliskan No. BL" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full !mt-3" :value="values.tanggal_bl"
        :errorText="formErrors.tanggal_bl?'failed':''" @input="v=>values.tanggal_bl=v" :hints="formErrors.tanggal_bl"
        :check="false" type="date" label="Tanggal BL" placeholder="Pilih Tanggal BL" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_invoice"
        :errorText="formErrors.no_invoice?'failed':''" @input="v=>values.no_invoice=v" :hints="formErrors.no_invoice"
        :check="false" label="No. Invoice" placeholder="Tuliskan No. Invoice" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full !mt-3" :value="values.tanggal_invoice"
        :errorText="formErrors.tanggal_invoice?'failed':''" @input="v=>values.tanggal_invoice=v"
        :hints="formErrors.tanggal_invoice" :check="false" type="date" label="Tanggal Invoice"
        placeholder="Pilih Tanggal Invoice" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full !mt-3" :value="values.tanggal_pengkont"
        :errorText="formErrors.tanggal_pengkont?'failed':''" @input="v=>values.tanggal_pengkont=v"
        :hints="formErrors.tanggal_pengkont" :check="false" type="date" label="Tanggal Pengkont."
        placeholder="Pilih Tanggal Pengkont." />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full !mt-3" :value="values.tanggal_pemasukan"
        :errorText="formErrors.tanggal_pemasukan?'failed':''" @input="v=>values.tanggal_pemasukan=v"
        :hints="formErrors.tanggal_pemasukan" :check="false" type="date" label="Tanggal Pemasukan"
        placeholder="Pilih Tanggal Pemasukan" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.jumlah_coo"
        :errorText="formErrors.jumlah_coo?'failed':''" @input="v=>values.jumlah_coo=v" :hints="formErrors.jumlah_coo"
        :check="false" label="COO (Jumlah)" placeholder="Masukan COO (Jumlah)" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.lembar_coo"
        :errorText="formErrors.lembar_coo?'failed':''" @input="v=>values.lembar_coo=v" :hints="formErrors.lembar_coo"
        :check="false" label="COO Doc. Lembar" placeholder="Masukan COO Doc. Lembar" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.jumlah_coo_ulang"
        :errorText="formErrors.jumlah_coo_ulang?'failed':''" @input="v=>values.jumlah_coo_ulang=v"
        :hints="formErrors.jumlah_coo_ulang" :check="false" label="COO Ulang (Jumlah)"
        placeholder="Masukan COO Ulang (Jumlah)" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.lembar_coo_ulang"
        :errorText="formErrors.lembar_coo_ulang?'failed':''" @input="v=>values.lembar_coo_ulang=v"
        :hints="formErrors.lembar_coo_ulang" :check="false" label="COO Ulang Doc. Lembar"
        placeholder="Masukan COO Ulang Doc. Lembar" />
    </div>
    <!-- <div>
      <FieldUpload class="col-span-9 w-full !mt-3" :bind="{ readonly: !actionText, disabled: !actionText }"
        :value="values.berkas_coo" @input="(v) => values.berkas_coo = v"
        :reducerDisplay="val => !val ? null : val.split(':::')[val.split(':::').length - 1]" :api="{	
          url: `${store.server.url_backend}/operation/t_buku_order/upload`,	
          headers: { Authorization: `${store.user.token_type} ${store.user.token}` },	
          params: { field: 'berkas_coo' },	
          onsuccess: response => response,	
          onerror: (error) => {},	
        }" :hints="formErrors.berkas_coo" placeholder="Masukan File" fa-icon="upload"
        :accept="acceptType('berkas_coo')" label="" :check="false" />
    </div> -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
        :value="values.kode_pelayaran_id" @input="v=>{
            if(v){
              values.kode_pelayaran_id=v
            }else{
              values.kode_pelayaran_id=null
            }
          }" :errorText="formErrors.kode_pelayaran_id?'failed':''" :hints="formErrors.kode_pelayaran_id"
        valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:`this.is_active=true and this.group='PELAYARAN'`

              }
          }" placeholder="Pilih Nama Pelayaran" label="Nama Pelayaran" :check="true" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_boking"
        :errorText="formErrors.no_boking?'failed':''" @input="v=>values.no_boking=v" :hints="formErrors.no_boking"
        :check="false" label="No. Boking" placeholder="Tuliskan No. Boking" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.voyage"
        :errorText="formErrors.voyage?'failed':''" @input="v=>values.voyage=v" :hints="formErrors.voyage" :check="false"
        label="Voyage" placeholder="Tuliskan Voyage" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.lokasi_stuffing"
        :errorText="formErrors.lokasi_stuffing?'failed':''" @input="v=>values.lokasi_stuffing=v"
        :hints="formErrors.lokasi_stuffing" :check="false" label="Lokasi Stuffing"
        placeholder="Tuliskan Lokasi Stuffing" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.gw"
        :errorText="formErrors.gw?'failed':''" @input="v=>values.gw=v" :hints="formErrors.gw" :check="false" label="GW"
        placeholder="Tuliskan GW" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.nw"
        :errorText="formErrors.nw?'failed':''" @input="v=>values.nw=v" :hints="formErrors.nw" :check="false" label="NW"
        placeholder="Tuliskan NW" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.nama_kapal"
        :errorText="formErrors.nama_kapal?'failed':''" @input="v=>values.nama_kapal=v" :hints="formErrors.nama_kapal"
        :check="false" label="Nama Kapal" placeholder="Tuliskan Nama Kapal" />
    </div>
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3" :value="values.pelabuhan_id"
        @input="v=>{
            if(v){
              values.pelabuhan_id=v
            }else{
              values.pelabuhan_id=null
            }
          }" :errorText="formErrors.pelabuhan_id?'failed':''" :hints="formErrors.pelabuhan_id" valueField="id"
        displayField="kode" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:`this.is_active=true and this.group='PELABUHAN'`

              }
          }" placeholder="Pilih Nama Pelabuhan" label="Nama Pelabuhan" :check="true" />
    </div>
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3" :value="values.tipe" @input="v=>{
            if(v){
              values.tipe=v
            }else{
              values.tipe=null
            }
          }" :errorText="formErrors.tipe?'failed':''" :hints="formErrors.tipe" valueField="id" displayField="deskripsi"
        :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:`this.is_active=true and this.group='TIPE KONTAINER'`

              }
          }" placeholder="Pilih Tipe" label="Tipe" :check="true" />
    </div>
    <div class="flex flex-col">
      <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer font-semibold"
            for="dispensasi_closing_cont_for_click"
            >Dispensasi Closing Cont.</label>
      <div class="flex w-40">
        <div class="flex-auto">
          <i class="text-red-500">Tidak</i>
        </div>
        <div class="flex-auto">
          <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                role="switch"
                id="dispensasi_closing_cont_for_click"
                :disabled="!actionText"
                v-model="values.dispensasi_closing_cont"
                />
        </div>
        <div class="flex-auto">
          <i class="text-green-500">Iya</i>
        </div>
      </div>
    </div>
    <div class="flex flex-col">
      <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer font-semibold"
            for="dispensasi_closing_doc_for_click"
            >Dispensasi Closing Doc.</label>
      <div class="flex w-40">
        <div class="flex-auto">
          <i class="text-red-500">Tidak</i>
        </div>
        <div class="flex-auto">
          <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                role="switch"
                id="dispensasi_closing_doc_for_click"
                :disabled="!actionText"
                v-model="values.dispensasi_closing_doc"
                />
        </div>
        <div class="flex-auto">
          <i class="text-green-500">Iya</i>
        </div>
      </div>
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.angkutan"
        :errorText="formErrors.angkutan?'failed':''" @input="v=>values.angkutan=v" :hints="formErrors.angkutan"
        :check="false" label="Angkutan" placeholder="Tuliskan Angkutan" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.jumlah_kemasan"
        :errorText="formErrors.jumlah_kemasan?'failed':''" @input="v=>values.jumlah_kemasan=v"
        :hints="formErrors.jumlah_kemasan" :check="false" label="Jml. Kemasan" placeholder="Masukkan Jml. Kemasan" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        :check="false" label="Catatan" type="textarea" placeholder="Tuliskan Catatan" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.status"
        :errorText="formErrors.status?'failed':''" @input="v=>values.status=v" :hints="formErrors.status" :check="false"
        label="Status" placeholder="Status" />
    </div>
  </div>

  <hr>

  <h2 class="p-4 text-20px font-bold" v-if="activeTabIndex === 0">Jumlah Kontainer</h2>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2" v-if="activeTabIndex === 0">
    <!-- START COLUMN -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3" :value="values.ukuran" @input="v=>{
            if(v){
              values.ukuran=v
            }else{
              values.ukuran=null
            }
          }" :errorText="formErrors.ukuran?'failed':''" :hints="formErrors.ukuran" valueField="id"
        displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:`this.is_active=true and this.group='UKURAN KONTAINER'`

              }
          }" placeholder="Pilih Ukuran Kontainer" label="Ukuran Kontainer" :check="true" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.jumlah_row"
        :errorText="formErrors.jumlah_row?'failed':''" @input="v=>values.jumlah_row=v" :hints="formErrors.jumlah_row"
        :check="false" label="Jumlah Baris" placeholder="Jumlah Baris Yang akan ditambahkan" />
    </div>
    <div>
      <button v-show="actionText" @click="addDetail" type="button" class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5 !mt-3">
          <icon fa="plus" />
          Add to List
        </button>
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>

  <hr class="<md:col-span-1 col-span-3">

  <div class="flex items-stretch lg:w-[40%] text-sm overflow-x-auto <md:col-span-1 col-span-3">
    <button
        class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
        :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 0}"
        @click="activeTabIndex = 0"
      >
        No Kontainer
      </button>
    <button
        class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
        :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 1}"
        @click="activeTabIndex = 1"
      >
        Detail AJU
      </button>
    <button
        class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
        :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 2}"
        @click="activeTabIndex = 2"
      >
        Detail Berkas
      </button>
  </div>

  <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 " v-if="activeTabIndex === 0">
    <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
      <table class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Depo
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Petugas Pengkont.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Petugas Pemasukan
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              No. Prefix
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              No. Sufix
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Sektor
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Ukuran
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Jenis
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
            <td class="p-2 border border-[#CACACA]" style="min-width: 100px !important;">
              <!-- <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full py-2 !mt-0"
                :value="item.depo" @input="v=>{
                  if(v){
                    item.depo=v
                  }else{
                    item.depo=null
                  }
                }" :errorText="formErrors.depo?'failed':''" :hints="formErrors.depo" valueField="id"
                displayField="kode" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      transform:false,
                      join:false,
                      where:`this.is_active=true and this.group='DEPO'`
                    }
                }" placeholder="" label="" :check="false" /> -->
                <FieldPopup label="" class="w-full py-2 !mt-0" valueField="id" displayField="kode"
                :hints="formErrors.depo" :value="item.depo"
                @input="(v)=>item.depo=v" :api="{
                            url: `${store.server.url_backend}/operation/m_general`,
                            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                            params: {
                              simplest:true,
                              transform:false,
                            join:false,
                            where:`this.is_active=true and this.group='DEPO'`
                            },
                            onsuccess:(response) => {
                              response.page = response.current_page
                              response.hasNext = response.has_next
                              return response
                            }
                          }" placeholder="" :check="false" :columns="[{
                            headerName: 'No',
                            valueGetter:(p)=>p.node.rowIndex + 1,
                            width: 60,
                            sortable: false, resizable: false, filter: false,
                            cellClass: ['justify-center', 'bg-gray-50']
                          },
                          {
                            flex: 1,
                            field: 'deskripsi',
                            headerName:  'Nama Depo',
                            sortable: false, resizable: true, filter: 'ColFilter',
                            cellClass: ['border-r', '!border-gray-200', 'justify-start']
                          },
                          ]" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldPopup label="" class="w-full py-2 !mt-0" valueField="id" displayField="nama"
                :hints="formErrors.m_petugas_pengkont_id" :value="item.m_petugas_pengkont_id"
                @input="(v)=>item.m_petugas_pengkont_id=v" :api="{
                            url: `${store.server.url_backend}/operation/m_kary`,
                            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                            params: {
                              simplest:true,
                            },
                            onsuccess:(response) => {
                              response.page = response.current_page
                              response.hasNext = response.has_next
                              return response
                            }
                          }" placeholder="" :check="false" :columns="[{
                            headerName: 'No',
                            valueGetter:(p)=>p.node.rowIndex + 1,
                            width: 60,
                            sortable: false, resizable: false, filter: false,
                            cellClass: ['justify-center', 'bg-gray-50']
                          },
                          {
                            flex: 1,
                            field: 'nama',
                            headerName:  'Nama',
                            sortable: false, resizable: true, filter: 'ColFilter',
                            cellClass: ['border-r', '!border-gray-200', 'justify-start']
                          },
                          {
                            flex: 1,
                            field: 'alamat_domisili',
                            headerName:  'Alamat Domisili',
                            sortable: false, resizable: true, filter: 'ColFilter',
                            cellClass: ['border-r', '!border-gray-200', 'justify-start']
                          },
                          {
                            flex: 1,
                            field: 'kota_domisili',
                            headerName:  'Kota Domisili',
                            sortable: false, resizable: true, filter: 'ColFilter',
                            cellClass: ['border-r', '!border-gray-200', 'justify-start']
                          }
                          ]" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldPopup label="" class="w-full py-2 !mt-0" valueField="id" displayField="nama"
                :hints="formErrors.m_petugas_pemasukan_id" :value="item.m_petugas_pemasukan_id"
                @input="(v)=>item.m_petugas_pemasukan_id=v" :api="{
                            url: `${store.server.url_backend}/operation/m_kary`,
                            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                            params: {
                              simplest:true,
                            },
                            onsuccess:(response) => {
                              response.page = response.current_page
                              response.hasNext = response.has_next
                              return response
                            }
                          }" placeholder="" :check="false" :columns="[{
                            headerName: 'No',
                            valueGetter:(p)=>p.node.rowIndex + 1,
                            width: 60,
                            sortable: false, resizable: false, filter: false,
                            cellClass: ['justify-center', 'bg-gray-50']
                          },
                          {
                            flex: 1,
                            field: 'nama',
                            headerName:  'Nama',
                            sortable: false, resizable: true, filter: 'ColFilter',
                            cellClass: ['border-r', '!border-gray-200', 'justify-start']
                          },
                          {
                            flex: 1,
                            field: 'alamat_domisili',
                            headerName:  'Alamat Domisili',
                            sortable: false, resizable: true, filter: 'ColFilter',
                            cellClass: ['border-r', '!border-gray-200', 'justify-start']
                          },
                          {
                            flex: 1,
                            field: 'kota_domisili',
                            headerName:  'Kota Domisili',
                            sortable: false, resizable: true, filter: 'ColFilter',
                            cellClass: ['border-r', '!border-gray-200', 'justify-start']
                          }
                          ]" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.no_prefix"
                :errorText="formErrors.no_prefix?'failed':''" @input="v=>item.no_prefix=v" :hints="formErrors.no_prefix"
                :check="false" label="" placeholder="No. Prefix" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.no_suffix"
                :errorText="formErrors.no_suffix?'failed':''" @input="v=>item.no_suffix=v" :hints="formErrors.no_suffix"
                :check="false" label="" placeholder="No. Sufix" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <!-- <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.sektor" @input="v=>item.sektor=v" :errorText="formErrors.sektor?'failed':''"
                :hints="formErrors.sektor" valueField="id" displayField="deskripsi" :api="{          
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            transform:false,
                            join:false,
                            where:`this.is_active=true and this.group='SEKTOR'`
                          }
                      }" placeholder="Pilih Sektor" label="" :check="false" /> -->
              <FieldPopup label="" class="w-full py-2 !mt-0" valueField="id" displayField="deskripsi"
                :hints="formErrors.sektor" :value="item.sektor"
                @input="(v)=>item.sektor=v" :api="{
                            url: `${store.server.url_backend}/operation/m_general`,
                            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                            params: {
                              simplest:true,
                              transform:false,
                            join:false,
                            where:`this.is_active=true and this.group='SEKTOR'`
                            },
                            onsuccess:(response) => {
                              response.page = response.current_page
                              response.hasNext = response.has_next
                              return response
                            }
                          }" placeholder="" :check="false" :columns="[{
                            headerName: 'No',
                            valueGetter:(p)=>p.node.rowIndex + 1,
                            width: 60,
                            sortable: false, resizable: false, filter: false,
                            cellClass: ['justify-center', 'bg-gray-50']
                          },
                          {
                            flex: 1,
                            field: 'deskripsi',
                            headerName:  'Nama Sektor',
                            sortable: false, resizable: true, filter: 'ColFilter',
                            cellClass: ['border-r', '!border-gray-200', 'justify-start']
                          },
                          ]" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full py-2 !mt-0" :value="item.ukuran"
                @input="v=>{
            if(v){
              item.ukuran=v
            }else{
              item.ukuran=null
            }
          }" :errorText="formErrors.ukuran?'failed':''" :hints="formErrors.ukuran" valueField="id"
                displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:`this.is_active=true and this.group='UKURAN KONTAINER'`

              }
          }" placeholder="Pilih Jumlah Kontainer" label="" :check="true" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.jenis" @input="v=>item.jenis=v" :errorText="formErrors.jenis?'failed':''"
                :hints="formErrors.jenis" valueField="id" displayField="deskripsi" :api="{          
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            transform:false,
                            join:false,
                            where:`this.is_active=true and this.group='JENIS KONTAINER'`
                          }
                      }" placeholder="Pilih Jenis" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <div class="flex justify-center">
                <button type="button" @click="removeDetail(i)" :disabled="!actionText" title="Hapus">
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

  <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 " v-if="activeTabIndex === 1">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <table class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] w-[20%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Customer
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] w-[10%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              No. AJU
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] w-[10%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Tgl. AJU
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] w-[10%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              PEB/PIB
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] w-[10%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Tgl PEB/PIB
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] w-[10%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              No. SPPB
            </td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(itemAju, i) in detailArrAju" :key="itemAju.id" class="border-t" v-if="detailArrAju.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="itemAju.m_customer_id" @input="v=>itemAju.m_customer_id=v"
                :errorText="formErrors.m_customer_id?'failed':''" :hints="formErrors.m_customer_id" valueField="id"
                displayField="nama_perusahaan" :api="{          
                            url: `${store.server.url_backend}/operation/m_customer`,
                            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                            params: {
                              simplest:true,
                              //transform:false,
                              //join:false,
                              //where:`this.is_active=true and this.group='JENIS KONTAINER'`
                            }
                        }" placeholder="Pilih Customer" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0" :value="itemAju.no_ppjk"
                :errorText="formErrors.no_ppjk?'failed':''" @input="v=>itemAju.no_ppjk=v" :hints="formErrors.no_ppjk"
                :check="false" label="" placeholder="No. AJU" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0" :value="itemAju.tanggal"
                :errorText="formErrors.tanggal?'failed':''" @input="v=>itemAju.tanggal=v" :hints="formErrors.tanggal"
                :check="false" label="" placeholder="Tgl. AJU" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0" :value="itemAju.peb_pib"
                :errorText="formErrors.peb_pib?'failed':''" @input="v=>itemAju.peb_pib=v" :hints="formErrors.peb_pib"
                :check="false" label="" placeholder="No. PEB/PIB" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0" :value="itemAju.tanggal_peb_pib"
                :errorText="formErrors.tanggal_peb_pib?'failed':''" @input="v=>itemAju.tanggal_peb_pib=v"
                :hints="formErrors.tanggal_peb_pib" :check="false" label="" placeholder="tgl. PEB/PIB" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0" :value="itemAju.no_sppb"
                :errorText="formErrors.no_sppb?'failed':''" @input="v=>itemAju.no_sppb=v" :hints="formErrors.no_sppb"
                :check="false" label="" placeholder="No. SPPB" />
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

  <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 " v-if="activeTabIndex === 2">
    <button
      title="Add to List"
      @click="addDetailBerkas"
      >
        <div class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
          <icon fa="plus" size="sm mr-0.5"/> Add to List
        </div>
    </button>
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <table class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] w-[20%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nama Berkas
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] w-[10%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Foto Berkas
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] w-[10%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Tanggal
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] w-[10%] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Catatan
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] w-[5%]">
              Action
            </td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(itemBerkas, i) in detailArrBerkas" :key="itemBerkas.id" class="border-t"
            v-if="detailArrBerkas.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="itemBerkas.nama_berkas"
                :errorText="formErrors.nama_berkas?'failed':''" @input="v=>itemBerkas.nama_berkas=v"
                :hints="formErrors.nama_berkas" :check="false" label="" placeholder="Nama Berkas" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldUpload class="col-span-9 w-full !mt-3" :bind="{ readonly: !actionText, disabled: !actionText }"
                :value="values.foto_berkas" @input="(v) => values.foto_berkas = v"
                :reducerDisplay="val => !val ? null : val.split(':::')[val.split(':::').length - 1]" :api="{	
                  url: `${store.server.url_backend}/operation/t_buku_order/upload`,	
                  headers: { Authorization: `${store.user.token_type} ${store.user.token}` },	
                  params: { field: 'foto_berkas' },	
                  onsuccess: response => response,	
                  onerror: (error) => {},	
                }" :hints="formErrors.foto_berkas" placeholder="Masukan File" fa-icon="upload"
                :accept="acceptType('foto_berkas')" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="itemBerkas.tgl"
                :errorText="formErrors.tgl?'failed':''" @input="v=>itemBerkas.tgl=v" :hints="formErrors.tgl"
                :check="false" type="date" label="" placeholder="Tgl. Berkas" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="itemBerkas.catatan"
                :errorText="formErrors.catatan?'failed':''" @input="v=>itemBerkas.catatan=v" :hints="formErrors.catatan"
                :check="false" type="textarea" label="" placeholder="Catatan" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <div class="flex justify-center">
                <button type="button" @click="removeDetailBerkas(i)" :disabled="!actionText" title="Hapus">
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