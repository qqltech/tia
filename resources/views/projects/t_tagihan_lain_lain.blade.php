<!-- LANDING -->
@if(!$req->has('id'))

<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="pl-4 pt-2 pb-2">
    <h1 class="text-xl font-semibold">TAGIHAN LAIN-LAIN</h1>
  </div>
  <div class="flex justify-between items-center px-4 py-1">
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
        <h1 class="text-20px font-bold">Tagihan</h1>
        <p class="text-gray-100">Transaksi Tagihan</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- No. Draft Coloumn -->
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3 w-full" :value="values.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>values.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft Coloumn" placeholder="No. Draft Coloumn" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3 w-full" :value="values.no_tagihan_lain_lain"
        :errorText="formErrors.no_tagihan_lain_lain?'failed':''" @input="v=>values.no_tagihan_lain_lain=v"
        :hints="formErrors.no_tagihan_lain_lain" label="No.Tagihan Lain Lain" placeholder="No.Tagihan Lain Lain"
        :check="false" />
    </div>

    <!-- Date Coloumn -->
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tgl"
        :errorText="formErrors.tgl?'failed':''" @input="v=>values.tgl=v" :hints="formErrors.tgl"
        placeholder="Masukkan Tanggal" :check="false" type="date" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.tgl_nota"
        :errorText="formErrors.tgl_nota?'failed':''" @input="v=>values.tgl_nota=v" :hints="formErrors.tgl_nota"
        placeholder="Masukkan Tanggal Nota" :check="false" type="date" />
    </div>

    <div>
      <FieldPopup label="No. Buku Order" :bind="{ readonly: !actionText , clearable: true }" class="w-full !mt-3"
        valueField="id" displayField="no_buku_order" :value="values.no_buku_order" @input="(v)=> values.no_buku_order=v"
        @update:valueFull="buku" :api="{
              url: `${store.server.url_backend}/operation/t_buku_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                view_tarif:true,
                join:true,
                simplest:true,
                scopes:'NotDuplicate',
                searchfield: 'this.tgl , this.no_buku_order , this.jenis_barang , m_customer.nama_perusahaan'
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
              field: 'tgl',
              headerName:  'Tanggal',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'no_buku_order',
              headerName:  'Nomor Buku Order',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'jenis_barang',
              headerName:  'Jenis Barang',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
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
              field: 'status',
              headerName:  'Status',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
    </div>


    <!-- Customer -->
    <div>
      <FieldSelect :bind="{ disabled: true, clearable:true }" class="w-full !mt-3" :value="values.customer"
        @input="v=>values.customer=v" :errorText="formErrors.customer?'failed':''" :hints="formErrors.customer"
        valueField="id" displayField="kode" :api="{
              url: `${store.server.url_backend}/operation/m_customer`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                transform:false,
                join:false,
                where:'this.is_active=true'
              }
          }" placeholder="Customer" label="Customer" :check="true" />
    </div>

    <!-- PIUTANG -->
    <div>
        <FieldNumber :bind="{ readonly: !actionText}" class="w-full !mt-3" :value="values.piutang"
          :errorText="formErrors.piutang?'failed':''" @input="v=>values.piutang=v"
          :hints="formErrors.piutang" label="Piutang" placeholder="Piutang" :check="false" />
      </div>


    <!-- Tax Coloumn -->
    <div class="flex space-x-2">
      <span class="text-lg font-semibold items-center !mt-3">%</span>
      <FieldNumber :bind="{ readonly: !actionText  }" class=" !mt-3 w-full" :value="values.ppn"
        :errorText="formErrors.ppn?'failed':''" @input="v=>values.ppn=v" :hints="formErrors.ppn" label="ppn"
        placeholder="masukan Persentase PPN" :check="false" />

    </div>

    <!-- Total Amount Coloumn -->
    <div class="hidden">
      <div>
        <FieldNumber :bind="{ readonly: true}" class="w-full !mt-3" :value="values.grand_total"
          :errorText="formErrors.grand_total?'failed':''" @input="v=>values.grand_total=v"
          :hints="formErrors.grand_total" label="Total Amount" placeholder="Total Amount" :check="false" />
      </div>
      <!-- Grand Total Amount Coloumn -->
      <div>
        <FieldNumber :bind="{ readonly: true}" class="w-full !mt-3" :value="values.grand_total_amount"
          :errorText="formErrors.grand_total_amount?'failed':''" @input="v=>values.grand_total_amount=v"
          :hints="formErrors.grand_total_amount" label="Grand Total Amount" placeholder="Grand Total Amount"
          :check="false" />
      </div>
      <!-- Grand Total Nota Rampung -->
      <div>
        <FieldNumber :bind="{ readonly: true}" class="w-full !mt-3" :value="values.grand_total_nota_rampung"
          :errorText="formErrors.grand_total_nota_rampung?'failed':''" @input="v=>values.grand_total_nota_rampung=v"
          :hints="formErrors.grand_total_nota_rampung" label="Grand Total nota_rampung"
          placeholder="Grand Total nota_rampung" :check="false" />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true}" class="w-full !mt-3" :value="values.total_kontainer"
          :errorText="formErrors.total_kontainer?'failed':''" @input="v=>values.total_kontainer=v"
          :hints="formErrors.total_kontainer" label="Grand Total nota_rampung" placeholder="Grand Total nota_rampung"
          :check="false" />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true}" class="w-full !mt-3" :value="values.total_lain"
          :errorText="formErrors.total_lain?'failed':''" @input="v=>values.total_lain=v" :hints="formErrors.total_lain"
          label="Grand Total nota_rampung" placeholder="Grand Total nota_rampung" :check="false" />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true}" class="w-full !mt-3" :value="values.total_ppn"
          :errorText="formErrors.total_ppn?'failed':''" @input="v=>values.total_ppn=v" :hints="formErrors.total_ppn"
          label="Grand Total nota_rampung" placeholder="Grand Total nota_rampung" :check="false" />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true}" class="w-full !mt-3" :value="values.total_setelah_ppn"
          :errorText="formErrors.total_setelah_ppn?'failed':''" @input="v=>values.total_setelah_ppn=v"
          :hints="formErrors.total_setelah_ppn" label="Grand Total nota_rampung" placeholder="Grand Total nota_rampung"
          :check="false" />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: true}" class="w-full !mt-3" :value="values.total_tarif_jasa"
          :errorText="formErrors.total_tarif_jasa?'failed':''" @input="v=>values.total_tarif_jasa=v"
          :hints="formErrors.total_tarif_jasa" label="Grand Total nota_rampung" placeholder="Grand Total nota_rampung"
          :check="false" />
      </div>
    </div>


    <!-- No. Faktur Pajak -->
    <!-- <div>

      <FieldX :bind="{ readonly: false }" class="w-full !mt-3 w-full" :value="values.no_faktur_pajak"
        :errorText="formErrors.no_faktur_pajak?'failed':''" @input="v=>values.no_faktur_pajak=v"
        :hints="formErrors.no_faktur_pajak" label="No. Faktur Pajak" placeholder="No. Faktur Pajak" :check="false" />
    </div> -->

    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        label="Catatan" placeholder="Catatan" :check="false" type="textarea" />
    </div>


    <div>
      <FieldX class="w-full !mt-3" :bind="{ disabled: true, clearable:true }" :value="values.status"
        @input="v=>values.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status"
        placeholder="Pilih Status" label="Status" :check="false" />
    </div>


  </div>

  <!-- detail -->

  <div class="p-4">
    <!-- Tombol Generate Total -->
    <button @click="generateTotal" v-show="actionText" class="p-2 bg-green-500 text-white rounded-lg font-semibold mb-4">
    Generate Total
  </button>


    <table class="w-full bg-white rounded-lg shadow-md overflow-hidden">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-3 text-left  border-r ext-sm font-semibold text-gray-700 w-1/2">Keterangan</th>
          <th class="p-3 text-left text-sm font-semibold text-gray-700 w-1/2">Jumlah</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-b border-gray-200 hover:bg-gray-50">
          <td class="p-3 border-r text-sm text-gray-700">Total Lain-lain (PPN)</td>
          <td class="p-3 text-sm text-gray-900">{{ formatCurrency(values.total_amount_ppn) || 0 }}</td>
        </tr>
        <tr class="border-b border-gray-200 hover:bg-gray-50">
          <td class="p-3 text-sm border-r text-gray-700">Total PPN</td>
          <td class="p-3 text-sm text-gray-900">{{ formatCurrency(values.total_ppn) || 0 }}</td>
        </tr>
        <tr class="border-b border-gray-200 hover:bg-gray-50">
          <td class="p-3 text-sm border-r text-gray-700">Total Lain-lain (NON PPN)</td>
          <td class="p-3 text-sm text-gray-900">{{ formatCurrency(values.total_amount_non_ppn) || 0 }}</td>
        </tr>
        <tr class=" border-gray-200 hover:bg-gray-50">
          <td class="p-3 text-sm border-r text-gray-700">Grand Total Amount</td>
          <td class="p-3 text-sm text-gray-900">{{ formatCurrency(values.grand_total_amount) || 0 }}</td>
        </tr>


      </tbody>
    </table>
  </div>





  <hr>

  <!-- TAB UNTUK DETAIL -->
  <!-- Tab Tarif Jasa Tia -->
  <!-- Tab Tarif Lain-Lain -->
  <!-- Tab Detail AJU -->
  <!-- Detail NPWP -->
  <!-- Detail Tarif Jasa Tia -->
  <!-- Detail Lain-Lain -->
  <div class="md:col-span-1 col-span-3 p-4 bg-white rounded-lg shadow-md">
    <div class="overflow-x-auto">
      <button v-show="actionText" type="button" @click="addDetail3" class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-2 mb-4">
      <icon fa="plus" />
      Add to List
    </button>

      <table class="w-full bg-white rounded-lg shadow-md overflow-hidden">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-3 text-left border-r text-sm font-semibold text-gray-700 w-[5%]">No.</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[20%]">Keterangan</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[15%]">Tarif Realisasi</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[10%]">QTY</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[10%]">PPN</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[10%]">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr3" :key="item.id" class="border-b border-gray-200 hover:bg-gray-50"
            v-if="detailArr3.length > 0">
            <td class="p-3 text-sm text-gray-700 text-center border-r">{{ i + 1 }}.</td>

            <td class="p-3 text-sm text-gray-900 border-r">
              <FieldX :bind="{readonly: !actionText, clearable: false }" class="w-full py-2 !mt-0"
                :value="item.keterangan" @input="v => item.keterangan = v"
                :errorText="formErrors.keterangan ? 'failed' : ''" :hints="formErrors.keterangan"
                placeholder="Keterangan" label="" :check="false" />
            </td>

            <td class="p-3 text-sm text-gray-900 border-r">
              <FieldNumber :bind="{readonly: !actionText, clearable: false }" class="w-full py-2 !mt-0"
                :value="item.tarif_realisasi" @input="v => item.tarif_realisasi = v"
                :errorText="formErrors.tarif_realisasi ? 'failed' : ''" :hints="formErrors.tarif_realisasi"
                placeholder="Masukan nominal" label="" :check="false" />
            </td>

            <td class="p-3 text-sm text-gray-900 border-r">
              <FieldNumber :bind="{readonly: !actionText, clearable: false }" class="w-full py-2 !mt-0"
                :value="item.qty" @input="v => item.qty = v" :errorText="formErrors.qty ? 'failed' : ''"
                :hints="formErrors.qty" placeholder="Masukan qty" label="" :check="false" />
            </td>

            <td class="p-3 text-center text-sm text-gray-900 border-r">
              <input
              type="checkbox"
              class="h-5 w-5 text-blue-500 rounded"
              v-model="item.is_ppn"
              :true-value="true"
              :false-value="false"
              :disabled="!actionText"
            />
            </td>

            <td class="p-3 text-sm text-gray-900 border-r">
              <div class="flex justify-center">
                <button
                type="button"
                @click="removeDetail(i)"
                :disabled="!actionText"
                title="Hapus"
                class="text-red-500 hover:text-red-700 transition-colors duration-300"
              >
                <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                </svg>
              </button>
              </div>
            </td>
          </tr>

          <!-- No Data Message -->
          <tr v-else class="text-center text-gray-500">
            <td colspan="8" class="py-5 text-xl">
              Tidak ada data untuk ditampilkan.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
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
    v-show="actionText"
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



</div>



@endverbatim
@endif