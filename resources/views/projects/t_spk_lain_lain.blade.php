<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">SPK LAIN-LAIN</h1>
  </div>
  <div class="flex justify-between items-center px-4 py-1">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-gray-600 text-white hover:gray-600-600' 
          : 'border border-gray-600 text-gray-600 bg-white hover:bg-gray-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          DRAFT
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-amber-600 text-white hover:bg-amber-600' 
          : 'border border-amber-600 text-amber-600 bg-white hover:bg-amber-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          POST
        </button>
      </div>
    </div>


    <!-- ACTION BUTTON -->
    <div class="flex items-center gap-x-4">
      <RouterLink :to="$route.path + '/create?' + (Date.parse(new Date()))" class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white text-sm rounded py-1 px-2.5
      transition-colors duration-300">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>

  <!-- TABLE -->
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions" class="max-h-[500px] pt-2 !px-4 
  !pb-8">
    <template #header>
      <div class="pb-13 h-full"></div>
    </template>
  </TableApi>
</div>
@else

<!-- FORM DATA -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md pt-2 pb-3 px-4">
    <div class="flex items-center gap-2">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
      <div class="flex flex-col py-1 gap-1">
        <h1 class="text-lg font-bold leading-none">Form SPK Lain-lain</h1>
        <p class="text-gray-100 leading-none">Transaction SPK Lain-lain</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 items-start">
    <!-- col-span-2 -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_draft" @input="v=>values.no_draft=v"
        :errorText="formErrors.no_draft?'failed':''" :hints="formErrors.no_draft" label="No. Draft"
        placeholder="Auto Generate" :check="false" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ readonly: true, disabled: true, clearable:true }"
        :value="values.status" @input="v=>values.status=v" :errorText="formErrors.status?'failed':''"
        :hints="formErrors.status" valueField="id" displayField="key" :options="[{'id' : 'DRAFT' , 'key' : 'DRAFT'},
      {'id' : 'POSTED' , 'key' : 'POSTED'},
      {'id' : 'IN PROCESS' , 'key' : 'IN PROCESS'},
      {'id' : 'COMPLETE' , 'key' : 'COMPLETE'}]" placeholder="Pilih Status" fa-icon="sort-desc" label="Status"
        :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true, disabled: true }" class="w-full !mt-3" :value="values.no_spk"
        @input="v=>values.no_spk=v" :errorText="formErrors.no_spk?'failed':''" :hints="formErrors.no_spk"
        placeholder="Nomor SPK Lain-Lain" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" class="w-full !mt-3" :value="values.tanggal"
        @input="v=>values.tanggal=v" :errorText="formErrors.tanggal?'failed':''" :hints="formErrors.tanggal" type="date"
        placeholder="Tanggal SPK Lain-Lain" :check="false" />
    </div>
    <div>
      <FieldPopup :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.genzet"
        @input="(v)=>values.genzet=v" :errorText="formErrors.genzet?'failed':''" :hints="formErrors.genzet"
        valueField="id" displayField="kode" :api="{
          url:  `${store.server.url_backend}/operation/m_supplier`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            searchfield: 'this.kode, this.nama, this.alamat, this.no_telp1'
          },
          onsuccess(response) {
            response.page = response.current_page
            response.hasNext = response.has_next
            return response
          }
        }" placeholder="Genzet" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'kode',
          headerName:  'Kode Supplier',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          flex: 1,
          field: 'nama',
          headerName:  'Nama Supplier',
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
        {
          flex: 1,
          field: 'no_telp1',
          headerName:  'No. Telp',
          sortable: false, resizable: true, filter: 'ColFilter',
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        }
        ]" />
    </div>
    <div>
      <FieldPopup :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_container"
        @input="(v)=>values.no_container=v" :errorText="formErrors.no_container?'failed':''"
        :hints="formErrors.no_container" valueField="id" displayField="no_container_gabungan" @update:valueFull="(dt) => {
              $log(dt)
              values.t_buku_order_id = dt['t_buku_order.id']
              values.m_customer_id = dt.['t_buku_order.m_customer_id']
              values.ukuran = dt['ukuran.id']
            }" :api="{
          url:  `${store.server.url_backend}/operation/t_buku_order_d_npwp`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            getNoContainer:true,
            searchfield: 't_buku_order.no_buku_order, this.no_prefix, this.no_suffix, ukuran.deskripsi, jenis.deskripsi'
          },
          onsuccess(response) {
            response.page = response.current_page
            response.hasNext = response.has_next
            return response
          }
        }" placeholder="No. Container" :check="false" :columns="[{
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 't_buku_order.no_buku_order',
          headerName: 'No. Order',
          sortable: true, resizable: true, filter: false,
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'No. Container',
          field: 'no_container_gabungan',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-center',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Ukuran',
          field: 'ukuran.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-center',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'jenis',
          field: 'jenis.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-center',],
          sortable: true, resizable: true, filter: false,
        }
        ]" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, clearable:false }" :value="values.t_buku_order_id"
        @input="v=>values.t_buku_order_id=v" :errorText="formErrors.t_buku_order_id?'failed':''"
        :hints="formErrors.t_buku_order_id" valueField="id" displayField="no_buku_order" :api="{
            url: `${store.server.url_backend}/operation/t_buku_order`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
            }
        }" placeholder="No. Order" :check="false" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, clearable:false }" :value="values.m_customer_id"
        @input="v=>values.m_customer_id=v" :errorText="formErrors.m_customer_id?'failed':''"
        :hints="formErrors.m_customer_id" valueField="id" displayField="kode" :api="{
            url: `${store.server.url_backend}/operation/m_customer`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
            }
        }" placeholder="Customer" :check="false" />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3" :bind="{ disabled: true, clearable:false }" :value="values.ukuran"
        @input="v=>values.ukuran=v" :errorText="formErrors.ukuran?'failed':''" :hints="formErrors.ukuran"
        valueField="id" displayField="deskripsi" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
            }
        }" placeholder="Ukuran" :check="false" />
    </div>
    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
      <FieldX :bind="{ readonly: !actionText, disabled: !actionText}" class="w-full !mt-3"
        :value="values.keluar_lokasi_tanggal" @input="v=>values.keluar_lokasi_tanggal=v"
        :errorText="formErrors.keluar_lokasi_tanggal?'failed':''" :hints="formErrors.keluar_lokasi_tanggal" type="date"
        placeholder="Keluar Lokasi" :check="false" />

      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.keluar_lokasi_jam"
        @input="v=>values.keluar_lokasi_jam=v" :errorText="formErrors.keluar_lokasi_jam?'failed':''"
        :hints="formErrors.keluar_lokasi_jam" type="time" placeholder="Jam Keluar" :check="false" />

      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.keluar_lokasi_temperatur"
        @input="v=>values.keluar_lokasi_temperatur=v" :errorText="formErrors.keluar_lokasi_temperatur?'failed':''"
        :hints="formErrors.keluar_lokasi_temperatur" placeholder="Keluar Temperature" :check="false" />
    </div>
    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
      <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" class="w-full !mt-3"
        :value="values.tiba_lokasi_tanggal" @input="v=>values.tiba_lokasi_tanggal=v"
        :errorText="formErrors.tiba_lokasi_tanggal?'failed':''" :hints="formErrors.tiba_lokasi_tanggal" type="date"
        placeholder="Tiba Lokasi" :check="false" />

      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tiba_lokasi_jam"
        @input="v=>values.tiba_lokasi_jam=v" :errorText="formErrors.tiba_lokasi_jam?'failed':''"
        :hints="formErrors.tiba_lokasi_jam" type="time" placeholder="Jam Tiba" :check="false" />

      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tiba_lokasi_temperatur"
        @input="v=>values.tiba_lokasi_temperatur=v" :errorText="formErrors.tiba_lokasi_temperatur?'failed':''"
        :hints="formErrors.tiba_lokasi_temperatur" placeholder="Tiba Lokasi Temperature" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.setting_temperatur"
        @input="v=>values.setting_temperatur=v" :errorText="formErrors.setting_temperatur?'failed':''"
        :hints="formErrors.setting_temperatur" label="Setting Temperature" placeholder="Setting Temperature"
        type="textarea" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.lokasi_stuffing"
        @input="v=>values.lokasi_stuffing=v" :errorText="formErrors.lokasi_stuffing?'failed':''"
        :hints="formErrors.lokasi_stuffing" label="Lokasi Stuffing" placeholder="Lokasi Stuffing" type="textarea"
        :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.catatan"
        @input="v=>values.catatan=v" :errorText="formErrors.catatan?'failed':''" :hints="formErrors.catatan"
        label="Catatan" placeholder="Catatan" type="textarea" :check="false" />
    </div>
  </div>
  <hr />

  <!-- START DETAIL -->
  <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <ButtonMultiSelect v-if="actionText" @add="addDetail" :api="{
        url: `${store.server.url_backend}/operation/m_general`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: true, 
            searchfield: 'this.kode, this.deskripsi',
            //notin: `this.id: ${detailArr.map((det)=> (det.m_item_id))}`,
            where: `this.group = 'SEKTOR' AND this.is_active = true`
        },
          onsuccess(response) {
            response.page = response.current_page
            response.hasNext = response.has_next
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
        }, {
          pinned: false,
          headerName: 'Kode',
          field: 'kode',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Nama Sektor',
          field: 'deskripsi',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        ]">
        <div class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300">
          <icon fa="plus" size="sm" />
          <span>Add To List</span>
        </div>
      </ButtonMultiSelect>
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Sektor</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Catatan</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              Action</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="i" v-show="detailArr.length > 0">
            <td class="p-1 text-center border border-[#CACACA]">
              {{ i+1 }}.
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldSelect class="m-0" :bind="{ disabled: true, readonly:true, clearable:false }" :value="item.sektor"
                @input="v=>item.sektor=v" :errorText="formErrors.sektor?'failed':''" :hints="formErrors.sektor"
                valueField="id" displayField="deskripsi" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      transform:false,
                      join:false
                    }
                }" placeholder="" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldX :bind="{ readonly: !actionText }" class="m-0" :value="item.catatan" @input="v=>item.catatan=v"
                :hints="formErrors.catatan" type="textarea" :check="false" />
            </td>
            <td class="p-1 border border-[#CACACA]">
              <div class="flex justify-center">
                <button type="button" @click="delDetailArr(i)" :disabled="!actionText">
                  <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
          <tr v-show="detailArr.length <= 0" class="text-center">
            <td colspan="15" class="py-[20px]">
              No data to show
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- END DETAIL -->

  <!-- ACTION BUTTON FORM -->
  <hr>
  <div class="flex flex-row items-center justify-end space-x-2 p-2">
    <i v-show="actionText" class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
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