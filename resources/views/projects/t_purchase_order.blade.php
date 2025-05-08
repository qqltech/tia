<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">PURCHASE ORDER</h1>
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
        <button @click="filterShowData('IN APPROVAL')" :class="filterButton === 'IN APPROVAL' ? 'bg-blue-600 text-white hover:bg-blue-600' 
          : 'border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white'"
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
      <!-- <RouterLink :to="$route.path + '/create?' + (Date.parse(new Date()))" class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white text-sm rounded py-1 px-2.5
      transition-colors duration-300">
        Create New
      </RouterLink> -->
      <button class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white text-sm rounded py-1 px-2.5
      transition-colors duration-300" @click="isModalOpen=true">Create New</button>
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

<div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
  <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Pilih Tipe PO</h2>
      <hr>
    </div>
    <div class="p-1 ">
      <div class=" flex justify-center">
        <button @click="setTipe('Item')"
          class="hover:bg-blue-100 text-blue-500 font-semibold w-50 h-50 mx-5 text-center rounded-lg"
          :class="tipe === 'Item' ? 'bg-blue-200': 'bg-blue-50'">
          <div class="h-40 w-full flex items-center justify-center">
            <icon fa="box-open" class="text-8xl" />
          </div>
          <div
            class="bg-blue-500 hover:bg-blue-600 text-white text-2xl font-semibold h-10 w-full rounded-b-lg flex items-center justify-center">
            <p>Item</p>
          </div>
        </button>
        <button @click="setTipe('Asset')"
          class="hover:bg-blue-100 text-blue-500 font-semibold w-50 h-50 mx-5 text-center rounded-lg"
          :class="tipe === 'Asset' ? 'bg-blue-200': 'bg-blue-50'"
          >
          <div class="h-40 w-full flex items-center justify-center">
            <icon fa="boxes-stacked" class="text-8xl" />
          </div>
          <div
            class="bg-blue-500 hover:bg-blue-600 text-white text-2xl font-semibold h-10 w-full rounded-b-lg flex items-center justify-center">
            <p>Asset</p>
          </div>
        </button>
      </div>
    </div>
    <div class="flex justify-end pt-4">
      <RouterLink :to="`${$route.path}/create?${Date.parse(new Date())}&tipe=${tipe}`"
        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 mr-4">
        Create</RouterLink>

      <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600" @click="isModalOpen=false; setTipe('')">Cancel</button>
    </div>
  </div>
</div>
@else

<!-- FORM DATA -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md pt-2 pb-3 px-4">
    <div class="flex items-center gap-2">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
      <div class="flex flex-col py-1 gap-1">
        <h1 class="text-lg font-bold leading-none">Form Purchase Order</h1>
        <p class="text-gray-100 leading-none">Transaction Purchase Order</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 items-start">
    <!-- col-span-2 -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_draft" @input="v=>data.no_draft=v"
        :errorText="formErrors.no_draft?'failed':''" :hints="formErrors.no_draft" placeholder="No. Draft"
        :check="false" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, clearable:true }" :value="data.status"
        @input="v=>data.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status" valueField="id"
        displayField="key" :options="[{'id' : 'Posted' , 'key' : 'Posted'},
      {'id' : 'In Approval', 'key' : 'In Approval'},
      {'id' : 'In Process' , 'key' : 'In Process'},
      {'id' : 'Complete', 'key' : 'Complete'},
      {'id' : 'Cancel' , 'key' : 'Cancel'}]" placeholder="Pilih Status" label="Status" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ disabled: true, readonly: true}" class="w-full !mt-3" :value="data.tanggal"
        :errorText="formErrors.tanggal?'failed' :''" @input="v=>data.tanggal=v" :hints="formErrors.tanggal"
        :check="false" type="date" label="Tanggal" placeholder="Pilih Tanggal" />
    </div>
    <div>
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/t_purchase_order`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest:false,
          transform:false,
          join:true,
          // override:true,
          where:`this.status='APPROVED'`,
          // searchfield:'this.no_id, this.nip, this.nama, this.alamat_domisili',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili'
          notin: `this.id: ${actionText=='Edit' ? [data.id] : []}`, 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="no_po" valueField="id" :bind="{ readonly: !actionText }" :value="data.t_purchase_order_id"
        @input="(v)=>data.t_purchase_order_id=v" @update:valueFull="(response)=>{
        if(response) {getPOD(response.id)}
        else detailArr.splice(0, 100);
      }" :errorText="formErrors.t_purchase_order_id?'failed':''" class="w-full !mt-3"
        :hints="formErrors.t_purchase_order_id" label="Copy PO" placeholder="Pilih Copy PO" :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'no_draft',
          headerName: 'No Draft',
          sortable: true, resizable: true, filter: false,
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'No. PO',
          field: 'no_po',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Supplier',
          field: 'm_supplier.nama',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Tipe',
          field: 'tipe',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Status',
          field: 'status',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
      ]" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_po" @input="v=>data.no_po=v"
        :errorText="formErrors.no_po?'failed':''" :hints="formErrors.no_po" placeholder="No. PO" :check="false" />
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
          where:`this.is_active=true`,
          searchfield:'this.kode, this.nama, this.alamat, this.kota',
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="nama" valueField="id" :bind="{ readonly: !actionText }" :value="data.m_supplier_id"
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
        {
          headerName: 'Alamat',
          field: 'alamat',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Kota',
          field: 'kota',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
      ]" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.tipe" @input="v=>data.tipe=v"
        :errorText="formErrors.tipe?'failed':''" :hints="formErrors.tipe" placeholder="Tipe" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" class="w-full !mt-3" :value="data.estimasi_kedatangan"
        :errorText="formErrors.estimasi_kedatangan?'failed':''" @input="v=>data.estimasi_kedatangan=v"
        :hints="formErrors.estimasi_kedatangan" :check="false" type="date" label="Estimasi Kedatangan"
        placeholder="Pilih Estimasi Kedatangan" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.termin"
        @input="v=>data.termin=v" :errorText="formErrors.termin?'failed':''" :hints="formErrors.termin" valueField="id"
        displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='TERMIN'`
              }
          }" label="Termin" fa-icon="sort-desc" placeholder="Pilih Termin" :check="false" />
    </div>
    <div>
      <!-- <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.ppn"
        @input="v=>data.ppn=v" :errorText="formErrors.ppn?'failed':''" :hints="formErrors.ppn" valueField="id"
        displayField="key" :options="[{'id' : 'INCLUDE' , 'key' : 'INCLUDE'},
      {'id' : 'EXCLUDE', 'key' : 'EXCLUDE'}]" placeholder="PPN" label="PPN" fa-icon="sort-desc" :check="false" /> -->
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.ppn"
        @input="v=>data.ppn=v" :errorText="formErrors.ppn?'failed':''" :hints="formErrors.ppn" valueField="id"
        displayField="deskripsi" :options="allPpnOptions" placeholder="PPN" label="PPN" fa-icon="sort-desc"
        :check="false" />
    </div>
    <div class="flex flex-col gap-2" v-if="data.tipe=='Asset'">
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
                v-model="data.b2b"
                />
        </div>
        <div class="flex-auto">
          <i class="text-green-500">IYA</i>
        </div>
      </div>
    </div>

    <div>
      <FieldSelect class="w-full !mt-3" valueField="id" :bind="{ disabled: !actionText, clearable:true }" displayField="nama" :value="data.tipe_po"
        @input="(v)=>data.tipe_po=v" :errorText="formErrors.tipe_po?'failed':''" :hints="formErrors.tipe_po" :api="{
              url: `${store.server.url_backend}/operation/m_business_unit`,
              headers: { 'Content-Type': 'Application/json', 
              Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                //scopes: 'NoFakturNew,NoFakturPI'
              }
            }" label="Tipe PO" fa-icon="sort-desc" placeholder="Pilih Tipe PO" :check="false" />
    </div>

    <div v-if="data.tipe=='Asset'">
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.b2b_link" @input="v=>data.b2b_link=v"
        :errorText="formErrors.b2b_link?'failed':''" :hints="formErrors.b2b_link" placeholder="B2B Link"
        :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.catatan" @input="v=>data.catatan=v"
        :hints="formErrors.catatan" :check="false" type="textarea" label="Catatan" placeholder="Catatan" />
    </div>
    <div>
      <FieldX :bind="{ readonly: data.status != 'IN APPROVAL' }" class="w-full !mt-3" :value="data.alasan_revisi"
        :errorText="formErrors.alasan_revisi?'failed':''" @input="v=>data.alasan_revisi=v"
        :hints="formErrors.alasan_revisi" :check="false" type="textarea" label="Alasan Revisi"
        placeholder="Alasan Revisi" />
    </div>


  </div>
  <hr />
  <!-- START TABLE DETAIL -->
  <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <ButtonMultiSelect v-if="actionText  && data.tipe=='Item'" @add="addDetailArr" :api="{
        url: `${store.server.url_backend}/operation/m_item`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: true, 
            searchfield: 'this.kode, this.nama_item, this.tipe_item',
            notin: `this.id: ${detailArr.map((det)=> (det.m_item_id))}`,
            where: `this.is_active = true`
            },
            onsuccess: (response) => {
              response.data = [...response.data].map((dt) => {
                return {
                  t_po_id: data.id || null,
                  m_item_id: dt.id,
                  kode: dt.kode,
                  nama_item: dt.nama_item,
                  tipe_item: dt.tipe_item,
                  quantity: 0,
                  disc1: 0,
                  disc2: 0,
                  harga: 0,
                  total_amount: 0,
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
        }, {
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
      </ButtonMultiSelect>
      <ButtonMultiSelect v-if="actionText  && data.tipe=='Asset'" @add="addDetailArr" :api="{
        url: `${store.server.url_backend}/operation/m_item`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: true, 
            searchfield: 'this.kode, this.nama, this.tipe_item',
            notin: `this.id: ${detailArr.map((det)=> (det.m_item_id))}`
            },
            onsuccess: (response) => {
              response.data = [...response.data].map((dt) => {
                return {
                  t_po_id: data.id || null,
                  m_item_id: dt.id,
                  kode: dt.kode,
                  nama_item: dt.nama_item,
                  tipe_item: dt.tipe_item,
                  quantity: 0,
                  disc1: 0,
                  disc2: 0,
                  harga: 0,
                  total_amount: 0,
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
        }, {
          pinned: false,
          headerName: 'Kode Asset',
          field: 'kode',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Nama Asset',
          field: 'nama_item',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, ]">
        <div class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300">
          <icon fa="plus" size="sm" />
          <span>Add To List</span>
        </div>
      </ButtonMultiSelect>
      <!-- <button @click="deleteDetailArrAll" class="text-xs rounded py-2 px-2.5 text-white bg-red-600 hover:bg-red-700 flex gap-x-1
          items-center transition-colors duration-300">
        <icon fa="trash" size="sm" />
        <span>Hapus Semua</span>
      </button> -->
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.</td>
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
            <td v-if="data.tipe=='Asset'"
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Disc (%)</td>
            <td v-if="data.tipe=='Asset'"
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Disc.2 (%)</td>
            <td v-if="data.tipe=='Asset'"
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Disc Amt</td>
            <td v-if="data.tipe=='Asset'"
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Total Disc</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Bundling</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Catatan</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              History Harga</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              History Stok</td>
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
              <!-- <p class="text-black leading-none">{{ detailArr[i].kode }}</p> -->
              <FieldX :bind="{ readonly: true }" class="m-0" :value="detailArr[i].kode" @input="v=>detailArr[i].kode=v"
                :hints="formErrors.kode" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <!-- <p class="text-black leading-none">{{ detailArr[i].nama_item }}</p> -->
              <FieldX :bind="{ readonly: true }" class="m-0" :value="detailArr[i].nama_item"
                @input="v=>detailArr[i].nama_item=v" :hints="formErrors.nama_item" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].quantity"
                :errorText="formErrors.quantity?'failed':''" @input="v=>detailArr[i].quantity=v"
                :hints="formErrors.quantity" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <!-- <p class="text-black leading-none">{{ detailArr[i].satuan }}</p> -->
              <FieldX :bind="{ readonly: true }" class="m-0" :value="detailArr[i].satuan"
                @input="v=>detailArr[i].satuan=v" :hints="formErrors.satuan" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].harga"
                :errorText="formErrors.harga?'failed':''" @input="v=>detailArr[i].harga=v" :hints="formErrors.harga"
                :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: true }" class="m-0" :value="detailArr[i].total_amount"
                :errorText="formErrors.total_amount?'failed':''" @input="v=>detailArr[i].total_amount=v"
                :hints="formErrors.total_amount" :check="false" />
              <!-- <p class="text-black leading-none">{{ detailArr[i].total_amount }}</p> -->
            </td>
            <!-- <FieldNumber type="number" :bind="{ readonly: !actionText }" :value="detailArr[i].nominal"
              :errorText="formErrors.nominal?'failed':''" @input="v=>detailArr[i].nominal=v" :hints="formErrors.nominal"
              label="" placeholder="Nominal" :check="false" /> -->
            <td class="p-1 text-center border border-[#CACACA]" v-if="data.tipe=='Asset'">
              <FieldNumber :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].disc1"
                :errorText="formErrors.disc1?'failed':''" @input="v=>detailArr[i].disc1=v" :hints="formErrors.disc1"
                :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]" v-if="data.tipe=='Asset'">
              <FieldNumber :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].disc2"
                :errorText="formErrors.disc2?'failed':''" @input="v=>detailArr[i].disc2=v" :hints="formErrors.disc2"
                :check="false" />
            </td>

            <td class="p-1 text-center border border-[#CACACA]" v-if="data.tipe=='Asset'">
              <FieldNumber :bind="{ readonly: true }" class="m-0" :value="detailArr[i].disc_amt"
                :errorText="formErrors.disc_amt?'failed':''" @input="v=>detailArr[i].disc_amt=v"
                :hints="formErrors.disc_amt" :check="false" />
              <!-- <p class="text-black leading-none">{{ detailArr[i].disc_amt }}</p> -->
            </td>
            <td class="p-1 text-center border border-[#CACACA]" v-if="data.tipe=='Asset'">
              <FieldNumber :bind="{ readonly: true }" class="m-0" :value="detailArr[i].total_disc"
                :errorText="formErrors.total_disc?'failed':''" @input="v=>detailArr[i].total_disc=v"
                :hints="formErrors.total_disc" :check="false" />
              <!-- <p class="text-black leading-none">{{ detailArr[i].total_disc }}</p> -->
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <div class="flex w-10 space-x-2">
                <div class="flex justify-center items-center space-x-1">
                  <input
                  class="h-6 w-6 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  type="checkbox"
                  :disabled="!actionText"
                  v-model="detailArr[i].is_bundling"
                  @change="changeIsBundling(i)"
                />
                </div>
                <div class="flex items-center justify-start">
                  <i class="text-green-500">IYA</i>
                </div>
              </div>
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldX type="textarea" :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].catatan"
                :errorText="formErrors.catatan?'failed':''" @input="v=>detailArr[i].catatan=v"
                :hints="formErrors.catatan" :check="false" />
            </td>
            <td class="p-1 border border-[#CACACA] ">
              <div class="flex justify-center">
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold w-15 h-1 py-5 text-center items-center justify-center rounded-md flex items-center justify-center"
                @click="openHistoryItem(detailArr[i])">
                  <icon fa="eye" />
                </button>
              </div>
            </td>
            <td class="p-1 border border-[#CACACA] ">
              <div class="flex justify-center">

                <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold w-15 h-1 py-5 text-center items-center justify-center rounded-md flex items-center justify-center"
                @click="openHistoryStock(detailArr[i])">
                  <icon fa="eye" />
                </button>
              </div>
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
  <!-- POP UP HISTORY HARGA -->
  <div v-show="modalOpenHistoryItem" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
    <div class="modal-container bg-white  w-[70%] mx-auto rounded shadow-lg z-50 overflow-y-auto">
      <div class="modal-content py-4 text-left px-6">
        <!-- Modal Header -->
        <div class="modal-header flex items-center justify-between flex-wrap">
          <div class="flex items-center">
            <h3 class="text-xl font-semibold ml-2">History Harga Item</h3>
          </div>
        </div>

        <hr class="mt-2 mb-4">
        <div class="modal-header flex items-center justify-between flex-wrap">
          <div class="flex items-center">
            <h4 class="text-md font-bold ml-2">{{dataHistoryDataItem.itemName}}</h4>
          </div>
        </div>
        <div class="modal-header flex items-center justify-between flex-wrap">
          <div class="flex items-center">
            <h4 class="text-md ml-2">Kode {{dataHistoryDataItem.itemCode}}</h4>
          </div>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
          <table class="w-[100%] my-3 border">
            <thead>
              <tr class="border">
                <td class="border px-2 py-1 font-medium ">No</td>
                <td class="border px-2 py-1 font-medium ">Tanggal</td>
                <td class="border px-2 py-1 font-medium ">No PO</td>
                <td class="border px-2 py-1 font-medium ">Qty</td>
                <td class="border px-2 py-1 font-medium ">Harga</td>
                <td class="border px-2 py-1 font-medium ">Disc1 (%)</td>
                <td class="border px-2 py-1 font-medium ">Disc2 (%)</td>
                <td class="border px-2 py-1 font-medium ">Disc Amount</td>
                <td class="border px-2 py-1 font-medium ">Total Amount</td>
                <td class="border px-2 py-1 font-medium ">Total Diskon</td>
                <td class="border px-2 py-1 font-medium ">Netto</td>
                <!-- <td class="border px-2 py-1 font-medium ">Status Tracking</td> -->
              </tr>
            </thead>
            <tr class="border" v-if="dataHistoryDataItem?.items.length" v-for="d,i in dataHistoryDataItem?.items"
              :key="i">
              <td class="border px-2 py-1">{{ i+1 }}</td>
              <td class="border px-2 py-1">{{ d['t_no_po.tanggal'] ?? '-' }}</td>
              <td class="border px-2 py-1">{{ d['t_no_po.no_po'] ?? '-' }}</td>
              <td class="border px-2 py-1">{{ d.quantity ? (d.quantity).toLocaleString('id'):'-' }}</td>
              <td class="border px-2 py-1">{{ d.harga ? (d.harga).toLocaleString('id'):'-' }}</td>
              <td class="border px-2 py-1">{{ d.disc1 ? (d.disc1).toLocaleString('id'):'-' }}</td>
              <td class="border px-2 py-1">{{ d.disc2 ? (d.disc2).toLocaleString('id'):'-' }}</td>
              <td class="border px-2 py-1">{{ d.disc_amt ? (d.disc_amt).toLocaleString('id'):'-' }}</td>
              <td class="border px-2 py-1">{{ d.total_amt ? (d.total_amt).toLocaleString('id'):'-' }}</td>
              <td class="border px-2 py-1">{{ d.total_disc ? (d.total_disc).toLocaleString('id'):'-' }}</td>
              <td class="border px-2 py-1">{{ d.netto ? (d.netto).toLocaleString('id'):'-' }}</td>
              <!-- <td class="border px-2 py-1">{{ d.status ?? '-' }}</td> -->
            </tr>
            <tr v-else class="text-center">
              <td colspan="11" class="py-[20px]">
                No data to show
              </td>
            </tr>
          </table>
        </div>
        <!-- Modal Footer -->
        <div class="modal-footer flex justify-end mt-2">
          <button @click="closeModalHistoryItem" class="modal-button bg-gray-200 hover:bg-gray-400 text-black font-semibold ml-2 px-2 py-1 rounded-sm">
                Tutup
              </button>
        </div>

      </div>
    </div>
  </div>
  <!-- END POP UP HISTORY HARGA -->

  <!-- POP UP HISTORY STOCK -->
  <div v-show="modalOpenHistoryStock" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
    <div class="modal-container bg-white  w-[50%] mx-auto rounded shadow-lg z-50 overflow-y-auto">
      <div class="modal-content py-4 text-left px-6">
        <!-- Modal Header -->
        <div class="modal-header flex items-center justify-between flex-wrap">
          <div class="flex items-center">
            <h3 class="text-xl font-semibold ml-2">Stok Gudang</h3>
          </div>
        </div>

        <hr class="mt-2 mb-4">
        <div class="modal-header flex items-center justify-between flex-wrap">
          <div class="flex items-center">
            <h4 class="text-md font-bold ml-2">{{dataHistoryStockItem.itemName}}</h4>
          </div>
        </div>
        <div class="modal-header flex items-center justify-between flex-wrap">
          <div class="flex items-center">
            <h4 class="text-md ml-2">Kode {{dataHistoryStockItem.itemCode}}</h4>
          </div>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
          <table class="w-[100%] my-3 border">
            <thead>
              <tr class="border">
                <td class="border px-2 py-1 font-medium ">No</td>
                <td class="border px-2 py-1 font-medium ">No Pemakaian Stok</td>
                <td class="border px-2 py-1 font-medium ">usage</td>
              </tr>
            </thead>
            <tr v-if="dataHistoryStockItem?.items.length" class="border" v-for="d,i in dataHistoryStockItem?.items"
              :key="i">
              <td class="border px-2 py-1">{{ i+1 }}</td>
              <td class="border px-2 py-1">{{ d.['t_pemakaian_stok.no_pemakaian_stok'] ?? '-' }}</td>
              <td class="border px-2 py-1">{{ d.usage ? d.usage:'-' }}</td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="3" class="py-[20px]">
                No data to show
              </td>
            </tr>
          </table>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer flex justify-end mt-2">
          <button @click="closeModalHistoryStock" class="modal-button bg-gray-200 hover:bg-gray-400 text-black font-semibold ml-2 px-2 py-1 rounded-sm">
                Tutup
              </button>
        </div>

      </div>
    </div>
  </div>
  <!-- END POP UP HISTORY STOCK -->


  <!-- END TABLE DETAIL -->
  <div class="w-full flex justify-center">
    <div class="w-md">
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">Total Amount :</label>
        <FieldNumber type="number" :bind="{ readonly: true }" class="w-full content-center !mt-3"
          :value="data.total_amount" @input="v => data.total_amount = v"
          :errorText="formErrors.total_amount ? 'failed' : ''" :hints="formErrors.total_amount" :check="false" />
      </div>
      <div v-if="data.tipe=='Asset'" class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">Total Disc Amount :</label>
        <FieldNumber type="number" :bind="{ readonly: true }" class="w-full content-center !mt-3"
          :value="data.total_disc_amount" @input="v => data.total_disc_amount = v"
          :errorText="formErrors.total_disc_amount ? 'failed' : ''" :hints="formErrors.total_disc_amount"
          :check="false" />
      </div>
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">DPP :</label>
        <FieldNumber type="number" :bind="{ readonly: true }" class="w-full content-center !mt-3" :value="data.dpp"
          @input="v => data.dpp = v" :errorText="formErrors.dpp ? 'failed' : ''" :hints="formErrors.dpp"
          :check="false" />
      </div>
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">Total PPN :</label>
        <FieldNumber type="number" :bind="{ readonly: true }" class="w-full content-center !mt-3"
          :value="data.total_ppn" @input="v => data.total_ppn = v" :errorText="formErrors.total_ppn ? 'failed' : ''"
          :hints="formErrors.total_ppn" :check="false" />
      </div>
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-3">
        <label class="!mt-4 !ml-3">Grand Total :</label>
        <FieldNumber type="number" :bind="{ readonly: true }" class="w-full content-center !mt-3"
          :value="data.grand_total" @input="v => data.grand_total = v"
          :errorText="formErrors.grand_total ? 'failed' : ''" :hints="formErrors.grand_total" :check="false" />
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