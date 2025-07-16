<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-5 border-red-700">
  <!-- Position Indicator -->
  <div class="flex justify-between items-center bg-[#800000] text-white px-4 py-2 rounded-t-md">
    <h3 class="text-lg font-bold">TRANSAKSI PEMBELIAN BAHAN MENTAH</h3>
  </div>

  <!-- Table Header -->
  <div class="p-4">
    <!-- Filter Section -->
    <div class="mb-4 flex items-center">
      <span class="mr-2 font-semibold">Filter Status:</span>
      <!-- Active Button -->
      <button
        @click="filterShowData(true, 1)"
        :class="{'bg-green-500 text-white': activeBtn === 1, 'border-green-500 text-green-500': activeBtn !== 1}"
        class="border-1 font-semibold bg-white hover:bg-green-500 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2"
      >
        Aktif
      </button>

      <!-- Inactive Button -->
      <button
        @click="filterShowData(false, 2)"
        :class="{'bg-red-500 text-white': activeBtn === 2, 'border-red-500 text-red-500': activeBtn !== 2}"
        class="border-1 font-semibold bg-white hover:bg-red-500 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2 ml-2"
      >
        Nonaktif
      </button>
    </div>

    <TableApi
      ref="apiTable"
      :api="landing.api"
      :columns="landing.columns"
      :actions="landing.actions"
      class="max-h-[450px]"
    >
      <!-- Create New Button and Filter Buttons -->
      <template #header>
        <div class="flex space-x-2">
          <!-- Create New Button -->
          <RouterLink
            :to="$route.path + '/create?' + (Date.parse(new Date()))"
            class="border-1 border-[#800000] font-semibold text-[#800000] bg-white hover:bg-[#800000] hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2"
          >
            Create New
          </RouterLink>
        </div>
      </template>
    </TableApi>
  </div>
</div>
@else

<!-- CONTENT -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-[#800000] border-none text-white">
  <!-- Header -->
  <div class="rounded-t-md py-2 px-4 mt-5">
    <div class="flex items-center gap-x-2">
      <!-- Back Button -->
      <button
        class="py-1 px-2 rounded transition-all text-[#800000] bg-[#FFEBEE] border border-[#FFEBEE] duration-300 hover:text-white hover:bg-[#800000]"
        @click="onBack"
      >
        <icon fa="arrow-left" size="sm" />
      </button>
      <!-- Title and Subtitle -->
      <div>
        <h1 class="text-2xl font-bold">Form Pembelian Bahan Mentah</h1>
        <p v-if="actionText" class="text-[#FFEBEE]">
          {{ actionText === 'Edit' ? 'Edit' : (actionText === 'Tambah' ? 'New' : '') }} Data
        </p>
      </div>
    </div>
  </div>

<div class="bg-white"> 
        <!-- Tabs Section -->
    <div class="mb-4 bg-white p-4">
      <!-- Tab Buttons -->
      <div class="flex space-x-2 border-b border-gray-300">
        <!-- Kelinci Tab -->
        <button
          @click="activeTab = 1"
          :class="{'border-b-2 border-[#800000] text-[#800000] font-bold': activeTab === 1, 'text-gray-600 hover:text-[#800000]': activeTab !== 1}"
          class="pb-2 focus:outline-none"
        >
          HEADER
        </button>

        <!-- Kucing Tab -->
        <button
          @click="activeTab = 2"
          :class="{'border-b-2 border-[#800000] text-[#800000] font-bold': activeTab === 2, 'text-gray-600 hover:text-[#800000]': activeTab !== 2}"
          class="pb-2 focus:outline-none"
        >
          DETAIL SORTIRAN
        </button>

        <!--  LANJUTKAN -->
      </div>
    </div>

  <!-- Content HEADER -->
  <div class="p-4 grid <md:grid-cols-1 grid-cols-2 gap-2 bg-white text-gray-700"  v-if="activeTab === 1">
    <!-- No. Pembelian BM -->
    <div>
      <label class="text-sm font-bold">No. Pembelian BM</label>
      <FieldX
        :bind="{ readonly: !actionText }"
        class="w-full !mt-3"
        :value="values.group"
        :errorText="formErrors.group ? 'failed' : ''"
        @input="v => values.group = v"
        :hints="formErrors.group"
        label=""
        placeholder="No Pembelian BM | "
        :check="false"
      />
    </div>

    <!-- STATUS -->
    <div>
      <label class="text-sm font-bold">Status</label>
      <FieldX
        :bind="{ readonly: true }"
        class="w-full !mt-3"
        :value="values.status"
        :errorText="formErrors.status ? 'failed' : ''"
        @input="v => values.status = v"
        :hints="formErrors.status"
        label=""
        placeholder="Code | Optional"
        :check="false"
      />
    </div>

    <!-- KEY 1 -->
    <div>
      <label class="text-sm font-bold">No Reference</label><label class="text-red-500">*</label>
      <div>
        <FieldSelect 
            :bind="{ readonly: !actionText }" 
            class="w-full !mt-3"
            :value="values.m_dir_id" 
            :errorText="formErrors.m_dir_id ? 'failed' : ''"
            @input="v => values.m_dir_id = v" 
            :hints="formErrors.m_dir_id" 
            :check="false"
            @update:valueFull="(objVal)=>{
              values.m_divisi_id = null
            }"
            placeholder="Pilih Reference"
            valueField="id" 
            displayField="nama"
            :api="{
                url: `${store.server.url_backend}/operation/m_dir`,
                headers: { 
                    'Content-Type': 'Application/json', 
                    Authorization: `${store.user.token_type} ${store.user.token}`
                },
                params: {
                    single: true,
                    join: false,                    
                    where: `this.is_active='true'`
                }
            }"
          fa-icon="search" :check="true"
        />
      </div>
    </div>

    <!-- KEY 2 -->
    <div>
      <label class="text-sm font-bold">Tanggal</label>
      <FieldX
        :bind="{ readonly: !actionText }"
        class="w-full !mt-3"
        :value="values.tanggal"
        :errorText="formErrors.tanggal ? 'failed' : ''"
        @input="v => values.tanggal = v"
        :hints="formErrors.tanggal"
        label=""
        type="date"
        :check="false"
      />
    </div>

    <!-- Supplier -->
    <div>
      <label class="text-sm font-bold">Supplier</label>
      <div>
        <FieldSelect 
            :bind="{ readonly: !actionText }" 
            class="w-full !mt-3"
            :value="values.m_dir_id" 
            :errorText="formErrors.m_dir_id ? 'failed' : ''"
            @input="v => values.m_dir_id = v" 
            :hints="formErrors.m_dir_id" 
            :check="false"
            label="Direktorat"
            @update:valueFull="(objVal)=>{
              values.m_divisi_id = null
            }"
            placeholder="Pilih Supplier"
            valueField="id" 
            displayField="nama"
            :api="{
                url: `${store.server.url_backend}/operation/m_dir`,
                headers: { 
                    'Content-Type': 'Application/json', 
                    Authorization: `${store.user.token_type} ${store.user.token}`
                },
                params: {
                    single: true,
                    join: false,                    
                    where: `this.is_active='true'`
                }
            }"
          fa-icon="search" :check="true"
        />
      </div>
    </div>

    <!-- KEY 4 -->
    <div>
      <label class="text-sm font-bold">Tipe Item</label>
      <FieldX
        :bind="{ readonly: !actionText }"
        class="w-full !mt-3"
        :value="values.key4"
        :errorText="formErrors.key4 ? 'failed' : ''"
        @input="v => values.key4 = v"
        :hints="formErrors.key4"
        label=""
        placeholder="Key 4 | Optional"
        :check="false"
      />
    </div>

    <!-- VALUE 1 -->
    <div>
      <label class="text-sm font-bold">Supir</label>
      <FieldX
        :bind="{ readonly: !actionText }"
        class="w-full !mt-3"
        :value="values.supir"
        :errorText="formErrors.supir ? 'failed' : ''"
        @input="v => values.supir = v"
        :hints="formErrors.supir"
        label=""
        placeholder=" Supir"
        :check="false"
      />
    </div>

    <!-- No Polisi -->
    <div>
      <label class="text-sm font-bold">No Polisi</label>
      <FieldX
        :bind="{ readonly: !actionText }"
        class="w-full !mt-3"
        :value="values.value2"
        :errorText="formErrors.value2 ? 'failed' : ''"
        @input="v => values.value2 = v"
        :hints="formErrors.value2"
        label=""
        placeholder="No Polisi | "
        :check="false"
      />
    </div>

    <!-- ToP -->
    <div>
      <label class="text-sm font-bold">ToP</label><label class="text-red-500">*</label>
      <FieldX
        :bind="{ readonly: !actionText }"
        class="w-full !mt-3"
        :value="values.value3"
        :errorText="formErrors.value3 ? 'failed' : ''"
        @input="v => values.value3 = v"
        :hints="formErrors.value3"
        label=""
        placeholder="Value 3 | Optional"
        :check="false"
      />
    </div>

    <!-- Kota Pengambilan -->
    <div>
      <label class="text-sm font-bold">Kota Pengambilan</label>
      <FieldX
        :bind="{ readonly: !actionText }"
        class="w-full !mt-3"
        :value="values.value4"
        :errorText="formErrors.value4 ? 'failed' : ''"
        @input="v => values.value4 = v"
        :hints="formErrors.value4"
        label=""
        placeholder="Kota Pengambilan"
        :check="false"
      />
    </div>

    <!-- Kecamatan Pengambilan -->
    <div>
      <label class="text-sm font-bold">Kecamatan Pengambilan</label>
      <FieldX
        :bind="{ readonly: !actionText }"
        class="w-full !mt-3"
        :value="values.value4"
        :errorText="formErrors.value4 ? 'failed' : ''"
        @input="v => values.value4 = v"
        :hints="formErrors.value4"
        label=""
        placeholder="Kecamatan Pengambilan"
        :check="false"
      />
    </div>

    <!-- PPH -->
    <div>
      <label class="text-sm font-bold">PPH</label>
      <FieldX
        :bind="{ readonly: !actionText }"
        class="w-full !mt-3"
        :value="values.PPh"
        :errorText="formErrors.PPh ? 'failed' : ''"
        @input="v => values.PPh = v"
        :hints="formErrors.PPh"
        label=""
        placeholder="PPH | "
        :check="false"
      />
    </div>

     <!-- Cara Bayar -->
    <div>
      <label class="text-sm font-bold">Cara Bayar</label><label class="text-red-500">*</label>
      <FieldX
        :bind="{ readonly: !actionText }"
        class="w-full !mt-3"
        :value="values.cara_bayar"
        :errorText="formErrors.cara_bayar ? 'failed' : ''"
        @input="v => values.cara_bayar = v"
        :hints="formErrors.cara_bayar"
        label=""
        placeholder="Cara Bayar | "
        :check="false"
      />
    </div>

    <!-- TGL Penerimaan -->
    <div>
      <label class="text-sm font-bold">TGL Penerimaan</label>
      <FieldX
        :bind="{ readonly: !actionText }"
        class="w-full !mt-3"
        :value="values.tanggal"
        :errorText="formErrors.tanggal ? 'failed' : ''"
        @input="v => values.tanggal = v"
        :hints="formErrors.tanggal"
        label=""
        type="date"
        placeholder="TGL Penerimaan | "
        :check="false"
      />
    </div>


    <!-- Catatan -->
    <div>
      <label class="text-sm font-bold">Catatan</label>
      <FieldX
        :bind="{ readonly: !actionText }"
        class="w-full !mt-3"
        :value="values.catatan"
        :errorText="formErrors.catatan ? 'failed' : ''"
        @input="v => values.catatan = v"
        :hints="formErrors.catatan"
        label=""
        type="textarea"
        placeholder="Catatan | Optional"
        :check="false"
      />
    </div>
  </div>

<!-- PBM_Detail -->
    <div class= "space-y- p-4">
      <label class="  text-sm text-black font-bold">Pembelian Bahan Mentah Detail</label>
    <div>
      <label class=" text-sm text-black ">Harga Kesepakatan</label>
      <FieldX
        :bind="{ readonly: true }"
        class="w-3/12 !mt-1"
        :value="values.PBMD"
        :errorText="formErrors.PBMD ? 'failed' : ''"
        @input="v => values.PBMD = v"
        :hints="formErrors.PBMD"
        label=""
        placeholder="Harga"
        :check="false"
      />
      </div>
    </div>

<!-- Detail  -->
  <div class="p-4">
       <table class="w-full bg-white rounded-lg shadow-md overflow-hidden">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-3 text-left border-r text-sm font-semibold text-gray-700 w-[5%]">No.</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[10%]">Kode</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[15%]">Nama Item</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[10%]">Sak (PCS)</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[15%]">Qty Pembelian</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[5%]">UoM</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[10%]">Harga</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[10%]">Total</th>
            <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[10%]">Catatan</th>
            <!-- <th class="p-3 text-left text-sm border-r font-semibold text-gray-700 w-[10%]">Action</th> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detailArr" :key="item.id" class="border-b border-gray-200 hover:bg-gray-50"
            v-if="detailArr.length > 0">
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

            <!-- <td class="p-3 text-sm text-gray-900 border-r">
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
            </td> -->
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




  <!-- Divider -->
  <hr v-show="actionText" class="border-gray-300">

  <!-- Action Buttons -->
  <div v-show="actionText" class="flex flex-row items-center justify-end space-x-2 p-2 bg-white rounded-b-md">
    <i class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
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