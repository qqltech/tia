
@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm <md:w-full w-full bg-white">
      <div class="bg-gray-500 text-white rounded-t-md py-2 px-4">
        <div class="flex items-center">
          <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali" @click="onBack"/>
          <div>
            <h1 class="text-20px font-bold mb-4 mt-4">Laporan View Stock</h1>
          </div>
        </div>
      </div>
      <hr>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-x-[60px] gap-y-[12px] px-4 py-4">
        <!-- START COLUMN -->
          <div>
            <label>Business Unit</label>
             <FieldSelect
                :bind="{ disabled: true, clearable:true }" class="col-span-8 !mt-0 w-full"
                :value="values.comp_id" @input="v=>{
                  if(v){
                    values.comp_id=v
                  }else{
                    values.comp_id=null
                  }            
                  values.sub_comp_id=null
                  values.branch_id=null
                }"
                :errorText="formErrors.comp_id?'failed':''" 
                :hints="formErrors.comp_id"
                valueField="id" displayField="name"
                :api="{
                    url: `${store.server.url_backend}/operation/m_comp`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      transform:false,
                      join:false,
                      where: 'this.is_active=true'
                    }
                }"
                placeholder="Pilih Business Unit" label="" :check="false"
              />
          </div>
          <div>
            <label>Sub Business Unit</label>
             <FieldSelect
                :bind="{ disabled: true, clearable:true }" class="col-span-8 !mt-0 w-full"
                :value="values.sub_comp_id"  @input="v=>{
                  if(v){
                    values.sub_comp_id=v
                  }else{
                    values.sub_comp_id=null
                  }            
                  values.branch_id=null
                }"
                :errorText="formErrors.sub_comp_id?'failed':''" 
                :hints="formErrors.sub_comp_id"
                valueField="id" displayField="name"
                :api="{
                    url: `${store.server.url_backend}/operation/m_subcomp`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      transform:false,
                      join:false,
                      where: 'this.is_active=true and this.m_comp_id='+`${values.comp_id??0}`,
                      searchfield:'this.id, this.name, this.is_active, this.m_comp_id',
                      selectfield:'this.id, this.name, this.is_active, this.m_comp_id'
                    }
                }"
                placeholder="Pilih Sub Business Unit" label="" :check="true"
              />
          </div>
          <div>
            <label>Cabang</label>
             <FieldSelect
                :bind="{ disabled: true, clearable:true }" class="col-span-8 !mt-0 w-full"
                :value="values.branch_id"  @input="v=>{
                  if(v){
                    values.branch_id=v
                  }else{
                    values.branch_id=null
                  }            
                }"
                :errorText="formErrors.branch_id?'failed':''" 
                :hints="formErrors.branch_id"
                valueField="id" displayField="name"
                :api="{
                    url: `${store.server.url_backend}/operation/m_branch`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      transform:false,
                      join:false,
                    // where: 'this.is_active=true and this.m_comp_id='+`${values.m_comp_id??0}`+' and this.m_subcomp_id='+`${values.m_subcomp_id??0}`,
                      where: 'this.is_active=true and this.m_subcomp_id='+`${values.sub_comp_id??0}`,
                      searchfield:'this.id, this.name, this.is_active, this.m_subcomp_id',
                      selectfield:'this.id, this.name, this.is_active, this.m_subcomp_id'
                    }
                }"
                placeholder="Pilih Cabang" label="" :check="true"
              />
          </div>
          <div>
            <label>Gudang</label>
             <FieldSelect
                :bind="{ disabled: false, clearable:true }" class="col-span-8 !mt-0 w-full"
                :value="values.warehouse_id"  @input="v=>{
                  if(v){
                    values.warehouse_id=v
                  }else{
                    values.warehouse_id=null
                  }            
                }"
                :errorText="formErrors.warehouse_id?'failed':''" 
                :hints="formErrors.warehouse_id"
                valueField="id" displayField="name"
                :api="{
                    url: `${store.server.url_backend}/operation/m_warehouse`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      transform:false,
                      join:false,
                      //where: 'this.is_active=true and this.m_subcomp_id='+`${values.sub_comp_id??0}`,
                      //searchfield:'this.id, this.name, this.is_active, this.m_subcomp_id',
                      //selectfield:'this.id, this.name, this.is_active, this.m_subcomp_id'
                    }
                }"
                placeholder="Pilih Gudang" label="" :check="true"
              />
          </div>
          <div>
            <label>Tipe</label>
              <FieldSelect 
                :bind="{ readonly: !actionText, clearable: false }" 
                class="col-span-8 !mt-0 w-full"
                :value="values.tipe_report" 
                :errorText="formErrors.tipe_report ? 'failed' : ''"
                @input="v => values.tipe_report = v" 
                :hints="formErrors.tipe_report" 
                :check="false"
                label=""
                :options="['Summary','Detail']"
                placeholder="Pilih Tipe"
                valueField="key" 
                displayField="key"
            />
          </div>
          <div class="grid grid-cols-2 gap-2">
              <div>
                  <label>Periode
                      <label class="text-red-500"></label>
                  </label>
                  <FieldX 
                      type="date"
                      :bind="{ readonly: false }" 
                      class="col-span-8 !mt-0 w-full" 
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
                      class="w-full py-2 !mt-3" 
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
          <div>
            <label>Tipe Item</label>
             <FieldSelect
                :bind="{ disabled: false, clearable:true }" class="col-span-8 !mt-0 w-full"
                :value="values.tipe_item_id"  @input="v=>{
                  if(v){
                    values.tipe_item_id=v
                  }else{
                    values.tipe_item_id=null
                  }            
                }"
                :errorText="formErrors.tipe_item_id?'failed':''" 
                :hints="formErrors.tipe_item_id"
                valueField="id" displayField="name"
                :api="{
                    url: `${store.server.url_backend}/operation/m_gen`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      transform:false,
                      join:false,
                      //where: 'this.is_active=true and this.m_subcomp_id='+`${values.sub_comp_id??0}`,
                      //searchfield:'this.id, this.name, this.is_active, this.m_subcomp_id',
                      //selectfield:'this.id, this.name, this.is_active, this.m_subcomp_id'
                    }
                }"
                placeholder="Pilih Tipe Item" label="" :check="true"
              />
          </div>
          <div>
            <label>Kategori Item</label>
             <FieldSelect
                :bind="{ disabled: false, clearable:true }" class="col-span-8 !mt-0 w-full"
                :value="values.kat_item_id"  @input="v=>{
                  if(v){
                    values.kat_item_id=v
                  }else{
                    values.kat_item_id=null
                  }            
                }"
                :errorText="formErrors.kat_item_id?'failed':''" 
                :hints="formErrors.kat_item_id"
                valueField="id" displayField="name"
                :api="{
                    url: `${store.server.url_backend}/operation/m_gen`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      transform:false,
                      join:false,
                      //where: 'this.is_active=true and this.m_subcomp_id='+`${values.sub_comp_id??0}`,
                      //searchfield:'this.id, this.name, this.is_active, this.m_subcomp_id',
                      //selectfield:'this.id, this.name, this.is_active, this.m_subcomp_id'
                    }
                }"
                placeholder="Pilih Kategori Item" label="" :check="true"
              />
          </div>
          <div>
            <label>Item</label>
              <FieldPopup
                :api="{
                    url:  `${store.server.url_backend}/operation/m_item`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest:true,
                      //scopes: 'filterRespo,WithTipeItem,notClosed,getApproved',
                      //searchfield: 'this.no,this.date,tipe.value1',
                    }
                  }"

                displayField="no"
                valueField="id"
                :bind="{ readonly: !actionText }"
                :value="values.m_item_id" @input="(v)=>values.m_item_id=v"
                @update:valueFull="(v)=>{
                }"
                :errorText="formErrors.m_item_id?'failed':''"  class="col-span-8 !mt-0 w-full"
                :hints="formErrors.m_item_id" placeholder="Pilih Item" label=""
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
                    headerName: 'No. PP',
                    field: 'no',
                    sortable: false, resizable: true, filter: false,
                    cellClass: ['border-r', '!border-gray-200', 'justify-start']
                  },
                  {
                    flex: 1,
                    headerName: 'Tanggal PP',
                    field: 'date',
                    sortable: false, resizable: true, filter: false,
                    cellClass: ['border-r', '!border-gray-200', 'justify-start']
                  },
                  {
                    flex: 1,
                    headerName: 'Tipe Barang',
                    field: 'tipe_item',
                    sortable: false, resizable: true, filter: false,
                    cellClass: ['border-r', '!border-gray-200', 'justify-start']
                  }
                ]"/> 
          </div>
          <div>
            <label>Tipe Export</label>
              <FieldSelect 
                :bind="{ readonly: !actionText, clearable: false }" 
                class="col-span-8 !mt-0 w-full"
                :value="values.tipe" 
                :errorText="formErrors.tipe ? 'failed' : ''"
                @input="v => values.tipe = v" 
                :hints="formErrors.tipe" 
                :check="false"
                label=""
                :options="['Excel','PDF','HTML']"
                placeholder="Pilih Tipe Export"
                valueField="key" 
                displayField="key"
            />
          </div>
      </div>
        <div class="flex flex-row justify-end space-x-[20px] mt-[1em]">
          <button @click="onGenerate" class="bg-green-600 hover:bg-green-800 duration-300 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            {{ values.tipe?.toLowerCase() === 'html' ? 'View' : 'Export' }}
          </button>
        </div>
        <!-- END COLUMN -->
        <!-- ACTION BUTTON START -->
        <div class="overflow-x-auto mt-6 mb-4 px-4" v-show="exportHtml">
          <hr>
          <div id="exportTable" class="w-[200%] mt-6">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endverbatim