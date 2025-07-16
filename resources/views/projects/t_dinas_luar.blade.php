<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center px-2.5 py-1">
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
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Dinas Luar</h1>
        <p class="text-gray-100">Transaksi Dinas Luar</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
        <!-- Date Coloumn -->
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3 w-full" :value="values.tanggal"
        :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v" :hints="formErrors.tanggal" :check="false"
        type="date" label="Tanggal" placeholder=" Tanggal" />
    </div>
    <!-- No.Dinas Luar -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3 w-full" :value="values.no_dinas_luar"
        :errorText="formErrors.no_dinas_luar?'failed':''" @input="v=>values.no_dinas_luar=v" :hints="formErrors.no_dinas_luar"
        label="No.Dinas Luar" placeholder="No.Dinas Luar" :check="false" />
    </div>

        <!-- No.Dinas Luar -->
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3 w-full" :value="values.total_amt"
        :errorText="formErrors.total_amt?'failed':''" @input="v=>values.total_amt=v" :hints="formErrors.total_amt"
        label="Total" placeholder="Total" :check="false" />
    </div>

    <!-- Pilih sopir -->
       <div>
      <FieldPopup label="Sopir" :bind="{ readonly: !actionText , disabled: !actionText , clearable:true}"  class="w-full !mt-3" valueField="id" displayField="nama"
        :value="values.supir_id"
        @update:valueFull="supir"
         @input="(v)=>values.supir_id=v" 
        :api="{
              url: `${store.server.url_backend}/operation/m_kary`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                view_buku_order_on_spk_angkutan: true,
                join:true,
                simplest:true,
                searchfield: 'this.tgl , this.no_buku_order , this.jenis_barang'
              }
            }" placeholder="Pilih Sopir" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'no_id',
              headerName:  'Nomor ID Supir',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'nama',
              headerName:  'Nama',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            ]" />
    </div>

    <div> </div>
    <div> </div>
    <!-- Status Coloumn -->
    <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.status" :errorText="formErrors.status?'failed':''"
          @input="v=>values.status=v" :hints="formErrors.status" 
          :check="false"
          label="Status"
          placeholder="Status"
        />
      </div>

    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>

  <!-- detail -->
  
<div class="flex flex-row items-center justify-end space-x-2 p-2">
  <i class="text-gray-500 text-[12px]" v-show="actionText">
    Tekan CTRL + S untuk shortcut Save Data
  </i>
  <button
    class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
    v-show="actionText"
    @click="onReset(true)"
  >
    <icon fa="times" />
    Reset
  </button>

  <button
    v-if="values.status === 'DRAFT'"
    class="text-sm rounded-md py-2 px-3 text-white bg-yellow-600 hover:bg-yellow-700 flex gap-x-1 items-center
    transition-colors duration-300"
    @click="onSave(true)"
  >
    <icon fa="paper-plane" />
    <span>Simpan Dan Post</span>
  </button>
  
  <button
    class="text-sm rounded-md py-2 px-3 text-white bg-green-600 hover:bg-green-700 flex gap-x-1 items-center
    transition-colors duration-300"
    v-show="actionText"
    @click="onSave(false)"
  >
    <icon fa="save" />
    <span>Simpan</span>
  </button>
</div>

    <hr class="w-full">
      
<div class="p-4"> 
  <span class="text-xl font-semibold">Detail Dinas Luar</span>
  <div class="font-bold mt-4">Total: {{ formatRupiah(values.total_amt) }}</div>
          <div class=" space-x-5">
          <!-- <button v-show="actionText" class="mt-5 rounded bg-blue-500 text-white hover:bg-blue-600 duration-300 px-4 py-2 font-semibold" @click="addItem"> + Tambah Detail</button> -->
          <button v-if="selectedItems.length > 0" @click="removeSelectedDetails" type="button" class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5 ml-2">
              <icon fa="trash" />
              Hapus yang Dipilih
            </button>
        </div>
        <div v-for="(item, i) in detailArr" :key="i" class="border rounded-lg p-4 my-2">
          <div class="font-bold">No. {{i+1}}</div>
          <div class="flex justify-between items-center space-x-6">
            <input v-show="actionText" type="checkbox" class="h-6 w-6" v-model="selectedItems" :value="i" />
            <button v-show="actionText" class="rounded px-4 py-2 border border-red-300 hover:bg-gray-100" @click="removeItem(i)">
              <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
              </svg>
          </button>
          </div>
         <div class="grid grid-cols-3 gap-3 p-4"> 
          <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3 w-full" :value="item.no_order"
        :errorText="formErrors.no_order?'failed':''" @input="v=>item.no_order=v" :hints="formErrors.no_order"
        label="No.Order" placeholder="No.Order" :check="false" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3 w-full" :value="item.nominal"
        :errorText="formErrors.nominal?'failed':''" @input="v=>item.nominal=v" :hints="formErrors.nominal"
        label="Nominal" placeholder="Nominal" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3 w-full" :value="item.keterangan"
        :errorText="formErrors.keterangan?'failed':''" @input="v=>item.keterangan=v" :hints="formErrors.keterangan"
        label="Catatan"  type="textarea" placeholder="Catatan" :check="false" />
    </div>
         </div>
        </div>
</div>



  <!-- </div> -->

</div>
@endverbatim
@endif