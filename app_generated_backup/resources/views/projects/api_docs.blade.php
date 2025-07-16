@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <button :disabled="isRequesting" @click="getData()" class="p-2 bg-blue-500 text-white rounded-md z-10 fixed right-10 top-15"><Icon fa="refresh"/> {{ isRequesting ? 'Memuat' : 'Segarkan' }}</button>
      <div class="flex justify-between items-center">
        <h1 class="font-bold text-[16pt]">Dokumentasi Api</h1>
      </div>
      <hr class="my-4">
        <div 
          v-for="(item, idx) in dataDocs.items" :key="idx"
          class="grid grid-cols-1  rounded-md shadow-md bg-amber-100 mb-4">
          <div @click="view(idx)" class="p-2 bg-amber-300 cursor-pointer">
            <h4 class="font-semibold text-[12pt]">{{idx+1}}. {{item.type}}-{{item.title}}</h4>
            <p class="text-gray-700 mt-2">{{item.desc}}</p>
          </div>
          <div class="p-4" :class="item.open===true? 'visible' : 'hidden'">
            <div class="flex gap-x-2 items-end">
              <FieldX 
                :bind="{ readonly: false }" 
                class="w-full"
                :value="item.endpoint" 
                @input="v=>dataDocs.items[idx]['endpoint']=v"
                :check="false"
                label="Endpoint"
              />
              <Icon @click="copyToClipboard(item.endpoint)" fa="copy" class="text-gray-400 cursor-pointer text-[18px] mb-2"/>
              <button :if="item.type=='GET'" :disabled="isRequesting" @click="check(idx)" class="p-2 bg-green-500 text-white rounded-md w-[120px]"><Icon fa="upload"/> {{ isRequesting ? 'Memuat' : 'Coba' }}</button>
            </div>
            <div class="flex gap-x-2 items-center">
              <pre 
                class="text-sm mt-2 h-[150px] bg-gray-800 overflow-auto text-red-400 w-full p-2 rounded shadow-md"
                contenteditable="true"
                @input="dataDocs.items[idx]['body']"
              >{{item.body}}</pre>
              <Icon @click="copyToClipboard(item.body)" fa="copy" class="text-gray-400 cursor-pointer text-[18px] mt-2"/>
            </div>
            <div v-if="item.trial && item.trial != null" class="flex gap-x-2 items-center">
              <pre class="text-sm mt-2 h-[400px] bg-gray-800 overflow-auto text-yellow-400 w-full p-2 rounded shadow-md"
                contenteditable="true"
                @input="dataDocs.items[idx]['trial']"
              >{{item.trial}}</pre>
              <div class="flex flex-col">
                <Icon @click="copyToClipboard(item.trial)" fa="copy" class="text-gray-400 cursor-pointer text-[18px] mt-2"/>
                <Icon @click="deleteTrial(idx)" fa="times" class="text-red-500 cursor-pointer text-[18px] mt-2"/>
              </div>
            </div>
            <p class="text-gray-700 mt-2">Dokumentasi Lengkap : 
              <a class="!text-blue underline" :href="store.server.url_backend+'/docs/frontend#read_'+item.model" target="_blank">disini</a>
            </p>
          
          </div>
      </div>
    </div>
  </div>
</div>
@endverbatim