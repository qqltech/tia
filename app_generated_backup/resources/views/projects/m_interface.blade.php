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
        <h1 class="text-20px font-bold">Form Interface</h1>
        <p class="text-gray-100"></p>
      </div>
    </div>
  </div>
  <!-- HEADER END -->

  <!-- FORM START -->
  <!-- DIVISI -->
  <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row gap-x-4 gap-y-4 mb-5 pt-4 p-4">

    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" displayField="deskripsi" valueField="id"
        :bind="{ disabled: !actionText , readonly: !actionText }" :value="values.divisi" @input="(v)=>values.divisi=v"
        :errorText="formErrors.divisi?'failed':''" class="w-full !mt-3" :hints="formErrors.divisi"
        placeholder="Pilih Divisi" label="Divisi" :check="false" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              where:`this.group='DIVISI INTERFACE'`,

            },
            onsuccess: (response) => {
              return response;
            }
          }" />
    </div>
    <!-- TIPE -->
    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" displayField="deskripsi" valueField="id"
        :bind="{ disabled: !actionText , readonly: !actionText }" :value="values.tipe" @input="(v)=>values.tipe=v"
        :errorText="formErrors.tipe?'failed':''" class="w-full !mt-3" :hints="formErrors.tipe" placeholder="Pilih Tipe"
        label="Tipe" :check="false" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              where:`this.group='TIPE INTERFACE'`

            },
            onsuccess: (response) => {
              return response;
            }
          }" />
    </div>
    <!-- Catatan -->
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        type="textarea" placeholder="Catatan" label="Catatan" :check="false" />
    </div>
    <!-- VARIABLE -->
    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" displayField="deskripsi" valueField="id"
        :bind="{ disabled: !actionText , readonly: !actionText }" :value="values.variable"
        @input="(v)=>values.variable=v" :errorText="formErrors.variable?'failed':''" class="w-full !mt-3"
        :hints="formErrors.variable" placeholder="Pilih Variable" label="Variable" :check="false" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              where:`this.group='VARIABLE INTERFACE'`

            },
            onsuccess: (response) => {
              return response;
            }
          }" />
    </div>
    <!-- Group -->
    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" displayField="deskripsi" valueField="id"
        :bind="{ disabled: !actionText , readonly: !actionText }" :value="values.grp" @input="(v)=>values.grp=v"
        :errorText="formErrors.grp?'failed':''" class="w-full !mt-3" :hints="formErrors.grp"
        placeholder="Pilih Group" label="Group" :check="false" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              where:`this.group='GROUP INTERFACE'`

            },
            onsuccess: (response) => {
              return response;
            }
          }" />
    </div>
    <!-- STATUS -->
    <div> </div>
    <div class="flex flex-col gap-2">
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
  </div>

  <!-- START TABLE DETAIL -->
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

  <hr class="w-full">

  <div class="p-4">
    <span class="text-xl font-semibold">Detail Interface</span>
    <div class=" space-x-5">
      <button v-show="actionText" class="mt-5 rounded bg-blue-500 text-white hover:bg-blue-600 duration-300 px-4 py-2 font-semibold" @click="addItem"> + Tambah Detail</button>
      <button v-if="selectedItems.length > 0" @click="removeSelectedDetails" type="button" class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5 ml-2">
              <icon fa="trash" />
              Hapus yang Dipilih
            </button>
    </div>
    <div v-for="(item, i) in detailArr" :key="i" class="border rounded-lg p-4 my-2">
      <div class="font-bold">No. {{i+1}}</div>
      <div class="flex justify-between items-center space-x-6">
        <input v-show="actionText" type="checkbox" class="h-6 w-6" v-model="selectedItems" :value="i" />
        <button v-show="actionText" class="rounded px-4 py-2 border border-red-300 hover:bg-gray-100" @click="removeDetail(i)">
              <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
              </svg>
          </button>
      </div>
      <div class="grid grid-cols-3 gap-3">

        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3 w-full" 
          :value="item.kode"
           @input="v=>item.kode=v" 
           :hints="formErrors.kode"
            label="kode" placeholder="kode" :check="false" />
        </div>

        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3 w-full" 
          :value="item.nama"
           @input="v=>item.nama=v" 
           :hints="formErrors.nama"
            label="Nama" placeholder="Nama" :check="false" />
        </div>

        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3 w-full" 
          :value="item.catatan"
           @input="v=>item.catatan=v" 
           :hints="formErrors.catatan"
            label="Catatan" placeholder="Catatan" :check="false" />
        </div>

      </div>
    </div>
  </div>

  @endverbatim
  @endif