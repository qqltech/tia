@php
$req = app()->request;

$bkkD = \DB::table('t_bkk_d')->where('t_bkk_id', $req['id'])->first();

$bkk = \DB::select('select * from t_bkk where id = ?', [$req['id']]);

if (!$bkkD) {
echo "<p>Data BKK tidak ditemukan.</p>";
return;
}

$b = $bkk[0];


$dataBukuOrder = \DB::table('t_buku_order as tbo')
->selectRaw("
tbo.*,
tbo.id as t_buku_order_id,
tbo.tipe_order,
mg.kode as pelabuhan_nama,
mg2.deskripsi as depo_nama,
mg3.deskripsi as jenis_kontainer,
mg4.deskripsi as ukuran_kontainer_value,
mg5.deskripsi as kode_pelayaran,
mk.nama as petugas_pengkont_nama,
mk2.nama as petugas_pemasukan_nama,
mc.jenis_perusahaan,
mc.nama_perusahaan,
mc.kode as kode_perusahaan,
tbo.no_boking,
tbodn.no_prefix,
tbodn.no_suffix
")
->leftJoin('t_buku_order_d_npwp as tbodn', 'tbodn.t_buku_order_id', '=', 'tbo.id')
->leftJoin('t_buku_order_d_aju as tboda', 'tboda.t_buku_order_id', '=', 'tbo.id')
->leftJoin('set.m_general as mg', 'mg.id', '=', 'tbo.pelabuhan_id')
->leftJoin('set.m_general as mg2', 'mg2.id', '=', 'tbodn.depo')
->leftJoin('set.m_general as mg3', 'mg3.id', '=', 'tbodn.jenis')
->leftJoin('set.m_general as mg4', 'mg4.id', '=', 'tbodn.ukuran')
->leftJoin('set.m_general as mg5', 'mg5.id', '=', 'tbo.kode_pelayaran_id')
->leftJoin('set.m_kary as mk', 'mk.id', '=', 'tbodn.m_petugas_pengkont_id')
->leftJoin('set.m_kary as mk2', 'mk2.id', '=', 'tbodn.m_petugas_pemasukan_id')
->leftJoin('m_customer as mc', 'mc.id', '=', 'tbo.m_customer_id')
->where('tbo.id', $bkkD->t_buku_order_id)
->first();


if (!$dataBukuOrder) {
echo "<p>Data Buku Order tidak ditemukan.</p>";
return;
}

$n = $dataBukuOrder;

$detailBukuOrder = \DB::select(
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

$dl = $detailBukuOrder;

$str = [];
$count = 0;
foreach ($detailBukuOrder as $dbo) {
    $str[$count] = $dbo->jumlah . "x" . $dbo->ukuran_value . ' ' .$dbo->jenis_singkatan_value;
    $count += 1;
}
$format = implode(", ", $str);

$detailBukuOrder2 = \DB::select(
    "select 
        tboda.t_ppjk_id,
        tboda.m_customer_id,
        tboda.tanggal,
        tboda.peb_pib,
        tboda.tanggal_peb_pib,
        tboda.no_sppb
    from t_buku_order_d_aju as tboda
    where tboda.t_buku_order_id = ?
    order by tboda.tanggal asc
", [$n->t_buku_order_id]
);

$dl2 = $detailBukuOrder2;

$currentDate = date("d/m/Y");
$currentTime = date("H:i:s");

function formatDate($date){
$unixTime = strtotime($date);
return date("d/m/Y", $unixTime);
}
@endphp


<div
  style="border: 1px solid black; font-family: Arial, sans-serif; font-size: 10px; color: #000; width: 100%; padding-left: 10px">
  <pre>{{var_dump($dataBukuOrder)}}</pre>
  <!-- HEADER -->
  <table
    style="width: 100%; border-collapse: collapse; font-size: 14px; line-height: 1.2; font-weight: bold; margin-bottom: 4px;">
    <tbody>
      <tr>
        <td style="font-size: 10px;">
          PT. TIA SENTOSA MAKMUR<br>
              JL. PERAK TIMUR NO.236 SURABAYA
        </td>
        <td style="text-align: right; padding: 2px 4px; font-size: 10px;">TANGGAL CETAK : {{$currentDate}}</td>
      </tr>
    </tbody>
  </table>

  <br>
  <br>

  <table
    style="width: 100%; border-collapse: collapse; font-size: 14px; line-height: 1.2; font-weight: bold; margin-bottom: 4px;">
    <tbody>
      <tr style="text-align: center;">
        <td></td>
        <td colspan="3">{{$n->no_buku_order}}</td>
        <td></td>
      </tr>
      <tr style="text-align: center;">
        <td></td>
        <td colspan="3" style=" border-top: 1px solid black;">{{$b->nama_penerima}}</td>
        <td></td>
      </tr>
    </tbody>
  </table>

  <br>
  <br>
  <br>
  <!-- DETAIL EKSPOR -->
  <table style="width: 100%; border-collapse: collapse; font-size: 10px; line-height: 1.2; font-weight:bold;">
    <tbody>
      <tr>
        <td style="padding: 2px; width: 90px;">AJU</td>
        <td style="padding: 2px; width: 10px;">:</td>
        <td style="padding: 2px; width: 100px;">{{$dl2->no_ppjk ?? '-'}}</td>
        <td colspan="3"></td>
        <td style="padding: 2px; width: 30px;">Tgl</td>
        <td style="padding: 2px; width: 10px;">:</td>
        <td style="padding: 2px; width: 80px;">{{formatDate($b->tanggal) }}</td>
      </tr>

      <tr>
        <td style="padding: 2px; width: 90px;">Jml. Kont.</td>
        <td style="padding: 2px; width: 10px;">:</td>
        <td style="padding: 2px; width: 70px;">{{$format ?? '-'}}</td>
        <td colspan="3"></td>
        <td style="padding: 2px; text-align: right; width: 100px;">Tgl. Order</td>
        <td style="padding: 2px; width: 10px;">:</td>
        <td style="padding: 2px; width:80px;">{{formatDate($n->tanggal_invoice ?? '-')}}</td>
      </tr>

      <tr>
        <td style="padding: 2px;">Pelayaran</td>
        <td style="padding: 2px;">:</td>
        <td colspan="2" style="padding: 2px;">{{$n->kode_pelayaran ?? '-'}}</td>
        <td colspan="6"></td>
      </tr>

      <tr>
        <td style="padding: 2px;">Jenis Barang</td>
        <td style="padding: 2px;">:</td>
        <td colspan="4" style="padding: 2px;">{{$n->jenis_barang ?? '-'}}</td>
      </tr>
      <tr>
        <td style="padding: 2px;">Kapal</td>
        <td style="padding: 2px;">:</td>
        <td colspan="2" style="padding: 2px;">{{$n->nama_kapal ?? '-'}}</td>
        <td colspan="2"></td>
        <td style="padding: 2px; text-align: right; width:100px;">Voyage</td>
        <td style="padding: 2px;">:</td>
        <td style="padding: 2px;">{{$n->voyage ?? '-'}}</td>
      </tr>

      <tr>
        <td style="padding: 2px; width:90px;">Dari ke (Tujuan)</td>
        <td style="padding: 2px; width:10px;">:</td>
        <td style="padding: 2px; width:90px;">{{$n->tujuan_asal ?? '-'}}</td>
        <td colspan="2" style="padding: 2px; text-align: center; width:20px;">TO</td>
        <td style="padding: 2px; width:70;">?</td>
        <td colspan="3"></td>
      </tr>

      <tr>
        <td style="padding: 2px; width:90px;">Depo</td>
        <td style="padding: 2px; width:10px;">:</td>
        <td style="padding: 2px; width:120px;">GFI T. OSOWILANGO</td>
        <td colspan="3"></td>
        <td style="padding: 2px; text-align: right;width:100px;">Pelabuhan</td>
        <td style="padding: 2px;">:</td>
        <td style="padding: 2px;">ICT</td>
      </tr>

      <tr>
        <td style="padding: 2px; width:90px;">Peng. Kont.</td>
        <td style="padding: 2px; width:10px;">:</td>
        <td style="padding: 2px; width:200px;">ZAINUL</td>
        <td colspan="3"></td>
        <td style="padding: 2px; text-align: right;width:30px;">Tgl.</td>
        <td style="padding: 2px;">:</td>
        <td style="padding: 2px;">02/01/2025</td>

      </tr>

      <tr>
        <td style="padding: 2px; width: 90px;">Pemasukan</td>
        <td style="padding: 2px; width: 10px;">:</td>
        <td style="padding: 2px; width: 200px;">DONI</td>
        <td colspan="3"></td>
        <td style="padding: 2px; text-align: right;width:30px;">Tgl.</td>
        <td style="padding: 2px; width:10px;">:</td>
        <td style="padding: 2px; width:70px;">03/01/2025</td>
      </tr>
    </tbody>
  </table>

  <br>

  <div style="font-size: 12px">
    <b><u>Keterangan</u></b>
  </div>


  <!-- KETERANGAN -->
  <table style="width: 100%; border-collapse: collapse; font-size: 10px; line-height: 1.2; font-weight:bold;">
    <tbody>
      <tr>
        <td style="padding: 3px; width:120px;">1. COO</td>
        <td style="padding: 3px; width:10px;">:</td>
        <td style="padding: 3px; width:150px;">Tanpa COO</td>
        <td style="padding: 3px; width:90px;">5. OW</td>
        <td style="padding: 3px; width:30px;">:</td>
        <td style="padding: 2px; width:150px;"> === </td>
      </tr>
      <tr>
        <td style="padding: 3px; width:120px;">2. HC</td>
        <td style="padding: 3px; width:10px">:</td>
        <td style="padding: 3px; width:150px;"></td>
        <td style="padding: 3px; width 90px">6. Closing</td>
        <td style="padding: 3px; width:30px;">:</td>
        <td style="padding: 2px; width:150px;"> === </td>
      </tr>
      <tr>
        <td style="padding: 3px; width:120px;">3. Angkutan</td>
        <td style="padding: 3px; width:10px">:</td>
        <td style="padding: 3px; width:150px;">DWP</td>
        <td style="padding: 3px; width:90px">7. STAPEL</td>
        <td style="padding: 3px; width:30px;">:</td>
        <td style="padding: 2px; width:150px;"> 0.0 </td>
      </tr>
      <tr>
        <td style="padding: 3px; width:120px;s">4. GenSet</td>
        <td style="padding: 3px width:10px;">:</td>
        <td style="padding: 3px; width:150px;"></td>
        <td style="padding: 3px; width:90px;">8. Tgl. Stpl.</td>
        <td style="padding: 3px; width:30px;">:</td>
        <td style="padding: 2px; width:150px;"> 02 Jan 25 – 03 Jan 25 </td>
      </tr>
      <tr>
        <td style="padding: 3px; width:120px;" colspan="4">9. Lokasi Stuffing </td>
        <td style="padding: 3px; width:10px;">:</td>
        <td style="padding: 3px; width:150px;">Gempol</td>
      </tr>
      <br>
      <br>
      <br>
      <tr>
        <td style="padding: 3px; padding-left: 10px;" colspan="4">10. Lain-lain :</td>
      </tr>
    </tbody>
  </table>

  <div></div>
  <div></div>

  <!-- TABEL BIAYA -->
  <table style="border: 1px solid black; border-collapse: collapse; width: 100%; font-size: 9px;">
    <thead>
      <tr style="background-color: #f2f2f2;">
        <th style="border: 1px solid black; text-align: center;">Tanggal</th>
        <th style="border: 1px solid black; text-align: center;">Perincian Biaya</th>
        <th style="border: 1px solid black; text-align: center;">Bkk</th>
        <th style="border: 1px solid black; text-align: center;">Nama</th>
        <th style="border: 1px solid black; text-align: center;">No. AC</th>
        <th style="border: 1px solid black; text-align: center;">Jumlah</th>
      </tr>
    </thead>
    <tbody>
      <!-- contoh baris kosong -->
      <tr>
        <td style="border: 1px solid black; text-align: center;">...</td>
        <td style="border: 1px solid black;">...</td>
        <td style="border: 1px solid black; text-align: center;">...</td>
        <td style="border: 1px solid black;">...</td>
        <td style="border: 1px solid black; text-align: center;">...</td>
        <td style="border: 1px solid black; text-align: right;">...</td>
      </tr>
      <tr style="font-weight: bold;">
        <td colspan="1" style="text-align: left;">10. Lain-Lain</td>
        <td colspan="4"
          style="border-top: 1px solid black-top; border-right: 1px solid black-top;border-top: 1px solid black-bottom; text-align: right;">
          Total Biaya</td>
        <td style="border: 1px solid black; text-align: right;">8,775,760.00</td>
      </tr>
    </tbody>
  </table>

  <!-- FOOTER -->

  <table style="width: 100%; border-collapse: collapse; font-size: 10px; line-height: 1.2; font-weight:bold;">
    <div style="display: flex; justify-content: space-between; margin-top: 10px; font-size: 9px;">
      <tbody>
        <tr>
          <td style="width:280px;">ACC :</td>
          <td>TGH :</td>
        </tr>
    </div>
    <tbody>
  </table>
</div>