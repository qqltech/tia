<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-gray-500">
  <div class="flex justify-between items-center px-2.5 py-1">
    <div class="flex items-center gap-x-4">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true,1)" :class="activeBtn === 1?'bg-green-600 text-white hover:bg-green-400':'border border-green-600 text-green-600 bg-white  hover:bg-green-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Active</button>
        <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
        <button @click="filterShowData(false,2)" :class="activeBtn === 2?'bg-red-600 text-white hover:bg-red-400':'border border-red-600 text-red-600 bg-white  hover:bg-red-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Inactive</button>
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
    <div class="bg-gray-500 text-white rounded-t-md py-2 px-4">
      <div class="flex items-center">
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali" @click="onBack"/>
        <div>
          <h1 class="text-20px font-bold">Form Template Header Detail</h1>
          <p class="text-gray-100">Master Tempplate Header Detail Dengan Multi Select</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-2 gap-2 ">
      <!-- START COLUMN -->
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
            placeholder="Pilih Direktorat"
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
      <div>   
        <FieldX  
            class="w-full !mt-3"
            :bind="{ readonly: !actionText }"
            :value="values.nama" :errorText="formErrors.nama?'failed':''"
            @input="v=>values.nama=v"
            :hints="formErrors.nama"
            label="Nama"
            placeholder="Masukan Nama"
            :check="false" />
      </div>
      <div>
        <FieldX  
            class="w-full !mt-3"
            :bind="{ readonly: !actionText }"
            :value="values.desc" :errorText="formErrors.desc?'failed':''"
            @input="v=>values.desc=v"
            type="textarea"
            :hints="formErrors.desc"
            label="Keterangan"
            placeholder="Tuliskan Keterangan"
            :check="false" />
      </div>
      <div>
        <FieldSelect
          :bind="{ disabled: !actionText, clearable:false }" class="w-full !mt-3"
          :value="values.is_active" @input="v=>values.is_active=v"
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
        <div class="flex justify-start items-center space-x-4 px-4 pt-4">
          <h1 class="font-semibold text-lg">Detail Form</h1>
        </div>
      <div class="<md:col-span-1 col-span-3 p-4 pt-0 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <ButtonMultiSelect title="Add To List" @add="onDetailAdd" :api="{
            url: `${store.server.url_backend}/operation/t_si`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: { 
                simplest: true,
                unit_name: true,
                searchfield: 'this.code, this.name, this.shortname',
                //notin: detailArr.length>0?`t_si.id:${detailArr.map(dt=>dt.t_si_id).join(',')}`:null
                },
            onsuccess:(response)=>{
              response.data = [...response.data].map((dt)=>{
                Object.keys(dt).forEach(k=>dt['t_si.'+k] = dt[k])
                return dt
              })
              response.page = response.current_page
              response.hasNext = response.has_next
              return response
            }
          }" :columns="[{
            checkboxSelection: true,
            headerCheckboxSelection: true,
            headerName: 'No',
            valueGetter:p=>'',
            width:60,
            sortable: false, resizable: true, filter: false,
            cellClass: ['justify-start', 'bg-gray-50', '!border-gray-200']
          },
          {
            flex: 1,
            headerName:'Nama',
            sortable: false, resizable: true, filter: 'ColFilter',
            field: 'no',
            cellClass: ['justify-start','!border-gray-200']
          },
          {
            flex: 1,
            headerName:'Tanggal',
            sortable: false, resizable: true, filter: 'ColFilter',
            field: 'date',
            cellClass: ['justify-start','!border-gray-200']
          }
          ]">
        <div class="flex items-center space-x-2">
          <div v-show="actionText"
            class="bg-blue-600 text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
            <icon fa="plus" />
            Add To List
          </div>
        </div>
      </ButtonMultiSelect>

      <!-- valueGetter:(p)=> p.node.data['spec_type_text'], -->
      <TableStatic 
      customClass="h-50vh" 
      class="<md:col-span-1 col-span-3" 
          :key="tableKey"
          ref="tableDetail" 
          :value="detailArr" 
          @input="onRetotal"
          :columns="[{
                headerName: 'No',
                cellRenderer: !actionText?null:'ButtonGrid',
                valueGetter:(params)=>{
                  return params.node.rowIndex + 1
                },
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
                          removeDetail(app.params.node.data.rowIndex)
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
                filter:true,
                headerName: 'Nama',
                field:'m_cust.name',
                autoHeight:true,wrapText: true,
                sortable: false, resizable: true,
                cellClass: ['!border-gray-200', 'bg-gray-50', 'justify-start']
              },
              {
                filter:true,
                headerName: 'Tanggal',
                field:'no',
                autoHeight:true,wrapText: true,
                sortable: false, resizable: true,
                cellClass: ['!border-gray-200', 'bg-gray-50', 'justify-start']
              },
              { 
                width: 150, 
                headerName: 'Jumlah',
                field: 'jml_tagihan',
                editable: actionText ? true : false,
                sortable: false,
                resizable: true,
                filter: false,
                cellClass: ['!border-gray-200', 'justify-end'],
                cellRenderer: (p) => parseFloat(p.value||0).toLocaleString('id'),
                cellEditor: 'FieldNumber',
                cellEditorParams: {
                  input(val, api) {
                    api.data['jml_tagihan']=val
                  },
                },
              },
              {
                headerName: 'Note',
                field: 'note',
                editable: actionText ? true : false,
                sortable: false,
                resizable: true,
                filter: false,
                cellClass: ['!border-gray-200', 'justify-start'],
                cellEditor: 'agLargeTextCellEditor',
                cellEditorParams: {
                  maxLength: 200,
                  input(val, api) {
                    api.data['note'] = val;
                  }
                }
              },

            ]">
          <template #header></template>
        </TableStatic>
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