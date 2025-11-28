@php
// Use request() helper (works in Lumen & Laravel)
$req = request();

// Normalisasi 'id' parameter: support id[]=1&id[]=2  OR id=1,2
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
    echo 'No valid id provided';
    return;
}

// Decode user: accept numeric user OR base64-encoded numeric user
$rawUser = $req->query('user');
$userId = null;
if (is_numeric($rawUser)) {
    $userId = (int) $rawUser;
} elseif (is_string($rawUser) && trim($rawUser) !== '') {
    $decoded = base64_decode($rawUser, true);
    if ($decoded !== false && is_numeric($decoded)) {
        $userId = (int) $decoded;
    }
}

$userValue = null;
if ($userId) {
    $userValue = \DB::table('default_users')->where('id', $userId)->first();
}
if (!$userValue) {
    $userValue = (object)['name' => 'Unknown', 'username' => 'unknown'];
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

function generateKodeLaporanPremi()
{
    // ambil tahun & bulan sekarang
    $bulan = (int)date('m');
    $tahun = date('y'); // dua digit

    // konversi ke romawi
    $romawi = bulanRomawi($bulan);
    try {
        $count = \DB::table('t_bkk')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', date('Y'))
            ->count();
    } catch (\Exception $e) {
        $count = 0; // fallback jika error
    }

    $noUrut = $count + 1; // nomor urut berikutnya
    $noUrutFormatted = str_pad($noUrut, 4, '0', STR_PAD_LEFT);

    return "PPJK/" . $noUrutFormatted . "/" . $romawi . "/" . $tahun;
}

// Get BKK headers for all ids and keep ordering
$dataBkkNonKasbon = \DB::table('t_bkk as tb')
    ->select('tb.*', 'tb.total_amt')
    ->whereIn('tb.id', $ids)
    ->orderByRaw("array_position(ARRAY[" . implode(',', $ids) . "]::bigint[], tb.id::bigint)")
    ->get();

// Get details for all those BKKs (we will combine into one table)
$detailRaw = \DB::table('t_bkk_d as tbd')
    ->leftJoin('m_coa as mc', 'mc.id', 'tbd.m_coa_id')
    ->leftJoin('t_buku_order as tbo', 'tbo.id', 'tbd.t_buku_order_id')
    ->whereIn('tbd.t_bkk_id', $ids)
    ->select('tbd.*', 'mc.nomor as nomor_coa', 'mc.nama_coa', 'tbo.no_buku_order', 'tbd.t_bkk_id')
    ->orderBy('tbd.t_bkk_id')
    ->orderBy('tbd.id')
    ->get();

// Combine all details into one grouped table (group by COA + keterangan)
$grouped = [];
foreach ($detailRaw as $d) {
    // group key: nomor_coa + nama_coa + keterangan (keeps rows concise)
    $key = ($d->nomor_coa ?? '') . '||' . ($d->nama_coa ?? '') . '||' . ($d->keterangan ?? '');
    if (!isset($grouped[$key])) {
        $grouped[$key] = [
            'nomor_coa' => $d->nomor_coa ?? '-',
            'nama_coa' => $d->nama_coa ?? '-',
            'keterangan' => $d->keterangan ?? '-',
            'orders' => [],
            'nominal_sum' => 0.0,
            // track t_bkk_id list (if you want to show which BKK each order came from)
            'bkk_ids' => []
        ];
    }

    if (!empty($d->no_buku_order)) {
        $grouped[$key]['orders'][] = trim($d->no_buku_order);
    }
    $grouped[$key]['nominal_sum'] += floatval($d->nominal);
    $grouped[$key]['bkk_ids'][] = $d->t_bkk_id;
}

// Normalize orders (unique) and keep order
foreach ($grouped as $k => $g) {
    $unique = array_values(array_unique($g['orders']));
    $grouped[$k]['orders'] = $unique;
    $grouped[$k]['bkk_ids'] = array_values(array_unique($g['bkk_ids']));
}

// Prepare render rows
$renderRows = array_values($grouped);

// Totals
$total_nominal = 0;
foreach ($renderRows as $r) {
    $total_nominal += $r['nominal_sum'];
}

// Prepare header fields: join no_bkk into single string (kept design but concatenate)
$joined_no_bkk = implode(', ', $dataBkkNonKasbon->pluck('no_bkk')->toArray());
$first_tanggal = $dataBkkNonKasbon->first()->tanggal ?? date('Y-m-d');

function formatDate($date){
  $unixTime = strtotime($date);
  return date("d/m/Y", $unixTime);
}

function terbilang($number) {
  $huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
  $temp = '';

  if ($number == 10) {
    $temp = ' sepuluh';
  } elseif ($number == 11) {
    $temp = ' sebelas';
  } elseif ($number < 12) { $temp=' ' . $huruf[$number]; }
  elseif ($number < 20) { $temp=terbilang($number - 10) . ' belas' ; }
  elseif ($number < 100) { $temp=terbilang((int)($number / 10)) . ' puluh' . terbilang($number % 10); }
  elseif ($number < 200) { $temp=' seratus' . terbilang($number - 100); }
  elseif ($number < 1000) { $temp=terbilang((int)($number / 100)) . ' ratus' . terbilang($number % 100); }
  elseif ($number < 2000) { $temp=' seribu' . terbilang($number - 1000); }
  elseif ($number < 1000000) { $temp=terbilang((int)($number / 1000)) . ' ribu' . terbilang($number % 1000); }
  elseif ($number < 1000000000) { $temp=terbilang((int)($number / 1000000)) . ' juta' . terbilang($number % 1000000); }
  return $temp; 
}

$currentDate = date("d/m/Y");
$currentTime = date("H:i:s");
@endphp

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    /* desain Anda dipertahankan, hanya sedikit tweak agar semua order tampil di SATU table */
    ul { list-style-type: none; padding: 0; margin: 0; }
    ul li { position: relative; padding-left: 15px; }
    ul li::before { content: "-"; position: absolute; left: 0; }
    @page { margin: 10mm; }
    body { margin: 0; }
    .sheet { margin: 5mm auto; overflow: visible; position: relative; box-sizing: border-box; /* page-break-after: always; removed so single sheet */ }
    /* lebar kertas (disesuaikan kalau Anda cetak A4, tweak jika perlu) */
    body.continuous_form .sheet { width: 210mm; /* full A4 width */ max-width: 210mm; padding: 6mm; }
    @media screen {
      body { background: #e0e0e0; }
      .sheet { background: white; box-shadow: 0 0.5mm 2mm rgba(0,0,0,0.3); margin: 10mm auto; }
    }
    table { 
      border-collapse: collapse; 
      table-layout: fixed; 
      font-size: 10px; 
      font-family: sans-serif; 
      width: 100%; 
    }
    tr { height: 10px; }
    th, td { border: 0.5px solid black; padding: 2px 4px; vertical-align: top; }
    .no-border { border: none; }
    .nowrap { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    /* Jika ingin "memaksa" semuanya dalam 1 page: shrink font sedikit saat print */
    @media print {
      table { font-size: 9px; }
      .sheet { page-break-after: avoid; page-break-inside: avoid; }
    }
  </style>
</head>

<body class="continuous_form">
  <!-- Single page combining all selected BKKs into ONE table -->
  <section class="sheet padding-3mm">
    <div style="width: 100%; font-size: 14px; font-family: sans-serif; font-weight: bold; text-align: center; padding: 2%;">
      <tr>
        <td style="">BKK NON KASBON</td>
      </tr>
    </div>

    <div style="width:100%; font-size: 12px; margin-bottom:6px;">
      <tr>
        <td style="width: 60%; padding-bottom: 10px;">No. Bukti
          <span style="margin-left: 3.5%;">:</span>
          <span style="margin-left: 2%; font-weight: bold; text-decoration: underline; font-style: italic;">{{ generateKodeLaporanPremi() }}</span>
        </td>
      </tr>
    </div>
    <div style="width:100%; font-size: 12px; margin-bottom:6px;">
      <tr>
        <td style="width: 40%;">Tanggal
          <span style="margin-left: 4.7%;">:</span>
          <span style="margin-left: 2%;">{{ formatDate($first_tanggal) }}</span>
        </td>
      </tr>
    </div>

    <!-- Main combined table -->
    <table cellspacing="0" cellpadding="3" style="width:100%; border: 0.5px solid black; border-collapse: collapse; font-size: 9px;">
      <tr>
        <th style="width: 15%; text-align: center;">Nomor Order</th>
        <th style="width: 12%; text-align: center;">Perk</th>
        <th style="width: 15%; text-align: center;">Nama Perk</th>
        <th style="width: 37%; text-align: center;">Detail</th>
        <th style="width: 15%; text-align: center;">Nominal</th>
      </tr>

      @php $no = 1; @endphp
      @foreach($renderRows as $row)
        <tr>

          <!-- Gabungkan semua nomor order menjadi satu cell, dipisah koma (bisa diubah ke newline) -->
          <td style="text-align: center; padding-left:6px;">
            {!! e(implode(", ", $row['orders'] ?: ['-'])) !!}
          </td>

          <td style="text-align: center;">{{ $row['nomor_coa'] ?? '-' }}</td>
          <td style="text-align: center; padding-left:6px;">{{ $row['nama_coa'] ?? '-' }}</td>
          <td style="text-align: left; padding-left:6px;">{{ $row['keterangan'] ? $row['keterangan'] : '-' }}</td>
          <td style="text-align: right; padding-right:6px;">Rp {{ number_format($row['nominal_sum'],2,',','.') }}</td>
        </tr>
      @endforeach

      <!-- TOTAL -->
      <tr>
        <td colspan="3" style="border-right: none; padding-top:6px; text-align:left; padding-left:6px;"></td>
        <td style="border-left: none; border-right: none; text-align: right; font-weight: bold;">TOTAL :</td>
        <td style="border-left: none; text-align: right; padding-right:6px; font-weight: bold;">Rp {{ number_format($total_nominal,2,',','.') }}</td>
      </tr>
    </table>

    <table style="width:100%; margin-top:6px;">
      <tr>
        <td style="width: 8%; padding-left: 4px; border-right: none;">Terbilang &nbsp; :</td>
        <td style="text-decoration: underline; font-style: italic; border-left: none">{{ terbilang($total_nominal) }} rupiah</td>
      </tr>
    </table>

    <table style="width: 100%; border: 0.5px solid black; margin-top:6px; border-collapse: collapse;">
      <tr>
        <td style="text-align: center;">Mengetahui</td>
        <td style="text-align: center;">Admin / Kasir</td>
        <td style="text-align: center;">Penerima</td>
      </tr>
      <tr>
        <td style="height:28px; text-align: center; padding-top: 12%;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
        <td style="text-align: center; padding-top: 12%;">(&nbsp;&nbsp;&nbsp;&nbsp;Kasir&nbsp;&nbsp;&nbsp;&nbsp;)</td>
        <td style="text-align: center; padding-top: 12%;">( <span>{!! $dataBkkNonKasbon->first()?->nama_penerima ?? str_repeat('&nbsp;', 50) !!}</span> )</td>
      </tr>
    </table>

    <div style="margin-top:8px; font-size: 9px; font-weight: bold;">
      Dicetak pada tgl : {{ $currentDate }} &nbsp; jam {{ $currentTime }} &nbsp; Operator : {{ $userValue->name }} # {{ $userValue->username }}
    </div>
  </section>
</body>