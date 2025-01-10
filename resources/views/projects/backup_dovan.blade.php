<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center px-2.5 py-1">
    <div class="flex items-center gap-x-4">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true,1)" :class="activeBtn === 1?'bg-green-600 text-white hover:bg-green-400':'border border-green-600 text-green-600 bg-white  hover:bg-green-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Active</button>
        <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
        <button @click="filterShowData(false,2)" :class="activeBtn === 2?'bg-red-600 text-white hover:bg-red-400':'border border-red-600 text-red-600 bg-white  hover:bg-red-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">InActive</button>
      </div>
    </div>
    <div>
      <RouterLink :to="`${$route.path}/create?${Date.parse(new Date())}&tipe=${tipe}`"
        class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
      <!-- <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600" @click="isModalOpen=false; setTipe('')">Cancel</button> -->
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px]">
    <template #header>
      <div class="pb-13 h-full"></div>
    </template>
  </TableApi>
</div>
<div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
  <div class="bg-white p-2 rounded-lg shadow-lg max-w-2xl w-full">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Pilih Tarif Komisi</h2>
      <hr>
    </div>
    <div class="p-1 ">
      <div class=" flex justify-center">
        <button @click="setTipe('TIA')"
          class="hover:bg-blue-100 text-blue-500 font-semibold w-50 h-50 mx-5 text-center rounded-lg"
          :class="tipe === 'TIA' ? 'bg-blue-200': 'bg-blue-50'"
          >
          <div class="h-40 w-full flex items-center justify-center">
            <icon fa="box" class="text-8xl" />
          </div>
          <div
            class="bg-blue-500 hover:bg-blue-600 text-white text-2xl font-semibold h-10 w-full rounded-b-lg flex items-center justify-center">
            <p>TIA</p>
          </div>
        </button>
        <button @click="setTipe('SUT')"
          class="hover:bg-blue-100 text-blue-500 font-semibold w-50 h-50 mx-5 text-center rounded-lg"
          :class="tipe === 'SUT' ? 'bg-blue-200': 'bg-blue-50'">
          <div class="h-40 w-full flex items-center justify-center">
            <icon fa="box-open" class="text-8xl" />
          </div>
          <div
            class="bg-blue-500 hover:bg-blue-600 text-white text-2xl font-semibold h-10 w-full rounded-b-lg flex items-center justify-center">
            <p>SUT</p>
          </div>
        </button>
      </div>
    </div>
    <!-- <div class="flex justify-end pt-4">
      <RouterLink :to="`${$route.path}/create?${Date.parse(new Date())}&tipe=${tipe}`"
        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 mr-4">
        Create</RouterLink>

      <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600" @click="isModalOpen=false; setTipe('')">Cancel</button>
    </div> -->
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
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.kode_tarif_komisi"
        :errorText="formErrors.kode_tarif_komisi?'failed':''" @input="v=>values.kode_tarif_komisi=v"
        :hints="formErrors.kode_tarif_komisi" label="Kode Tarif Komisi" placeholder="Kode Tarif Komisi" :check="false" />
    </div>

    <!-- Container Tarif 20 Coloumn -->
    <div class="w-full w-1/2 !mt-1 text-blue-600 flex items-center">
      <input type="checkbox" id="CustomStupleBox" v-model="values.c_tarif_20" style="width: 20px; height: 20px;">
      <div class="ml-2"> <!-- tambahkan margin-left untuk memberi jarak -->
          <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.c_tarif_20"
              :errorText="formErrors.c_tarif_20 ? 'failed' : ''" @input="v => values.c_tarif_20 = v"
              :hints="formErrors.c_tarif_20" label="Container Tarif 20" placeholder="Container Tarif 20" :check="false" />
      </div>
    </div>

    <!-- Container Tarif 40 Coloumn -->
    <div class="w-full w-1/2 !mt-1 text-blue-600 flex items-center">
      <input type="checkbox" id="CustomStupleBox" v-model="values.c_tarif_40" style="width: 20px; height: 20px;">
      <div class="ml-2"> <!-- tambahkan margin-left untuk memberi jarak -->
          <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.c_tarif_40"
              :errorText="formErrors.c_tarif_40 ? 'failed' : ''" @input="v => values.c_tarif_40 = v"
              :hints="formErrors.c_tarif_40" label="Container Tarif 40" placeholder="Container Tarif 40" :check="false" />
      </div>
    </div>

    <!-- Tarif Dokumen Coloumn -->
    <div class="w-full w-1/2 !mt-1 text-blue-600 flex items-center">
      <input type="checkbox" id="CustomStupleBox" v-model="values.tarif_dokumen" style="width: 20px; height: 20px;">
      <div class="ml-2"> <!-- tambahkan margin-left untuk memberi jarak -->
          <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_dokumen"
              :errorText="formErrors.tarif_dokumen ? 'failed' : ''" @input="v => values.tarif_dokumen = v"
              :hints="formErrors.tarif_dokumen" label="Tarif Dokumen" placeholder="Tarif Dokumen" :check="false" />
      </div>
    </div>
    
    <!-- Tarif Order Coloumn -->
    <div class="w-full w-1/2 !mt-1 text-blue-600 flex items-center">
      <input type="checkbox" id="CustomStupleBox" v-model="values.tarif_order" style="width: 20px; height: 20px;">
      <div class="ml-2"> <!-- tambahkan margin-left untuk memberi jarak -->
          <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_order"
              :errorText="formErrors.tarif_order ? 'failed' : ''" @input="v => values.tarif_order = v"
              :hints="formErrors.tarif_order" label="Tarif Order" placeholder="Tarif Order" :check="false" />
      </div>
    </div>
    <!-- Invoice Minimal Coloumn -->
    <div class="w-full w-1/2 !mt-1 text-blue-600 flex items-center">
      <input type="checkbox" id="CustomStupleBox" v-model="values.invoice_minimal" style="width: 20px; height: 20px;">
      <div class="ml-2"> <!-- tambahkan margin-left untuk memberi jarak -->
          <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.invoice_minimal"
              :errorText="formErrors.invoice_minimal ? 'failed' : ''" @input="v => values.invoice_minimal = v"
              :hints="formErrors.invoice_minimal" label="Invoice Minimal" placeholder="Invoice Minimal" :check="false" />
      </div>
    </div>

    <!-- Tarif UMKM -->
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tarif_umkm"
        :errorText="formErrors.tarif_umkm?'failed':''" @input="v=>values.tarif_umkm=v"
        :hints="formErrors.tarif_umkm" label="Tarif UMKM" placeholder="Tarif UMKM" :check="false" />
    </div>

    <!-- Tarif UMKM -->
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.selisih_ppn_pph"
        :errorText="formErrors.selisih_ppn_pph?'failed':''" @input="v=>values.selisih_ppn_pph=v"
        :hints="formErrors.selisih_ppn_pph" label="Selisih PPN/PPH" placeholder="Selisih PPN/PPH" :check="false" />
    </div>

    <!-- Tarif UMKM -->
    <div>
      <FieldX :bind="{ readonly: !actionText }" type="textarea" class="w-full !mt-3" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v"
        :hints="formErrors.catatan" label="Catatan" placeholder="Catatan" :check="false" />
    </div>

    <!-- Status Column -->
    

    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>
  <!-- Status -->
  <div class="flex flex-col gap-2 p-4">
      <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer font-semibold"
            for="is_active_for_click"
            >Status :</label>
      <div class="flex w-40">
        <div class="flex-auto">
          <i class="text-red-500">InActive</i>
        </div>
        <div class="flex-auto">
          <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                role="switch"
                id="is_active_for_click"
                :disabled="!actionText"
                v-model="values.is_active"
                />
        </div>
        <div class="flex-auto">
          <i class="text-green-500">Active</i>
        </div>
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