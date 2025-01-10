@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px] border-t-10 border-blue-500 ">
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
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions" class="max-h-[450px] pt-2 !px-4 !pb-8">
    <template #header>
      <div class="pb-13 h-full"></div>
    </template>
  </TableApi>
</div>

</div>
@else

@verbatim
<div class="flex flex-col gap-y-3 rounded-t-md bg-white">
      <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
      <div class="flex items-center">
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-blue-300" title="Kembali" @click="onBack"/>
        <div>
          <h1 class="text-20px font-bold">COPY PASTE</h1>
          <p class="text-gray-100">Untuk mengatur Menu pada Sidebar</p>
        </div>
      </div>
    </div>
  <div class="flex gap-x-4 px-2 p-4">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">

      <div class="grid <md:grid-cols-1 grid-cols-3 gap-2">
        <!-- START COLUMN -->
        <!-- PROVINSI KOTA KECAMATAN -->
        <div>
        <FieldSelect :bind="{  clearable: true }" class="w-full  !mt-3" 
      :value="values.provinsi" @input="v => values.provinsi = v" @update:valueFull="(objVal) => {
      values.kota = '';     
    }"
    label="Provinsi"
    placeholder="Pilih Provinsi" :api="{
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
    }" :check="false"
       />
        </div>

         <div>
        <FieldSelect :bind="{  clearable: true }" class="w-full  !mt-3" 
        :value="values.kota" 
        @input="v => values.kota = v" 
        @update:valueFull="(objVal) => {
      values.kecamatan = '';
    }" 
    label="kota"
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
    }" :check="false" 
    />
        </div>
        
         <div>
        <FieldSelect :bind="{  clearable: true }" class="w-full  !mt-3" 
        :value="values.kecamtan" @input="v => values.kecamtan = v"
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
    }" :check="false" 
    />
        </div>


        <!-- BANK -->

                <div class="flex">
          <FieldPopup class="!mt-3 w-full" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              where:`this.group='BANK'`
            },
          }" displayField="deskripsi" valueField="deskripsi" :bind="{ readonly: !actionText }"
            :value="values.m_employee_id" @input="(v)=>values.m_employee_id=v" @update:valueFull="(data)=>{
    if (data && data.kode) {
      values.kode_bank = data.kode;
    } else {
      values.kode_bank = ''; 
    }
          return response;
        }" :errorText="formErrors.m_employee_id?'failed':''" class="w-full !mt-3" :hints="formErrors.m_employee_id"
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
            sortable: false, resizable: true, filter: false,
          },
          ]" />
          <span class="text-red-500"> * </span>
        </div>
        <div class="flex">
          <FieldX :bind="{ readonly: true }" class="w-full  !mt-3" :value="values.kode_bank"
            :errorText="formErrors.kode_bank ? 'failed' : ''" @input="v => values.kode_bank = v"
            :hints="formErrors.kode_bank" :check="false" type="number" label="Kode Bank"
            placeholder="Tuliskan Kode Bank" />
          <span class="text-red-500"> * </span>
        </div>
         <!-- END PROVINSI KOTA KECAMATAN -->
        <!-- END COLUMN -->
        <!-- ACTION BUTTON START -->

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