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

  <div class="container" style="font-size: 6px;">
    <table>
      <tr>
        <th class="underline-2" style="height: 14px; text-align:right;">
        </th>
        <th colspan="1" class="underline-2" style="text-align:right;">Tanggal In : {{$tanggal_in}}
        </th>
        <th colspan="1" style="text-align:right;">Tanggal Out : {{$tanggal_out}}
        </th>
      </tr>
      <tr>
        <td colspan="3" style="border: 0.5px solid black; ">
          <table>
            <tr>
              <td style="width: 16%;">Order Angk.</td>
              <td style="width: 4%">
                <span style="font-weight: normal;">:</span>
              </td>
              <td style="width: 30%; border: none; border-bottom: 0.5px dashed black;">{{$n->no_spk}}</td>
              <td style="width: 9%;"></td>
              <td style="width: 15%;">Pagi/sore</td>
              <td style="width: 4%;">
                <span style="font-weight: normal;">:</span>
              </td>
              <td style="width: 20%; border: none; border-bottom: 0.5px dashed black;">{{$n->waktu_in}}</td>
            </tr>
            <tr>
              <td style="width: 16%;">No. Angkutan</td>
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
              <td style="width: 20%; border-bottom: 0.5px dashed black; white-space: nowrap;">{{{$n->supir_nama}}}</td>
            </tr>
            <tr>
              <td style="width: 16%;">Rit</td>
              <td style="width: 3.5%;">
                <span style="font-weight: normal;">:</span>
              </td>
              <td style="width: 30%; border: none; border-bottom: 0.5px dashed black;">{{$n->trip_deskripsi}} Chs-2
                {{$n->chasis1_deskripsi}}</td>
              <td style="width: 9.5%;"></td>
              <td style="width: 15%;">Cont.</td>
              <td style="width: 4%;">
                <span style="font-weight: normal;">:</span>
              </td>
              <td style="width: 20%; border: none; border-bottom: 0.5px dashed black;">{{$n->ukuran1_deskripsi}} Ft</td>
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
              <td style="width: 2%;"></td>
              <td style="width: 15%; line-height:15px">Sangu.</td>
              <td style="width: 4%;">
                <span style="font-weight: normal; line-height:15px;">:</span>
              </td>
              <td style="width: 20%; line-height:15px; border: none;">Rp. {{number_format($n->sangu, 0, ',', '.')}}</td>
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
            <tr style:"height:2px">
              <td></td>
            </tr>
            <tr>
              <td style="width: 16%;">LAIN-LAIN</td>
              <td style="width: 4%;">
                <span style="font-weight: normal;">:</span>
              </td>
              <td style="width: 70%;">{{$n->catatan}}</td>
            </tr>
            <tr style:"height:2px">
              <td></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <th colspan="3"
          style="height: 10px; border-bottom: 0.5px solid black; border-left: 0.5px solid black; border-right: 0.5px solid black;">
        </th>
      </tr>
    <tbody>
      <tr>
        <td style="width: 100%; border-right: 0.5px solid black; border-left: 0.5px solid black; padding: 10px;"></td>
      </tr>
      <tr>
        <td style="width: 100%; padding: 5px; border-right: 0.5px solid black; border-left: 0.5px solid black;">
          <table cellspacing="0" cellpadding="3" border="0.5" style="width: 98%; text-align: center;">
            <tr>
              <th style="width: 20%; text-align: center;">Order</th>
              <th style="width: 22%; text-align: center;">Exp / Imp</th>
              <th style="width: 38%; text-align: center;">Keterangan</th>
              <th style="width: 21%; text-align: center;">Jumlah</th>
            </tr>
            <tr>
              <td style="width: 20%; text-align: center; height: 25px;"></td>
              <td style="width: 22%;"></td>
              <td style="width: 38%;"></td>
              <td style="width: 21%; text-align: center;"></td>
            </tr>
            <tr>
              <td style="width: 101%; text-align: right;"> Total : Rp {{number_format($n->total_sangu, 0, ',', '.');}}</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="border-left: 0.5px solid black; border-right: 0.5px solid black; border-bottom: 0.5px solid black;"></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td colspan="3">
          <table>
            <tr>
              <td style="width: 20%; font-weight:600; line-height: 10px; ">Terbilang</td>
            </tr>
            <tr>
              <td style="width: 97%;"># {{ucfirst(trim(terbilang($n->total_sangu)))}} #</td>
            </tr>
            <tr style:"height:2px">
              <td></td>
            </tr>
            <br>
            <tr>
              <td style="text-align: center; width: 10%;">Admin / Kasir</td>
              <td style="text-align: center; width: 38%;">Sopir</td>
              <td style="text-align: center; width: 10%;">Pengebon,</td>
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
              <td style="text-align: center; width: 10%;">
                (&nbsp; Kusmiati &nbsp;)
              </td>
              <td style="text-align: center; width: 38%;">
                (&nbsp; {{$n->supir_nama}} &nbsp;)
              </td>
              <td style="text-align: center; width: 9%;">
                (&nbsp; Budi &nbsp;)
              </td>
            </tr>
            <tr>
              <td></td>
            </tr>
            <tr>
              <td></td>
            </tr>
            <tr>
              <td style="width: 60%; font-weight: bold; border: none; border-bottom: 0.5px solid black;">Dicetak pada
                tgl :
                {{$currentDate}}
                jam
                {{$currentTime}} Operator : DEWI-PC # dewi</td>
            </tr>
          </table>
        </td>
      </tr>

    </table>
  </div>

  @endforeach