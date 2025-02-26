@php
$req = app()->request;

function formatDate($tanggal){
$unixTime = strtotime($tanggal);
$result = date("d/m/Y",$unixTime);
return $result;
}

//get data
$data = \DB::select("
select
tsl.id as spk_lain_id,
tsl.no_spk,
ms.kode as kode_supp,
tsl.t_buku_order_id,
tbo.no_buku_order,
tsl.m_customer_id,
mc.kode as kode_cust,
tsl.lokasi_stuffing,
tsl.no_container,
tbodn.no_prefix,
tbodn.no_suffix,
tbodn.ukuran,
mg.deskripsi as ukuran_cont_desc,
tsl.setting_temperatur,
tsl.keluar_lokasi_tanggal,
tsl.keluar_lokasi_jam,
tsl.keluar_lokasi_temperatur,
tsl.tiba_lokasi_tanggal,
tsl.tiba_lokasi_jam,
tsl.tiba_lokasi_temperatur,
tsl.catatan

from t_spk_lain tsl
left join m_supplier ms on ms.id = tsl.genzet
left join t_buku_order tbo on tbo.id = tsl.t_buku_order_id
left join m_customer mc on mc.id = tsl.m_customer_id
left join t_buku_order_d_npwp tbodn on tbodn.id = tsl.no_container
left join set.m_general mg on mg.id = tbodn.ukuran
where tsl.id = ?
",[$req['id']]);

$n = $data[0];

$currentDate = date("d/m/Y");
$currentTime = date("H:i:s");
$tanggal_keluar = formatDate($n->keluar_lokasi_tanggal);
$tanggal_tiba = formatDate($n->tiba_lokasi_tanggal);


//detail spk lain-lain
$detailSPKLain = \DB::select("
select
tsld.sektor,
mg.deskripsi as sektor_desc
from
t_spk_lain_d tsld
left join set.m_general as mg on mg.id = tsld.sektor
where tsld.t_spk_lain_id = ?
",[$n->spk_lain_id]);


//get sektor
$sektorArr = [];
foreach($detailSPKLain as $dsl){
$sektorArr[] = $dsl->sektor_desc ?? '-';
}
$sektor=implode(', ',$sektorArr);



@endphp


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>SPK LAIN-LAIN</title>
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
      font-size: 13px;
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
  <section class="sheet padding-3mm" style="padding-right: 32px; padding-top: 21px; page-break-after: always;">
    <table style="width:100%;">
      <!-- <p style="text-align: center;">SPK LAIN-LAIN</p> -->
      <!-- <thead>
        <tr>
          <th colspan="2" class="underline-2"
            style="text-decoration: underline; font-weight: bold; height:20px; text-align:left;">SPK LAIN-LAIN
          </th>
          <th colspan="2" style="text-align:right;">
            aaaaaa
          </th>
        </tr>
      </thead> -->
      <tbody>
        <tr>
          <td style="">No. SPK</td>
          <td colspan="3">: &nbsp;&nbsp;{{$n->no_spk??'-'}}</td>
        </tr>
        <tr>
          <td style="font-weight: normal">Genzet</td>
          <td colspan="3" style="padding-top: 3px;">: &nbsp;&nbsp;{{$n->kode_supp??'-'}}</td>
        </tr>
        <tr>
          <td style="">Order No.</td>
          <td colspan="2" style="padding-top: 3px;">: &nbsp;&nbsp;{{$n->no_buku_order ?? '-'}}</td>
          <td colspan="2" style="padding-top: 3px; padding-left: 3%;">Exp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;{{$n->kode_cust
            ?? '-'}}</td>
        </tr>
      </tbody>
      <tr>
        <td style="padding-top: 3%;"></td>
      </tr>
      <tbody>
        <tr>
          <td style="">Lokasi Stuffing</td>
          <td colspan="4" style="line-height: 150%;">: &nbsp;&nbsp;{{$n->lokasi_stuffing ?? '-'}}</td>
        </tr>
        <tr>
          <td style="">Sektor</td>
          <td colspan="4" style="">: &nbsp;&nbsp;{{$sektor}}</td>
        </tr>
        <tr>
          <td style="padding-top: 3%;"></td>
        </tr>
        <tr>
          <td colspan="3" style="">Cont. No.
            <span style="padding-left: 6%;">:</span>
            <span style="border-bottom: 0.5px dashed black; margin-left: 3%;">{{$n->no_prefix??'-'}}-{{$n->no_suffix??'-'}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          </td>
          <td colspan="2" style="padding-left: 3%;">Ukuran
            <span>:</span>
            <span style="margin-left: 3%; border-bottom: 0.5px dashed black;">{{$n->ukuran_cont_desc ?? '-'}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          </td>
        </tr>
        <tr>
          <td style="padding-top: 1%;"></td>
        </tr>
        <tr>
          <td colspan="4" style="padding-left: 23.5%;">Setting Temp.
            <span style="padding-left: 5%;">:</span>
            <span style="margin-left: 3%; border-bottom: 0.5px dashed black;">{{$n->setting_temperatur}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          </td>
        </tr>
        <tr>
          <td colspan="4" style="padding-left: 23.5%;">Ventilation
            <span style="padding-left: 14.75%;">:</span>
            <span style="margin-left: 3%; border-bottom: 0.5px dashed black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          </td>
        </tr>
        <tr>
          <td style="padding-top: 2%;"></td>
        </tr>
        <tr>
          <td style="">Keluar Lok.</td>
          <td colspan="3" style="">:
            <span style="padding-left: 3%;">Tanggal</span>
            <span style="padding-left: 2%;">:</span>
            <span style="border-bottom: 0.5px dashed black;">{{$tanggal_keluar??'-'}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          </td>
        </tr>
        <tr>
          <td colspan="5" style="padding-left: 24%;">Jam
            <span style="padding-left: 9.75%;">:
              <strong style="border-bottom: 0.5px dashed black; font-weight: normal;">{{$n->keluar_lokasi_jam??'-'}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </span>
            <span style="font-weight: normal; padding-left: 5%;">Temp.
              <strong style="font-weight: normal; margin-left: 2%;">:</strong>
            </span>
            <span style="border-bottom: 0.5px dashed black; margin-left: 2%;">{{$n->keluar_lokasi_temperatur??'-'}}&nbsp;&nbsp;&nbsp;</span>
          </td>
        </tr>
        <tr>
          <td style="padding-top: 2%;"></td>
        </tr>
        <tr>
          <td style="">Tiba di TPS</td>
          <td colspan="3" style="">:
            <span style="padding-left: 3%;">Tanggal</span>
            <span style="padding-left: 2%;">:</span>
            <span style="border-bottom: 0.5px dashed black;">{{$tanggal_tiba??'-'}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          </td>
        </tr>
        <tr>
          <td colspan="5" style="padding-left: 24%;">Jam
            <span style="padding-left: 9.75%;">:
              <strong style="border-bottom: 0.5px dashed black; font-weight: normal;">{{$n->tiba_lokasi_jam??'-'}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </span>
            <span style="font-weight: normal; padding-left: 5%;">Temp.
              <strong style="font-weight: normal; margin-left: 2%;">:</strong>
            </span>
            <span style="border-bottom: 0.5px dashed black; margin-left: 2%;">{{$n->tiba_lokasi_temperatur??'-'}}&nbsp;&nbsp;&nbsp;</span>
          </td>
        </tr>
        <tr>
          <td style="padding-top: 2%;"></td>
        </tr>
        <tr>
          <td>Keterangan</td>
          <td colspan="4">:
            <span style="border-bottom: 0.5px dashed black; margin-left: 2%;">{{$n->catatan}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </span>
          </td>
        </tr>
        <tr>
          <td style="padding-top: 5%;"></td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: center;">Mengetahui,</td>
          <td colspan="3" style="padding-left: 15%; text-align: center;">Tanda Tangan Penerima</td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: center;">A/n Pemilik Barang</td>
          <td colspan="3" style="padding-left: 15%; text-align: center;">Genzet</td>
        </tr>
        <tr>
          <td style="padding-top: 10%;"></td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: center;">
            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
          </td>
          <td colspan="3" style="padding-left: 15%; text-align: center;">
            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
          </td>
        </tr>
        <tr>
          <td style="padding-top: 5%;"></td>
        </tr>
        <tr style="width: 100%; font-size: 10px;">
          <td style=" font-weight: bold; border-bottom: 0.5px solid black; white-space: nowrap;">Dicetak pada
            tgl : {{$currentDate}} jam Operator : {{$currentTime}} DEWI-PC # dewi</td>
          <td style="border-bottom: 0.5px solid black;"></td>
          <td style="border-bottom: 0.5px solid black;"></td>
          <td style="border-bottom: 0.5px solid black;"></td>
          <td style="border-bottom: 0.5px solid black;"></td>
        </tr>
      </tbody>
    </table>
  </section>
</body>

</html>