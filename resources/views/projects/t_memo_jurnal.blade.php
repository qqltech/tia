<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="p-2"> 
    <h1 class="text-xl font-semibold">Memo Journal</h1>
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
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions" class="max-h-[450px]">
    <!-- <template #header>
    </template> -->
  </TableApi>
</div>
@else

<!-- CONTENT -->
@verbatim
  <div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
    <div class="bg-gray-500 text-white rounded-t-md py-2 px-4">
      <div class="flex items-center">
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali" @click="onBack"/>
        <div>
          <h1 class="text-20px font-bold">Form Memo Journal</h1>
          <p class="text-gray-100">Transaksi Memo Journal</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-2 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.no_memo" :errorText="formErrors.no_memo?'failed':''"
          @input="v=>values.no_memo=v" :hints="formErrors.no_memo" 
          label="No. Memo"
          placeholder="No. Memo"
          :check="false"
        />
      </div>
      <div>
         <FieldSelect
          :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
          :value="values.divisi"  @input="v=>{
            if(v){
              values.divisi=v
            }else{
              values.divisi=null
            }
          }"
          :errorText="formErrors.divisi?'failed':''" 
          :hints="formErrors.divisi"
          valueField="id" displayField="deskripsi"
          :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:`this.is_active=true and this.group='DIVISI INTERFACE'`
              }
          }"
          placeholder="Pilih Divisi" label="Divisi" :check="true"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.tanggal_memo" :errorText="formErrors.tanggal_memo?'failed':''"
          @input="v=>values.tanggal_memo=v" :hints="formErrors.tanggal_memo" 
          :check="false"
          type="date"
          label="Tanggal"
          placeholder="Pilih Tanggal"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.catatan" :errorText="formErrors.catatan?'failed':''"
          @input="v=>values.catatan=v" :hints="formErrors.catatan" 
          :check="false"
          label="Catatan"
          type="textarea"
          placeholder="Catatan"
        />
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
                where:'this.is_active=true',
                searchfield: 'this.nomor,this.nama_coa,kategori.deskripsi,jenis.deskripsi',
                notin:detailArr.length>0?`this.id:${detailArr.map(dt=>dt.m_coa_id).join(',')}`:null },
              onsuccess:(response)=>{
                response.data = [...response.data].map((dt)=>{
                  Object.keys(dt).forEach(k=>dt['coa.'+k] = dt[k])
                  dt['m_coa_id'] = dt['id']
                  dt['debit'] = 0
                  dt['credit'] = 0
                  dt['nama_coa'] = dt['nama_coa']
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
                headerName:'No. COA',
                sortable: false, resizable: true, filter: false,
                field: 'nomor',
                cellClass: ['justify-start','!border-gray-200']
              },
              {
                flex: 1,
                headerName:'Nama',
                sortable: false, resizable: true, filter: false,
                field: 'nama_coa',
                cellClass: ['justify-start','!border-gray-200']
              },
              {
                flex: 1,
                headerName:'Kategori',
                sortable: false, resizable: true, filter: false,
                field: 'kategori.deskripsi',
                cellClass: ['justify-start','!border-gray-200']
              },
              {
                flex: 1,
                headerName:'Jenis',
                sortable: false, resizable: true, filter: false,
                field: 'jenis.deskripsi',
                cellClass: ['justify-start','!border-gray-200']
              }
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
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                No.
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                COA
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Debet
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Credit
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Catatan
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
                <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
                  :value="item.nama_coa" :errorText="formErrors.nama_coa?'failed':''"
                  @input="v=>item.nama_coa=v" :hints="formErrors.nama_coa" 
                  :check="false"
                  label=""
                  placeholder="COA"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
                  :value="item.debit" :errorText="formErrors.debit?'failed':''"
                  @input="v=>item.debit=v" :hints="formErrors.debit" 
                  :check="false"
                  label=""
                  placeholder="Debet"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
                  :value="item.credit" :errorText="formErrors.credit?'failed':''"
                  @input="v=>item.credit=v" :hints="formErrors.credit" 
                  :check="false"
                  label=""
                  placeholder="Credit"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
                  :value="item.catatan" :errorText="formErrors.catatan?'failed':''"
                  @input="v=>item.catatan=v" :hints="formErrors.catatan" 
                  :check="false"
                  label=""
                  placeholder="Catatan"
                />
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