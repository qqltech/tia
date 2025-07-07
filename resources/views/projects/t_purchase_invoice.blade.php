<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">PURCHASE INVOICE</h1>
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
        <button @click="filterShowData('IN APPROVAL')" :class="filterButton === 'IN APPROVAL' ? 'bg-blue-600 text-white hover:bg-blue-600' 
          : 'border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          IN APPROVAL
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('IN PROCESS')" :class="filterButton === 'IN PROCESS' ? 'bg-yellow-600 text-white hover:bg-yellow-600' 
          : 'border border-yellow-600 text-yellow-600 bg-white hover:bg-yellow-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          IN PROCESS
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('COMPLETED')" :class="filterButton === 'COMPLETED' ? 'bg-purple-600 text-white hover:bg-purple-600' 
          : 'border border-purple-600 text-purple-600 bg-white hover:bg-purple-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          COMPLETED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('CANCEL')" :class="filterButton === 'CANCEL' ? 'bg-red-600 text-white hover:bg-red-600' 
          : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          CANCEL
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
        <h1 class="text-lg font-bold leading-none">Form Purchase Invoice</h1>
        <p class="text-gray-100 leading-none">Transaction Purchase Invoice</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 items-start">
    <!-- col-span-2 -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>data.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.status"
        :errorText="formErrors.status?'failed':''" @input="v=>data.status=v" :hints="formErrors.status" label="Status"
        placeholder="Status" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ disabled: true, readonly: true }" class="w-full !mt-3 pointer-events-none" :value="data.tanggal"
        :errorText="formErrors.tanggal?'failed':''" :hints="formErrors.tanggal" :check="false" type="date"
        label="Tanggal" placeholder="Tanggal" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_pi"
        :errorText="formErrors.no_pi?'failed':''" @input="v=>data.no_pi=v" :hints="formErrors.no_pi" label="No. PI"
        placeholder="No. PI" :check="false" />
    </div>
    <div>
      <FieldPopup :bind="{ readonly: !actionText }" label="No. PO" class="w-full !mt-3" valueField="id"
        displayField="t_po.no_po" :value="data.t_lpb_id" @input="(v)=>data.t_lpb_id=v"
        :errorText="formErrors.t_lpb_id?'failed':''" :hints="formErrors.t_lpb_id" @update:valueFull="(response)=>{
        // if(response == undefined) data.tipe_po =''; else data.tipe_po=response.tipe;
        if(response == undefined) {
          detailArr.splice(0, 100);
          data.t_po_id = '';
          data.tanggal_lpb = '';
          data.jenis_ppn = '';
          data.m_supplier_id = '';
          data.no_po = '';
          data.tipe_po = '';
          // data.t_lpb_id = '';
        }
        else {
          data.t_po_id = response.t_po_id;
          // data.t_lpb_id = response.id;
          data.tanggal_lpb = response.tanggal_lpb;
          data.jenis_ppn = response['t_po.ppn'];
          data.m_supplier_id = response.m_supplier_id;
          data.no_po = response['t_po.no_po'];
          data.tipe_po = response['t_po.tipe'];
          poChanged(response.id);
        }
        $log(response);
      }" :api="{
              url: `${store.server.url_backend}/operation/t_lpb`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:false,
                //selectfield: 'id, no_draft, no_po, tanggal, tipe, status, catatan',
                searchfield: 'this.no_draft, this.no_po, this.tanggal, this.tipe, this.status, this.catatan',
                // where: `this.status!='DRAFT' AND this.status!='REJECTED' AND this.status!='REVISED'`
              }
            }" placeholder="Pilih No. PO" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 't_po.no_draft',
              headerName:  'No. Draft',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 't_po.no_po',
              headerName:  'No. PO',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 't_po.tanggal',
              headerName:  'Tanggal',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 't_po.tipe',
              headerName:  'Tipe',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 't_po.status',
              headerName:  'Status',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 't_po.catatan',
              headerName:  'Catatan',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.tipe_po"
        :errorText="formErrors.tipe_po?'failed':''" @input="v=>data.tipe_po=v" :hints="formErrors.tipe_po"
        label="Tipe PO" placeholder="Tipe PO" :check="false" valueField="id" displayField="tipe" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }"
        :value="data.tipe_pembayaran_id" @input="v=>data.tipe_pembayaran_id=v"
        :errorText="formErrors.tipe_pembayaran_id?'failed':''" :hints="formErrors.tipe_pembayaran_id" valueField="id"
        displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='TIPE PEMBAYARAN INV'`
              }
          }" @update:valueFull="(response) => {
            $log(response.id);
      }" label="Tipe Pembayaran" fa-icon="sort-desc" placeholder="Pilih Tipe Pembayaran" :check="false" />
    </div>
    <div>
      <FieldPopup class="w-full !mt-3" v-if="data.tipe_pembayaran_id == 839":api="{
        url: `${store.server.url_backend}/operation/m_coa`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest:false,
          transform:false,
          join:true,
          // override:true,
          // where:`this.is_active=true`,
          // searchfield:'this.no_id, this.nip, this.nama, this.alamat_domisili',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili'
          // notin: `this.id: ${actionText=='Edit' ? [data.m_akun_pembayaran_id] : []}`, 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="nama_coa" valueField="id" :bind="{ readonly: !actionText }" :value="data.m_coa_id"
        @input="(v)=>data.m_coa_id=v" @update:valueFull="(response)=>{
        $log(response);
      }" :errorText="formErrors.m_coa_id?'failed':''" class="w-full !mt-3"
        :hints="formErrors.m_coa_id" placeholder="Pilih Akun Pembayaran" :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'nomor',
          headerName: 'No. COA',
          sortable: true, resizable: true, filter: false,
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'Nama COA',
          field: 'nama_coa',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Kategori',
          field: 'kategori.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Jenis',
          field: 'jenis',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Parent COA',
          field: 'induk',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
      ]" />
    </div>
    <div>
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/m_supplier`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest:false,
          transform:false,
          join:false,
          // override:true,
          // where:`this.is_active=true`,
          // searchfield:'this.no_id, this.nip, this.nama, this.alamat_domisili',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili' 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="nama" valueField="id" :bind="{ readonly: true }" :value="data.m_supplier_id"
        @input="(v)=>data.m_supplier_id=v" @update:valueFull="(response)=>{
        $log(response);
      }" :errorText="formErrors.m_supplier_id?'failed':''" class="w-full !mt-3" :hints="formErrors.m_supplier_id"
        placeholder="Pilih Supplier" :check='false' :columns="[
        {
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
    <!-- <div>
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/m_faktur_pajak`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest:false,
          transform:false,
          join:false,
          // override:true,
          // where:`this.is_active=true`,
          // searchfield:'this.no_id, this.nip, this.nama, this.alamat_domisili',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili' 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="prefix" valueField="id" :bind="{ readonly: !actionText }" :value="data.m_faktur_pajak_id"
        @input="(v)=>data.m_faktur_pajak_id=v" @update:valueFull="(response)=>{
        $log(response);
      }" :errorText="formErrors.m_faktur_pajak_id?'failed':''" class="w-full !mt-3"
        :hints="formErrors.m_faktur_pajak_id" placeholder="Pilih Faktur Pajak" :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'prefix',
          headerName: 'Prefix',
          sortable: true, resizable: true, filter: false,
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'No. Awal',
          field: 'no_awal',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },        {
          headerName: 'No. Akhir',
          field: 'no_akhir',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
      ]" />
    </div> -->
    <div>
      <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" class="w-full !mt-3" valueField="id" displayField="no_faktur_pajak" :value="data.no_faktur_pajak"
        @input="(v)=>data.no_faktur_pajak=v" :errorText="formErrors.no_faktur_pajak?'failed':''"
        :hints="formErrors.no_faktur_pajak"  label="No. Faktur Pajak" placeholder="Pilih No. Faktur Pajak" :check="false" />
    </div>
    <div>
      <!-- <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, clearable:false }" :value="data.t_lpb_id"
        @input="v=>data.t_lpb_id=v" :errorText="formErrors.name?'failed':''" :hints="formErrors.name" valueField="no_lpb"
        displayField="no_lpb" :api="{
            url: `${store.server.url_backend}/operation/t_lpb`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true
            },
            onsuccess: (res)=>{
              $log(res);
              return res;
            }
        }" label="No. LPB" placeholder="Pilih No. LPB" :check="false" /> -->

      <FieldPopup :bind="{ readonly: true }" label="No. LPB" class="w-full !mt-3" valueField="id" displayField="no_lpb"
        :value="data.t_lpb_id" @input="(v)=>data.t_lpb_id=v" :errorText="formErrors.t_lpb_id?'failed':''"
        :hints="formErrors.t_lpb_id" @update:valueFull="(response)=>{
          if(response == undefined) data.tanggal_lpb = '';
        else data.tanggal_lpb=response.tanggal_lpb;
      }" :api="{
            url: `${store.server.url_backend}/operation/t_lpb`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              //selectfield: 'id, no_lpb, status, catatan, tanggal_lpb',
              searchfield: 'this.no_lpb, this.status, this.catatan'
            }
          }" placeholder="Pilih No. LPB" :check="false" :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'no_lpb',
            headerName:  'No. LPB',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-start']
          },
          {
            flex: 1,
            field: 'status',
            headerName:  'Status',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-start']
          },
          {
            flex: 1,
            field: 'catatan',
            headerName:  'Catatan',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-start']
          }
          ]" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true, disabled: true }" class="w-full !mt-3" :value="data.tanggal_lpb"
        :errorText="formErrors.tanggal_lpb?'failed':''" @input="v=>checkTglEdDate(v)" :hints="formErrors.tanggal_lpb"
        type="date" label="Tanggal LPB" placeholder="Masukkan Tanggal LPB" :check="false" />
    </div>
    <div>
      <div class="grid grid-cols-2 gap-4">
        <!-- <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, clearable:true }" :value="data.jenis_ppn"
          @input="v=>data.jenis_ppn=v" :errorText="formErrors.jenis_ppn?'failed':''" :hints="formErrors.jenis_ppn"
          valueField="id" displayField="key"
          :options="[{'id' : 'INCLUDE' , 'key' : 'INCLUDE'},{'id': 'EXCLUDE', 'key' : 'EXCLUDE'}]"
          placeholder=" Pilih PPN" label="PPN" :check="false" fa-icon="sort-desc" /> -->
        <FieldSelect class="w-full !mt-3" :bind="{ readonly: true, disabled: true }" valueField="id"
          displayField="deskripsi" :value="data.jenis_ppn" @input="(v)=>data.jenis_ppn=v"
          :errorText="formErrors.jenis_ppn?'failed':''" :hints="formErrors.jenis_ppn" :options="allPpnOptions"
          label="Tipe PPN" placeholder="Pilih Tipe PPN" :check="false" />


        <FieldX class="w-full !mt-3" :bind="{ readonly: true }"
          :value="data.persen_ppn !== undefined ? `${data.persen_ppn}%` : ''"
          :errorText="formErrors.persen_ppn?'failed':''" @input="v => data.persen_ppn = v.replace('%', '')"
          :hints="formErrors.persen_ppn" placeholder="Persen PPN" :check="false" />
      </div>
    </div>
    <!-- <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="data.nilai_ppn"
        :errorText="formErrors.nilai_ppn?'failed':''" @input="v=>data.nilai_ppn=v" :hints="formErrors.nilai_ppn"
        label="Nilai PPN" placeholder="Nilai PPN" :check="false" />
    </div> -->
    <div>
      <div class="grid grid-cols-2 gap-4">
        <!-- <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.jenis_pph"
          @input="v=>data.jenis_pph=v" :errorText="formErrors.jenis_pph?'failed':''" :hints="formErrors.jenis_pph"
          valueField="id" displayField="key"
          :options="[{'id' : 1 , 'key' : 'INCLUDE', 'persen': 2},{'id': 2, 'key' : 'NOT INCLUDE', 'persen': 0}]"
          label="PPH" fa-icon="sort-desc" placeholder="Pilih PPH" :check="false" /> -->
        <FieldSelect :bind="{ readonly: !actionText, disabled: !actionText }" class="w-full !mt-3" valueField="id" displayField="deskripsi" :value="data.jenis_pph"
          @input="(v)=>data.jenis_pph=v" :errorText="formErrors.jenis_pph?'failed':''" :hints="formErrors.jenis_pph"
          :options="allPphOptions" @update:valueFull="(res)=>{
            if(res) {
              data.persen_pph = res.deskripsi2;
            }
            else {
              data.persen_pph = 0;
              $log(data.persen_pph);
            }
      }" label="Tipe PPH" placeholder="Pilih Tipe PPH" :check="false" />
        <FieldX class="w-full !mt-3" :bind="{ readonly: !actionText, disabled: !actionText }"
          :value="data.persen_pph !== undefined ? `${data.persen_pph}%` : ''"
          :errorText="formErrors.persen_pph?'failed':''" @input="v => data.persen_pph = v.replace('%', '')"
          :hints="formErrors.persen_pph" placeholder="Persen PPH" :check="false" />
      </div>
    </div>
    <!-- <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="data.nilai_pph"
        :errorText="formErrors.nilai_pph?'failed':''" @input="v=>data.nilai_pph=v" :hints="formErrors.nilai_pph"
        label="Nilai PPH" placeholder="Nilai PPH" :check="false" />
    </div> -->
    <div>
      <FieldX type="textarea" :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>data.catatan=v" :hints="formErrors.catatan"
        label="Catatan" placeholder="Masukan Catatan" :check="false" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="data.jumlah "
        :errorText="formErrors.jumlah ?'failed':''" @input="v=>data.jumlah =v" :hints="formErrors.jumlah "
        label="Jumlah" placeholder="Jumlah" :check="false" />
    </div>


  </div>
  <hr />
  <!-- START TABLE DETAIL -->

  <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <!-- <ButtonMultiSelect v-if="actionText" @add="addDetailArr" :api="{
        url: `${store.server.url_backend}/operation/t_purchase_order_d`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: true, 
            where: `t_no_po_id=${data.t_po_id ? data.t_po_id : '0'}`,
            searchfield: 'this.kode, this.nama_item, this.tipe_item',
            notin: `this.id: ${detailArr.map((det)=> (det.t_po_detail_id))}`
            },
            onsuccess: (response) => {
              $log(response.data[0]['m_item.id'])
              response.data = [...response.data].map((dt) => {
                return {
                  t_pi_id: data.id || null,
                  t_po_id: dt.t_no_po_id,
                  t_no_po: dt.['t_no_po.no_po'],
                  t_po_detail_id: dt.id,
                  m_item_id: dt['m_item.id'],
                  m_item_id: dt.['m_item.id'],
                  kode: dt.['m_item.kode'],
                  nama_item: dt.['m_item.nama_item'],
                  tipe_item: dt.['m_item.tipe_item'],
                  quantity: dt.quantity,
                  harga: dt.harga,
                  satuan: 'Pcs',
                  catatan: '',
                }
              })
            return response
          }
        }" :columns="[{
          checkboxSelection: true,
          headerCheckboxSelection: true,
          headerName: 'No',
          valueGetter: (params) => '',
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        }, 
        {
          pinned: false,
          headerName: 'No. PO',
          field: 't_no_po',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Kode Item',
          field: 'kode',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Nama Item',
          field: 'nama_item',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Tipe Item',
          field: 'tipe_item',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, ]">
        <div class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300">
          <icon fa="plus" size="sm" />
          <span>Add To List</span>
        </div>
      </ButtonMultiSelect> -->
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              No. PO</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Kode Item</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nama Item</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              quantity</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Satuan</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Harga</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Total Amount</td>
            <td v-if="data.tipe_po=='Asset'"
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Disc (%)</td>
            <td v-if="data.tipe_po=='Asset'"
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Disc.2 (%)</td>
            <td v-if="data.tipe_po=='Asset'"
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Disc Amt</td>
            <td v-if="data.tipe_po=='Asset'"
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Total Disc</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Catatan</td>
            <td v-show="actionText"
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              Action</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="i" v-show="detailArr.length > 0">
            <td class="p-1 text-center border border-[#CACACA]">
              {{ i+1 }}.
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <!-- <p class="text-black leading-none">{{ detailArr[i].t_no_po }}</p> -->
              <FieldX type="string" :bind="{ readonly: true }" class="m-0" :value="detailArr[i].t_no_po"
                :errorText="formErrors.t_no_po?'failed':''" @input="v=>detailArr[i].t_no_po=v"
                :hints="formErrors.t_no_po" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <!-- <p class="text-black leading-none">{{ detailArr[i].kode }}</p> -->
              <FieldX type="string" :bind="{ readonly: true }" class="m-0" :value="detailArr[i].kode"
                :errorText="formErrors.kode?'failed':''" @input="v=>detailArr[i].kode=v" :hints="formErrors.kode"
                :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldX type="string" :bind="{ readonly: true }" class="m-0" :value="detailArr[i].nama_item"
                :errorText="formErrors.nama_item?'failed':''" @input="v=>detailArr[i].nama_item=v"
                :hints="formErrors.nama_item" :check="false" />
              <!-- <p class="text-black leading-none">{{ detailArr[i].nama_item }}</p> -->
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber type="number" :bind="{ readonly: true }" class="m-0" :value="detailArr[i].quantity"
                :errorText="formErrors.quantity?'failed':''" @input="v=>detailArr[i].quantity=v"
                :hints="formErrors.quantity" :check="false" />
              <!-- <p class="text-black leading-none">{{ detailArr[i].quantity }}</p> -->
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <!-- <p class="text-black leading-none">{{ detailArr[i].satuan }}</p> -->
              <FieldX type="string" :bind="{ readonly: true }" class="m-0" :value="detailArr[i].satuan"
                :errorText="formErrors.satuan?'failed':''" @input="v=>detailArr[i].satuan=v" :hints="formErrors.satuan"
                :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber type="number" :bind="{ readonly: true }" class="m-0" :value="detailArr[i].harga"
                :errorText="formErrors.harga?'failed':''" @input="v=>detailArr[i].harga=v" :hints="formErrors.harga"
                :check="false" />
              <!-- <p class="text-black leading-none">{{ detailArr[i].harga }}</p> -->
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber type="number" :bind="{ readonly: true }" class="m-0" :value="detailArr[i].total_amount"
                :errorText="formErrors.total_amount?'failed':''" @input="v=>detailArr[i].total_amount=v"
                :hints="formErrors.total_amount" :check="false" />
              <!-- <p class="text-black leading-none">{{ detailArr[i].total_amount }}</p> -->
            </td>
            <td class="p-1 text-center border border-[#CACACA]" v-if="data.tipe_po=='Asset'">
              <FieldNumber :bind="{ readonly: true }" class="m-0" :value="detailArr[i].disc1"
                :errorText="formErrors.disc1?'failed':''" @input="v=>detailArr[i].disc1=v" :hints="formErrors.disc1"
                :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]" v-if="data.tipe_po=='Asset'">
              <FieldNumber :bind="{ readonly: true }" class="m-0" :value="detailArr[i].disc2"
                :errorText="formErrors.disc2?'failed':''" @input="v=>detailArr[i].disc2=v" :hints="formErrors.disc2"
                :check="false" />
            </td>

            <td class="p-1 text-center border border-[#CACACA]" v-if="data.tipe_po=='Asset'">
              <FieldNumber :bind="{ readonly: true }" class="m-0" :value="detailArr[i].disc_amt"
                :errorText="formErrors.disc_amt?'failed':''" @input="v=>detailArr[i].disc_amt=v"
                :hints="formErrors.disc_amt" :check="false" />
              <!-- <p class="text-black leading-none">{{ detailArr[i].disc_amt }}</p> -->
            </td>
            <td class="p-1 text-center border border-[#CACACA]" v-if="data.tipe_po=='Asset'">
              <FieldNumber :bind="{ readonly: true }" class="m-0" :value="detailArr[i].total_disc"
                :errorText="formErrors.total_disc?'failed':''" @input="v=>detailArr[i].total_disc=v"
                :hints="formErrors.total_disc" :check="false" />
              <!-- <p class="text-black leading-none">{{ detailArr[i].total_disc }}</p> -->
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldX type="textarea" :bind="{ readonly: true }" class="m-0" :value="detailArr[i].catatan"
                :errorText="formErrors.catatan?'failed':''" @input="v=>detailArr[i].catatan=v"
                :hints="formErrors.catatan" :check="false" />
            </td>
            <td v-show="actionText" class="p-1 border border-[#CACACA]">
              <div class="flex justify-center">
                <button type="button" @click="delDetailArr(i)" :disabled="!actionText">
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

  <div class="w-full flex justify-center">
    <div class="w-md">
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">Total Amount :</label>
        <FieldNumber :bind="{ readonly: true }" class="w-full content-center !mt-3" :value="data.total_amount"
          @input="v => data.total_amount = v" :errorText="formErrors.total_amount ? 'failed' : ''"
          :hints="formErrors.total_amount" :check="false" />
      </div>
      <div v-if="data.tipe_po=='Asset'" class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">Total Disc Amount :</label>
        <FieldNumber :bind="{ readonly: true }" class="w-full content-center !mt-3" :value="data.total_disc_amount"
          @input="v => data.total_disc_amount = v" :errorText="formErrors.total_disc_amount ? 'failed' : ''"
          :hints="formErrors.total_disc_amount" :check="false" />
      </div>
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">DPP :</label>
        <FieldNumber :bind="{ readonly: true }" class="w-full content-center !mt-3" :value="data.dpp"
          @input="v => data.dpp = v" :errorText="formErrors.dpp ? 'failed' : ''" :hints="formErrors.dpp"
          :check="false" />
      </div>
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">Total PPN :</label>
        <FieldNumber :bind="{ readonly: true }" class="w-full content-center !mt-3" :value="data.total_ppn"
          @input="v => data.total_ppn = v" :errorText="formErrors.total_ppn ? 'failed' : ''"
          :hints="formErrors.total_ppn" :check="false" />
      </div>
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">Total PPH :</label>
        <FieldNumber :bind="{ readonly: true }" class="w-full content-center !mt-3" :value="data.total_pph"
          @input="v => data.total_pph = v" :errorText="formErrors.total_pph ? 'failed' : ''"
          :hints="formErrors.total_pph" :check="false" />
      </div>
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-3">
        <label class="!mt-4 !ml-3">Grand Total :</label>
        <FieldNumber :bind="{ readonly: true }" class="w-full content-center !mt-3" :value="data.grand_total"
          @input="v => data.grand_total = v" :errorText="formErrors.grand_total ? 'failed' : ''"
          :hints="formErrors.grand_total" :check="false" />
      </div>
    </div>
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
    <button v-show="((actionText=='Edit' || actionText=='Create'  || actionText=='Copy') && data.status=='DRAFT')" class="text-sm rounded py-2 px-2.5 text-white bg-purple-600 hover:bg-purple-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="post">
      <icon fa="location-arrow" />
      <span>Post</span>
    </button>
  </div>
  <hr v-show="isApproval" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="isApproval">
    <i class="text-gray-500 text-[12px] mr-4">Tekan CTRL + S untuk shortcut Save Data</i>
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