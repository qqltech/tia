<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">KOMISI</h1>
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
  <TableApi ref='apiTable' :api="table.api" :columns="table.columns" :actions="table.actions" class="max-h-[500px] pt-2 !px-4 
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
        <h1 class="text-lg font-bold leading-none">Form Komisi</h1>
        <p class="text-gray-100 leading-none">Transaction Komisi</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 items-start">
    <!-- START COLUMN -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="w-full !mt-3" :value="data.tipe_komisi"
        @input="v=>data.tipe_komisi=v" :errorText="formErrors.tipe_komisi?'failed':''" :hints="formErrors.tipe_komisi"
        valueField="id" displayField="key" :options="['Eksport','Import']" placeholder="Tipe Komisi" :check="false"
        fa-icon="sort-desc" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.status"
        :errorText="formErrors.status?'failed':''" @input="v=>data.status=v" :hints="formErrors.status" label="Status"
        placeholder="Status" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_komisi"
        :errorText="formErrors.no_komisi?'failed':''" @input="v=>data.no_komisi=v" :hints="formErrors.no_komisi"
        label="No. Komisi" placeholder="No. Komisi" :check="false" />
    </div>
    <div>
      <FieldPopup label="Kode Tarif Komisi" :bind="{ disabled: !actionText, readonly: !actionText }"
        class="w-full !mt-3" valueField="id" displayField="kode" :value="data.m_tarif_komisi_id"
        @input="(v)=>data.m_tarif_komisi_id=v" :errorText="formErrors.m_tarif_komisi_id?'failed':''"
        :hints="formErrors.m_tarif_komisi_id" :api="{
              url: `${store.server.url_backend}/operation/m_tarif_komisi`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                // selectfield: 'id,no_invoice,tipe_order,jenis_barang,sektor',
                searchfield: 'this.id, this.kode, this.tarif_umkm'
              }
            }" placeholder="Pilih Kode Tarif Komisi" :check="false" :columns="[{
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
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'tarif_umkm',
              headerName:  'Tarif UMKM',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            ]" />
    </div>
    <div>
      <FieldPopup label="Customer" class="w-full !mt-3" valueField="id" displayField="nama_perusahaan"
        :value="data.m_customer_id" @input="(v)=>data.m_customer_id=v" :api="{
              url: `${store.server.url_backend}/operation/m_customer`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                //selectfield: 'id,name,code,shortname',
                searchfield: 'this.kode, this.nama_perusahaan, this.alamat, this.kota'
              }
            }" placeholder="Pilih Customer" @update:valueFull="(v) => {
              if(v){
                data.kode_cust=v.kode
              }else{
                data.kode_cust=null
              }
            }" :check="false" :columns="[{
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
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'nama_perusahaan',
              headerName:  'Nama',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'alamat',
              headerName:  'Alamat',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'kota',
              headerName:  'Kota',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
    </div>
    <div>
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/t_buku_order_d_npwp`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          // scopes:'GetNoOrder',
          simplest:false,
          //where: `this.m_customer_id = ${data.m_customer_id}`,
          // override:true,
          // where:`this.id NOT IN(${dataOrderId.join(', ')}) AND this.id!=${data.t_detail_npwp_container_2_id ? data.t_detail_npwp_container_2_id: 0 }`,
          //searchfield: 'this.no_suffix, this.no_prefix, this.jenis'
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="t_buku_order.no_buku_order" valueField="id" :bind="{ readonly: !actionText }"
        :value="data.t_buku_order_awal_id" @input="(v)=>{
          data.t_buku_order_awal_id=v
          }" @update:valueFull="(response)=>{
            $log(response)
      }" :errorText="formErrors.t_buku_order_awal_id?'failed':''" class="w-full !mt-3"
        :hints="formErrors.t_buku_order_awal_id" placeholder="No. Order Awal" :check='false' :columns="[
        {
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
          headerName: 'No. Prefix',
          field: 'no_prefix',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'No. Suffix',
          field: 'no_suffix',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Ukuran',
          field: 'ukuran.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'jenis',
          field: 'jenis.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
      ]" />
    </div>
    <div>
      <FieldPopup class="w-full !mt-3" :api="{
        url: `${store.server.url_backend}/operation/t_buku_order_d_npwp`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          // scopes:'GetNoOrder',
          simplest:false,
          // transform:true,
          // join:true,
          // override:true,
          // where:`this.id NOT IN(${dataOrderId.join(', ')}) AND this.id!=${data.t_detail_npwp_container_2_id ? data.t_detail_npwp_container_2_id: 0 }`,
          searchfield: 'this.no_suffix, this.no_prefix, this.jenis',
          // selectfield: 'this.no_id,this.no_prefix, this.nama, this.alamat_domisili' 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="t_buku_order.no_buku_order" valueField="id" :bind="{ readonly: !actionText }"
        :value="data.t_buku_order_akhir_id" @input="(v)=>{
          data.t_buku_order_akhir_id=v
          }" @update:valueFull="(response)=>{
            $log(response)
      }" :errorText="formErrors.t_buku_order_akhir_id?'failed':''" class="w-full !mt-3"
        :hints="formErrors.t_buku_order_akhir_id" placeholder="No. Order Akhir" :check='false' :columns="[
        {
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
          headerName: 'No. Prefix',
          field: 'no_prefix',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'No. Suffix',
          field: 'no_suffix',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'Ukuran',
          field: 'ukuran.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
        {
          headerName: 'jenis',
          field: 'jenis.deskripsi',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
      ]" />
    </div>
    <!-- <div class="flex space-x-3 !mt-3 text-blue-600">
      <label class="col-start text-black gap-3" for="CustomStupleBox">Pembayaran (Order Yang Belum Dibayar)</label>
      <input type="checkbox" id="CustomStupleBox" v-model="data.pembayaran" style="width: 20px; height: 20px;">
      
    </div> -->
    <div class="grid grid-cols-2 gap-y-2 gap-x-2">
      <FieldX :bind="{readonly: !actionText, disabled: !actionText }" class="w-full !mt-3"
        @input="v=>data.pertanggal_awal=v" :value="data.pertanggal_awal"
        :errorText="formErrors.pertanggal_awal?'failed':''" :hints="formErrors.pertanggal_awal" :check="false"
        type="date" label="Pertanggal Awal" placeholder="Pertanggal Awal" />
      <FieldX :bind="{readonly: !actionText, disabled: !actionText }" class="w-full !mt-3"
        @input="v=>data.pertanggal_akhir=v" :value="data.pertanggal_akhir"
        :errorText="formErrors.pertanggal_akhir?'failed':''" :hints="formErrors.pertanggal_akhir" :check="false"
        type="date" label="Pertanggal Akhir" placeholder="Pertanggal Akhir" />
    </div>
    <!-- <div class="flex space-x-3 !mt-3 text-blue-600">
      <label class="col-start text-black gap-3" for="CustomStupleBox">Pembayaran (Order Yang Sudah Dibayar)</label>
      <input type="checkbox" id="CustomStupleBox" v-model="data.pembayaran" style="width: 20px; height: 20px;">
     
    </div> -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="w-full !mt-3" :value="data.is_pph"
        @input="v=>data.is_pph=v" :errorText="formErrors.is_pph?'failed':''" :hints="formErrors.is_pph" valueField="id"
        displayField="key" :options="[{'id':true, 'key':'Ya'},{'id':false,'key':'Tidak'}]" placeholder="PPH 23"
        :check="false" fa-icon="sort-desc" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data
      .catatan" :errorText="formErrors.catatan?'failed':''" @input="v=>data
        .catatan=v" :hints="formErrors.catatan" :check="false" label="Catatan" type="textarea"
        placeholder="Tuliskan Catatan" />
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>
  <div class="grid grid-cols-4 place-content-center place-items-center w-[calc(80%)] min-w-220 items-center">

  </div>
  <hr />
  <!-- detail -->

  <!-- START TABLE DETAIL -->

  <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <!-- <ButtonMultiSelect @add="addDetailArr" :api="{
        url: `${store.server.url_backend}/operation/m_coa`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: true, 
            searchfield: 'this.kategori, this.debit_kredit',
            notin: `this.id: ${detailArr.map((det)=> (det.m_coa_id))}`
            },
            onsuccess: (response) => {
              $log(response.data[0]['m_item.id'])
              response.data = [...response.data].map((dt) => {
                return {
                  t_bkk_id: data.id || null,
                  m_coa_id: dt.id,
                  nomor: dt.nomor,
                  nama_coa: dt.nama_coa,
                  kategori: dt.['kategori.deskripsi'],
                  jenis: dt.['jenis.deskripsi'],
                  catatan: '',
                }
              })
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
        }, 
        {
          pinned: false,
          headerName: 'Nomor Coa',
          field: 'nomor',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Nama Coa',
          field: 'nama_coa',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Kategori',
          field: 'kategori',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Jenis',
          field: 'jenis',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, ]">
        <div class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300">
          <icon fa="plus" size="sm" />
          <span>Add To List</span>
        </div>
      </ButtonMultiSelect> -->
      <button class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300" @click="setDetail">
          <icon fa="plus" size="sm" />
          <span>Add To List</span>
      </button>
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              No. Order
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              No. Bukti
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Tgl Bukti
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] w-[5%]">
              Action
            </td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="i" v-show="detailArr.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <td class="p-2 border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].no_order }}</p>
            </td>
            <td class="p-2 border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].no_bukti }}</p>
            </td>
            <td class="p-2 border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].tgl_bukti }}</p>
            </td>
            <td class="p-2 border border-[#CACACA]">
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

  <!-- <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <ButtonMultiSelect @add="addDetailArr" :api="{
        url: `${store.server.url_backend}/operation/m_coa`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: true, 
            searchfield: 'this.kategori, this.debit_kredit',
            notin: `this.id: ${detailArr.map((det)=> (det.m_coa_id))}`
            },
            onsuccess: (response) => {
              response.data = [...response.data].map((dt) => {
                return {
                  t_bll_id: data.id || 0,
                  m_coa_id: dt.id,
                  nomor: dt.nomor,
                  nama_coa: dt.nama_coa,
                  jenis: dt['jenis.deskripsi'],
                  kategori: dt['kategori.deskripsi'],
                  tipe_perkiraan: dt['tipe_perkiraan.deskripsi'],
                  catatan: '',
                }
              })
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
        }, 
        {
          pinned: false,
          headerName: 'No. Akun',
          field: 'nomor',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Nama Akun',
          field: 'nama_coa',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Jenis',
          field: 'jenis',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Kategori',
          field: 'kategori',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        },
        {
          pinned: false,
          headerName: 'Tipe Perkiraan',
          field: 'tipe_perkiraan',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, ]">
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
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Kode AKun
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nama Akun
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Nominal
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Detail
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] w-[5%]">
              Action
            </td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="i" v-show="detailArr.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <td class="p-2 border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].nomor }}</p>
            </td>
            <td class="p-2 border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].nama_coa }}</p>
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.nominal" @input="v=>item.nominal=v" :errorText="formErrors.nominal?'failed':''"
                :hints="formErrors.nominal" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldX type="textarea" :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].keterangan"
                :errorText="formErrors.keterangan?'failed':''" @input="v=>detailArr[i].keterangan=v"
                :hints="formErrors.keterangan" :check="false" />
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
  </div> -->
  <!-- END TABLE DETAIL -->
  <!-- ACTION BUTTON FORM -->
  <hr v-show="actionText" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="actionText">
    <i class="text-gray-500 text-[12px] mr-4">Tekan CTRL + S untuk shortcut Save Data</i>
    <button class="text-sm rounded py-2 px-2.5 text-white bg-red-600 hover:bg-red-700 flex gap-x-1 
        items-center transition-colors duration-300" @click="onReset(true)">
      <icon fa="times" />
      <span>Reset</span>
    </button>
    <button class="text-sm rounded py-2 px-2.5 text-white bg-green-600 hover:bg-green-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="onSave">
      <icon fa="save" />
      <span>Simpan</span>
    </button>
    <button v-show="(((actionText=='Edit' || actionText=='Create'|| actionText=='Copy') && (data.status=='DRAFT' || data.status=='REVISED')))" class="text-sm rounded py-2 px-2.5 text-white bg-purple-600 hover:bg-purple-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="sendPost">
      <icon fa="location-arrow" />
      <span>Send Post</span>
    </button>
  </div>
  <hr v-show="isApproval" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="isApproval">
    <button class="text-sm rounded py-2 px-2.5 text-white  bg-green-600 hover:bg-green-700 flex gap-x-1 items-center 
        transition-colors duration-300" @click="progress('APPROVED')">
      <!-- <icon fa="times" /> -->
      <span>Approve</span>
    </button>
    <button class="text-sm rounded py-2 px-2.5 text-white bg-orange-600 hover:bg-orange-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="progress('REVISED')">
      <!-- <icon fa="save" /> -->
      <span>Revise</span>
    </button>
    <button class="text-sm rounded py-2 px-2.5 text-white  bg-red-600 hover:bg-red-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="progress('REJECTED')">
      <!-- <icon fa="save" /> -->
      <span>Reject</span>
    </button>
  </div>
</div>

@endverbatim
@endif