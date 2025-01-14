@php
$req = app()->request;
$dataBukuOrder = \DB::select("select * ,tbo.id as t_buku_order_id, tbo.tipe_order,
	mg.kode pelabuhan_nama, mg2.deskripsi depo_nama, mg4.deskripsi as ukuran_kontainer_value,
  mg3.deskripsi as jenis_kontainer,mk.nama as petugas_pengkont_nama,
	mk2.nama as petugas_pemasukan_nama, mc.jenis_perusahaan,
	mc.nama_perusahaan, mc.kode as kode_perusahan, mg5.deskripsi as kode_pelayaran, tbo.no_boking, tbodn.no_prefix, tbodn.no_suffix
from t_buku_order tbo
left join t_buku_order_d_npwp tbodn on tbodn.t_buku_order_id = tbo.id
left join set.m_general mg on mg.id = tbo.pelabuhan_id
LEFT JOIN set.m_general mg2 on mg2.id = tbodn.depo
left join set.m_general mg3 on mg3.id = tbodn.jenis 
left join set.m_general mg4 on mg4.id = tbodn.ukuran
left join set.m_general mg5 on mg5.id = tbo.kode_pelayaran_id
left join set.m_kary mk on mk.id = tbodn.m_petugas_pengkont_id 
left join set.m_kary mk2 on mk2.id = tbodn.m_petugas_pemasukan_id 
left join m_customer mc on mc.id = tbo.m_customer_id
where tbo.id = ? ", [$req['id']]);

$n = $dataBukuOrder[0];

$detailBukuOrder = \DB::select(
  "select 
  mg1.deskripsi as jenis_value,
  mg2.deskripsi as ukuran_value,
  mg1.deskripsi2 as jenis_singkatan_value,
  COUNT(tbodn.ukuran) AS jumlah, 
  tbodn.ukuran
  from t_buku_order_d_npwp as tbodn
  left join set.m_general mg1 on mg1.id = tbodn.jenis 
  left join set.m_general mg2 on mg2.id = tbodn.ukuran
  where tbodn.t_buku_order_id = ?
  GROUP BY 
    tbodn.ukuran, 
    mg1.deskripsi,
    mg1.deskripsi2, 
    mg2.deskripsi
  Order by tbodn.ukuran asc
  ", [$n->t_buku_order_id]
);

$detailBukuOrder2 = \DB::select(
  "select 
  tbodn.no_prefix, 
  tbodn.no_suffix,
  mg1.deskripsi as jenis_value,
  mg2.deskripsi as ukuran_value,
  mg1.deskripsi2 as jenis_singkatan_value,
  COUNT(tbodn.ukuran) AS jumlah, 
  tbodn.ukuran
  from t_buku_order_d_npwp as tbodn
  left join set.m_general mg1 on mg1.id = tbodn.jenis 
  left join set.m_general mg2 on mg2.id = tbodn.ukuran 
  where tbodn.t_buku_order_id = ?
  GROUP BY 
    tbodn.no_prefix, 
    tbodn.no_suffix, 
    tbodn.ukuran, 
    mg1.deskripsi,
    mg1.deskripsi2, 
    mg2.deskripsi
  Order by tbodn.ukuran asc
  ", [$n->t_buku_order_id]
);


$str = [];
$count = 0;
foreach ($detailBukuOrder as $dbo) {
    $str[$count] = $dbo->jumlah . "x" . $dbo->ukuran_value . ' ' .$dbo->jenis_singkatan_value;
    $count += 1;
}
$format = implode(", ", $str);


$currentDate = date("d/m/Y");
$currentTime = date("H:i:s");

function formatDate($date){
  $unixTime = strtotime($date);
  $date_result = date("d/m/Y", $unixTime);
  return $date_result;
}

@endphp
<div class="container">
  <!-- <pre>{{var_dump($detailBukuOrder)}}</pre> -->
  <!-- <pre>{{$format}}</pre> -->
  

  <table style="width: 100%">
      <tr>
          <td style="width: 50%; vertical-align: top;">
              PT. TIA SENTOSA MAKMUR<br>
              JL. PERAK TIMUR NO.236 -SURABAYA
          </td>
          <td style="width: 50%; vertical-align: top; text-align: right">
              Tanggal Cetak : {{$currentDate}}
          </td>
      </tr>
  </table>
  <br>
          <h1 style="color: #333; margin-left: 40px; text-align: center;">
              <u>{{$n->no_buku_order}}</u><br> {{$n->kode_perusahan}}, {{$n->jenis_perusahaan}} {{$n->nama_perusahaan}}
          </h1>
  <br>
  <table style="width: 100%">
      <tr>
          <td style="vertical-align: top; width: 50%; font-size: larger; font-weight: bold;"> AJU :
          </td>
          <td style="vertical-align: top; width: 50%; font-size: larger; font-weight: bold;">
              Tgl : 
          </td>
      </tr>
  </table>
  <table style="border-collapse: collapse; width: 100%">
      <tr>
          <td style="vertical-align: top;" width="20%" >Jml. Kontainer</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="35%">{{$format}}</td>

          <td style="vertical-align: top; text-align: right;" width="21%" >Tgl. Order</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="20%">{{formatDate($n->tgl)}}</td>
      </tr>
      <tr>
          <td style="vertical-align: top;" width="20%" >Pelayaran</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="43%">{{$n->kode_pelayaran}}</td>
      </tr>
      <tr>
          <td style="vertical-align: top;" width="20%" >Jenis Barang</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="43%">{{$n->jenis_barang}}</td>

          <td style="vertical-align: top; text-align: right;" width="13%" >ETD/ETA</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <!-- <td style="vertical-align: top;" width="20%">{{$n->tgl_etd_eta}}</td> -->
          <td style="vertical-align: top;" width="43%">{{ empty($n->tgl_etd_eta) || $n->tgl_etd_eta == '1970-01-01' ? '-' : formatDate($n->tgl_etd_eta) }}</td>
      </tr>
      <tr>
          <td style="vertical-align: top;" width="20%" >Kapal</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="43%">{{$n->nama_kapal}}</td>

          <td style="vertical-align: top; text-align: right;" width="13%" >Voyage</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="20%">{{$n->voyage}}</td>
      </tr>
      <tr>
          <td style="vertical-align: top;" width="20%" >Dari ke ( Tujuan )</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="43%" colspan:4>{{$n->tujuan_asal}}</td>
      </tr>
      <tr>
          <td style="vertical-align: top;" width="20%" >Closing Cont. Tgl.</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <!-- <td style="vertical-align: top;" width="43%">{{formatDate($n->tanggal_closing_cont)}}</td> -->
          <td style="vertical-align: top;" width="43%">{{ empty($n->tanggal_closing_cont) || $n->tanggal_closing_cont == '1970-01-01' ? '-' : formatDate($n->tanggal_closing_cont) }}</td>

          <td style="vertical-align: top; text-align: right;" width="13%" >Jam</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="20%">{{$n->jam_closing_cont}}</td>
      </tr>
      <tr>
          <td style="vertical-align: top;" width="20%" >Closing Doc Tgl.</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <!-- <td style="vertical-align: top;" width="43%">{{formatDate($n->tanggal_closing_doc)}}</td> -->
          <td style="vertical-align: top;" width="43%">{{ empty($n->tanggal_closing_doc) || $n->tanggal_closing_doc == '1970-01-01' ? '-' : formatDate($n->tanggal_closing_doc) }}</td>

          <td style="vertical-align: top; text-align: right;" width="13%" >Jam</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="20%">{{$n->jam_closing_doc}}</td>
      </tr>
      <tr>
          <td style="vertical-align: top;" width="20%" >NO. BL</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="43%">{{$n->no_bl}}</td>

          <td style="vertical-align: top; text-align: right;" width="13%" >Tgl</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="20%">{{formatDate($n->tanggal_bl)}}</td>
      </tr>
      <tr>
          <td style="vertical-align: top;" width="20%" >SI / Booking No.</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="43%">{{$n->no_boking}}</td>
      </tr>
      <tr>
          <td style="vertical-align: top;" width="20%" >No. Invoice</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="43%">{{$n->no_invoice}}</td>

          <td style="vertical-align: top; text-align: right;" width="13%" >Tgl</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="20%">{{formatDate($n->tanggal_invoice)}}</td>
      </tr>
      <tr>
          <td style="vertical-align: top;" width="20%" >Pelabuhan</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="43%">{{$n->pelabuhan_nama}}</td>

          <td style="vertical-align: top; text-align: right;" width="13%" >Depo</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="20%">{{$n->depo_nama}}</td>
      </tr>
  </table>
  <br>
  <table style="width: 100%">
      <tr>
          <td style="width: 50%; font-size: larger; font-weight: bold;">
            <p>No. Pend PEB/PIB :</p>
          </td>
          <td style="width: 50%; font-size: larger; font-weight: bold;">
            <p>Tgl : </p>
          </td>
      </tr>
  </table>
  <table style="border-collapse: collapse; width: 100%">
      <!-- <tr>
          <td style="vertical-align: top;" width="15%" >BPBP</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="33%">-</td>

          <td style="vertical-align: top;" width="5%" >Tgl.</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="43%">24/10/2024</td>
      </tr> -->
      <tr>
    <td style="vertical-align: top;" width="15%">Peng. Kont.</td>
    <td style="vertical-align: top;" width="2%">:</td>
    <td style="vertical-align: top;" width="33%">{{ $n->petugas_pengkont_nama }}</td>

    <td style="vertical-align: top;" width="5%">Tgl.</td>
    <td style="vertical-align: top;" width="2%">:</td>
    <td style="vertical-align: top;" width="43%">{{ empty($n->tanggal_pengkont) || $n->tanggal_pengkont == '1970-01-01' ? '-' : formatDate($n->tanggal_pengkont) }}</td>
</tr>

      <tr>
          <td style="vertical-align: top;" width="15%" >Pemasukan</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="33%">{{$n->petugas_pemasukan_nama}}</td>

          <td style="vertical-align: top;" width="5%" >Tgl.</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <!-- <td style="vertical-align: top;" width="43%">{{formatDate($n->tanggal_pemasukan)}}</td> -->
          <td style="vertical-align: top;" width="43%">{{ empty($n->tanggal_pemasukan) || $n->tanggal_pemasukan == '1970-01-01' ? '-' : formatDate($n->tanggal_pemasukan) }}</td>
      </tr>
  </table>
  <br>
  <table style="border-collapse: collapse; width: 100%">
    <tr>
        <td colspan="8">
            <p><u>Keterangan</u></p>
        </td>
    </tr>
      <tr>
          <td style="vertical-align: top; text-align: center;" width="5%">1.</td>
          <td style="vertical-align: top;" width="15%" >COO</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="30%">{{$n->coo}}</td>

          <td style="vertical-align: top; text-align: center;" width="5%">6.</td>
          <td style="vertical-align: top;" width="15%" >Overweight</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="30%">-</td>
      </tr>
      <tr>
          <td style="text-align: center;" width="5%">2.</td>
          <td style="vertical-align: top;" width="15%" >HC</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="30%">{{$n->hc}}</td>

          <td style="vertical-align: top; text-align: center;" width="5%">7.</td>
          <td style="vertical-align: top;" width="15%" >Closing</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="30%">-</td>
      </tr>
      <tr>
          <td style="vertical-align: top; text-align: center;" width="5%">3.</td>
          <td style="vertical-align: top;" width="15%" >Angkutan</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="30%">{{$n->angkutan}}</td>

          <td style="vertical-align: top; text-align: center;" width="5%">8.</td>
          <td style="vertical-align: top;" width="15%" >STAPEL</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="30%">.... Hari</td>
      </tr>
      <tr>
          <td style="vertical-align: top; text-align: center;" width="5%">4.</td>
          <td style="vertical-align: top;" width="15%" >Genset</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="30%">{{$n->genzet}}</td>

          <td style="vertical-align: top; text-align: center;" width="5%">9.</td>
          <td style="vertical-align: top;" width="15%" >Tgl. Stempel</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="30%">-</td>
      </tr>
      <tr>
          <td style="vertical-align: top; text-align: center;" width="5%">5.</td>
          <td style="vertical-align: top;" width="15%" >Lokasi Stuffing</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top;" width="30%">
              {{$n->lokasi_stuffing}}
          </td>

          <td style="vertical-align: top; text-align: center;vertical-align: top;" width="5%">10.</td>
          <td style="vertical-align: top;" width="15%" >Lain-lain</td>
          <td style="vertical-align: top;" width="2%">:</td>
          <td style="vertical-align: top; text-align: left;" width="30%">
          </td>
      </tr>
  </table>
  <p></p>
  <table style="vertical-align: top; border-collapse: collapse; width: 100%">
    <tr>
        <!-- Bagian Tabel Kiri -->
        <td style="vertical-align: top;" width="40%">
            <table style="border: 0.5px solid black; border-collapse: collapse; width: 100%">
                <tr>
                    <td style="vertical-align: top; border: 0.5px solid black; text-align: center; font-weight: bold; font-size: 10pt;" width="13%">No.</td>
                    <td style="vertical-align: top; border: 0.5px solid black; font-weight: bold; font-size: 10pt" width="42%">Tipe Container</td>
                    <td style="vertical-align: top; border: 0.5px solid black; font-weight: bold; font-size: 10pt" width="45%">Detail</td>
                </tr>
                @foreach ($detailBukuOrder2 as $index => $dbo)
                <tr>
                    <td style="vertical-align: top; border: 0.5px solid black; text-align: center; font-size: 10pt" width="13%">{{ $index + 1 }}.</td>
                    <td style="vertical-align: top; border: 0.5px solid black; font-size: 10pt" width="42%">
                        {{ $dbo->ukuran_value }} {{ $dbo->jenis_singkatan_value }}
                    </td>
                    <td style="vertical-align: top; border: 0.5px solid black; font-size: 10pt" width="45%">
                        {{ $dbo->no_prefix }} {{ $dbo->no_suffix }}
                    </td>
                </tr>
                @endforeach
            </table>
        </td>

        <!-- Ruang Kosong -->
        <td width="10%"></td>

        <!-- Bagian Tabel Kanan -->
        <td style="vertical-align: top;" width="50%">
            <table style="border: 0.5px solid black; border-collapse: collapse; width: 100%">
                <tr>
                    <td style="vertical-align: top; border: 0.5px solid black; text-align: center; font-weight: bold; font-size: 10pt;" width="20%">PPJK</td>
                    <td style="vertical-align: top; border: 0.5px solid black; text-align: center; font-weight: bold; font-size: 10pt;" width="20%">Faktur Pajak</td>
                    <td style="vertical-align: top; border: 0.5px solid black; text-align: center; font-weight: bold; font-size: 10pt;" width="20%">Kwitansi Angkutan</td>
                    <td style="vertical-align: top; border: 0.5px solid black; text-align: center; font-weight: bold; font-size: 10pt;" width="20%">Tagihan</td>
                    <td style="vertical-align: top; border: 0.5px solid black; text-align: center; font-weight: bold; font-size: 10pt;" width="20%">Scan</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; border: 0.5px solid black; text-align: center; font-size: 10pt;" width="20%" height="75px"></td>
                    <td style="vertical-align: top; border: 0.5px solid black; text-align: center; font-size: 10pt;" width="20%" height="75px"></td>
                    <td style="vertical-align: top; border: 0.5px solid black; text-align: center; font-size: 10pt;" width="20%" height="75px"></td>
                    <td style="vertical-align: top; border: 0.5px solid black; text-align: center; font-size: 10pt;" width="20%" height="75px"></td>
                    <td style="vertical-align: top; border: 0.5px solid black; text-align: center; font-size: 10pt;" width="20%" height="75px"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>



</div>
