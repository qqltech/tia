<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center px-2.5 py-1">
    <div class="flex items-center gap-x-4">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true,1)" :class="activeBtn === 1?'bg-green-600 text-white hover:bg-green-400':'border border-green-600 text-green-600 bg-white  hover:bg-green-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Active</button>
        <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
        <button @click="filterShowData(false,2)" :class="activeBtn === 2?'bg-red-600 text-white hover:bg-red-400':'border border-red-600 text-red-600 bg-white  hover:bg-red-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">InActive</button>
      </div>
    </div>
    <div>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))" class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
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
    <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
      <div class="flex items-center">
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali" @click="onBack"/>
        <div>
          <h1 class="text-20px font-bold">Produk</h1>
          <p class="text-gray-100">Master produk</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-2 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
          :value="values.code" :errorText="formErrors.code?'failed':''"
          @input="v=>values.code=v" :hints="formErrors.code" 
          label="Auto By Sistem"
          placeholder="Auto By Sistem"
          :check="false"
        />
      </div>
      <div>
        <FieldSelect 
          class="w-full !mt-3"
          :bind="{ readonly: !actionText }" 
          :value="values.m_product_cat_id" 
          :errorText="formErrors.m_product_cat_id ? 'failed' : ''"
          @input="v => values.m_product_cat_id = v" 
          :hints="formErrors.m_product_cat_id" 
          :check="false"
          label="Jenis Produk"
          placeholder="Pilih Jenis Produk"
          valueField="id" 
          displayField="name"
          :api="{
              url: `${store.server.url_backend}/operation/m_product_cat`,
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
        fa-icon="search" :check="true" />
      </div>
    
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.name" :errorText="formErrors.name?'failed':''"
          @input="v=>values.name=v" :hints="formErrors.name" 
          :check="true"
          label="Nama Produk"
          placeholder="Isikan Nama Produk"
        />
      </div>
      <div>
        <FieldUpload
          class="w-full !mt-3"
          :reducerDisplay="(val)=>!val?null:val.split(':::')[val.split(':::').length-1]"
          :api="{
              url: `${store.server.url_backend}/operation/m_product/upload`,
              headers: {Authorization: `${store.user.token_type} ${store.user.token}`},
              params: { field: 'thumbnail' },
              onsuccess: function(response){
                return response
              },
              onerror:(error)=>{
                alert(error.message)
              }
           }"
           accept="*"
          :value="values.thumbnail" @input="(v)=>values.thumbnail=v" :maxSize="2500"
          :hints="formErrors.thumbnail" 
          label="Thumbnail Produk"
          placeholder="Thumbnail Produk" fa-icon="upload"
          :check="false" />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.price" :errorText="formErrors.price?'failed':''"
          @input="v=>values.price=v" :hints="formErrors.price" 
          :check="false"
          label="Harga"
          placeholder="Harga"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.price_best" :errorText="formErrors.price_best?'failed':''"
          @input="v=>values.price_best=v" :hints="formErrors.price_best" 
          :check="false"
          label="Harga Spesial"
          placeholder="Harga Spesial"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.discount_reguler_pctg" :errorText="formErrors.discount_reguler_pctg?'failed':''"
          @input="v=>values.discount_reguler_pctg=v" :hints="formErrors.discount_reguler_pctg" 
          :check="false"
          label="Diskon Reguler (%)"
          placeholder="Diskon Reguler (%)"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.discount_reguler" :errorText="formErrors.discount_reguler?'failed':''"
          @input="v=>values.discount_reguler=v" :hints="formErrors.discount_reguler" 
          :check="false"
          label="Diskon Reguler Nominal"
          placeholder="Diskon Reguler Nominal"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.discount_member_pctg" :errorText="formErrors.discount_member_pctg?'failed':''"
          @input="v=>values.discount_member_pctg=v" :hints="formErrors.discount_member_pctg" 
          :check="false"
          label="Diskon Member (%)"
          placeholder="Diskon Member (%)"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.discount_member" :errorText="formErrors.discount_member?'failed':''"
          @input="v=>values.discount_member=v" :hints="formErrors.discount_member" 
          :check="false"
          label="Diskon Member Nominal"
          placeholder="Diskon Member Nominal"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.discount_premium_pctg" :errorText="formErrors.discount_premium_pctg?'failed':''"
          @input="v=>values.discount_premium_pctg=v" :hints="formErrors.discount_premium_pctg" 
          :check="false"
          label="Diskon Premium (%)"
          placeholder="Diskon Premium (%)"
        />
      </div>
      <div>
        <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.discount_premium" :errorText="formErrors.discount_premium?'failed':''"
          @input="v=>values.discount_premium=v" :hints="formErrors.discount_premium" 
          :check="false"
          label="Diskon Premium Nominal"
          placeholder="Diskon Premium Nominal"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.desc" :errorText="formErrors.desc?'failed':''"
          @input="v=>values.desc=v" :hints="formErrors.desc" 
          :check="true"
          type="textarea"
          label="Deskripsi"
          placeholder="Deskripsi"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.tags" :errorText="formErrors.tags?'failed':''"
          @input="v=>values.tags=v" :hints="formErrors.tags" 
          :check="true"
          label="Tag"
          placeholder="Tag | ex: #diskonbesar #promoakhirbulan"
        />
      </div>
      <div>     
        <FieldSelect
          :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3"
          :value="values.is_active" @input="v=>{
            if(v){
              values.is_active=v
            }else{
              values.is_active=null
            }
          }"
          :errorText="formErrors.is_active?'failed':''" 
          :hints="formErrors.is_active"
          valueField="id" displayField="key"
          :options="[{'id' : 1 , 'key' : 'Active'},{'id': 0, 'key' : 'InActive'}]"
          placeholder="Pilih Status" label="Status" :check="false"
        />  
      </div>
      


      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
    </div>
    <div class="p-4 grid grid-cols-2  gap-x-2">
        <div class="flex col-span-2">
          <h4 class="font-semibold ">Detail Foto Produk</h4>
          <Icon fa="plus" class="cursor-pointer" @click="addRow"/>
        </div>
      <div class="flex items-center"   v-for="(d, idx) in values.m_product_det_photo">
        <FieldUpload  
          class="w-full !mt-4"
          :reducerDisplay="(val)=>!val?null:val.split(':::')[val.split(':::').length-1]"
          :api="{
              url: `${store.server.url_backend}/operation/m_product_det_photo/upload`,
              headers: {Authorization: `${store.user.token_type} ${store.user.token}`},
              params: { field: 'photo' },
              onsuccess: function(response){
                return response
              },
              onerror:(error)=>{
                alert(error.message)
              }
           }"
           accept="*"
          :value="values.m_product_det_photo[idx]['photo']" @input="(v)=>values.m_product_det_photo[idx]['photo']=v" :maxSize="2500"
          :hints="formErrors.thumbnail" 
          label=""
          placeholder="" fa-icon="upload"
          :check="false" />
        <Icon fa="trash" class="text-red-500 cursor-pointer" @click="deleteRow(idx)"/>
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