@php
$req = app()->request;

$periodeText = '';
if ($req->periode_awal && $req->periode_akhir) {
    // Buat WHERE Clause
    $whereClause = " AND date BETWEEN ? AND ?";
    $params[] = $req->periode_awal;
    $params[] = $req->periode_akhir;

    // Format tanggal awal & akhir
    $awalText = date('d F Y', strtotime($req->periode_awal));
    $akhirText = date('d F Y', strtotime($req->periode_akhir));

    if ($awalText === $akhirText) {
        $periodeText = $awalText;
    } else {
        $periodeText = $awalText . ' - ' . $akhirText;
    }
}

$data = \DB::select("SELECT * FROM r_gl ORDER BY date DESC");
$grand_debet = 0;
$grand_credit = 0;
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
  td, th {
    border: 1px solid black;
    padding: 4px;
  }
</style>

<h4 style="color: #333; margin-bottom: 20px; text-align: center; font-weight: bold;">Laporan General Journal</h4>
    <h6 style="text-align: center; font-style: italic;">Periode:&nbsp; {{ $periodeText ?: '-' }}</h6>
<table>
  <thead>
    <tr style="text-align: center; background-color: #f0f0f0; font-weight: bold;">
      <th rowspan="2" style="width: 5%; line-height: 1.8;">No</th>
      <th rowspan="2" style="width: 10%; line-height: 1.8;">Tanggal</th>
      <th rowspan="2" style="width: 15%; line-height: 1.8;">Transaksi</th>
      <th rowspan="2" style="width: 10%; line-height: 1.8;">No. Reference</th>
      <th colspan="3" style="width: 41%; line-height: 1.8;">Amount</th>
      <th rowspan="2" style="width: 17.5%; line-height: 1.8;">Catatan</th>
    </tr>
    <tr style="text-align: center; background-color: #f0f0f0; font-weight: bold; line-height: 1.8;">
      <th style="width: 20%;">Nama COA</th>
      <th style="width: 11%;">Debet</th>
      <th style="width: 11%;">Credit</th>
    </tr>
  </thead>

  <tbody>
    @if (count($data) == 0)
      <tr>
        <td colspan="7" style="text-align: center;">Tidak ada data</td>
      </tr>
    @else
      @foreach ($data as $key => $dt)
        @php
          $detail = \DB::select("
            SELECT b.*, b.desc as gl_desc, c.nomor, c.nama_coa, c.catatan
            FROM r_gl_d b
            LEFT JOIN m_coa c ON c.id = b.m_coa_id
            LEFT JOIN r_gl a ON a.id = b.r_gl_id
            WHERE b.r_gl_id = ?
            " . ($req->periode_awal ? " AND a.date >= '$req->periode_awal'" : "") . "
            " . ($req->periode_akhir ? " AND a.date <= '$req->periode_akhir'" : "") . "
            ORDER BY b.seq
          ", [$dt->id]);
          $span = count($detail);
          $balance = 0;
        @endphp

        @if ($span > 0)
          @foreach ($detail as $index => $det)
            @php
              $grand_debet += $det->debet;
              $grand_credit += $det->credit;
              $balance += $det->debet - $det->credit;
            @endphp
            <tr style="line-height: 1.8;">
              @if ($index === 0)
                <td rowspan="{{ $span }}" style="text-align: center; width: 5%; ">{{ $key + 1 }}</td>
                <td rowspan="{{ $span }}" style="text-align: center; width: 10%;">{{ date('d/m/Y', strtotime($dt->date)) }}</td>
                <td rowspan="{{ $span }}" style="text-align: center; width: 15%;">{{ $dt->type }}</td>
                <td rowspan="{{ $span }}" style="text-align: center; width: 10%;">{{ $dt->no_reference ?? '-' }}</td>
              @endif
              <td style="text-align: left; width: 20%;">{{ $det->nama_coa }}</td>
              <td style="text-align: right; width: 11%;">{{ 'Rp ' . number_format($det->debet, 2, ',', '.') }}</td>
              <td style="text-align: right; width: 10%;">{{ 'Rp ' . number_format($det->credit, 2, ',', '.') }}</td>
              @if ($index === 0)
                <td rowspan="{{ $span }}" style="text-align: left; width: 17.5%;">{{ $dt->desc }}</td>
              @endif
            </tr>
          @endforeach
        @endif
      @endforeach
    @endif
  </tbody>

  <tfoot>
    <tr style="font-weight: bold; background-color: #f0f0f0; line-height: 1.8;">
      <td colspan="4" style="text-align: right;">Total</td>
      <td style="text-align: right;">{{ 'Rp ' . number_format($grand_debet, 2, ',', '.') }}</td>
      <td style="text-align: right;">{{ 'Rp ' . number_format($grand_credit, 2, ',', '.') }}</td>
      <td></td>
    </tr>
  </tfoot>
</table>
