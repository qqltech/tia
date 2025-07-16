@php
$req = app()->request;
$userId = floatval(base64_decode($req->user));
$user = \DB::select('select
*
from default_users as du
where du.id = ?
', [$userId]);
$userValue = $user[0];

$dataBkm = \DB::select('select
tb.*,
tbo.no_buku_order
from t_bkm as tb
left join t_buku_order tbo on tbo.id = tb.t_buku_order_id
where tb.id = ?
',[$req['id']]);

$detailBkm = \DB::select('select
*, 
tbd.nominal as tbd_nominal,
tbd.keterangan as tbd_keterangan,
mc.nama_coa,
mc.nomor as nomor_coa
from t_bkm_d tbd
left join m_coa as mc on mc.id = tbd.m_coa_id
where tbd.t_bkm_id = ?
', [$req['id']]);

$n = $dataBkm[0];
$numbering = 1;
$total_nominal = 0;
foreach($detailBkm as $db){
  $total_nominal+=floatval($db->nominal);
}


function formatDate($date){
  $unixTime = strtotime($date);
  $date_result = date("d/m/Y", $unixTime);
  return $date_result;
}

$currentDate = date("d/m/Y");
$currentTime = date("H:i:s");

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
    . ' juta' . terbilang($number % 1000000); } 
    return $temp; 
  }
@endphp
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- <title>DOT MATRIX</title> -->
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
      height: 9px;
    }

    table {
      table-layout: fixed;
      font-size: 10px;
      /* font-family: 'Courier New', monospace; */
      font-family: sans-serif;
      /* font-family: monospace; */
      /* font-family: monako; */
      /* font-family: arial; */
    }
  </style>
</head>

<body class="continuous_form">
  <section class="sheet padding-3mm" style="padding-right: 30px;">
    <table style="width: 100%; font-size: 12px;">
        <tr>
          <!-- {{var_dump($n)}} -->
          <td style="text-align: center; font-family: sans-serif; font-weight: bold; padding: 2%;">B K M</td>
        </tr>
      </table>
<!-- Tabel 1 -->
    <table style="width:100%; border: 0.5px solid black; line-height: 1.5;">
      <tr>
        <td style="width: 60%;">No. Bukti
          <span style="margin-left: 3.5%;">:</span>
          <span style="margin-left: 5%; font-weight: bold; text-decoration: underline;">{{$n->no_bkm}}</span>
        </td>
        <td style="width: 40%;">Tanggal
          <span style="margin-left: 7.3%;">:</span>
          <span style="margin-left: 5%;">{{formatDate($n->tanggal)}}</span>
        </td>
      </tr>
      <tr>
        <td style="width: 60%%;">No. Order
          <span style="margin-left: 2%;">:</span>
          <span style="margin-left: 5%;">{{$n->no_buku_order}}</span>
        </td>
        <td style="width: 40%;">Penerima
          <span style="margin-left: 2%;">:</span>
          <span style="margin-left: 5%;">{{$n->nama_penerima??'-'}}</span>
        </td>
      </tr>
      <tr>
        <td></td>
      </tr>
    </table>
<!-- Tabel 2 -->
    <table>
      <tr>
        <td colspan="3"
          style="border-left: 0.5px solid black; border-right: 0.5px solid black; border-bottom: 0.5px solid black; padding: 4px;">
          <table cellspacing="0" cellpadding="3" border="0.5"
            style="width: 100%; border-collapse: collapse; font-size: 8px;">
            <tr>
              <th style="width: 5%; text-align: center; border: 0.5px solid black;">No.</th>
              <th style="width: 20%; text-align: center; border: 0.5px solid black;">Kode Akun</th>
              <th style="width: 25%; text-align: center; border: 0.5px solid black;">Nama Akun</th>
              <th style="width: 22%; text-align: center; border: 0.5px solid black;">Keterangan</th>
              <th style="width: 28%; text-align: center; border: 0.5px solid black;">Nominal</th>
            </tr>
            @foreach($detailBkm as $db)
            <tr>
              <td style="width: 5%; text-align: center; border: 0.5px solid black;">{{$numbering++}}</td>
              <td style="width: 20%; text-align: center; border: 0.5px solid black;">{{$db->nomor_coa}}</td>
              <td style="width: 25%; text-align: center; border: 0.5px solid black;">{{$db->nama_coa}}</td>
              <td style="width: 22%; text-align: center; border: 0.5px solid black;">{{$db->tbd_keterangan??'-'}}</td>
              <td style="width: 28%; text-align: center; border: 0.5px solid black;">Rp {{number_format($db->tbd_nominal,2,',','.')}}</td>
            </tr>
            @endforeach
          </table>
 <!-- Tabel 3 -->
          <table style="width: 100%; border: 0.5px solid black; border-top: none;">
            <tr>
              <td style="width: 5%;"></td>
              <td style="width: 18%;"></td>
              <td style="width: 25%; padding-left: 8%; font-weight: bold;">TOTAL&nbsp; :</td>
              <td style="width: 32%; padding-left: 2%; text-align: center; font-weight: bold;">Rp {{number_format($total_nominal,2,',','.')}}</td>
              <td style="width: 21%;"></td>
            </tr>
          </table>
<!-- Tabel 4 -->
          <table style="width: 100%; border: 0.5px solid black; border-top: none;">
            <tr>
              <td style="width: 21%; padding-left: 1%;">Terbilang &nbsp; :</td>
              <td style="text-decoration: underline; font-style: italic;">{{terbilang($total_nominal)}}rupiah</td>
            </tr>
          </table>
          <table>
          <tr>
            <td></td>
          </tr>
          </table>
<!-- Tabel 5 -->
          <table style="width: 100%; border: 0.5px solid black;">
            <tr>
              <td style="text-align: center; border: 0.5px solid black;">Mengetahui</td>
              <td style="text-align: center; border: 0.5px solid black;">Admin / Kasir</td>
              <td style="text-align: center; border: 0.5px solid black;">Penerima</td>
            </tr>
            <tr>
              <td style="border: 0.5px solid black; border-bottom: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none;"></td>
            </tr>
            <tr>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
            </tr>
            <tr>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
            </tr>
            <tr>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
            </tr>
            <tr>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
            </tr>
            <tr>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
            </tr>
            <tr>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
            </tr>
            <tr>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
            </tr>
            <tr>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none;"></td>
            </tr>
            <tr>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none; text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;)</td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none; text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kasir&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
              <td style="border: 0.5px solid black; border-bottom: none; border-top: none; text-align: center;">( <span>{{$n->nama_penerima??'-'}}</span> )</td>
            </tr>
          </table>
          <tr>
            <td></td>
          </tr>
          <tr>
            <td></td>
          </tr>
          <tr>
            <td></td>
          </tr>
          <tr style="width: 100%; font-size: 10px;">
              <td style="width:100%; font-weight: bold; border-bottom: 0.5px solid black; white-space: nowrap;">Dicetak pada
              tgl : {{$currentDate}} &nbsp; jam {{$currentTime}} &nbsp; Operator : {{$userValue->name}} # {{$userValue->username}}</td>
            <td style=" border-bottom: 0.5px solid black; "></td>
          </tr>
        </td>
      </tr>
    </table>
  </section>
</body>