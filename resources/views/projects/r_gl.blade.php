@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm <md:w-full w-full bg-white">
      <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
        <div class="flex items-center">
          <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
            @click="onBack" />
          <div>
            <h1 class="text-20px font-bold mb-4 mt-4">Laporan General Ledger</h1>
          </div>
        </div>
      </div>
      <hr>
      <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
        <!-- START COLUMN -->
        <!-- <div>
              <FieldSelect 
                :bind="{ readonly: !actionText, clearable: false }" 
                class="w-full !mt-3"
                :value="values.tipe_report" 
                :errorText="formErrors.tipe_report ? 'failed' : ''"
                @input="v => values.tipe_report = v" 
                :hints="formErrors.tipe_report" 
                :check="false"
                label="Tipe Laporan"
                :options="['Summary','Detail']"
                placeholder="Pilih Tipe Laporan"
                valueField="key" 
                displayField="key"
            />
          </div> -->
        <div class="grid grid-cols-2 gap-2">
          <div>
            <FieldX type="date" :bind="{ readonly: false }" class="w-full !mt-3" :value="values.periode_awal"
              label="Periode Awal" placeholder="Pilih Periode Awal" :errorText="formErrors.periode_awal?'failed':''"
              @input="v=>values.periode_awal=v" :hints="formErrors.periode_awal" :check="false" />
          </div>
          <div>
            <FieldX type="date" :bind="{ readonly: false }" class="w-full !mt-3" :value="values.periode_akhir"
              label="Periode Akhir" placeholder="Pilih Periode Akhir" :errorText="formErrors.periode_akhir?'failed':''"
              @input="v=>values.periode_akhir=v" :hints="formErrors.periode_akhir" :check="false" />
          </div>
        </div>
        <div>
          <FieldSelect :bind="{ readonly: !actionText, clearable: false }" class="w-full !mt-3" :value="values.m_business_unit_id"
            :errorText="formErrors.m_business_unit_id ? 'failed' : ''" @input="v => {
              values.m_business_unit_id = v;
              }" :hints="formErrors.m_business_unit_id" valueField="id" displayField="nama" :api="{
                url: `${store.server.url_backend}/operation/m_business_unit`,
                headers: { 
                  'Content-Type': 'Application/json', 
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                params: {
                  where : `this.is_active=true`,
                  paginate: 200
                }
              }" placeholder="Pilih Business Unit" label="Business Unit" :check="true" />
        </div>
        <div>
          <FieldSelect :bind="{ readonly: !actionText, clearable: false }" class="w-full !mt-3" :value="values.tipe"
            :errorText="formErrors.tipe ? 'failed' : ''" @input="v => values.tipe = v" :hints="formErrors.tipe"
            :check="true" label="Tipe Export" :options="['Excel',PDF, 'HTML']" placeholder="Pilih Tipe Export"
            valueField="key" displayField="key" />
        </div>
      </div>

      <div class="flex flex-row items-center justify-end space-x-2 p-2">
        <button
          class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"

            @click="onGenerate"
          >
          Lihat Laporan 
        </button>
      </div>
      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
      <div class="overflow-x-auto my-4 px-4 w-[100%]" v-show="exportHtml">
        <hr>
        <div id="exportTable" class="w-full mt-6">
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endverbatim