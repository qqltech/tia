@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">BERITA ACARA BUKU ORDER</h1>
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
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('APPROVED')" :class="filterButton === 'APPROVED' ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          APPROVED
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
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px] pt-2 !px-4 !pb-8">
    <template #header>
      <div class="pb-13 h-full"></div>
    </template>
  </TableApi>
</div>


</div>
@else

<!-- CONTENT -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Berita Acara Buku Order</h1>
        <p class="text-gray-100">Untuk mengatur informasi berita acara buku order pada sistem</p>
      </div>
    </div>
  </div>
  <!-- HEADER END -->

  <!-- FORM START -->
  <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row p-4 gap-3">
    <div class=" w-full !mt-3 pointer-events-none">
      <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>values.no_draft=v" :hints="formErrors.no_draft"
        placeholder="Auto Generate By System" label="No. Draft" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: true, clearable:true }" :value="values.status"
        @input="v=>values.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status"
        valueField="id" displayField="key" :options="[{'id' : 'DRAFT' , 'key' : 'DRAFT'},
          {'id' : 'IN APPROVAL', 'key' : 'IN APPROVAL'},
          {'id' : 'APPROVED', 'key' : 'APPROVED'},]" placeholder="Pilih Status" label="Status" :check="true" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.no_ba_buku_order"
        :errorText="formErrors.no_ba_buku_order?'failed':''" @input="v=>values.no_ba_buku_order=v"
        :hints="formErrors.no_ba_buku_order" placeholder="Auto Generate By System" label="No. BA Buku Order"
        :check="false" />
    </div>
    <div class="w-full !mt-3 pointer-events-none">
      <FieldX class="!mt-0" :bind="{ disabled: true, readonly: true }" :value="values.tanggal"
        :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v" :hints="formErrors.tanggal"
        placeholder="Tanggal" type="date" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" :bind="{ readonly: !actionText }" :value="values.t_buku_order_id"
        @input="(v)=>values.t_buku_order_id=v" :errorText="formErrors.t_buku_order_id?'failed':''"
        :hints="formErrors.t_buku_order_id" valueField="id" displayField="no_buku_order" :api="{
          url: `${store.server.url_backend}/operation/t_buku_order`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
          }
        }" placeholder="Pilih No. Order" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'no_buku_order',
          headerName:  'Nomor Order',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'm_customer.kode',
          headerName:  'Kode Customer',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'm_customer.nama_perusahaan',
          headerName:  'Nama Customer',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        ]" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.alasan"
        :errorText="formErrors.alasan?'failed':''" @input="v=>values.alasan=v" :hints="formErrors.alasan"
        placeholder="Alasan" :check="false" />
    </div>
    <div></div>
  </div>
  <!-- FORM END -->

  <!-- Button Form-->
  <hr>
  <div class="flex flex-row items-center justify-end space-x-2 p-2" v-show="actionText">
    <i class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
    <button class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform 
    hover:-translate-y-0.5 rounded-md p-2" @click="onReset(true)" v-show="actionText">
      <icon fa="times" />
      <span>Reset</span>
    </button>
    <button v-if="(((actionText=='Edit' || actionText=='Create' || actionText=='Copy') && (values.status=='DRAFT')))" 
    class="text-sm rounded-md py-2 px-3 text-white bg-cyan-600 hover:bg-cyan-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="sendApproval" v-show="actionText">
      <icon fa="location-arrow" />
      <span>Send Approval</span>
    </button>
    <button class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 
    transform hover:-translate-y-0.5 rounded-md p-2" @click="onSave" v-show="actionText">
      <icon fa="save" />
      <span>Simpan</span>
    </button>
  </div>
  <!-- END Button Form -->

  <!-- Button Aprroval -->
  <hr v-show="is_approval" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="is_approval">
    <button class="text-sm rounded py-2 px-2.5 text-white  bg-green-600 hover:bg-green-700 flex gap-x-1 items-center 
        transition-colors duration-300" @click="progress('APPROVED')">
      <span>Approve</span>
    </button>
    <!-- <button class="text-sm rounded py-2 px-2.5 text-white bg-orange-400 hover:bg-orange-500 flex gap-x-1 items-center
        transition-colors duration-300" @click="progress('REVISED')">
      <span>Revise</span>
    </button>
    <button class="text-sm rounded py-2 px-2.5 text-white  bg-red-600 hover:bg-red-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="progress('REJECTED')">
      <span>Reject</span>
    </button> -->
  </div>
  <!-- END Button Aprroval -->

</div>

@endverbatim
@endif