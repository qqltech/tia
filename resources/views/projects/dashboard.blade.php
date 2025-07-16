@verbatim
<h1 class="text-2xl font-semibold px-4 mt-6 mb-4">Dashboard</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4 w-full mb-4">
    <div class="bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
      <p class="font-semibold text-base">HUTANG PERUSAHAAN</p>
      <p class="font-bold text-2xl mt-2 mb-1">Rp.283.234.234.-</p>
      <p><span class="text-green-600 font-semibold"><icon fa="arrow-up"/>+10% </span>Dari Kemarin</p>
    </div>
    

    <div class="bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
      <p class="font-semibold text-base">UANG KELUAR</p>
      <p class="font-bold text-2xl mt-2 mb-1">Rp.2.490.-</p>
      <p><span class="text-green-600 font-semibold"><icon fa="arrow-up"/>+20% </span>Dari Bulan Lalu</p>
    </div>

    <div class="bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
      <p class="font-semibold text-base">UANG MASUK</p>
      <p class="font-bold text-2xl mt-2 mb-1">Rp.999.999.999.999.-</p>
      <p><span class="text-green-600 font-semibold"><icon fa="arrow-up"/>+15% </span>Dari Bulan Lalu</p>
    </div>

    <div class="bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
      <p class="font-semibold text-base">New User</p>
      <p class="font-bold text-2xl mt-2 mb-1">1.670</p>
      <p><span class="text-green-600 font-semibold"><icon fa="arrow-up"/>+10% </span>Dari Bulan Lalu</p>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 px-4 w-full mb-4">
  <div class="col-span-12 md:col-span-8 bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
    <div class="flex mb-4 justify-between">
      <h1 class="font-semibold text-base">Total Pesanan: Order by Month</h1>
      <FieldSelect class="w-[20%] !mt-0"
        :bind="{ disabled: false, clearable:false }"
        :value="values.bulan_1" @input="v=>values.bulan_1=v"
        valueField="key" displayField="key"
        :options="months"
        label="" :check="false"
      />
    </div>  
  <line-chart :data="dataChart" height="390px" adapter="highcharts"/>
  </div>
  <div class="col-span-12 md:col-span-4 bg-white bg-opacity-95 border-t-4 border-[#00AA13] shadow-lg py-4 rounded-lg px-4 flex flex-col">
  <div class="flex justify-between p-2"><h3 class="text-lg font-semibold">Calendar</h3><h3 class="align-text-bottom align-bottom">{{ currentMonth }} {{ currentYear }}</h3></div>
    <table class="border-collapse text-[#757575] mb-4">
      <tr>
        <th v-for="day in daysOfWeek" :key="day" class="p-1 font-semibold">{{ day }}</th>
      </tr>
      <tr v-for="row in calendarRows" :key="row" class="">
        <td v-for="cell in row" :key="cell.date" :class="{ 'bg-[#00AA13] text-white': cell.isToday, 'cursor-pointer':cell.day }" class="p-1 text-center" @click="handleDateClick(cell.date)">{{ cell.day }}</td>
      </tr>
    </table>
    <div class="bg-[#00AA13] h-[1px] mb-6"></div>
    <div class="flex justify-between mb-2">
      <h1 class="font-semibold text-lg">Activity</h1>
      <h1>{{currentDate}} {{currentMonth}} {{currentYear}}</h1>
    </div>
    <table>
      <tr class="bg-[#FBFBFB] rounded-xl">
        <td class="p-2 rounded-lg">Supriyadi - PT Jaya Makmur</td>
      </tr>
      <tr>
        <td class="p-2 rounded-lg">Fahrizal - PT Jasa</td>
      </tr>
      <tr class="bg-[#FBFBFB]">
        <td class="p-2 rounded-lg">Syamsul - PT Agung</td>
      </tr>
    </table>
  </div>
</div>






</div>
</div>





@endverbatim

