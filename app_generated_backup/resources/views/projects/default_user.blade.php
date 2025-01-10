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
          <h1 class="text-20px font-bold">Form User</h1>
          <p class="text-gray-100">Untuk mengatur informasi user pada sistem</p>
        </div>
      </div>
    </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row gap-x-4 gap-y-4 mb-5 p-4">
        <div class="w-full !mt-3">
        <FieldPopup
        class="!mt-0"
        :api="{
            url: `${store.server.url_backend}/operation/m_kary`,
            headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
              simplest:false,
              // transform:false,
              // join:true,
              // override:true,
              // where:`this.is_active=true`,
              // searchfield:'this.no_id, this.nip, this.nama, this.alamat_domisili',
              // selectfield: 'this.no_id,this.nip, this.nama, this.alamat_domisili' 
            },
          }"

        displayField="nama"
        valueField="id"
        :bind="{ readonly: !actionText }"
        :value="values.m_employee_id" @input="(v)=>values.m_employee_id=v"

        @update:valueFull="(response)=>{
          values.name = response.nama;
          values.email = response.email;
          return response;
        }"
        :errorText="formErrors.m_employee_id?'failed':''"  class="w-full !mt-3"
        :hints="formErrors.m_employee_id" placeholder="Pilih Nama Karyawan" 
        :check= 'false'
        :columns="[{
            headerName: 'No',
            valueGetter:(p)=>p.node.rowIndex + 1,
            width: 60,
            sortable: false, resizable: false, filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'nip',
            headerName: 'NIP',
            sortable: false, resizable: true, filter: false,
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          },
          {
            flex: 1,
            headerName: 'Nama',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            field: 'nama',
            sortable: false, resizable: true, filter: false,
          },
          {
            flex: 1,
            headerName: 'Alamat',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            field: 'alamat_domisili',
            sortable: false, resizable: true, filter: false,
          },
          {
            flex: 1,
            headerName: 'Email',
            cellClass: ['justify-center', 'border-r', '!border-gray-200',],
            field: 'email',
            sortable: false, resizable: true, filter: false,
          }
          ]"/>          
        </div>
        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.username" :errorText="formErrors.username?'failed':''"
            @input="v=>values.username=v" 
            :hints="formErrors.username" 
            placeholder="Masukkan Username" :check="false"
          />
        </div>

      <div class="w-full !mt-3">
          <FieldSelect
            class="!mt-0"
            :bind="{ disabled: !actionText, clearable:true }"
            :value="values.tipe" @input="v=>values.tipe=v"
            :errorText="formErrors.tipe?'failed':''" 
            :hints="formErrors.tipe"
            valueField="id" displayField="key"
            :options="[{'id' : 'ADMIN' , 'key' : 'Admin'},{'id': 'SUPER ADMIN', 'key' : 'Super Admin'}]"
            placeholder="Pilih Tipe User"  :check="false"
          /> 
        </div>

        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.catatan" :errorText="formErrors.catatan?'failed':''"
            @input="v=>values.catatan=v" 
            :hints="formErrors.catatan" 
            type="textarea"
            placeholder="Catatan" :check="false"
          />
        </div>
        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.password" :errorText="formErrors.password?'failed':''"
            @input="v=>values.password=v" 
            :hints="formErrors.password" 
            placeholder="Masukkan Password" :check="false"
          />
        </div>
        <div class="w-full !mt-3">
          <FieldX 
            class="!mt-0"
            :bind="{ readonly: !actionText }" 
            :value="values.password_confirm" :errorText="formErrors.password_confirm?'failed':''"
            @input="v=>values.password_confirm=v" 
            :hints="formErrors.password_confirm" 
            placeholder="Masukkan Ulang Password" :check="false"
          />
        </div>
        <div class="w-full flex flex-col gap-2 pt-2 ml-1">
          <label class="text-gray-600 text-xs font-semibold">Status</label>
          <div class="flex gap-2">
            <div class="relative">
              <input class="relative h-[16px] w-7 p-px appearance-none rounded-full bg-white border disabled:!cursor-default
                hover:cursor-pointer after:content-[''] after:h-[10.5px] after:w-[10.5px] after:rounded-full after:border-none
                after:absolute after:mt-[0.9px] focus:outline-none after:ml-[0.95px] checked:after:right-[1.25px] disabled:opacity-75
                after:bg-red-600 border-red-600 checked:after:bg-green-600 checked:border-green-600" 
                type="checkbox" role="switch" :disabled="!actionText" v-model="values.is_active" />
            </div>
            <div :class="(values.is_active ? 'text-green-600' : 'text-red-600') + ' text-xs'">
              {{values.is_active ? 'Active' : 'InActive' }}
            </div>
          </div>
        </div>
      </div>
     
        
      
      <!-- START TABLE DETAIL -->
      <hr class="<md:col-span-1 col-span-3">
      <div class="<md:col-span-1 col-span-3 pl-4 pr-4">
        <ButtonMultiSelect
          title="Add to list"
          @add="onDetailAdd"
          :api="{
            url: `${store.server.url_backend}/operation/m_responsibility`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { simplest: true },
            onsuccess:(response)=>{
              response.data = [...response.data].map((dt)=>{
                //Object.keys(dt).forEach(k=>dt['m_barang.'+k] = dt[k])
                return dt
              })
              response.page = response.current_page
              response.hasNext = response.has_next
              return response
            }
          }"
          :columns="[{
            checkboxSelection: true,
            headerCheckboxSelection: true,
            headerName: 'No',
            valueGetter:p=>'',
            width:60,
            sortable: false, resizable: true, filter: false,
            cellClass: ['justify-center', 'bg-gray-50', '!border-gray-200']
          },
          {
            flex: 1,
            headerName:'Responsibility',
            sortable: false, resizable: true, filter: 'ColFilter',
            field: 'nama',
            cellClass: ['justify-center','!border-gray-200']
          }]">
            <div class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5 mt-3">
              <icon fa="plus" size="sm mr-0.5"/> Add to list
            </div>
        </ButtonMultiSelect>
        <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3 mt-4">
          <table class="w-full overflow-x-auto table-auto border border-[#CACACA] pt-4">
            <thead>
              <tr class="border">
                <td class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No</td>
                <td class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center w-[80%] border bg-[#f8f8f8] border-[#CACACA]">Responsibility</td>
                <td class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center w-[15%] border bg-[#f8f8f8] border-[#CACACA]">Action</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length > 0">
                  <td class="p-2 text-center border border-[#CACACA]">
                    {{ i + 1 }}. 
                  </td>
                  <td class="p-2 text-center">{{item.nama}}
                  </td>
                   <td class="p-2 border border-[#CACACA]">
                    <div class="flex justify-center">
                      <button type="button" @click="removeDetail(i)" :disabled="!actionText">
                      <svg width="14" height="14" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                      </svg>
                    </button>
                    </div>
                  </td>
                </tr>
                <tr v-else class="text-center">
                  <td colspan="7" class="py-[20px]">
                    No data to show
                  </td>
                </tr>
              </tr>
            </tbody>
          </table>
        </div>
      <!-- END TABLE DETAIL -->

      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
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