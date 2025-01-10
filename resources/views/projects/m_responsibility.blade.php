<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex justify-between items-center gap-x-4 p-4">

    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true)" :class="filterButton === true ? 'bg-green-600 text-white hover:bg-green-600' 
          : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'" 
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          Active
        </button>
        <div class="flex my-auto h-4 w-px bg-gray-300"></div>
        <button @click="filterShowData(false)" :class="filterButton === false ? 'bg-red-600 text-white hover:bg-red-600' 
          : 'border border-red-600 text-red-600 bg-white hover:bg-red-600 hover:text-white'" 
          class="rounded text-sm py-1 px-2.5 transition-colors duration-300">
          InActive
        </button>
      </div>
    </div>

    <!-- ACTION BUTTON -->
    <div class="flex items-center gap-x-4">
      <RouterLink :to="$route.path + '/create?' + (Date.parse(new Date()))" class="border border-blue-600 
      text-blue-600 bg-white hover:bg-blue-600 hover:text-white text-sm rounded py-1 px-2.5
      transition-colors duration-300">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>

  <!-- TABLE -->
  <TableApi ref='apiTable' :api="table.api" :columns="table.columns" :actions="table.actions" class="max-h-[500px] pt-2 !px-4 !pb-8">
    <template #header>
      <div class="pb-13 h-full"></div>
    </template>
  </TableApi>
</div>
@else

<!-- FORM DATA -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md pt-2 pb-3 px-4">
    <div class="flex items-center gap-2">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
      <div class="flex flex-col py-1 gap-1">
        <h1 class="text-lg font-bold leading-none">Form Responsibility</h1>
        <p class="text-gray-100 leading-none">Atur berbagai menu untuk apps</p>
      </div>
    </div>
  </div>

  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 sticky">
    <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.nama" @input="v=>data.nama=v" 
      :errorText="formErrors.nama?'failed':''" :hints="formErrors.nama" placeholder="Nama" :check="false" />

    <div class="relative col-span-2">
      <div class="top-0 left-0 absolute w-full">
        <FieldX :bind="{ readonly: !actionText }" class="pt-1" :value="data.catatan" 
        :errorText="formErrors.catatan?'failed':''" @input="v=>data.catatan=v" :hints="formErrors.catatan" 
        placeholder="Catatan" :check="false" type="textarea" />
      </div>
    </div>

    <!-- CHECKBOX COMPONENT -->
    <div class="flex flex-col gap-2 pt-2 ml-1">
      <label class="text-gray-600 text-xs font-semibold">Status</label>
      <div class="flex gap-2">
        <div class="relative">
          <input class="relative h-[16px] w-7 p-px appearance-none rounded-full bg-white border disabled:!cursor-default
            hover:cursor-pointer after:content-[''] after:h-[10.5px] after:w-[10.5px] after:rounded-full after:border-none
            after:absolute after:mt-[0.9px] focus:outline-none after:ml-[0.95px] checked:after:right-[1.25px] disabled:opacity-75
            after:bg-red-600 border-red-600 checked:after:bg-green-600 checked:border-green-600" 
            type="checkbox" role="switch" :disabled="!actionText" v-model="data.is_active" />
        </div>
        <div :class="(data.is_active ? 'text-green-600' : 'text-red-600') + ' text-xs'">
          {{data.is_active ? 'Active' : 'InActive' }}
        </div>
      </div>
    </div>
    <!-- END CHECKBOX COMPONENT -->
  
  </div>
  <hr />

  <!-- DETAIL -->
  <div class="p-4 flex flex-col gap-2">
    <div class="flex items-center justify-start gap-2" v-show="actionText">
      <ButtonMultiSelect @add="addDetail" :api="{
                              url: `${store.server.url_backend}/operation/m_role`,
                              headers: {
                                'Content-Type': 'Application/json', 
                                authorization: `${store.user.token_type} ${store.user.token}`
                              },
                              params: { 
                                simplest: true, 
                                searchfield: 'this.name',
                                where: `this.is_active='true'`,
                                notin: `this.id: ${detail.data.map((det)=> (det.m_role_id))}`
                              },
                              onsuccess: (response) => {
                                response.data = [...response.data].map((dt) => {
                                  return {
                                    m_responsibility_id: data.id || null,
                                    m_role_id: dt.id,
                                    name: dt.name,
                                    is_active: dt.is_active,
                                    is_superadmin: dt.is_superadmin,
                                    text_superadmin: (dt.is_superadmin === true) ? 'Yes' : 'No',
                                    keterangan: '',
                                  }
                                })
                                return response
                              }
                           }" :columns="[{
                             checkboxSelection: true,
                             headerCheckboxSelection: true,
                             headerName: 'No',
                             valueGetter: (params) => '',
                             width: 60,
                             sortable: false, resizable: false, filter: false,
                             cellClass: ['justify-center', 'bg-gray-50']
                           }, {
                             pinned: false,
                             headerName: 'Role',
                             field: 'name',
                             cellClass: ['border-r', '!border-gray-200', 'justify-center'],
                             filter: 'ColFilter',
                             flex: 1
                            }, {
                              headerName: 'Super Admin',
                              field: 'text_superadmin',
                              editable: false,
                              sortable: false, resizable: true,
                              valueFormatter: params => {
                                if (params.data.is_superadmin) {
                                  params.colDef.cellClass = ['border-r', '!border-gray-200', 'justify-center', 'font-semibold', 'text-green-600'];
                                } else {
                                  params.colDef.cellClass = ['border-r', '!border-gray-200', 'justify-center', 'font-semibold', 'text-red-600'];
                                }
                              },
                          }]">
        <div class="text-xs rounded py-2 px-2.5 text-white bg-blue-600 hover:bg-blue-700 flex gap-x-1
            items-center transition-colors duration-300">
          <icon fa="plus" size="sm" />
          <span>Add To List</span>
        </div>
      </ButtonMultiSelect>
       <button @click="deleteDetailAll" class="text-xs rounded py-2 px-2.5 text-white bg-red-600 hover:bg-red-700 flex gap-x-1
          items-center transition-colors duration-300">
        <icon fa="trash" size="sm" />
        <span>Hapus Semua</span>
      </button>
    </div>

    <!-- TABLE -->
    <div class="relative">
    <TableStatic :value="detail.data" :key="detail.data" :columns="[{
                        headerName: 'No',
                        valueGetter: (params) => params.node.rowIndex + 1,
                        width: 60,
                        sortable: false,
                        resizable: false,
                        filter: false,
                        cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
                     }, {
                        headerName: 'Role',
                        field: 'name',
                        editable: false,
                        sortable: false, resizable: true, filter: 'agSetColumnFilter',
                        cellClass: ['!border-gray-200']
                     }, {
                        headerName: 'Super Admin',
                        field: 'text_superadmin',
                        editable: false, sortable: false, resizable: true,
                        valueFormatter: params => {
                          if (params.data.is_superadmin) {
                            params.colDef.cellClass = ['border-r', '!border-gray-200', 'justify-center', 'font-semibold', 'text-green-600'];
                          } else {
                            params.colDef.cellClass = ['border-r', '!border-gray-200', 'justify-center', 'font-semibold', 'text-red-600'];
                          }
                        },
                      },
                     {
                        flex: 1,
                        headerName: 'Keterangan',
                        field: 'keterangan',
                        editable: (actionText) ? true : false,
                        sortable: false, resizable: true, filter: 'agSetColumnFilter',
                        cellClass: ['!border-gray-200'],
                        cellEditor: 'FieldX',
                        valueFormatter: params => {
                          if(params.value === ''  && formErrors.keterangan){
                              params.colDef.cellClass = ['!border-red-300', 'bg-red-50'];
                          } else {
                              params.colDef.cellClass = ['!border-gray-200'];
                          }
                        },
                        cellEditorParams: {
                          input(val, api) {
                            api.data['keterangan'] = val;
                          }
                        }
                     }, {
                        headerName: '',
                        hide: !actionText,
                        cellRenderer: 'ButtonGrid',
                        cellRendererParams: {
                          showValue: true,
                          icon: 'times',
                          class: 'text-red-400 hover:text-red-600',
                          click: (app) => {
                            if (app && app.params && actionText) {
                              const row = app.params.node.data
                              swal.fire({
                                icon: 'warning', showDenyButton: true,
                                text: `Hapus ${app.params.node.data['name']}?`,
                              }).then((res) => {
                                if (res.isConfirmed) {
                                  deleteDetail(app.params.node.data);
                                }
                              })
                            }
                          }
                        },
                        width: 60,
                        sortable: false, resizable: true, filter: false,
                        cellClass: ['justify-center', 'bg-gray-50']
                     }]">
    </TableStatic>
    </div>
  </div>

  <!-- ACTION BUTTON FORM -->
  <hr v-show="actionText" />
  <div class="flex flex-row items-center justify-end space-x-2 py-3 px-4" v-show="actionText">
    <i class="text-gray-500 text-[12px] mr-4">Tekan CTRL + S untuk shortcut Save Data</i>
    <button class="text-sm rounded py-2 px-2.5 text-white bg-red-600 hover:bg-red-700 flex gap-x-1 
        items-center transition-colors duration-300" @click="onReset(true)">
      <icon fa="times" />
      <span>Reset</span>
    </button>
    <button class="text-sm rounded py-2 px-2.5 text-white bg-green-600 hover:bg-green-700 flex gap-x-1 items-center
        transition-colors duration-300" @click="onSave">
      <icon fa="save" />
      <span>Simpan</span>
    </button>
  </div>
</div>

<!-- DUMP TAILWIND OUTPUT -->
<div class="hidden !border-red-300 bg-red-50 text-green-600"></div>
@endverbatim
@endif