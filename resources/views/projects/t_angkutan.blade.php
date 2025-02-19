<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="p-2">
    <h1 class="text-xl font-semibold">Angkutan</h1>
  </div>
  <div class="flex justify-between items-center px-2.5 py-1">
    <!-- FILTER -->
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
        <h1 class="text-20px font-bold">Angkutan</h1>
        <p class="text-gray-100">Transaksi Angkutan</p>
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
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_angkutan"
        :errorText="formErrors.no_angkutan?'failed':''" @input="v=>values.no_angkutan=v" :hints="formErrors.no_angkutan"
        label="No. Angkutan" placeholder="No. Angkutan" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full !mt-3" :value="values.tanggal"
        :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v" :hints="formErrors.tanggal"
        :check="false" type="date" label="Tanggal" placeholder="Pilih Tanggal" />
    </div>
    <div>
      <FieldPopup label="No. Buku Order" class="w-full !mt-3" valueField="id" displayField="no_buku_order"
        :value="values.t_buku_order_id" @input="(v)=>values.t_buku_order_id=v" @update:valueFull="v=>{
              detailArr = []              
              values['custom_stuple']=(v?v['m_customer.custom_stuple']:null)
              values['code_customer']=(v?v['m_customer.kode']:null)
            }" :api="{
              url: `${store.server.url_backend}/operation/t_buku_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                //selectfield: 'id,no_buku_order,tgl',
                scopes:'NotDuplicateForAngkutan',
                searchfield: 'this.no_buku_order, this.tgl'
              }
            }" placeholder="Pilih No. Buku Order" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'no_buku_order',
              headerName:  'No. Buku Order',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'm_customer.kode',
              headerName:  'Kode Customer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'tgl',
              headerName:  'Tanggal Buku Order',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            }
            ]" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.code_customer"
        :errorText="formErrors.code_customer?'failed':''" @input="v=>values.code_customer=v" :hints="formErrors.code_customer" :check="false"
        label="Kode Customer" placeholder="Kode Customer" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.party"
        :errorText="formErrors.party?'failed':''" @input="v=>values.party=v" :hints="formErrors.party" :check="false"
        label="Party" placeholder="Party" />
    </div>
    <div class="flex flex-col">
      <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer font-semibold"
            for="pph_for_click"
            >PPH 23</label>
      <div class="flex w-40">
        <div class="flex-auto">
          <i class="text-red-500">Tidak</i>
        </div>
        <div class="flex-auto">
          <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                role="switch"
                id="pph_for_click"
                :disabled="!actionText"
                v-model="values.pph"
                />
        </div>
        <div class="flex-auto">
          <i class="text-green-500">Iya</i>
        </div>
      </div>
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.status"
        :errorText="formErrors.status?'failed':''" @input="v=>values.status=v" :hints="formErrors.status" :check="false"
        label="Status" placeholder="Status" />
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>

  <!-- detail -->
  <div class="p-4 mb-2">
    <button v-show="actionText" @click="addDetail" type="button" class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5 mb-2">
          <icon fa="plus" />
          Add to List
        </button>

    <div style="overflow-x: auto; width: 100%; border: 1px solid #CACACA;">
      <table class="w-[230%] table-auto border border-[#CACACA]">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[3%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              SPK Angkutan
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              No. Container
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Trip
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Ukuran
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[25%] border bg-[#f8f8f8] border-[#CACACA]">
              Depo
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[15%] border bg-[#f8f8f8] border-[#CACACA]">
              Sektor
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Tgl Out
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Waktu Out
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Jam Out
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Tgl In
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Waktu In
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Jam In
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[7%] border bg-[#f8f8f8] border-[#CACACA]">
              Staple
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Free
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Biaya Lain-Lain
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[20%] border bg-[#f8f8f8] border-[#CACACA]">
              Nama Angkutan
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Tarif Los Cargo
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Tgl Stuffing
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[15%] border bg-[#f8f8f8] border-[#CACACA]">
              Pelabuhan
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Angk. Pelabuhan
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Head
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center min-w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
              Catatan
            </td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true , required: true}" class="w-full !mt-3" :value="item.no_spk"
                :errorText="formErrors.no_spk?'failed':''" @input="v=>item.no_spk=v" :hints="formErrors.no_spk"
                :check="false" placeholder="" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX
                :bind="{ readonly: item.item_no_spk === null ? false : (item.no_spk ? !item.no_spk.includes('Luar') : true) }"
                class="w-full !mt-3" :value="item.no_container" :errorText="formErrors.no_container?'failed':''"
                @input="v=>item.no_container=v" :hints="formErrors.no_container" :check="false" label=""
                placeholder="" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="item.trip_desc"
                :errorText="formErrors.trip_desc?'failed':''" @input="v=>item.trip_desc=v" :hints="formErrors.trip_desc"
                :check="false" label="" placeholder="" />
            </td>
            <td class="p-2 border border-[#CACACA]" style="min-width: 150px !important;">
              <FieldSelect
                :bind="{ disabled:true, readonly:true }"
                class="w-full !mt-3" :value="item.ukuran" @input="v=>{
                    if(v){
                      item.ukuran=v
                    }else{
                      item.ukuran=null
                    }
                  }" :errorText="formErrors.ukuran?'failed':''" :hints="formErrors.ukuran" valueField="id"
                displayField="deskripsi" :api="{
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        simplest:true,
                        transform:false,
                        join:false,
                        where:`this.is_active=true and this.group='UKURAN KONTAINER'`
                      }
                  }" placeholder="" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]" style="min-width: 150px !important;">
              <FieldSelect
                :bind="{ disabled: item.item_no_spk === null ? false : (item.no_spk ? !item.no_spk.includes('Luar') : true), clearable:true }"
                class="w-full !mt-3" :value="item.depo" @input="v=>{
                    if(v){
                      item.depo=v
                    }else{
                      item.depo=null
                    }
                  }" :errorText="formErrors.depo?'failed':''" :hints="formErrors.depo" valueField="id"
                displayField="kode" :api="{
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        simplest:true,
                        transform:false,
                        join:false,
                        where:`this.is_active=true and this.group='DEPO'`
                      }
                  }" placeholder="" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]" style="min-width: 150px !important;">
              <FieldSelect
                :bind="{ disabled: item.item_no_spk === null ? false : (item.no_spk ? !item.no_spk.includes('Luar') : true), clearable:true }"
                class="w-full !mt-3" :value="item.sektor" @input="v=>{
                    if(v){
                      item.sektor=v
                    }else{
                      item.sektor=null
                    }
                  }" :errorText="formErrors.sektor?'failed':''" :hints="formErrors.sektor" valueField="id"
                displayField="deskripsi" :api="{
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        simplest:true,
                        transform:false,
                        join:false,
                        where:`this.is_active=true and this.group='SEKTOR'`
                      }
                  }" placeholder="" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]" style="min-width: 100px !important;">
              <FieldX
                :bind="{ readonly: true, disabled: item.item_no_spk === null ? false : (item.no_spk ? !item.no_spk.includes('Luar') : true), required: true}"
                class="w-full !mt-3" :value="item.tanggal_out" :errorText="formErrors.tanggal_out?'failed':''"
                @input="v=>updateTanggalOut(v, item)" :hints="formErrors.tanggal_out" :check="false" type="date"
                placeholder="" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldSelect
                :bind="{ readonly: item.item_no_spk === null ? false : (item.no_spk ? !item.no_spk.includes('Luar') : true), clearable:false }"
                class="w-full !mt-3" :value="item.waktu_out" @input="v=>{
                    if(v){
                      item.waktu_out=v
                    }else{
                      item.waktu_out=null
                    }
                  }" :errorText="formErrors.waktu_out?'failed':''" :hints="formErrors.waktu_out" valueField="id"
                displayField="deskripsi" :api="{
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        simplest:true,
                        transform:false,
                        where:`this.group='WAKTUOUT'`,
                        join:false
                      }
                  }" placeholder="" fa-icon="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: !actionText , required: false}" class="w-full !mt-3" :value="item.jam_out"
                :errorText="formErrors.jam_out?'failed':''" @input="v=>updateJamOut(v, item)"
                :hints="formErrors.jam_out" :check="false" type="time" placeholder="" />
            </td>
            <td class="p-2 border border-[#CACACA]" style="min-width: 100px !important;">
              <FieldX
                :bind="{ readonly: true, disabled: item.item_no_spk === null ? false : (item.no_spk ? !item.no_spk.includes('Luar') : true), required: false, clearable:false}"
                class="w-full !mt-3" :value="item.tanggal_in" :errorText="formErrors.tanggal_in?'failed':''"
                @input="v=>updateTanggalIn(v, item)" :hints="formErrors.tanggal_in" :check="false" type="date"
                placeholder="" fa-icon="" />
            </td>
            <td class="p-2 border border-[#CACACA]">

              <FieldSelect
                :bind="{ readonly: item.item_no_spk === null ? false : (item.no_spk ? !item.no_spk.includes('Luar') : true), clearable:false }"
                class="w-full !mt-3" :value="item.waktu_in" @input="v=>{
                    if(v){
                      item.waktu_in=v
                    }else{
                      item.waktu_in=null
                    }
                  }" :errorText="formErrors.waktu_in?'failed':''" :hints="formErrors.waktu_in" valueField="id"
                displayField="deskripsi" :api="{
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        simplest:true,
                        transform:false,
                        where:`this.group='WAKTUIN'`,
                        join:false
                      }
                  }" placeholder="" fa-icon="" :check="false" />

            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: !actionText , required: false}" class="w-full !mt-3" :value="item.jam_in"
                :errorText="formErrors.jam_in?'failed':''" @input="v=>updateJamIn(v, item)" :hints="formErrors.jam_in"
                :check="false" type="time" placeholder="" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="item.staple"
                :errorText="formErrors.staple?'failed':''" @input="v=>item.staple=v" :hints="formErrors.staple"
                :check="false" label="" placeholder="" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="item.free"
                :errorText="formErrors.free?'failed':''" @input="v=>item.free=v" :hints="formErrors.free" :check="false"
                label="" placeholder="" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber
                :bind="{ readonly: item.item_no_spk === null ? false : (item.no_spk ? !item.no_spk.includes('Luar') : true) }"
                class="w-full !mt-3" :value="item.biaya_lain_lain" :errorText="formErrors.biaya_lain_lain?'failed':''"
                @input="v=>item.biaya_lain_lain=v" :hints="formErrors.biaya_lain_lain" :check="false" label=""
                placeholder="" />
            </td>
            <td class="p-2 border border-[#CACACA]" style="min-width: 150px !important;">
              <FieldSelect
                :bind="{ disabled: item.item_no_spk === null ? false : (item.no_spk ? !item.no_spk.includes('Luar') : true), clearable:true }"
                class="w-full !mt-3" :value="item.nama_angkutan_id" @input="v=>{
                    if(v){
                      item.nama_angkutan_id=v
                    }else{
                      item.nama_angkutan_id=null
                    }
                  }" :errorText="formErrors.nama_angkutan_id?'failed':''" :hints="formErrors.nama_angkutan_id"
                valueField="id" displayField="kode" :api="{
                      url: `${store.server.url_backend}/operation/m_supplier`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        transform:false,
                        where:`this.is_active=true and lower(jenis.deskripsi)='angkutan'`,
                        //searchfield:'this.id, this.kode'
                      }
                  }" placeholder="" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="item.tarif_los_cargo"
                :errorText="formErrors.tarif_los_cargo?'failed':''" @input="v=>item.tarif_los_cargo=v"
                :hints="formErrors.tarif_los_cargo" :check="false" label="" placeholder="" />
            </td>
            <td class="p-2 border border-[#CACACA]" style="min-width: 100px !important;">
              <FieldX :bind="{ readonly: true,disabled: true, required: true}" class="w-full !mt-3"
                :value="item.tgl_stuffing" :errorText="formErrors.tgl_stuffing?'failed':''"
                @input="v=>item.tgl_stuffing=v" :hints="formErrors.tgl_stuffing" :check="false" type="date"
                placeholder="" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldSelect
                :bind="{ readonly: item.item_no_spk === null ? true : (item.no_spk ? item.no_spk.includes('SPK') : true) }"
                class="w-full !mt-3" :value="item.pelabuhan" @input="v=>item.pelabuhan=v"
                :errorText="formErrors.pelabuhan?'failed':''" :hints="formErrors.pelabuhan" valueField="id"
                displayField="kode" :api="{
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        simplest:true,
                        transform:false,
                        where:`this.group='PELABUHAN'`,
                        join:false
                      }
                  }" placeholder="" label="" fa-icon="" :check="false" />

              <!-- <FieldX :bind="{ readonly: item.item_no_spk === null ? true : (item.no_spk ? item.no_spk.includes('SPK') : true) }" class="w-full !mt-3" :value="item.pelabuhan"
                  :errorText="formErrors.pelabuhan?'failed':''" @input="v=>item.pelabuhan=v" :hints="formErrors.pelabuhan"
                  :check="false" label="" placeholder="" /> -->
            </td>
            <td class="p-2 border border-[#CACACA]">


              <FieldSelect
                :bind="{ disabled: item.item_no_spk === null ? false : (item.no_spk ? !item.no_spk.includes('Luar') : true), clearable:true }"
                class="w-full !mt-3" :value="item.angkutan_pelabuhan" @input="v=>{
                    if(v){
                      item.angkutan_pelabuhan=v
                    }else{
                      item.angkutan_pelabuhan=null
                    }
                  }" :errorText="formErrors.angkutan_pelabuhan?'failed':''" :hints="formErrors.angkutan_pelabuhan"
                valueField="id" displayField="kode" :api="{
                      url: `${store.server.url_backend}/operation/m_supplier`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        transform:false,
                        where:`this.is_active=true and lower(jenis.deskripsi)='angkutan'`
                      }
                  }" placeholder="" label="" :check="false" />

            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="item.head_desc"
                :errorText="formErrors.head_desc?'failed':''" @input="v=>item.head_desc=v" :hints="formErrors.head_desc"
                :check="false" label="" placeholder="" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX
                :bind="{ readonly: item.item_no_spk === null ? true : (item.no_spk ? item.no_spk.includes('SPK') : true) }"
                type="textarea" class="w-full !mt-3" :value="item.catatan" :errorText="formErrors.catatan?'failed':''"
                @input="v=>item.catatan=v" :hints="formErrors.catatan" :check="false" label="" placeholder="" />
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
    <button
        class="bg-rose-600 text-white font-semibold hover:bg-rose-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click="onSaveAndPost"
      >
        <icon fa="location-arrow" />
        Simpan dan Post Data
      </button>
  </div>
</div>
@endverbatim
@endif