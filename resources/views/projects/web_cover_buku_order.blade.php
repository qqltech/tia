@php
$req = app()->request;

// Normalisasi 'id' parameter: support id[]=1&id[]=2 OR id=1,2
$rawIds = $req->query('id');
$ids = [];

if (is_array($rawIds)) {
    $ids = $rawIds;
} elseif (is_string($rawIds) && trim($rawIds) !== '') {
    $ids = array_filter(array_map('trim', explode(',', $rawIds)));
}

$ids = array_values(array_filter($ids, function($v) { return is_numeric($v); }));
$ids = array_map('intval', $ids);

if (empty($ids)) {
    echo '<p>No valid id provided</p>';
    return;
}

function formatDate($date){
  if (!$date) return '-';
  $unixTime = strtotime($date);
  if ($unixTime === false || $unixTime <= 0) return '-';
  return date("d/m/Y", $unixTime);
}

$currentDateGlobal = date("d/m/Y");
$currentTimeGlobal = date("H:i:s");
@endphp

<style>
/* Styling mirip web_biaya_tagihan_p untuk menghasilkan lembar putih terpisah */
html, body {
    margin: 0;
    padding: 0;
    background: #efefef;
    font-family: Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
}
.page-wrapper {
    padding: 24px;
    box-sizing: border-box;
}
.print-sheet {
    width: 800px;
    max-width: 95%;
    margin: 0 auto 28px;
    background: #ffffff;
    border: 1px solid #000;
    padding: 18px;
    box-sizing: border-box;
    box-shadow: 0 6px 18px rgba(0,0,0,0.18);
    color: #000;
    font-size: 10px;
    page-break-inside: avoid;
    break-inside: avoid;
    page-break-after: always;
    break-after: page;
}
.print-sheet:last-child {
    page-break-after: auto;
    break-after: auto;
    margin-bottom: 0;
}
.print-sheet table { background: transparent; }
@media print {
    html, body { background: #ffffff !important; }
    .page-wrapper { padding: 0; }
    .print-sheet {
        box-shadow: none !important;
        background: #ffffff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        margin: 0 0 12mm 0;
        width: auto;
        max-width: 100%;
        border: 1px solid #000;
        page-break-inside: avoid;
        page-break-after: always;
        break-after: page;
    }
    .print-sheet:last-child {
        page-break-after: auto;
        break-after: auto;
        margin-bottom: 0;
    }
}
</style>

<div class="page-wrapper">
@foreach($ids as $id)
    @php
    // Ambil data utama untuk id
    $dataBukuOrder = \DB::select("select * ,tbo.id as t_buku_order_id, tbo.tipe_order,
        mg.kode pelabuhan_nama, mg2.deskripsi depo_nama, mg4.deskripsi as ukuran_kontainer_value,
        mg3.deskripsi as jenis_kontainer,mk.nama as petugas_pengkont_nama,
        mk2.nama as petugas_pemasukan_nama, mc.jenis_perusahaan,
        mc.nama_perusahaan, mc.kode as kode_perusahan, mg5.deskripsi as kode_pelayaran, tbo.no_boking, tbodn.no_prefix, tbodn.no_suffix
    from t_buku_order tbo
    left join t_buku_order_d_npwp tbodn on tbodn.t_buku_order_id = tbo.id
    left join t_buku_order_d_aju tboda on tboda.t_buku_order_id = tbo.id
    left join set.m_general mg on mg.id = tbo.pelabuhan_id
    LEFT JOIN set.m_general mg2 on mg2.id = tbodn.depo
    left join set.m_general mg3 on mg3.id = tbodn.jenis 
    left join set.m_general mg4 on mg4.id = tbodn.ukuran
    left join set.m_general mg5 on mg5.id = tbo.kode_pelayaran_id
    left join set.m_kary mk on mk.id = tbodn.m_petugas_pengkont_id 
    left join set.m_kary mk2 on mk2.id = tbodn.m_petugas_pemasukan_id 
    left join m_customer mc on mc.id = tbo.m_customer_id
    where tbo.id = ? ", [$id]);

    if (empty($dataBukuOrder)) {
        echo "<div class='print-sheet'><p>Data Buku Order dengan id {$id} tidak ditemukan.</p></div>";
        continue;
    }

    $n = $dataBukuOrder[0];

    // detail agregat (jumlah per tipe/ukuran) -- sesuai versi awal Anda
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
    @endphp

    <div class="print-sheet">
      <!-- copy layout lama cover Anda -->
      <table style="width: 100%">
          <tr>
              <td style="width: 50%; vertical-align: top; font-size: 10pt;">
                  PT. TIA SENTOSA MAKMUR<br>
                  JL. PERAK TIMUR NO.236 -SURABAYA
              </td>
              <td style="width: 50%; vertical-align: top; text-align: right; font-size: 10pt;">
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
              <td style="vertical-align: top; width: 50%; font-size: larger; font-weight: bold;"> AJU :</td>
              <td style="vertical-align: top; width: 50%; font-size: larger; font-weight: bold;"> Tgl :</td>
          </tr>
      </table>
      <table style="border-collapse: collapse; width: 100%">
          <tr>
              <td style="vertical-align: top;" width="20%" >Jml. Kontainer</td>
              <td style="vertical-align: top;" width="2%">:</td>
              <td style="vertical-align: top;" width="35%">{{$format}}</td>

              <td style="vertical-align: top;" width="10%" >Tgl. Order</td>
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

              <td style="vertical-align: top;" width="13%" >ETD/ETA</td>
              <td style="vertical-align: top;" width="2%">:</td>
              <td style="vertical-align: top;" width="43%">{{ empty($n->tgl_etd_eta) || $n->tgl_etd_eta == '1970-01-01' ? '-' : formatDate($n->tgl_etd_eta) }}</td>
          </tr>
          <tr>
              <td style="vertical-align: top;" width="20%" >Kapal</td>
              <td style="vertical-align: top;" width="2%">:</td>
              <td style="vertical-align: top;" width="43%">{{$n->nama_kapal}}</td>

              <td style="vertical-align: top;" width="13%" >Voyage</td>
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
              <td style="vertical-align: top;" width="43%">{{ empty($n->tanggal_closing_cont) || $n->tanggal_closing_cont == '1970-01-01' ? '-' : formatDate($n->tanggal_closing_cont) }}</td>

              <td style="vertical-align: top;" width="13%" >Jam</td>
              <td style="vertical-align: top;" width="2%">:</td>
              <td style="vertical-align: top;" width="20%">{{$n->jam_closing_cont}}</td>
          </tr>
          <tr>
              <td style="vertical-align: top;" width="20%" >Closing Doc Tgl.</td>
              <td style="vertical-align: top;" width="2%">:</td>
              <td style="vertical-align: top;" width="43%">{{ empty($n->tanggal_closing_doc) || $n->tanggal_closing_doc == '1970-01-01' ? '-' : formatDate($n->tanggal_closing_doc) }}</td>

              <td style="vertical-align: top;" width="13%" >Jam</td>
              <td style="vertical-align: top;" width="2%">:</td>
              <td style="vertical-align: top;" width="20%">{{$n->jam_closing_doc}}</td>
          </tr>
          <tr>
              <td style="vertical-align: top;" width="20%" >NO. BL</td>
              <td style="vertical-align: top;" width="2%">:</td>
              <td style="vertical-align: top;" width="43%">{{$n->no_bl}}</td>

              <td style="vertical-align: top;" width="13%" >Tgl</td>
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

              <td style="vertical-align: top;" width="13%" >Tgl</td>
              <td style="vertical-align: top;" width="2%">:</td>
              <td style="vertical-align: top;" width="20%">{{formatDate($n->tanggal_invoice)}}</td>
          </tr>
          <tr>
              <td style="vertical-align: top;" width="20%" >Pelabuhan</td>
              <td style="vertical-align: top;" width="2%">:</td>
              <td style="vertical-align: top;" width="43%">{{$n->pelabuhan_nama}}</td>

              <td style="vertical-align: top;" width="13%" >Depo</td>
              <td style="vertical-align: top;" width="2%">:</td>
              <td style="vertical-align: top;" width="20%">{{$n->depo_nama}}</td>
          </tr>
      </table>
      
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
      <table>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
      </table>
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
@endforeach
</div>