<!-- TGL BACKUP 6 Februari 2025 -->

@php
$req = app()->request;

$nospk = \DB::select("SELECT tsa.*,
mg1.deskripsi as chasis1_deskripsi, mg2.deskripsi as chasis2_deskripsi, mg3.deskripsi as ukuran1_deskripsi,
mk.nama as supir_nama,


tbo.no_buku_order as no_buku_order,
mcu.nama_perusahaan as customer_nama_perusahaan,

mg8.deskripsi as head_deskripsi, mg9.deskripsi as trip_deskripsi,




mg8.deskripsi as sektor1_deskripsi, mg9.deskripsi as sektor2_deskripsi


FROM t_spk_angkutan tsa
LEFT JOIN \"set\".m_general mg1 ON tsa.chasis = mg1.id
left join \"set\".m_general mg2 on tsa.chasis2 = mg2.id
left join \"set\".m_kary mk on tsa.supir = mk.id

left join t_buku_order_d_npwp tbod on tsa.t_detail_npwp_container_1_id = tbod.id
left join t_buku_order tbo on tbod.t_buku_order_id = tbo.id
left join m_customer mcu on tbo.m_customer_id = mcu.id

left join \"set\".m_general mg3 on tbod.ukuran = mg3.id
left join \"set\".m_general mg4 on tsa.head = mg4.id
left join \"set\".m_general mg5 on tsa.trip_id = mg5.id


left join \"set\".m_general mg8 on tsa.sektor1 = mg8.id
left join \"set\".m_general mg9 on tsa.sektor2 = mg9.id


WHERE tsa.id = ?", [$req['id']]);


$nospkd = \DB::select("SELECT tsbd.*
from t_spk_bon_detail tsbd
WHERE tsbd.t_spk_angkutan_id = ?", [$req['id']]);


$count_spk = count($nospk);
$currentDate = date("d/m/Y");
$currentTime = date("H:i:s");


// left join t_buku_order tbo on tsa.t_buku_order_1_id = tbo.id

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
  . ' juta' . terbilang($number % 1000000); } return $temp; } @endphp @foreach ($nospk as $n) @php
  $unixTime1=strtotime($n->tanggal_in);
  $tanggal_in = date("d/m/Y", $unixTime1);

  $unixTime2 = strtotime($n->tanggal_out);
  $tanggal_out = date("d/m/Y", $unixTime2);
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
        height: 20px;
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
    <section class="sheet padding-3mm" style="padding-right: 25px;">
      <!-- <pre>{{var_dump($nospk)}}</pre> -->
      <table style="width:100%;">
        &nbsp;
        <tr>
          <th style="width:50%;  font-size: 11px; text-align:left;">Tanggal In : {{$tanggal_in}}
          </th>
          <th style="width:50%; font-size: 11px; text-align:right;">Tanggal Out : {{$tanggal_out}}
          </th>
        </tr>
        <tr>
          <td colspan="2" style="border: 0.5px solid black;">
            <table style="width:99%;font-size:11px">
              <tr>
                <td style="width: 20%; ">Order Angk.</td>
                <td style="width: 4%">:</td>
                <td style="width: 30%; border: none; border-bottom: 0.5px dashed black;">{{$n->no_spk}}</td>
                <td style="width: 9%;"></td>
                <td style="width: 15%;">Pagi/sore</td>
                <td style="width: 4%;">
                  <span style="font-weight: normal;">:</span>
                </td>
                <td style="width: 20%; border: none; border-bottom: 0.5px dashed black;">{{$n->waktu_in}}</td>
              </tr>
              <tr>
                <td style="width: 16%; ">No. Angkutan</td>
                <td style="width: 4%;">
                  <span style="font-weight: normal;">:</span>
                </td>
                <td style="width: 30%; border: none; border-bottom: 0.5px dashed black;">{{$n->head_deskripsi}} Chs-1:
                  {{$n->chasis1_deskripsi}}</td>
                <td style="width: 9%;"></td>
                <td style="width: 15%;">Supir</td>
                <td style="width: 4%;">
                  <span style="font-weight: normal;">:</span>
                </td>
                <td style="width: 20%; border-bottom: 0.5px dashed black;">{{{$n->supir_nama}}}
                </td>
              </tr>
              <tr>
                <td style="width: 16%;">Rit</td>
                <td style="width: 4%;">
                  <span style="font-weight: normal;">:</span>
                </td>
                <td style="width: 30%; border: none; border-bottom: 0.5px dashed black;">{{$n->trip_deskripsi}} Chs-2
                  {{$n->chasis1_deskripsi}}</td>
                <td style="width: 9%;"></td>
                <td style="width: 15%;">Cont.</td>
                <td style="width: 4%;">
                  <span style="font-weight: normal;">:</span>
                </td>
                <td style="width: 20%; border: none; border-bottom: 0.5px dashed black;">{{$n->ukuran1_deskripsi}} Ft
                </td>
              </tr>
              <tr>
                <td style="width: 16%;">Sektor</td>
                <td style="width: 4%;">
                  <span style="font-weight: normal;">:</span>
                </td>
                <td style="width: 30%; border: none; border-bottom: 0.5px dashed black;">{{$n->sektor1_deskripsi}}
                  {{$n->sektor2_deskripsi}}</td>
                <td style="width: 4%;"></td>
              </tr>
              <tr>
                <td style="width: 16%;">Dari</td>
                <td style="width: 4%;">
                  <span style="font-weight: normal;">:</span>
                </td>
                <td style="width: 30%; border: none; border-bottom: 0.5px dashed black;">{{@$n->dari}}</td>
                <td style="width: 9%;"></td>
                <td style="width: 15%;">Ke</td>
                <td style="width: 4%;">
                  <span style="font-weight: normal;">:</span>
                </td>
                <td style="width: 20%; border: none; border-bottom: 0.5px dashed black;">{{@$n->ke}}</td>
              </tr>
              <tr style:"height:2px">
                <td></td>
              </tr>
            </table>
          </td>
        </tr>


        <tr>
          <td colspan="3" style="border: 0.5px solid black; ">
            <table>
              <tr>
                <td style="width: 20%;"></td>
                <td style="width: 2%;">
                  <span style="font-weight: normal;"></span>
                </td>
                <td style="width: 35%; border: none; "></td>
                <td style="width: 15%;">Sangu&nbsp;&nbsp;&nbsp;:</td>
                <td style="width: 20%; border: none;">Rp. {{number_format($n->sangu, 0, ',', '.')}}
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="3" style="border: 0.5px solid black; ">
            <table>
              <tr>
                <td style="width: 25%;">LAIN-LAIN</td>
                <td style="width: 4%;">
                  <span style="font-weight: normal;">:</span>
                </td>
                <td style="width: 70%;">{{$n->catatan}}</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="3" style="border: 0.5px solid black;">
          </td>
        <tr>
          <td colspan="3"
            style="border-left: 0.5px solid black; border-right: 0.5px solid black; border-bottom: 0.5px solid black; padding: 4px;">
            <table cellspacing="0" cellpadding="3" border="0.5"
              style="width: 100%; border-collapse: collapse; font-size: 10px;">
              <tr>
                <th style="width: 25%; text-align: center; border: 0.5px solid black;">Order</th>
                <th style="width: 35%; text-align: center; border: 0.5px solid black;">Exp / Imp</th>
                <th style="width: 20%; text-align: center; border: 0.5px solid black;">Keterangan</th>
                <th style="width: 20%; text-align: center; border: 0.5px solid black;">Jumlah</th>
              </tr>
              @foreach ($nospkd as $nd)
              <tr>
                <td style="width: 25%; text-align: center; border: 0.5px solid black;">{{$n->no_buku_order}}</td>
                <td style="width: 35%; text-align: center; border: 0.5px solid black;">
                  {{$n->customer_nama_perusahaan}}
                </td>
                <td style="width: 20%; text-align: center; border: 0.5px solid black;">{{$nd->keterangan}}</td>
                <td style="width: 20%; text-align: center; border: 0.5px solid black;">Rp {{number_format($nd->nominal,
                  0, ',', '.')}}</td>
              </tr>
              @endforeach
              <tr>
                <td colspan="4"
                  style="border-left: 0.5px solid black; border-bottom: 0.5px solid black; border-right: 0.5px solid black; text-align: right;">
                  Total :
                  <span style="padding: 4px;">Rp {{number_format($n->total_sangu, 0, ',', '.');}}</span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        </tr>
        <tr>
        <td colspan="4">
          <table style="width: 100%; font-size: 11px;">
              <span style=" font-weight:600; font-size: 11px;">Terbilang</span>
              <br>
              <span style="width: 100%; font-size: 11px;"># {{ucfirst(trim(terbilang($n->total_sangu)))}} #</span>
            <tr style="height:2px">
              <td></td>
            </tr>
            <tr>
              <td style="width: 5%; text-align: center;">Admin / Kasir</td>
              <td style="width: 5%; text-align: center;">Sopir</td>
              <td style="width: 5%; text-align: center;">Pengebon,</td>
            </tr>
            <tr>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center; width: 5%;">
                (Kusmiati)
              </td>
              <td style="text-align: center; width: 5%;">
                ({{$n->supir_nama}})
              </td>
              <td style="text-align: center; width: 5%;">
                (Budi)
              </td>
            </tr>
            <tr>
              <td></td>
            </tr>
          </table>
            <tr style="width: 100%; font-size: 10px;">
              <td style="width:100%; font-weight: bold; border-bottom: 0.5px solid black; white-space: nowrap;">Dicetak pada
                tgl : {{$currentDate}} jam {{$currentTime}} Operator : DEWI-PC # dewi</td>
              <td style=" border-bottom: 0.5px solid black; "></td>
            </tr>
        </td>
        </tr>
      </table>
      
    </section>
  </body>

  </html>

  @endforeach