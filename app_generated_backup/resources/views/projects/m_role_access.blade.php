<!-- LANDING TABLE -->
@if(!$req->has('id'))
<div class="bg-white rounded-md min-h-[520px] border-t-10 border-blue-500">
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
        <h1 class="text-lg font-bold leading-none">Form Role Akses</h1>
        <p class="text-gray-100 leading-none">Atur berbagai menu untuk apps</p>
      </div>
    </div>
  </div>

  <!-- HEADER -->
  <div class="pt-2 pb-4 px-4 grid grid-cols-3 gap-y-2 gap-x-4 sticky">
    <FieldX :bind="{ readonly: true }" class="pt-1 col-span-3" :value="data.name" placeholder="User" :check="false" />
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
        }, params: { 
            simplest: true, 
            searchfield: 'this.name',
            where: `this.is_active='true'`,
            notin: `this.id: ${detail.data.map((det)=> (det.m_role_id))}`
            },
        onsuccess: (response) => {
          response.data = [...response.data].map((dt) => {
            return {
              m_role_id: dt.id,
              role: dt.name,
              superadmin: dt.is_superadmin ? 'Yes' : 'No',
              is_superadmin: dt.is_superadmin
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
                             field: 'role',
                             cellClass: ['border-r', '!border-gray-200', 'justify-center'],
                             filter: 'ColFilter',
                             flex: 1
                            }, {
                              headerName: 'Super Admin',
                              field: 'superadmin',
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
                          flex: 3,
                          headerName: 'Role',
                          field: 'role',
                          editable: false,
                          sortable: false, resizable: true, filter: 'agSetColumnFilter',
                          cellClass: ['!border-gray-200']
                      }, {
                              headerName: 'Super Admin',
                              field: 'superadmin',
                              editable: false,
                              sortable: false, resizable: true,
                              valueFormatter: params => {
                                if (params.data.is_superadmin) {
                                  params.colDef.cellClass = ['border-r', '!border-gray-200', 'justify-center', 'font-semibold', 'text-green-600'];
                                } else {
                                  params.colDef.cellClass = ['border-r', '!border-gray-200', 'justify-center', 'font-semibold', 'text-red-600'];
                                }
                              },
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
                                  text: `Hapus ${app.params.node.data['role']}?`,
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
<div class="hidden text-green-600"></div>
@endverbatim
@endif