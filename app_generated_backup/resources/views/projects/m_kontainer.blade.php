@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-blue-500">
  <div class="flex flex-col">
    <span class="text-center font-semibold text-2xl">
    KONTAINER
  </span>
    <!-- JENIS KONTAINER -->
    <div class="grid grid-cols-3 place-content-center place-items-center w-full h-[400px] items-center">
      <button
      class="bg-blue-500 hover:bg-blue-600 shadow-xl p-4 h-[250px] w-[250px] rounded-2xl transition-transform duration-300 transform hover:-translate-y-0.5"
      @click="go('JENIS KONTAINER')">
      <div class="flex justify-center">
        <svg width="150" height="150" viewBox="0 0 61 61" fill="none" xmlns="http://www.w3.org/2000/svg">
<circle cx="30.5" cy="30.5" r="30.5" fill="white"/>
<g clip-path="url(#clip0_2005_74937)">
<path d="M48.5716 17.1426H11.4287M48.5716 42.8569H11.4287M21.4287 24.2854V35.714M30.0001 24.2854V35.714M38.5716 24.2854V35.714M46.4287 42.8569V17.1426H13.5716V42.8569H46.4287Z" stroke="#1E3C78" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
</g>
<defs>
<clipPath id="clip0_2005_74937">
<rect width="40" height="40" fill="white" transform="translate(10 10)"/>
</clipPath>
</defs>
</svg>


      </div>
      <p class="p-2 text-xl text-white font-semibold pt-5"> 
        Jenis Kontainer
      </p>
    </button>
      <!-- TIPE KONTAINER -->
      <button
      class="bg-blue-500 hover:bg-blue-600 transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl p-4 h-[250px] w-[250px] rounded-2xl"
      @click="go('TIPE KONTAINER')">
      <div class="flex justify-center">
        <svg width="150" height="150" viewBox="0 0 61 61" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="30.5" cy="30.5" r="30.5" fill="white"/>
          <g clip-path="url(#clip0_2005_74930)">
            <path 
              fill-rule="evenodd" 
              clip-rule="evenodd" 
              d="M12.4286 16.7139C12.0497 16.7139 11.6863 16.8644 11.4184 17.1323C11.1505 17.4002 11 17.7636 11 18.1424C11 18.5213 11.1505 18.8847 11.4184 19.1526C11.6863 19.4205 12.0497 19.571 12.4286 19.571H13.1429V42.4282H12.4286C12.0497 42.4282 11.6863 42.5787 11.4184 42.8466C11.1505 43.1145 11 43.4778 11 43.8567C11 44.2356 11.1505 44.599 11.4184 44.8669C11.6863 45.1348 12.0497 45.2853 12.4286 45.2853H49.5714C49.9503 45.2853 50.3137 45.1348 50.5816 44.8669C50.8495 44.599 51 44.2356 51 43.8567C51 43.4778 50.8495 43.1145 50.5816 42.8466C50.3137 42.5787 49.9503 42.4282 49.5714 42.4282H48.8571V19.571H49.5714C49.9503 19.571 50.3137 19.4205 50.5816 19.1526C50.8495 18.8847 51 18.5213 51 18.1424C51 17.7636 50.8495 17.4002 50.5816 17.1323C50.3137 16.8644 49.9503 16.7139 49.5714 16.7139H12.4286ZM24.2143 25.2853C24.2143 24.8117 24.0261 24.3575 23.6913 24.0226C23.3564 23.6877 22.9022 23.4996 22.4286 23.4996C21.955 23.4996 21.5008 23.6877 21.1659 24.0226C20.831 24.3575 20.6429 24.8117 20.6429 25.2853V36.7139C20.6429 37.1875 20.831 37.6417 21.1659 37.9766C21.5008 38.3114 21.955 38.4996 22.4286 38.4996C22.9022 38.4996 23.3564 38.3114 23.6913 37.9766C24.0261 37.6417 24.2143 37.1875 24.2143 36.7139V25.2853ZM31 23.4996C31.9857 23.4996 32.7857 24.2996 32.7857 25.2853V36.7139C32.7857 37.1875 32.5976 37.6417 32.2627 37.9766C31.9278 38.3114 31.4736 38.4996 31 38.4996C30.5264 38.4996 30.0722 38.3114 29.7373 37.9766C29.4024 37.6417 29.2143 37.1875 29.2143 36.7139V25.2853C29.2143 24.2996 30.0143 23.4996 31 23.4996ZM41.3571 25.2853C41.3571 24.8117 41.169 24.3575 40.8341 24.0226C40.4992 23.6877 40.045 23.4996 39.5714 23.4996C39.0978 23.4996 38.6436 23.6877 38.3087 24.0226C37.9739 24.3575 37.7857 24.8117 37.7857 25.2853V36.7139C37.7857 37.1875 37.9739 37.6417 38.3087 37.9766C38.6436 38.3114 39.0978 38.4996 39.5714 38.4996C40.045 38.4996 40.4992 38.3114 40.8341 37.9766C41.169 37.6417 41.3571 37.1875 41.3571 36.7139V25.2853Z" 
              fill="#1e3c78"/>
          </g>
          <defs>
            <clipPath id="clip0_2005_74930">
              <rect width="40" height="40" fill="white" transform="translate(11 11)"/>
            </clipPath>
          </defs>
        </svg>
      </div>
      <p class="p-2 text-xl text-white font-semibold pt-5">
        Tipe Kontainer
      </p>
    </button>
      <!-- UKURAN KONTAINER -->
      <button
      class="bg-blue-500 hover:bg-blue-600 transition-transform duration-300 transform hover:-translate-y-0.5 shadow-xl p-4 h-[250px] w-[250px] rounded-2xl"
      @click="go('UKURAN KONTAINER')">
      <div class="flex justify-center">
        <svg width="150" height="150" viewBox="0 0 61 61" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="30.5" cy="30.5" r="30.5" fill="white"/>
          <g clip-path="url(#clip0_2005_74927)">
            <path 
              d="M5 39.2225V23H7.48625V30.4638H9.94875V23H12.54V25.4875H14.975V23H17.49V27.975H19.9775V23H22.5162V25.435H24.9525V23H27.5437V27.975H29.9787V23H32.5187V30.4638H34.9012V23H37.52V25.435H39.9825V23H42.5487V27.975H44.9062V23H47.525V25.435H49.9337V23H52.5V27.975H54.9625V23H57.5025V39.2238L5 39.2225Z" 
              fill="#1e3c78"/>
          </g>
          <defs>
            <clipPath id="clip0_2005_74927">
              <rect width="40" height="40" fill="white" transform="translate(11 11)"/>
            </clipPath>
          </defs>
        </svg>
      </div>
      <p class="p-2 text-xl text-white font-semibold pt-5">
        Ukuran Kontainer
      </p>
    </button>
    </div>
  </div>

  <div class="w-full flex justify-center">
    <span class="text-2xl font-semibold">
    LIST KONTAINER
  </span>
  </div>
  <div>
  </div>
  <TableApi ref="apiTable" :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px] pt-2 !px-4 !pb-8">
    <template #header>
      <!-- FILTER TABLE -->
      <div class="space-x-2 w-full">
        <div class="flex justify-center gap-2 my-4">
          <button
          :class="{
            'bg-green-600 hover:bg-green-500 duration-300 border-4 border-dotted border-lime-500 ': pilih === 'TIPE KONTAINER',
            'bg-green-500 hover:bg-green-600 duration-300': pilih !== 'TIPE KONTAINER'
          }"
          class="rounded-md text-white font-semibold w-[75px]"
          @click="filterByGroup('TIPE KONTAINER')"
        >
          TIPE
        </button>
          <button
          :class="{
            'bg-blue-600 hover:bg-blue-500 duration-300 border-4 border-dotted border-sky-500 ': pilih === 'JENIS KONTAINER',
            'bg-blue-500 hover:bg-blue-600 duration-300': pilih !== 'JENIS KONTAINER'
          }"
          class="rounded-md text-white font-semibold w-[75px]"
          @click="filterByGroup('JENIS KONTAINER')"
        >
          JENIS
        </button>
          <button
          :class="{
            'bg-yellow-600 hover:bg-yellow-500 duration-300 border-4 border-dotted border-yellow-300': pilih === 'UKURAN KONTAINER',
            'bg-yellow-500 hover:bg-yellow-600 duration-300': pilih !== 'UKURAN KONTAINER'
          }"
          class="rounded-md text-white font-semibold w-[75px]"
          @click="filterByGroup('UKURAN KONTAINER')"
        >
          UKURAN
        </button>
        </div>
      </div>
    </template>
  </TableApi>

</div>

@else

<!-- CONTENT -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-blue-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-white" title="Kembali" @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Kontainer</h1>
        <p class="text-gray-100">Untuk mengatur informasi Kontainer pada sistem</p>
      </div>
    </div>
  </div>
  <!-- TABLE DETAIL -->
  <div class="col-span-8 md:col-span-12 p-4">

    <div class="flex justify-start items-center space-x-5">
      <!-- Add Container Button -->
      <button
    v-show="actionText"
    @click="addDetail"
    type="button"
    class="bg-[#005FBF] w-[250px] hover:bg-[#0055ab] text-white py-[8px] px-[14px] flex items-center justify-center space-x-2 rounded-md shadow-sm ml-1"
  >
    <icon fa="plus" />
    <span>Tambah Kontainer</span>
  </button>

      <!-- Search Input -->
      <div>
        <input
      v-model="searchTerm"
      type="text"
      placeholder="Search..."
      class="p-2 border border-[#CACACA] rounded"
    />
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-row items-center justify-end space-x-2 p-2 w-full">
        <i class="text-gray-500 text-[12px]">
      Harap Perhatikan Data sebelum Menyimpan Data!
    </i>
        <button
      class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
      v-show="actionText"
      @click="onBack"
    >
      <icon fa="times" />
      Back
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
    <div class="mx-1 mt-4">
      <table class="w-full overflow-x-auto table-auto border border-[#CACACA]">
        <thead>
          <tr class="border">
            <!-- No. Column -->
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
              No.
            </td>

            <!-- KODE Column -->
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] hidden">
              KODE
            </td>

            <!-- GROUP Column -->
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[20%] bg-[#f8f8f8] border-[#CACACA]">
              GROUP
            </td>

            <!-- DESKRIPSI Column with Sorting Button -->
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize text-center border bg-[#f8f8f8] border-[#CACACA]">
              <button
            @click="toggleSort"
            class="w-full p-4 flex justify-center items-center hover:bg-gray-300 duration-300 font-semibold text-[#8F8F8F] text-[14px] text-capitalize"
          >
            <div class="flex">
              <span>DESKRIPSI</span>
              <span v-if="sortOrder === 1">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5 0L0 5H10L5 0Z" fill="#8F8F8F"/>
                </svg>
              </span>
              <span v-if="sortOrder === -1">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5 10L10 5H0L5 10Z" fill="#8F8F8F"/>
                </svg>
              </span>
            </div>
          </button>
            </td>

            <!-- Status Column -->
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA] hidden">
              Status
            </td>

            <!-- Aksi Column -->
            <td
              class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
              Aksi
            </td>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(item, i) in filteredDetails" :key="item.id" class="border-t" v-if="detailArr.length > 0"
            v-show="item.is_active">
            <!-- No. Cell -->
            <td class="p-2 text-center border border-[#CACACA]">
              {{ i + 1 }}.
            </td>

            <!-- KODE Cell -->
            <td class="p-2 border border-[#CACACA] hidden">
              <FieldX class="!mt-0 w-full" :bind="{ readonly: true }" :value="item.kode"
                :errorText="formErrors.kode ? 'failed' : ''" @input="v => item.kode = v" :hints="formErrors.kode"
                placeholder="kode" label="" :check="false" />
            </td>

            <!-- GROUP Cell -->
            <td class="p-2 border border-[#CACACA]">
              <FieldSelect class="!mt-0 w-full" :bind="{ disabled: true, clearable: false }" :value="item.group"
                :errorText="formErrors.group ? 'failed' : ''" @input="v => item.group = v" :hints="formErrors.group"
                placeholder="Group" :check="false"
                :options="[{'key': 'TIPE KONTAINER'}, {'key': 'JENIS KONTAINER'}, {'key': 'UKURAN KONTAINER'}]"
                valueField="key" label="" displayField="key" />
            </td>

            <!-- DESKRIPSI Cell -->
            <td class="p-2 border border-[#CACACA]">
              <FieldX class="!mt-0 w-full" :bind="{ readonly: !actionText }" :value="item.deskripsi"
                :errorText="formErrors.deskripsi ? 'failed' : ''" @input="v => item.deskripsi = v"
                :hints="formErrors.deskripsi" placeholder="Tuliskan Deskripsi" label="" :check="false" />
            </td>

            <!-- Status Cell -->
            <td class="p-2 mt-2 flex justify-center hidden">
              <div class="grid grid-cols-3 place-items-center w-[50%] justify-center space-x-5">
                <div class="flex">
                  <i class="text-red-500">Tidak Aktif</i>
                </div>
                <div class="flex">
                  <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                role="switch"
                id="is_active_for_click"
                :disabled="true"
                v-model="item.is_active"
              />
                </div>
                <div class="flex-auto">
                  <i class="text-green-500">Aktif</i>
                </div>
              </div>
            </td>

            <!-- Aksi Cell -->
            <td class="p-2 border border-[#CACACA]">
              <div class="flex justify-center">
                <button
              type="button"
              @click="removeDetail(item)"
              :disabled="!actionText"
              class="text-red-500 hover:text-red-700 duration-300"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-slash-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="M11.354 4.646a.5.5 0 0 0-.708 0l-6 6a.5.5 0 0 0 .708.708l6-6a.5.5 0 0 0 0-.708"/>
              </svg>
            </button>
              </div>
            </td>
          </tr>

          <!-- No Data Row -->
          <tr v-else class="text-center">
            <td colspan="7" class="py-[20px]">
              Tidak Ada Kontainer
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>





  @endverbatim
  @endif