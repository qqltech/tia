@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm <md:w-full w-full bg-white">
      <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
        <div class="flex items-center">
          <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
            @click="onBack" />
          <div>
            <h1 class="text-20px font-bold mb-4 mt-4">TUTUP BUKU</h1>
          </div>
        </div>
      </div>
      <hr>
      <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
        <!-- START COLUMN -->
        <div>
          <div>
            <FieldSelect class="w-full !mt-3" :bind="{ disabled: false, clearable:true }" :value="values.m_modul_id"
              @input="v=>values.m_modul_id=v" :errorText="formErrors.m_modul_id?'failed':''"
              :hints="formErrors.m_modul_id" valueField="id" displayField="modul" :options="allmodul"
              placeholder="Pilih Salah Satu Modul" label="Modul" :check="true" />

          </div>
        </div>
        <div>
          <FieldSelect class="w-full !mt-3" :bind="{ disabled: false || !values.m_modul_id, clearable:true }"
            :value="values.m_menu_id" @input="v=>values.m_menu_id=v" :errorText="formErrors.m_menu_id?'failed':''"
            :hints="formErrors.m_menu_id" valueField="id" displayField="menu" :options="filteredmenu"
            placeholder="Pilih Salah Satu Menu" label="Menu" :check="true" />

        </div>
        <div>
          <FieldX type="date" typeProps="year" :bind="{ readonly: false }" class="w-full !mt-3" :value="values.periode"
            label="Periode" placeholder="Pilih Periode" :errorText="formErrors.periode?'failed':''"
            @input="v=>values.periode=v" :hints="formErrors.periode" :check="false" />
        </div>

      </div>

      <div class="flex flex-row items-center justify-end space-x-2 p-2">
        <button
          class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"

            @click="onGenerate"
          >
          Tutup Buku
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