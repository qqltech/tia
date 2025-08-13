<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2">
    <h1 class="text-xl font-semibold">BON DINAS LUAR</h1>
  </div>
  <div class="flex justify-between items-center gap-x-4 p-4">

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
        <button @click="filterShowData('PRINTED')" :class="filterButton === 'PRINTED' ? 'bg-purple-600 text-white hover:bg-purple-600' 
          : 'border border-purple-600 text-purple-600 bg-white hover:bg-purple-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          PRINTED
        </button>
      </div>
    </div>

    <!-- ACTION BUTTON -->
    <div class="flex items-center gap-x-4">
      <ButtonMultiSelect title="Multiple Post" @add="onDetailAdd" :api="{
          url: `${store.server.url_backend}/operation/${endpointApi}`,
          headers: {'Content-Type': 'Application/json', authorization: `${store.user.token_type} ${store.user.token}`},
          params:{
            searchfield: 'this.no_bon_dinas_luar, this.tanggal, this.total_amt, this.status',
            where: `this.status= 'DRAFT'`
          },
          onsuccess:(response)=>{
            response.data = [...response.data].map((dt)=>{
              Object.keys(dt).forEach(k=>dt['t_bkk_non_order.'+k] = dt[k])
              return dt
            })
            response.page = response.current_page
            response.hasNext = response.has_next
            return response
          }
        }" :columns="[{
            checkboxSelection: true,
            headerCheckboxSelection: true,
            headerName: 'No',
            valueGetter:(params)=>{
              return ''
            },
            width:60,
            sortable: false, resizable: true, filter: false,
            cellClass: ['justify-center', 'bg-gray-50', '!border-gray-200']
          },
          {
            flex: 1,
            headerName:'No. Bon Dinas Luar',
            sortable: false, resizable: true, filter: false,
            field: 'no_bon_dinas_luar',
            cellClass: ['justify-center','!border-gray-200'],
            filter:'ColFilter'
          },
          {
            flex: 1,
            headerName:'Tanggal',
            sortable: false, resizable: true, filter: false,
            field: 'tanggal',
            cellClass: ['justify-center','!border-gray-200'],
            filter:'ColFilter'
          },
          {
            flex: 1,
            headerName:'Nominal',
            sortable: false, resizable: true, filter: false,
            field: 'total_amt',
            cellClass: ['justify-end','!border-gray-200'],
            filter:'ColFilter',
            valueFormatter: params => {
              if (params.value == null) return '';
              return 'Rp ' + params.value.toLocaleString('id-ID');
            }
          },
          {
            flex: 1,
            headerName:'Status',
            sortable: false, resizable: true, filter: false,
            field: 'status',
            cellClass: ['justify-center','!border-gray-200'],
            filter:'ColFilter'
          }
          ]" @selection-change="selectedItems = $event">
        <div class="flex items-center space-x-2">
          <div
            class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
            <icon fa="arrow-up-right-from-square" />
            Multiple Post
          </div>
        </div>
      </ButtonMultiSelect>

      <button class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white text-sm rounded py-1 px-2.5
      transition-colors duration-300" @click="openModal('320', 'Eksport')">Create New Eksport</button>

      <button class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white text-sm rounded py-1 px-2.5
      transition-colors duration-300" @click="openModal('319', 'Import')">Create New Import</button>
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
<div v-if="isModalOpen"
  class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto max-h-[100vh]">
  <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">
        Pilih Akun <span v-text="tipe_order"></span>
      </h2>
      <hr>
    </div>
    <div class="p-1 ">
      <div class="flex justify-center">
        <div class="grid grid-cols-3">
          <button v-for="(item, i) in coaListEks" :key="i" v-show="coaListEks.length > 0 && tipe_order=='Eksport'"
            @click="setTipeKategori(item.id)"
            class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(90%)] rounded-1xl m-2 text-xl font-semibold"
            :class="(tipe_kategori_id == item.id) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'">
            <span v-text="item.nama_coa"></span>
          </button>
          <button v-for="(item, i) in coaListImp" :key="i" v-show="coaListImp.length > 0 && tipe_order=='Import'"
            @click="setTipeKategori(item.id)"
            class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(90%)] rounded-1xl m-2 text-xl font-semibold"
            :class="(tipe_kategori_id == item.id) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'">
            <span v-text="item.nama_coa"></span>
          </button>
        </div>
      </div>
    </div>
    <div class="flex justify-end pt-4">
      <RouterLink v-if="tipe_kategori_id"
        :to="`${$route.path}/create?${Date.parse(new Date())}&tipe_order_id=${tipe_order_id}&tipe_kategori_id=${tipe_kategori_id}`"
        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 mr-4">
        Create</RouterLink>
      <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600" @click="isModalOpen=false; setTipeKategori('')">Cancel</button>
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
        <h1 class="text-lg font-bold leading-none">Form Bon Dinas Luar</h1>
        <p class="text-gray-100 leading-none">Transaction Bon Dinas Luar</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <div class="grid grid-cols-2 gap-4">
        <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, clearable:true }" :value="data.tipe_order_id"
          @input="v=>data.tipe_order_id=v" :errorText="formErrors.tipe_order_id?'failed':''"
          :hints="formErrors.tipe_order_id" valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where:`this.group='TIPE ORDER'`
              }
          }" fa-icon="sort-desc" placeholder="Tipe Order" :check="false" />
        <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, clearable:true }" :value="data.tipe_kategori_id"
          @input="v=>data.tipe_kategori_id=v" :errorText="formErrors.tipe_kategori_id?'failed':''"
          :hints="formErrors.tipe_kategori_id" valueField="id" displayField="nama_coa" :api="{
              url: `${store.server.url_backend}/operation/m_coa`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:false,
                transform:false,
                join:true,
              }
          }" fa-icon="sort-desc" placeholder="Tipe Kategori" :check="false" />
      </div>
    </div>
    <!-- <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>data.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft" placeholder="No. Draft" :check="false" />
    </div> -->
    <div>
      <FieldX :bind="{ readonly: !actionText, disabled: !actionText, clearable:false }" class="w-full !mt-3"
        :value="data.tanggal" :errorText="formErrors.tanggal?'failed':''" @input="v=>data.tanggal=v"
        :hints="formErrors.tanggal" :check="false" type="date" label="Tanggal" placeholder="Pilih Tanggal" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3 pointer-events-none" :value="data.no_bkk"
        :errorText="formErrors.no_bkk?'failed':''" @input="v=>data.no_bkk=v" :hints="formErrors.no_bkk" label="No. BKK"
        placeholder="No. BKK" :check="false" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="data.total_amt"
        :errorText="formErrors.total_amt?'failed':''" @input="v=>data.total_amt=v" :hints="formErrors.total_amt"
        label="Total Amt" placeholder="Total Amt" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" :bind="{ readonly: !actionText }" :value="data.m_kary_id" @input="v=>{
          if(v){
            data.m_kary_id=v
          }else{
            data.m_kary_id=null
          }
        }" :errorText="formErrors.m_kary_id?'failed':''" :hints="formErrors.m_kary_id" @update:valueFull="(dt) => {
              $log(dt)
            }" valueField="id" displayField="nama" :api="{
              url: `${store.server.url_backend}/operation/m_kary`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where: `this.is_active=true`,
                searchfield: 'this.nip, this.nama, this.divisi',
              }
            }" placeholder="Nama Karyawan" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'nip',
              headerName:  'NIP',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'nama',
              headerName:  'Nama Karyawan',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'divisi',
              headerName:  'Divisi',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            ]" />
    </div>

    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" :bind="{ readonly: !actionText }" :value="data.m_supplier_id" @input="v=>{
          if(v){
            data.m_supplier_id=v
          }else{
            data.m_supplier_id=null
          }
        }" :errorText="formErrors.m_supplier_id?'failed':''" :hints="formErrors.m_supplier_id" @update:valueFull="(dt) => {
              $log(dt)
            }" valueField="id" displayField="nama" :api="{
              url: `${store.server.url_backend}/operation/m_supplier`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where: `this.is_active=true`,
                searchfield: 'this.kode, this.nama, this.no_telp1',

              }
            }" placeholder="Nama Supplier" :check="false" :columns="[{
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
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'nama',
              headerName:  'Nama Supplier',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'no_telp1',
              headerName:  'No. Telephone',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            ]" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.status"
        :errorText="formErrors.status?'failed':''" @input="v=>data.status=v" :hints="formErrors.status" label="Status"
        placeholder="Status" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" type="textarea" :value="data.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>data.catatan=v" :hints="formErrors.catatan"
        label="Catatan" placeholder="Catatan" :check="false" />
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>
  <div class="grid grid-cols-4 place-content-center place-items-center w-[calc(80%)] min-w-220 items-center">
    <button v-for="(item, i) in coaList" :key="i" v-show="coaList.length > 0"
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_coa_id === item.id) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(item.id)">
        {{item.nama_coa}}
    </button>
  </div>
  <!-- <div class="grid grid-cols-4 place-content-center place-items-center w-[calc(80%)] min-w-220 items-center">
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_perkiraan_id === 1) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(1)">
        Tipe Perkiraan 1
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_perkiraan_id === 2) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(2)">
        Tipe Perkiraan 2
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_perkiraan_id === 3) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(3)">
        Tipe Perkiraan 3
    </button>
    <button
      class="border-1 border-blue-500 hover:bg-blue-400 hover:text-white transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl m-2 h-[80px] w-[calc(80%)] rounded-1xl m-2 text-xl font-semibold"
      :class="(data && data.m_perkiraan_id === 4) ? 'bg-blue-600 text-white' : 'bg-white text-blue-500'"
      @click="setPerkiraan(4)">
        Tipe Perkiraan 4
    </button>
  </div> -->
  <hr />
  <!-- detail -->

  <!-- START TABLE DETAIL -->

  <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <ButtonMultiSelect @add="addDetailArr" :api="{
        url: `${store.server.url_backend}/operation/t_buku_order`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: false, 
            searchfield: 'this.kategori, this.debit_kredit',
            // notin: `this.id: ${detailArr.map((det)=> (det.t_buku_order_id))}`
            },
            onsuccess: (response) => {
              $log(response.data[0]['m_item.id'])
              response.data = [...response.data].map((dt) => {
                return {
                  t_bon_dinas_luar_id: data.id || null,
                  t_buku_order_id: dt.id,
                  no_buku_order: dt.no_buku_order,
                  jenis_barang: dt.jenis_barang,
                  nama_perusahaan: dt['m_customer.nama_perusahaan'],
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
              flex: 1,
              field: 'no_buku_order',
              headerName:  'No Order',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'jenis_barang',
              headerName:  'Jenis Barang',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            
            {
              flex: 1,
              field: 'nama_perusahaan',
              headerName:  'Nama Customer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            }
             ]">
        <div v-show="actionText" class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300">
          <icon fa="plus" size="sm" />
          <span>Add To List</span>
        </div>
      </ButtonMultiSelect>
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              No. Order
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Keterangan
            </td>
            <!-- <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Ukuran Container
            </td> -->
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Sub Total
            </td>
            <td v-show="actionText"
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] w-[5%]">
              Action
            </td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="i" v-show="detailArr.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <td class="p-2 border border-[#CACACA] text-center">{{ item.no_buku_order }}</td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: !actionText}" class="w-full py-2 !mt-0" :value="item.keterangan"
                @input="v=>item.keterangan=v" :errorText="formErrors.keterangan?'failed':''"
                :hints="formErrors.keterangan" placeholder="Masukkan Keterangan" label="" type="textarea"
                :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].sub_total"
                :errorText="formErrors.sub_total?'failed':''" @input="v=>detailArr[i].sub_total=v"
                :hints="formErrors.sub_total" :check="false" />
            </td>
            <td v-show="actionText" class="p-2 border border-[#CACACA]">
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
    <!-- <button v-show="(((actionText=='Edit' || actionText=='Create'|| actionText=='Copy') && (data.status=='DRAFT' || data.status=='REVISED')))" class="text-sm rounded py-2 px-2.5 text-white bg-purple-600 hover:bg-purple-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="sendApproval">
      <icon fa="location-arrow" />
      <span>send Approval</span>
    </button> -->
  </div>

  <hr v-show="isApproval" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="isApproval">
    <button class="text-sm rounded py-2 px-2.5 text-white  bg-green-600 hover:bg-green-700 flex gap-x-1 items-center 
        transition-colors duration-300" @click="progress('APPROVED')">
      <!-- <icon fa="times" /> -->
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