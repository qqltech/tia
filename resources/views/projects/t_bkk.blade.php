<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
   <div class="pl-4 pt-2">
    <h1 class="text-xl font-semibold">BKK</h1>
  </div>
  <div class="flex justify-between items-center gap-x-4 p-4">
    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          DRAFT
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('IN APPROVAL')" :class="filterButton === 'IN APPROVAL' ? 'bg-sky-600 text-white hover:bg-sky-600' 
          : 'border border-sky-600 text-sky-600 bg-white hover:bg-sky-600 hover:text-white'"
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
        <button @click="filterShowData('APPROVED')" :class="filterButton === 'APPROVED' ? 'bg-purple-600 text-white hover:bg-purple-600' 
          : 'border border-purple-600 text-purple-600 bg-white hover:bg-purple-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          APPROVED
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('REVISED')" :class="filterButton === 'REVISED' ? 'bg-yellow-600 text-white hover:bg-yellow-600' 
          : 'border border-yellow-600 text-yellow-600 bg-white hover:bg-yellow-600 hover:text-white'"
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
    <div>
      <!-- <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink> -->
      <button class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white text-sm rounded py-1 px-2.5
      transition-colors duration-300" @click="isModalOpen=true">Create New</button>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px]">
    <!-- <template #header>
    </template> -->
  </TableApi>
</div>
<div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
  <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Pilih Tipe BKK</h2>
      <hr>
    </div>
    <div class="p-1 ">
      <div class=" flex justify-center">
        <button @click="setTipe('Buku Order')"
          class="hover:bg-blue-100 text-blue-500 font-semibold w-50 h-50 mx-5 text-center rounded-lg"
          :class="tipe === 'Buku Order' ? 'bg-blue-200': 'bg-blue-50'">
          <div class="h-40 w-full flex items-center justify-center">
            <icon fa="box-open" class="text-8xl" />
          </div>
          <div
            class="bg-blue-500 hover:bg-blue-600 text-white text-2xl font-semibold h-10 w-full rounded-b-lg flex items-center justify-center">
            <p>Buku Order</p>
          </div>
        </button>
        <button @click="setTipe('Non Buku Order')"
          class="hover:bg-blue-100 text-blue-500 font-semibold w-50 h-50 mx-5 text-center rounded-lg"
          :class="tipe === 'Non Buku Order' ? 'bg-blue-200': 'bg-blue-50'"
          >
          <div class="h-40 w-full flex items-center justify-center">
            <icon fa="boxes-stacked" class="text-8xl" />
          </div>
          <div
            class="bg-blue-500 hover:bg-blue-600 text-white text-2xl font-semibold h-10 w-full rounded-b-lg flex items-center justify-center">
            <p>Non Buku Order</p>
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

<!-- CONTENT -->
@verbatim
<div v-if="values.tipe_bkk=='Buku Order'"
  class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">BKK</h1>
        <p class="text-gray-100">Transaksi BKK</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>values.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_bkk"
        :errorText="formErrors.no_bkk?'failed':''" @input="v=>values.no_bkk=v" :hints="formErrors.no_bkk"
        label="No. BKK" placeholder="No. BKK" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-1/2 !mt-3" :value="values.tanggal"
        :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v" :hints="formErrors.tanggal"
        :check="false" type="date" label="Tgl BKK" placeholder="Pilih Tgl BKK" />
    </div>
    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" :bind="{ readonly: !actionText }" :value="values.t_buku_order_id" @input="v=>{
          if(v){
            values.t_buku_order_id=v
          }else{
            values.t_buku_order_id=null
          }
        }" :errorText="formErrors.t_buku_order_id?'failed':''" :hints="formErrors.t_buku_order_id" @update:valueFull="(dt) => {
              $log(dt)
            }" valueField="id" displayField="no_buku_order" :api="{
              url: `${store.server.url_backend}/operation/t_buku_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where: `this.status='POST'`
              }
            }" placeholder="No Order" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
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
              field: 'm_customer.nama_perusahaan',
              headerName:  'Nama Customer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            }
            ]" />
    </div>
    <div>
      <FieldPopup class="w-full !mt-3" :api="{
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
          searchfield:'this.nomor, this.nip, this.nama_coa, kategori.deskripsi, this.jenis, this.induk',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili'
          notin: `this.id: ${actionText=='Edit' ? [values.m_akun_pembayaran_id] : []}`, 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="nama_coa" valueField="id" :bind="{ readonly: !actionText }" :value="values.m_akun_pembayaran_id"
        @input="(v)=>values.m_akun_pembayaran_id=v" @update:valueFull="(response)=>{
        $log(response);
      }" :errorText="formErrors.m_akun_pembayaran_id?'failed':''" class="w-full !mt-3"
        :hints="formErrors.m_akun_pembayaran_id" placeholder="Pilih Akun Pembayaran" :check='false' :columns="[
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
    <!-- <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_reference"
        :errorText="formErrors.no_reference?'failed':''" @input="v=>values.no_reference=v"
        :hints="formErrors.no_reference" label="No. Reference" placeholder="No. Reference" :check="false" />
    </div> -->
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="values.total_amt"
        :errorText="formErrors.total_amt?'failed':''" @input="v=>values.total_amt=v" :hints="formErrors.total_amt"
        label="Total Amt" placeholder="Total Amt" :check="false" />
    </div>

    <div>
      <FieldSelect :bind="{ disabled: true, clearable: false }" class="w-full !mt-3" :value="values.tipe_bkk"
        @input="v=>values.tipe_bkk=v" :errorText="formErrors.tipe_bkk?'failed':''" :hints="formErrors.tipe_bkk"
        valueField="id" displayField="key" :options="['Buku Order','Non Buku Order']" placeholder="Tipe BKK"
        :check="false" fa-icon="sort-desc" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.status"
        :errorText="formErrors.status?'failed':''" @input="v=>values.status=v" :hints="formErrors.status" label="Status"
        placeholder="Status" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" type="textarea" :value="values.keterangan"
        :errorText="formErrors.keterangan?'failed':''" @input="v=>values.keterangan=v" :hints="formErrors.keterangan"
        label="Keterangan" placeholder="Keterangan" :check="false" />
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>

  <!-- detail -->
  <div class="p-4">
    <ButtonMultiSelect @add="addDetailArr" :api="{
        url: `${store.server.url_backend}/operation/m_coa`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: true, 
            searchfield: 'this.nomor, this.nama_coa',
            notin: `this.id: ${detailArr.map((det)=> (det.m_coa_id))}`
            },
            onsuccess: (response) => {
              response.data = [...response.data].map((dt) => {
                return {
                  t_bkk_id: values.id || 1,
                  m_coa_id: dt.id,
                  kategori: dt['kategori.deskripsi'],
                  jenis: dt['jenis.deskripsi'],
                  nama_coa: dt['nama_coa'],
                  induk: dt['m_induk.nama_coa'],
                  nomor: dt['nomor'],
                  
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
          field: 'kategori',
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
         ]">
      <div class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300">
        <icon fa="plus" size="sm" />
        <span>Add To List</span>
      </div>
    </ButtonMultiSelect>

    <div class="mt-4">
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA]">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Kode Akun
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nama Akun
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nominal
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Detail
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
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.nomor"
                @input="v=>item.nomor=v" :errorText="formErrors.nomor?'failed':''" :hints="formErrors.nomor" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.nama_coa"
                @input="v=>item.nama_coa=v" :errorText="formErrors.nama_coa?'failed':''" :hints="formErrors.nama_coa" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.nominal" @input="v=>item.nominal=v" :errorText="formErrors.nominal?'failed':''"
                :hints="formErrors.nominal" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.keterangan" @input="v=>item.keterangan=v" :errorText="formErrors.keterangan?'failed':''"
                :hints="formErrors.keterangan" type="textarea" />
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

  <hr>

  <div class="flex flex-row items-center justify-end space-x-2 p-2" v-show="actionText">
    <i class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
    <button
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        @click="onReset(true)"
      >
        <icon fa="times" />
        Reset
      </button>
    <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        @click="onSave"
      >
      <icon fa="save" />
        Simpan
    </button>
    <button  v-if="(actionText=='Edit' && values.status=='DRAFT')" class="text-sm rounded py-2 px-2.5 text-white bg-purple-600 hover:bg-purple-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="sendApproval">
      <icon fa="location-arrow" />
      <span>send Approval</span>
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

<div v-else-if="values.tipe_bkk=='Non Buku Order'"
  class="fl ex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">BKK Non Order</h1>
        <p class="text-gray-100">Transaksi BKK Non Order</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>values.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_bkk"
        :errorText="formErrors.no_bkk?'failed':''" @input="v=>values.no_bkk=v" :hints="formErrors.no_bkk"
        label="No. BKK" placeholder="No. BKK" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tanggal"
        :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v" :hints="formErrors.tanggal"
        :check="false" type="date" label="Tgl BKK" placeholder="Pilih Tgl BKK" />
    </div>
    <div>
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/m_coa`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          //simplest:false,
          //join:true,
          // override:true,
          // where:`this.is_active=true`,
           searchfield:'this.nomor, this.nama_coa',
           selezctfield:'this.nomor, this.nama_coa',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili'
          //notin: `this.id: ${actionText=='Edit' ? [values.m_akun_pembayaran_id] : []}`, 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="nama_coa" valueField="id" :bind="{ readonly: !actionText }" :value="values.m_akun_pembayaran_id"
        @input="(v)=>values.m_akun_pembayaran_id=v" @update:valueFull="(response)=>{
        $log(response);
      }" :errorText="formErrors.m_akun_pembayaran_id?'failed':''" class="w-full !mt-3"
        :hints="formErrors.m_akun_pembayaran_id" placeholder="Pilih Akun Pembayaran" :check='false' :columns="[
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
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_reference"
        :errorText="formErrors.no_reference?'failed':''" @input="v=>values.no_reference=v"
        :hints="formErrors.no_reference" label="No. Reference" placeholder="No. Reference" :check="false" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="values.total_amt"
        :errorText="formErrors.total_amt?'failed':''" @input="v=>values.total_amt=v" :hints="formErrors.total_amt"
        label="Total Amt" placeholder="Total Amt" :check="false" />
    </div>


    <div>
      <FieldSelect :bind="{ disabled: true, clearable: false }" class="w-full !mt-3" :value="values.tipe_bkk"
        @input="v=>values.tipe_bkk=v" :errorText="formErrors.tipe_bkk?'failed':''" :hints="formErrors.tipe_bkk"
        valueField="id" displayField="key" :options="['Buku Order','Non Buku Order']" placeholder="Tipe BKK"
        :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.status"
        :errorText="formErrors.status?'failed':''" @input="v=>values.status=v" :hints="formErrors.status" label="Status"
        placeholder="Status" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" type="textarea" :value="values.keterangan"
        :errorText="formErrors.keterangan?'failed':''" @input="v=>values.keterangan=v" :hints="formErrors.keterangan"
        label="Keterangan" placeholder="Keterangan" :check="false" />
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>

  <!-- detail -->
  <div class="p-4">
    <ButtonMultiSelect @add="addDetailArr" :api="{
        url: `${store.server.url_backend}/operation/m_coa`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: true, 
            searchfield: 'this.kode, this.nama_item, this.tipe_item',
            notin: `this.id: ${detailArr.map((det)=> (det.m_coa_id))}`
            },
            onsuccess: (response) => {
              response.data = [...response.data].map((dt) => {
                return {
                  t_bkk_id: values.id || 1,
                  m_coa_id: dt.id,
                  kategori: dt['kategori.deskripsi'],
                  jenis: dt['jenis.deskripsi'],
                  nama_coa: dt['nama_coa'],
                  induk: dt['m_induk.nama_coa'],
                  nomor: dt['nomor'],
                  
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
          field: 'kategori',
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
         ]">
      <div class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300">
        <icon fa="plus" size="sm" />
        <span>Add To List</span>
      </div>
    </ButtonMultiSelect>

    <div class="mt-4">
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA]">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Kode Akun
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nama Akun
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nominal
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Detail
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
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0" :value="item.nomor"
                @input="v=>item.nomor=v" :errorText="formErrors.nomor?'failed':''" :hints="formErrors.nomor" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.nama_coa" @input="v=>item.nama_coa=v" :errorText="formErrors.nama_coa?'failed':''"
                :hints="formErrors.nama_coa" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.nominal" @input="v=>item.nominal=v" :errorText="formErrors.nominal?'failed':''"
                :hints="formErrors.nominal" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.keterangan" @input="v=>item.keterangan=v" :errorText="formErrors.keterangan?'failed':''"
                :hints="formErrors.keterangan" type="textarea" />
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
    <button  v-if="(actionText=='Edit' && values.status=='DRAFT')" class="text-sm rounded py-2 px-2.5 text-white bg-purple-600 hover:bg-purple-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="sendApproval">
      <icon fa="location-arrow" />
      <span>send Approval</span>
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