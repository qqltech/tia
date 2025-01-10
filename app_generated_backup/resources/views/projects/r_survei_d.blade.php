@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Rekapitulasi Survei Detail
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-x-[60px] gap-y-[12px] px-4">
        <!-- START COLUMN -->
          <div>
            <label class="font-semibold">Tipe Ekspor</label>
              <FieldSelect 
                :bind="{ readonly: !actionText }" 
                class="w-full py-2 !mt-0"
                :value="values.tipe" 
                :errorText="formErrors.tipe ? 'failed' : ''"
                @input="v => values.tipe = v" 
                :hints="formErrors.tipe" 
                :check="false"
                label=""
                :options="['Excel','PDF','HTML']"
                placeholder="Pilih Tipe Ekspor"
                valueField="key" 
                displayField="key"
            />
          </div>
          <div class="grid grid-cols-2 gap-2">
              <div>
                  <label class="font-semibold">Periode
                      <label class="text-red-500 space-x-0 pl-0"></label>
                  </label>
                  <FieldX 
                      type="date"
                      :bind="{ readonly: false }" 
                      class="w-full py-2 !mt-0" 
                      :value="values.periode_from" 
                      label="" 
                      placeholder="DD/MM/YY" 
                      :errorText="formErrors.periode_from?'failed':''"
                      @input="v=>values.periode_from=v" 
                      :hints="formErrors.periode_from" 
                      :check="false"
                  />
              </div>
              <div>
                  <FieldX 
                      type="date"
                      :bind="{ readonly: false }" 
                      class="w-full py-2 !mt-5" 
                      :value="values.periode_to" 
                      label="" 
                      placeholder="DD/MM/YY" 
                      :errorText="formErrors.periode_to?'failed':''"
                      @input="v=>values.periode_to=v" 
                      :hints="formErrors.periode_to"  
                      :check="false"
                  />
              </div>
          </div>
      </div>
        <div class="flex flex-row justify-end space-x-[20px] mt-[1em]">
          <button @click="onGenerate" class="bg-green-600 hover:bg-green-800 duration-300 text-white px-[24px] py-[10px] rounded-[6px] ">
            {{ values.tipe?.toLowerCase() === 'html' ? 'Pratinjau' : 'Ekspor' }}
          </button>
        </div>
        <!-- END COLUMN -->
        <!-- ACTION BUTTON START -->
        <div class="overflow-x-auto mt-6 mb-4 px-4" v-show="exportHtml">
          <hr>
          <div id="exportTable">
          </div>
        </div>
      
      </div>
    </div>
  </div>
</div>
@endverbatim