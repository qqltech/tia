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
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px]">
    <!-- <template #header>
    </template> -->
  </TableApi>
</div>
@else

<!-- CONTENT -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-transparent text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold text-gray-400 hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-black text-20px font-bold">User Header</h1>
        <p class="text-red-500">new data</p>
      </div>
    </div>
  </div>
  <!-- START COLUMN -->
  <div class="p-4 grid <md:grid-cols-1 grid-cols-2 gap-2 ">
    <div class="grid grid-cols-4 ">
    <label class="my-auto font-bold">Nama User</label>
    <FieldPopup class="w-full col-span-2 !mt-3" :bind="{ readonly: !actionText }" :value="values.name" @input="(v)=>values.name=v"
      :errorText="formErrors.name?'failed':''" :hints="formErrors.name" valueField="id" displayField="key" :api="{
        url: 'endpoint',
        headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
        params: {
          simplest:true,
        }
      }" placeholder="nama user" :check="false" :columns="[{
        headerName: 'No',
        valueGetter:(p)=>p.node.rowIndex + 1,
        width: 60,
        sortable: false, resizable: false, filter: false,
        cellClass: ['justify-center', 'bg-gray-50']
      },
      {
        flex: 1,
        field: 'columnname',
        headerName:  'Label Header Name',
        sortable: false, resizable: true, filter: 'ColFilter',
        cellClass: ['border-r', '!border-gray-200', 'justify-center']
      }]" />
    </div>

    <div class="w-full !mt-3 grid grid-cols-4">
      <label class="font-semibold my-auto">Status</label>
      <FieldSelect class="col-span-2 !mt-0" :bind="{ disabled: !actionText, clearable:true }" :value="values.act"
        @input="v=>values.act=v" :errorText="formErrors.act?'failed':''" :hints="formErrors.act" valueField="id"
        displayField="key" :options="[{'id' : 1, 'key' : 'Aktif'},{'id': 0, 'key' : 'Tidak Aktif'}]"
        placeholder="Status" label="" :check="false" />
    </div>

    <div class="w-full !mt-3 grid grid-cols-4">
      <label class="font-semibold my-auto">NIK</label>
      <FieldX class="!mt-0 col-span-2" :bind="{ readonly: !actionText }" :value="values.nik"
        :errorText="formErrors.nik?'failed':''" @input="v=>values.nik=v" :hints="formErrors.nik" placeholder="NIK"
        :check="false" />
    </div>

    <div class="w-full !mt-3 grid grid-cols-4">
      <label class="font-semibold my-auto">Devisi</label>
      <FieldX class="!mt-0 col-span-3" :bind="{ readonly: !actionText }" :value="values.devisi"
        :errorText="formErrors.devisi?'failed':''" @input="v=>values.devisi=v" :hints="formErrors.devisi" placeholder="Devisi" label=""
        :check="false" />
    </div>

    <div class="w-full !mt-3 grid grid-cols-4">
      <label class="font-semibold my-auto">User Login</label>
      <FieldX class="!mt-0 col-span-2" :bind="{ readonly: !actionText }" :value="values.login"
        :errorText="formErrors.login?'failed':''" @input="v=>values.login=v" :hints="formErrors.login" placeholder="User login" label=""
        :check="false" />
    </div>
    
     <div class="w-full !mt-3 grid grid-cols-4">
      <label class="font-semibold my-auto">Tipe User</label>
      <FieldSelect class="col-span-2 !mt-0" :bind="{ disabled: !actionText, clearable:true }" :value="values.tipe"
        @input="v=>values.tipe=v" :errorText="formErrors.tipe?'failed':''" :hints="formErrors.tipe" valueField="id"
        displayField="key" :options="[{'id' : 'ADMIN' , 'key' : 'Admin'},{'id': 'SUPER ADMIN', 'key' : 'Super Admin'}]"
        placeholder="Status" label="" :check="false" />
    </div>

     <div class="w-full !mt-3 grid grid-cols-4">
      <label class="font-semibold my-auto">Password</label>
      <FieldX class="!mt-0 col-span-2" :bind="{ readonly: !actionText }" :value="values.pw"
        :errorText="formErrors.pw?'failed':''" @input="v=>values.pw=v" :hints="formErrors.pw" placeholder="User password" label=""
        :check="false" />
    </div>
   
    <div class="w-full !mt-3 grid grid-cols-4">
      <label class="font-semibold my-auto" >Catatan</label>
      <FieldX class="!mt-0 col-span-3" :bind="{ readonly: !actionText }" :value="values.deskripsi"
        :errorText="formErrors.deskripsi?'failed':''" @input="v=>values.deskripsi=v" :hints="formErrors.deskripsi"
        type="textarea" placeholder="Deskripsi" label="" :check="false" />
    </div>
  </div>
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
            <div class="bg-blue-600 text-white font-semibold hover:bg-blue-500 rounded p-1.5 mt-3">
              <icon fa="plus" size="sm mr-0.5"/> Add to list
            </div>
        </ButtonMultiSelect>

  <!-- END COLUMN -->

  <!-- FORM START -->
   <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3 mt-4">
          <table class="w-full overflow-x-auto table-auto border border-[#CACACA] pt-4">
            <thead>
              <tr class="border">
                <td class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No</td>
                <td class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center w-[47%] border bg-[#f8f8f8] border-[#CACACA]">Kode Role</td>
                <td class="text-[#8f8f8f] font-semibold text-[14px] text-capitalize p-2 text-center w-[48%] border bg-[#f8f8f8] border-[#CACACA]">Nama Role</td>
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
  <!-- FORM END -->


  <!-- ACTION BUTTON START -->
</div>
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