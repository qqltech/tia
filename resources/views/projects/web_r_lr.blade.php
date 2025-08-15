@php
// Get date parameters
$start_date = request('periode_from') ?? date('Y-m-01'); // First day of current month if not specified
$end_date = request('periode_to') ?? date('Y-m-d'); // Current date if not specified

// Get unique COAs filtered by categories
$pendapatan = \DB::select("
SELECT DISTINCT
c.id,
c.no_induk,
c.nomor,
c.nama_coa
FROM r_gl_d d
LEFT JOIN m_coa c ON c.id=d.m_coa_id
INNER JOIN set.m_general gen ON gen.id = c.kategori
INNER JOIN r_gl gl ON gl.id = d.r_gl_id
WHERE gen.deskripsi='PENDAPATAN'
AND gl.date BETWEEN ? AND ?
ORDER BY c.nomor", [$start_date, $end_date]);

$biaya = \DB::select("
SELECT DISTINCT
c.id,
c.no_induk,
c.nomor,
c.nama_coa
FROM r_gl_d d
LEFT JOIN m_coa c ON c.id=d.m_coa_id
INNER JOIN set.m_general gen ON gen.id = c.kategori
INNER JOIN r_gl gl ON gl.id = d.r_gl_id
WHERE gen.deskripsi='BIAYA'
AND gl.date BETWEEN ? AND ?
ORDER BY c.nomor", [$start_date, $end_date]);

$total_pendapatan = 0;
$total_biaya = 0;
@endphp

<table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th colspan="3" style="text-align: center; background-color: #f0f0f0; padding: 10px;">
                <div style="font-size: 16px;"><strong>LAPORAN LABA RUGI</strong></div>
                <div style="font-size: 14px;">Periode: {{ date('d F Y', strtotime($start_date)) }} - {{ date('d F Y', strtotime($end_date)) }}</div>
            </th>
        </tr>
        <tr>
            <th style="width: 15%;">Kode Akun</th>
            <th style="width: 55%;">Nama Akun</th>
            <th style="width: 30%; text-align: right;">Jumlah</th>
        </tr>
    </thead>

    <tbody>
        <!-- Pendapatan Section -->
        <tr>
            <td colspan="3" style="background-color: #e0e0e0; padding: 8px;"><strong>PENDAPATAN</strong></td>
        </tr>
        @foreach ($pendapatan as $coa)
        @php
        $movements = \DB::select("
        SELECT
        SUM(d.debet) as total_debet,
        SUM(d.credit) as total_credit
        FROM r_gl_d d
        INNER JOIN r_gl gl ON gl.id = d.r_gl_id
        WHERE d.m_coa_id = ?
        AND gl.date BETWEEN ? AND ?",
        [$coa->id, $start_date, $end_date]);

        $debet = $movements[0]->total_debet ?? 0;
        $credit = $movements[0]->total_credit ?? 0;
        $saldo_pendapatan = $credit - $debet; // Pendapatan: Credit - Debit
        $total_pendapatan += $saldo_pendapatan;
        @endphp

        <tr>
            <td>{{ $coa->nomor }}</td>
            <td>{{ $coa->nama_coa }}</td>
            <td style="text-align: right;">{{ number_format($saldo_pendapatan, 2) }}</td>
        </tr>
        @endforeach

        <tr style="background-color: #f0f0f0;">
            <td colspan="2" style="padding-left: 20px;"><strong>Total Pendapatan</strong></td>
            <td style="text-align: right; border-top: 1px solid #000; border-bottom: double;">
                <strong>{{ number_format($total_pendapatan, 2) }}</strong>
            </td>
        </tr>

        <!-- Biaya Section -->
        <tr>
            <td colspan="3" style="background-color: #e0e0e0; padding: 8px;"><strong>BIAYA</strong></td>
        </tr>
        @foreach ($biaya as $coa)
        @php
        $movements = \DB::select("
        SELECT
        SUM(d.debet) as total_debet,
        SUM(d.credit) as total_credit
        FROM r_gl_d d
        INNER JOIN r_gl gl ON gl.id = d.r_gl_id
        WHERE d.m_coa_id = ?
        AND gl.date BETWEEN ? AND ?",
        [$coa->id, $start_date, $end_date]);

        $debet = $movements[0]->total_debet ?? 0;
        $credit = $movements[0]->total_credit ?? 0;
        $saldo_biaya = $debet - $credit; // Biaya: Debit - Credit
        $total_biaya += $saldo_biaya;
        @endphp

        <tr>
            <td>{{ $coa->nomor }}</td>
            <td>{{ $coa->nama_coa }}</td>
            <td style="text-align: right;">{{ number_format($saldo_biaya, 2) }}</td>
        </tr>
        @endforeach

        <tr style="background-color: #f0f0f0;">
            <td colspan="2" style="padding-left: 20px;"><strong>Total Biaya</strong></td>
            <td style="text-align: right; border-top: 1px solid #000; border-bottom: double;">
                <strong>{{ number_format($total_biaya, 2) }}</strong>
            </td>
        </tr>

        <!-- Laba/Rugi Bersih -->
        <tr style="background-color: #d0d0d0;">
            <td colspan="2" style="padding: 10px;"><strong>LABA / RUGI BERSIH</strong></td>
            <td style="text-align: right; border-top: 2px solid #000; border-bottom: double;">
                <strong>{{ number_format($total_pendapatan - $total_biaya, 2) }}</strong>
            </td>
        </tr>
    </tbody>
</table>