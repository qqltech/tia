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
  <TableApi ref='apiTable' :api="table.api" :columns="table.columns" :actions="table.actions" class="max-h-[500px] pt-2 !px-4 
  !pb-8">
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
        <h1 class="text-lg font-bold leading-none">Form Role</h1>
        <p class="text-gray-100 leading-none">Atur berbagai menu untuk apps</p>
      </div>
    </div>
  </div>

  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 sticky">
    <FieldX :bind="{ readonly: !actionText }" class="pt-1 col-span-3" :value="data.name" @input="v=>data.name=v" 
      :errorText="formErrors.name?'failed':''" :hints="formErrors.name" placeholder="Nama" :check="false" />

    <!-- <FieldSelect :bind="{ disabled: !actionText, clearable: false }" :value="data.is_superadmin" @input="v=>data.is_superadmin=v" 
      :errorText="formErrors.is_superadmin?'failed':''" :hints="formErrors.is_superadmin" valueField="id" displayField="key" 
      :options="[{'id' : '1' , 'key' : 'Yes'}, {'id': '0', 'key' : 'No'}]" placeholder="Super Admin" :check="false"
      class="pt-1" /> -->

    <!-- CHECKBOX COMPONENT -->
    <div class="flex flex-col gap-2 pt-2 ml-1">
      <label class="text-gray-600 text-xs font-semibold">Super Admin</label>
      <div class="flex gap-2">
        <div class="relative">
          <input class="relative h-[16px] w-7 p-px appearance-none rounded-full bg-white border disabled:!cursor-default
            hover:cursor-pointer after:content-[''] after:h-[10.5px] after:w-[10.5px] after:rounded-full after:border-none
            after:absolute after:mt-[0.9px] focus:outline-none after:ml-[0.95px] checked:after:right-[1.25px] disabled:opacity-75
            after:bg-red-600 border-red-600 checked:after:bg-green-600 checked:border-green-600" 
            type="checkbox" role="switch" :disabled="!actionText" v-model="data.is_superadmin" />
        </div>
        <div :class="(data.is_superadmin ? 'text-green-600' : 'text-red-600') + ' text-xs'">
          {{data.is_superadmin ? 'Yes' : 'No' }}
        </div>
      </div>
    </div>
    <!-- END CHECKBOX COMPONENT -->

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
        url: `${store.server.url_backend}/operation/m_menu`,
        headers: {
          'Content-Type': 'Application/json', 
          authorization: `${store.user.token_type} ${store.user.token}`
        }, params: { 
            simplest: true, 
            searchfield: 'this.name, this.is_active',
            where: `this.is_active='true'`,
            notin: `this.id: ${detail.data.map((det)=> (det.m_menu_id))}`
            },
        onsuccess: (response) => {
          response.data = [...response.data].map((dt) => {
            return {
              m_role_id: data.id || null,
              m_menu_id: dt.id,
              modul: dt.modul,
              submodul: dt.submodul,
              menu: dt.menu,
              can_read: true,
              can_create: false,
              can_update: false,
              can_delete: false,
              can_verify: false
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
                             headerName: 'Modul',
                             field: 'modul',
                             cellClass: ['border-r', '!border-gray-200', 'justify-center'],
                             filter: 'ColFilter',
                             flex: 1
                            }, 
                            {
                             pinned: false,
                             headerName: 'Submodul',
                             field: 'submodul',
                             cellClass: ['border-r', '!border-gray-200', 'justify-center'],
                             filter: 'ColFilter',
                             flex: 1
                            }, 
                            {
                             pinned: false,
                             headerName: 'Menu',
                             field: 'menu',
                             cellClass: ['border-r', '!border-gray-200', 'justify-center'],
                             filter: 'ColFilter',
                             flex: 1
                            }, ]">
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
                          flex: 3,
                          headerName: 'Menu',
                          field: 'menu',
                          editable: false,
                          sortable: false, resizable: true, filter: 'agSetColumnFilter',
                          cellClass: ['!border-gray-200']
                      }, {
                          flex: 2,
                          headerName: 'Modul',
                          field: 'modul',
                          editable: false,
                          sortable: false, resizable: true, filter: 'agSetColumnFilter',
                          cellClass: ['!border-gray-200']
                      }, {
                          flex: 2,
                          headerName: 'Submodul',
                          field: 'submodul',
                          editable: false,
                          sortable: false, resizable: true, filter: 'agSetColumnFilter',
                          cellClass: ['!border-gray-200']
                      }, {
                          flex: 1,
                          headerName: 'Read',
                          field: 'can_read',
                          cellClass: ['justify-center', 'border-r', '!border-gray-200'],
                          width: 80, resizable: false, sortable: false, filter: false,
                          cellRenderer: 'ButtonGridCheck',
                          cellRendererParams: {
                            readonly: !actionText || data.can_read,
                            change: (app, isChecked) => {
                              app.params.node.data['can_read'] = (isChecked) ? true : false
                              app.params.api.applyTransaction({ update: [app.params.node.data] })
                            }
                          }
                      }, {
                          flex: 1,
                          headerName: 'Create',
                          field: 'can_create',
                          cellClass: ['justify-center', 'border-r', '!border-gray-200'],
                          width: 80, resizable: false, sortable: false, filter: false,
                          cellRenderer: 'ButtonGridCheck',
                          cellRendererParams: {
                            readonly: !actionText || data.can_create,
                            change: (app, isChecked) => {
                              app.params.node.data['can_create'] = (isChecked) ? true : false
                              app.params.api.applyTransaction({ update: [app.params.node.data] })
                            }
                          }
                      }, {
                          flex: 1,
                          headerName: 'Update',
                          field: 'can_update',
                          cellClass: ['justify-center', 'border-r', '!border-gray-200'],
                          width: 80, resizable: false, sortable: false, filter: false,
                          cellRenderer: 'ButtonGridCheck',
                          cellRendererParams: {
                            readonly: !actionText || data.can_update,
                            change: (app, isChecked) => {
                              app.params.node.data['can_update'] = (isChecked) ? true : false
                              app.params.api.applyTransaction({ update: [app.params.node.data] })
                            }
                          }
                      }, {
                          flex: 1,
                          headerName: 'Delete',
                          field: 'can_delete',
                          cellClass: ['justify-center', 'border-r', '!border-gray-200'],
                          width: 80, resizable: false, sortable: false, filter: false,
                          cellRenderer: 'ButtonGridCheck',
                          cellRendererParams: {
                            readonly: !actionText || data.can_delete,
                            change: (app, isChecked) => {
                              app.params.node.data['can_delete'] = (isChecked) ? true : false
                              app.params.api.applyTransaction({ update: [app.params.node.data] })
                            }
                          }
                      }, {
                          flex: 1,
                          headerName: 'Verify',
                          field: 'can_verify',
                          cellClass: ['justify-center', 'border-r', '!border-gray-200'],
                          width: 80, resizable: false, sortable: false, filter: false,
                          cellRenderer: 'ButtonGridCheck',
                          cellRendererParams: {
                            readonly: !actionText || data.can_verify,
                            change: (app, isChecked) => {
                              app.params.node.data['can_verify'] = (isChecked) ? true : false
                              app.params.api.applyTransaction({ update: [app.params.node.data] })
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
                                  text: `Hapus ${app.params.node.data['menu']}?`,
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

@endverbatim
@endif