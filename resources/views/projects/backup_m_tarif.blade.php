<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
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
<div class="sticky flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Tarif</h1>
        <p class="text-gray-100">Untuk mengatur informasi tarif pada sistem</p>
      </div>
    </div>
  </div>
  <!-- HEADER END -->

  <!-- FORM START -->
  <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row gap-x-4 gap-y-4 p-4">
    <div class=" w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText, disabled:!actionText }" :value="values.no_tarif"
        :errorText="formErrors.no_tarif?'failed':''" @input="v=>values.no_tarif=v" :hints="formErrors.no_tarif"
        placeholder="Kode Tarif" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, clearable:true }" :value="values.tipe_tarif"
        @input="v => values.tipe_tarif = v" :errorText="formErrors.tipe_tarif ? 'failed' : ''"
        :hints="formErrors.tipe_tarif" valueField="id" displayField="key"
        :options="[{'id' : 'Eksport' , 'key' : 'Eksport'}, {'id' : 'Import', 'key' : 'Import'}, {'id' : 'Eksport S', 'key' : 'Eksport S'}, {'id' : 'Lokal', 'key' : 'Lokal'},{'id' : 'OL', 'key' : 'OL'},{'id' : 'OLS', 'key' : 'OLS'}]"
        label="Tipe Tarif" placeholder="Pilih Eksport/Import" :check="true" />
    </div>

    <div class=" w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, clearable:true }" :value="values.ukuran_kontainer"
        @input="v=>values.ukuran_kontainer=v" :errorText="formErrors.ukuran_kontainer?'failed':''"
        :hints="formErrors.ukuran_kontainer" valueField="id" displayField="deskripsi" :api="{
                url: `${store.server.url_backend}/operation/m_general`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest: true,
                  where: `this.group='UKURAN KONTAINER'`
                }
            }" label="Ukuran Kontainer" placeholder="Pilih Ukuran Kontainer" :check="true" />
    </div>
    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" :bind="{ readonly: !values.tipe_tarif || !actionText }" :api="{
                url: `${store.server.url_backend}/operation/m_customer`,
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                params: {
                  simplest:false,
                  scopes: 'WithTarifPPJK',
                  tipe_tarif: values.tipe_tarif,
                  searchfield: 'this.kode, this.nama_perusahaan'
                },
                onsuccess: (response) => {
                  response.page = response.current_page
                  response.hasNext = response.has_next
                  return response;
                }
              }" displayField="nama_perusahaan" valueField="id" :bind="{ readonly: !actionText }"
        :value="values.m_customer_id" @input="v=>{
                if(v){
                values.m_customer_id=v
                }else{
                values.m_customer_id=null
                }
                values.tarif_ppjk=null
                values.kode=null
                values.tarif_sewa=null
                values.tarif_sewa_diskon=null
        }" @update:valueFull="(response) => {
          if (response)
              values.kode = response?.kode || '';
                if (response?.m_customer) {
                  values.m_customer.kode = response.m_customer?.kode || '';
                }
              values.is_in_tarif = response.is_in_tarif
              values.tarif_ppjk = parseFloat(response.tarif_ppjk)
              $log(response);
        }" :errorText="formErrors.m_customer_id?'failed':''" class="w-full !mt-3" :hints="formErrors.m_customer_id"
        placeholder="Pilih Customer" :check='false' :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                headerName: 'Kode Customer',
                cellClass: ['justify-center', 'border-r', '!border-gray-200',],
                field: 'kode',
                sortable: false, resizable: true, filter: 'ColFilter',
              },
              {
                flex: 1,
                headerName: 'Nama',
                cellClass: ['justify-center', 'border-r', '!border-gray-200',],
                field: 'nama_perusahaan',
                sortable: false, resizable: true, filter: 'ColFilter',
              },
              {
                flex: 1,
                headerName: 'Tarif PPJK',
                cellClass: ['justify-center', 'border-r', '!border-gray-200'],
                field: 'tarif_ppjk',
                sortable: false,
                resizable: true,
                filter: 'ColFilter',
                cellRenderer: function (params) {
                  return formatRupiah(params.value);
                }
              }
            ]" />
    </div>
    <div class=" w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: true }" :value="values.kode" :errorText="formErrors.kode?'failed':''"
        @input="v=>values.kode=v" :hints="formErrors.kode" placeholder="Kode Customer" :check="false" />
    </div>
    <div class=" w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, clearable:true }" :value="values.jenis"
        @input="v=>values.jenis=v" :errorText="formErrors.jenis?'failed':''" :hints="formErrors.jenis" valueField="id"
        displayField="deskripsi" :api="{
                url: `${store.server.url_backend}/operation/m_general`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  where: `this.group='JENIS KONTAINER'`
                }
            }" placeholder="Pilih Jenis Kontainer" label="Jenis Kontainer" :check="true" />
    </div>
    <div class=" w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, clearable:true }" :value="values.sektor"
        @input="v=>values.sektor=v" :errorText="formErrors.sektor?'failed':''" :hints="formErrors.sektor"
        valueField="id" displayField="deskripsi" :api="{
                url: `${store.server.url_backend}/operation/m_general`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  where: `this.group='SEKTOR'`
                },
                onsuccess: (response) => {
                  return {
                    ...response,
                    page: response.current_page,
                    hasNext: response.has_next,
                  };
                }
            }" placeholder="Pilih Jenis Sektor" label="Sektor" :check="true" />
    </div>
    <div class=" w-full !mt-3">
      <FieldNumber class="!mt-0" :bind="{ readonly: !actionText }" :value="values.tarif_sewa"
        @input="(v)=>values.tarif_sewa=v" :errorText="formErrors.tarif_sewa?'failed':''" :hints="formErrors.tarif_sewa"
        placeholder="Tarif Jasa EMKL" :check="false" />
    </div>
    <div class=" w-full !mt-3">
      <FieldNumber class="!mt-0" :bind="{ readonly: (!values.tarif_sewa) || !actionText }"
        :value="values.tarif_sewa_diskon" @input="(v)=>values.tarif_sewa_diskon=v"
        :errorText="formErrors.tarif_sewa_diskon?'failed':''" :hints="formErrors.tarif_sewa_diskon"
        placeholder="Tarif Jasa EMKL Kontainer 2" :check="false" />
    </div>
    <div class=" w-full !mt-3">
      <FieldNumber class="!mt-0" :bind="{ 
          readonly: actionText === 'Edit' ? false : actionText === 'Copy' ? true : values.is_in_tarif === undefined ? true : 
            values.is_in_tarif ? true :  
              values.tarif_sewa_diskon ? false : true
        }" :value="values.tarif_ppjk" @input="(v)=>values.tarif_ppjk=v" :errorText="formErrors.tarif_ppjk?'failed':''"
        :hints="formErrors.tarif_ppjk" placeholder="Tarif PPJK" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        type="textarea" placeholder="Catatan" :check="false" />
    </div>
    <div>
      <FieldUpload :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tt_elektronik"
        @input="(v)=>values.tt_elektronik=v" :maxSize="10"
        :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
            url: `${store.server.url_backend}/operation/${endpointApi}/upload`,
            headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { field: 'tt_elektronik' },
            onsuccess: response=> {
              return response
            },
            onerror:(error)=>{
              swal.fire({ icon: 'error', text: error });
            },
           }" :hints="formErrors.tt_elektronik" :errorText="formErrors.tt_elektronik?'failed':''"
        placeholder="Tanda Tangan" accept="image/*" :check="false" />
    </div>
  </div>
  <div class="flex flex-col gap-2 p-4">
    <label class="text-gray-600 text-xs font-semibold">Status</label>
    <div class="flex gap-2">
      <div class="relative">
        <input class="relative h-[16px] w-7 p-px appearance-none rounded-full bg-white border disabled:!cursor-default
            hover:cursor-pointer after:content-[''] after:h-[10.5px] after:w-[10.5px] after:rounded-full after:border-none
            after:absolute after:mt-[0.9px] focus:outline-none after:ml-[0.95px] checked:after:right-[1.25px] disabled:opacity-75
            after:bg-red-600 border-red-600 checked:after:bg-green-600 checked:border-green-600"
            type="checkbox" role="switch" :disabled="!actionText" v-model="values.is_active" />
      </div>
      <div :class="(values.is_active ? 'text-green-600' : 'text-red-600') + ' text-xs'">
        {{values.is_active ? 'Active' : 'InActive' }}
      </div>
    </div>
  </div>


  <!-- START TABLE DETAIL TARIF EMKL -->
  <hr class="<md:col-span-1 col-span-3">
  <div class="flex items-stretch lg:w-[40%] text-sm overflow-x-auto <md:col-span-1 col-span-3">
    <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 0}"
            @click="activeTabIndex = 0"
          >
            Tarif EMKL
          </button>
    <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 1}"
            @click="activeTabIndex = 1"
          >
            Tarif Lain-Lain
          </button>
  </div>
  <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 " v-if="activeTabIndex === 0">
    <div class="col-span-3 mt-3">
      <ButtonMultiSelect title="Add to list" @add="onDetailAdd" :api="{
              url: `${store.server.url_backend}/operation/m_jasa`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: { simplest: true },
              onsuccess:(res)=>{
                res.data = res.data.map((dt)=>({
                ...dt, is_active: dt.is_active ? 1 : 0, lokasi_stuff: dt.id
                }))
                return res;
              }
            }" :columns="[{
              checkboxSelection: true,
              headerCheckboxSelection: true,
              headerName: 'No',
              valueGetter:p=>'',
              width:60,
              sortable: false, resizable: true, filter: false,
              cellClass: ['justify-center', 'bg-gray-50', '!border-gray-200']
            },
            {
              flex: 1,
              headerName:'Kode Jasa',
              sortable: false, resizable: true, filter: 'ColFilter',
              field: 'kode_jasa',
              cellClass: ['justify-center','!border-gray-200']
            },
            {
              flex: 1,
              headerName:'Nama Jasa',
              sortable: false, resizable: true, filter: 'ColFilter',
              field: 'nama_jasa',
              cellClass: ['justify-center','!border-gray-200']
            },
            ]">
        <div
          class="bg-blue-600 text-sm text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
          <icon fa="plus" size="sm mr-0.5" /> Add to list
        </div>
      </ButtonMultiSelect>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
      <table class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[2%] border bg-[#f8f8f8] border-[#CACACA]">
              No.</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Kode Jasa</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nama Jasa</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Tarif</td>
            <!-- <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Catatan</td> -->
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              PPN</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[2%] border bg-[#f8f8f8] border-[#CACACA]">
              Action</td>
          </tr>
        </thead>
        <tbody>
          <tr v-if="detailArr.length === 0" class="text-center">
            <td colspan="6" class="py-[20px]">No data to show</td>
          </tr>
          <tr v-else v-for="(item, index) in detailArr" :key="index" class="border">
            <td class="p-2 text-center border border-[#CACACA]">{{ index + 1 }}</td>
            <td class="p-2 text-center border border-[#CACACA]">{{item.kode}}</td>
            <td class="p-2 text-center border border-[#CACACA]">{{item.nama_jasa}}</td>
            <td class="p-2 text-center border border-[#CACACA] flex items-center justify-center">
              <FieldNumber class="!mt-0" :bind="{ readonly: !actionText }" :value="item.tarif"
                @input="(v)=>item.tarif=v" :errorText="formErrors.tarif?'failed':''" :hints="formErrors.tarif" label=""
                placeholder="Masukkan Harga Tarif" :check="false" />

            </td>
            <!-- <td class="p-2 border border-[#CACACA] text-center max-w-52 truncate">
              <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="item.catatan"
                :errorText="formErrors.catatan?'failed':''" @input="v=>item.catatan=v" :hints="formErrors.catatan"
                placeholder="Catatan" label="" :check="false" />
            </td> -->
            <td class="p-2 text-center border border-[#CACACA]">
              <input
                  type="checkbox"
                  class="h-5 w-5 text-blue-500 rounded"
                  v-model="item.ppn"
                >
            </td>
            <td class="p-2 border border-[#CACACA] text-center">
              <button type="button" @click="removeDetail(index)" :disabled="!actionText">
                  <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                  </svg>
                </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- END TABLE DETAIL -->

  <!-- START TARIF LAIN-LAIN -->
  <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 " v-if="activeTabIndex === 1">
    <div class="!mt-3">
      <button :disabled="!actionText" @click="addDetailLL" type="button" class="bg-blue-600 text-white font-semibold 
            hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
              <icon fa="plus" size="sm mr-0.5"/>
              Add to list
        </button>
    </div>
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Deskripsi</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Satuan</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nominal</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              Action</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArrLL" :key="i" class="border-t" v-if="detailArrLL.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              <FieldX :bind="{ readonly: !actionText }" :value="item.deskripsi"
                :errorText="formErrors.deskripsi?'failed':''" @input="v=>item.deskripsi=v" :hints="formErrors.deskripsi"
                label="" placeholder="Masukkan Deskripsi" :check="false" />
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              <FieldSelect :bind="{ disabled: !actionText, clearable: true }" :value="item.satuan_id"
                @input="v=>item.satuan_id=v" :errorText="formErrors.satuan_id?'failed':''"
                :hints="formErrors.satuan_id" valueField="id" displayField="deskripsi" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      where: `this.group = 'SATUAN'`
                    }
                }" placeholder="Pilih Salah Satu" label="" :check="true" />
            </td>
            <td class="p-2 text-center border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: !actionText }" :value="item.nominal" @input="(v)=>item.nominal=v"
                :errorText="formErrors.nominal?'failed':''" :hints="formErrors.nominal" label=""
                placeholder="Masukkan Nominal" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <div class="flex justify-center">
                <button type="button" @click="delDetailLL(i)" :disabled="!actionText">
                      <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                      </svg>
                    </button>
              </div>
            </td>
          </tr>
          <tr v-else class="text-center">
            <td colspan="15" class="py-[20px]">
              No data to show
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- END TABLE TARIF LAIN-LAIN -->

  <!-- ACTION BUTTON START -->
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