@php
$req = app()->request;

$baseSql = "
SELECT id, tanggal, total_amt, no_bkk AS no_transaksi, created_at, 't_bkk' AS source
FROM t_bkk
WHERE status IN ('APPROVED','PRINTED')

UNION ALL

SELECT id, tanggal, total_amt, no_bkk AS no_transaksi, created_at, 't_bkk_non_order' AS source
FROM t_bkk_non_order
WHERE status IN ('APPROVED','PRINTED')

UNION ALL

SELECT id, tanggal, total_amt, no_bkm AS no_transaksi, created_at, 't_bkm' AS source
FROM t_bkm
WHERE status IN ('POST','PRINTED')

UNION ALL

SELECT id, tanggal, total_amt, no_bkm AS no_transaksi, created_at, 't_bkm_non_order' AS source
FROM t_bkm_non_order
WHERE status IN ('POST','PRINTED')

UNION ALL

SELECT id, tgl AS tanggal, 0::numeric AS total_amt, no_buku_order AS no_transaksi, created_at, 't_buku_order' AS source
FROM t_buku_order
WHERE status IN ('APPROVED','PRINTED')
";

$where = [];
$params = [];

/* filter periode */
$periodeText = '-';
if ($req->periode_awal && $req->periode_akhir) {
    // Cast to date in SQL to avoid format mismatch
    $where[] = " tanggal::date BETWEEN ?::date AND ?::date ";
    $params[] = $req->periode_awal;
    $params[] = $req->periode_akhir;

    $awalText = date('d F Y', strtotime($req->periode_awal));
    $akhirText = date('d F Y', strtotime($req->periode_akhir));

    $periodeText = ($awalText === $akhirText) ? $awalText : $awalText . ' - ' . $akhirText;
}

/* compose final SQL */
if ($where) {
    $sql = "
        SELECT *
        FROM ({$baseSql}) x
        WHERE " . implode(' AND ', $where) . "
        ORDER BY x.tanggal ASC, x.created_at ASC
    ";
} else {
    // fallback TANPA filter
    $sql = "
        SELECT *
        FROM ({$baseSql}) x
        ORDER BY x.tanggal ASC, x.created_at ASC
    ";
}

/* FETCH HEADERS */
$data = \DB::select($sql, $params);

/* --- START: batch-fetch details to avoid N+1 queries --- */

/*
  detailMap: sesuaikan 'table' dan 'fk' dengan schema Anda.
  Set 'has_buku_order' => true hanya jika tabel detail punya kolom t_buku_order_id.
*/
$detailMap = [
  't_bkk' => ['table' => 't_bkk_d', 'fk' => 't_bkk_id', 'has_buku_order' => false],
  't_bkk_non_order' => ['table' => 't_bkk_non_order_d', 'fk' => 't_bkk_non_order_id', 'has_buku_order' => false],
  't_bkm' => ['table' => 't_bkm_d', 'fk' => 't_bkm_id', 'has_buku_order' => true],
  't_bkm_non_order' => ['table' => 't_bkm_non_order_d', 'fk' => 't_bkm_non_order_id', 'has_buku_order' => false],
  't_buku_order' => ['table' => 't_buku_order_d', 'fk' => 't_buku_order_id', 'has_buku_order' => false],
];

$idsBySource = [];
if (!empty($data)) {
    foreach ($data as $row) {
        $idsBySource[$row->source][] = $row->id;
    }
}

$detailsGrouped = []; // $detailsGrouped[$source][$parent_id] = [rows...]

// chunk ini mencegah placeholder IN terlalu panjang; sesuaikan sesuai limit DB/driver
$chunkSize = 500;

foreach ($idsBySource as $source => $ids) {
    if (!isset($detailMap[$source])) continue;

    $map = $detailMap[$source];
    $table = $map['table'];
    $fk = $map['fk'];
    $hasBukuOrder = $map['has_buku_order'] ?? false;

    // pilih hanya kolom yang diperlukan untuk mengurangi memory/transfer
    $selectCols = "d.id, d.nominal, d.m_coa_id, d.{$fk} AS parent_id";
    if ($hasBukuOrder) {
        // include foreign key to join buku_order
        $selectCols = "d.id, d.nominal, d.m_coa_id, d.t_buku_order_id, d.{$fk} AS parent_id";
    }

    $selectBuku = $hasBukuOrder ? ", bo.no_buku_order" : ", NULL AS no_buku_order";
    $joinBuku = $hasBukuOrder ? "LEFT JOIN t_buku_order bo ON bo.id = d.t_buku_order_id" : "";

    $chunks = array_chunk($ids, $chunkSize);
    foreach ($chunks as $chunk) {
        $placeholders = implode(',', array_fill(0, count($chunk), '?'));

        $detailSql = "
            SELECT {$selectCols}, c.nama_coa, c.nomor {$selectBuku}
            FROM {$table} d
            LEFT JOIN m_coa c ON c.id = d.m_coa_id
            {$joinBuku}
            WHERE d.{$fk} IN ({$placeholders})
            ORDER BY d.id
        ";

        try {
            $rows = \DB::select($detailSql, $chunk);
        } catch (\Exception $e) {
            // jika tabel/kolom tidak ada atau error, skip chunk
            $rows = [];
        }

        foreach ($rows as $r) {
            $parent = $r->parent_id;
            $detailsGrouped[$source][$parent][] = $r;
        }
    }
}

/* --- END: batch-fetch details --- */
@endphp

<style>
  table {
    border-collapse: collapse;
    width: 100%;
    font-family: sans-serif;
    font-size: 10px;
  }

  thead {
    display: table-header-group;
  }

  tfoot {
    display: table-footer-group;
  }

  tr {
    page-break-inside: avoid;
  }

  td,
  th {
    border: 1px solid black;
    padding: 4px;
  }
</style>

<h4 style="color: #333; margin-bottom: 20px; text-align: center; font-weight: bold;">LAPORAN KERJA HARIAN</h4>
<h6 style="text-align: center; font-style: italic;">Periode:&nbsp; {{ $periodeText ?: '-' }}</h6>

<h5 style="">Kode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</h5>
<!-- <h5 style="">Deskripsi&nbsp;&nbsp;&nbsp;&nbsp;:</h5> -->

<table>
  <thead>
    <tr style="text-align: center; background-color: #f0f0f0; font-weight: bold;">
      <th style="width:4%">No</th>
      <th style="width:8%">Tanggal</th>
      <th style="width:12%">Transaksi</th>
      <th style="width:20%">COA Detail</th>
      <th style="width:15%">No Order</th>
      <th style="width:13%">Debet</th>
      <th style="width:13%">Kredit</th>
      <th style="width:14%">Saldo</th>
    </tr>
  </thead>

  <tbody>
    @php
      // init counters (footer expects these always defined)
      $no = 1;
      $saldo = 0;
      $grand_debet = 0;
      $grand_credit = 0;
    @endphp

    @if (empty($data))
      <tr>
        <td colspan="8" style="text-align:center;">Tidak ada data</td>
      </tr>
    @else
      @foreach ($data as $dt)
        @php
          // ambil detail hasil batch-fetch
          $detail = $detailsGrouped[$dt->source][$dt->id] ?? [];
        @endphp

        @if (empty($detail))
          {{-- tampilkan ringkasan transaksi tanpa detail --}}
          <tr>
            <td style="text-align:center; width:4%;">{{ $no++ }}</td>
            <td style="text-align:center; width:8%;">{{ date('d/m/Y', strtotime($dt->tanggal)) }}</td>
            <td style="text-align:center; width:12%;">{{ $dt->no_transaksi }}</td>
            <td style="text-align:left; width:20%;">-</td>
            <td style="text-align:center; width:15%;">-</td>
            <td style="text-align:right; width:13%;">{{ number_format(0, 2, ',', '.') }}</td>
            <td style="text-align:right; width:13%;">{{ number_format(0, 2, ',', '.') }}</td>
            <td style="text-align:right; width:14%;">{{ number_format($saldo, 2, ',', '.') }}</td>
          </tr>
        @else
          @foreach ($detail as $det)
            @php
              // nominal fallback handling
              $nominal = isset($det->nominal) ? (float)$det->nominal : (isset($det->amount) ? (float)$det->amount : 0.0);

              if (in_array($dt->source, ['t_bkm', 't_bkm_non_order'])) {
                // BKM = debet
                $debet = $nominal;
                $credit = 0;
                $saldo += $nominal;
                $grand_debet += $nominal;
              } else {
                // BKK = credit
                $debet = 0;
                $credit = $nominal;
                $saldo -= $nominal;
                $grand_credit += $nominal;
              }

              $no_buku_order = $det->no_buku_order ?? ($det->t_buku_order_id ?? null);
            @endphp

            <tr>
              <td style="text-align:center; width:4%;">{{ $no++ }}</td>
              <td style="text-align:center; width:8%;">{{ date('d/m/Y', strtotime($dt->tanggal)) }}</td>
              <td style="text-align:center; width:12%;">{{ $dt->no_transaksi }}</td>
              <td style="text-align:left; width:20%;">{{ '(' . ($det->nomor ?? '-') . ') ' . ($det->nama_coa ?? '-') }}</td>
              <td style="text-align:center; width:15%;">{{ $no_buku_order ?? '-' }}</td>
              <td style="text-align:right; width:13%;">{{ number_format($debet, 0, ',', '.') }}</td>
              <td style="text-align:right; width:13%;">{{ number_format($credit, 0, ',', '.') }}</td>
              <td style="text-align:right; width:14%;">{{ number_format($saldo, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        @endif
      @endforeach
    @endif
  </tbody>

  @php
    // grand_saldo adalah saldo akhir setelah seluruh loop (total saldo)
    $grand_saldo = $saldo;
  @endphp

  <tfoot>
    <tr style="font-weight:bold; background:#f0f0f0;">
      <td colspan="5" style="text-align:right;">Total</td>
      <td style="text-align:right;">{{ number_format($grand_debet, 0, ',', '.') }}</td>
      <td style="text-align:right;">{{ number_format($grand_credit, 0, ',', '.') }}</td>
      <td style="text-align:right;">{{ number_format($grand_saldo, 0, ',', '.') }}</td>
    </tr>
  </tfoot>
</table>