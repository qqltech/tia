<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center px-2.5 py-1">
    <!-- FILTER -->
        <!-- FILTER -->
        <div class="flex items-center gap-x-2">
            <p>Filter Status :</p>
            <div class="flex gap-x-2">
                <button @click="filterShowData('DRAFT')" :class="activeBtn?.toUpperCase() === 'DRAFT'?'bg-gray-600 font-semibold !text-white hover:bg-gray-400':'border border-gray-600 text-gray-600 bg-white  hover:bg-gray-600 hover:text-white'" class="duration-300 transform transition hover:-translate-y-0.5 rounded-md py-1 px-2">DRAFT</button>
                <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
                <button @click="filterShowData('POST')" :class="activeBtn?.toUpperCase() === 'POST'?'bg-amber-600 !text-white hover:bg-amber-400':'border border-amber-600 font-semibold bg-white text-amber-600  hover:bg-amber-600 hover:text-white'" class="duration-300 transition transform hover:-translate-y-0.5 rounded-md py-1 px-2">POST</button>
                <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
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
        <h1 class="text-20px font-bold">BKM</h1>
        <p class="text-gray-100">Transaksi BKM</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
        :value="values.m_business_unit_id" @input="v=>{
            if(v){
              values.m_business_unit_id=v
            }else{
              values.m_business_unit_id=null
            }
          }" :errorText="formErrors.m_business_unit_id?'failed':''" :hints="formErrors.m_business_unit_id"
        valueField="id" displayField="nama" :api="{
              url: `${store.server.url_backend}/operation/m_business_unit`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                searchfield: 'nama',
                where:`this.is_active=true`

              }
          }" placeholder="Pilih Bussiness Unit" label="Bussiness Unit" :check="true" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_draft" :errorText="formErrors.no_draft?'failed':''"
        @input="v=>values.no_draft=v" :hints="formErrors.no_draft" label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_bkm" :errorText="formErrors.no_bkm?'failed':''"
        @input="v=>values.no_bkm=v" :hints="formErrors.no_bkm" label="No. BKM" placeholder="No. BKM" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText, disabled:!actionText, clearable:false }" class="w-full !mt-3"
        :value="values.tanggal" :errorText="formErrors.tanggal?'failed':''"
        @input="v=>values.tanggal=v"  :hints="formErrors.tanggal" 
        :check="false"
        type="date"
        label="Tanggal BKM"
        placeholder="Pilih Tanggal BKM"
      />
    </div>
    <div>
      <FieldPopup 
          label="No. Buku Order"
          class="w-full !mt-3"
          valueField="id" displayField="no_buku_order"
          :value="values.t_buku_order_id" @input="(v)=>values.t_buku_order_id=v"
          :errorText="formErrors.t_buku_order_id?'failed':''"
          :hints="formErrors.t_buku_order_id"
          @update:valueFull="(v)=>{
            detailArr = [] 
            values.total_amt = 0
          }"
          :api="{
            url: `${store.server.url_backend}/operation/t_buku_order`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              //where: `this.status='POST'`,
              //selectfield: 'id,tanggal,no_po,m_supplier.nama,m_supplier.alamat,m_supplier.kota',
              searchfield: 'this.tgl, this.no_buku_order,m_customer.nama_perusahaan,m_customer.alamat,m_customer.kota'
            }
          }"
          placeholder="Pilih No. Buku Order"
          :check="false" 
          :columns="[{
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
            field: 'tgl',
            headerName:  'Tanggal',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          },
          {
            flex: 1,
            field: 'm_customer.nama_perusahaan',
            headerName:  'Customer',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-start']
          },
          {
            flex: 1,
            field: 'm_customer.alamat',
            headerName:  'Alamat Customer',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-start']
          },
          {
            flex: 1,
            field: 'm_customer.kota',
            headerName:  'Kota',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-start']
          }
          ]"
        />
    </div>
    <div>
      <FieldPopup 
          label="Akun Pembayaran"
          class="w-full !mt-3"
          valueField="id" displayField="nama_coa"
          :value="values.m_akun_pembayaran_id" @input="(v)=>values.m_akun_pembayaran_id=v"
          :errorText="formErrors.m_akun_pembayaran_id?'failed':''"
          :hints="formErrors.m_akun_pembayaran_id"
          :api="{
            url: `${store.server.url_backend}/operation/m_coa`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              transform:false,
              join: 'INNER JOIN kategori ON kategori.id = m_coa.kategori_id',
              searchfield: 'nama_coa,nomor',
              selectfield: 'nama_coa,id,nomor',
              where: `this.is_active = true AND kategori.deskripsi = 'MODAL'`
            }
          }"
          placeholder="Pilih Akun Pembayaran"
          :check="false" 
          :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'nomor',
            headerName:  'Kode Akun',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-start']
          },
          {
            flex: 1,
            field: 'nama_coa',
            headerName:  'Nama Akun',
            sortable: false, resizable: true, filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-start']
          }
          ]"
        />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full !mt-3" :value="values.total_amt"
        :errorText="formErrors.total_amt?'failed':''" @input="v=>values.total_amt=v" :hints="formErrors.total_amt"
        label="Total Amt" placeholder="Total Amt" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" type="textarea" :value="values.keterangan"
        :errorText="formErrors.keterangan?'failed':''" @input="v=>values.keterangan=v" :hints="formErrors.keterangan"
        label="Catatan" placeholder="Catatan" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
        :value="values.status" :errorText="formErrors.status?'failed':''"
        @input="v=>values.status=v" :hints="formErrors.status" 
        :check="false"
        label="Status"
        placeholder="Status"
      />
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>

  <!-- detail -->
  <div class="p-4">
    <ButtonMultiSelect
      title="Add Detail"
      @add="onDetailAdd"
      :api="{
        url: `${store.server.url_backend}/operation/m_coa`,
          headers: {'Content-Type': 'Application/json', authorization: `${store.user.token_type} ${store.user.token}`},
          params: { 
            simplest: false,
            join:true,
            searchfield:'this.nama_coa, this.nomor',
            where: `this.is_active=true`,
            notin:detailArr.length>0?`this.id:${detailArr.map(dt=>dt.m_coa_id).join(',')}`:null },
          onsuccess:(response)=>{
            response.data = [...response.data].map((dt)=>{
              Object.keys(dt).forEach(k=>dt['cost.'+k] = dt[k])
              dt['kode'] = dt['nomor']
              dt['nama'] = dt['nama_coa']
              dt['m_coa_id'] = dt['id']
            return dt
          })
          response.page = response.current_page
          response.hasNext = response.has_next
          return response
        }
      }"
        :columns="[{
          checkboxSelection: true,
          headerCheckboxSelection: true,
          headerName: 'No',
          valueGetter:(params)=>{
            return ''
          },
          width:60,
          sortable: false, resizable: true, filter: false,
          cellClass: ['justify-start', 'bg-gray-50', '!border-gray-200']
        },
        {
            flex: 1,
            headerName:'Kode Akun',
            sortable: false, resizable: true, filter: false,
            field: 'nomor',
            cellClass: ['justify-start','!border-gray-200']
          },
          {
            flex: 1,
            headerName:'Nama Akun',
            sortable: false, resizable: true, filter: false,
            field: 'nama_coa',
            cellClass: ['justify-start','!border-gray-200']
          },
        ]"
      >
        <div class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
          <icon fa="plus" size="sm mr-0.5"/> Add Detail
        </div>
      </ButtonMultiSelect>

    <div class="mt-4">
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA]">
        <thead>
          <tr class="border">
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-start w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Kode Akun
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
              Keterangan
            </td>
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] w-[5%]">
              Action
            </td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.kode" :check="false"
                @input="v=>item.kode=v" :errorText="formErrors.kode?'failed':''" :hints="formErrors.kode" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ readonly: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.nama" :check="false"
                @input="v=>item.nama=v" :errorText="formErrors.nama?'failed':''" :hints="formErrors.nama" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldNumber :bind="{ readonly: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                :value="item.nominal" @input="v=>inputValue(v,item)" :errorText="formErrors.nominal?'failed':''" :check="false"
                :hints="formErrors.nominal" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <FieldX :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0" :value="item.keterangan" :check="false"
                @input="v=>item.keterangan=v" :errorText="formErrors.keterangan?'failed':''" :hints="formErrors.keterangan"
                type="textarea" />
            </td>
            <td class="p-2 border border-[#CACACA]">
              <div class="flex justify-center">
                <button type="button" @click="removeDetail(i)" :disabled="!actionText" title="Hapus">
                      <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                      </svg>
                    </button>
              </div>

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
  </div>
</div>
@endverbatim
@endif