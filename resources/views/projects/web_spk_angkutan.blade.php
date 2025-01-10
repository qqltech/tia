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
    } elseif ($number < 12) {
        $temp = ' ' . $huruf[$number];
    } elseif ($number < 20) {
        $temp = terbilang($number - 10) . ' belas';
    } elseif ($number < 100) {
        $temp = terbilang((int)($number / 10)) . ' puluh' . terbilang($number % 10);
    } elseif ($number < 200) {
        $temp = ' seratus' . terbilang($number - 100);
    } elseif ($number < 1000) {
        $temp = terbilang((int)($number / 100)) . ' ratus' . terbilang($number % 100);
    } elseif ($number < 2000) {
        $temp = ' seribu' . terbilang($number - 1000);
    } elseif ($number < 1000000) {
        $temp = terbilang((int)($number / 1000)) . ' ribu' . terbilang($number % 1000);
    } elseif ($number < 1000000000) {
        $temp = terbilang((int)($number / 1000000)) . ' juta' . terbilang($number % 1000000);
    }

    return $temp;
}
@endphp

@foreach ($nospk as $n)
@php
$unixTime1 = strtotime($n->tanggal_in);
$tanggal_in = date("d/m/Y", $unixTime1);

$unixTime2 = strtotime($n->tanggal_out);
$tanggal_out = date("d/m/Y", $unixTime2);
@endphp

<div class="container">
  <table>
    <tr>
      <th class="underline-2" style="height:30px; text-align:right;">
      </th>
      <th colspan="1" class="underline-2" style="height:30px; text-align:right;">Tanggal In : {{$tanggal_in}}
      </th>
      <th colspan="1" style="text-align:right;">Tanggal Out : {{$tanggal_out}}
      </th>
    </tr>
    <tr>
      <td colspan="3" style="border: 1px solid black; ">
        <table>
          <tr>
            <td style="width: 20%;">Order Angk.</td>
            <td style="width: 2%;">
              <span style="font-weight: normal;">:</span>
            </td>
            <td style="width: 28%; border: none; border-bottom: 1px dashed black;">{{$n->no_spk}}</td>
            <td style="width: 2%;"></td>
            <td style="width: 18%;">Pagi/sore</td>
            <td style="width: 2%;">
              <span style="font-weight: normal;">:</span>
            </td>
            <td style="width: 25%; border: none; border-bottom: 1px dashed black;">{{$n->waktu_in}}</td>
          </tr>
          <tr>
            <td style="width: 20%;">No. Angkutan</td>
            <td style="width: 2%;">
              <span style="font-weight: normal;">:</span>
            </td>
            <td style="width: 28%; border: none; border-bottom: 1px dashed black;">{{$n->head_deskripsi}} Chs-1:
              {{$n->chasis1_deskripsi}}</td>
            <td style="width: 2%;"></td>
            <td style="width: 18%;">Supir</td>
            <td style="width: 2%;">
              <span style="font-weight: normal;">:</span>
            </td>
            <td style="width: 25%; border: none; border-bottom: 1px dashed black;">{{$n->supir_nama}}</td>
          </tr>
          <tr>
            <td style="width: 20%;">Rit</td>
            <td style="width: 2%;">
              <span style="font-weight: normal;">:</span>
            </td>
            <td style="width: 28%; border: none; border-bottom: 1px dashed black;">{{$n->trip_deskripsi}} Chs-2
              {{$n->chasis1_deskripsi}}</td>
            <td style="width: 2%;"></td>
            <td style="width: 18%;">Cont.</td>
            <td style="width: 2%;">
              <span style="font-weight: normal;">:</span>
            </td>
            <td style="width: 20%; border: none; border-bottom: 1px dashed black;">{{$n->ukuran1_deskripsi}}</td>
            <td style="width: 5%; border: none; border-bottom: 1px dashed black;">Ft</td>
          </tr>
          <tr>
            <td style="width: 20%;">Sektor</td>
            <td style="width: 2%;">
              <span style="font-weight: normal;">:</span>
            </td>
            <td style="width: 28%; border: none; border-bottom: 1px dashed black;">{{$n->sektor1_deskripsi}}
              {{$n->sektor2_deskripsi}}</td>
            <td style="width: 2%;"></td>

          </tr>
          <tr>
            <td style="width: 20%;">Dari</td>
            <td style="width: 2%;">
              <span style="font-weight: normal;">:</span>
            </td>
            <td style="width: 28%; border: none; border-bottom: 1px dashed black;">{{$n->dari}}</td>
            <td style="width: 2%;"></td>
            <td style="width: 18%;">Ke</td>
            <td style="width: 2%;">
              <span style="font-weight: normal;">:</span>
            </td>
            <td style="width: 20%; border: none; border-bottom: 1px dashed black;">{{$n->ke}}</td>
          </tr>
          <tr style:"height:2px">
            <td></td>
          </tr>
        </table>
      </td>
    </tr>


    <tr>
      <td colspan="3" style="border: 1px solid black; ">
        <table>
          <tr>
            <td style="width: 20%;"></td>
            <td style="width: 2%;">
              <span style="font-weight: normal;"></span>
            </td>
            <td style="width: 28%; border: none; "></td>
            <td style="width: 2%;"></td>
            <td style="width: 18%;">Sangu.</td>
            <td style="width: 2%;">
              <span style="font-weight: normal;">:</span>
            </td>
            <td style="width: 20%; border: none;">Rp. {{number_format($n->sangu, 0, ',', '.')}}</td>
          </tr>
          <tr style:"height:2px">
            <td></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="3" style="border: 1px solid black; ">
        <table>
          <tr style:"height:2px">
            <td></td>
          </tr>
          <tr>
            <td style="width: 28%;">LAIN-LAIN</td>
            <td style="width: 2%;">
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
      <td colspan="3" style="border: 1px solid black; ">
        <table>
          <tr>
            <td style="width: 28%; border-right: 1px solid black;">Order</td>
            <td style="width: 22%; border-right: 1px solid black;">
              <span style="font-weight: normal;">Exp</span>
              <br>
              <span style="font-weight: normal;">Imp</span>
            </td>
            <td style="width: 25%;border-right: 1px solid black;"><span> Keterangan</span>
            </td>
            <td style="width: 22%;text-align:right;"><span> Jumlah</span>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="3"
        style=" border-left: 1px solid black; border-right: 1px solid black;">
        <table>
          <tr>
            <td style="width: 28%; border-right: 1px solid black;"><span>{{$n->no_buku_order}} </span></td>
            <td style="width: 22%; border-right: 1px solid black; "><span>{{$n->customer_nama_perusahaan}}</span>
            </td>
            <td colspan="2">
              <table>
                @foreach ($nospkd as $nd)
                <tr style="">
                  <td style="width: 50%;border-bottom: 1px dashed black; ">{{$nd->keterangan}}
                  </td>
                  <td style="width: 44%;border-bottom: 1px dashed black; text-align:right;"><span> Rp. {{number_format($nd->nominal, 0, ',', '.')}}</span>
                  </td>
                </tr>
                @endforeach
              </table>
            </td>

          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="3" style="border: 1px solid black; ">
        <table>
          <tr>
            <td style="width: 60%;"></td>
            <td style="width: 12%;">Total &nbsp; : Rp.</td>
            <td style="width: 25%;text-align:right;">{{number_format($n->total_sangu, 0, ',', '.');}}
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="3">
        <table>
          <tr>
            <td style="width: 20%; font-weight:600">Terbilang</td>
          </tr>
          <tr>
            <td style="width: 97%;"># {{ucfirst(trim(terbilang($n->total_sangu)))}} #</td>
          </tr>
          <tr style:"height:2px">
            <td></td>
          </tr>
          <tr>
            <td style="width: 25%;text-align: center;">Mengetahui</td>
            <td style="width: 73%;text-align: center;">Tanda Tangan,</td>
          </tr>
          <br>
          <tr>
            <td style="text-align: center; width: 25%;">Admin / Kasir</td>
            <td style="text-align: center; width: 43%;">Sopir</td>
            <td style="text-align: center; width: 30%;">Pengebon,</td>
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
            <td style="text-align: center; width: 25%;">
              (&nbsp; Kusmiati &nbsp;)
            </td>
            <td style="text-align: center; width: 43%;">
              (&nbsp; {{$n->supir_nama}} &nbsp;)
            </td>
            <td style="text-align: center; width: 30%;">
              (&nbsp; Budi &nbsp;)
            </td>
          </tr>
          <!-- <tr>
            <td style="text-align: center; width: 25%;">Nama Terang</td>
            <td style="text-align: center; width: 43%;">Nama Terang</td>
            <td style="text-align: center; width: 30%;">Nama Terang</td>
          </tr> -->
          <tr>
            <td></td>
          </tr>
          <tr>
            <td></td>
          </tr>
          <tr>
            <td style="width: 97%; font-weight: bold; border: none; border-bottom: 1px solid black;">Dicetak pada tgl :
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