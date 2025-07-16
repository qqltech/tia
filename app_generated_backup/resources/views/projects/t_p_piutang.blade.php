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
  <div class="flex items-center gap-x-4"></div>
</div>

  <!-- TABLE -->
<hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions" class="max-h-[450px] pt-2 !px-4 !pb-8">
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
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack"/>
        <div>
          <h1 class="text-20px font-bold">Form Pembayaran Piutang</h1>
          <p class="text-gray-100">Untuk Melakukan Pembayaran Piutang</p>
        </div>
      </div>
    </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row p-4 gap-3">
        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0"
            :bind="{ readonly: true }" 
            :value="values.no_draft" :errorText="formErrors.no_draft?'failed':''"
            @input="v=>values.no_draft=v" 
            :hints="formErrors.no_draft" 
            label=""
            placeholder="No Draft" 
            :check="false"
          />
        </div>
        <div class="w-full !mt-3">
          <FieldX
            class="!mt-0"
            :bind="{ readonly: true }" 
            :value="values.no_pembayaran" :errorText="formErrors.no_pembayaran?'failed':''"
            @input="v=>values.no_pembayaran=v"
            :hints="formErrors.no_pembayaran" 
            placeholder="No. Pembayaran" :check="false"
          />
        </div>
        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0" 
            :bind="{ readonly: true}" 
            :value="values.tanggal" :errorText="formErrors.tanggal?'failed':''" 
            @input="v=>values.tanggal=v" 
            :hints="formErrors.tanggal" 
            placeholder="Tanggal" 
            :check="false" 
            type="date" 
          />
        </div>
        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0" 
            :bind="{ readonly: !actionText}" 
            :value="values.tanggal_pembayaran" :errorText="formErrors.tanggal_pembayaran?'failed':''" 
            @input="v=>values.tanggal_pembayaran=v" 
            :hints="formErrors.tanggal_pembayaran" 
            placeholder="Tanggal Pembayaran" 
            :check="false" 
            type="date" 
          />
        </div>
        <div class="w-full !mt-3">
          <FieldPopup
            class="!mt-0"
            :bind="{ readonly: !actionText }"
            :value="values.customer" @input="(v)=>values.customer=v"
            :errorText="formErrors.customer?'failed':''" 
            :hints="formErrors.customer" 
            valueField="id" displayField="nama_perusahaan"
            :api="{
              url: `${store.server.url_backend}/operation/m_customer`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
              }
            }"
            placeholder="Customer" :check="false" 
            :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'nama_perusahaan',
              headerName:  'Nama Customer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            }]"
          />
          
        </div>
        <div class="w-full !mt-3">
          <FieldSelect
            class="!mt-0"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.m_akun_pembayaran_id" @input="v=>values.m_akun_pembayaran_id=v"
            :errorText="formErrors.m_akun_pembayaran_id?'failed':''" 
            :hints="formErrors.m_akun_pembayaran_id"
            valueField="id" displayField="nama_coa"
            :api="{
                url: `${store.server.url_backend}/operation/m_coa`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  transform:false,
                  join:false
                }
            }"
            placeholder="Pilih Akun Pembayaran" label="Akun Pembayaran" 
            fa-icon="caret-down"
            :check="false"
          />
          
        </div>
        <div class="w-full !mt-3">
          <FieldSelect
            class="!mt-0"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.tipe_pembayaran" @input="v=>values.tipe_pembayaran=v"
            :errorText="formErrors.tipe_pembayaran?'failed':''" 
            :hints="formErrors.tipe_pembayaran"
            valueField="id" displayField="deskripsi"
            :api="{
                url: `${store.server.url_backend}/operation/m_general`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  where: `this.group='TIPE PEMBAYARAN'`
                }
            }"
            placeholder="Pilih Tipe Pembayaran" label="Tipe Pembayaran" 
            fa-icon="caret-down"
            :check="false"
          />
          
        </div>
        <div class="w-full !mt-3">
          <FieldX
            class="!mt-0"
            :bind="{ readonly: true }"
            :value="values.total_amt" @input="v=>values.total_amt=v"
            :errorText="formErrors.total_amt?'failed':''" 
            :hints="formErrors.total_amt"
            placeholder="Total Amount" 
            :check="false"
            />
        </div>
        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.catatan" :errorText="formErrors.catatan?'failed':''"
            @input="v=>values.catatan=v" 
            :hints="formErrors.catatan" 
            type="textarea"
            placeholder="Catatan" 
            :check="false"
          />
        </div>
        <div class="w-full !mt-3">
          <FieldSelect
            class="!mt-0" 
            :bind="{ disabled: true }" 
            :value="values.status" 
            :errorText="formErrors.status ? 'failed' : ''"
            @input="v => values.status = v" 
            :hints="formErrors.status"
            valueField="id" displayField="key"
            :options="[
              { 'id': 'DRAFT', 'key': 'DRAFT' },
              { 'id': 'POST', 'key': 'POST' },
              { 'id': 'IN APRROVAL', 'key': 'IN APRROVAL' },
              { 'id': 'APPROVED', 'key': 'APPROVED' },
              { 'id': 'REJECTED', 'key': 'REJECTED' },
              { 'id': 'REVISED', 'key': 'REVISED' },
            ]"
            label="Status"
            placeholder="Status" 
            :check="false"
          />
        </div>
      </div>

      <!-- START TABLE -->
      <hr>
      <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
      <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
        <table class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
          <thead>
            <tr class="border">
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[2%] border bg-[#f8f8f8] border-[#CACACA]">No.</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center border bg-[#f8f8f8] border-[#CACACA]">No. Tagihan</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Tgl. Tagihan</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Tgl. JT</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Nilai Piutang</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Dibayar</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Sisa Piutang</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Total Bayar</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Catatan</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[2%] border bg-[#f8f8f8] border-[#CACACA]">Action</td>
            </tr>
          </thead>
          <tbody>
            <tr v-if="detailArr.length === 0" class="text-center">
              <td colspan="6" class="py-[20px] justify-center items-center">No data to show</td>
            </tr>
            <tr v-else v-for="(item, index) in detailArr" :key="index" class="border">
              <td class="p-2 text-center border border-[#CACACA]">{{ index + 1 }}</td>
              <td class="p-2 text-center border border-[#CACACA]">{{item.no_tagihan}}</td>
              <td class="p-2 text-center border border-[#CACACA]">{{item.tgl}}</td>
              <td class="p-2 text-center border border-[#CACACA]">{{item.tgl_jt}}</td>
              <td class="p-2 text-center border border-[#CACACA]">{{item.nilai_piutang}}</td>
              <td class="p-2 text-center border border-[#CACACA]">{{item.bayar}}</td>
              <td class="p-2 text-center border border-[#CACACA]">{{item.sisa_piutang}}</td>
              <td class="p-2 text-center border border-[#CACACA]">
                <FieldNumber
                  :bind="{ readonly: !actionText }"
                  :value="values.total_bayar" @input="(v)=>values.total_bayar=v"
                  :errorText="formErrors.total_bayar?'failed':''" 
                  :hints="formErrors.total_bayar"
                  placeholder="Total Bayar" 
                  label=""
                  :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA] text-center max-w-52 truncate">
                <FieldX :bind="{ readonly: !actionText }" 
                  :value="values.catatan" :errorText="formErrors.catatan?'failed':''"
                  @input="v=>values.catatan=v" :hints="formErrors.catatan" 
                  placeholder="Catatan" label="" :check="false"
                />
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
      <!-- END TABLE -->

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
      <button class="text-sm rounded-md py-2 px-3 text-white bg-yellow-600 hover:bg-yellow-700 flex gap-x-1 items-center
        transition-colors duration-300" v-show="actionText" @click="onSave(true)">
            <icon fa="paper-plane" />
            <span>Post</span>
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