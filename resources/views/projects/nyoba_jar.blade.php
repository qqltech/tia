@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[670px] border-t-10 border-blue-500 dark:bg-black">
  <div class="flex justify-between items-center p-2">
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


  </div>
  <hr>

  <!-- TABLE -->
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[550px] pt-2 !px-4 !pb-8">
    <template #header>
      <!-- ACTION BUTTON -->
      <div class="flex items-center gap-x-4">
        <RouterLink :to="$route.path + '/create?' + (Date.parse(new Date()))" class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white 
                        text-sm rounded-md py-1 px-2.5 transition-colors duration-300">
          Create New
        </RouterLink>
      </div>
    </template>
  </TableApi>
</div>

</div>
@else

@verbatim
<div class="flex flex-col gap-y-3 rounded-t-md bg-white">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-blue-300" title="Kembali" @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form COA</h1>
        <p class="text-gray-100"></p>
      </div>
    </div>
  </div>
  <div class="flex gap-x-4 px-2 p-4">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">

      <!-- NOMOR -->


         <!-- KATEGORI -->
        <div class="p-2">
          <span class="text-xl font-semibold">Kategori</span>
          <FieldSelect :bind="{ disabled: !actionText, clearable: true }" class="w-1/2  !mt-3"
            :value="values.kategori" :errorText="formErrors.kategori?'failed':''"
            :hints="formErrors.kategori" valueField="id" displayField="deskripsi"
            @input="v=>{
            if(v){
              values.kategori=v
            }else{
              values.kategori=null
              values.m_induk_id=null
              values.no_induk=null
              values.nama_coa=null
            }
            }"
             :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                where:`this.group='KATEGORI COA'`,
                simplest:true,
              }
          }" placeholder="Pilih Kategori" fa-icon="sort-desc" label="" :check="false" />
        </div>

        <div class="flex items-center mt-5">
          <div class="flex space-x-5">
            <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer font-semibold"
            for="induk_for_click"
            >Parent Induk :</label>
            <div class="flex w-40 space-x-5">
              <div class="flex justify-center items-center space-x-3">
                <input
                          class="h-6 w-6 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                          type="checkbox"
                          id="induk_for_click"
                          :disabled="!actionText"
                          v-model="values.induk"
                          @change="changeParent"
                        />
              </div>
              <div class="flex items-center justify-start">
                <i class="text-green-500">IYA</i>
              </div>
            </div>
          </div>
        </div>


      <div class="grid <md:grid-cols-1 grid-cols-3 gap-2">
        <!-- START COLUMN -->
<div v-show="values.induk === true"> 
  <FieldPopup
    label="Induk"
    :bind="{ disabled: !actionText , readonly: !actionText , clearable: true }"
    class="w-full !mt-3" 
    valueField="id" 
    displayField="nama_coa"
    :value="values.m_induk_id" 
    @input="v => {
      if (v) {
        values.m_induk_id = v;
      } else {
        values.m_induk_id = null;
        values.no_induk = null;
        values.nama_coa = null;
      }
    }"
    @update:valueFull="v => {
      if (v) {
        values.no_induk = v.nomor;
        values.level = v.level + 1;
        values.nama_coa = v.nama_coa;
      } else {
        values.level = v.level;
        values.no_induk = null;
        values.nama_coa = null;
      }
    }"
    :api="{
      url: `${store.server.url_backend}/operation/m_coa`,
      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
      params: {
        where: `this.kategori = ${values.kategori ?? 0}`,
        join: true,
        simplest: true,
      }
    }"
    placeholder="Pilih Induk"
    :check="false" 
    :columns="[{
      headerName: 'No',
      valueGetter: (p) => p.node.rowIndex + 1,
      width: 60,
      sortable: false, resizable: false, filter: false,
      cellClass: ['justify-center', 'bg-gray-50']
    },
    {
      flex: 1,
      field: 'nomor',
      headerName:  'Nomor Induk',
      sortable: false, resizable: true, filter: 'ColFilter',
      cellClass: ['border-r', '!border-gray-200', 'justify-start']
    },
    {
      flex: 1,
      field: 'nama_coa',
      headerName:  'Nama',
      sortable: false, resizable: true, filter: 'ColFilter',
      cellClass: ['border-r', '!border-gray-200', 'justify-start']
    },
    ]"
  />
</div>

<div class="flex space-x-1">
        <FieldX v-show="values.induk" :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.no_induk" :errorText="formErrors.no_induk?'failed':''"
          @input="v=>values.no_induk=v" :hints="formErrors.no_induk" 
          :check="false"
          label="Nomor Parent"
          placeholder="Autofield Nomor Parent"
        />
        <span class="flex items-center" v-show="values.induk" > - </span>
        <FieldX :bind="{ readonly: !actionText }" :class="{'col-span-2':!values.is_parent}" class="w-full !mt-3"
          :value="values.tempNomor" :errorText="formErrors.nomor?'failed':''"
          @input="v=>{
            if(v===''){
              values.nomor=null
            }
            values.tempNomor=v}" 
            :hints="formErrors.nomor" 
          :check="true"
          type="number"
          label="Nomor"
          placeholder="Masukan Nomor"
        />
      </div>

        <div v-show="values.induk === true">
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.nama"
            :errorText="formErrors.nama?'failed':''" @input="v=>values.nama=v" :hints="formErrors.nama"
            :check="false" label="Nama" placeholder="Tuliskan Nama Parent" />
        </div>
        <!-- NAMA -->
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.nama_coa"
            :errorText="formErrors.nama_coa?'failed':''" @input="v=>values.nama_coa=v" :hints="formErrors.nama_coa"
            :check="false" label="Nama Coa" placeholder="Tuliskan Nama Coa" />
        </div>
        <!-- level -->
        <div>
        <FieldX class="w-full !mt-3" :bind="{ readonly : true }" :value="values.level"
        :errorText="formErrors.level?'failed':''" @input="v=>values.level=v" :hints="formErrors.level" :check="false" 
        label="Level" placeholder="Level"/>
        </div>
        <!-- JENIS -->
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="w-full  !mt-3" :value="values.jenis"
            @input="v=>values.jenis=v" :errorText="formErrors.jenis?'failed':''" :hints="formErrors.jenis"
            valueField="id" displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                where:`this.group='JENIS COA'`,
                simplest:true,
              }
          }" placeholder="Pilih jenis" fa-icon="sort-desc" label="jenis" :check="false" />
        </div>
        <!-- Debit -->
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="w-full  !mt-3"
            :value="values.debit_kredit" @input="v=>values.debit_kredit=v"
            :errorText="formErrors.debit_kredit?'failed':''" :hints="formErrors.debit_kredit" valueField="key"
            displayField="key" :options="[{'key': 'DEBIT'}, {'key': 'KREDIT'},]" placeholder="Pilih Debit / Kredit"
            fa-icon="sort-desc" label="Debit / Kredit" :check="false" />
        </div>
        <!-- Catatan -->
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full  !mt-3" :value="values.catatan"
            :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
            :check="false" label="Catatan" placeholder="Masukan catatan" type="textarea" />
        </div>

        <!-- PERKIRAAN -->
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="w-full  !mt-3"
            :value="values.tipe_perkiraan" @input="v=>values.tipe_perkiraan=v"
            :errorText="formErrors.tipe_perkiraan?'failed':''" :hints="formErrors.tipe_perkiraan" valueField="id"
            displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                where:`this.group='TIPE PERKIRAAN COA'`,
                simplest:true,
              }
          }" placeholder="Pilih Tipe Perkiraan" fa-icon="sort-desc" label="Tipe Perkiraan" :check="false" />
        </div>
      </div>





      <div class="pt-5">
        <div class="flex flex-col gap-2 mt-10">
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