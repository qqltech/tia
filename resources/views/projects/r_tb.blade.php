@php
$data = \DB::select("SELECT * FROM r_gl ORDER BY date DESC");
$grand_debet = 0;
$grand_credit = 0;
@endphp
@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm <md:w-full w-full bg-white">
      <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
        <div class="flex items-center">
          <!-- <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
            @click="onBack" /> -->
          <div>
            <h1 class="text-20px font-bold mb-4 mt-4">Laporan Trial Balance</h1>
          </div>
        </div>
      </div>
      <hr>
      <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
        <!-- START COLUMN -->
        <div>
          <FieldSelect :bind="{ readonly: !actionText, clearable: false }" class="w-full !mt-3"
            :value="values.tipe_report" :errorText="formErrors.tipe_report ? 'failed' : ''"
            @input="v => values.tipe_report = v" :hints="formErrors.tipe_report" :check="false" label="Tipe Laporan"
            :options="['Trial Balance', 'Laba Rugi', 'Neraca']" placeholder="Pilih Tipe Laporan" valueField="key"
            displayField="key" />
        </div>
        <div class="grid grid-cols-1 gap-1">
          <div class="w-full" style="margin-top:-14px">
            <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
            <input
              type="month"
              v-model="values.selected_month"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
              :class="{ 'border-red-500': formErrors.selected_month }"
              placeholder="Pilih Periode"
              @click="$refs.monthPicker?.showPicker()"
              ref="monthPicker">
            <div v-if="formErrors.selected_month" class="text-xs text-red-500 mt-1">{{ formErrors.selected_month }}
            </div>
          </div>
        </div>
        <div>
          <FieldSelect :bind="{ readonly: !actionText, clearable: false }" class="w-full !mt-3" :value="values.tipe"
            :errorText="formErrors.tipe ? 'failed' : ''" @input="v => values.tipe = v" :hints="formErrors.tipe"
            :check="false" label="Tipe Export" :options="['Excel','PDF','HTML']" placeholder="Pilih Tipe Export"
            valueField="key" displayField="key" />
        </div>

      </div>

      <div class="flex flex-row items-center justify-end space-x-2 p-2">
        <button
          class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"

          @click="onGenerate">
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