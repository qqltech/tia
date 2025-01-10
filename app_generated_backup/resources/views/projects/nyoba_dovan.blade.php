<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-gray-500">
  <div class="flex justify-between items-center px-2.5 py-1">
    <div class="flex items-center gap-x-4">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true,1)" :class="activeBtn === 1?'bg-green-600 text-white hover:bg-green-400':'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Aktif</button>
        <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
        <button @click="filterShowData(false,2)" :class="activeBtn === 2?'bg-red-600 text-white hover:bg-red-400':'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Inaktif</button>
      </div>
    </div>
    <div>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))" class="border border-blue-600 text-blue-600 bg-white hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions" class="max-h-[450px]"></TableApi>
</div>
@else

<!-- CONTENT -->
@verbatim
<style>
  .infoPlaceholder::-webkit-input-placeholder {
    font-weight: bold;
    color:#AFB2B3;
  }
  .infoPlaceholder::-moz-placeholder {
      font-weight: bold;
      color:#AFB2B3;
  }
  .infoPlaceholder::-ms-input-placeholder {
      font-weight: bold;
      color:#AFB2B3;
  }
</style>
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-gray-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali" @click="onBack"/>
      <div>
        <h1 class="text-20px font-bold">Form Tes</h1>
        <p class="text-gray-100">Untuk mengatur informasi tes pada sistem</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldX class="w-full !mt-3 infoPlaceholder" :bind="{ readonly: !actionText }" 
        :vaitem.nomor" :errorText="formErrors.nomor?'failed':''"
        @input="v=>values.nomor=v" :hints="formErrors.nomor" 
        placeholder="Nomor" :check="false"
      />
    </div>
    <div>
      <FieldSelect class="w-full !mt-3"
        :bind="{ disabled: !actionText, clearable:false }"
        :value="values.cust_name" @input="v=>values.cust_name=v"
        :errorText="formErrors.cust_name?'failed':''" 
        :hints="formErrors.cust_name"
        valueField="name" displayField="name"
        :api="{
            url: `${store.server.url_backend}/operation/default_users`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              transform:false,
              join:false
            }
        }"
        placeholder="Pilih Customer" :check="false"
      />
    </div>
    <div>
      <FieldX class="w-full !mt-3" :bind="{ readonly: !actionText }" 
        :value="values.cust_addr" :errorText="formErrors.cust_addr?'failed':''"
        @input="v=>values.cust_addr=v" :hints="formErrors.cust_addr" 
        placeholder="Address" :check="false"
      />
    </div>
    <div>
      <FieldNumber class="w-full !mt-3"
        :bind="{ readonly: !actionText }"
        :value="values.subtotal" @input="(v)=>values.subtotal=v"
        :errorText="formErrors.subtotal?'failed':''" 
        :hints="formErrors.subtotal"
        placeholder="Subtotal" :check="false"
      />
    </div>
    <div class="col-span-3">
      <button @click="addDetail" type="button" class="bg-blue-500 text-white font-bold hover:bg-blue-400 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2 my-3">
        <i class="fas fa-plus"></i> Tambah
      </button>
    </div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
  <table class="w-[150%] lg:w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
    <thead>
      <tr class="border">
        <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No.</td>
        <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">Item</td>
        <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">Price</td>
        <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">Qty</td>
        <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">Subtotal</td>
        <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">Action</td>
      </tr>
    </thead>
    <tbody>
      <tr v-if="detailArr.length === 0" class="text-center">
        <td colspan="6" class="py-[20px]">No data to show</td>
      </tr>
      <tr v-else v-for="(item, index) in detailArr" :key="index" class="border">
        <td class="p-2 border border-[#CACACA] text-center">{{ index + 1 }}</td>
        <td class="p-2 border border-[#CACACA] text-center">
          <FieldSelect
            class="!mt-0"
            label=""
            :bind="{ disabled: !actionText, clearable:false }"
            :value="item.m_item_id" @input="v=>item.m_item_id=v"
            :errorText="formErrors.m_item_id?'failed':''" 
            :hints="formErrors.m_item_id"
            valueField="id" displayField="name_long"
            :api="{
                url: `${store.server.url_backend}/operation/m_item`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  transform:false,
                  join:false
                }
            }"
            placeholder="Item" :check="false"
          />
          
        </td>
        <td class="p-2 border border-[#CACACA] text-center">
          <FieldNumber 
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            label=""
            :value="item.price" 
            @input="(v) => item.price = v"
            placeholder="Masukan Price" 
            :check="false"
          />
        </td>
        <td class="p-2 border border-[#CACACA] text-center">
          <FieldNumber
            :bind="{ readonly: !actionText }" 
            label=""
            :value="item.qty" 
            @input="(v) => item.qty = v"
            placeholder="Jumlah" 
            :check="false"
          />
        </td>
        <td class="p-2 border border-[#CACACA] text-center">
          <FieldNumber
            :bind="{ readonly: !actionText }" 
            label=""
            :value="item.subtotal" 
            @input="(v) => item.subtotal = v"
            placeholder="Subtotal" 
            :check="false"
          />
        </td>
        <td class="p-2 border border-[#CACACA] text-center">
          <button 
            @click="removeDetail(index)" 
            v-show="actionText" 
            class="bg-red-500 border text-white hover:bg-red-400 transition-transform duration-300 transform hover:-translate-y-0.5 mx-1 p-1 rounded"
          >
            <i class="fas fa-trash"></i>
          </button>
        </td>
      </tr>
    </tbody>
  </table>
  <h1>Nyoba hafizh</h1>
  <TableStatic
    customClass="h-50vh w-full"
    ref="detail" 
    :value="dataDetail" 
    @input="onRetotal"
    :columns="[{
        headerName: 'No',
        cellRenderer: !actionText?null:'ButtonGrid',
        valueGetter:p=>p.node.rowIndex + 1,
        cellRendererParams: !actionText?null:{
          showValue: true,
          icon: 'times',
          class: 'btn-text-danger',
          click:(app)=>{
            if (app && app.params) {
              const row = app.params.node.data
              swal.fire({
                icon: 'warning', showDenyButton: true,
                text: `Hapus Baris ${app.params.node.rowIndex-(-1)}?`,
              }).then((res) => {
                if (res.isConfirmed) {
                  app.params.api.applyTransaction({ remove: [app.params.node.data] })
                }
              })
            }
          }
        },
        width: 60,
        sortable: false, resizable: true, filter: false,
        cellClass: ['justify-center', 'bg-gray-50']
      },
      {
        flex: 1,
        headerName: 'Field Select',
        field: 'field_select',
        editable: actionText?true:false,
        sortable: false, resizable: true, filter: false,
        cellClass: ['border-gray-50'],
        cellEditor: 'FieldSelect',
        cellEditorParams: {
            options: ['English', 'Spanish', 'French', 'Portuguese', '(other)'],
        },
        cellRenderer: SelectCellRender
      },
      ]"
    >
    <template #header>
      <div class="hidden !left-[-5%] !text-gray-500 w-5 h-5 !relative !absolute !border-none !border-r-0 absolute top-1.5 -left-5 w-full flex justify-between z-[999]"></div>
    </template>
  </TableStatic>
  
</div>
     </div>
     </div>

      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
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