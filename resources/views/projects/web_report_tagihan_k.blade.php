@php
$req = app()->request;

// data tagihan
$t_tagihan_kon = \DB::select("
SELECT
tt.no_tagihan,
tt.no_faktur_pajak,
tt.tgl as tgl_tagihan,
tt.ppn,
mc.nama_perusahaan,
tbo.no_buku_order,
tbo.id as t_buku_order_id,
tbo.lokasi_stuffing,
tbo.jenis_barang,
tbo.nama_kapal,
tbo.voyage,
tbo.no_bl,
tbo.tujuan_asal,
tbo.no_invoice,
tbo.tanggal_pengkont,
mgnad.no_aju

FROM t_tagihan tt
LEFT JOIN t_buku_order tbo ON tbo.id = tt.no_buku_order
LEFT JOIN m_customer mc ON mc.id = tt.customer
LEFT JOIN t_ppjk tppjk ON tppjk.t_buku_order_id = tbo.id
LEFT JOIN m_generate_no_aju_d mgnad ON mgnad.id = tppjk.no_ppjk_id
LEFT JOIN m_generate_no_aju mgna ON mgna.id = mgnad.m_generate_no_aju_id
where tt.id=?
",[$req['id']]);

$tk=$t_tagihan_kon[0];

// data kontainer
$detailContainerSpkLain = \DB::select(
"select
mg1.deskripsi as jenis_value,
mg2.deskripsi as ukuran_value,
mg1.deskripsi2 as jenis_singkatan_value,
COUNT(ttdn.ukuran) AS jumlah,
ttdn.ukuran
from t_tagihan_d_npwp as ttdn
left join set.m_general mg1 on mg1.id = ttdn.jenis
left join set.m_general mg2 on mg2.id = ttdn.ukuran
where ttdn.t_tagihan_id = ?
GROUP BY
ttdn.ukuran,
mg1.deskripsi,
mg1.deskripsi2,
mg2.deskripsi
Order by ttdn.ukuran asc
", [$req['id']]
);

$str = [];
$count = 0;
foreach ($detailContainerSpkLain as $dsl) {
$str[$count] = $dsl->jumlah . "x" . $dsl->ukuran_value . ' ' .$dsl->jenis_singkatan_value;
$count += 1;
}
$format = implode(", ", $str);

$noContainer = \DB::select(
"select CONCAT(COALESCE(ttdn.no_prefix,'-'),'-',COALESCE(ttdn.no_suffix,'-'))as no_container
from t_tagihan_d_npwp as ttdn
where ttdn.t_buku_order_id = ? and ttdn.t_tagihan_id = ?",
[$tk->t_buku_order_id, $req['id']]
);

$data=[];
$count = 0;
foreach($noContainer as $nc){
$data[$count] = $nc->no_container;
$count+=1;
}
$resultNoContainer= implode(", ",$data);

// data tagihan berdasarkan ppn dan non_ppn
$total_ppn = \DB::select("
SELECT 
    tt.grand_total, 
    tt.total_ppn, 
    tt.total_jasa_cont_ppjk, 
    tt.total_jasa_angkutan, 
    ttdl.keterangan AS keterangan_lain, 
    ttdl.qty, 
    ttdl.tarif_realisasi, 
    ttdl.is_ppn, 
    ttdt.tarif, 
    ttdt.ppn,
    ttdt.catatan,
    tbo.no_buku_order,
    np.keterangan AS keterangan_non_ppn,
    np.qty AS qty_non_ppn,
    np.tarif_realisasi AS tarif_realisasi_non_ppn,
    (
    SELECT 
        COALESCE(SUM(ttdl.qty * ttdl.tarif_realisasi), 0)
    FROM 
        t_tagihan_d_lain ttdl
    WHERE 
        ttdl.t_tagihan_id = tt.id 
        AND ttdl.is_ppn IS NOT NULL
    ) AS total_tarif_realisasi_ppn,
    (
    SELECT COUNT(*) 
    FROM t_tagihan_d_lain 
    WHERE t_tagihan_d_lain.t_tagihan_id = tt.id 
    AND t_tagihan_d_lain.is_ppn IS NOT NULL
    ) AS jumlah_is_ppn,
    (
        SELECT COUNT(*) 
        FROM t_tagihan 
        WHERE total_jasa_cont_ppjk IS NOT NULL
        AND id = tt.id
    ) AS jumlah_tjcp
FROM t_tagihan tt 
LEFT JOIN t_buku_order tbo ON tt.no_buku_order = tbo.id 
LEFT JOIN t_ppjk p ON tbo.id = p.t_buku_order_id
LEFT JOIN t_tagihan_d_lain ttdl ON tt.id = ttdl.t_tagihan_id 
LEFT JOIN t_tagihan_d_tarif ttdt ON tt.id = ttdt.t_tagihan_id
LEFT JOIN (
    SELECT  
        ttdl.t_tagihan_id, 
        ttdl.keterangan, 
        ttdl.qty, 
        ttdl.tarif_realisasi
    FROM t_tagihan_d_lain ttdl
    WHERE ttdl.is_ppn IS NULL
) AS np ON tt.id = np.t_tagihan_id
WHERE tt.id = ?;
", [$req['id']]);

$tp=$total_ppn[0];

// format waktu dan tanggal
function formatDate($date){
$unixTime = strtotime($date);
$date_result = date("d M y", $unixTime);
return $date_result;
}

$currentDate = date("d/m/Y");
$currentTime = date("H:i:s");
$shown_items = [];

// format keuangan
function terbilang($number) {
    $huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
    $temp = '';  
    if ($number == 10) {
        $temp = 'Sepuluh';
    } elseif ($number == 11) {
        $temp = 'Sebelas';
    } elseif ($number < 12) {
        $temp = $huruf[$number];
    } elseif ($number < 20) {
        $temp = terbilang($number - 10) . ' Belas';
    } elseif ($number < 100) {
        $temp = terbilang((int)($number / 10)) . ' Puluh ' . terbilang($number % 10);
    } elseif ($number < 200) {
        $temp = 'Seratus ' . terbilang($number - 100);
    } elseif ($number < 1000) {
        $temp = terbilang((int)($number / 100)) . ' Ratus ' . terbilang($number % 100);
    } elseif ($number < 2000) {
        $temp = 'Seribu ' . terbilang($number - 1000);
    } elseif ($number < 1000000) {
        $temp = terbilang((int)($number / 1000)) . ' Ribu ' . terbilang($number % 1000);
    } elseif ($number < 1000000000) {
        $temp = terbilang((int)($number / 1000000)) . ' Juta ' . terbilang($number % 1000000);
    }
    return trim($temp);
}

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
      <b> {{ $tk->no_tagihan }} </b>
    </td>
    <!-- END NOTA NO -->
    <td style="text-align: left; padding: 20px; width: 50% ; font-size:12px;">
      ORD: <b> {{ $tk->no_buku_order }} </b>
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
      <b>{{ $tk->nama_perusahaan }}</b>
    </td>
    <!-- END NOTA NO -->
  </tr>

  <tr style="font-size: 9px">
    <td style="text-align: left; padding: 10px; width: 21% ; ">
      Pembayaran <br> <br> <br> <br> <br> <br>
  KDR(DRY)
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
  STUFF <br>
  INV
    </td>
    <td style="text-align: left; padding: 10px; width: 2%; ">
      : <br>
  : <br>
  : <br>
  : <br>
  :
    </td>
    <td style="text-align: left; padding: 10px; width: 22%; ">
      1 x 400 <br>
  {{ $tk->jenis_barang }}<br>
  {{ $resultNoContainer ?? '-' }} <br>
  {{ $tk->lokasi_stuffing ?? '-' }} <br>
  {{ $tk->no_invoice ?? '-' }}
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
      {{ $tk->nama_kapal ?? '-' }} <br>
  {{ $tk->voyage ?? '-' }} <br>
  {{ $tk->tujuan_asal ?? '-' }} <br>
  {{ $tk->no_aju }} <br>
  {{ formatDate($tk->tanggal_pengkont ?? '-') }} <br>
  2 <br>
  -
    </td>
  </tr>
</table>

  <hr>

<table style="font-size: 11px">
  <tr>
    <td border="1" style="width:5%; text-align: center;">No.</td>
    <td border="1" style="width:35%; text-align: center;">Keterangan</td>
    <td border="1" style="width:10%;"></td>
    <td border="1" colspan="2" style="width:25%; text-align: center;">Tarif</td>
    <td border="1" colspan="2" style="width:25%; text-align: center;">Jumlah</td>
  </tr>

  @php
    $jumlah_total_jasa_cont_ppjk = $tp->jumlah_tjcp * $tp->total_jasa_cont_ppjk;
  @endphp
  <tr>
    <td border="1" style="text-align: center;">1.</td>
    <td border="1" > Total Jasa Cont + PPJK</td>
    <td border="1" style="text-align: center;"> {{ $tp->jumlah_tjcp }} </td>
    <td style="width:5%; border-bottom: 1px solid black">Rp. </td>
    <td style="width:20%; border-bottom: 1px solid black; text-align: right;"></td>
    <td style="width:5%; border-left: 1px solid black"> Rp.</td>
    <td style="width:20%; border-bottom: 1px solid black; text-align: right;">  {{ number_format($jumlah_total_jasa_cont_ppjk, 2, ',', '.') }} </td>
  </tr>

  <tr>
    <td border="1" style="text-align: center;">2.</td>
    <td border="1"> Total Lain-lain (PPN)</td>
    <td border="1" style="text-align: center;"> {{ $tp->jumlah_is_ppn }} </td>
    <td style="width:5%; border-bottom: 1px solid black;">Rp.</td>
    <td style="width:20%; border-bottom: 1px solid black; text-align: right;"></td>
    <td style="width:5%; border-top: 1px solid black; border-left: 1px solid black"> Rp.</td>
    <td style="width:20%; border-bottom: 1px solid black; text-align: right;">{{ number_format($tp->total_tarif_realisasi_ppn, 2, ',', '.') }}</td>
  </tr>

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
    $total_jasa_lain_ppn = $jumlah_total_jasa_cont_ppjk + $tp->total_tarif_realisasi_ppn; 
  @endphp
  <tr>
    <td border="1" colspan="3" style="text-align: right;">TOTAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;
    </td>
    <td border="1" colspan="2"></td>
    <td style="width:5%; border-top: 1px solid black; border-left: 1px solid black"> Rp.</td>
    <td style="width:20%; border-bottom: 1px solid black; text-align: right;">{{ number_format($total_jasa_lain_ppn, 2, ',', '.') }}</td>
  </tr>

  <tr>
    <td border="1" colspan="3" style="text-align: right;"> </td>
    <td border="1" colspan="2" style="text-align: center;">PPN</td>
    <td style="width:5%; border-top: 1px solid black; border-left: 1px solid black"> Rp.</td>
    <td style="width:20%; border-bottom: 1px solid black; text-align: right;">{{ number_format($tp->total_ppn, 2, ',', '.') }}</td>
  </tr>

  @php
    $total_dpp_ppn = $total_jasa_lain_ppn + $tp->total_ppn;
  @endphp
  <tr>
    <td border="1" colspan="3" style="text-align: left; ">&nbsp; PPN = {{ number_format($tk->ppn) }} % X Dasar Pengenaan Pajak</td>
    <td border="1" colspan="2" style="text-align: center;">DPP + PPN</td>
    <td style="width:5%; border-top: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black"> Rp.</td>
    <td style="width:20%; border-bottom: 1px solid black; text-align: right;">{{ number_format($total_dpp_ppn, 2, ',', '.') }}</td>
  </tr>

</table>

<table>
  <tr>
    <td style="text-align: center; width: 50%;"></td>
    <td style="text-align: left; width: 25%; font-size:10px"> Surabaya,</td>
    <td style="text-align: left; width: 25%; font-size:10px"> {{ \Carbon\Carbon::parse($tk->tgl_tagihan)->format('M d, y') }}
    </td>
  </tr>
  <tr>
    <td colspan="2" style="text-align: right; width: 18%; font-size:10px"> <i><u>TERBILANG&nbsp;&nbsp;</u></i></td>
  </tr>

  <tr>
    <td style="text-align: left; width: 7%;"> </td>
    <td colspan="2" style="width: 93%;  font-size:10px;"><i><u># {{ucfirst(trim(terbilang($total_dpp_ppn)))}} Rupiah #</u></i></td>
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
      <b> {{ formatDate($tk->tgl_tagihan) }} </b> <br>
      <b> {{ $tk->no_buku_order }} </b>
    </td>
    <td style="text-align: left; text-size:8px; padding: 10px; width: 15%; font-size: 10px ;">
      <i> <b>EX No. NOTA</b> </i>
    </td>
    <td style="text-align: left; text-size:8px; padding: 10px; width: 5%;">
      :
    </td>
    <td style="text-align: left; text-size:8px; font-size: 10px; width: 20%;">
      {{ $tk->no_tagihan }}
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

  <!-- @php
    $total_tarif_qty = $tp->tarif_realisasi * $tp->qty;
  @endphp
  <tr style="text-align: left;">
    <td style="width: 30%;">{{ $tp->keterangan_lain }}</td>
    <td style="width: 30%; text-align: right;">{{ number_format($tp->tarif_realisasi, 2, ',', '.') }}</td>
    <td style="width: 10%; text-align: right;">{{ number_format($tp->qty) }}</td>
    <td style="width: 10%; text-align: left;">Rp.</td>
    <td style="width: 20%; text-align: right;">{{ number_format($total_tarif_qty, 2, ',', '.') }}</td>
    <td style="width: 20%; text-align: left;"> </td>
  </tr> -->

  @php
    $ppn_null_items = [];
    $total_non_ppn = 0;
  @endphp

  @foreach ($total_ppn as $tp)
      @if(is_null($tp->is_ppn)) <!-- Filter hanya data NON-PPN -->
          @unless(in_array($tp->keterangan_lain, $ppn_null_items))
              @php
                  $total_tarif_qty = $tp->tarif_realisasi * $tp->qty;
                  $ppn_null_items[] = $tp->keterangan_lain;
                  $total_non_ppn += $total_tarif_qty;
              @endphp
              <tr style="text-align: left;">
                  <td style="width: 30%;">{{ $tp->keterangan_lain }}</td>
                  <td style="width: 30%; text-align: right;">{{ number_format($tp->tarif_realisasi, 2, ',', '.') }}</td>
                  <td style="width: 10%; text-align: right;">{{ number_format($tp->qty) }}</td>
                  <td style="width: 10%; text-align: left;">Rp.</td>
                  <td style="width: 20%; text-align: right;">{{ number_format($total_tarif_qty, 2, ',', '.') }}</td>
                  <td style="width: 20%; text-align: left;"></td>
              </tr>
          @endunless
      @endif
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
    <td style="text-align: left; padding: 10px; width: 60%; font-size: 7px; font-style:italic; font-weight:bold;">Dicetak pada tgl :{{$currentDate}} jam {{$currentTime}} -
      Opr:RINI#255</td>
  </tr>
</table>