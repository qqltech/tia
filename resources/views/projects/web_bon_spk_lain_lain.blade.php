<!DOCTYPE html>

@php
$req = app()->request;
$dataBonSpkLain = \DB::select("
select 
tbsl.no_bsg, 
tbsl.t_spk_lain_lain_id, 
tsl.no_spk, 
tbsl.tanggal as tanggal_bon_spk,
tsl.genzet, 
ms.nama as nama_genzet, 
tbo.no_buku_order, 
tbo.tipe_order,
tbo.id as buku_order_id, 
mk.nama as nama_operator,
mc.kode as kode_customer,
mc.id as cust_id,
tbsl.total_bon

from t_bon_spk_lain as tbsl
left join t_spk_lain as tsl on tsl.id = tbsl.t_spk_lain_lain_id
left join m_supplier as ms on ms.id = tsl.genzet
left join t_buku_order as tbo on tbo.id = tsl.t_buku_order_id
left join set.m_kary as mk on mk.id = tbsl.operator
left join m_customer as mc on mc.id = tsl.m_customer_id
where tbsl.id = ?
", [$req['id']]);

$n = $dataBonSpkLain[0];

$sektor = \DB::select("
select 
tbodn.sektor,
mg.kode as sektor_kode,
mg.deskripsi as sektor_deskripsi
from t_buku_order_d_npwp as tbodn
left join set.m_general as mg on mg.id = tbodn.sektor
where tbodn.t_buku_order_id = ?
",[$n->buku_order_id]);

$sektor_kode_arr = [];
$sektor_deskripsi_arr=[];
$count = 0;
foreach ($sektor as $sek) {
    $sektor_kode_arr[$count] = $sek->sektor_kode??'-';
    $sektor_deskripsi_arr[$count] = $sek->sektor_deskripsi??'-';
    $count += 1;
}
$sektor_kode_result = implode(", ", $sektor_kode_arr);
$sektor_deskripsi_result = implode(", ", $sektor_deskripsi_arr);

$currentDate = date("d/m/Y");
$currentTime = date("H:i:s");

function formatDate($date){
  $unixTime = strtotime($date);
  $date_result = date("d/m/Y", $unixTime);
  return $date_result;
}

function formatRupiah($angka) {
    if (!is_numeric($angka)) {
        return '-';
    }
    return 'Rp ' . number_format($angka, 2, ',', '.');
}

function terbilang($number) {
$huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
$temp = '';

if ($number == 10) {
$temp = ' sepuluh';
} elseif ($number == 11) {
$temp = ' sebelas';
} elseif ($number < 12) { $temp=' ' . $huruf[$number]; } elseif ($number < 20) { $temp=terbilang($number - 10)
  . ' belas' ; } elseif ($number < 100) { $temp=terbilang((int)($number / 10)) . ' puluh' . terbilang($number % 10); }
  elseif ($number < 200) { $temp=' seratus' . terbilang($number - 100); } elseif ($number < 1000) {
  $temp=terbilang((int)($number / 100)) . ' ratus' . terbilang($number % 100); } elseif ($number < 2000) {
  $temp=' seribu' . terbilang($number - 1000); } elseif ($number < 1000000) { $temp=terbilang((int)($number / 1000))
  . ' ribu' . terbilang($number % 1000); } elseif ($number < 1000000000) { $temp=terbilang((int)($number / 1000000))
  . ' juta' . terbilang($number % 1000000); } return $temp; } 
@endphp

<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>BON SPK LAIN-LAIN</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>
    ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
    }

    ul li {
      position: relative;
      padding-left: 15px;
    }

    ul li::before {
      content: "-";
      position: absolute;
      left: 0;
    }

    @page {
      margin: 0;
    }

    body {
      margin: 0;
    }

    .sheet {
      margin: 0;
      overflow: hidden;
      position: relative;
      box-sizing: border-box;
      page-break-after: always;
    }

    /** Paper sizes **/
    body.A3 .sheet {
      width: 297mm;
      height: 419mm;
    }

    body.A3.landscape .sheet {
      width: 420mm;
      height: 296mm;
    }

    body.A4 .sheet {
      width: 210mm;
      height: 296mm;
    }

    body.A4.landscape .sheet {
      width: 297mm;
      height: 209mm;
    }

    body.A5 .sheet {
      width: 148mm;
      height: 209mm;
    }

    body.A5.landscape .sheet {
      width: 210mm;
      height: 147mm;
    }

    body.continuous_form .sheet {
      width: 105mm;
      height: 135mm;
    }

    body.continuous_form.landscape .sheet {
      width: 105mm;
      height: 135mm;
    }

    /** Padding area **/
    .sheet.padding-3mm {
      padding: 3mm;
    }

    .sheet.padding-5mm {
      padding: 5mm;
    }

    .sheet.padding-7mm {
      padding: 7mm;
    }

    .sheet.padding-10mm {
      padding: 10mm;
    }

    .sheet.padding-15mm {
      padding: 15mm;
    }

    .sheet.padding-20mm {
      padding: 20mm;
    }

    .sheet.padding-25mm {
      padding: 25mm;
    }

    /** For screen preview **/
    @media screen {
      body {
        background: #e0e0e0;
      }

      .sheet {
        background: white;
        box-shadow: 0 0.5mm 2mm rgba(0, 0, 0, 0.3);
        margin: 5mm;
      }
    }

    /** Fix for Chrome issue #273306 **/
    @media print {
      body.A3.landscape {
        width: 420mm;
      }

      body.A3,
      body.A4.landscape {
        width: 297mm;
      }

      body.A4,
      body.A5.landscape {
        width: 210mm;
      }

      body.A5 {
        width: 148mm;
      }

      body.continuous_form {
        width: 105mm;
      }

      body.continuous_form.landscape {
        width: 135mm;
      }
    }

    @page {
      size: continuous_form;
    }

    table {
      border-collapse: collapse;
    }

    table,
    td,
    th {
      /* border: 0.5px solid black; */
      /*  padding-top: 0px;  */
    }

    tr {
      height: 0px;
    }

    table {
      table-layout: fixed;
      font-size: 12px;
      /* font-family: 'Courier New', monospace; */
      font-family: sans-serif;
      /* font-family: monospace; */
      /* font-family: monako; */
      /* font-family: arial; */
    }
  </style>
</head>

<body class="continuous_form">
  <!-- Lembar 1 -->
  <!-- <pre>{{var_dump($sektor)}}</pre> -->
  <section class="sheet padding-3mm" style="padding-right: 32px; padding-top: 21px; page-break-after: always;">
    <table style="width:100%;">
      &nbsp;
      <tr>
        <th
          style="width:100%; font-weight: bold; text-decoration: underline; text-align: center; border: 0.5px solid black; border-bottom: none;">
          BON SANGU GZ TIA</th>
      </tr>
      <tr>
        <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
      </tr>
      <tr>
        <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
      </tr>
      <tr>
        <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
      </tr>
      <tr>
        <td colspan="2" style="border: 0.5px solid black; border-top: none;">
          <table style="width:100%;">
            <tr>
              <td style="width: 25%; ">No. BSG</td>
              <td style="width: 3%">:</td>
              <td style="width: 40%;">{{@$n->no_bsg??'-'}}</td>
              <td style="width: 8%;">SPK</td>
              <td style="width: 3%;">
                <span style="font-weight: normal;">:</span>
              </td>
              <td style="width: 18%;">{{@$n->no_spk??'-'}}</td>
            </tr>
            <tr>
              <td>Tanggal</td>
              <td>:</td>
              <td>{{@formatDate($n->tanggal_bon_spk)??'-'}}</td>
              <td style="width: 10%;"></td>
            </tr>
            <tr>
              <td>No. Gz</td>
              <td>:</td>
              <td>{{@$n->nama_genzet??'-'}}</td>
            </tr>
            <tr>
              <td>Operator</td>
              <td>:</td>
              <td>{{@$n->nama_operator??'-'}}</td>
            </tr>
            <tr>
              <td>No. Order</td>
              <td>:</td>
              <td>{{@$n->no_buku_order??'-'}}</td>
            </tr>
            <tr>
              <td>Exp / Imp</td>
              <td>:</td>
              <td>{{@$n->tipe_order??'-'}}</td>
            </tr>
            <tr>
              <td>Shipper</td>
              <td>:</td>
              <td colspan="2">{{@$n->kode_customer??'-'}}</td>
            </tr>
            <tr>
              <td>Kode Lokasi</td>
              <td>:</td>
              <td colspan="4">{{$sektor_kode_result}}</td>
            </tr>
            <tr>
              <td>Nama Lokasi</td>
              <td>:</td>
              <td colspan="4">{{$sektor_deskripsi_result}}</td>
            </tr>
            <tr>
              <td></td>
              <td>:</td>
              <td>0.00 Jam</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="border: 0.5px solid black; border-top: none;">
          <table style="width:100%;">
            <tr>
              <td style="width: 25%; ">Sangu</td>
              <td style="width: 3%">:</td>
              <td style="width: 30%;">{{@formatRupiah($n->total_bon)}}</td>
              <td style="width: 18%;"></td>
              <td style="width: 3%;">
                <span style="font-weight: normal;"></span>
              </td>
              <td style="width: 18%;"></td>
            </tr>
            <tr>
              <td style="font-weight: bold;">Tarif Kenaikan</td>
              <td></td>
              <td style="font-weight: bold;">Sangu</td>
              <td style="width: 10%;"></td>
            </tr>
            <tr>
              <td>Solar</td>
              <td>:</td>
              <td>Rp 0.00</td>
            </tr>
            <tr>
              <td>Bon Solar</td>
              <td>:</td>
              <td>Rp 0</td>
              <td colspan="2">Uang Makan :</td>
              <td>Rp 0</td>
              <td></td>
            </tr>
            <tr>
              <td>Pelanggan</td>
              <td>:</td>
              <td colspan="2">TS01-TIA SENTOSA</td>
            </tr>
            <tr>
              <td colspan="4" style="font-size: 10px;">Terbilang : # {{@terbilang($n->total_bon)}} rupiah #</td>
            </tr>
            
          </table>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="border: 0.5px solid black; border-top: none;">
          <table style="width:100%;">
            <tr>
              <td style="width: 30%;">Mengetahui</td>
              <td style="width: 33.3%; text-align: center;">Mengetahui</td>
              <td style="width: 35%; text-align: center;">Tanda Tangan</td>
            </tr>
            <tr>
              <td style="width: 30%;">Admin / Kasir</td>
              <td style="width: 33.3%; text-align: center;">Pimp. Genzet</td>
              <td style="width: 35%; text-align: center;">Pengebon</td>
            </tr>
            <tr>
              <td style="width: 30%;">&nbsp;</td>
              <td style="width: 33.3%; text-align: center;">&nbsp;</td>
              <td style="width: 35%; text-align: center;">&nbsp;</td>
            </tr>
            <tr>
              <td style="width: 30%;">&nbsp;</td>
              <td style="width: 33.3%; text-align: center;">&nbsp;</td>
              <td style="width: 35%; text-align: center;">&nbsp;</td>
            </tr>
            <tr>
              <td style="width: 30%;">&nbsp;</td>
              <td style="width: 33.3%; text-align: center;">&nbsp;</td>
              <td style="width: 35%; text-align: center;">&nbsp;</td>
            </tr>
            <tr>
              <td style="width: 30%;">(&nbsp;&nbsp;Kusmiati&nbsp;&nbsp;)</td>
              <td style="width: 33.3%; text-align: center;">(&nbsp;&nbsp;Charles&nbsp;&nbsp;)</td>
              <td style="width: 35%; text-align: center;">(&nbsp;&nbsp;Suprapto&nbsp;&nbsp;)</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>
          <tr>
            <td style="padding-top: 3%;"></td>
          </tr>
          <tr>
              <td colspan="4" style="font-style: italic; font-size: 10px;">Dicetak pada tanggal : {{$currentDate}} jam {{$currentTime}}</td>
            </tr>
        </td>
      </tr>
    </table>
  </section>
</body>

</html>