@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">TARIF KOMISI UNDERNAME</h1>
  </div>
  <div class="flex justify-between items-center px-4 py-1">

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
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Tarif Komisi Undername</h1>
        <p class="text-gray-100">Untuk mengatur informasi tarif komisi undername pada sistem</p>
      </div>
    </div>
  </div>
  <!-- HEADER END -->

  <!-- FORM START -->
  <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row p-4 gap-3">
    <div class=" w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.kode_tarif_komisi_undername"
        :errorText="formErrors.kode_tarif_komisi_undername?'failed':''" @input="v=>values.kode_tarif_komisi_undername=v"
        :hints="formErrors.kode_tarif_komisi_undername" placeholder="Kode" :check="false" />
    </div>
    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ readonly: !actionText }" :value="values.is_active"
        :errorText="formErrors.is_active ? 'failed' : ''" @input="v => values.is_active = v"
        :hints="formErrors.is_active" valueField="id" displayField="key" :options="[
              { 'id': 1, 'key': 'Active' },
              { 'id': 0, 'key': 'InActive' }
            ]" label="Status" placeholder="Status" :check="true" />
    </div>
    <div class="w-full !mt-3">
      <FieldPopup class="!mt-0" :bind="{ readonly: !actionText }" :value="values.m_cust_id"
        @input="(v)=>values.m_cust_id=v" :errorText="formErrors.m_cust_id?'failed':''" :hints="formErrors.m_cust_id"
        valueField="id" displayField="nama_perusahaan" :api="{
          url: `${store.server.url_backend}/operation/m_customer`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            scopes: 'GetPerusahaan',
            searchfield: 'this.kode, this.nama_perusahaan'
          },
          onsuccess(response) {
            response.page = response.current_page
            response.hasNext = response.has_next
            return response
          }
        }" placeholder="Pilih Customer" label="Nama Customer" :check="false" :columns="[{
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
          field: 'jenis_nama_perusahaan',
          headerName:  'Nama Customer',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        }]" />
    </div>
    <div class="w-full !mt-3">
      <FieldSelect class="!mt-0" :bind="{ disabled: !actionText, clearable:false }" :value="values.tipe_tarif"
        @input="v=>values.tipe_tarif=v" :errorText="formErrors.tipe_tarif?'failed':''" :hints="formErrors.tipe_tarif"
        valueField="id" displayField="key" :options="[
        { 'id': 'QQ', 'key': 'QQ' }, 
        { 'id': 'NON QQ', 'key': 'NON QQ'}
        ]" placeholder="Tipe Tarif" :check="true" />
    </div>
    <div class="flex items-center w-full !mt-3 space-x-2">

      <FieldNumber class="!mt-0 flex-1" :bind="{ readonly: !actionText }" :value="values.tarif_komisi"
        :errorText="formErrors.tarif_komisi ? 'failed' : ''" @input="v => values.tarif_komisi = v"
        :hints="formErrors.tarif_komisi" placeholder="Masukkan Tarif Komisi" label="Tarif Komisi" :check="false" />

      <div class="border text-xs rounded w-[45px] h-[34px] text-gray-400 bg-gray-100 flex justify-center items-center">
        / DOC
      </div>
    </div>
    <div class="w-full !mt-3">
      <FieldX class="!mt-0" :bind="{ readonly: !actionText }" :value="values.keterangan"
        :errorText="formErrors.keterangan?'failed':''" @input="v=>values.keterangan=v" :hints="formErrors.keterangan"
        placeholder="Keterangan" type="textarea" :check="false" />
    </div>

    <!-- START TABLE DETAIL -->
    <hr class="<md:col-span-1 col-span-3">
    <div class="<md:col-span-1 col-span-3 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <div class="!mb-2">
        <button :disabled="!actionText" @click="addDetail" type="button" class="bg-blue-600 text-white font-semibold 
            hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
              <icon fa="plus" size="sm mr-0.5"/>
              Add to List
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
                Nilai Awal ($)</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Nilai Akhir ($)</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Presentase</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Catatan</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                Action</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArr" :key="i" class="border-t" v-if="detailArr.length > 0">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" :value="item.nilai_awal"
                  :errorText="formErrors.nilai_awal?'failed':''" @input="v=>item.nilai_awal=v"
                  :hints="formErrors.nilai_awal" label="" placeholder="Nilai Awal" :check="false" />
              </td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" :value="item.nilai_akhir"
                  :errorText="formErrors.nilai_akhir?'failed':''" @input="v=>item.nilai_akhir=v"
                  :hints="formErrors.nilai_akhir" label="" placeholder="Nilai Akhir" :check="false" />
              </td>
              <td class="p-2 text-center font-semibold border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" :value="item.persentase"
                  :errorText="formErrors.persentase?'failed':''" @input="v=>item.persentase=v"
                  :hints="formErrors.persentase" label="" placeholder="Presentase" :check="false" />
              </td>
              <td class="p-2 text-center font-semibold border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" :value="item.catatan"
                  :errorText="formErrors.catatan?'failed':''" @input="v=>item.catatan=v" :hints="formErrors.catatan"
                  type="textarea" label="" placeholder="Catatan" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div class="flex justify-center">
                  <button type="button" @click="delDetail(i)" :disabled="!actionText">
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
    <!-- END OF TABLE DETAIL -->

  </div>
  <!-- FORM END -->
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
        @click="onSave(true)"
      >
        <icon fa="save" />
        Simpan
      </button>
  </div>
</div>

@endverbatim
@endif