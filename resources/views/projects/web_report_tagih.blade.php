@php
$req = app()->request;

$t_spk_lain = \DB::select("
WITH kontainer_summary AS (
SELECT
tbo.id AS t_buku_order_id,
mg2.deskripsi AS ukuran_value,
mg1.deskripsi2 AS jenis_value,
COUNT(tbodn.id) AS jumlah
FROM t_buku_order_d_npwp tbodn
LEFT JOIN set.m_general mg2 ON mg2.id = tbodn.ukuran
LEFT JOIN set.m_general mg1 ON mg1.id = tbodn.jenis
LEFT JOIN t_buku_order tbo ON tbo.id = tbodn.t_buku_order_id
GROUP BY tbo.id, mg2.deskripsi, mg1.deskripsi2
)
SELECT
tt.no_tagihan,
tt.no_buku_order AS t_buku_order_id,
tt.no_faktur_pajak,
tt.tgl AS tgl_tagihan,
tt.ppn,
tt.catatan,
tt.total_ppn,
mc.nama_perusahaan AS nama_customer,
tbo.no_buku_order AS kode_buku_order,
tbo.lokasi_stuffing,
tbo.no_invoice,
tbo.jenis_barang,
tbo.nama_kapal,
tbo.voyage,
tbo.no_bl,
tbo.tujuan_asal,
tbo.tanggal_pengkont,
tang.party,
tang.no_angkutan,

-- ✅ gabungkan semua no_aju yang terkait
STRING_AGG(DISTINCT mgnad.no_aju, ', ' ORDER BY mgnad.no_aju) AS daftar_no_aju,

-- ✅ hitung berapa banyak no_aju unik
COUNT(DISTINCT mgnad.no_aju) AS jumlah_no_aju,

-- ✅ gabungkan hasil subquery (jumlah x ukuran jenis)
STRING_AGG(
CONCAT(ks.jumlah, 'x', ks.ukuran_value, ' ', ks.jenis_value),
', ' ORDER BY ks.ukuran_value
) AS ukuran_jenis_summary

FROM t_tagihan tt
LEFT JOIN t_buku_order tbo ON tbo.id = tt.no_buku_order
LEFT JOIN m_customer mc ON mc.id = tt.customer
LEFT JOIN t_angkutan tang ON tang.t_buku_order_id = tbo.id
LEFT JOIN kontainer_summary ks ON ks.t_buku_order_id = tbo.id
LEFT JOIN t_ppjk tppjk ON tppjk.t_buku_order_id = tbo.id
LEFT JOIN m_generate_no_aju_d mgnad ON mgnad.id = tppjk.no_ppjk_id
LEFT JOIN m_generate_no_aju mgna ON mgna.id = mgnad.m_generate_no_aju_id
WHERE tt.id = ?
GROUP BY
tt.no_tagihan,
tt.no_buku_order,
tt.no_faktur_pajak,
tt.tgl,
tt.ppn,
tt.catatan,
tt.total_ppn,
mc.nama_perusahaan,
tbo.no_buku_order,
tbo.lokasi_stuffing,
tbo.no_invoice,
tbo.jenis_barang,
tbo.nama_kapal,
tbo.voyage,
tbo.no_bl,
tbo.tujuan_asal,
tbo.tanggal_pengkont,
tang.party,
tang.no_angkutan
LIMIT 1;
", [$req['id']]);

$n = $t_spk_lain[0] ?? null;

// 2️⃣ Detail Container (ukuran & jenis singkatan)
$detailContainerSpkLain = \DB::select("
SELECT
mg1.deskripsi AS jenis_value,
mg2.deskripsi AS ukuran_value,
mg1.deskripsi2 AS jenis_singkatan_value,
COUNT(ttdn.ukuran) AS jumlah,
ttdn.ukuran
FROM t_tagihan_d_npwp AS ttdn
LEFT JOIN set.m_general mg1 ON mg1.id = ttdn.jenis
LEFT JOIN set.m_general mg2 ON mg2.id = ttdn.ukuran
WHERE ttdn.t_tagihan_id = ?
GROUP BY
ttdn.ukuran,
mg1.deskripsi,
mg1.deskripsi2,
mg2.deskripsi
ORDER BY ttdn.ukuran ASC
", [$req['id']]);

$str = [];
foreach ($detailContainerSpkLain as $dsl) {
$str[] = $dsl->jumlah . "x" . $dsl->ukuran_value . ' ' . $dsl->jenis_singkatan_value;
}
$format = implode(", ", $str);

// 3️⃣ Nomor Container
$noContainer = \DB::select("
SELECT
CONCAT(COALESCE(ttdn.no_prefix, '-'), '-', COALESCE(ttdn.no_suffix, '-')) AS no_container
FROM t_tagihan_d_npwp AS ttdn
WHERE ttdn.t_buku_order_id = ? AND ttdn.t_tagihan_id = ?
", [$n->t_buku_order_id ?? 0, $req['id']]);

$data = [];
foreach ($noContainer as $nc) {
$data[] = $nc->no_container;
}
$resultNoContainer = implode(", ", $data);

// 4️⃣ Fungsi Tambahan
function formatDate($date) {
$unixTime = strtotime($date);
return date("d M Y", $unixTime);
}

// 5️⃣ Query Total PPN / Non-PPN
// 5️⃣ Query Total PPN / Non-PPN (FIX: tambah CTE angkutan_* dan select JSON-nya)
$generate_total = \DB::select("
WITH dl AS (
SELECT
ttdl.t_tagihan_id,
COUNT(*) FILTER (WHERE ttdl.is_ppn IS TRUE) AS jumlah_is_ppn_true,
SUM(ttdl.tarif_realisasi) FILTER (WHERE ttdl.is_ppn IS TRUE) AS total_tarif_is_ppn_true,
COUNT(*) FILTER (WHERE COALESCE(ttdl.is_ppn, FALSE) = FALSE) AS jumlah_non_ppn,
SUM(ttdl.tarif_realisasi) FILTER (WHERE COALESCE(ttdl.is_ppn, FALSE) = FALSE) AS total_tarif_non_ppn
FROM t_tagihan_d_lain ttdl
WHERE ttdl.t_tagihan_id = ?
GROUP BY ttdl.t_tagihan_id
),
angkutan_true AS (
SELECT
ttdt.t_tagihan_id,
json_agg(
json_build_object(
'nama_jasa', mj.nama_jasa,
'tarif', ttdt.tarif
)
ORDER BY mj.nama_jasa
) AS items
FROM t_tagihan_d_tarif ttdt
JOIN m_jasa mj ON mj.id = ttdt.m_jasa_id
WHERE ttdt.t_tagihan_id = ?
AND ttdt.ppn IS TRUE
-- kalau perlu batasi hanya yang angkutan, aktifkan salah satu:
-- AND mj.nama_jasa ILIKE '%ANGKUT%' -- by name
-- AND mj.kategori = 'ANGKUTAN' -- by category (contoh)
GROUP BY ttdt.t_tagihan_id
),
angkutan_nonppn AS (
SELECT
ttdt.t_tagihan_id,
json_agg(
json_build_object(
'nama_jasa', mj.nama_jasa,
'tarif', ttdt.tarif
)
ORDER BY mj.nama_jasa
) AS items
FROM t_tagihan_d_tarif ttdt
JOIN m_jasa mj ON mj.id = ttdt.m_jasa_id
WHERE ttdt.t_tagihan_id = ?
AND COALESCE(ttdt.ppn, FALSE) = FALSE -- ← termasuk FALSE dan NULL
-- batasi hanya yang angkutan jika perlu:
-- AND mj.nama_jasa ILIKE '%ANGKUT%'
-- AND mj.kategori = 'ANGKUTAN'
GROUP BY ttdt.t_tagihan_id
)
SELECT
-- header
tt.grand_total,
tt.total_ppn,
tt.total_jasa_cont_ppjk,
tt.total_jasa_angkutan,
tbo.no_buku_order,

-- agregat lain-lain
COALESCE(dl.jumlah_is_ppn_true, 0) AS jumlah_is_ppn_true,
COALESCE(dl.total_tarif_is_ppn_true, 0) AS total_tarif_is_ppn_true,
COALESCE(dl.jumlah_non_ppn, 0) AS jumlah_non_ppn,
COALESCE(dl.total_tarif_non_ppn, 0) AS total_tarif_non_ppn,

-- detail lain-lain (yang kamu loop manual)
ttdl.keterangan AS keterangan_lain,
ttdl.qty,
ttdl.tarif_realisasi,
ttdl.is_ppn AS is_ppn,

-- ✅ JSON untuk loop angkutan
COALESCE(angkutan_true.items, '[]'::json) AS detail_angkutan_ppn_true,
COALESCE(angkutan_nonppn.items, '[]'::json) AS detail_angkutan_non_ppn

FROM t_tagihan tt
LEFT JOIN t_buku_order tbo ON tt.no_buku_order = tbo.id
LEFT JOIN t_tagihan_d_lain ttdl ON tt.id = ttdl.t_tagihan_id
LEFT JOIN dl ON dl.t_tagihan_id = tt.id
LEFT JOIN angkutan_true ON angkutan_true.t_tagihan_id = tt.id
LEFT JOIN angkutan_nonppn ON angkutan_nonppn.t_tagihan_id = tt.id
WHERE tt.id = ?
", [$req['id'], $req['id'], $req['id'], $req['id']]);

$gt = $generate_total[0] ?? null; // tetap untuk header/ringkasan




// 6️⃣ Fungsi terbilang
function terbilang($number) {
$huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
$temp = '';
if ($number == 10) $temp = 'sepuluh';
elseif ($number == 11) $temp = 'sebelas';
elseif ($number < 12) $temp=$huruf[$number]; elseif ($number < 20) $temp=terbilang($number - 10) . ' belas' ; elseif
  ($number < 100) $temp=terbilang((int)($number / 10)) . ' puluh ' . terbilang($number % 10); elseif ($number < 200)
  $temp='seratus ' . terbilang($number - 100); elseif ($number < 1000) $temp=terbilang((int)($number / 100)) . ' ratus '
  . terbilang($number % 100); elseif ($number < 2000) $temp='seribu ' . terbilang($number - 1000); elseif ($number <
  1000000) $temp=terbilang((int)($number / 1000)) . ' ribu ' . terbilang($number % 1000); elseif ($number < 1000000000)
  $temp=terbilang((int)($number / 1000000)) . ' juta ' . terbilang($number % 1000000); return trim($temp); }
  $currentDate=date("d/m/Y"); $currentTime=date("H:i:s"); $shown_items=[]; if (!empty($gt->ppn)) {
  $total_perhitungan = ($gt->total_jasa_cont_ppjk ?? 0) + ($gt->tarif ?? 0);
  } else {
  $total_perhitungan = $gt->total_jasa_cont_ppjk ?? 0;
  }

  // ========================
  // 7️⃣ Ambil Data DP
  // ========================
  $dp_tagihan = \DB::select("
  SELECT
  t_dp.total_amount,
  t_tag.no_tagihan,
  t_bo.no_buku_order,
  m_customer.id AS customer_id
  FROM t_dp_penjualan AS t_dp
  LEFT JOIN t_buku_order AS t_bo ON t_dp.t_buku_order_id = t_bo.id
  LEFT JOIN t_tagihan AS t_tag ON t_bo.id = t_tag.no_buku_order
  LEFT JOIN m_customer ON t_dp.m_customer_id = m_customer.id
  WHERE t_tag.id = ?
  ", [$req['id']]);

  $dt = $dp_tagihan[0] ?? null;

  @endphp



  <!-- halaman 1 -->
  <!-- TITTLE -->
  <table>
    <tr>
      <td style="padding: 10px; width: 10%; font-weight: bold;" rowspan="2">
        logo
      </td>
      <td style="padding: 10px; width: 33%; font-weight: bold;">
        PT. TIA SENTOSA MAKMUR <br>
        <?php
    $spasi = '';
    for ($i = 0; $i < 14; $i++) {
        $spasi .= '&nbsp;';
    }
    echo $spasi . 'Surabaya';
    ?>
      </td>
    </tr>
    <!-- <tr>
  <td style="text-align: center; font-weight: bold;">  Surabaya</td>
</tr> -->
  </table>

  <hr>
  <!-- BODY -->
  <table border="1">
    <table>
      <!-- NOTA NO -->
      <br>
      <br>
      <tr>
        <td style="text-align: left; padding: 10px; width: 23% ; font-size:12px; border: 1px solid black;">
          No. NOTA
        </td>
        <td style="text-align: left; padding: 10px; width: 35% ;font-size:12px; border: 1px solid black;">
          <b> {{ $n->kode_buku_order ?? '-' }} </b>
        </td>
        <!-- END NOTA NO -->
        <td style="text-align: left; padding: 20px; width: 50% ; font-size:12px;">
          ORD: <b> {{ $n->kode_buku_order ?? '-' }} </b>
        </td>
      </tr>
      <br>
      <tr>
        <td style="text-align: left; padding: 10px; width: 21% ; font-size:11px;">
          <i>Sudah terima dari </i>
        </td>
        <td style="text-align: left; padding: 10px; width: 2%; ">
          :
        </td>
        <td style="text-align: left; padding: 5px; width: 79% ; font-size:11px; ">
          <b> {{ $n->nama_customer ?? '-'}} </b>
        </td>
        <!-- END NOTA NO -->
      </tr>

      <tr style="font-size: 9px">
        <td style="text-align: left; padding: 10px; width: 21% ; ">
          Pembayaran <br> <br> <br> <br> <br> <br> <br>
          <!-- KDR(DRY) -->
        </td>
        <td style="text-align: left; padding: 10px; width: 2%; ">
          : <br>
  : <br>
  :
        </td>
        <td style="text-align: left; padding: 10px; width: 18% ; ">
          PARTY <br>
  JENIS BARANG <br>
  CONT NO.<br>
  STUFF <br> <br>
  INV
        </td>
        <td style="text-align: left; padding: 10px; width: 2%; ">
          : <br>
  : <br>
  : <br>
  : <br> <br>
  :
        </td>
        <td style="text-align: left; padding: 10px; width: 22%; ">
          <!-- party : angkutan -->
          {{ $n->ukuran_jenis_summary ?? '-' }} <br>
  {{ $n->jenis_barang ?? '-' }} <br>
  {{ $n->no_angkutan ?? '-' }} <br>
  {{ $n->lokasi_stuffing ?? '-' }} <br>
  {{ $n->no_invoice ?? '-' }}
        </td>
        <td style="text-align: left; padding: 10px; width: 13%; ">
          KAPAL <br>
  VOYAGE <br>
  TUJUAN <br>
  NO. AJU <br>
  TGL STUFF <br>
  DOC <br>
  KET
        </td>
        <td style="text-align: left; padding: 10px; width: 2%; ">
          : <br>
  : <br>
  : <br>
  : <br>
  : <br>
  : <br>
  :
        </td>
        <td style="text-align: left; padding: 10px; width: 22%; ">
          {{ $n->nama_kapal ?? '-'}} <br>
  {{ $n->voyage ?? '-' }} <br>
  {{ $n->tujuan_asal ?? '-' }} <br>
  {{ $n->daftar_no_aju ?? '-' }} <br>
  {{ formatDate($n->tanggal_pengkont ?? '-') }} <br>
  {{ $n->jumlah_no_aju ?? 0 }} <br>
  {{ $n->catatan ?? '-' }}
        </td>
      </tr>
    </table>

    <hr>
    <!-- BODY -->
    <table border="1">

      @php
      $total_jasa_ppn = $gt->total_jasa_cont_ppjk + $gt->total_tarif_is_ppn_true + $gt->total_jasa_angkutan;
      @endphp
      @php
      $angkutanPpnTrue = json_decode($gt->detail_angkutan_ppn_true ?? '[]', true);
      $angkutanNonPpn = json_decode($gt->detail_angkutan_non_ppn ?? '[]', true);
      $rowNo = 3; // contoh penomoran awal (silakan sesuaikan)
      @endphp

      <tr>
        <td border="1" style="width:5%; text-align: center;">No.</td>
        <td border="1" style="width:35%; text-align: center;">Keterangan</td>
        <td border="1" style="width:10%;"></td>
        <td border="1" colspan="2" style="width:25%; text-align: center;">Tarif</td>
        <td border="1" colspan="2" style="width:25%; text-align: center;">Jumlah</td>
      </tr>

      <tr>
        <td border="1" style="text-align: center;">1.</td>
        <td border="1"> Total Jasa Cont + PPJK</td>
        <td border="1" style="text-align: center;"> 1 </td>
        <td style="width:5%; border-bottom: 1px solid black">Rp. </td>
        <td style="width:20%; border-bottom: 1px solid black; text-align: right;"></td>
        <td style="width:5%; border-left: 1px solid black"> Rp.</td>
        <td style="width:20%; border-bottom: 1px solid black; text-align: right;">
          {{number_format($gt->total_jasa_cont_ppjk, 2, ',', '.')}} </td>
      </tr>

      <tr>
        <td border="1" style="text-align: center;">2.</td>
        <td border="1"> Total Lain-lain (PPN)</td>
        <td border="1" style="text-align: center;"> {{ $gt->jumlah_is_ppn_true }} </td>
        <td style="width:5%; border-bottom: 1px solid black;">Rp.</td>
        <td style="width:20%; border-bottom: 1px solid black; text-align: right;"></td>
        <td style="width:5%; border-top: 1px solid black; border-left: 1px solid black"> Rp.</td>
        <td style="width:20%; border-bottom: 1px solid black; text-align: right;">{{
          number_format($gt->total_tarif_is_ppn_true, 2, ',', '.') }}</td>
      </tr>

      @foreach ($angkutanPpnTrue as $row)
      <tr>
        <td border="1" style="text-align: center;">{{ $rowNo++ }}.</td>
        <td border="1">{{ $row['nama_jasa'] ?? '' }}</td>
        <td border="1" style="text-align: center;"></td>

        <td style="width:5%; border-bottom: 1px solid black;">Rp.</td>
        <td style="width:20%; border-bottom: 1px solid black; text-align: right;"></td>

        <td style="width:5%; border-top: 1px solid black; border-left: 1px solid black">Rp.</td>
        <td style="width:20%; border-bottom: 1px solid black; text-align: right;">
          {{ number_format(($row['tarif'] ?? 0), 2, ',', '.') }}
        </td>
      </tr>
      @endforeach


      <!-- <tr>
    <td border="1" style="text-align: center;">3.</td>
    <td border="1" > Biaya Pelabuhan / COO / PPJK</td>
    <td border="1"></td>
    <td style="width:5%; border-bottom: 1px solid black;"> </td>
    <td style="width:20%; border-bottom: 1px solid black; text-align: right;"> </td>
    <td border="1" style="width:5%;"> Rp.</td>
    <td border="1" style="width:20%; text-align: right;">100.000,00</td>
  </tr> -->

      <!-- <tr>
    <td border="1" style="text-align: center;">6.</td>
    <td border="1" style="text-align: center;"></td>
    <td border="1" ></td>
    <td style="width:5%; border-bottom: 1px solid black; "> </td>
    <td style="width:20%; border-bottom: 1px solid black; text-align: right;"> </td>
    <td border="1" style="width:5%;"> Rp.</td>
    <td border="1" style="width:20%; text-align: right;">100.000,00</td>
  </tr> -->

      @php
      $total_dpp_ppn = $total_jasa_ppn + $n->total_ppn;
      @endphp
      <tr>
        <td border="1" colspan="3" style="text-align: right;">TOTAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td border="1" colspan="2"></td>
        <td style="width:5%; border-top: 1px solid black; border-left: 1px solid black"> Rp.</td>
        <td style="width:20%; border-bottom: 1px solid black; text-align: right;">{{ number_format($total_jasa_ppn, 2,
          ',', '.') }}</td>
      </tr>

      <tr>
        <td border="1" colspan="3" style="text-align: right;"> </td>
        <td border="1" colspan="2" style="text-align: center;">PPN</td>
        <td style="width:5%; border-top: 1px solid black; border-left: 1px solid black"> Rp.</td>
        <td style="width:20%; border-bottom: 1px solid black; text-align: right;">{{ number_format($n->total_ppn, 2,
          ',', '.') }}</td>
      </tr>

      <tr>
        <td border="1" colspan="3" style="text-align: left; ">&nbsp; PPN = {{ number_format($n->ppn, 0, ',', '.') }} % X
          Dasar Pengenaan Pajak</td>
        <td border="1" colspan="2" style="text-align: center;">DPP + PPN</td>
        <td style="width:5%; border-top: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black">
          Rp.</td>
        <td style="width:20%; border-bottom: 1px solid black; text-align: right;">{{ number_format($total_dpp_ppn, 2,
          ',', '.') }}</td>
      </tr>

    </table>

    <table>
      <tr>
        <td style="text-align: center; width: 50%;"></td>
        <td style="text-align: left; width: 25%; font-size:10px"> Surabaya,</td>
        <td style="text-align: left; width: 25%; font-size:10px"> {{ formatDate($n->tgl_tagihan ?? '-') }}
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: right; width: 18%; font-size:10px"> <i><u>TERBILANG&nbsp;&nbsp;</u></i></td>
      </tr>

      <tr>
        <td style="text-align: left; width: 7%;"> </td>
        <td colspan="2" style="width: 93%;  font-size:10px;">
          <i><u># {{ucfirst(trim(terbilang($total_dpp_ppn)))}} Rupiah #</u></i></td>
      </tr>
    </table>
    <br>
    <br>
  </table>

  <table>
    <tr>
      <td style="font-size:8px;">NB :</td>
    </tr>
    <tr>
      <td style="font-size:8px;">PEMBAYARAN DENGAN CEK/GIRO AKAN DIANGGAP LUNAS, SETELAH DICAIRKAN DI REK. KAMI</td>
    </tr>
    <tr>
      <td style="font-size:8px; font-weight: bold;">Dicetak pada tgl :{{$currentDate}} jam {{$currentTime}} -
        Opr:RINI#255</td>
    </tr>
  </table>

  <!-- halaman 2 -->
  <?php
$br = '';
for ($i = 0; $i < 32; $i++) {
    $br .= '<br>';
}
echo $br;
?>

  <!-- <div style="border-top: 1px dashed #000; width: 100%; margin: 20px 0;"></div> -->
  <!-- Lampiran Biaya  -->

  <table>
    <tr>
      <td
        style="text-align: left; text-align: center; padding: 6px; width: 100%; font-weight: bold; text-decoration: underline; ">
        LAMPIRAN BIAYA (REIMBURSED)
      </td>
    </tr>
  </table>

  <table style="font-size: 10px;">
    <tr>
      <td style="text-align: left; text-size:8px; padding: 10px; width: 23% ; ">
        TANGGAL <br>
    EX Nota
      </td>
      <td style="text-align: left; text-size:8px; padding: 10px; width: 3% ; ">
        : <br>
    :
      </td>
      <td style="text-align: left; text-size:8px; padding: 10px; width: 30% ; ">
        <b> {{ formatDate($n->tgl_tagihan ?? '-') }} </b> <br>
        <b> {{ $n->kode_buku_order ?? '-' }}  </b>
      </td>
      <td style="text-align: left; text-size:8px; padding: 10px; width: 15%; font-size: 10px ;">
        <i> <b>EX No. NOTA</b> </i>
      </td>
      <td style="text-align: left; text-size:8px; padding: 10px; width: 5%;">
        :
      </td>
      <td style="text-align: left; text-size:8px; font-size: 10px; width: 20%;">
        {{ $n->kode_buku_order ?? '-' }}
      </td>
    </tr>
  </table>

  <table cellspacing="0" cellpadding="2" style="font-size: 10px; width: 80%; border-collapse: collapse;">
    <tr style="text-align: center; font-weight:bold;">
      <td style="width: 30%; font-size: 10px; text-align: left; "><u> RINCIAN BIAYA </u></td>
      <td style="width: 30%;"></td>
      <td style="width: 10%;"></td>
      <td style="width: 10%;"></td>
      <td style="width: 20%;"></td>
      <td style="width: 20%;"></td>
    </tr>

    @php
    $ppn_null_items = [];
    $total_non_ppn_lain = 0; // dari t_tagihan_d_lain (is_ppn = null)
    $total_non_ppn = 0; // total gabungan yang diminta
    @endphp

    {{-- ========== SUM dari Lain-lain (is_ppn = null) ========== --}}
    @foreach($generate_total as $gt)
    @php
    $isPpn = property_exists($gt, 'is_ppn') ? $gt->is_ppn : null;
    @endphp

    @if(is_null($isPpn))
    @unless(in_array($gt->keterangan_lain, $ppn_null_items))
    @php
    $total_tarif_qty = ($gt->tarif_realisasi ?? 0) * ($gt->qty ?? 0);
    $ppn_null_items[] = $gt->keterangan_lain;
    $total_non_ppn_lain += $total_tarif_qty;
    @endphp
    <tr style="text-align: left;">
      <td style="width: 30%;">{{ $gt->keterangan_lain }}</td>
      <td style="width: 30%; text-align: right;">{{ number_format($gt->tarif_realisasi ?? 0, 2, ',', '.') }}</td>
      <td style="width: 10%; text-align: right;">{{ number_format($gt->qty ?? 0, 0, ',', '.') }}</td>
      <td style="width: 10%; text-align: left;">Rp.</td>
      <td style="width: 20%; text-align: right;">{{ number_format($total_tarif_qty, 2, ',', '.') }}</td>
      <td style="width: 20%; text-align: left;"></td>
    </tr>
    @endunless
    @endif
    @endforeach

    {{-- set total gabungan awal = hasil lain-lain --}}
    @php
    $total_non_ppn = $total_non_ppn_lain;
    @endphp

    {{-- ========== SUM dari Angkutan Non-PPN (false/null) ========== --}}
    @foreach ($angkutanNonPpn as $row)
    @php
    $tarif = (float)($row['tarif'] ?? 0);
    // akumulasi ke total gabungan
    $total_non_ppn += $tarif;
    @endphp
    <tr style="text-align: left;">
      <td style="width: 30%;">{{ $row['nama_jasa'] ?? '-' }}</td>
      <td style="width: 30%; text-align: right;"></td>
      <td style="width: 10%; text-align: right;"></td>
      <td style="width: 10%; text-align: left;">Rp.</td>
      <td style="width: 20%; text-align: right;">{{ number_format($tarif, 2, ',', '.') }}</td>
      <td style="width: 20%; text-align: left;"></td>
    </tr>
    @endforeach

    <!-- <tr style="text-align: left;">
    <td style="width: 30%;">LIFT ON</td>
    <td style="width: 30%; text-align: right;">12</td>
    <td style="width: 10%; text-align: right;">1</td>
    <td style="width: 10%; text-align: left;">Rp.</td>
    <td style="width: 20%; text-align: right;">84.382,00</td>
    <td style="width: 20%; text-align: left;"> </td>
  </tr> -->

    <br>
    <br>
    <tr style="text-align: right;">
      <td colspan="4"></td>
      <td style="width: 20%; text-align: right;"></td>
      <td style="width: 20%; text-align: right;">Mengetahui : <br> <br> <br>
    (.....................)
      </td>
    </tr>

  </table>
  <table border="0">
    <tr>
      <td colspan="30"></td>
    </tr>
    <tr style="font-size: 12px;">
      <td colspan="5"></td>
      <td colspan="25">
        <table border="1">
          <tr>
            <td colspan="2" style="width: 20%; text-align: center;">TOTAL</td>
            <td style="width: 40%; text-align: right;">{{ number_format($total_non_ppn, 2, ',', '.')}}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <br>
  <table style="font-size: 10px">
    <tr>
      <td colspan="1" style="width: 20%; text-align:right">TERBILANG : </td>
      <td colspan="1" style="width: 80%; text-decoration:underline"><u>#
          {{ucfirst(trim(terbilang($total_non_ppn)))}} Rupiah #</u></td>
    </tr>
  </table>

  <br>
  <br>
  <table>
    <tr>
      <td style="text-align: left; padding: 10px; width: 15%; font-size: 8px;">
        Keterangan : <br>
    40| Feet
      </td>
      <!-- <td style="text-align: left; padding: 10px; width: 60% ; font-style:italic; text-decoration: underline; font-weight: bold;">
    Rp. 20.000 rupiah
    </td>  -->
    </tr>
  </table>

  <br>
  <br>
  <table>
    <tr>
      <td style="font-size:8px;">NB :</td>
    </tr>
    <tr>
      <td style="font-size:8px;">PEMBAYARAN DENGAN CEK/GIRO AKAN DIANGGAP LUNAS, SETELAH DICAIRKAN DI REK. KAMI</td>
    </tr>
    <tr>
      <td style="font-size:8px;">PEMBAYARAN LEBIH DARI TANGGAL JATUH TEMPO AKAN DIKENAKAN 2.000 PER HARI</td>
    </tr>
    <tr>
      <td style="font-size:8px; font-weight: bold;">Pembayaran ditransfer ke Rek. :</td>
    </tr>
    <tr>
      <td style="font-size:8px; font-weight: bold;">BCA.</td>
    </tr>
    <tr>
      <td style="font-size:8px; font-weight: bold;">A/N : PT.TIA SENTOSA MAKMUR</td>
    </tr>
    <tr>
      <td style="font-size:8px; font-weight: bold;">A/C : 187.099.1009</td>
    </tr>
    <tr>
      <td style="text-align: left; padding: 10px; width: 60%; font-size: 7px; font-style:italic; font-weight:bold;">
        Dicetak pada tgl : {{$currentDate}} jam {{$currentTime}} -
        Opr:RINI#255</td>
    </tr>
  </table>