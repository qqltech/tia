@php
$req = app()->request;
$t_spk_lain = \DB::select("
select
tt.no_buku_order,
tt.no_tagihan,
tt.no_faktur_pajak,
mc.nama_perusahaan,
tt.tgl as tgl_tagihan,
tbo.lokasi_stuffing,
tbo.jenis_barang,
tbo.nama_kapal,
tbo.voyage,
tbo.no_bl,
tbo.tujuan_asal

from t_tagihan tt
left join t_buku_order tbo on tbo.id = tt.no_buku_order
left join m_customer mc on mc.id = tt.customer
where tt.id=?
",[$req['id']]);

$n=$t_spk_lain[0];

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
[$n->no_buku_order, $req['id']]
);

$data=[];
$count = 0;
foreach($noContainer as $nc){
$data[$count] = $nc->no_container;
$count+=1;
}
$resultNoContainer= implode(", ",$data);

function formatDate($date){
$unixTime = strtotime($date);
$date_result = date("d M y", $unixTime);
return $date_result;
}

// query berdasarkan PPN
$generate_total = \DB::select("
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
np.tarif_realisasi AS tarif_realisasi_non_ppn
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


$gt=$generate_total[0];

function terbilang($number) {
$huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
$temp = '';
if ($number == 10) {
$temp = 'sepuluh';
} elseif ($number == 11) {
$temp = 'sebelas';
} elseif ($number < 12) { $temp=$huruf[$number]; } elseif ($number < 20) { $temp=terbilang($number - 10) . ' belas' ; }
  elseif ($number < 100) { $temp=terbilang((int)($number / 10)) . ' puluh ' . terbilang($number % 10); } elseif ($number
  < 200) { $temp='seratus ' . terbilang($number - 100); } elseif ($number < 1000) { $temp=terbilang((int)($number /
  100)) . ' ratus ' . terbilang($number % 100); } elseif ($number < 2000) { $temp='seribu ' . terbilang($number - 1000);
  } elseif ($number < 1000000) { $temp=terbilang((int)($number / 1000)) . ' ribu ' . terbilang($number % 1000); } elseif
  ($number < 1000000000) { $temp=terbilang((int)($number / 1000000)) . ' juta ' . terbilang($number % 1000000); } return
  trim($temp); } $currentDate=date("d/m/Y"); $currentTime=date("H:i:s"); $shown_items=[]; if ($gt->ppn) {
  $total_perhitungan = $gt->total_jasa_cont_ppjk + $gt->tarif;
  } else {
  $total_perhitungan = $gt->total_jasa_cont_ppjk;
  }

  // ambil query DP
  $dp_tagihan = \DB::select("
  SELECT
  t_dp.total_amount,
  t_tag.no_tagihan,
  t_bo.no_buku_order,
  m_customer.id AS customer_id
  FROM t_dp_penjualan AS t_dp
  LEFT JOIN t_buku_order AS t_bo
  ON t_dp.t_buku_order_id = t_bo.id
  LEFT JOIN t_tagihan AS t_tag
  ON t_bo.id = t_tag.no_buku_order
  LEFT JOIN m_customer
  ON t_dp.m_customer_id = m_customer.id
  WHERE t_tag.id = ?;
  ",[$req['id']]);

  $dt=$dp_tagihan[0] ?? null;

  @endphp

  <!-- TITTLE -->
  <!-- <pre>{{var_dump($t_spk_lain)}}</pre> -->
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
  </table>

  <!-- BODY -->
  <table border="1">
    <table>
      <!-- NOTA NO -->
      <br>
      <br>
      <tr>
        <td style="text-align: left; padding: 10px; width: 23% ; font-size:11px ">
          NOTA NO. <br>
    SUDAH TERIMA DARI
        </td>
        <td style="text-align: left; padding: 10px; width: 5% ; ">
          : <br>
      :
        </td>
        <td style="text-align: left; padding: 10px; width: 35% ;font-size:11px; ">
          <b>{{$n->no_tagihan ?? '-'}} </b> <br>
      {{$n->nama_perusahaan??'-'}}
        </td>
        <!-- END NOTA NO -->
        <!-- TANGGAL -->
        <td>
          FAKTUR NO. <br>
      TANGGAL
        </td>
        <td style="text-align: left; padding: 10px; width: 5% ; ">
          : <br>
      :
        </td>
        <td style="font-weight: bold; ">
          {{$n->no_faktur_pajak??'-'}} <br>
      {{formatDate($n->tgl_tagihan ?? '-')}}
        </td>
      </tr>
      <!-- END TANGGAL -->
      <br>
      <tr>
        <td style="text-align: left; padding: 10px; width: 23% ; ">
          LOKASI BONGKAR <br>
    PARTY<br>
    Jenis Barang
        </td>
        <td style="text-align: left; padding: 10px; width: 5% ; ">
          : <br>
      : <br>
      :
        </td>
        <td style="text-align: left; padding: 10px; width: 50% ; ">
          {{$n->lokasi_stuffing??'-'}} <br>
          <b>{{$format}}</b> <br>
      {{$n->jenis_barang??'-'}}
        </td>
      </tr>
      <tr>
        <td style="text-align: left; padding: 10px; width: 23% ; ">
          EX KAPAL <br>
    DARI
        </td>
        <td style="text-align: left; padding: 10px; width: 5% ; ">
          : <br>
      :
        </td>
        <td style="text-align: left; padding: 10px; width: 35% ; ">
          {{$n->nama_kapal??'-'}}<br>
      {{$n->tujuan_asal??'-'}}
        </td>
        <td td style="text-align: left; padding: 10px; width: 17% ; ">
          VOY
        </td>
        <td td style="text-align: left; padding: 10px; width: 5% ; ">
          :
        </td>
        <td style="text-align: left; padding: 10px; width: 20% ; ">
          {{$n->voyage??'-'}}
        </td>
      </tr>
      <tr>
        <td style="text-align: left; padding: 10px; width: 23% ; ">
          B/L NO. <br>
    CONT.NO
        </td>
        <td style="text-align: left; padding: 10px; width: 5% ; ">
          : <br>
      :
        </td>
        <td style="text-align: left; padding: 10px; width: 50% ; ">
          {{$n->no_bl??'-'}} <br>
      {{$resultNoContainer}}
        </td>
      </tr>
    </table>

    <table>
      <tr>
        <td border="1" style="width:5%; text-align: center;">No.</td>
        <td border="1" style="width:35%; text-align: center;">Keterangan</td>
        <td border="1" colspan="2" style="width:60%; text-align: center;">Jumlah</td>
      </tr>
      <tr>

        <td border="1" style="text-align: center;">1.</td>
        <td border="1">Total Jasa Cont + PPJK</td>
        <td style="width:20%; border-left: 1px solid black;"> Rp.</td>
        <td style="width:40%; border-bottom: 1px solid black; text-align: right;">
          {{number_format($gt->total_jasa_cont_ppjk, 2, ',', '.')}}
        </td>
      </tr>

      <!-- <tr>
    <td style="width:2%;">-</td>
    <td style="width:60%;">Jasa EMKL Clearance Doc. Fee All in Cont Pertama</td>
    <td style="width:5%;">Rp.</td>
    <td style="border-style: solid; border-width: 0px 1px 1px 1px; width:28%; text-align: right;">
      500.000,00
    </td>
  </tr>

  <tr>
    <td style="width:2%;">-</td>
    <td style="width:60%;">Jasa EMKL Clearance Doc. Fee All in Cont ke II dst</td>
    <td style="width:5%;">Rp.</td>
    <td style="border-style: solid; border-width: 0px 1px 1px 1px; width:28%; text-align: right;">
      0,00
    </td>
  </tr>

  <tr>
    <td style="width:2%;">-</td>
    <td style="width:60%;">Jasa PPJK</td>
    <td style="width:5%;">Rp.</td>
    <td style="border-style: solid; border-width: 0px 1px 1px 1px; width:28%; text-align: right;">
      250.000,00
    </td>
  </tr> -->

      <!-- <tr>
    <td style="width:2%;">-</td>
    <td style="width:60%;">Jasa BEHANDLE</td>
    <td style="width:5%;">Rp.</td>
    <td style="border-style: solid; border-width: 0px 1px 1px 1px; width:28%; text-align: right;">
     2.750.000,00
    </td>
  </tr>

  <tr>
    <td style="width:2%;">-</td>
    <td style="width:60%;">BIAYA PELABUHAN</td>
    <td style="width:5%;">Rp.</td>
    <td style="border-style: solid; border-width: 0px 1px 1px 1px; width:28%; text-align: right;">
     2.886.400,00
    </td>
  </tr> -->

      <tr>
        <td border="1" style="text-align: center;">2.</td>
        <td border="1">Total Lain-lain (PPN)</td>
        <td border="1" style="width:60%; border-bottom: 1px solid black"></td>
        <td></td>
        <td></td>
      </tr>
      @php $subIndex = 1; @endphp
      @foreach ($generate_total as $gt)
      @if(!is_null($gt->is_ppn))
      <!-- Pastikan hanya menampilkan data dengan is_ppn TRUE -->
      @unless(in_array($gt->keterangan_lain, $shown_items))
      @php
      $main = 2; $sub = 1;
      $subtotal = $gt->tarif_realisasi * $gt->qty;
      $total_perhitungan += $subtotal; // Tambahkan subtotal ke total keseluruhan
      $shown_items[] = $gt->keterangan_lain;
      @endphp
      <tr>
        <td colspan="3">
          <table style="width:100%;">
            <tr>
              <td style="width:4%; text-align: center;">2.{{ $subIndex++ }}</td>
              <td border="1" style="width:35.2%;">{{ $gt->keterangan_lain }}</td>
              <td style="width:10%; border-top: 1px solid black; border-left: 1px solid black"> Rp. </td>
              <td style="width:50.7%; border-bottom: 1px solid black; text-align: right; ">
                {{ number_format($subtotal, 2, ',', '.') }}
              </td>
            </tr>
          </table>
        </td>
      </tr>
      @endunless
      @endif
      @endforeach


      @if ($gt->ppn)
      <tr>
        <td style="width:2%;">-</td>
        <td style="width:60%;">Total Jasa Tia (ANGK)</td>
        <td style="width:5%;"> Rp.</td>
        <td style="border-style: solid; border-width: 0px 1px 1px 1px; width:28%; text-align: right;">
          {{ number_format($gt->tarif, 2, ',', '.') }}
        </td>
      </tr>
      @endif

      <tr>
        <td border="1" colspan="2" style="text-align: center;">Jumlah</td>
        <td style="width:5%; border-top: 1px solid black; border-left: 1px solid black"> Rp.</td>
        <td style="width:55%; border-bottom: 1px solid black; border-top: 1px solid black;  text-align: right; ">
          {{ number_format($total_perhitungan, 2, ',', '.') }}
        </td>
      </tr>

      <tr>
        <td border="1" colspan="2" style="text-align: center;"> PPN </td>
        <td style="width:5%; border-top: 1px solid black; border-left: 1px solid black"> Rp.</td>
        <td style="width:55%; border-bottom: 1px solid black; border-top: 1px solid black;  text-align: right; ">
          {{ number_format($gt->total_ppn, 2, ',', '.') }}
        </td>
      </tr>

      @php
      $grand_total_ppn = $total_perhitungan + $gt->total_ppn;
      @endphp
      <tr>
        <td border="1" colspan="2" style="text-align: center;"> TOTAL </td>
        <td style="width:5%; border-top: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;"> Rp.</td>
        <td style="width:55%; border-bottom: 1px solid black; border-top: 1px solid black;  text-align: right; ">
          {{ number_format($grand_total_ppn, 2, ',', '.') }}
        </td>
      </tr>

      <!-- <tr>
    <td style="text-align:right; padding: 10px; width: 95% ; ">
      Hormat Kami
    </td>
  </tr> -->
      <br>
      <tr>
        <td style="text-align: left; padding: 10px; width: 15% ; ">
          TERBILANG
        </td>
        <td style="text-align: left; padding: 10px; width: 3% ; ">
          :
        </td>
        <td
          style="text-align: left; padding: 10px; width: 60% ; font-style:italic; font-weight:bold;text-decoration: underline; ">
          {{ucfirst(trim(terbilang($grand_total_ppn)))}} rupiah
        </td>
        <td style="text-align:right; padding: 10px; width: 17%;">
          Hormat Kami
        </td>
      </tr>
      <br>
      <br>
      <tr>
        <td
          style="text-align: left; padding: 10px; width: 60%;font-size: 7px; font-style:italic; font-weight:bold;text-decoration: underline; ">
          IMP-1 Dicetak pada tanggal:{{$currentDate}} jam {{$currentTime}} - Opr:UMI#25
        </td>
        <td style="text-align: right; padding: 10px; width: 35% ; font-size: 10px; ">
          (Arie Sutandio)
        </td>
      </tr>
    </table>
  </table>

  <hr>
  <!-- Lampiran Biaya  -->
  <table>
    <tr>
      <td
        style="text-align: left; text-align: center; padding: 10px; width: 100%; font-weight: bold; text-decoration: underline; ">
        LAMPIRAN BIAYA (REIMBURSED)
      </td>
    </tr>
  </table>

  <br><br>

  <table style="font-weight: bold;">
    <tr>
      <td style="text-align: left; padding: 10px; width: 23% ; ">
        TANGGAL <br>
      EX Nota
      </td>
      <td style="text-align: left; padding: 10px; width: 3% ; ">
        : <br>
      :
      </td>
      <td style="text-align: left; padding: 10px; width: 30% ; ">
        {{formatDate($n->tgl_tagihan ?? '-')}} <br>
      {{ $gt->no_buku_order }}
      </td>
      <td style="text-align: left; padding: 10px; width: 15%; font-size: 10px ;">
        EX No. FP
      </td>
      <td style="text-align: left; padding: 10px; width: 5%;">
        :
      </td>
      <td style="text-align: left; font-size: 10px; width: 20%;">
        010.004-24.458929
      </td>
    </tr>
  </table>

  <table border="1" cellspacing="0" cellpadding="10" style="font-size: 10px; width: 80%; border-collapse: collapse;">
    <tr style="text-align: center; font-weight:bold;">
      <th style="width: 30%;">DESCRIPTION</th>
      <th style="width: 10%;">Qty</th>
      <th style="width: 30%;">Amount</th>
      <th style="width: 30%;">Total</th>
      <th></th>
    </tr>

    @php
    $total_lain_non_ppn = 0;
    $total_jasa_non_ppn = 0;
    $shown_items = [];
    $hasData = false;
    @endphp

    @foreach($generate_total as $gt)
    @if(is_null($gt->is_ppn))
    <!-- Pastikan is_ppn bernilai NULL -->
    @unless(in_array($gt->keterangan_lain, $shown_items))
    <!-- Cek apakah sudah pernah ditampilkan -->
    @php
    $total_lain_non_ppn = $gt->qty * $gt->tarif_realisasi; // Hitung total
    // Menambahkan keterangan_lain atau 'Tidak Ada Keterangan' ke dalam array $shown_items
    $shown_items[] = $gt->keterangan_lain;
    $hasData = true;
    @endphp
    <tr>
      <td style="text-align: left;">
        {{ $gt->keterangan_lain }}
        <!-- Menampilkan keterangan_lain atau teks jika NULL -->
      </td>
      <td style="text-align: center;">{{ $gt->qty }}</td>
      <td style="text-align: right;">Rp. {{ number_format($gt->tarif_realisasi, 2, ',', '.') }}</td>
      <td style="text-align: right;">Rp. {{ number_format($total_lain_non_ppn, 2, ',', '.') }}</td>
      <td></td>
    </tr>
    @endunless
    @endif
    @endforeach


    @foreach($generate_total as $gt)
    @if(is_null($gt->ppn))
    @unless(in_array($gt->catatan, $shown_items))
    @php
    $qty = 1;
    $total_jasa_non_ppn = $qty * $gt->tarif;
    $shown_items[] = $gt->catatan;
    $hasData = true;
    @endphp
    <tr>
      <td style="text-align: left;">{{ $gt->catatan }}</td>
      <td style="text-align: center;">{{ $qty }}</td>
      <td style="text-align: right;">Rp. {{ number_format($gt->tarif, 2, ',', '.') }}</td>
      <td style="text-align: right;">Rp. {{ number_format($total_jasa_non_ppn, 2, ',', '.') }}</td>
      <td></td>
    </tr>
    @endunless
    @endif
    @endforeach

    @if (!$hasData)
    <tr>
      <td style="text-align: left; font-style: italic;">-</td>
      <td style="text-align: center; font-style: italic;">-</td>
      <td style="text-align: right;">-</td>
      <td style="text-align: right; font-style: italic;">-</td>
      <td></td>
    </tr>
    @endif
  </table>


  @php
  $total_rp = $grand_total_ppn + $total_lain_non_ppn + $total_jasa_non_ppn;
  @endphp
  <table cellpadding="3">
    <tr>
      <td
        style="border-style: solid; border-width: 0px 1px 1px 1px; width:50.5%; text-align: right; font-weight: Bold;">
        TOTAL RP.
      </td>
      <td
        style="border-style: solid; border-width: 0px 1px 1px 1px; width:29.5%; text-align: right; font-weight: Bold;">
        {{ number_format($total_rp, 2, ',', '.') }}
      </td>
      <td style="left; text-align: right; width:15%"> Mengetahui :</td>
    </tr>
  </table>

  <table cellpadding="3">
    <tr>
      <td
        style="border-style: solid; border-width: 0px 1px 1px 1px; width:50.5%; text-align: right; font-weight: Bold;">
        Uang Muka Tanggal RP.
      </td>
      <td
        style="border-style: solid; border-width: 0px 1px 1px 1px; width:29.5%; text-align: right; font-weight: Bold;">
        {{ number_format($dt->total_amount ?? 0, 2, ',', '.') }}
      </td>
    </tr>
  </table>

  <table cellpadding="3">
    @php
    $kurang_bayar = $total_rp - $dt->total_amount ?? 0;
    @endphp
    <tr>
      <td
        style="border-style: solid; border-width: 0px 1px 1px 1px; width:50.5%; text-align: right; font-weight: Bold;">
        KURANG - BAYAR RP.
      </td>
      <td
        style="border-style: solid; border-width: 0px 1px 1px 1px; width:29.5%; text-align: right; font-weight: Bold;">
        {{ number_format($kurang_bayar, 2, ',', '.') }}</td>
      <td style="left; text-align: right; width:15%"> (..................)</td>
    </tr>
  </table>

  <table>
    <tr>
      <td style="text-align: left; padding: 10px; width: 15% ; font-style:italic; ">
        TERBILANG
      </td>
      <td style="text-align: left; padding: 10px; width: 3% ; ">
        :
      </td>
      <td
        style="text-align: left; padding: 10px; width: 60% ; font-style:italic; text-decoration: underline; font-weight: bold;">
        {{ucfirst(trim(terbilang($kurang_bayar)))}} rupiah
      </td>
    </tr>
  </table>

  <br>
  <br>
  <table style="font-weight: bold;">
    <tr>
      <td>
        Pembayaran ditransfer ke Rek. : <br>
      BCA.<br>
      A/N : ARIE SUTANDIO <br>
      A/C : 187.099.1009
      </td>
    </tr>

    <tr>
      <td style="text-align: left; padding: 10px; width: 60%;font-size: 7px; font-style:italic; font-weight:bold; ">
        IMP-1 Dicetak pada tanggal:{{$currentDate}} jam {{$currentTime}} - Opr:UMI#25
      </td>
    </tr>
  </table>