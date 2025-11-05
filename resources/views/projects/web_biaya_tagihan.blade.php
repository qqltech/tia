@php
$req = app()->request;

// jupuk data tagihan
$tagihan = \DB::table('t_tagihan')->where('id', $req['id'])->first();
if (!$tagihan) {
    echo "<p>Data Tagihan tidak ditemukan.</p>";
    return;
}

// jupuk detail BKK berdasarkan t_buku_order_id
$bkkD = \DB::table('t_bkk_d as bd')
    ->selectRaw('
        bd.*,
        bkk.tanggal as tanggal_bkk,
        bkk.no_bkk,
        coa.nama_coa,
        coa.nomor
    ')
    ->leftJoin('t_bkk as bkk', 'bkk.id', '=', 'bd.t_bkk_id')
    ->leftJoin('m_coa as coa', 'coa.id', '=', 'bd.m_coa_id')
    ->where('bd.t_buku_order_id', $tagihan->no_buku_order)
    ->get();

if ($bkkD->isEmpty()) {
    echo "<p>Data BKK Detail tidak ditemukan untuk Buku Order ID {$tagihan->no_buku_order}.</p>";
    return;
}
    
$bD = $bkkD->first();

// jupuk data BKK utama (t_bkk) gawe info tambahan
$bkk = \DB::table('t_bkk')->where('id', $bD->t_bkk_id)->first();
if (!$bkk) {
    echo "<p>Data BKK utama tidak ditemukan.</p>";
    return;
}

$b = $bkk;

// jupuk data id buku order
$bukuOrder = \DB::table('t_buku_order')->where('id', $bD->t_buku_order_id)->first();
if (!$bukuOrder) {
    echo "<p>Data Buku Order tidak ditemukan.</p>";
    return;
}

// Validasi relasi
if ($tagihan->no_buku_order != $bD->t_buku_order_id) {
    echo "<p>Data tidak valid: Buku Order Tagihan dan Buku Order BKK tidak cocok.</p>";
    return;
}

// iki datane buku order
$dataBukuOrder = \DB::table('t_buku_order as tbo')
->selectRaw("
    tbo.*,
    tbo.id as t_buku_order_id,
    tbo.tipe_order,
    mg.kode as nama_pelabuhan,
    mg2.deskripsi as depo_nama,
    mg3.deskripsi as jenis_kontainer,
    mg4.deskripsi as ukuran_kontainer_value,
    mg5.deskripsi as kode_pelayaran,
    mk.nama as petugas_pengkont_nama,
    mk2.nama as petugas_pemasukan_nama,
    mc.jenis_perusahaan,
    mc.nama_perusahaan,
    mc.kode as kode_perusahaan,
    tbo.no_boking,
    tbodn.no_prefix,
    tbodn.no_suffix
")
->leftJoin('t_buku_order_d_npwp as tbodn', 'tbodn.t_buku_order_id', '=', 'tbo.id')
->leftJoin('t_buku_order_d_aju as tboda', 'tboda.t_buku_order_id', '=', 'tbo.id')
->leftJoin('set.m_general as mg', 'mg.id', '=', 'tbo.pelabuhan_id')
->leftJoin('set.m_general as mg2', 'mg2.id', '=', 'tbodn.depo')
->leftJoin('set.m_general as mg3', 'mg3.id', '=', 'tbodn.jenis')
->leftJoin('set.m_general as mg4', 'mg4.id', '=', 'tbodn.ukuran')
->leftJoin('set.m_general as mg5', 'mg5.id', '=', 'tbo.kode_pelayaran_id')
->leftJoin('set.m_kary as mk', 'mk.id', '=', 'tbodn.m_petugas_pengkont_id')
->leftJoin('set.m_kary as mk2', 'mk2.id', '=', 'tbodn.m_petugas_pemasukan_id')
->leftJoin('m_customer as mc', 'mc.id', '=', 'tbo.m_customer_id')
->where('tbo.id', $bukuOrder->id)
->first();

if (!$dataBukuOrder) {
    echo "<p>Data Buku Order tidak ditemukan.</p>";
    return;
}

$n = $dataBukuOrder;

// iki datane detail NPWP buku order
$detailBukuOrder = \DB::table('t_buku_order_d_npwp as tbodn')
    ->selectRaw("
        tbodn.*,
        mg1.deskripsi as jenis_value,
        mg1.deskripsi2 as jenis_singkatan_value,
        mg2.deskripsi as ukuran_value,
        sektor.deskripsi as sektor_value,
        depo.deskripsi as depo_value,
        pengkont.nama as petugas_pengkont_nama,
        pemasukan.nama as petugas_pemasukan_nama
    ")
    ->leftJoin('set.m_general as mg1', 'mg1.id', '=', 'tbodn.jenis')
    ->leftJoin('set.m_general as mg2', 'mg2.id', '=', 'tbodn.ukuran')
    ->leftJoin('set.m_general as sektor', 'sektor.id', '=', 'tbodn.sektor')
    ->leftJoin('set.m_general as depo', 'depo.id', '=', 'tbodn.depo')
    ->leftJoin('set.m_kary as pengkont', 'pengkont.id', '=', 'tbodn.m_petugas_pengkont_id')
    ->leftJoin('set.m_kary as pemasukan', 'pemasukan.id', '=', 'tbodn.m_petugas_pemasukan_id')
    ->where('tbodn.t_buku_order_id', $n->t_buku_order_id)
    ->orderBy('tbodn.id', 'asc')
    ->get();

$dl = $detailBukuOrder[0];

$str = [];
$grouped = $detailBukuOrder->groupBy(function ($item) {
    return $item->ukuran . ' ' . $item->jenis_singkatan_value;
});

foreach ($grouped as $key => $items) {
    $str[] = count($items) . "x" . $key;
}

$format = implode(", ", $str);

// iki datane detail AJU buku order
$detailBukuOrder2 = \DB::select("
    select 
        tboda.t_ppjk_id,
        tboda.m_customer_id,
        tboda.tanggal,
        tboda.peb_pib,
        tboda.tanggal_peb_pib,
        tboda.no_sppb
    from t_buku_order_d_aju as tboda
    where tboda.t_buku_order_id = ?
    order by tboda.tanggal asc
", [$n->t_buku_order_id]);

$dl2 = $detailBukuOrder2;

// jupuk data angkutan (t_angkutan) + detailnya (t_angkutan_d)
$angkutan = \DB::table('t_angkutan as ta')
    ->selectRaw('ta.*, ta.id as t_angkutan_id')
    ->where('ta.t_buku_order_id', $n->t_buku_order_id)
    ->orderBy('ta.id', 'asc')
    ->get();


$detailAngkutan = collect();

if ($angkutan->count() > 0) {
    $angkutanIds = $angkutan->pluck('t_angkutan_id');

    $detailAngkutan = \DB::table('t_angkutan_d as tad')
      ->selectRaw("
          tad.*,
          mg_head.deskripsi as head_value,
          mg_ukuran.deskripsi as ukuran_value,
          mg_spk.deskripsi as spk_value,
          mg_sektor.deskripsi as sektor_value,
          mg_trip.deskripsi as trip_value,
          mg_asal.deskripsi as pelabuhan_asal_value,
          depo.deskripsi as depo_value
      ")
      ->leftJoin('set.m_general as mg_head', 'mg_head.id', '=', 'tad.head')
      ->leftJoin('set.m_general as mg_ukuran', 'mg_ukuran.id', '=', 'tad.ukuran')
      ->leftJoin('set.m_general as mg_spk', 'mg_spk.id', '=', 'tad.t_spk_id')
      ->leftJoin('set.m_general as mg_sektor', 'mg_sektor.id', '=', 'tad.sektor')
      ->leftJoin('set.m_general as mg_trip', 'mg_trip.id', '=', 'tad.trip')
      ->leftJoin('set.m_general as mg_asal', 'mg_asal.id', '=', 'tad.pelabuhan')
      ->leftJoin('set.m_general as depo', 'depo.id', '=', 'tad.depo')
      ->whereIn('tad.t_angkutan_id', $angkutanIds)
      ->orderBy('tad.id', 'asc')
      ->get();
}


$angk = $angkutan;
$angkD = $detailAngkutan[0];



$currentDate = date('d/m/Y');
$currentTime = date('H:i:s');

function formatDate($date) {
    if (!$date) return '-';
    $unixTime = strtotime($date);
    return date('d/m/Y', $unixTime);
}
@endphp



<div
  style="border: 1px solid black; font-family: Arial, sans-serif; font-size: 10px; color: #000; width: 100%; padding-left: 10px">
  <!-- <pre>{{var_dump($dataBukuOrder)}}</pre> -->
  <!-- HEADER -->
  <table
    style="width: 100%; border-collapse: collapse; font-size: 14px; line-height: 1.2; font-weight: bold; margin-bottom: 4px;">
    <tbody>
      <tr>
        <td style="font-size: 10px;">
          PT. TIA SENTOSA MAKMUR<br>
              JL. PERAK TIMUR NO.236 SURABAYA
        </td>
        <td style="text-align: right; padding: 2px 4px; font-size: 10px;">TANGGAL CETAK : {{$currentDate}}</td>
      </tr>
    </tbody>
  </table>

  <br>
  <br>

  <table
    style="width: 100%; border-collapse: collapse; font-size: 14px; line-height: 1.2; font-weight: bold; margin-bottom: 4px;">
    <tbody>
      <tr style="text-align: center;">
        <td></td>
        <td colspan="3">{{$n->no_buku_order}}</td>
        <td></td>
      </tr>
      <tr style="text-align: center;">
        <td></td>
        <td colspan="3" style=" border-top: 1px solid black;">{{ $n->nama_perusahaan }}, {{ $n->jenis_perusahaan }}</td>
        <td></td>
      </tr>
    </tbody>
  </table>

  <br>
  <br>
  <br>
  <!-- DETAIL EKSPOR -->
  <table style="width: 100%; border-collapse: collapse; font-size: 10px; line-height: 1.2; font-weight:bold;">
    <tbody>
      <tr>
        <td style="padding: 2px; width: 90px;">AJU</td>
        <td style="padding: 2px; width: 10px;">:</td>
        <td style="padding: 2px; width: 100.5px;">{{$dl2->no_ppjk ?? '-'}}</td>
        <td></td>
        <td style="padding: 2px; width: 57px;">Tgl</td>
        <td style="padding: 2px; width: 10px;">:</td>
        <td style="padding: 2px; width: 80px;">{{formatDate($b->tanggal) }}</td>
      </tr>

      <tr>
        <td style="padding: 2px; width: 90px;">Jml. Kont.</td>
        <td style="padding: 2px; width: 10px;">:</td>
        <td style="padding: 2px; width: 100px;">{{$format ?? '-'}}</td>
        <td ></td>
        <td style="padding: 2px; text-align: right; width: 57px;">Tgl. Order</td>
        <td style="padding: 2px; width: 10px;">:</td>
        <td style="padding: 2px; width:80px;">{{formatDate($n->tanggal_invoice ?? '-')}}</td>
      </tr>

      <tr>
        <td style="padding: 2px;">Pelayaran</td>
        <td style="padding: 2px;">:</td>
        <td colspan="2" style="padding: 2px;">{{$n->kode_pelayaran ?? '-'}}</td>
        <td colspan="6"></td>
      </tr>

      <tr>
        <td style="padding: 2px;">Jenis Barang</td>
        <td style="padding: 2px;">:</td>
        <td colspan="1" style="padding: 2px;">{{$n->jenis_barang ?? '-'}}</td>
      </tr>
      <tr>
        <td style="padding: 2px;">Kapal</td>
        <td style="padding: 2px;">:</td>
        <td colspan="1" style="padding: 2px;">{{$n->nama_kapal ?? '-'}}</td>
        <td></td>
        <td style="padding: 2px; text-align: left; width:57px;">Voyage</td>
        <td style="padding: 2px; width:10px;">:</td>
        <td style="padding: 2px; width:40px;">{{$n->voyage ?? '-'}}</td>
      </tr>

      <tr>
        <td style="padding: 2px; width:90px;">Tujuan / Asal</td>
        <td style="padding: 2px; width:10px;">:</td>
        <td style="padding: 2px; width:90px;">{{$n->tujuan_asal ?? '-'}}</td>
        <td colspan="3"></td>
      </tr>

      <tr>
        <td style="padding: 2px; width:90px;">Depo</td>
        <td style="padding: 2px; width:10px;">:</td>
        <td style="padding: 2px; width:180px;">{{$dl->depo_value ?? '-'}}</td>
        <td style="padding: 2px; text-align: left;width:56.5px;">Pelabuhan</td>
        <td style="padding: 2px; width:10px;">:</td>
        <td style="padding: 2px; width:100px;">{{$n->nama_pelabuhan ?? '-'}}</td>
      </tr>

      <tr>
        <td style="padding: 2px; width:90px;">Peng. Kont.</td>
        <td style="padding: 2px; width:10px;">:</td>
        <td style="padding: 2px; width:180px;">{{$dl->petugas_pengkont_nama ?? '-'}}</td>
        <td style="padding: 2px; text-align: left; width:55.5px;">Tgl.</td>
        <td style="padding: 2px; width:10px;">:</td>
        <td style="padding: 2px; text-align: center; white-space: nowrap; width: 58px;">{{formatDate($n->tanggal_pengkont)}}</td>
      </tr>

      <tr>
        <td style="padding: 2px; width: 90px;">Pemasukan</td>
        <td style="padding: 2px; width: 10px;">:</td>
        <td style="padding: 2px; width: 180;">{{$dl->petugas_pemasukan_nama ?? '-'}}</td>
        <td style="padding: 2px; text-align: left;width:55px;">Tgl.</td>
        <td style="padding: 2px; width:10px;">:</td>
        <td style="padding: 2px; width:70px;">{{formatDate($n->tanggal_pemasukan)}}</td>
        <td colspan="3"></td>
      </tr>
    </tbody>
  </table>

  <br>

  <div style="font-size: 12px">
    <b><u>Keterangan</u></b>
  </div>
  <br>


  <!-- KETERANGAN -->
  <table style="width: 100%; border-collapse: collapse; font-size: 10px; line-height: 1.2; font-weight:bold;">
    <tbody>
      <tr>
        <td style="padding: 3px; width:120px;">1. COO</td>
        <td style="padding: 3px; width:10px;">:</td>
        <td style="padding: 3px; width:150px;">{{$n->coo}}</td>
        <td style="padding: 3px; width:90px;">5. OW</td>
        <td style="padding: 3px; width:30px;">:</td>
        <td style="padding: 2px; width:150px;"> / </td>
      </tr>
      <tr>
        <td style="padding: 3px; width:120px;">2. HC</td>
        <td style="padding: 3px; width:10px">:</td>
        <td style="padding: 3px; width:150px;">{{$n->hc}}</td>
        <td style="padding: 3px; width 90px">6. Closing</td>
        <td style="padding: 3px; width:10px;">:</td>
        <td style="padding: 2px; width:150px;"> {{formatDate($n->tanggal_closing_cont)}} </td>
      </tr>
      <tr>
        <td style="padding: 3px; width:120px;">3. Angkutan</td>
        <td style="padding: 3px; width:10px">:</td>
        <td style="padding: 3px; width:150px;">{{$n->angkutan ?? '-'}}</td>
        <td style="padding: 3px; width:90px">7. Lokasi Stuffing</td>
        <td style="padding: 3px; width:13px;">:</td>
        <td style="padding: 2px; width:150px;">{{$n->lokasi_stuffing ?? '-'}}</td>
      </tr>
      <tr>
        <td style="padding: 3px; width:120px;s">4. GenSet</td>
        <td style="padding: 3px width:10px;">:</td>
        <td style="padding: 3px; width:150px;">{{$n->genzet}}</td>
        <td style="padding: 3px; width:90px;">8. STAPEL</td>
        <td style="padding: 3px; width:10px;">:</td>
        <td style="padding: 2px; width:150px;"> {{$angkD->staple ?? '-'}} ({{formatDate($angkD->tanggal_out)}} - {{formatDate($angkD->tanggal_in)}}) </td>
      </tr>
      <!-- <tr>
        <td style="padding: 3px; width:120px;" colspan="4">9. STAPEL </td>
        <td style="padding: 3px; width:10px;">:</td>
        <td style="padding: 3px; width:150px;">{{$angkD->staple ?? '-'}}</td>
      </tr> -->
      <tr>
        <td style="padding: 3px; padding-left: 10px;">9. Lain-lain</td>
        <td style="padding: 3px width:10px;">:</td>
        <td colspan="4" style="padding: 3px; width:150px;"></td>
      </tr>
    </tbody>
  </table>

  <div></div>
  <div></div>

  <!-- TABEL BIAYA -->
  <table style="border: 1px solid black; border-collapse: collapse; width: 100%; font-size: 9px;">
    <thead>
      <tr style="background-color: #f2f2f2;">
        <th style="border: 1px solid black; text-align: center;">Tanggal</th>
        <th style="border: 1px solid black; text-align: center;">Perincian Biaya</th>
        <th style="border: 1px solid black; text-align: center;">Bkk</th>
        <th style="border: 1px solid black; text-align: center;">Nama</th>
        <th style="border: 1px solid black; text-align: center;">No. AC</th>
        <th style="border: 1px solid black; text-align: center;">Jumlah</th>
      </tr>
    </thead>
    <tbody>
      @php $totalNominal = 0; @endphp

    @foreach($bkkD as $bD)
      @php $totalNominal += (float) $bD->nominal; @endphp
      <tr>
        <td style="border: 1px solid black; text-align: center;">{{formatDate($bD->tanggal_bkk)}}</td>
        <td style="border: 1px solid black; text-align: center;">{{$bD->keterangan ?? '-'}}</td>
        <td style="border: 1px solid black; text-align: center;">{{$bD->no_bkk ?? '-'}}</td>
        <td style="border: 1px solid black;">{{$bD->nama_coa ?? '-'}}</td>
        <td style="border: 1px solid black; text-align: center;">{{$bD->nomor ?? '-'}}</td>
        <td style="border: 1px solid black; text-align: right;">Rp {{ number_format((float) $bD->nominal, 2, ',', '.') }}</td>
      </tr>
      @endforeach
      <tr style="font-weight: bold;">
        <td colspan="1" style="text-align: left;">Lain-Lain :</td>
        <td colspan="4"
          style=" text-align: right;">
          Total Biaya :</td>
        <td style="text-align: right;">Rp {{ number_format($totalNominal, 2, ',', '.') }}</td>
      </tr>
    </tbody>
  </table>

  <!-- FOOTER -->

  <table style="width: 100%; border-collapse: collapse; font-size: 10px; line-height: 1.2; font-weight:bold;">
    <div style="display: flex; justify-content: space-between; margin-top: 10px; font-size: 9px;">
      <tbody>
        <tr>
          <td style="width:280px;">ACC :</td>
          <td>TGH :</td>
        </tr>
    </div>
    <tbody>
  </table>
</div>