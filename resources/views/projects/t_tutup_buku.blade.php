@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold mb-4 mt-4">TUTUP BUKU TAHUNAN</h1>
      </div>
    </div>
  </div>
  <hr>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldSelect :bind="{ disabled: isReadData, clearable:true }" class="w-full !mt-3" :value="values.m_bu_id"
        @input="v=>values.m_bu_id=v" :errorText="formErrors.m_bu_id?'failed':''" :hints="formErrors.m_bu_id"
        valueField="id" displayField="nama" :api="{
              url: `${store.server.url_backend}/operation/m_business_unit`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                where: 'this.is_active=true'
              }
          }" placeholder="Pilih Business Unit" label="Business Unit" :check="true" />
    </div>
    
    <div>
      <FieldSelect :bind="{ disabled: true , clearable:false }" class="w-full !mt-3" :value="values.grup" @input="v=>{
            if(v){
              values.grup = v
            }else{
              values.grup=null
              values.periode_tahun=null
            }
          }" :errorText="formErrors.grup?'failed':''" :hints="formErrors.grup" :options="['TAHUNAN']"
        placeholder="Pilih Grup" label="Grup" :check="true" />
    </div>
    <div>
      <FieldX type="date" typeProps="year" :bind="{ readonly: isReadData }" class="w-full !mt-3" :value="values.periode_tahun"
        label="Periode Tahun" placeholder="Pilih Periode Tahun" :errorText="formErrors.periode_tahun?'failed':''"
        @input="v=>values.periode_tahun=v" :hints="formErrors.periode_tahun" :check="false" />
    </div>

    <hr class="<md:col-span-1 col-span-3">

    <div class="<md:col-span-1 col-span-3 px-4 pt-2">
      <div class="flex space-x-6 border-b border-gray-300">
        <button
          @click="activeTab = 'outstanding'"
          :class="{'border-b-2 border-blue-600 font-bold text-blue-600': activeTab === 'outstanding', 'text-gray-500 font-semibold hover:text-blue-400': activeTab !== 'outstanding'}"
          class="pb-2 px-1 transition duration-200">
          <icon fa="exclamation-circle" class="mr-1"/> Outstanding Transaction
        </button>
        <button
          @click="activeTab = 'coa'"
          :class="{'border-b-2 border-blue-600 font-bold text-blue-600': activeTab === 'coa', 'text-gray-500 font-semibold hover:text-blue-400': activeTab !== 'coa'}"
          class="pb-2 px-1 transition duration-200">
          <icon fa="file-invoice-dollar" class="mr-1"/> Detail Saldo COA
        </button>
      </div>
    </div>

    <div v-show="activeTab === 'outstanding'" class="<md:col-span-1 col-span-3">
      <div class="px-4 mt-2">
        <button v-show="!isReadData" @click="autoGenerate()" type="button" class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded px-3 py-1.5 shadow-sm">
          <icon fa="search" class="mr-1" /> Show Data
        </button>
      </div>

      <div class="<md:col-span-1 col-span-3 mt-4 pb-4 overflow-x-auto px-4">
        <table class="w-[80%] overflow-x-auto table-fixed border border-[#CACACA]">
          <thead>
            <tr class="border bg-gray-100">
              <td class="font-semibold text-[14px] p-2 text-center w-[5%] border border-[#CACACA]">No.</td>
              <td class="font-semibold text-[14px] px-2 text-center border border-[#CACACA] w-[65%]">Transaksi</td>
              <td class="font-semibold text-[14px] text-center border border-[#CACACA] w-[15%]">Jumlah</td>
              <td class="font-semibold text-[14px] text-center border border-[#CACACA] w-[15%]">Aksi</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item,i) in detailArr" :key="i" class="border-t hover:bg-gray-50" v-if="detailArr.length > 0">
              <td class="p-2 text-center border border-[#CACACA]">{{ i + 1 }}.</td>
              <td class="p-2 border border-[#CACACA]">{{item.nama_transaksi ?? '-'}}</td>
              <td class="p-2 border border-[#CACACA] text-center font-semibold text-red-600">
                {{item.jumlah?.toLocaleString('ID') ?? '-'}}</td>
              <td class="p-2 border border-[#CACACA] text-center">
                <button type="button" @click="openDetailModal(item)" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-md text-xs transition duration-200">
                  <icon fa="eye" /> Show
                </button>
              </td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="4" class="py-[20px] text-gray-500 font-semibold italic">Data Outstanding Bersih / Klik Show
                Data</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-show="activeTab === 'coa'" class="<md:col-span-1 col-span-3 mt-4 pb-4 px-4 overflow-x-auto">
      <table class="w-full overflow-x-auto border border-[#CACACA] text-sm">
        <thead>
          <tr class="border bg-gray-100">
            <td class="font-bold p-2 text-center w-[5%] border border-[#CACACA]">No</td>
            <td class="font-bold p-2 text-center w-[12%] border border-[#CACACA]">No. Akun</td>
            <td class="font-bold p-2 text-center border border-[#CACACA]">Nama Akun</td>
            <td class="font-bold p-2 text-center w-[15%] border border-[#CACACA]">Saldo Awal</td>
            <td class="font-bold p-2 text-center w-[15%] border border-[#CACACA]">Debet</td>
            <td class="font-bold p-2 text-center w-[15%] border border-[#CACACA]">Kredit</td>
            <td class="font-bold p-2 text-center w-[15%] border border-[#CACACA]">Saldo Akhir</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(coa, i) in detailCoaArr" :key="i" class="border-t hover:bg-gray-50"
            v-if="detailCoaArr.length > 0">
            <td class="p-2 text-center border border-[#CACACA]">{{ i + 1 }}</td>
            <td class="p-2 text-center border border-[#CACACA]">{{ coa.nomor }}</td>
            <td class="p-2 border border-[#CACACA]">{{ coa.nama_coa }}</td>
            <td class="p-2 text-right border border-[#CACACA]">{{ coa.awal?.toLocaleString('id-ID',
              {minimumFractionDigits: 2}) }}</td>
            <td class="p-2 text-right border border-[#CACACA]">{{ coa.debet?.toLocaleString('id-ID',
              {minimumFractionDigits: 2}) }}</td>
            <td class="p-2 text-right border border-[#CACACA]">{{ coa.credit?.toLocaleString('id-ID',
              {minimumFractionDigits: 2}) }}</td>
            <td class="p-2 text-right border border-[#CACACA] font-bold" :class="{'text-red-600': coa.akhir < 0}">{{
              coa.akhir?.toLocaleString('id-ID', {minimumFractionDigits: 2}) }}</td>
          </tr>
          <tr v-else class="text-center">
            <td colspan="7" class="py-[20px] text-gray-500 font-semibold italic">Klik Show Data pada Tab Outstanding
              untuk me-load COA.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div v-show="showModalDetail" class="fixed inset-0 flex items-center justify-center z-50">
    <div @click.self="showModalDetail = false"  class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
    <div class="modal-container bg-white w-[70%] mx-auto rounded shadow-lg z-50 overflow-y-auto max-h-[90vh]">
      <div class="modal-content py-4 text-left px-6">
        <div class="modal-header flex items-center justify-between flex-wrap pb-3 border-b">
          <h3 class="text-xl font-bold text-gray-800">
            Daftar Outstanding: <span class="text-blue-600">{{ selectedModalTitle }}</span>
          </h3>
          <button @click="showModalDetail = false" class="text-gray-500 hover:text-red-500"><icon fa="times" /></button>
        </div>
        <div class="modal-body mt-4 overflow-x-auto">
          <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200 text-gray-700">
              <tr>
                <th class="border border-gray-300 px-3 py-2 w-10 text-center">No</th>
                <th class="border border-gray-300 px-3 py-2 text-center">Tanggal</th>
                <th class="border border-gray-300 px-3 py-2 text-left">No. Transaksi</th>
                <th class="border border-gray-300 px-3 py-2 text-center">Status Terakhir</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(det, idx) in selectedModalData" :key="idx" class="hover:bg-gray-50">
                <td class="border border-gray-300 px-3 py-2 text-center">{{ idx + 1 }}</td>
                <td class="border border-gray-300 px-3 py-2 text-center">{{ formatDate(det.date) }}</td>
                <td class="border border-gray-300 px-3 py-2 text-center font-medium">{{ det.no || '(DRAFT BELUM ADA NOMOR)' || null }}</td>
                <td class="border border-gray-300 px-3 py-2 text-center">
                  <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-yellow-300">
                      {{ det.status_name }}
                    </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="modal-footer flex justify-end mt-4 pt-3 border-t">
          <button @click="showModalDetail = false" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-md transition duration-200">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="flex flex-row items-center justify-end space-x-2 p-2">
    <i class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
    <button
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="!isReadData"
        @click="onReset(true)"
      >
        <icon fa="times" />
        Reset
      </button>
    <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="!isReadData && isOpen"
        @click="onSave"
      >
        <icon fa="save" />
        Simpan
      </button>
  </div>
</div>
@endverbatim