@php
$req = app()->request;
$data = \DB::select("
select * from t_surat_jalan tsj left join t_buku_order tbo on tsj.t_buku_order_id = tbo.id left join m_customer mc on
tbo.m_customer_id = mc.id where tsj.id = ? ",[$req['id']]
);

/*$nospk = \DB::select("select tsj.*, tbo.*, tbodn.*, tsa.*, UPPER(mg.deskripsi) as deskripsi_container, mc.*,
mg2.deskripsi as
deskripsi_jenis from t_surat_jalan tsj
left join t_buku_order tbo on tsj.t_buku_order_id = tbo.id
left join m_customer mc on tbo.m_customer_id = mc.id
left join t_buku_order_d_npwp tbodn on tbodn.t_buku_order_id = tbo.id
left join t_spk_angkutan tsa on tbodn.id = tsa.t_buku_order_1_id
left join \"set\".m_general mg on tsa.isi_container_1 = mg.id
left join \"set\".m_general mg2 on tbodn.jenis = mg2.id
where tsj.id = ? and tsa.status = 'APPROVED'",[$req['id']]);*/

$nospk = \DB::select("SELECT tsj.*, tsj.depo as depo_tsj, UPPER(mg3.deskripsi) as jenis_sj_deskripsi, tbo.*, mc.*,
tbodn.*, mg2.deskripsi as deskripsi_jenis, mg2.deskripsi2 as singkatan_jenis
FROM t_surat_jalan tsj
LEFT JOIN t_buku_order tbo ON tsj.t_buku_order_id = tbo.id
LEFT JOIN m_customer mc ON tbo.m_customer_id = mc.id
LEFT JOIN t_buku_order_d_npwp tbodn ON tbodn.id = tsj.t_buku_order_d_npwp_id
LEFT JOIN \"set\".m_general mg2 ON tbodn.jenis = mg2.id
left join \"set\".m_general mg3 on tsj.jenis_sj = mg3.id
WHERE tsj.id = ?",[$req['id']]);

$count_spk = count($nospk);

$currentDate = date("d/m/Y");
$currentTime = date("H:i:s");

@endphp
@foreach ($nospk as $n)
@php
$unixTime = strtotime($n->tanggal_berangkat);

$tanggal_berangkat = date("d/m/Y", $unixTime);

//cek surat jalan impor atau ekspor
$cekImp = "";
if($n->tipe_surat_jalan == "IMPORT"){
$cekImp = "IMP";
}
else if ($n->tipe_surat_jalan == "EKSPORT"){
$cekImp = "EXP";
}

//cek jenis container
$jeniskontainer = "";
if ($n->jenis_kontainer == "DRY CONTAINER"){
$jeniskontainer = "DC";
}
else if ($n->jenis_kontainer == "FLAT RACK CONTAINER"){
$jeniskontainer = "FRC";
}
else if ($n->jenis_kontainer == "OPEN TOP CONTAINER"){
$jeniskontainer = "OTC";
}
else if ($n->jenis_kontainer == "TUNNEL CONTAINER"){
$jeniskontainer = "TC";
}
else if ($n->jenis_kontainer == "OPEN SIDE CONTAINER"){
$jeniskontainer = "OSC";
}
else if ($n->jenis_kontainer == "REEFER CONTAINER"){
$jeniskontainer = "RR";
}
else if ($n->jenis_kontainer == "INSULATED CONTAINER"){
$jeniskontainer = "IC";
}
else if ($n->jenis_kontainer == "ISO TANK"){
$jeniskontainer = "ISO";
}
else if ($n->jenis_kontainer == "CARGO STORAGE ROLL"){
$jeniskontainer = "CSR";
}
else if ($n->jenis_kontainer == "HALF CONTAINER"){
$jeniskontainer = "HC";
}
else if ($n->jenis_kontainer == "CAR CARRIER"){
$jeniskontainer = "CC";
}
else if ($n->jenis_kontainer == "VENTILATION CONTAINER"){
$jeniskontainer = "VC";
}
else if ($n->jenis_kontainer == "SPECIAL PURPOSE CONTAINER"){
$jeniskontainer = "SPC";
}

//$container = \DB::select("select count('ukuran') as jumlah, ukuran from t_buku_order_d_npwp where
//t_buku_order_d_npwp.t_buku_order_id = ? group by ukuran ",[$n->t_buku_order_id]);

//$str = [];
//$count = 0;
//foreach ($container as $cont) {
//$str[$count] = $cont->jumlah . "x" . $cont->ukuran . "RR";
//$count += 1;
//}
//$format = implode(", ", $str);
@endphp

<!DOCTYPE html>
<html>

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
      font-size: 11px;
      /* font-family: 'Courier New', monospace; */
      font-family: sans-serif;
      /* font-family: monospace; */
      /* font-family: monako; */
      /* font-family: arial; */
    }
  </style>
</head>

<body class="continuous_form">
  <section class="sheet padding-3mm" style="padding-right: 21px; padding-top: 21px;">
    <!-- <pre>{{var_dump($nospk)}}</pre> -->
    <table style="width:100%;">
      <thead>
        <tr>
          <th colspan="2" class="underline-2"
            style="text-decoration: underline; font-weight: bold; height:20px; text-align:left;">SURAT JALAN / PENGANTAR
          </th>
          <th colspan="2" style="text-align:right;">
            {{$n->no_surat_jalan}}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>No. Order</td>
          <td colspan="2">
            <span style="font-weight: normal;">:</span>
            <span style="font-weight: bold;">&nbsp;&nbsp;{{$n->no_buku_order}}</span>
          </td>
        </tr>
        <tr>
          <td>Tgl. Berangkat</td>
          <td colspan="2">
            <span style="font-weight: normal;">:</span>
            <span style="font-weight: bold;">&nbsp;&nbsp;{{$tanggal_berangkat}}</span>
          </td>
        </tr>
        <tr>
          <td>Kepada Yth</td>
          <td colspan="2">
            <span style="font-weight: normal;">:</span>
            <span style="font-weight: normal;">&nbsp;&nbsp;{{$n->nama_perusahaan}}</span>
          </td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td colspan="2">
            <span style="font-weight: normal;">:</span>
            <span style="font-weight: bold;">&nbsp;&nbsp;{{$n->alamat}}</span>
          </td>
        </tr>
        <td>
          <span style="display: inline-block; width: 360px; border-bottom: 0.5px solid black;"></span>
          <span style="display: inline-block; width: 360px; border-bottom: 0.5px solid black;"></span>
        </td>
        <tr>
          <td>Nama Angk.</td>
          <td colspan="3">
            <span style="font-weight: normal; white-space: nowrap;">:</span>
            <span style="display: inline-block; width: 262px; border-bottom: 0.5px solid black;">{{$n->angkutan}}</span>
          </td>
        </tr>
        <tr>
          <td>No. Polisi</td>
          <td colspan="3">
            <span style="font-weight: normal; white-space: nowrap;">:</span>
            <span style="display: inline-block; width: 262px; border-bottom: 0.5px solid black;">&nbsp;</span>
          </td>
        </tr>
        <tr>
          <td>Pelabuhan</td>
          <td colspan="3">
            <span style="font-weight: normal; white-space: nowrap;">:</span>
            <span style="display: inline-block; width: 262px; border-bottom: 0.5px solid black;">{{$n->pelabuhan}}</span>
          </td>
        </tr>
        <tr>
          <td>Depo</td>
          <td colspan="3">
            <span style="font-weight: normal; white-space: nowrap;">:</span>
            <span style="display: inline-block; width: 262px; border-bottom: 0.5px solid black;">{{$n->depo_tsj}}</span>
          </td>
        </tr>
        <tr>
          <td>Kapal</td>
          <td colspan="3">
            <span style="font-weight: normal; white-space: nowrap;">:</span>
            <span style="display: inline-block; width: 262px; border-bottom: 0.5px solid black;">{{$n->kapal}}</span>
          </td>
        </tr>
        <td>
          <span></span>
        </td>
    </table>
    <table border="0" style="width: 100%; border: 0.5px solid black;">
      <thead style="border: 0.5px solid black;">
        <tr>
          <th colspan="4" style="text-align: center; border-right: 0.5px solid black;">
            <span>Keterangan</span>
          </th>
          <th colspan="4" style="text-align: center;">
            Jenis Barang
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="4" style="border-right: 0.5px solid black;"></td>
          <td colspan="4" style="border-left: 0.5px solid black;">
            &nbsp;&nbsp;&nbsp;&nbsp;{{$n->jenis_barang}}
          </td>
        </tr>
        <tr>
          <td>Type Cont</td>
          <td colspan="3">
            <span style="font-weight: normal; white-space: nowrap;">:</span>
            <span style="display: inline-block; width: 125px; border-bottom: 0.5px solid black; font-weight: bold;">1x{{$n->ukuran_kontainer}} {{$n->singkatan_jenis}}</span>
          </td>
          <td colspan="2" style="border-left: 0.5px solid black;"></td>
        </tr>
        <tr>
          <td>No. Cont</td>
          <td colspan="3">
            <span style="font-weight: normal; white-space: nowrap;">:</span>
            <span style="display: inline-block; width: 125px; border-bottom: 0.5px solid black; font-weight: bold;">{{$n->no_prefix}}{{$n->no_suffix}}</span>
          </td>
          <td colspan="2" style="border-left: 0.5px solid black;"></td>
        </tr>
        <tr>
          <td>No. Seal</td>
          <td colspan="3">
            <span style="font-weight: normal; white-space: nowrap;">:</span>
            <span style="display: inline-block; width: 125px; border-bottom: 0.5px solid black; font-weight: bold;"></span>
          </td>
          <td colspan="2" style="border-left: 0.5px solid black;"></td>
        </tr>
        <tr>
          <td style="width: 25%;"></td>
          <td style="width: 3%;"></td>
          <td style="width: 30%; border: none;"></td>
          <td style="width: 2%; border: none;"></td>
          <td style="width: 1%; border-left: 0.5px solid black;"></td>
          <td colspan="2" style="border: 0.5px solid black; text-align: center; font-weight: bold; font-size: 14px; 
           vertical-align: middle; padding: 5px;">
            {{$cekImp}} {{$n->jenis_sj_deskripsi}}
          </td>
        </tr>
        <tr>
          <td style="border-left: 0.5px solid black; border-top: 0.5px solid black;">NW</td>
          <td colspan="3" style="border-top: 0.5px solid black">
            <span style="font-weight: normal;">:</span>
            <span style="display: inline-block; width: 125px; border-bottom: 0.5px solid black; font-weight: bold;">
              {{$n->nw}}
            </span>
          </td>
          <td colspan="2" style="border-left: 0.5px solid black;"></td>
        </tr>
        <tr>
          <td style="border-left: 0.5px solid black;">GW</td>
          <td colspan="3">
            <span style="font-weight: normal;">:</span>
            <span style="display: inline-block; width: 125px; border-bottom: 0.5px solid black; font-weight: bold;">
              {{$n->gw}}
            </span>
          </td>
          <td colspan="2" style="border-left: 0.5px solid black;"></td>
        </tr>
        <tr>
          <td style="border-left: 0.5px solid black;">TARE</td>
          <td colspan="3">
            <span style="font-weight: normal;">:</span>
            <span style="display: inline-block; width: 125px; border-bottom: 0.5px solid black; font-weight: bold;"></span>
          </td>
          <td colspan="2" style="border-left: 0.5px solid black;"></td>
        </tr>
        <tr>
          <td style="border-left: 0.5px solid black; border-bottom: 0.5px solid black;"></td>
          <td style="border-bottom: 0.5px solid black;">
            <span style="font-weight: normal; border-bottom: 0.5px solid black;"></span>
          </td>
          <td style="border: none; border-bottom: 0.5px solid black; font-weight: bold; "></td>
          <td style="border: none; border-right: 0.5px solid black; border-bottom: 0.5px solid black;">
          </td>
          <td style="border: none;"></td>
        </tr>
        <tr>
          <td style="border-bottom: 0.5px solid black;"></td>
          <td style="border-bottom: 0.5px solid black;">
            <span style="font-weight: normal; border-bottom: 0.5px solid black;"></span>
          </td>
          <td style="border: none; border-bottom: 0.5px solid black; font-weight: bold; "></td>
          <td style="border: none; border-bottom: 0.5px solid black;"></td>
          <td style="border: none; border-bottom: 0.5px solid black;">
          </td>
          <td colspan="3" style="border: none; border-bottom: 0.5px solid black;"></td>
        </tr>
    </table>
    <table style="width: 100%">
      <tr>
        <td colspan="3">Barang-barang tersebut diatas harap diterima dengan baik.</td>
      </tr>
      <tr>
        <td style="text-align: center; ">Penerima,</td>
        <td style="text-align: center; ">Pembawa,</td>
        <td style="text-align: center; ">Pengirim,</td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr><tr>
        <td></td>
      </tr><tr>
        <td></td>
      </tr><tr>
        <td></td>
      </tr><tr>
        <td></td>
      </tr><tr>
        <td></td>
      </tr><tr>
        <td></td>
      </tr><tr>
        <td></td>
      </tr><tr>
        <td></td>
      </tr><tr>
        <td></td>
      </tr>
      <tr>
        <td style="text-align: center;">
          (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        </td>
        <td style="text-align: center;">
          (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        </td>
        <td style="text-align: center;">
          (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        </td>
      </tr>
      <tr>
        <td style="text-align: center;">Nama Terang</td>
        <td style="text-align: center;">Nama Terang</td>
        <td style="text-align: center;">Nama Terang</td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr><tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr><tr>
        <td></td>
      </tr><tr>
        <td></td>
      </tr>
      <tr>
        <td colspan="3" style="font-weight: bold; border: none; border-bottom: 0.5px solid black;">Dicetak pada tgl :
          {{$currentDate}}
          jam
          {{$currentTime}} Operator : DEWI-PC # dewi</td>
      </tr>
      </tbody>
    </table>
  </section>
</body>

</html>
@endforeach