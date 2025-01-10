<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-blue-600 text-white hover:bg-blue-600' 
          : 'border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          DRAFT
        </button>
        <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          POSTED
        </button>
        <!-- <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('In Approval')" :class="filterButton === 'In Approval' ? 'bg-sky-600 text-white hover:bg-sky-600' 
          : 'border border-sky-600 text-sky-600 bg-white hover:bg-sky-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          In Approval
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('In Process')" :class="filterButton === 'In Process' ? 'bg-yellow-600 text-white hover:bg-yellow-600' 
          : 'border border-yellow-600 text-yellow-600 bg-white hover:bg-yellow-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          In Process
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('Complete')" :class="filterButton === 'Complete' ? 'bg-purple-600 text-white hover:bg-purple-600' 
          : 'border border-purple-600 text-purple-600 bg-white hover:bg-purple-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          Complete
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData('Cancel')" :class="filterButton === 'Cancel' ? 'bg-red-600 text-white hover:bg-red-600' 
          : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'"
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          Cancel
        </button> -->
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
        <h1 class="text-lg font-bold leading-none">Form Rencana Pembayaran Hutang</h1>
        <p class="text-gray-100 leading-none">Transaction Rencana Pembayaran Hutang</p>
      </div>
    </div>
  </div>
  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 items-start">
    <!-- col-span-2 -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>data.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.no_rph"
        :errorText="formErrors.no_rph?'failed':''" @input="v=>data.no_rph=v" :hints="formErrors.no_rph" label="No. RPH"
        placeholder="No. RPH" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.status"
        :errorText="formErrors.status?'failed':''" @input="v=>data.status=v" :hints="formErrors.status" label="Status"
        placeholder="Status" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.tgl"
        :errorText="formErrors.tgl?'failed' :''" @input="v=>data.tgl=v" :hints="formErrors.tgl" :check="false"
        type="date" label="Tanggal" placeholder="Pilih Tanggal" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="data.total_pi"
        :errorText="formErrors.total_pi?'failed':''" @input="v=>data.total_pi=v" :hints="formErrors.total_pi"
        label="Total PI" placeholder="Total PI" :check="false" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="data.total_bayar"
        :errorText="formErrors.total_bayar?'failed':''" @input="v=>data.total_bayar=v" :hints="formErrors.total_bayar"
        label="Total Bayar" placeholder="Total Bayar" :check="false" />
    </div>
    <div>
      <FieldX type="textarea" :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="data.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>data.catatan=v" :hints="formErrors.catatan"
        label="Catatan" placeholder="Masukan Catatan" :check="false" />
    </div>


  </div>
  <hr />
  <!-- START TABLE DETAIL -->

  <div class="<md:col-span-1 col-span-3 p-2 grid <md:grid-cols-1 grid-cols-3 gap-2">
    <div class="overflow-x-auto <md:col-span-1 col-span-3">
      <div>
        <FieldPopup class="w-full !mt-3 mb-3 w-[calc(50%)]" :api="{
        url: `${store.server.url_backend}/operation/m_supplier`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest:false,
          transform:false,
          join:false,
          // override:true,
          // where:`this.is_active=true`,
          // searchfield:'this.no_id, this.nip, this.nama, this.alamat_domisili',
          // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili' 
        },
        onsuccess: (response) => {
          return response;
        }
      }" displayField="nama" valueField="id" :bind="{ readonly: !actionText }" :value="data.m_supplier_id"
          @input="(v)=>data.m_supplier_id=v" @update:valueFull="(response)=>{
             // detailArr = [];
            if(response) {
              data.nama_supplier = response.nama;
              data.top = response.top;
            }
            else {
              data.nama_supplier = '';
              data.top = 0;
            }
        $log(response);
      }" :errorText="formErrors.m_supplier_id?'failed':''" class="w-full !mt-3" :hints="formErrors.m_supplier_id"
          placeholder="Pilih Supplier" :check='false' :columns="[
        {
          headerName: 'No',
          valueGetter:(p)=>p.node.rowIndex + 1,
          width: 60,
          sortable: false, resizable: false, filter: false,
          cellClass: ['justify-center', 'bg-gray-50']
        },
        {
          flex: 1,
          field: 'kode',
          headerName: 'Kode',
          sortable: true, resizable: true, filter: false,
          cellClass: ['border-r', '!border-gray-200', 'justify-center']
        },
        {
          headerName: 'Nama',
          field: 'nama',
          flex: 1,
          cellClass: ['border-r', '!border-gray-200', 'justify-start',],
          sortable: true, resizable: true, filter: false,
        },
      ]" />
      </div>

      <ButtonMultiSelect @add="addDetailArr" :api="{
        url: `${store.server.url_backend}/operation/m_supplier/${data.m_supplier_id}`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: false,
            view_hutang: true,
            searchfield: 'this.kode, this.nama',
            notin: `t_jurnal_angkutan.id: ${detailArr.map((det)=> (det.t_jurnal_angkutan_id))}`
            },
            onsuccess: (response) => {
              response.data = [...response.data.data].map((dt) => {
                if(dt.no_jurnal){
                  // const dueDate = new Date(dt.tgl);
                  // dueDate.setDate(dueDate.getDate() + response.data.top);
                  return {
                    t_jurnal_angkutan_id: dt.id,  
                    no_referensi: dt.['no_jurnal'],
                    tanggal: dt.tgl,
                    tipe_tagihan: 'Jurnal Angkutan',
                    grand_total: dt['grand_total'],

                    m_supplier_id: data.m_supplier_id,
                    nama_supplier: data.nama_supplier,
                    top: data.top,
                    //no_tagihan
                    //supplier
                    //tgl_jatuh_tempu
                    //grand_total_amount
                    //jumlah_bayar
                    //keterangan
                    //tgl_realisasi
                    //jml_realisasi
                    //cara_bayar
                    //status
                    catatan: '',
                  }
                }
                else if(dt.no_pi)
                return {
                  t_purchase_invoice_id: dt.id,  
                  no_referensi: dt.['no_pi'],
                  tanggal: dt.tanggal,
                  tipe_tagihan: 'Purchase',
                  grand_total : dt['grand_total'],

                  m_supplier_id: data.m_supplier_id,
                  nama_supplier: data.nama_supplier,
                  top: data.top,

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
          headerName: 'No. Referensi',
          field: 'no_referensi',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Tanggal',
          field: 'tanggal',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Tipe Tagihan',
          field: 'tipe_tagihan',
          cellClass: ['border-r', '!border-gray-200', 'justify-center'],
          filter: 'ColFilter',
          flex: 1
        }, 
        {
          pinned: false,
          headerName: 'Total',
          field: 'grand_total',
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
              No.</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              No. Referensi</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Supplier</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Tgl Jatuh Tempo</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Jumlah</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Jumlah Bayar</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Keterangan</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Tgl Realisasi</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Jumlah Realisasi</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Cara Bayar</td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Status</td>
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
              <p class="text-black leading-none">{{ detailArr[i].no_referensi }}</p>
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].nama_supplier }}</p>
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].tgl_jatuh_tempo }}</p>
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].grand_total }}</p>
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].jumlah_bayar"
                :errorText="formErrors.jumlah_bayar?'failed':''" @input="v=>detailArr[i].jumlah_bayar=v"
                :hints="formErrors.jumlah_bayar" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <FieldX type="textarea" :bind="{ readonly: !actionText }" class="m-0" :value="detailArr[i].keterangan"
                :errorText="formErrors.keterangan?'failed':''" @input="v=>detailArr[i].keterangan=v"
                :hints="formErrors.keterangan" :check="false" />
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].tgl_realisasi }}</p>
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].jml_realisasi }}</p>
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].cara_bayar }}</p>
            </td>
            <td class="p-1 text-center border border-[#CACACA]">
              <p class="text-black leading-none">{{ detailArr[i].status }}</p>
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
  <!-- END TABLE DETAIL -->

  <!-- ACTION BUTTON FORM -->
  <hr v-show="actionText" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="actionText">
    <i class="text-gray-500 text-[12px] mr-4">Tekan CTRL + S untuk shortcut Save Data</i>
    <button v-if="data.status =='DRAFT'" class="text-sm rounded py-2 px-2.5 text-white bg-orange-600 hover:bg-orange-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="onSave('post')">
      <icon fa="location-arrow" />
      <span>Post</span>
    </button>
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
  </div>
</div>

@endverbatim
@endif