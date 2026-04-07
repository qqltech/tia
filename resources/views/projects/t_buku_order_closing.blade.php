@verbatim

<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold mb-4 mt-4">BUKU ORDER CLOSING</h1>
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
      <FieldX type="date" typeProps="year" :bind="{ readonly: false }" class="w-full !mt-3"
        :value="values.periode_tahun" label="Periode Tahun" placeholder="Pilih Periode Tahun"
        :errorText="formErrors.periode_tahun?'failed':''" @input="v=>values.periode_tahun=v"
        :hints="formErrors.periode_tahun" :check="false" />
    </div>
    <div>
      <FieldX type="textarea" :bind="{ readonly: false || values.is_closed}" class="w-full !mt-3" :value="values.alasan_closing"
        label="Alasan Closing" placeholder="Masukkan Alasan Closing" :errorText="formErrors.alasan_closing?'failed':''"
        @input="v=>values.alasan_closing=v" :hints="formErrors.alasan_closing" :check="false" />
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
              <th class="border border-gray-300 px-3 py-2 w-10 text-center">No</th>
              <th class="border border-gray-300 px-3 py-2 text-center">Tanggal</th>
              <th class="border border-gray-300 px-3 py-2 text-center">No. Transaksi</th>
              <th class="border border-gray-300 px-3 py-2 text-center">Status Terakhir</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="detailArr.length > 0">

              <tr v-for="(det, idx) in detailArr.flatMap(d => d.details || [])" :key="idx" class="hover:bg-gray-50">
                <td class="border border-gray-300 px-3 py-2 text-center">{{ idx + 1 }}</td>

                <td class="border border-gray-300 px-3 py-2 text-center">
                  {{ formatDate(det.date) }}
                </td>

                <td class="border border-gray-300 px-3 py-2 text-center font-medium">
                  {{ det.no || '(DRAFT BELUM ADA NOMOR)' }}
                </td>

                <td class="border border-gray-300 px-3 py-2 text-center">
                  <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-yellow-300">
                    {{ det.status_name }}
                  </span>
                </td>
              </tr>

            </template>

            <!-- EMPTY STATE -->
            <tr v-else class="text-center">
              <td colspan="4" class="py-[20px] text-gray-500 font-semibold italic">
                Data Outstanding Bersih / Klik Show Data
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="flex flex-row items-center justify-end space-x-2 p-2">
    <i class="text-gray-500 text-[12px]">Pastikan data sudah benar!</i>
    <button
        class="bg-amber-600 text-white font-semibold hover:bg-amber-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="!isReadData && isOpen"
        @click="onSave"
      >
        <icon fa="lock" />
        Closed Buku Order!
      </button>
  </div>
</div>
@endverbatim