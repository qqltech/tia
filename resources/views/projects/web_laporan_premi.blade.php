@php
$helper = getCore('Helper');

function formatUang($amount = 0) {
return number_format($amount, 0, ',', '.');
}

function formatTanggalIndonesia($date) {
if (!$date) return null;

// Jika format dari database biasanya Y-m-d atau Y-m-d H:i:s
try {
$carbon = \Carbon\Carbon::parse($date)->locale('id');
return $carbon->translatedFormat('d/m/Y');
} catch (\Exception $e) {
return $date; // fallback kalau format tidak dikenal
}
}

function singkatOrder($str) {
if (!$str) return $str;
$parts = explode('-', $str);
return implode('-', array_slice($parts, 0, 2)); // ambil dua bagian pertama
}

function convertToYmd($date) {
if (!$date) return null;
$parts = explode('/', $date); // d/m/Y
if (count($parts) !== 3) return null;
return $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // Y-m-d
}

function bulanRomawi($bulan)
{
$romawi = [
1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
];
return $romawi[(int)$bulan] ?? '';
}


$req = app()->request;
$userId = $req->user ? floatval(base64_decode($req->user)) : null;

$hutang_supir = $req->get('hutang_supir') ?? 0;
$hutang_dibayar = $req->get('hutang_dibayar') ?? 0;
$total_premi_diterima = $req->get('total_premi_diterima') ?? 0;
$kode = $req->get('kode') ?? '-';

// ambil id[] dari frontend
$ids = $req->get('id') ?? [];
if (!is_array($ids)) {
$ids = $ids ? [$ids] : [];
}
$ids = array_map('intval', $ids);

// jika user tidak dikirim, ambil dari creator_id t_premi
if (!$userId && count($ids) > 0) {
$creator = \DB::selectOne("
SELECT creator_id
FROM t_premi
WHERE id = ?
LIMIT 1
", [$ids[0]]);

if ($creator && $creator->creator_id) {
$userId = $creator->creator_id;
}
}

// error jika tetap tidak dapat userId
if (!$userId) {
dd("Error: parameter ?user= tidak dikirim dan creator_id tidak ditemukan", $req->all());
}

// ambil user
$user = \DB::selectOne("SELECT * FROM default_users WHERE id = ?", [$userId]);

if (!$user) {
dd("Error: user ID tidak ditemukan di DB", $userId);
}

// simpan dalam variabel
$userValue = $user;

$supirId = $req->get('supir_id') ?? null;
$start = $req->get('start_date') ?? null;
$end = $req->get('end_date') ?? null;


// ids yang dikirim sebagai id[] dari frontend (laporan yang dipilih)
$ids = $req->get('id') ?? []; // Laravel menerima id[] sebagai 'id' => array

// pastikan $ids berupa array dan cast ke int
if (!is_array($ids)) {
$ids = $ids ? [$ids] : [];
}
$ids = array_map('intval', array_filter($ids, function($v){ return $v !== null && $v !== ''; }));

// ambil nama supir bila supirId diberikan (agar tampil di header)
$supir = null;
if (!empty($supirId)) {
$sRow = \DB::selectOne("SELECT nama FROM set.m_kary WHERE id = ?", [(int)$supirId]);
$supir = $sRow->nama ?? null;
}

// Build SQL
$sql = "
SELECT
a.id AS id,
b.no_spk,
a.tgl,
a.hutang_supir,
b.dari,
b.ke,
c.no_buku_order,
ca.no_buku_order AS no_buku_order2,
d.nama_perusahaan,
d.kode,
a.no_premi,
ukuran.deskripsi AS ukuran,
trip.kode AS trip,
f.nama AS supir,
COALESCE(CAST(b.sangu AS DECIMAL(15,2)), 0) AS sangu,
COALESCE(a.tarif_premi, 0) AS tarif_premi,
COALESCE(a.tol, 0) AS tol,
COALESCE((SELECT SUM(ab.nominal) FROM t_premi_d ab WHERE ab.t_premi_id = a.id), 0) AS lain_lain,
COALESCE((SELECT SUM(ac.nominal) FROM t_ganti_solar ac WHERE ac.t_spk_angkutan_id = b.id), 0) AS ganti_solar,
a.catatan,
b.tanggal_spk
FROM t_premi a
LEFT JOIN t_spk_angkutan b ON b.id = a.t_spk_angkutan_id
LEFT JOIN t_buku_order c ON c.id = b.t_buku_order_1_id
LEFT JOIN t_buku_order ca ON ca.id = b.t_buku_order_2_id
LEFT JOIN m_customer d ON d.id = c.m_customer_id
LEFT JOIN t_buku_order_d_npwp e ON e.t_buku_order_id = c.id
LEFT JOIN set.m_general ukuran ON ukuran.id = e.ukuran
LEFT JOIN set.m_general trip ON trip.id = b.trip_id
LEFT JOIN set.m_kary f ON f.id = b.supir
";

$where = [];
$bindings = [];

// Jika ada id[] yang dikirim, pakai filter IN untuk menampilkan baris terpilih saja
if (count($ids) > 0) {
// siapkan placeholders untuk binding
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$where[] = "a.id IN ($placeholders)";
foreach ($ids as $v) $bindings[] = $v;
}

// filter berdasarkan supir jika diberikan
if (!empty($supirId)) {
$where[] = "b.supir = ?";
$bindings[] = (int)$supirId;
}



if (count($where) > 0) {
$sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY a.created_at DESC";

$data = \DB::select($sql, $bindings);

// hitung grand total
$grandtotal = [
"sangu" => 0,
"tarif_premi" => 0,
"tol" => 0,
"lain_lain" => 0,
"ganti_solar" => 0,
"total" => 0,
];

// untuk menampilkan periode di header gunakan start/end yang dikirim (jika ada)
$periode_awal = convertToYmd($start);
$periode_akhir = convertToYmd($end);
@endphp

<style>
  /* Area luar: seperti latar abu-abu di contoh BKK */
  body {
    background-color: #e9ecef;
    margin: 0;
    padding: 20px;
    font-family: Arial, sans-serif;
  }

  /* Lembaran putih di tengah */
  .sheet {
    background-color: #fff;
    width: 29.7cm;
    /* A4 landscape */
    height: 21cm;
    margin: 0 auto;
    padding: 10mm;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
    color: #000;
  }

  table {
    border-collapse: collapse;
    width: 100%;
    background-color: #fff;
  }

  th,
  td {
    border: 0.5px solid #000;
    padding: 3px 4px;
    text-align: center;
    vertical-align: middle;
    font-size: 12px;
  }

  .no-border td {
    border: none;
  }

  .header-table1 td {
    border: none;
    padding: 4px 0;
    text-align: center;
  }

  .header-table2 td {
    border: none;
    padding: 2px 0;
    text-align: center;
    font-size: 12px;
  }

  .title {
    font-weight: bold;
    font-size: 18px;
  }

  .highlight {
    background-color: #fffff;
  }

  .right {
    text-align: right;
  }

  .left {
    text-align: left;
  }

  .italic {
    font-style: italic;
  }

  .small {
    font-size: 9px;
  }

  .border-black {
    border: 0.5px solid #000;
  }

  .total-row td {
    font-weight: bold;
    background-color: #eee;
  }

  @page {
    size: A4 landscape;
    margin: 0;
  }

  @media print {
    body {
      background: #fff !important;
      margin: 0;
    }

    .sheet {
      box-shadow: none;
      margin: 0;
      width: 100%;
      height: auto;
      padding: 10mm;
      -webkit-print-color-adjust: exact;
      color-adjust: exact;
    }
  }
</style>

<div class="sheet">
  <!-- <pre>{{var_dump($data)}}</pre> -->
  <table class="header-table1">
    <tr>
      <td class="title">LAPORAN PREMI</td>
    </tr>
  </table>

  <table class="header-table2">
    <tr>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td colspan="2">
        <b>Periode :</b>
        {{ $periode_awal ? $helper->formatTanggalIndonesia($periode_awal) : '-' }}
        s/d
        {{ $periode_akhir ? $helper->formatTanggalIndonesia($periode_akhir) : '-' }}
      </td>
    </tr>
    <tr>
      <td style="width: 78%; text-align: left;">Nama Supir :
        <span style="font-weight: bold;">&nbsp;&nbsp;&nbsp;{{ $data[0]-> supir ?? '-' }}</span>
      </td>
      <td class="small italic right" style="text-align: right;">{{ $kode }}</td>
    </tr>
  </table>

  <br>

  <table>
    <thead>
      <tr>
        <th>Order Angk.</th>
        <th>Tanggal</th>
        <th>Dari</th>
        <th style="width: 5%;">Ke</th>
        <th>No. Ord Tia</th>
        <th>Exp/Imp</th>
        <th>TR No.</th>
        <th>20/40</th>
        <th>Trip</th>
        <th>Supir</th>
        <th>Sangu</th>
        <th>Premi</th>
        <th style="width: 5%;">Tol</th>
        <th>Lain-Lain</th>
        <th style="width: 5%;">Biaya Tmb. Solar</th>
        <th style="width: 6%;">Total</th>
        <th style="width: 10%;">Ket.</th>
      </tr>
    </thead>
    <tbody>

      @foreach($data as $dt)
      @php
      $tarif = (float) ($dt->tarif_premi ?? 0);
      $tol = (float) ($dt->tol ?? 0);
      $lain = (float) ($dt->lain_lain ?? 0);
      $solar = (float) ($dt->ganti_solar ?? 0);
      $sangu = (float) ($dt->sangu ?? 0);
      $total = ($tarif + $tol + $lain + $solar) - $sangu;

      $grandtotal['sangu'] += $sangu;
      $grandtotal['tarif_premi'] += $tarif;
      $grandtotal['tol'] += $tol;
      $grandtotal['lain_lain'] += $lain;
      $grandtotal['ganti_solar'] += $solar;
      $grandtotal['total'] += $total;
      @endphp

      <tr class="highlight">
        <td>{{ $dt->no_buku_order }}</td>
        <td>{{ formatTanggalIndonesia($dt->tgl) }}</td>
        <td>{{ $dt->dari }}</td>
        <td>{{ $dt->ke }}</td>
        <td>{{ singkatOrder($dt->no_buku_order) }}</td>
        <td>{{ $dt->kode }}</td>
        <td>{{ $dt->no_premi }}</td>
        <td>{{ $dt->ukuran }}</td>
        <td>{{ $dt->trip }}</td>
        <td>{{ $dt->supir }}</td>
        <td class="right">{{ formatUang($sangu) }}</td>
        <td class="right">{{ formatUang($tarif) }}</td>
        <td class="right">{{ formatUang($tol) }}</td>
        <td class="right">{{ formatUang($lain) }}</td>
        <td class="right">{{ formatUang($solar) }}</td>
        <td class="right">{{ formatUang($total) }}</td>
        <td>{{ $dt->catatan }}</td>
      </tr>
      @endforeach

      <tr class="total-row">
        <td colspan="10" class="right"><b>Total :</b> &nbsp;&nbsp;&nbsp;{{ $data[0]->supir ?? '-' }}</td>
        <td class="right border-black">{{ formatUang($grandtotal['sangu']) }}</td>
        <td class="right border-black">{{ formatUang($grandtotal['tarif_premi']) }}</td>
        <td class="right border-black">{{ formatUang($grandtotal['tol']) }}</td>
        <td class="right border-black">{{ formatUang($grandtotal['lain_lain']) }}</td>
        <td class="right border-black">{{ formatUang($grandtotal['ganti_solar']) }}</td>
        <td class="right border-black">{{ formatUang($grandtotal['total']) }}</td>
        <td></td>
      </tr>
    </tbody>
  </table>

  <br>

  <!-- <table class="no-border small">
    <tr>
      <td style="width:10%;">Awal</td>
      <td style="width:2%;">:</td>
      <td class="right" style="width:9%;">28.910.100</td>
    </tr>
    <tr>
      <td>Pinjaman</td>
      <td>:</td>
      <td class="right">0</td>
      <td class="right" colspan="11"></td>
    </tr>
    <tr>
      <td>Cicilan</td>
      <td>:</td>
      <td class="right">189.000</td>
      <td class="right" colspan="11"></td>
    </tr>
    <tr>
      <td>Saldo</td>
      <td>:</td>
      <td class="right">28.721.100</td>
    </tr>
  </table> -->

  <table class="no-border" style="width: 100%;">
    <tbody>
      <tr>
        <td style="text-align: left; width: 7%;">
          Hutang supir
          <span style="padding-left: 70%;">
            :
          </span>
        </td>
        <td style="text-align: left; width: 20%;">
          Rp
          <span style="padding-left: 3px;">
            {{ formatUang($hutang_supir) }}
          </span>
        </td>
      </tr>
      <tr>
        <td style="text-align: left;">
          Hutang supir yang ingin di bayarkan
          <span style="padding-left: 23%;">
            :
          </span>
        </td>
        <td style="text-align: left; width: 20%;">
          Rp
          <span style="padding-left: 3px;">
            {{ formatUang($hutang_dibayar) }}
          </span>
        </td>
      </tr>
      <tr>
        <td style="text-align: left;">
          Total premi yang di terima
          <span style="padding-left: 43.5%;">
            :
          </span>
        </td>
        <td style="text-align: left; width: 20%;">
          Rp
          <span style="padding-left: 3px;">
              {{ formatUang($total_premi_diterima) }}
          </span>
        </td>
      </tr>
    </tbody>
  </table>

  <br>

  <table class="no-border small italic">
    <tr>
      <td style="text-align: right; font-size: 10px;">
        Dicetak pada tgl : {{ date('d/m/Y') }} jam {{ date('H:i:s') }}
        &nbsp;&nbsp; Operator : {{ ($userValue->username ?? ' - ') }}
      </td>
    </tr>
  </table>
</div>