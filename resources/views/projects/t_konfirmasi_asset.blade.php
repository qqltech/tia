@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500 relative">

  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">Konfirmasi Asset</h1>
  </div>

  <div class="flex justify-between items-center px-4 py-1">

    <div class="flex items-center gap-x-2">
      <p class="font-medium text-gray-700">Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')"
          :class="filterButton === 'DRAFT' ? 'bg-green-600 text-white hover:bg-green-600' : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300 font-semibold shadow-sm">
          DRAFT
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('IN APPROVAL')"
          :class="filterButton === 'IN APPROVAL' ? 'bg-sky-600 text-white hover:bg-sky-600' : 'border border-sky-600 text-sky-600 bg-white hover:bg-sky-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300 font-semibold shadow-sm">
          IN APPROVAL
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('APPROVED')"
          :class="filterButton === 'APPROVED' ? 'bg-blue-600 text-white hover:bg-blue-600' : 'border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300 font-semibold shadow-sm">
          APPROVED
        </button>
      </div>
    </div>

    <div>
      <button @click="openCreatePopUp"
        class="border border-[#428BCA] font-semibold text-[#428BCA] bg-white hover:bg-[#428BCA] hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-3 shadow-sm flex items-center gap-2">
        <i class="fa fa-plus"></i> Create New
      </button>
    </div>

  </div>

  <hr class="mt-2 border-gray-200">

  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px] pt-2 !px-4 !pb-8">
    <template #header>
      <div class="flex gap-x-2 items-center">
        <div class="w-40">
          <FieldX type="date" typeProps="year" :value="valLand.filter_tahun" @input="v => {
              valLand.filter_tahun=v
              filterShowData()
            }" placeholder="Filter Tahunan" label="" :check="false" />
        </div>
      </div>
    </template>
  </TableApi>


  <div v-show="modalOpenCreate" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="modal-overlay fixed inset-0 bg-black opacity-50" @click="closeCreatePopUp"></div>
    <div
      class="modal-container bg-white w-[90%] md:w-[75%] mx-auto rounded-lg shadow-xl z-50 overflow-y-auto transform transition-all">

      <div class="modal-content py-4 text-left px-6">
        <div class="modal-header flex items-center justify-between mb-6 border-b pb-3">
          <h3 class="text-xl font-bold text-gray-800">Pilih Tipe Asset Confirmation</h3>
          <button @click="closeCreatePopUp" class="text-gray-400 hover:text-red-500 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">


          <RouterLink :to="`${$route.path}/create?isType=chasis&timestamp=${Date.now()}`"
            class="group bg-white border border-blue-200 hover:border-blue-500 hover:shadow-lg rounded-lg overflow-hidden flex flex-col h-40 transition-all duration-300">
            <div
              class="flex-grow flex items-center justify-center bg-blue-50 group-hover:bg-blue-100 transition-colors">
              <svg class="w-14 h-14 text-blue-500" fill="currentColor" viewBox="0 0 512 512">
                <path
                  d="M32 32l448 0c17.7 0 32 14.3 32 32l0 32c0 17.7-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96L0 64C0 46.3 14.3 32 32 32zm0 128l448 0 0 256c0 35.3-28.7 64-64 64L96 480c-35.3 0-64-28.7-64-64l0-256zm128 80c0 8.8 7.2 16 16 16l160 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-160 0c-8.8 0-16 7.2-16 16z" />
              </svg>
            </div>
            <div
              class="bg-blue-500 group-hover:bg-blue-600 text-white text-center py-2 font-semibold text-sm transition-colors">
              Chasis
            </div>
          </RouterLink>

          <RouterLink :to="`${$route.path}/create?isType=kendaraan&timestamp=${Date.now()}`"
            class="group bg-white border border-blue-200 hover:border-blue-500 hover:shadow-lg rounded-lg overflow-hidden flex flex-col h-40 transition-all duration-300">
            <div
              class="flex-grow flex items-center justify-center bg-blue-50 group-hover:bg-blue-100 transition-colors">
              <svg class="w-14 h-14 text-blue-500" fill="currentColor" viewBox="0 0 640 512">
                <path
                  d="M48 0C21.5 0 0 21.5 0 48L0 368c0 26.5 21.5 48 48 48l16 0c0 53 43 96 96 96s96-43 96-96l128 0c0 53 43 96 96 96s96-43 96-96l32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-64 0-32 0-18.7c0-17-6.7-33.3-18.7-45.3L512 114.7c-12-12-28.3-18.7-45.3-18.7L416 96l0-48c0-26.5-21.5-48-48-48L48 0zM416 160l50.7 0L544 237.3l0 18.7-128 0 0-96zM112 416a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm368-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
              </svg>
            </div>
            <div
              class="bg-blue-500 group-hover:bg-blue-600 text-white text-center py-2 font-semibold text-sm transition-colors">
              Kendaraan
            </div>
          </RouterLink>

          <RouterLink :to="`${$route.path}/create?isType=inventaris&timestamp=${Date.now()}`"
            class="group bg-white border border-blue-200 hover:border-blue-500 hover:shadow-lg rounded-lg overflow-hidden flex flex-col h-40 transition-all duration-300">
            <div
              class="flex-grow flex items-center justify-center bg-blue-50 group-hover:bg-blue-100 transition-colors">
              <svg class="w-14 h-14 text-blue-500" fill="currentColor" viewBox="0 0 512 512">
                <path
                  d="M32 32l448 0c17.7 0 32 14.3 32 32l0 32c0 17.7-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96L0 64C0 46.3 14.3 32 32 32zm0 128l448 0 0 256c0 35.3-28.7 64-64 64L96 480c-35.3 0-64-28.7-64-64l0-256zm128 80c0 8.8 7.2 16 16 16l160 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-160 0c-8.8 0-16 7.2-16 16z" />
              </svg>
            </div>
            <div
              class="bg-blue-500 group-hover:bg-blue-600 text-white text-center py-2 font-semibold text-sm transition-colors">
              Inventaris Kantor
            </div>
          </RouterLink>

          <RouterLink :to="`${$route.path}/create?isType=mesin&timestamp=${Date.now()}`"
            class="group bg-white border border-blue-200 hover:border-blue-500 hover:shadow-lg rounded-lg overflow-hidden flex flex-col h-40 transition-all duration-300">
            <div
              class="flex-grow flex items-center justify-center bg-blue-50 group-hover:bg-blue-100 transition-colors">
              <svg class="w-14 h-14 text-blue-500" fill="currentColor" viewBox="0 0 640 512">
                <path
                  d="M308.5 135.3c7.1-6.3 9.9-16.2 6.2-25c-2.3-5.3-4.8-10.5-7.6-15.5L304 89.4c-3-5-6.3-9.9-9.8-14.6c-5.7-7.6-15.7-10.1-24.7-7.1l-28.2 9.3c-10.7-8.8-23-16-36.2-20.9L199 27.1c-1.9-9.3-9.1-16.7-18.5-17.8C173.9 8.4 167.2 8 160.4 8l-.7 0c-6.8 0-13.5 .4-20.1 1.2c-9.4 1.1-16.6 8.6-18.5 17.8L115 56.1c-13.3 5-25.5 12.1-36.2 20.9L50.5 67.8c-9-3-19-.5-24.7 7.1c-3.5 4.7-6.8 9.6-9.9 14.6l-3 5.3c-2.8 5-5.3 10.2-7.6 15.6c-3.7 8.7-.9 18.6 6.2 25l22.2 19.8C32.6 161.9 32 168.9 32 176s.6 14.1 1.7 20.9L11.5 216.7c-7.1 6.3-9.9 16.2-6.2 25c2.3 5.3 4.8 10.5 7.6 15.6l3 5.2c3 5.1 6.3 9.9 9.9 14.6c5.7 7.6 15.7 10.1 24.7 7.1l28.2-9.3c10.7 8.8 23 16 36.2 20.9l6.1 29.1c1.9 9.3 9.1 16.7 18.5 17.8c6.7 .8 13.5 1.2 20.4 1.2s13.7-.4 20.4-1.2c9.4-1.1 16.6-8.6 18.5-17.8l6.1-29.1c13.3-5 25.5-12.1 36.2-20.9l28.2 9.3c9 3 19 .5 24.7-7.1c3.5-4.7 6.8-9.5 9.8-14.6l3.1-5.4c2.8-5 5.3-10.2 7.6-15.5c3.7-8.7 .9-18.6-6.2-25l-22.2-19.8c1.1-6.8 1.7-13.8 1.7-20.9s-.6-14.1-1.7-20.9l22.2-19.8zM112 176a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zM504.7 500.5c6.3 7.1 16.2 9.9 25 6.2c5.3-2.3 10.5-4.8 15.5-7.6l5.4-3.1c5-3 9.9-6.3 14.6-9.8c7.6-5.7 10.1-15.7 7.1-24.7l-9.3-28.2c8.8-10.7 16-23 20.9-36.2l29.1-6.1c9.3-1.9 16.7-9.1 17.8-18.5c.8-6.7 1.2-13.5 1.2-20.4s-.4-13.7-1.2-20.4c-1.1-9.4-8.6-16.6-17.8-18.5L583.9 307c-5-13.3-12.1-25.5-20.9-36.2l9.3-28.2c3-9 .5-19-7.1-24.7c-4.7-3.5-9.6-6.8-14.6-9.9l-5.3-3c-5-2.8-10.2-5.3-15.6-7.6c-8.7-3.7-18.6-.9-25 6.2l-19.8 22.2c-6.8-1.1-13.8-1.7-20.9-1.7s-14.1 .6-20.9 1.7l-19.8-22.2c-6.3-7.1-16.2-9.9-25-6.2c-5.3 2.3-10.5 4.8-15.6 7.6l-5.2 3c-5.1 3-9.9 6.3-14.6 9.9c-7.6 5.7-10.1 15.7-7.1 24.7l9.3 28.2c-8.8 10.7-16 23-20.9 36.2L315.1 313c-9.3 1.9-16.7 9.1-17.8 18.5c-.8 6.7-1.2 13.5-1.2 20.4s.4 13.7 1.2 20.4c1.1 9.4 8.6 16.6 17.8 18.5l29.1 6.1c5 13.3 12.1 25.5 20.9 36.2l-9.3 28.2c-3 9-.5 19 7.1 24.7c4.7 3.5 9.5 6.8 14.6 9.8l5.4 3.1c5 2.8 10.2 5.3 15.5 7.6c8.7 3.7 18.6 .9 25-6.2l19.8-22.2c6.8 1.1 13.8 1.7 20.9 1.7s14.1-.6 20.9-1.7l19.8 22.2zM464 304a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
              </svg>
            </div>
            <div
              class="bg-blue-500 group-hover:bg-blue-600 text-white text-center py-2 font-semibold text-sm transition-colors">
              Asset Mesin
            </div>
          </RouterLink>

          <RouterLink :to="`${$route.path}/create?isType=lain&timestamp=${Date.now()}`"
            class="group bg-white border border-blue-200 hover:border-blue-500 hover:shadow-lg rounded-lg overflow-hidden flex flex-col h-40 transition-all duration-300">
            <div
              class="flex-grow flex items-center justify-center bg-blue-50 group-hover:bg-blue-100 transition-colors">
              <icon fa="landmark" class="text-6xl text-blue-500" />
            </div>
            <div
              class="bg-blue-500 group-hover:bg-blue-600 text-white text-center py-2 font-semibold text-sm transition-colors">
              Lain - lain
            </div>
          </RouterLink>

        </div>

        <div class="modal-footer flex justify-end mt-4">
          <button @click="closeCreatePopUp" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-1 rounded shadow transition-colors">
            Tutup
          </button>
        </div>

      </div>
    </div>
  </div>

</div>

@else

<!-- CONTENT -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="rounded-t-md py-2 px-4 mt-5">
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-7 justify-start">
        <div class="flex items-center gap-x-2">
          <button class="py-1 px-2 rounded transition-all text-blue-900 bg-white border border-blue-900 duration-300 hover:text-white hover:bg-blue-600" @click="onBack">
                <icon fa="arrow-left" size="sm"/>            
              </button>
          <div>
            <h1 class="text-20px font-bold">Asset Confirmation
              <input v-model="values.tipe_asset" class="text-20px font-bold bg-transparent border-none outline-none" :disabled="true" ></input>
            </h1>
            <p v-if="!is_approval" class="text-red-500">{{actionText==='Edit'?'Edit':(actionText==='Tambah'?'New':'')}}
              Data</p>
            <p v-else class="text-red-500">Notifikasi Approval Asset Confirmation
              <input v-model="values.tipe_asset" class="text-20px font-bold bg-transparent border-none outline-none" :disabled="true" ></input>
            </p>
          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- HEADER END -->

  <div class="p-4 grid gap-x-10 font-semibold <md:grid-cols-1 col-span-2 grid-cols-2">
    <!-- No. Draft -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">No. Draft</label>
      </div>
      <FieldX class="col-span-4 !mt-3 w-full" :bind="{disabled:true, readonly: true }" :value="values.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>values.no_draft=v" :hints="formErrors.no_draft"
        :check="false" placeholder="Autofield By System" label="" />
    </div>

    <!-- Status -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Status</label>
      </div>
      <FieldSelect class="col-span-9 w-full !mt-3" :bind="{ disabled: true, clearable:false }" :value="values.status_id"
        @input="v=>{
            if(v){
              values.status_id=v
            }else{
              values.status_id=null
            }
          }" :errorText="formErrors.status_id?'failed':''" :hints="formErrors.status_id" valueField="id"
        displayField="deskripsi" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              where: `this.group='STATUS ASSET CONFIRMATION' and this.is_active=true`
            }
        }" :check="false" />
    </div>

    <!-- No Asset Confirmation -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">No. Asset Confirmation</label>
      </div>
      <FieldX class="col-span-4 !mt-3" :bind="{ disabled:true, readonly:true }" :value="values.no_asset_confirmation"
        :errorText="formErrors.no_asset_confirmation?'failed':''" @input="v=>values.no_asset_confirmation=v"
        :hints="formErrors.no_asset_confirmation" placeholder="Autofield By System" label="" :check="false" />
    </div>

    <!-- Tanggal -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Tanggal</label>
      </div>
      <FieldX class="col-span-4 w-full !mt-3" :bind="{ readonly: true , disabled:true }" :value="values.tanggal"
        :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v" :hints="formErrors.tanggal"
        :check="false" type="date" />
    </div>

    <!-- No. LPB Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">No. LPB<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="w-full col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.t_ri_id"
        @input="(v)=>values.t_ri_id=v" :errorText="formErrors.t_ri_id?'failed':''" :hints="formErrors.t_ri_id"
        @update:valueFull="(dt) => {
        if (!dt) {
          values.tanggal_asset = null;

          } else {
            values.tanggal_asset = dt['tanggal'];

            }
            $log(dt);
            }" valueField="id" displayField="no_lpb" :api="{
            url: `${store.server.url_backend}/operation/t_lpb`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              //where: `this.tipe = 'Asset'`,
              searchfield: `this.id, this.no_lpb, this.tanggal, m_supplier.nama`
              },
              onsuccess(response) {
                response.page = response.current_page;
                response.hasNext = response.has_next;
                return response;
                }
                }" placeholder="Pilih No. LPB" label="" fa-icon="" :check="false" :columns="[{
                  headerName: 'No',
                  valueGetter:(p)=>p.node.rowIndex + 1,
                  width: 60,
                  sortable: false, resizable: false, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
           },
           {
              flex: 1,
              field: 'no_lpb',
              headerName:  'No. LPB Item',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'tanggal_lpb',
              headerName:  'Tanggal LPB Item',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
              flex: 1,
              field: 'm_supplier.nama',
              headerName:  'Nama Supplier',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
        ]" />
    </div>

    <!--Barang Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Barang<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="w-full col-span-9 !mt-3" :bind="{ readonly: !actionText }"
        :value="values.t_ri_detail_id " @input="(v)=>values.t_ri_detail_id =v"
        :errorText="formErrors.t_ri_detail_id ?'failed':''" :hints="formErrors.t_ri_detail_id " valueField="id"
        displayField="m_item.nama_item" @update:valueFull="(dt)=>{
          //values.nama_asset = parseFloat(dt['harga_per_barang'])
          //values.id_lpb_barang = dt.id
          values.harga_perolehan = parseFloat(dt.harga_per_barang)
          values.nilai_buku = parseFloat(dt.harga_per_barang)
        $log(dt);
          }" :api="{
          url: `${store.server.url_backend}/operation/t_lpb_d`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            id_ri : `${values.t_ri_id}`,
            //id_lpb_barang : `${values.id_lpb_barang}`
            //where: `t_lpb_id = ${values.t_ri_id}`
          },
        onsuccess(response) {
        response.page = response.current_page;
        response.hasNext = response.has_next;
        return response;
      }
        }" placeholder="Cari atau pilih barang" label="" fa-icon="" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'm_item.kode',
          headerName:  'Kode Barang',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'm_item.nama_item',
          headerName:  'Nama Barang',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        //{
        //  flex: 1,
        //  field: 'qty_ri',
        //  headerName:  'Jumlah RI',
        //  sortable: false, resizable: true, filter: 'ColFilter',
        //  cellClass: ['border-r', '!border-gray-200', 'justify-center']
        //},
        ]" />
    </div>

    <!-- Kategori Kendaraan  -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Kategori</label>
      </div>
      <FieldX :bind="{ readonly: true, disable:true }" class="col-span-9 w-full mt-3" :value="values.tipe_asset"
        :errorText="formErrors.tipe_asset?'failed':''" @input="v=>values.tipe_asset=v" :hints="formErrors.tipe_asset"
        placeholder="" label="" fa-icon="" :check="false" />
    </div>

    <!-- No. LPB Inventaris -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'inventaris'">
      <div class="col-span-3">
        <label class="text-sm">No. LPB<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="w-full col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.t_ri_id"
        @input="(v)=>values.t_ri_id=v" :errorText="formErrors.t_ri_id?'failed':''" :hints="formErrors.t_ri_id"
        @update:valueFull="(dt) => {
        if (!dt) {
          values.tanggal_asset = null;

          } else {
            values.tanggal_asset = dt['tanggal'];

            }
            $log(dt);
            }" valueField="id" displayField="no_lpb" :api="{
            url: `${store.server.url_backend}/operation/t_lpb`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              //where: `this.tipe = 'Asset'`,
              searchfield: `this.id, this.no_lpb, this.tanggal, m_supplier.nama`
              },
              onsuccess(response) {
                response.page = response.current_page;
                response.hasNext = response.has_next;
                return response;
                }
                }" placeholder="Pilih No. LPB" label="" fa-icon="" :check="false" :columns="[{
                  headerName: 'No',
                  valueGetter:(p)=>p.node.rowIndex + 1,
                  width: 60,
                  sortable: false, resizable: false, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
           },
           {
            flex: 1,
            field: 'no_lpb',
            headerName:  'No. LPB Item',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'tanggal_lpb',
              headerName:  'Tanggal LPB Item',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
              flex: 1,
              field: 'm_supplier.nama',
              headerName:  'Nama Supplier',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
        ]" />
    </div>

    <!--Barang Inventaris -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'inventaris'">
      <div class="col-span-3">
        <label class="text-sm">Barang<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="w-full col-span-9 !mt-3" :bind="{ readonly: !actionText }"
        :value="values.t_ri_detail_id " @input="(v)=>values.t_ri_detail_id =v"
        :errorText="formErrors.t_ri_detail_id ?'failed':''" :hints="formErrors.t_ri_detail_id " valueField="id"
        displayField="m_item.nama_item" @update:valueFull="(dt)=>{
          //values.nama_asset = parseFloat(dt['harga_per_barang'])
          //values.id_lpb_barang = dt.id
          values.harga_perolehan = parseFloat(dt.harga_per_barang)
          values.nilai_buku = parseFloat(dt.harga_per_barang)
        $log(dt);
          }" :api="{
          url: `${store.server.url_backend}/operation/t_lpb_d`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            id_ri : `${values.t_ri_id}`,
            //id_lpb_barang : `${values.id_lpb_barang}`
            //where: `t_lpb_id = ${values.t_ri_id}`
          },
        onsuccess(response) {
        response.page = response.current_page;
        response.hasNext = response.has_next;
        return response;
      }
        }" placeholder="Cari atau pilih barang" label="" fa-icon="" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'm_item.kode',
          headerName:  'Kode Barang',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'm_item.nama_item',
          headerName:  'Nama Barang',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        ]" />
    </div>

    <!-- Kategori Inventaris  -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'inventaris'">
      <div class="col-span-3">
        <label class="text-sm">Kategori</label>
      </div>
      <FieldX :bind="{ readonly: true, disable:true }" class="col-span-9 w-full mt-3" :value="values.tipe_asset"
        :errorText="formErrors.tipe_asset?'failed':''" @input="v=>values.tipe_asset=v" :hints="formErrors.tipe_asset"
        placeholder="" label="" fa-icon="" :check="false" />
    </div>
    <div v-if="route.query.isType == 'inventaris'"></div>


    <!-- No. LPB Mesin -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'mesin'">
      <div class="col-span-3">
        <label class="text-sm">No. LPB<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="w-full col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.t_ri_id"
        @input="(v)=>values.t_ri_id=v" :errorText="formErrors.t_ri_id?'failed':''" :hints="formErrors.t_ri_id"
        @update:valueFull="(dt) => {
        if (!dt) {
          values.tanggal_asset = null;

          } else {
            values.tanggal_asset = dt['tanggal'];

            }
            $log(dt);
            }" valueField="id" displayField="no_lpb" :api="{
            url: `${store.server.url_backend}/operation/t_lpb`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              //where: `this.tipe = 'Asset'`,
              searchfield: `this.id, this.no_lpb, this.tanggal, m_supplier.nama`
              },
              onsuccess(response) {
                response.page = response.current_page;
                response.hasNext = response.has_next;
                return response;
                }
                }" placeholder="Pilih No. LPB" label="" fa-icon="" :check="false" :columns="[{
                  headerName: 'No',
                  valueGetter:(p)=>p.node.rowIndex + 1,
                  width: 60,
                  sortable: false, resizable: false, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
           },
           {
            flex: 1,
            field: 'no_lpb',
            headerName:  'No. LPB Item',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'tanggal_lpb',
              headerName:  'Tanggal LPB Item',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
              flex: 1,
              field: 'm_supplier.nama',
              headerName:  'Nama Supplier',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
        ]" />
    </div>

    <!--Barang Mesin -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'mesin'">
      <div class="col-span-3">
        <label class="text-sm">Barang<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="w-full col-span-9 !mt-3" :bind="{ readonly: !actionText }"
        :value="values.t_ri_detail_id " @input="(v)=>values.t_ri_detail_id =v"
        :errorText="formErrors.t_ri_detail_id ?'failed':''" :hints="formErrors.t_ri_detail_id " valueField="id"
        displayField="m_item.nama_item" @update:valueFull="(dt)=>{
          //values.nama_asset = parseFloat(dt['harga_per_barang'])
          //values.id_lpb_barang = dt.id
          values.harga_perolehan = parseFloat(dt.harga_per_barang)
          values.nilai_buku = parseFloat(dt.harga_per_barang)
        $log(dt);
          }" :api="{
          url: `${store.server.url_backend}/operation/t_lpb_d`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            //scopes : `GetHargaBarang`,
            id_ri : `${values.t_ri_id}`,
            //id_lpb_barang : `${values.id_lpb_barang}`
            //where: `t_lpb_id = ${values.t_ri_id}`
          },
        onsuccess(response) {
        response.page = response.current_page;
        response.hasNext = response.has_next;
        return response;
      }
        }" placeholder="Cari atau pilih barang" label="" fa-icon="" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'm_item.kode',
          headerName:  'Kode Barang',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'm_item.nama_item',
          headerName:  'Nama Barang',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        ]" />
    </div>

    <!-- Kategori Mesin  -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'mesin'">
      <div class="col-span-3">
        <label class="text-sm">Kategori</label>
      </div>
      <FieldX :bind="{ readonly: true, disable:true }" class="col-span-9 w-full mt-3" :value="values.tipe_asset"
        :errorText="formErrors.tipe_asset?'failed':''" @input="v=>values.tipe_asset=v" :hints="formErrors.tipe_asset"
        placeholder="" label="" fa-icon="" :check="false" />
    </div>
    <div v-if="route.query.isType == 'mesin'"></div>


    <!-- No. LPB Chasis -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'chasis'">
      <div class="col-span-3">
        <label class="text-sm">No. LPB<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="w-full col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.t_lpb_id"
        @input="(v)=>values.t_lpb_id=v" :errorText="formErrors.t_lpb_id?'failed':''" :hints="formErrors.t_lpb_id"
        @update:valueFull="(dt) => {
        if (!dt) {
          values.tanggal_asset = null;

          } else {
            values.tanggal_asset = dt['tanggal'];

            }
            $log(dt);
            }" valueField="id" displayField="no_lpb" :api="{
            url: `${store.server.url_backend}/operation/t_lpb`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              //where: `this.tipe = 'Asset'`,
              searchfield: `this.id, this.no_lpb, this.tanggal, m_supplier.nama`
              },
              onsuccess(response) {
                response.page = response.current_page;
                response.hasNext = response.has_next;
                return response;
                }
                }" placeholder="Pilih No. LPB" label="" fa-icon="" :check="false" :columns="[{
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
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'tanggal_lpb',
              headerName:  'Tanggal LPB',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
                flex: 1,
    field: 'm_supplier.nama',
    headerName:  'Nama Supplier',
    sortable: false, resizable: true, filter: 'ColFilter',
    cellClass: ['border-r', '!border-gray-200', 'justify-center']
    },
        ]" />
    </div>

    <!--Barang Chasis -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'chasis'">
      <div class="col-span-3">
        <label class="text-sm">Barang<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="w-full col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.m_item_id"
        @input="(v)=>values.m_item_id=v" :errorText="formErrors.m_item_id?'failed':''" :hints="formErrors.m_item_id"
        valueField="m_item_id" displayField="m_item.nama_item" @update:valueFull="(dt)=>{

         // Set Harga Perolehan Otomatis dari PO
          values.harga_perolehan = parseFloat(dt.harga_per_barang)
          values.nilai_buku = parseFloat(dt.harga_per_barang)
          
          $log(dt);
        }" :api="{
          url: `${store.server.url_backend}/operation/t_lpb_d`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            join: true,
            scopes: 'GetHargaBarang',
            t_lpb_id : values.t_lpb_id 
          },
          onsuccess(response) {
            response.page = response.current_page;
            response.hasNext = response.has_next;
            return response;
          }
        }" placeholder="Cari atau pilih barang dari LPB" label="" fa-icon="" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'm_item.kode',
          headerName:  'Kode Barang',
          sortable: true, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'm_item.nama_item',
          headerName:  'Nama Barang',
          sortable: true, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        }
        ]" />
    </div>

    <!-- Kategori Chasis  -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'chasis'">
      <div class="col-span-3">
        <label class="text-sm">Kategori</label>
      </div>
      <FieldX :bind="{ readonly: true, disable:true }" class="col-span-9 w-full mt-3" :value="values.tipe_asset"
        :errorText="formErrors.tipe_asset?'failed':''" @input="v=>values.tipe_asset=v" :hints="formErrors.tipe_asset"
        placeholder="" label="" fa-icon="" :check="false" />
    </div>
    <div v-if="route.query.isType == 'chasis'"></div>

    <!-- No. LPB lain -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'lain'">
      <div class="col-span-3">
        <label class="text-sm">No. LPB<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="w-full col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.t_lpb_id"
        @input="(v)=>values.t_lpb_id=v" :errorText="formErrors.t_lpb_id?'failed':''" :hints="formErrors.t_lpb_id"
        @update:valueFull="(dt)=>{
          values.barang_id = dt['id'];
          //values.
          values.tanggal_asset = dt['tanggal']
        $log(dt);
          }" valueField="id" displayField="no_lpb" :api="{
            url: `${store.server.url_backend}/operation/t_lpb`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              //where: `this.tipe = 'Asset'`,
              searchfield: `this.id, this.no_lpb, this.tanggal, m_supplier.nama`
              },
              onsuccess(response) {
                response.page = response.current_page;
                response.hasNext = response.has_next;
                return response;
                }
                }" placeholder="Pilih No. LPB" label="" fa-icon="" :check="false" :columns="[{
                  headerName: 'No',
                  valueGetter:(p)=>p.node.rowIndex + 1,
                  width: 60,
                  sortable: false, resizable: false, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
           },
           {
            flex: 1,
            field: 'no_lpb',
            headerName:  'No. LPB Item',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'tanggal_lpb',
              headerName:  'Tanggal LPB Item',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
              flex: 1,
              field: 'm_supplier.nama',
              headerName:  'Nama Supplier',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
        ]" />
    </div>

    <!--Barang lain -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'lain'">
      <div class="col-span-3">
        <label class="text-sm">Barang<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="w-full col-span-9 !mt-3" :bind="{ readonly: !actionText }"
        :value="values.t_ri_detail_id " @input="(v)=>values.t_ri_detail_id =v"
        :errorText="formErrors.t_ri_detail_id ?'failed':''" :hints="formErrors.t_ri_detail_id " valueField="id"
        displayField="m_item.nama_item" @update:valueFull="(dt)=>{
          //values.nama_asset = parseFloat(dt['harga_per_barang'])
          //values.id_lpb_barang = dt.id
          values.harga_perolehan = parseFloat(dt.harga_per_barang)
          values.nilai_buku = parseFloat(dt.harga_per_barang)
        $log(dt);
          }" :api="{
          url: `${store.server.url_backend}/operation/t_lpb_d`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            scopes : `GetHargaBarang`,
            id_ri : `${values.t_ri_id}`,
            //id_lpb_barang : `${values.id_lpb_barang}`
            //where: `t_lpb_id = ${values.t_ri_id}`
          },
        onsuccess(response) {
        response.page = response.current_page;
        response.hasNext = response.has_next;
        return response;
      }
        }" placeholder="Cari atau pilih barang" label="" fa-icon="" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'm_item.kode',
          headerName:  'Kode Barang',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'm_item.nama_item',
          headerName:  'Nama Barang',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        ]" />
    </div>

    <!-- Kategori lain  -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'lain'">
      <div class="col-span-3">
        <label class="text-sm">Kategori</label>
      </div>
      <FieldX :bind="{ readonly: true, disable:true }" class="col-span-9 w-full mt-3" :value="values.tipe_asset"
        :errorText="formErrors.tipe_asset?'failed':''" @input="v=>values.tipe_asset=v" :hints="formErrors.tipe_asset"
        placeholder="" label="" fa-icon="" :check="false" />
    </div>

    <!-- Kode Asset -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Kode Asset</label>
      </div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3 col-span-4" :value="values.kode_asset"
        :errorText="formErrors.kode_asset?'failed':''" @input="v=>values.kode_asset=v" :hints="formErrors.kode_asset"
        placeholder="Autofield By System" label="" fa-icon="" :check="false" />
    </div>

    <!-- Tanggal Asset -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Tanggal Asset</label>
      </div>
      <FieldX :bind="{ readonly: !actionText, disabled:!actionText }" class="w-full !mt-3 col-span-4" type="date"
        :value="values.tanggal_asset" :errorText="formErrors.tanggal_asset?'failed':''"
        @input="v=>values.tanggal_asset=v" :hints="formErrors.tanggal_asset" placeholder="" label="" fa-icon=""
        :check="false" />
    </div>

    <!-- Tanggal Pakai -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Tanggal Pakai</label>
      </div>
      <FieldX type="date" :bind="{ readonly: !actionText }" class="w-full col-span-4 !mt-3"
        :value="values.tanggal_pakai" :errorText="formErrors.tanggal_pakai?'failed':''"
        @input="v=>values.tanggal_pakai=v" :hints="formErrors.tanggal_pakai" placeholder="" label="" fa-icon=""
        :check="false" />
    </div>

    <!-- PIC -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">PIC<span style="color: red">*</span></label>
      </div>
      <FieldPopup class="col-span-9 !mt-3" :bind="{ disabled: !actionText, readonly: !actionText }" :value="values.pic"
        @input="(v)=>values.pic=v" :errorText="formErrors.pic?'failed':''" :hints="formErrors.pic" valueField="id"
        displayField="nama" :api="{
          url:  `${store.server.url_backend}/operation/m_kary`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            searchfield: `this.nama, this.nip`
          }
        }" placeholder="Pilih PIC" label="" fa-icon="" :check="false" :columns="[{
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
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'nip',
          headerName:  'NIP',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        ]" />
    </div>

    <!-- Nama Asset -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Nama Asset</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ disabled: !actionText, readonly: !actionText }"
        :value="values.nama_asset" :errorText="formErrors.nama_asset?'failed':''" @input="v=>values.nama_asset=v"
        :hints="formErrors.nama_asset" placeholder="Masukkan Nama Asset" label="" fa-icon="" :check="false" />
    </div>

    <!-- Jenis Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Jenis Kendaraan</label>
      </div>
      <FieldSelect class="col-span-9 !mt-3" :bind="{ disabled: !actionText, clearable:false }"
        :value="values.jenis_kendaraan_id" @input="v=>values.jenis_kendaraan_id=v"
        :errorText="formErrors.jenis_kendaraan_id?'failed':''" :hints="formErrors.jenis_kendaraan_id" valueField="id"
        displayField="deskripsi" :api="{
          url: `${store.server.url_backend}/operation/m_general`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            transform:false,
            join:false,
            where : `this.group = 'JENIS KENDARAAN'`
          }
      }" placeholder="Pilih Jenis Kendaraan" label="" fa-icon="fa-caret-down" :check="true" />
    </div>

    <!-- Nomor Mesin Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Nomor Mesin</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.no_mesin"
        :errorText="formErrors.no_mesin?'failed':''" @input="v=>values.no_mesin=v" :hints="formErrors.no_mesin"
        placeholder="Masukkan Nomor Mesin" label="" :check="false" />
    </div>

    <!-- Nomor Urut Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Nomor Urut Kendaran</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.no_urut_kendaraan"
        :errorText="formErrors.no_urut_kendaraan?'failed':''" @input="v=>values.no_urut_kendaraan=v"
        :hints="formErrors.no_urut_kendaraan" placeholder="Masukkan Nomor Urut Kendaran" label="" :check="false" />
    </div>

    <!-- Nomor Rangka Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Nomor Rangka</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.no_rangka"
        :errorText="formErrors.no_rangka?'failed':''" @input="v=>values.no_rangka=v" :hints="formErrors.no_rangka"
        :check="false" placeholder="Masukkan Nomor Rangka" label="" />
    </div>

    <!-- Nomor Polisi Kendaraan -->
    <div class="grid grid-cols-12 items-center gap-y-2" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Nopol</label>
      </div>
      <FieldX class="col-span-4 !mt-3 pr-3" :bind="{ readonly: !actionText && !actionEditBerkas}" :value="values.nopol"
        :errorText="formErrors.nopol ? 'failed' : ''" @input="v => values.nopol = v" :hints="formErrors.nopol"
        placeholder="Masukkan Nopol" label="" :check="false" />

      <!-- Tombol History Nopol -->
      <div class="col-span-3 !mt-0.5">
        <button
      class="mt-2 bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md flex items-center"
      @click="openHistoryNopol(values.id)">
      <icon fa="" class="mr-2"/> Riwayat Nopol </button>
      </div>
    </div>

    <!-- start pop up -->
    <div v-show="modalOpenHistoryNopol" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
      <div class="modal-container bg-white  w-[70%] mx-auto rounded shadow-lg z-50 overflow-y-auto">
        <div class="modal-content py-4 text-left px-6">
          <!-- Modal Header -->
          <div class="modal-header flex items-center justify-between flex-wrap">
            <div class="flex items-center">
              <h3 class="text-xl font-semibold ml-2">History Nopol</h3>
            </div>
          </div>

          <hr class="mt-2 mb-4">

          <!-- Modal Body -->
          <div v-if="dataHistoryNopol?.items.length" class="modal-body">
            <table class="w-[100%] my-3 border">
              <thead>
                <tr class="border">
                  <td class="border px-2 py-1 font-medium ">No</td>
                  <td class="border px-2 py-1 font-medium ">Tahun Produksi</td>
                </tr>
              </thead>
              <tr class="border" v-for="d,i in dataHistoryNopol?.items" :key="i">
                {{$log (d)}}
                <td class="border px-2 py-1">{{ i+1 }}</td>
                <td class="border px-2 py-1">{{ d['tahun_produksi'] ?? '-' }}</td>
                <td class="border px-2 py-1">{{ d['nopol'] ?? '-' }}</td>

              </tr>
            </table>
          </div>
          <!-- Modal Footer -->
          <div class="modal-footer flex justify-end mt-2">
            <button @click="closeModalHistoryNopol" class="modal-button bg-gray-200 hover:bg-gray-400 text-black font-semibold ml-2 px-2 py-1 rounded-sm">
                Tutup
              </button>
          </div>

        </div>
      </div>
    </div>

    <!-- Nomor BPKB Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Nomor BPKB</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.no_bpkb"
        :errorText="formErrors.no_bpkb?'failed':''" @input="v=>values.no_bpkb=v" :hints="formErrors.no_bpkb"
        placeholder="Masukkan BPKB" label="" :check="false" />
    </div>

    <!-- Tahun Produksi Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Tahun Produksi</label>
      </div>
      <FieldX type="Number" class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.tahun_produksi"
        :errorText="formErrors.tahun_produksi?'failed':''" @input="v=>values.tahun_produksi=v"
        :hints="formErrors.tahun_produksi" placeholder="Masukkan Tahun Produksi" label="" :check="false" />
    </div>

    <!-- Merk Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Merk</label>
      </div>
      <FieldSelect class="col-span-9 !mt-3" :bind="{ disabled: !actionText, clearable:false }" :value="values.merk_id"
        @input="v=>values.merk_id=v" :errorText="formErrors.merk_id?'failed':''" :hints="formErrors.merk_id"
        valueField="id" displayField="deskripsi" :api="{
          url: `${store.server.url_backend}/operation/m_general`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            transform:false,
            join:false,
            where : `this.group = 'MERK KENDARAAN'`
          }
      }" placeholder="Pilih Merk Kendaraan" label="" fa-icon="fa-caret-down" :check="true" />
    </div>

    <!-- Jumlah Roda Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Jumlah Roda</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.jumlah_roda"
        :errorText="formErrors.jumlah_roda?'failed':''" @input="v=>values.jumlah_roda=v" :hints="formErrors.jumlah_roda"
        :check="false" placeholder="Masukkan Jumlah Roda" label="" />
    </div>

    <!-- Bahan Bakar Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Bahan Bakar</label>
      </div>
      <FieldSelect class="col-span-9 !mt-3" :bind="{ disabled: !actionText, clearable:false }"
        :value="values.bahan_bakar_id" @input="v=>values.bahan_bakar_id=v"
        :errorText="formErrors.bahan_bakar_id?'failed':''" :hints="formErrors.bahan_bakar_id" valueField="id"
        displayField="deskripsi" :api="{
          url: `${store.server.url_backend}/operation/m_general`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            transform:false,
            join:false,
            where : `this.group = 'BAHAN BAKAR'`
          }
      }" placeholder="Pilih Bahan Bakar" label="" fa-icon="fa-caret-down" :check="true" />
    </div>

    <!-- Jumlah Cylinder Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Jumlah Cylinder</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.jumlah_cylinder"
        :errorText="formErrors.jumlah_cylinder?'failed':''" @input="v=>values.jumlah_cylinder=v"
        :hints="formErrors.jumlah_cylinder" placeholder="Masukkan Jumlah Cylinder" label="" :check="false" />
    </div>

    <!-- Warna Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Warna</label>
      </div>
      <FieldSelect class="col-span-9 !mt-3" :bind="{ disabled: !actionText, clearable:false }" :value="values.warna_id"
        @input="v=>values.warna_id=v" :errorText="formErrors.warna_id?'failed':''" :hints="formErrors.warna_id"
        valueField="id" displayField="deskripsi" :api="{
          url: `${store.server.url_backend}/operation/m_general`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            transform:false,
            join:false,
            where : `this.group = 'WARNA KENDARAAN'`
          }
      }" placeholder="Pilih Warna Kendaraan" label="" fa-icon="fa-caret-down" :check="true" />
    </div>

    <!-- No. Faktur Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">No. Faktur</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.no_faktur"
        :errorText="formErrors.no_faktur?'failed':''" @input="v=>values.no_faktur=v" :hints="formErrors.no_faktur"
        :check="false" placeholder="Masukkan No. Faktur" label="" />
    </div>

    <!-- Tanggal Faktur Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Tanggal Faktur</label>
      </div>
      <FieldX type="date" class="w-full col-span-4 !mt-3" :bind="{ readonly: !actionText }"
        :value="values.tanggal_faktur" :errorText="formErrors.tanggal_faktur?'failed':''"
        @input="v=>values.tanggal_faktur=v" :hints="formErrors.tanggal_faktur" :check="false" />
    </div>

    <!-- Nama Pemilik Kendaraan -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'kendaraan'">
      <div class="col-span-3">
        <label class="text-sm">Nama Pemilik</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.nama_pemilik"
        :errorText="formErrors.nama_pemilik?'failed':''" @input="v=>values.nama_pemilik=v"
        :hints="formErrors.nama_pemilik" :check="false" placeholder="Masukkan Nama Pemilik" label="" />
    </div>

    <!-- Jenis Inventaris -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'inventaris'">
      <div class="col-span-3">
        <label class="text-sm">Jenis Inventaris</label>
      </div>
      <FieldSelect class="col-span-9 !mt-3" :bind="{ disabled: !actionText, clearable:false }"
        :value="values.jenis_inventaris_id" @input="v=>values.jenis_inventaris_id=v"
        :errorText="formErrors.jenis_inventaris_id?'failed':''" :hints="formErrors.jenis_inventaris_id" valueField="id"
        displayField="deskripsi" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              transform:false,
              join:false,
              where :`this.group = 'JENIS INVENTARIS'`,
            }
        }" placeholder="Pilih Jenis Inventaris" label="" fa-icon="fa-caret-down" :check="true" />
    </div>

    <!-- Merk Inventaris -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'inventaris'">
      <div class="col-span-3">
        <label class="text-sm">Merk</label>
      </div>
      <FieldSelect class="col-span-9 !mt-3" :bind="{ disabled: !actionText, clearable:false }" :value="values.merk_id"
        @input="v=>values.merk_id=v" :errorText="formErrors.merk_id?'failed':''" :hints="formErrors.merk_id"
        valueField="id" displayField="deskripsi" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              transform:false,
              join:false,
               where :`this.group = 'MERK INVENTARIS'`,
            }
        }" placeholder="pilih Merk" label="" fa-icon="fa-caret-down" :check="true" />
    </div>

    <!-- Spesifikasi Inventaris -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'inventaris'">
      <div class="col-span-3">
        <label class="text-sm">Spesifikasi</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.spesifikasi"
        :errorText="formErrors.spesifikasi?'failed':''" @input="v=>values.spesifikasi=v" :hints="formErrors.spesifikasi"
        :check="false" placeholder="Masukkan Spesifikasi" label="" />
    </div>

    <!-- No. Mesin -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'mesin'">
      <div class="col-span-3">
        <label class="text-sm">No. Mesin</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.no_mesin"
        :errorText="formErrors.no_mesin?'failed':''" @input="v=>values.no_mesin=v" :hints="formErrors.no_mesin"
        :check="false" placeholder="Masukkan Nomor Mesin" label="" />
    </div>

    <!-- Tipe Mesin -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'mesin'">
      <div class="col-span-3">
        <label class="text-sm">Tipe Mesin</label>
      </div>
      <FieldSelect class="col-span-9 !mt-3" :bind="{ disabled: !actionText, clearable:false }"
        :value="values.tipe_mesin_id" @input="v=>values.tipe_mesin_id=v"
        :errorText="formErrors.tipe_mesin_id?'failed':''" :hints="formErrors.tipe_mesin_id" valueField="id"
        displayField="deskripsi" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              transform:false,
              join:false,
               where :`this.group = 'TIPE MESIN'`,
            }
        }" placeholder="Pilih Tipe Mesin" label="" fa-icon="fa-caret-down" :check="true" />
    </div>

    <!-- Dimensi Mesin -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'mesin'">
      <div class="col-span-3">
        <label class="text-sm">Dimensi Mesin</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.dimensi"
        :errorText="formErrors.dimensi?'failed':''" @input="v=>values.dimensi=v" :hints="formErrors.dimensi"
        :check="false" placeholder="Masukkan Dimensi Mesin" label="" />
    </div>

    <!-- No. Sertifikat Mesin -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'mesin'">
      <div class="col-span-3">
        <label class="text-sm">No. Sertifikat</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.nomor_sertifikat"
        :errorText="formErrors.nomor_sertifikat?'failed':''" @input="v=>values.nomor_sertifikat=v"
        :hints="formErrors.nomor_sertifikat" :check="false" placeholder="Masukkan Nomor Sertifikat" label="" />
    </div>

    <!-- Tahun Produksi -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'mesin'">
      <div class="col-span-3">
        <label class="text-sm">Tahun Produksi</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.tahun_produksi"
        :errorText="formErrors.tahun_produksi?'failed':''" @input="v=>values.tahun_produksi=v"
        :hints="formErrors.tahun_produksi" :check="false" placeholder="Masukkan Tahun Produksi" label="" />
    </div>

    <!-- Dimensi Chasis -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'chasis'">
      <div class="col-span-3">
        <label class="text-sm">Dimensi</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.dimensi"
        :errorText="formErrors.dimensi?'failed':''" @input="v=>values.dimensi=v" :hints="formErrors.dimensi"
        :check="false" placeholder="Masukkan Dimensi Chasis" label="" />
    </div>

    <!-- Jumlah ban Chasis -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'chasis'">
      <div class="col-span-3">
        <label class="text-sm">Jumlah Ban</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.jumlah_ban"
        :errorText="formErrors.jumlah_ban?'failed':''" @input="v=>values.jumlah_ban=v" :hints="formErrors.jumlah_ban"
        :check="false" placeholder="Masukkan Jumlah Ban" label="" />
    </div>

    <!-- Warna Chasis -->
    <div class="grid grid-cols-12 items-center" v-if="route.query.isType == 'chasis'">
      <div class="col-span-3">
        <label class="text-sm">Warna</label>
      </div>
      <FieldSelect class="col-span-9 !mt-3" :bind="{ disabled: !actionText, clearable:false }" :value="values.warna_id"
        @input="v=>values.warna_id=v" :errorText="formErrors.warna_id?'failed':''" :hints="formErrors.warna_id"
        valueField="id" displayField="deskripsi" :api="{
          url: `${store.server.url_backend}/operation/m_general`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            transform:false,
            join:false,
            where : `this.group = 'WARNA KENDARAAN'`
          }
      }" placeholder="Pilih Warna Chasis" label="" fa-icon="" :check="true" />
    </div>



    <!-- Masa Manfaat -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Masa Manfaat</label>
      </div>
      <FieldX type="Number" class="col-span-6 !mt-3 pr-1" :bind="{ readonly: !actionText }" :value="values.masa_manfaat"
        :errorText="formErrors.masa_manfaat?'failed':''" @input="v=>values.masa_manfaat=v"
        :hints="formErrors.masa_manfaat" placeholder="Masukkan Masa Manfaat" label="" :check="false" />
      <FieldX class="col-span-3 !mt-3" :bind="{ readonly: true }" :value="'Bulan'"
        :errorText="formErrors.bulan?'failed':''" @input="v=>values.bulan=v" :hints="formErrors.bulan"
        placeholder="Bulan" label="" :check="false" />
    </div>

    <!-- Harga Perolehan -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Harga Perolehan</label>
      </div>
      <FieldNumber class="col-span-9 !mt-3" :bind="{ readonly:!actionText }" :value="values.harga_perolehan"
        @update:valueFull="(dt)=>{
          //values.nama_asset = parseFloat(dt['harga_per_barang'])
          //values.id_lpb_barang = dt.id
          
          values.nilai_buku = dt.harga_perolehan
        $log(dt);
          }" :errorText="formErrors.harga_perolehan?'failed':''" @input="v=>values.harga_perolehan=v"
        :hints="formErrors.harga_perolehan" placeholder="Masukkan Harga Perolehan" label="" :check="false" />
    </div>

    <!-- Tanggal Awal Susut -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Tanggal Awal Susut</label>
      </div>
      <FieldX class="col-span-4 w-full !mt-3" :bind="{ readonly: true, clearable:false, disabled:true }"
        :value="values.tgl_awal_susut" :errorText="formErrors.tgl_awal_susut?'failed':''"
        @input="v=>values.tgl_awal_susut=v" :hints="formErrors.tgl_awal_susut" :check="false" type="date" />
    </div>

    <!-- Tanggal Akhir Susut -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Tanggal Akhir Susut</label>
      </div>
      <FieldX class="col-span-4 w-full !mt-3" :bind="{clearable:false, readonly: true,disabled:true  }"
        :value="tglAkhirSusut" :errorText="formErrors.tgl_akhir_susut?'failed':''" @input="v=>values.tgl_akhir_susut=v"
        :hints="formErrors.tgl_akhir_susut" :check="false" type="date" />
    </div>

    <!-- Akumulasi Penyusutan -->
    <!-- <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Akumulasi Penyusutan</label>
      </div>
      <FieldNumber class="col-span-4 w-full !mt-3" :bind="{ readonly: true }" :value="values.akum_penyusutan"
        :errorText="formErrors.akum_penyusutan?'failed':''" @input="v=>values.akum_penyusutan=v"
        :hints="formErrors.akum_penyusutan" :check="false" />
    </div> -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-12">
      </div>
    </div>

    <!-- Nilai Penyusutan -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Nilai Penyusutan</label>
      </div>
      <FieldNumber class="col-span-4 w-full !mt-3" :bind="{ readonly: true }" :value="values.nilai_penyusutan"
        :errorText="formErrors.nilai_penyusutan?'failed':''" @input="v=>values.nilai_penyusutan=v"
        :hints="formErrors.nilai_penyusutan" :check="false" />
    </div>

    <!-- Nilai Buku -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Nilai Buku</label>
      </div>
      <FieldNumber class="col-span-4 w-full !mt-3" :bind="{ readonly: true }" :value="values.nilai_buku"
        :errorText="formErrors.nilai_buku?'failed':''" @input="v=>values.nilai_buku=v" :hints="formErrors.nilai_buku"
        :check="false" />
    </div>

    <!-- Nilai Minimal -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Nilai Minimal</label>
      </div>
      <FieldNumber class="col-span-4 w-full !mt-3" :bind="{ readonly: !actionText }" :value="values.nilai_minimal"
        :errorText="formErrors.nilai_minimal?'failed':''" @input="v=>values.nilai_minimal=v"
        :hints="formErrors.nilai_minimal" :check="false" />
    </div>

    <!-- CoA Akun Penyusutan -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">CoA Akun Penyusutan<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.coa_penyusutan"
        @input="(v)=>values.coa_penyusutan=v" :errorText="formErrors.coa_penyusutan?'failed':''"
        :hints="formErrors.coa_penyusutan" valueField="id" displayField="nama_coa" :api="{
          url: `${store.server.url_backend}/operation/m_coa`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            searchfield : `this.nomor, this.nama_coa`
          },
      onsuccess(response) {
        response.page = response.current_page;
        response.hasNext = response.has_next;
        return response;
      }
        }" placeholder="Pilih COA" label="" fa-icon="" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'nomor',
          headerName:  'Nomor CoA',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'nama_coa',
          headerName:  'Nama CoA',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        }]" />
    </div>

    <!-- CoA Asset -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">CoA Asset<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.coa_asset"
        @input="(v)=>values.coa_asset=v" :errorText="formErrors.coa_asset?'failed':''" :hints="formErrors.coa_asset"
        valueField="id" displayField="nama_coa" :api="{
          url: `${store.server.url_backend}/operation/m_coa`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            searchfield : `this.nomor, this.nama_coa`
          },
      onsuccess(response) {
        response.page = response.current_page;
        response.hasNext = response.has_next;
        return response;
      }
        }" placeholder="Pilih COA" label="" fa-icon="" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'nomor',
          headerName:  'Nomor CoA',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'nama_coa',
          headerName:  'Nama CoA',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        }]" />
    </div>

    <!-- CoA By Akun Penyusutan -->
    <div class="grid grid-cols-12 items-center ">
      <div class="col-span-3">
        <label class="text-sm">CoA By Akun Penyusutan<span style="color: red">*</span></label>
      </div>
      <FieldPopup required class="col-span-9 !mt-3" :bind="{ readonly: !actionText }"
        :value="values.coa_by_akun_penyusutan" @input="(v)=>values.coa_by_akun_penyusutan=v"
        :errorText="formErrors.coa_by_akun_penyusutan?'failed':''" :hints="formErrors.coa_by_akun_penyusutan"
        valueField="id" displayField="nama_coa" :api="{
          url: `${store.server.url_backend}/operation/m_coa`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            searchfield : `this.nomor, this.nama_coa`
          },
      onsuccess(response) {
        response.page = response.current_page;
        response.hasNext = response.has_next;
        return response;
      }
        }" placeholder="Pilih COA" label="" fa-icon="" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'nomor',
          headerName:  'Nomor CoA',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'nama_coa',
          headerName:  'Nama CoA',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        }
        ]" />
    </div>

    <div></div>

    <!-- Catatan -->
    <div class="grid grid-cols-12 items-center">
      <div class="col-span-3">
        <label class="text-sm">Catatan</label>
      </div>
      <FieldX class="col-span-9 !mt-3" :bind="{ readonly: !actionText }" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        type="textarea" placeholder="" :check="false" />
    </div>

  </div>

  <!-- START TABLE DETAIL -->
  <div class="<md:col-span-1 col-span-3 pl-4 pr-4">
    <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3 mt-4">
      <button @click="generateTotal()"
      class="!focus:outline-none !text-xs rounded w-auto flex items-center space-x-2">
      <div v-show="actionText"
        class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
        <icon fa="plus" />
        Auto Generate
      </div>
    </button>
      <table class="w-full overflow-x-auto table-auto border mt-4 border-[#CACACA] pt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>

            <td
              class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Tanggal Penyusutan</td>

            <td
              class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nilai Akum Sblm Penyusutan</td>

            <td
              class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nilai Buku Sblm Penyusutan</td>

            <td
              class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nilai Penyusutan</td>

            <td
              class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nilai Akum Stl Penyusutan</td>
            <td
              class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nilai Buku Stl Penyusutan</td>

            <td
              class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Status</td>

          </tr>
        </thead>

        <tbody>
          <tr v-for="(item,i) in detailArr" :key="i" class="border-t" v-if="detailArr.length > 0">
            <td class=" text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <!-- <td class="p-2 border border-[#CACACA]">
              {{item.item_code ?? '-'}}
            </td> -->
            <td class="px-1 text-center border border-[#CACACA]">
              {{item['tanggal_penyusutan'] ?? '-'}}
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              {{ formatCurrency(item['nilai_akun_sebelum_penyusutan']) }}
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              {{ formatCurrency(item['nilai_buku_sebelum_penyusutan']) }}
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              {{ formatCurrency(item['nilai_penyusutan']) }}
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              {{ formatCurrency(item['nilai_akumulasi_setelah_penyusutan']) }}
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              {{ formatCurrency(item['nilai_buku_setelah_penyusutan']) }}
            </td>

            <td class="border border-[#CACACA]">
              <FieldX :bind="{ readonly: true ,disabled : true}" class="!mt-0" :value="item.status"
                @input="v=>item.status=v" :check="false" />
            </td>

          </tr>
          <tr v-else class="text-center">
            <td colspan="12" class="py-[20px]">
              No data to show
            </td>
          </tr>
        </tbody>
      </table>
    </div>

        <!-- START APPROVAL -->
    <div v-show="route.query.is_approval" class="<md:col-span-1 col-span-2 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2">
      <div>
        <table class=" w-[100%] my-3 border">
          <tr class="border">
            <td class="border px-2 py-1 font-semibold">Nomor</td>
            <td class="border px-2 py-1">{{ values.approval?.nomor ?? '-' }}</td>
          </tr>
          <tr class="border">
            <td class="border px-2 py-1 font-semibold">Tanggal</td>
            <td class="border px-2 py-1">{{ values.approval?.created_at ?? '-' }}</td>
          </tr>
          <tr class="border">
            <td class="border px-2 py-1 font-semibold">Pemohon</td>
            <td class="border px-2 py-1">{{ values.approval?.creator ?? '-' }}</td>
          </tr>
          <tr class="border">
            <td class="border px-2 py-1 font-semibold">Status</td>
            <td class="border px-2 py-1">{{ values.approval?.status ?? '-' }}</td>
          </tr>
        </table>
      </div>
      <div class="">
        <table class=" w-[100%] my-3 ">
          <tr>
            <td class=" px-2 py-1">
              <button
                    v-show="route.query.is_approval"
                    @click="openModal(values?.trx?.id ?? 0)"
                    class="hover:text-blue-500">
                    <icon fa="table" size="sm"/>
                    Log Approval
                  </button>
            </td>
          </tr>
          <!-- <tr v-show="isFinish">
              <td class=" px-2 py-1">
                <button
                  @click="downloadDoc()" 
                  class="hover:text-blue-500">
                  <icon fa="download" size="sm"/>
                  Download .docx
                </button>
              </td>
            </tr> -->
        </table>
      </div>
      <div class="w-1/2 mt-3">
        <label class="col-span-12 font-semibold">Catatan Approval<label class="text-red-500 space-x-0 pl-0">*</label></label>
        <FieldX :bind="{ readonly: false }" class="w-full py-2 !mt-0" :value="values.note_approval"
          :errorText="formErrors.note_approval?'failed':''" @input="v=>values.note_approval=v"
          :hints="formErrors.note_approval" :check="false" label="" placeholder="Tuliskan catatan" />
      </div>
    </div>

    <!-- ACTION BUTTON START -->
    <hr>
    <div class="grid grid-cols-12 items-center" >
      <div class="col-span-7 justify-start ml-5">
        <p v-if="values.created_id">Created By {{values.created_id}} on {{values.created_at}}</p>
        <p v-if="values.edited_id">Last Edit By {{values.edited_id}} on {{values.edited_at}}</p>
      </div>
      <div class="col-span-5 justify-end space-x-2 p-2">
        <div class="flex flex-row items-center justify-end space-x-2 p-2">
          <button
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText || actionEditBerkas"
        @click="onReset(true)"
      >
        <icon fa="times" />
        Reset
      </button>
          <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText || actionEditBerkas"
        @click="onSave"
      >
        <icon fa="save" />
        Simpan
      </button>
          <button v-show="route.query.is_approval" class="mx-1 bg-green-500 text-white hover:bg-green-600 rounded-[4px] px-[36.5px] py-[5px]" @click="onProcess('APPROVE')">
            Approve
          </button>
          <button v-show="route.query.is_approval" class="mx-1 bg-rose-500 text-white hover:bg-rose-600 rounded-[4px] px-[36.5px] py-[5px]" @click="onProcess('REJECT')">
            Reject
          </button>
          <button v-show="route.query.is_approval" class="mx-1 bg-amber-500 text-white hover:bg-amber-600 rounded-[4px] px-[36.5px] py-[5px]" @click="onProcess('REVISE')">
            Revise
          </button>
        </div>
      </div>

    </div>
  </div>

  <div v-show="modalOpen" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
    <div class="modal-container bg-white  w-[70%] mx-auto rounded shadow-lg z-50 overflow-y-auto">
      <div class="modal-content py-4 text-left px-6">
        <!-- Modal Header -->
        <div class="modal-header flex items-center justify-between flex-wrap">
          <div class="flex items-center">
            <h3 class="text-xl font-semibold ml-2">Log Approval
              <span v-if="!dataLog?.items.length" class="!text-red-600"> | Belum ada log approval</span>
            </h3>
          </div>
        </div>

        <!-- Modal Body -->
        <div v-if="dataLog?.items.length" class="modal-body">
          <table class="w-[100%] my-3 border">
            <thead>
              <tr class="border">
                <td class="border px-2 py-1 font-medium ">Urutan</td>
                <td class="border px-2 py-1 font-medium ">Nomor Transaksi</td>
                <td class="border px-2 py-1 font-medium ">Tipe Aksi</td>
                <td class="border px-2 py-1 font-medium ">Tanggal Aksi </td>
                <td class="border px-2 py-1 font-medium ">User Aksi</td>
                <td class="border px-2 py-1 font-medium ">Catatan</td>
              </tr>
            </thead>
            <tr class="border" v-for="d,i in dataLog?.items" :key="i">
              <td class="border px-2 py-1">{{ i+1 }}</td>
              <td class="border px-2 py-1">{{ d.trx_nomor ?? '-' }}</td>
              <td class="border px-2 py-1">{{ d.action_type ?? '-' }}</td>
              <td class="border px-2 py-1">{{ d.action_at ?? '-' }}</td>
              <td class="border px-2 py-1">{{ d.action_user ?? '-' }}</td>
              <td class="border px-2 py-1">{{ d.action_note ?? '-' }}</td>
            </tr>
          </table>
        </div>
        <!-- END APPROVAL   -->

        <!-- Modal Footer -->
        <div class="modal-footer flex justify-end mt-2">
          <button @click="closeModal" class="modal-button bg-yellow-500 hover:bg-yellow-600 text-white font-semibold ml-2 px-2 py-1 rounded-sm">
          Tutup
        </button>
        </div>
      </div>
    </div>
  </div>

  </div>

</div>

@endverbatim
@endif