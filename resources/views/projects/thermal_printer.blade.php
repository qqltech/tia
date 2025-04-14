@verbatim
<div class="bg-white p-4 flex flex-col gap-4 rounded">
  <h1 class="text-xl font-semibold">Thermal Printer</h1>
  <div class="flex flex-col gap-1 border border-gray-300 rounded p-4 py-3">
    <h2 class="text-lg font-medium">Instalasi</h2>
    <ul class="list-disc ml-5">
      <li>Install driver <a target="_blank" class="text-blue-700 hover:text-blue-500"
          href="http://www.cnfujun.com/d/33">thermal printer</a>.</li>
      <li>Setelah terinstall, buka aplikasi <b>POS-80</b>, Pilih <b>system</b> sesuai dengan sistem operasi (misalnya 64
        bit),
        dan pilih <b>POS-80</b> pada bagian <b>Select Printer</b>.</li>
      <li>Tekan <b>USB Port Check</b>, lalu jalankan <b>Begin Setup</b>.</li>
      <li>Buka <b>Printer & scanners</b> pada pengaturan, pilih <b>POS-80</b>.</li>
      <li>Masuk ke <b>Printer properties</b>, lalu buka tab <b>Sharing</b>. Aktifkan <b>Share this printer</b>.</li>
    </ul>
  </div>
  <div class="flex flex-col gap-1 border border-gray-300 rounded p-4 py-3">
    <h2 class="text-lg font-medium">Pengoperasian</h2>
    <ul class="list-disc ml-5">
      <li>Install dan jalankan aplikasi <a target="_blank" class="text-blue-700 hover:text-blue-500"
          href="https://drive.google.com/drive/folders/1v6aWINifLHmLAn8GK2bvl31lwdq53SNK?usp=drive_link">thermal-server</a> di local (win10-x64 recommendation).</li>
      <li>Buka <b>Pengaturan</b> di bawah ini, pastikan semua konfigurasi sudah sesuai.</li>
      <li>Printer siap digunakan.</li>
    </ul>
  </div>
  <div v-if="!isPublic" class="flex flex-wrap gap-2">
    <div>
      <button class="truncate p-2 rounded border border-gray-500 font-medium " :class="activeIndex === 0 ? 'bg-gray-500 text-white' : 'text-gray-500'" @click="setActiveIndex(0)">
        <icon fa="cog" />
        Pengaturan
      </button>
    </div>
    <div>
      <button class="truncate p-2 rounded border border-gray-500 font-medium " :class="activeIndex === 1 ? 'bg-gray-500 text-white' : 'text-gray-500'" @click="setActiveIndex(1)">
        <icon fa="file-text" />
        Dokumentasi
      </button>
    </div>
    <div>
      <button class="truncate p-2 rounded border border-gray-500 font-medium " :class="activeIndex === 2 ? 'bg-gray-500 text-white' : 'text-gray-500'" @click="setActiveIndex(2)">
        <icon fa="code" />
        Dynamic Custom
      </button>
    </div>
    <div>
      <button class="truncate p-2 rounded border border-gray-500 font-medium " :class="activeIndex === 3 ? 'bg-gray-500 text-white' : 'text-gray-500'" @click="setActiveIndex(3)">
        <icon fa="list" />
        Endpoint
      </button>
    </div>
  </div>
  <div v-if="activeIndex === 0" class="grid grid-cols-1 lg:grid-cols-2 gap-2 items-end border border-gray-300 rounded p-4">
    <div class="flex flex-col gap-2">
      <label class="font-medium">Nama Printer (Shared Name)</label>
      <FieldX class="w-full" :bind="{ readonly: false }" :value="thermal.interface" @input="v=>{thermal.interface=v}"
        placeholder="Nama Printer | Default : POS-80" label="" :check="false" />
    </div>
    <div class="flex flex-col gap-2">
      <label class="font-medium">Port Printer (Thermal Server)</label>
      <FieldX type="number" class="w-full" :bind="{ readonly: false }" :value="thermal.port" @input="v=>thermal.port=v"
        placeholder="Port Printer | Default : 9000" label="" :check="false" />
    </div>
    <div class="flex flex-col gap-2">
      <label class="font-medium">Endpoint</label>
      <FieldX class="w-full" :bind="{ readonly: false }" :value="thermal.url" @input="v=>thermal.url=v"
        placeholder="Endpoint | Contoh : /print/template" label="" :check="false" />
    </div>
    <div class="flex gap-2">
      <button class="bg-red-500 p-2 rounded text-white" @click="resetThermal">
        <icon fa="times" />
        Reset
      </button>
      <button class="bg-blue-500 p-2 rounded text-white" @click="saveLocalStorage">
        <icon fa="save" />
        Simpan
      </button>
    </div>
  </div>

  <div v-if="activeIndex === 1" class="flex flex-col gap-4 border border-gray-300 rounded p-4 pt-3">
    <div class="flex flex-col gap-2">
      <h2 class="font-semibold text-lg">Aturan Custom</h2>
      <ul class="list-disc ml-5">
        <li>Payload harus berupa object dengan key <b>data</b>.</li>
        <li>Data harus berisikan <b>array</b>, yang berisikan <b>object</b> dari parameter komponen (array of object).
        </li>
        <li>Object memiliki key utama yaitu <b>type</b> (required) dengan parameter lain-nya sesuai komponen-nya.</li>
        <li>Penerapan dilakukan pada file <b>javascript</b>. Developer silahkan cek menu SPK Angkutan sebagai referensi.
        </li>
        <li>Optimasi kedepan disarankan menggunakan bahasa pemograman yang lebih optimal seperti Python, Rust, atau Golang.
        </li>
        <li>Pengembangan lebih lanjut mengikuti pedoman <a href="https://www.npmjs.com/package/node-thermal-printer"
            target="_blank" class="text-blue-700 hover:text-blue-500">disini</a>.
        </li>
      </ul>
    </div>

    <div class="flex flex-col gap-2">
      <h2 class="font-semibold text-lg">Komponen</h2>
      <ol class="flex flex-col gap-3 list-decimal pl-4 text-lg w-full">

        <li>
          <div class="flex flex-col gap-1">
            <div class="font-medium text-lg">Print Text</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <table class="border-collapse border border-gray-300 text-sm">
                <thead>
                  <tr>
                    <th class="border border-gray-300 p-2">Params</th>
                    <th class="border border-gray-300 p-2">Type</th>
                    <th class="border border-gray-300 p-2">Value / Example</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-gray-300 p-2">type</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">print or println</td>
                  <tr>
                  <tr>
                    <td class="border border-gray-300 p-2">value</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">hello world!</td>
                  <tr>
                </tbody>
              </table>
              <textarea disabled class="min-h-[100px] min-h-[100px] border border-gray-300 bg-gray-50 text-sm p-2">
{
  "type": "println", // or print
  "value": "hello world!"
}</textarea>
            </div>
          </div>
        </li>
        
        <li>
          <div class="flex flex-col gap-1">
            <div class="font-medium text-lg">Paper Cut</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <table class="border-collapse border border-gray-300 text-sm">
                <thead>
                  <tr>
                    <th class="border border-gray-300 p-2">Params</th>
                    <th class="border border-gray-300 p-2">Type</th>
                    <th class="border border-gray-300 p-2">Value / Example</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-gray-300 p-2">type</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">cut or partialCut</td>
                  <tr>
                </tbody>
              </table>
              <textarea disabled class="min-h-[100px] border border-gray-300 bg-gray-50 text-sm p-2">
{
  "type": "cut" // or partialCut
}</textarea>
            </div>
          </div>
        </li>

        <li>
          <div class="flex flex-col gap-1">
            <div class="font-medium text-lg">Text Style</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <table class="border-collapse border border-gray-300 text-sm">
                <thead>
                  <tr>
                    <th class="border border-gray-300 p-2">Params</th>
                    <th class="border border-gray-300 p-2">Type</th>
                    <th class="border border-gray-300 p-2">Value / Example</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-gray-300 p-2">type</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">bold or underline</td>
                  <tr>
                </tbody>
              </table>
              <textarea disabled class="min-h-[100px] border border-gray-300 bg-gray-50 text-sm p-2">
{
  "type": "bold" // or underline
}</textarea>
            </div>
          </div>
        </li>

        <li>
          <div class="flex flex-col gap-1">
            <div class="font-medium text-lg">Draw Line</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <table class="border-collapse border border-gray-300 text-sm">
                <thead>
                  <tr>
                    <th class="border border-gray-300 p-2">Params</th>
                    <th class="border border-gray-300 p-2">Type</th>
                    <th class="border border-gray-300 p-2">Value / Example</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-gray-300 p-2">type</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">drawLine</td>
                  <tr>
                </tbody>
              </table>
              <textarea disabled class="min-h-[100px] border border-gray-300 bg-gray-50 text-sm p-2">
{
  "type": "drawLine"
}</textarea>
            </div>
          </div>
        </li>

        <li>
          <div class="flex flex-col gap-1">
            <div class="font-medium text-lg">Newline / Enter</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <table class="border-collapse border border-gray-300 text-sm">
                <thead>
                  <tr>
                    <th class="border border-gray-300 p-2">Params</th>
                    <th class="border border-gray-300 p-2">Type</th>
                    <th class="border border-gray-300 p-2">Value / Example</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-gray-300 p-2">type</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">newLine</td>
                  <tr>
                </tbody>
              </table>
              <textarea disabled class="min-h-[100px] border border-gray-300 bg-gray-50 text-sm p-2">
{
  "type": "newLine"
}</textarea>
            </div>
          </div>
        </li>

        <li>
          <div class="flex flex-col gap-1">
            <div class="font-medium text-lg">Text Aligment</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <table class="border-collapse border border-gray-300 text-sm">
                <thead>
                  <tr>
                    <th class="border border-gray-300 p-2">Params</th>
                    <th class="border border-gray-300 p-2">Type</th>
                    <th class="border border-gray-300 p-2">Value / Example</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-gray-300 p-2">type</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">alignCenter, alignLeft, or alignRight</td>
                  <tr>
                </tbody>
              </table>
              <textarea disabled class="min-h-[100px] border border-gray-300 bg-gray-50 text-sm p-2">
{
  "type": "alignCenter" // or alignLeft / alignRight
}</textarea>
            </div>
          </div>
        </li>

        <li>
          <div class="flex flex-col gap-1">
            <div class="font-medium text-lg">Text Strech</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <table class="border-collapse border border-gray-300 text-sm">
                <thead>
                  <tr>
                    <th class="border border-gray-300 p-2">Params</th>
                    <th class="border border-gray-300 p-2">Type</th>
                    <th class="border border-gray-300 p-2">Value / Example</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-gray-300 p-2">type</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">setTextNormal, setTextDoubleHeight, or setTextDoubleWidth</td>
                  <tr>
                </tbody>
              </table>
              <textarea disabled class="min-h-[100px] border border-gray-300 bg-gray-50 text-sm p-2">
{
  "type": "setTextNormal" // or setTextDoubleHeight / setTextDoubleWidth
}</textarea>
            </div>
          </div>
        </li>

        <li>
          <div class="flex flex-col gap-1">
            <div class="font-medium text-lg">Text Size</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <table class="border-collapse border border-gray-300 text-sm">
                <thead>
                  <tr>
                    <th class="border border-gray-300 p-2">Params</th>
                    <th class="border border-gray-300 p-2">Type</th>
                    <th class="border border-gray-300 p-2">Value / Example</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-gray-300 p-2">type</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">setTextSize</td>
                  <tr>
                  <tr>
                    <td class="border border-gray-300 p-2">height</td>
                    <td class="border border-gray-300 p-2">number</td>
                    <td class="border border-gray-300 p-2">1 (0 - 7)</td>
                  <tr>
                  <tr>
                    <td class="border border-gray-300 p-2">width</td>
                    <td class="border border-gray-300 p-2">number</td>
                    <td class="border border-gray-300 p-2">1 (0 - 7)</td>
                  <tr>
                </tbody>
              </table>
              <textarea disabled class="min-h-[100px] border border-gray-300 bg-gray-50 text-sm p-2">
{
  "type": "setTextSize",
  "height": 4,
  "width": 6
}</textarea>
            </div>
          </div>
        </li>

         <li>
          <div class="flex flex-col gap-1">
            <div class="font-medium text-lg">Table</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <table class="border-collapse border border-gray-300 text-sm">
                <thead>
                  <tr>
                    <th class="border border-gray-300 p-2">Params</th>
                    <th class="border border-gray-300 p-2">Type</th>
                    <th class="border border-gray-300 p-2">Value / Example</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-gray-300 p-2">type</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">table</td>
                  <tr>
                  <tr>
                    <td class="border border-gray-300 p-2">value</td>
                    <td class="border border-gray-300 p-2">array</td>
                    <td class="border border-gray-300 p-2">["Left", "Center", "Right"]</td>
                  <tr>
                </tbody>
              </table>
              <textarea disabled class="min-h-[100px] border border-gray-300 bg-gray-50 text-sm p-2">
{
  "type": "table",
  "value": ["Left", "Center", "Right"]
}</textarea>
            </div>
          </div>
        </li>

         <li>
          <div class="flex flex-col gap-1">
            <div class="font-medium text-lg">Custom Table</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <table class="border-collapse border border-gray-300 text-sm">
                <thead>
                  <tr>
                    <th class="border border-gray-300 p-2">Params</th>
                    <th class="border border-gray-300 p-2">Type</th>
                    <th class="border border-gray-300 p-2">Value / Example</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-gray-300 p-2">type</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">tableCustom</td>
                  <tr>
                  <tr>
                    <td class="border border-gray-300 p-2">value</td>
                    <td class="border border-gray-300 p-2">array of object</td>
                    <td class="border border-gray-300 p-2">[{ text:"Left", align:"LEFT", width:0.5 }, ...]</td>
                  <tr>
                </tbody>
              </table>
              <textarea disabled class="min-h-[100px] border border-gray-300 bg-gray-50 text-sm p-2">
{
  "type": "table",
  "value": [
    { text: "Left", align: "LEFT", width: 0.5 },
    { text: "Center", align: "CENTER", width: 0.25, bold: true },
    { text: "Right", align: "RIGHT", cols: 8 }
  ]
}</textarea>
            </div>
          </div>
        </li>

         <li>
          <div class="flex flex-col gap-1">
            <div class="font-medium text-lg">QR</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <table class="border-collapse border border-gray-300 text-sm">
                <thead>
                  <tr>
                    <th class="border border-gray-300 p-2">Params</th>
                    <th class="border border-gray-300 p-2">Type</th>
                    <th class="border border-gray-300 p-2">Value / Example</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-gray-300 p-2">type</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">printQR</td>
                  <tr>
                  <tr>
                    <td class="border border-gray-300 p-2">value</td>
                    <td class="border border-gray-300 p-2">string</td>
                    <td class="border border-gray-300 p-2">google.com</td>
                  <tr>
                </tbody>
              </table>
              <textarea disabled class="min-h-[100px] border border-gray-300 bg-gray-50 text-sm p-2">
{
  "type": "printQR",
  "value": "google.com"
}</textarea>
            </div>
          </div>
        </li>

         <li>
          <div class="flex flex-col gap-1">
            <div class="font-medium text-lg">Full Example</div>
            <div class="flex gap-4 w-full">
              <textarea rows="10" disabled class="w-full border border-gray-300 bg-gray-50 text-sm p-2">{{initialCustomField}}</textarea>
            </div>
          </div>
        </li>
      </ol>
    </div>
  </div>

  <div v-if="activeIndex === 2" class="flex flex-col gap-2 border border-gray-300 rounded p-4">
    <p>Baca dokumentasi terlebih dahulu
      <button class="text-blue-700 hover:text-blue-500" @click="setActiveIndex(1)">disini</button>.
    </p>
    <textarea v-model="customField" rows="10" class="p-2 border border-gray-300 focus:border-gray-500 rounded-md focus:ring focus:ring-gray-200 focus:outline-none"></textarea>
      <div>
      <button class="bg-blue-500 p-2 rounded text-white" @click="actionCustomPrint">
        <icon fa="print" />
        Print
      </button>
    </div>
  </div>
  <div v-if="activeIndex === 3" class="flex flex-col gap-2 border border-gray-300 rounded p-4 pt-3 w-full">
    <p v-if="endpointList.length == 0">Endpoint tidak tersedia</p>
    <ol class="flex flex-col gap-3 list-decimal pl-4 font-medium text-md w-full" v-else>
      <template v-for="(item, i) in endpointList" :key="i">
        <li v-for="(descriptor, j) in item?.descriptor" :key="j" v-show="!descriptor?.ignore">
          <div class="flex flex-col gap-1">
            <div class="font-medium text-md">{{descriptor?.name ?? item?.path}}</div>
            <div>
              <span class="font-medium text-sm">{{item?.methods[j]}}</span> |
              <span class="font-normal text-sm">Endpoint : {{item?.path}}</span>
            </div>
            <div>
              <span v-if="descriptor?.dynamic" class="text-red-500 font-normal text-sm">*Required data in body</span>
            </div>
          </div>
        </li>
      </template>
    </ol>
  </div>
  <div class="font-medium" :class="is_ok ? 'text-green-600' : 'text-red-600' ">Status : {{is_ok ? "OK" : "NOT OK"}}
  </div>
  <div class="flex gap-2">
    <div>
      <button class="bg-blue-500 p-2 rounded text-white" @click="tesPrint">
        <icon fa="print" />
        Print
      </button>
    </div>
    <div>
      <button class="bg-green-500 p-2 rounded text-white" @click="tesKoneksi(false)">
        <icon fa="plug" />
        Tes Koneksi
      </button>
    </div>
  </div>
</div>
@endverbatim