<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-2.5 pt-2 pb-2">
    <h1 class="text-xl font-semibold">JURNAL ANGKUTAN</h1>
  </div>
  <div class="flex justify-between items-center px-2.5 py-1">
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-gray-600 text-white hover:bg-gray-600' 
                        : 'border border-gray-600 text-gray-600 bg-white hover:bg-gray-600 hover:text-white'"
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
        <h1 class="text-20px font-bold">Form Jurnal Angkutan</h1>
        <p class="text-gray-100">Jurnal Angkutan Header</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->

    <!-- No. Draft Coloumn -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3 w-full" :value="values.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>values.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>


    <!-- Date Coloumn -->
    <div>
      <FieldX :bind="{ disabled: true, readonly: true }" class="w-full !mt-3" :value="values.tgl"
        :errorText="formErrors.tgl?'failed':''" @input="updateDate" :hints="formErrors.tgl" :check="false"
        label="Tanggal" placeholder="Pilih Tanggal" />
    </div>

    <!-- No. Jurnal Angkutan Coloumn -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_jurnal"
        :errorText="formErrors.no_jurnal?'failed':''" @input="v=>values.no_jurnal=v" :hints="formErrors.no_jurnal"
        label="No. Jurnal Angkutan" placeholder="No. Jurnal Angkutan" :check="false" />
    </div>

    <!-- Nama Supplier Coloumn -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.nama_supplier"
        :errorText="formErrors.nama_supplier?'failed':''" @input="v=>values.nama_supplier=v"
        :hints="formErrors.nama_supplier" label="Nama Supplier" placeholder="Nama Supplier" :check="false" />
    </div>

    <!-- Kode Supplier Coloumn -->
    <div class="flex">
      <FieldPopup class="!mt-3 w-full" :api="{
          url: `${store.server.url_backend}/operation/m_supplier`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            where: `jenis.deskripsi = 'Angkutan'`,
            searchfield: 'this.kode , this.nama , this.is_active'
            },
          }" displayField="kode" valueField="id" :bind="{ readonly: !actionText }" :value="values.m_supplier_id"
        @input="v=>{
            if(v){
              values.m_supplier_id=v
            }else{
              values.nama_supplier=null;
              values.m_supplier_id=null;
            }
          }" @update:valueFull="supplier" :errorText="formErrors.m_supplier_id?'failed':''" class="w-full !mt-3"
        :hints="formErrors.m_supplier_id" placeholder="Pilih Kode Supplier" label="Kode Supplier" :check='false'
        :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'kode',
              headerName: 'KODE',
              sortable: false, resizable: true, filter: false,
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'nama',
              headerName: 'NAMA SUPPLIER',
              cellClass: ['justify-center', 'border-r', '!border-gray-200',],
              sortable: false, resizable: true, filter: false,
            },
            {
              flex: 1,
              field: 'is_active',
              headerName: 'STATUS',
              cellClass: ['justify-center', 'border-r', '!border-gray-200',],
              sortable: false, resizable: true, filter: false,
              valueFormatter: (params) => {
              return params.value ? 'Aktif' : 'Nonaktif';
              }
            },
            ]" />
      <span class="text-red-500"> * </span>
    </div>

    <!-- No. Nota Piutang Coloumn -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_nota_piutang"
        :errorText="formErrors.no_nota_piutang?'failed':''" @input="v=>values.no_nota_piutang=v"
        :hints="formErrors.no_nota_piutang" label="No. Nota Piutang" placeholder="No. Nota Piutang" :check="false" />
    </div>
    <!-- Catatan Coloumn -->
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        label="Catatan" placeholder="Catatan" :check="false" type="textarea" />
    </div>

    <!-- Status  -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.status"
        :errorText="formErrors.status?'failed':''" @input="v=>values.status=v"
        :hints="formErrors.status" label="status" placeholder="status" :check="false" />
    </div>
    
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>

  <div class="flex flex-row items-center justify-end space-x-2 p-2">
    <i class="text-gray-500 text-[12px]" v-show="actionText">
    Tekan CTRL + S untuk shortcut Save Data
  </i>
    <button
    class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
    v-show="actionText"
    @click="onReset(true)"
  >
    <icon fa="times" />
    Reset
  </button>

    <button
    v-if="values.status === 'DRAFT'"
    class="text-sm rounded-md py-2 px-3 text-white bg-yellow-600 hover:bg-yellow-700 flex gap-x-1 items-center
    transition-colors duration-300"
    @click="onSave(true)"
  >
    <icon fa="paper-plane" />
    <span>Simpan Dan Post</span>
  </button>

    <button
    class="text-sm rounded-md py-2 px-3 text-white bg-green-600 hover:bg-green-700 flex gap-x-1 items-center
    transition-colors duration-300"
    v-show="actionText"
    @click="onSave(false)"
  >
    <icon fa="save" />
    <span>Simpan</span>
  </button>
  </div>

  <!-- detail -->
  <div class="p-4">
    <div class="mt-4" style="overflow-x: auto; border: 1px solid #CACACA;">
      <table class="w-[120%] table-auto border border-[#CACACA]">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nomor Angkutan
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nomor Kontainer
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Sektor
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Tipe Kontainer
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Jenis Kontainer
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] ">
              Ukuran Kontainer
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] ">
              Nominal
            </td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: true, readonly: true , clearable:false }" class="w-full py-2 !mt-0"
                :value="item.no_angkutan" @input="v=>item.no_angkutan=v" :errorText="formErrors.no_angkutan?'failed':''"
                :hints="formErrors.no_angkutan" placeholder="Nomor Angkutan" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: true, readonly: true , clearable:false }" class="w-full py-2 !mt-0"
                :value="item.no_container" @input="v=>item.no_container=v"
                :errorText="formErrors.no_container?'failed':''" :hints="formErrors.no_container"
                placeholder="Kode Supplier" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldSelect :bind="{ disabled: true, readonly: true , clearable:false }" class="w-full py-2 !mt-0"
                :value="item.sektor" @input="v=>item.sektor=v" :errorText="formErrors.sektor?'failed':''"
                :hints="formErrors.sektor" valueField="id" displayField="deskripsi" :api="{          
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        simplest:true,
                      }
                  }" placeholder="Sektor" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldSelect :bind="{ disabled: true, readonly: true , clearable:false }" class="w-full py-2 !mt-0"
                :value="item.tipe" @input="v=>item.tipe=v" :errorText="formErrors.tipe?'failed':''"
                :hints="formErrors.tipe" valueField="id" displayField="deskripsi" :api="{          
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        simplest:true,
                      }
                  }" placeholder="Tipe Kontainer" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldSelect :bind="{ disabled: true, readonly: true ,clearable:false }" class="w-full py-2 !mt-0"
                :value="item.jenis" @input="v=>item.jenis=v" :errorText="formErrors.jenis?'failed':''"
                :hints="formErrors.jenis" valueField="id" displayField="deskripsi" :api="{          
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        simplest:true,
                      }
                  }" placeholder="Jenis Kontainer" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.ukuran"
                @input="v=>item.ukuran=v" :errorText="formErrors.ukuran?'failed':''" :hints="formErrors.ukuran"
                placeholder="Ukuran Kontainer" label="" :check="false" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: true }" class="w-full py-2 !mt-0" :value="item.nominal"
                :errorText="formErrors.nominal?'failed':''" @input="v=>item.nominal=v" :hints="formErrors.nominal"
                label="" placeholder="nominal" :check="false" />
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

  <div class="w-full flex justify-start">
    <div class="w-md p-4">
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">DPP :</label>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="values.dpp"
          :errorText="formErrors.dpp?'failed':''" @input="v=>values.dpp=v"
          :hints="formErrors.dpp" label="" placeholder="Total DPP" :check="false" />
      </div>
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-0">
        <label class="!mt-4 !ml-3">Total PPN :</label>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="values.ppn"
          :errorText="formErrors.ppn?'failed':''" @input="v=>values.ppn=v"
          :hints="formErrors.ppn" label="" placeholder="Total PPN" :check="false" />
      </div>
      <div class="grid grid-cols-2 gap-y-0 gap-x-2 items-start mb-3">
        <label class="!mt-4 !ml-3">Grand Total :</label>
        <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="values.grand_total"
          :errorText="formErrors.grand_total?'failed':''" @input="v=>values.grand_total=v"
          :hints="formErrors.grand_total" label="" placeholder="Grand Total" :check="false" />
      </div>
    </div>
  </div>


</div>
@endverbatim
@endif