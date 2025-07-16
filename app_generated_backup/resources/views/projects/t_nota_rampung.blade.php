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
      <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.no_nota_rampung"
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
          values.pelabuhan = data.nama_pelabuhan;
          
        } else {
          values.customer = ''; 
          values.pelabuhan = '';
          
        }
          return response;
        }" :api="{
              url: `${store.server.url_backend}/operation/t_buku_order`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
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
            }
            ]" />
    </div>
    <div>
      <FieldX :bind="{ disabled: true, readonly: true }" class="w-full !mt-3" :value="values.tanggal"
        :errorText="formErrors.tanggal?'failed':''" @input="updateDate" :hints="formErrors.tanggal" :check="false"
        label="Tanggal" placeholder="Pilih Tanggal" />
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
      <fieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full !mt-3" :value="values.container1"
        @input="v=>values.container1=v" :errorText="formErrors.container1?'failed':''" :hints="formErrors.container1"
        valueField="id" displayField="deskripsi" 
        @update:valueFull="e=>{
          if(e){
            values.tipe1 = e.tipe
          }
        }"
        :api="{
        url: `${store.server.url_backend}/operation/t_buku_order/${values.t_buku_order_id?? 0}/t_buku_order_d_npwp`, //Ambil dari param buku order
        headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
        params:{
          selectfield: 'this.id,jenis.deskripsi,tipe'
        }
        
        }" placeholder="Pilih Container 1" label="Container 1" :check="false" :columns="[{      
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'jenis',
              headerName:  'Jenis Kontainer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
    </div>
    <div>
      <FieldSelect :bind="{ disabled: !actionText, clearable:true }" class="w-full !mt-3" :value="values.container2"
        @input="v=>values.container2=v" :errorText="formErrors.container2?'failed':''" :hints="formErrors.container2"
        valueField="id" displayField="deskripsi" 
        @update:valueFull="e=>{
          if(e){
            values.tipe2 = e.tipe
          }
        }"
        :api="{
              url: `${store.server.url_backend}/operation/t_buku_order/${values.t_buku_order_id?? 0}/t_buku_order_d_npwp`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
            selectfield: 'this.id,jenis.deskripsi,tipe'
        },
        onsuccess: (e) => {
          $log(e)
          return e;
        }
        }" placeholder="Pilih Container 2" label="Container 2" :check="false" :columns="[{      
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'deskripsi',
              headerName:  'Jenis Kontainer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
    </div>

    <div>
      <FieldSelect :bind="{ disabled: true, clearable:false}" class="w-full !mt-3" :value="values.tipe1" @input="v=>{
            if(v){
              values.tipe1=v
            }else{
              values.tipe1=null
            }
          }" :errorText="formErrors.tipe1?'failed':''" :hints="formErrors.tipe1" valueField="id"
        displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
               params: {
          //tambahkan Params where ke group jenis kontainer , 
          where:
          `this.group = 'TIPE KONTAINER' `,
          searchfield: 'this.deskripsi'
        },
        onsuccess: (e) => {
          $log(e)
          return e;
        }
        }" placeholder="Pilih Tipe Container 1" label="Tipe Container 1" :check="false" :columns="[{      
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'deskripsi',
              headerName:  'Tipe Kontainer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
    </div>

    <div>
      <FieldSelect :bind="{ disabled: true, clearable:false }" class="w-full !mt-3" :value="values.tipe2" @input="v=>{
            if(v){
              values.tipe2=v
            }else{
              values.tipe2=null
            }
          }" :errorText="formErrors.tipe2?'failed':''" :hints="formErrors.tipe2" valueField="id"
        displayField="deskripsi" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
          //tambahkan Params where ke group jenis kontainer , 
          where:
          `this.group = 'TIPE KONTAINER' `,
          searchfield: 'this.deskripsi'
        },
        onsuccess: (e) => {
          $log(e)
          return e;
        }
        }" placeholder="Pilih Tipe Container 2" label="Tipe Container 2" :check="false" :columns="[{      
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'deskripsi',
              headerName:  'Tipe Kontainer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            }
            ]" />
    </div>

    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.lolo"
        :errorText="formErrors.lolo?'failed':''" @input="v=>values.lolo=v" :hints="formErrors.lolo" :check="false"
        label="Lolo" placeholder="Lolo" />
    </div>

    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.m1"
        :errorText="formErrors.m1?'failed':''" @input="v=>values.m1=v" :hints="formErrors.m1" :check="false" label="M1"
        placeholder="M1" />
    </div>

    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.m2"
        :errorText="formErrors.m2?'failed':''" @input="v=>values.m2=v" :hints="formErrors.m2" :check="false" label="M2"
        placeholder="M2" />
    </div>

    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.m4"
        :errorText="formErrors.m4?'failed':''" @input="v=>values.m4=v" :hints="formErrors.m4" :check="false" label="M4"
        placeholder="M4" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.ow"
        :errorText="formErrors.ow?'failed':''" @input="v=>values.ow=v" :hints="formErrors.ow" :check="false" label="OW"
        placeholder="OW" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.plg_mon"
        :errorText="formErrors.plg_mon?'failed':''" @input="v=>values.plg_mon=v" :hints="formErrors.plg_mon"
        :check="false" label="PLG + MON" placeholder="PLG + MON" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.m3"
        :errorText="formErrors.m3?'failed':''" @input="v=>values.m3=v" :hints="formErrors.m3" :check="false" label="M3"
        placeholder="M3" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.ge"
        :errorText="formErrors.ge?'failed':''" @input="v=>values.ge=v" :hints="formErrors.ge" :check="false" label="GE"
        placeholder="GE" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.m5"
        :errorText="formErrors.m5?'failed':''" @input="v=>values.m5=v" :hints="formErrors.m5" :check="false" label="M5"
        placeholder="M5" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.canc_doc"
        :errorText="formErrors.canc_doc?'failed':''" @input="v=>values.canc_doc=v" :hints="formErrors.canc_doc"
        :check="false" label="Canc. Doc" placeholder="Canc. Doc" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.strp_stuf"
        :errorText="formErrors.strp_stuf?'failed':''" @input="v=>values.strp_stuf=v" :hints="formErrors.strp_stuf"
        :check="false" label="Strtp/Stuf" placeholder="Strtp/Stuf" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.batal_muat"
        :errorText="formErrors.batal_muat?'failed':''" @input="v=>values.batal_muat=v" :hints="formErrors.batal_muat"
        :check="false" label="Batal Muat/Pindah Kapal" placeholder="Batal Muat/Pindah Kapal" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.closing_container"
        :errorText="formErrors.closing_container?'failed':''" @input="v=>values.closing_container=v"
        :hints="formErrors.closing_container" :check="false" label="Closing Container"
        placeholder="Closing Container" />
    </div>
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.vgm"
        :errorText="formErrors.vgm?'failed':''" @input="v=>values.vgm=v" :hints="formErrors.vgm" :check="false"
        label="VGM" placeholder="VGM" />
    </div>
    
    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full !mt-3" :value="values.lolo_non_sp"
        :errorText="formErrors.lolo_non_sp?'failed':''" @input="v=>values.lolo_non_sp=v" :hints="formErrors.lolo_non_sp"
        :check="false" label="Biaya Lain-Lain" placeholder="Biaya Lain-Lain" />
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
      
    }" accept="image/*" :value="values.foto_scn" @input="(v)=>values.foto_scn=v" :maxSize="25" :hints="formErrors.foto_scn"
            placeholder="" fa-icon="upload" :check="false" />

          <div v-if="previewSrc" class="mt-4">
            <img :src="previewSrc" alt="Foto Preview" class="w-full h-auto rounded" />
          </div>
  </div>


    <div>
      <FieldSelect :bind="{ disabled: true, clearable:false }" class="w-full !mt-3" :value="values.status"
        @input="v=>values.status=v" :errorText="formErrors.status?'failed':''" :hints="formErrors.status"
        valueField="key" displayField="key" :options="[
          {'key' : 'DRAFT'}, 
          {'key' : 'POSTED'}]" placeholder="Status" label="Status" :check="false" />
    </div>
    <!-- END COLUMN --> 
    <!-- ACTION BUTTON START -->
  </div>
  <hr>

    <!-- Detail Nota Rampung -->
    <div class="<md:col-span-1 col-span-3 p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 " >
      <div class="overflow-scroll lg:overflow-visible <md:col-span-1 col-span-3">
        <h1 class="text-3xl"> Nota Rampung Detail </h1>
        <br>
        <ButtonMultiSelect
            title="Add to list"
            @add="onDetailAdd"
            :api="{
              url: `${store.server.url_backend}/operation/t_buku_order/${values.t_buku_order_id?? 0}/t_buku_order_d_npwp`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: { 
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
              field: 'ukuran',
              headerName:  'Ukuran Kontainer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'tipe.deskripsi',
              headerName:  'Tipe Kontainer',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            {
              flex: 1,
              field: 'sektor.deskripsi',
              headerName:  'Sektor',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-start']
            },
            
           
            ]">
            <div class="bg-blue-600 text-sm text-white font-semibold hover:bg-blue-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded p-1.5">
              <icon fa="plus" size="sm mr-0.5"/> Add to list
            </div>
          </ButtonMultiSelect>
        <table class="w-full overflow-x-auto table-auto border border-[#CACACA] mt-4">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize p-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                No.
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                No. Container
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Ukuran Kontainer
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Tipe Kontainer
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Jenis Kontainer
              </td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                Sektor
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
                <FieldX :bind="{readonly: true, clearable:false }" class="w-full py-2 !mt-0"
                  :value="item.no_kontainer" @input="v=>item.no_kontainer=v" :errorText="formErrors.no_kontainer?'failed':''"
                  :hints="formErrors.no_kontainer" placeholder="No Kontainer" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{disabled: true, clearable:false}" class="w-full py-2 !mt-0" :value="item.ukuran"
                  @input="v=>item.ukuran=v" 
                  valueField="id" displayField="deskripsi" :api="{          
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            transform:false,
                            join:false,
                            where:`this.is_active=true and this.group='UKURAN KONTAINER'`
                          }
                      }" placeholder="Ukuran Kontainer" label="" :check="false" />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.tipe"
                  @input="v=>item.tipe=v"
                  valueField="id" displayField="deskripsi" :api="{          
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            transform:false,
                            join:false,
                            where:`this.is_active=true and this.group='TIPE KONTAINER'`
                          }
                      }" placeholder="Pilih Tipe Kotainer" label="" :check="false" />
              </td>
             

             <!-- KASUS READ, EDIT DATA DI KOLOM JENIS, MUNCUL DATANYA, TAPI DI CREATE DATA KOSONG  -->

              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.jenis"
                  @input="v=>item.jenis=v" 
                  valueField="id" displayField="deskripsi" :api="{          
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            transform:false,
                            join:false,
                            where:`this.is_active=true and this.group='JENIS KONTAINER'`
                          }
                      }" placeholder="Pilih Jenis" label="" :check="false" />
              </td>


              <!-- KASUS CREATE DATA DI KOLOM SEKTOR, MUNCUL DATANYA, TAPI KETIKA DI READ, EDIT DATA, KOLOM KOSONG -->
              
              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: true, clearable:false }" class="w-full py-2 !mt-0" :value="item.sektor"
                  @input="v=>item.sektor=v" 
                  valueField="id" displayField="deskripsi" :api="{          
                          url: `${store.server.url_backend}/operation/m_general`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                            simplest:true,
                            transform:false,
                            join:false,
                            where:`this.is_active=true and this.group='SEKTOR'`
                          }
                      }" placeholder="Pilih Sektor" label="" :check="false" />
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

  

  
  <div class="flex flex-row items-center justify-end space-x-2 p-2 bg-white">
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