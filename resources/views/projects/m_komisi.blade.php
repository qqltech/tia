<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('Active')" :class="filterButton === 'Active' ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          Active
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('InActive')" :class="filterButton === 'InActive' ? 'bg-red-600 text-white hover:bg-red-600' 
          : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          InActive
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
      <h2 class="text-xl font-semibold">Pilih Tipe Tarif Komisi</h2>
      <hr>
    </div>
    <div class="p-1 ">
      <div class=" flex justify-center">
        <button @click="setTipe('TIA')"
          class="hover:bg-blue-100 text-blue-500 font-semibold w-50 h-50 mx-5 text-center rounded-lg"
          :class="tipe === 'TIA' ? 'bg-blue-200': 'bg-blue-50'">
          <div class="h-40 w-full flex items-center justify-center">
            <icon fa="box-open" class="text-8xl" />
          </div>
          <div
            class="bg-blue-500 hover:bg-blue-600 text-white text-2xl font-semibold h-10 w-full rounded-b-lg flex items-center justify-center">
            <p>TIA</p>
          </div>
        </button>
        <button @click="setTipe('SUT')"
          class="hover:bg-blue-100 text-blue-500 font-semibold w-50 h-50 mx-5 text-center rounded-lg"
          :class="tipe === 'SUT' ? 'bg-blue-200': 'bg-blue-50'"
          >
          <div class="h-40 w-full flex items-center justify-center">
            <icon fa="boxes-stacked" class="text-8xl" />
          </div>
          <div
            class="bg-blue-500 hover:bg-blue-600 text-white text-2xl font-semibold h-10 w-full rounded-b-lg flex items-center justify-center">
            <p>SUT</p>
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
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Tarif Komisi</h1>
        <p class="text-gray-100">Form unutk Pengisian Tarif Komisi</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <!-- No Tarif Komisi Column -->
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.kode"
        :errorText="formErrors.kode?'failed':''" @input="v=>data.kode=v" :hints="formErrors.kode"
        label="Kode Tarif Komisi" placeholder="Kode Tarif Komisi" :check="false" />
    </div>

    <div>
      <FieldX class="w-full !mt-3" :bind="{ readonly: true }" :value="data.tipe_komisi"
        :errorText="formErrors.tipe_komisi?'failed':''" @input="v=>data.tipe_komisi=v" :hints="formErrors.tipe_komisi"
        label="Tipe Tarif Komisi" placeholder="Tipe Tarif Komisi" :check="false" />
    </div>

    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:false }" :value="data.is_active"
        @input="v=>data.is_active=v" :errorText="formErrors.is_active?'failed':''" :hints="formErrors.is_active"
        valueField="id" displayField="key" :options="[
              { 'id': 1, 'key': 'Active' },
              { 'id': 0, 'key': 'InActive' }
        ]" placeholder="Status" :check="true" />
    </div>

    <div v-if="data.tipe === 'TIA'">
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:true }" :value="data.tipe_order"
        @input="v=>data.tipe_order=v" :errorText="formErrors.tipe_order?'failed':''" :hints="formErrors.tipe_order"
        valueField="id" displayField="deskripsi" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              where: `this.group='TIPE ORDER' and this.is_active=true`
            }
        }" placeholder="Tipe Order" :check="true" />
    </div>

    <div>
      <FieldPopup class="w-full !mt-3" :bind="{ readonly: !actionText }" :value="data.m_customer_id"
        @input="(v)=>data.m_customer_id=v" :errorText="formErrors.m_customer_id?'failed':''"
        :hints="formErrors.m_customer_id" valueField="id" displayField="nama_perusahaan" :api="{
          url: `${store.server.url_backend}/operation/m_customer`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            where: `this.is_active=true`,
            searchfield: 'this.id, this.kode, this.nama_perusahaan, this.alamat',
          },
          onsuccess(response) {
            response.page = response.current_page
            response.hasNext = response.has_next
            return response
          }
        }" placeholder="Pilih Customer" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'kode',
          headerName:  'Kode Customer',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'nama_perusahaan',
          headerName:  'Nama Customer',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'alamat',
          headerName:  'Alamat',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        ]" />
    </div>

    <!-- Container Tarif 20 Coloumn -->
    <div class="flex items-center gap-x-2">
      <input
          :bind="{ readonly: !actionText }"
          class="!mt-3"
          type="checkbox"
          id="CustomStupleBox"
          v-model="data.is_container_tarif_20"
          style="width: 20px; height: 20px;"
        />
      <FieldNumber :bind="{ readonly: !actionText || !data.is_container_tarif_20 }" class="w-full !mt-3" :value="data.container_tarif_20"
        :errorText="formErrors.container_tarif_20 ? 'failed' : ''" @input="v => data.container_tarif_20 = v"
        :hints="formErrors.container_tarif_20" label="Container Tarif 20" placeholder="Container Tarif 20"
        :check="false" />
    </div>


    <!-- Container Tarif 40 Coloumn -->
    <div class="flex items-center gap-x-2">
      <input
          :bind="{ readonly: !actionText }"
          class="!mt-3"
          type="checkbox"
          id="CustomStupleBox"
          v-model="data.is_container_tarif_40"
          style="width: 20px; height: 20px;"
        />
      <FieldNumber :bind="{ readonly: !actionText || !data.is_container_tarif_40 }" class="w-full !mt-3" :value="data.container_tarif_40"
        :errorText="formErrors.container_tarif_40 ? 'failed' : ''" @input="v => data.container_tarif_40 = v"
        :hints="formErrors.container_tarif_40" label="Container Tarif 40" placeholder="Container Tarif 40"
        :check="false" />
    </div>

    <!-- Tarif Dokumen Coloumn -->
    <div class="flex items-center gap-x-2">
      <input
          :bind="{ readonly: !actionText }"
          class="!mt-3"
          type="checkbox"
          id="CustomStupleBox"
          v-model="data.is_tarif_dokumen"
          style="width: 20px; height: 20px;"
        />
      <FieldNumber :bind="{ readonly: !actionText || !data.is_tarif_dokumen }" class="w-full !mt-3" :value="data.tarif_dokumen"
        :errorText="formErrors.tarif_dokumen ? 'failed' : ''" @input="v => data.tarif_dokumen = v"
        :hints="formErrors.tarif_dokumen" label="Tarif Dokumen" placeholder="Tarif Dokumen" :check="false" />
    </div>

    <!-- Tarif Order Coloumn -->
    <div class="flex items-center gap-x-2">
      <input
          :bind="{ readonly: !actionText }"
          class="!mt-3"
          type="checkbox"
          id="CustomStupleBox"
          v-model="data.is_tarif_order"
          style="width: 20px; height: 20px;"
        />
      <FieldNumber :bind="{ readonly: !actionText || !data.is_tarif_order }" class="w-full !mt-3" :value="data.tarif_order"
        :errorText="formErrors.tarif_order ? 'failed' : ''" @input="v => data.tarif_order = v"
        :hints="formErrors.tarif_order" label="Tarif Dokumen" placeholder="Tarif Dokumen" :check="false" />
    </div>

    <!-- Invoice Minimal Coloumn -->
    <div class="flex items-center gap-x-2" v-if="data.tipe === 'SUT'">
      <input
          :bind="{ readonly: !actionText }"
          class="!mt-3"
          type="checkbox"
          id="CustomStupleBox"
          v-model="data.is_invoice_minimal"
          style="width: 20px; height: 20px;"
        />
      <FieldNumber :bind="{ readonly: !actionText || !data.is_invoice_minimal }" class="w-full !mt-3" :value="data.invoice_minimal"
        :errorText="formErrors.invoice_minimal ? 'failed' : ''" @input="v => data.invoice_minimal = v"
        :hints="formErrors.invoice_minimal" label="Tarif Dokumen" placeholder="Tarif Dokumen" :check="false" />
    </div>

    <!-- Tarif UMKM -->
    <div class="flex items-center space-x" v-if="data.tipe === 'SUT'">
      <FieldNumber :bind="{ readonly: !actionText }" class="w-[90%] !mt-3" :value="data.tarif_umkm"
        :errorText="formErrors.tarif_umkm?'failed':''" @input="v=>data.tarif_umkm=v" :hints="formErrors.tarif_umkm"
        label="Tarif UMKM" placeholder="Tarif UMKM" :check="false" />

      <div
        class="border text-xs rounded w-[45px] h-[34px] text-gray-400 bg-gray-100 flex justify-center items-center !mt-3">
        %
      </div>
    </div>

    <!-- Tarif UMKM -->
    <div class="flex items-center space-x" v-if="data.tipe === 'SUT'">
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.selisih_ppn_pph"
        :errorText="formErrors.selisih_ppn_pph?'failed':''" @input="v=>data.selisih_ppn_pph=v"
        :hints="formErrors.selisih_ppn_pph" label="Selisih PPN/PPH" placeholder="Selisih PPN/PPH" :check="false" />

      <div
        class="border text-xs rounded w-[45px] h-[34px] text-gray-400 bg-gray-100 flex justify-center items-center !mt-3">
        %
      </div>
    </div>

    <!-- Kurs -->
    <div v-if="data.tipe === 'SUT'">
      <FieldNumber class="w-full !mt-3" :bind="{ readonly: !actionText }" :value="data.kurs" @input="(v)=>data.kurs=v"
        :errorText="formErrors.kurs?'failed':''" :hints="formErrors.kurs" placeholder="Kurs" :check="false" />
    </div>

    <!-- Tarif UMKM -->
    <div>
      <FieldX :bind="{ readonly: !actionText }" type="textarea" class="w-full !mt-3" :value="data.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>data.catatan=v" :hints="formErrors.catatan"
        label="Catatan" placeholder="Catatan" :check="false" />
    </div>

    <!-- Status Column -->


    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
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