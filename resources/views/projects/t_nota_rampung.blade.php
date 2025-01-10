<!-- LANDING -->
@if(!$req->has('id'))

<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="p-2">
    <h1 class="text-xl font-semibold">Nota Rampung</h1>
  </div>
  <div class="flex justify-between items-center px-2.5 py-1">
    <!-- FILTER -->
    <!-- FILTER -->
    <div class="flex items-center gap-x-2">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData('DRAFT')" :class="filterButton === 'DRAFT' ? 'bg-green-600 text-white hover:bg-green-600' 
                        : 'border border-green-600 text-green-600 bg-white hover:bg-green-600 hover:text-white'"
                        class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
                    DRAFT
                </button>
        <div class="flex my-auto h-4 w-px bg-[#6E91D1]"></div>
        <button @click="filterShowData('POST')" :class="filterButton === 'POST' ? 'bg-yellow-600 text-white hover:bg-yellow-600' 
                        : 'border border-yellow-600 text-yellow-600 bg-white hover:bg-yellow-600 hover:text-white'"
                        class="rounded-md text-sm py-1 px-2.5 transition-colors duration-300">
                    POST
                </button>
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
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Nota Rampung</h1>
        <p class="text-gray-100">Transaksi Nota Rampung</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_draft"
        :errorText="formErrors.no_draft?'failed':''" @input="v=>values.no_draft=v" :hints="formErrors.no_draft"
        label="No. Draft" placeholder="No. Draft" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_nota_rampung"
        :errorText="formErrors.no_nota_rampung?'failed':''" @input="v=>values.no_nota_rampung=v"
        :hints="formErrors.no_nota_rampung" label="No. Nota Rampung" placeholder="No. Nota Rampung" :check="false" />
    </div>
    <div>
      <FieldPopup label="No. Buku Order" class="w-full !mt-3" valueField="id" displayField="no_buku_order"
        :value="values.t_buku_order_id" @input="(v)=>values.t_buku_order_id=v" @update:valueFull="(data)=>{
        
        //values.jenis_option = data.t_buku_order_d_npwp.map(res => (res.id));
        $log(data)
        
        if (data && data.m_customer_id) {
          values.customer = data['m_customer.nama_perusahaan'];
          values.pelabuhan = data.['pelabuhan.deskripsi'];
          values.tipe1 = data.tipe;
          
        } else {
          values.customer = ''; 
          values.pelabuhan = '';
          values.tipe1 = '';
          
        }
          return response;
        }" :api="{
              url: `${store.server.url_backend}/operation/t_buku_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                detail: true,
                join:true,
                simplest:true,
                searchfield: 'this.tgl , this.no_buku_order , this.jenis_barang, this.status',
                where: `this.status = 'POST'`
                
              }
            }" placeholder="Pilih No. Buku Order" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'tgl',
              headerName:  'Tanggal',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'no_buku_order',
              headerName:  'Nomor Buku Order',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'jenis_barang',
              headerName:  'Jenis Barang',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'm_customer.nama_perusahaan',
              headerName:  'Customer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
    </div>
    <div>
      <FieldX :bind="{ disabled: true, readonly: true }" class="w-full !mt-3" :value="values.tanggal"
        :errorText="formErrors.tanggal?'failed':''" @input="updateDate" :hints="formErrors.tanggal" :check="false"
        label="Tanggal Nota Rampung" placeholder="Pilih Tanggal Nota Rampung" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.customer"
        :errorText="formErrors.customer?'failed':''" @input="v=>values.customer=v" :hints="formErrors.customer"
        label="Customer" placeholder="Customer" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.pelabuhan"
        :errorText="formErrors.pelabuhan?'failed':''" @input="v=>values.pelabuhan=v" :hints="formErrors.pelabuhan"
        label="Pelabuhan" placeholder="Pelabuhan" :check="false" />
    </div>
    <div> 
      <FieldSelect :bind="{ disabled: true, clearable:false }" class="w-full !mt-3" :value="values.tipe1"
                  @input="v=>values.tipe1=v" valueField="id" displayField="deskripsi" :api="{          
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            transform:false,
                            join:false,
                            where:`this.is_active=true and this.group='TIPE'`
                          }
                      }" placeholder="Pilih Tipe" label=" Tipe Kontainer" :check="false" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.lolo_non_sp"
        :errorText="formErrors.lolo_non_sp?'failed':''" @input="v=>values.lolo_non_sp=v" :hints="formErrors.lolo_non_sp"
        :check="false" label="Biaya Lain-Lain" placeholder="Biaya Lain-Lain" />
    </div>

    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_stack"
        :errorText="formErrors.no_stack?'failed':''" @input="v=>values.no_stack=v" :hints="formErrors.no_stack"
        :check="false" label="Nomor Stack" placeholder="Nomor Stack" />
    </div>

    <div>
      <FieldX :bind="{ disabled: !actionText, readonly: !actionText }" class="w-full !mt-3" :value="values.tgl_stack"
        :errorText="formErrors.tgl_stack?'failed':''" type="date" @input="values.tgl_stack" :hints="formErrors.tgl_stack" :check="false"
        label="Tanggal Stack" placeholder="Pilih Tanggal Stack" />
    </div>

    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.no_eir"
        :errorText="formErrors.no_eir?'failed':''" @input="v=>values.no_eir=v" :hints="formErrors.no_eir"
        :check="false" label="Nomor Eir" placeholder="Nomor Eir" />
    </div>

    <div>
      <FieldX :bind="{ disabled: !actionText, readonly: !actionText }" class="w-full !mt-3" :value="values.tgl_eir"
        :errorText="formErrors.tgl_eir?'failed':''" @input="values.tgl_eir" type="date" :hints="formErrors.tgl_eir" :check="false"
        label="Tanggal Eir" placeholder="Pilih Tanggal Eir" />
    </div>

    <div>
      <FieldX :bind="{ disabled: !actionText, readonly: !actionText }" class="w-full !mt-3" :value="values.tgl_nr"
        :errorText="formErrors.tgl_nr?'failed':''" @input="values.tgl_nr" type="date" :hints="formErrors.tgl_nr" :check="false"
        label="Tanggal Nota Rampung" placeholder="Pilih Tanggal Nota Rampung" />
    </div>

    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.catatan"
        :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
        :check="false" type="textarea" label="Catatan" placeholder="Catatan" :required="true" />
    </div>

    <!-- Buat filedUpload Foto disini  -->
    <div>
      <FieldUpload class="w-full !mt-3" :bind="{ readonly: !actionText }"
        :reducerDisplay="(val)=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
      url: `${store.server.url_backend}/operation/t_nota_rampung/upload`,
      headers: {Authorization: `${store.user.token_type} ${store.user.token}`},
      params: { field: 'foto_scn' },
      onsuccess: function(response){
        // Call the preview function after a successful upload
        previewImage(response.data.filePath);
        return response;
      },
      onerror:(error)=>{
        $log(error);
      }
      
    }" accept="image/*" :value="values.foto_scn" @input="(v)=>values.foto_scn=v" :maxSize="25"
        :hints="formErrors.foto_scn" placeholder="Upload Foto Scan" fa-icon="upload" :check="false" />

      <div v-if="previewSrc" class="mt-4">
        <img :src="previewSrc" alt="Foto Preview" class="w-full h-auto rounded" />
      </div>
    </div>

    <div class="hidden">
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.grand_total"
        :errorText="formErrors.grand_total?'failed':''" @input="v=>values.grand_total=v" :hints="formErrors.grand_total"
        :check="false" label="Grand Total" placeholder="Grand Total" />
    </div>



    <div>
      <FieldSelect :bind="{ disabled: true, clearable:false }" class="w-full !mt-3" :value="values.status"
        @input="v=>values.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status"
        valueField="key" displayField="key" :options="[
          {'key' : 'DRAFT'}, 
          {'key' : 'POSTED'}]" placeholder="Status" label="Status" :check="false" />
    </div>
    <!-- END COLUMN -->

  </div>
  <div class="p-4"> 
    <button
        class="bg-yellow-600 text-white font-semibold hover:bg-yellow-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click=hitung
      >
        <icon fa="sack-dollar" />
        Calculate
      </button>
  </div>

    <div class="p-4 font-semibold text-xl"> 
     <span>Total = {{ formatCurrency(values.grand_total) }}</span>
  </div>
  <hr>


  <!-- Detail Nota Rampung -->
  <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
      <h1 class="text-3xl"> Nota Rampung Detail </h1>
      <br>
      <ButtonMultiSelect title="Add to list" @add="onDetailAdd" :api="{
              url: `${store.server.url_backend}/operation/t_buku_order/${values.t_buku_order_id?? 0}/t_buku_order_d_npwp`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: { 
                join: true,
                simplest: true,
                notin: detailArr.length>0?`this.id:${detailArr.map(dt=>dt.id).join(',')}`:null
               },
              onsuccess:(res)=>{
                $log(res)
                res.data = res.data.map((dt)=>({
                ...dt, is_active: dt.is_active ? 1 : 0,
                }))
                return res;
              }
            }" :columns="[{
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
              field: 'no_prefix',
              headerName:  'No.Prefix',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'no_suffix',
              headerName:  'No.Suffix',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'ukuran.deskripsi',
              headerName:  'Ukuran Kontainer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'jenis.deskripsi',
              headerName:  'Jenis Kontainer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            
           
            ]">
        <div
          class="bg-blue-600 text-sm text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
          <icon fa="plus" size="sm mr-0.5" /> Add to list
        </div>
      </ButtonMultiSelect>

      <div class="mt-4" style="overflow-x: auto; border: 1px solid #CACACA;">
        <table class="w-[350%] table-auto border border-[#CACACA]">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[1%] border bg-[#f8f8f8] border-[#CACACA]">
                No.
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                No. Container
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Kontainer
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                LOLO
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                M2
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                M3
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                M4
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                M5
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                OW
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                PLG + MON
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                GE
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                CANC.DOC
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                STRTP/STUFF
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                BATAL MUAT PINDAH
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                CLOSING CONTAINER
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Catatan
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Action
              </td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length > 0">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>

              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{readonly: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.no_kontainer"
                  @input="v=>item.no_kontainer=v" :errorText="formErrors.no_kontainer?'failed':''"
                  :hints="formErrors.no_kontainer" placeholder="No Kontainer" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{readonly: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.spek_kont"
                  @input="v=>item.spek_kont=v" :errorText="formErrors.spek_kont?'failed':''"
                  :hints="formErrors.spek_kont" placeholder="Spesifikasi Kontainer" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.lolo"
                  :errorText="formErrors.lolo?'failed':''" @input="v=>item.lolo=v" :hints="formErrors.lolo"
                  :check="false" label="" placeholder="Lolo" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.m2"
                  :errorText="formErrors.m2?'failed':''" @input="v=>item.m2=v" :hints="formErrors.m2" :check="false"
                  label="" placeholder="M2" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.m3"
                  :errorText="formErrors.m3?'failed':''" @input="v=>item.m3=v" :hints="formErrors.m3" :check="false"
                  label="" placeholder="M3" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.m4"
                  :errorText="formErrors.m4?'failed':''" @input="v=>item.m4=v" :hints="formErrors.m4" :check="false"
                  label="" placeholder="M4" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.m5"
                  :errorText="formErrors.m5?'failed':''" @input="v=>item.m5=v" :hints="formErrors.m5" :check="false"
                  label="" placeholder="M5" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.ow"
                  :errorText="formErrors.ow?'failed':''" @input="v=>item.ow=v" :hints="formErrors.ow" :check="false"
                  label="" placeholder="OW" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.plg_mon"
                  :errorText="formErrors.plg_mon?'failed':''" @input="v=>item.plg_mon=v" :hints="formErrors.plg_mon"
                  :check="false" label="" placeholder="PLG / MON" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.ge"
                  :errorText="formErrors.ge?'failed':''" @input="v=>item.ge=v" :hints="formErrors.ge" :check="false"
                  label="" placeholder="GE" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.canc_doc"
                  :errorText="formErrors.canc_doc?'failed':''" @input="v=>item.canc_doc=v" :hints="formErrors.canc_doc"
                  :check="false" label="" placeholder="CANC.DOC" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.strp_stuf"
                  :errorText="formErrors.strp_stuf?'failed':''" @input="v=>item.strp_stuf=v"
                  :hints="formErrors.strp_stuf" :check="false" label="" placeholder="STRP / STUF" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.batal_muat"
                  :errorText="formErrors.batal_muat?'failed':''" @input="v=>item.batal_muat=v"
                  :hints="formErrors.batal_muat" :check="false" label="" placeholder="Batal Muat Pindah" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="item.closing_container"
                  :errorText="formErrors.closing_container?'failed':''" @input="v=>item.closing_container=v"
                  :hints="formErrors.closing_container" :check="false" label="" placeholder="Batal Muat Pindah" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{readonly: !actionText, clearable:false }" class="w-full py-2 !mt-0"
                  :value="item.catatan" @input="v=>item.catatan=v" :errorText="formErrors.catatan?'failed':''"
                  :hints="formErrors.catatan" placeholder="Catatan" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div class="flex justify-center">
                  <button type="button" @click="removeDetail(values)" :disabled="!actionText">
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
          </tbody>
        </table>
      </div>

    </div>
  </div>


  <div class="flex flex-row items-center justify-end space-x-2 p-2 bg-white">
    <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click=onSave
      >
        <icon fa="save" />
        Simpan
      </button>
    <button
        class="bg-rose-600 text-white font-semibold hover:bg-rose-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click=onSaveAndPost

      >
        <icon fa="location-arrow" />
        Simpan dan Post Data
      </button>
  </div>

</div>
@endverbatim
@endif