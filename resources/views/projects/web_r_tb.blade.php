@php
// Get date parameters
$start_date = request('periode_from') ?? date('Y-m-01'); // First day of current month if not specified
$end_date = request('periode_to') ?? date('Y-m-d'); // Current date if not specified

$coas = \DB::select("
SELECT DISTINCT
c.id,
c.no_induk,
c.nomor,
c.nama_coa,
COALESCE((
SELECT SUM(d2.debet) - SUM(d2.credit)
FROM r_gl_d d2
INNER JOIN r_gl g2 ON g2.id = d2.r_gl_id
WHERE d2.m_coa_id = c.id
AND g2.date < ?
    ), 0) as opening_balance
    FROM r_gl_d d
    LEFT JOIN m_coa c ON c.id=d.m_coa_id
    INNER JOIN r_gl gl ON gl.id=d.r_gl_id
    WHERE gl.date BETWEEN ? AND ?
    ORDER BY c.no_induk", [$start_date, $start_date, $end_date]);

    $grand_opening=0;
    $grand_debet=0;
    $grand_credit=0;
    $grand_ending=0;
    @endphp

    <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; background-color: #f0f0f0; padding: 10px;">
                <div style="font-size: 14px;"><strong>TRIAL BALANCE</strong></div>
                <div style="font-size: 12px; font-style: italic;">Periode: {{ date('d F Y', strtotime($start_date)) }} - {{ date('d F Y', strtotime($end_date)) }}</div>
            </th>
        </tr>
        <tr style="text-align: left; font-weight: bold;">
            <th>Account Code</th>
            <th>Account Name</th>
            <th style="text-align: right;">Opening Balance</th>
            <th style="text-align: right;">Debit</th>
            <th style="text-align: right;">Credit</th>
            <th style="text-align: right;">Ending Balance</th>
        </tr>
        <tr>
            <td colspan="6">
                <hr style="border: 1px solid black; margin: 0;">
            </td>
        </tr>
    </thead>

    <tbody>
        @foreach ($coas as $coa)
        @php
        $movements = \DB::select("
        SELECT
        SUM(d.debet) as total_debet,
        SUM(d.credit) as total_credit
        FROM r_gl_d d
        INNER JOIN r_gl gl ON gl.id = d.r_gl_id
        WHERE d.m_coa_id = ?
        AND gl.date BETWEEN ? AND ?
        ", [$coa->id, $start_date, $end_date]);

        $debet = $movements[0]->total_debet ?? 0;
        $credit = $movements[0]->total_credit ?? 0;
        $ending_balance = $coa->opening_balance + $debet - $credit;

        $grand_opening += $coa->opening_balance;
        $grand_debet += $debet;
        $grand_credit += $credit;
        $grand_ending += $ending_balance;
        @endphp

        <tr>
            <td>{{ $coa->nomor }}</td>
            <td>{{ $coa->nama_coa }}</td>
            <td style="text-align: right;">Rp {{ number_format($coa->opening_balance, 2, ',', '.') }}</td>
            <td style="text-align: right;">Rp {{ number_format($debet, 2, ',', '.') }}</td>
            <td style="text-align: right;">Rp {{ number_format($credit, 2, ',', '.') }}</td>
            <td style="text-align: right;">Rp {{ number_format($ending_balance, 2, ',', '.') }}</td>
        </tr>
        @endforeach

        <tr style="background-color: #d0d0d0;">
            <td colspan="2"><strong>TOTAL</strong></td>
            <td style="text-align: right;"><strong>Rp {{ number_format($grand_opening, 2, ',', '.') }}</strong></td>
            <td style="text-align: right;"><strong>Rp {{ number_format($grand_debet, 2, ',', '.') }}</strong></td>
            <td style="text-align: right;"><strong>Rp {{ number_format($grand_credit, 2, ',', '.') }}</strong></td>
            <td style="text-align: right;"><strong>Rp {{ number_format($grand_ending, 2, ',', '.') }}</strong></td>
        </tr>
    </tbody>
    </table>