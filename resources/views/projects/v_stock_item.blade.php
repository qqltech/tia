@verbatim
<vue-final-modal v-model="modalOpenHistory" :drag="true" :resize="true">
  <div
    v-if="modalOpenHistory"
    class="top-0 left-0 w-screen h-screen !m-0 flex items-center justify-center"
  >
    <div
      class="absolute z-41 top-0 left-0 bg-dark-50 w-full h-full bg-opacity-50"
      @click.self="modalOpenHistory = !modalOpenHistory"
    />
    <div
      class="z-42 py-3 px-3 <md:px-0 bg-white w-1/2 <md:w-full max-h-3/4 rounded relative overflow-auto"
    >
      <div class="modal-content py-4 text-left px-6">
        <!-- Modal Header -->
        <div class="modal-header flex items-center justify-between flex-wrap">
          <div class="flex items-start flex-col">
            <h3 class="text-xl font-semibold">History Item</h3>
            <h3 class="text-base font-normal">[ {{dataHistoryDataItem?.nama_item ??'-' }} ]</h3>
          </div>
        </div>
        <!-- KENE COK -->

        <!-- Modal Body -->
        <div v-if="dataHistoryDataItem?.items?.length" class="modal-body max-h-[400px] overflow-y-auto">
          <table class="w-full my-3 border border-collapse">
            <thead class="bg-gray-100 text-center">
              <tr class="border">
                <td class="border px-2 py-1 font-medium sticky top-0 bg-gray-100 z-10">No</td>
                <td class="border px-2 py-1 font-medium sticky top-0 bg-gray-100 z-10">Referensi Table</td>
                <td class="border px-2 py-1 font-medium sticky top-0 bg-gray-100 z-10">No. Referensi</td>
                <td class="border px-2 py-1 font-medium sticky top-0 bg-gray-100 z-10">Tanggal</td>
                <td class="border px-2 py-1 font-medium sticky top-0 bg-gray-100 z-10">Tipe Transaksi</td>
                <td class="border px-2 py-1 font-medium sticky top-0 bg-gray-100 z-10">Qty Awal</td>
                <td class="border px-2 py-1 font-medium sticky top-0 w-[5%] bg-gray-100 z-10">Qty Masuk</td>
                <td class="border px-2 py-1 font-medium sticky top-0 w-[5%] bg-gray-100 z-10">Qty Keluar</td>
                <td class="border px-2 py-1 font-medium sticky top-0 w-[5%] bg-gray-100 z-10">Qty Sisa</td>
                <td class="border px-2 py-1 font-medium sticky top-0 w-[20%] bg-gray-100 z-10">Harga</td>
              </tr>
            </thead>
            <tbody>
              <tr class="border" v-for="(d, i) in dataHistoryDataItem?.items" :key="i">
                <td class="border px-2 py-1 text-center">{{ i + 1 }}</td>
                <td class="border px-2 py-1">{{ d.type ?? '-' }}</td>
                <td class="border px-2 py-1">{{ d.ref_no ?? '-' }}</td>
                <td class="border px-2 py-1">{{ d.date ?? '-' }}</td>
                <td class="border px-2 py-1 text-center">{{ d.tipe_transaksi ?? '-' }}</td>
                <td class="border px-2 py-1 text-right">{{ d.qty_awal ?? 0 }}</td>
                <td class="border px-2 py-1 text-right">
                  {{d.qty_in ?? 0}}
                </td>
                <td class="border px-2 py-1 text-right">
                  {{d.qty_out ?? 0}}
                </td>
                <td class="border px-2 py-1 text-right">
                  {{d.qty_sisa ?? 0}}
                </td>
                <td class="border px-2 py-1 text-right">
                  {{ formatRupiah(d.price.toFixed(0)) ?? '-'}}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="modal-body">
          <table class="w-[100%] my-3 border">
            <thead>
              <tr class="border">
                <td class="border px-2 py-1 font-medium">No</td>
                <td class="border px-2 py-1 font-medium">Tanggal</td>
                <td class="border px-2 py-1 font-medium">Harga</td>
                <td class="border px-2 py-1 font-medium">Edited By</td>
              </tr>
            </thead>
            <tbody>
              <tr class="border">
                <td colspan="20" class="py-[20px] text-center">No data to show</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Modal Footer -->
        <div class="modal-footer flex justify-end mt-2">
          <button
            @click="closeHistory"
            class="modal-button bg-gray-500 hover:bg-gray-600 text-white font-semibold ml-2 px-2 py-1 rounded-sm"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</vue-final-modal>

<div class="bg-white p-1 rounded-md  min-h-[520px] border-t-8 border-blue-500">
  <div class="text-xl p-2 font-bold">KARTU STOK</div>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions" class="max-h-[750px]">
    <template #header class="text-left flex min-h-[320px] ">
      <!-- <div>Filter Gudang : </div>
      <FieldSelect class="w-200px" :bind="{ clearable: true }" :value="warehouse_id"
        @update:value="v => {
          $log(v);
          warehouse_id = v;
          apiTable.reload();
        }" valueField="id"
        displayField="nama_warehouse" :api="{
          url: `${store.server.url_backend}/operation/m_warehouse`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: { simplest:true, transform:false, join:false, order_by: 'id', order_type: 'asc', }
        }" placeholder="Pilih Warehouse" label="" :check="false" /> -->
    </template>
  </TableApi>
</div>
@endverbatim