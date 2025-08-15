@php
// Get date parameters
$start_date = request('periode_from') ?? date('Y-m-01'); // First day of current month if not specified
$end_date = request('periode_to') ?? date('Y-m-d'); // Current date if not specified

// Calculate Laba Rugi first
$pendapatan = \DB::select("
SELECT DISTINCT
c.id,
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

// Calculate total pendapatan
foreach ($pendapatan as $coa) {
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
$saldo_pendapatan = $credit - $debet;
$total_pendapatan += $saldo_pendapatan;
}

// Calculate total biaya
foreach ($biaya as $coa) {
$movements = \DB::select("
SELECT
SUM(d.debet) as total_debet,
SUM(d.credit) as total_credit
FROM r_gl_d d
INNER JOIN r_gl gl ON gl.id=d.r_gl_id
WHERE d.m_coa_id=?
AND gl.date BETWEEN ? AND ?",
[$coa->id, $start_date, $end_date]);

$debet = $movements[0]->total_debet ?? 0;
$credit = $movements[0]->total_credit ?? 0;
$saldo_biaya = $debet - $credit;
$total_biaya += $saldo_biaya;
}

$laba_rugi = $total_pendapatan - $total_biaya;

// Original Neraca queries
$aktiva_parent = \DB::select("
SELECT m_coa.id FROM m_coa
INNER JOIN set.m_general gen ON gen.id = m_coa.kategori
WHERE gen.deskripsi='NERACA'
AND LEFT(nomor, 1)='1'
LIMIT 1 ");

$aktiva_parent_id = $aktiva_parent[0]->id ?? null;

$aktiva = \DB::select("
SELECT DISTINCT
c.id,
c.nomor,
c.nama_coa
FROM r_gl_d d
LEFT JOIN m_coa c ON c.id=d.m_coa_id
INNER JOIN r_gl gl ON gl.id = d.r_gl_id
INNER JOIN set.m_general gen ON gen.id = c.kategori
WHERE gen.deskripsi='NERACA'
AND LEFT(c.nomor, 1) IN ('1','2') -- 1, 2 for AKTIVA
AND gl.date BETWEEN ? AND ?
ORDER BY c.nomor", [$start_date, $end_date]);

$pasiva = \DB::select("
SELECT DISTINCT
c.id,
c.nomor,
c.nama_coa
FROM r_gl_d d
LEFT JOIN m_coa c ON c.id=d.m_coa_id
INNER JOIN r_gl gl ON gl.id = d.r_gl_id
INNER JOIN set.m_general gen ON gen.id = c.kategori
WHERE gen.deskripsi='NERACA'
AND LEFT(c.nomor, 1) IN ('3','4') -- 3 for KEWAJIBAN, 4 for EKUITAS
AND gl.date BETWEEN ? AND ?
ORDER BY c.nomor", [$start_date, $end_date]);

$total_aktiva = 0;
$total_pasiva = 0;
@endphp

<div>
    <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th colspan="6" style="text-align: center; background-color: #f0f0f0; padding: 10px;">
                    <div style="font-size: 16px;"><strong>NERACA</strong></div>
                    <div style="font-size: 14px;">Per Tanggal: {{ date('d F Y', strtotime($end_date)) }}</div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3" style="background-color: #e0e0e0; width: 50%;"><strong>AKTIVA</strong></td>
                <td colspan="3" style="background-color: #e0e0e0; width: 50%;"><strong>PASIVA</strong></td>
            </tr>

            <tr>
                <td style="width: 7%;"><strong>Kode</strong></td>
                <td style="width: 28%;"><strong>Nama Akun</strong></td>
                <td style="width: 15%; text-align: right;"><strong>Jumlah</strong></td>
                <td style="width: 7%;"><strong>Kode</strong></td>
                <td style="width: 28%;"><strong>Nama Akun</strong></td>
                <td style="width: 15%; text-align: right;"><strong>Jumlah</strong></td>
            </tr>

            @php
            $max_rows = max(count($aktiva), count($pasiva));
            @endphp

            @for ($i = 0; $i < $max_rows; $i++)
                <tr>
                <!-- Aktiva Column -->
                @if(isset($aktiva[$i]))
                @php
                $movements = \DB::select("
                SELECT
                COALESCE(SUM(CASE WHEN gl.date < ? THEN d.debet - d.credit ELSE 0 END), 0) as opening_balance,
                    COALESCE(SUM(CASE WHEN gl.date BETWEEN ? AND ? THEN d.debet ELSE 0 END), 0) as period_debet,
                    COALESCE(SUM(CASE WHEN gl.date BETWEEN ? AND ? THEN d.credit ELSE 0 END), 0) as period_credit
                    FROM r_gl_d d
                    INNER JOIN r_gl gl ON gl.id=d.r_gl_id
                    WHERE d.m_coa_id=?",
                    [$start_date, $start_date, $end_date, $start_date, $end_date, $aktiva[$i]->id]);

                    $opening_balance = $movements[0]->opening_balance ?? 0;
                    $period_movement = ($movements[0]->period_debet ?? 0) - ($movements[0]->period_credit ?? 0);
                    $saldo_aktiva = $opening_balance + $period_movement;
                    $total_aktiva += $saldo_aktiva;
                    @endphp
                    <td>{{ $aktiva[$i]->nomor }}</td>
                    <td>{{ $aktiva[$i]->name }}</td>
                    <td style="text-align: right;">{{ number_format($saldo_aktiva, 2) }}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif

                    <!-- Pasiva Column -->
                    @if(isset($pasiva[$i]))
                    @php
                    $movements = \DB::select("
                    SELECT
                    COALESCE(SUM(CASE WHEN gl.date < ? THEN d.credit - d.debet ELSE 0 END), 0) as opening_balance,
                        COALESCE(SUM(CASE WHEN gl.date BETWEEN ? AND ? THEN d.debet ELSE 0 END), 0) as period_debet,
                        COALESCE(SUM(CASE WHEN gl.date BETWEEN ? AND ? THEN d.credit ELSE 0 END), 0) as period_credit
                        FROM r_gl_d d
                        INNER JOIN r_gl gl ON gl.id=d.r_gl_id
                        WHERE d.m_coa_id=?",
                        [$start_date, $start_date, $end_date, $start_date, $end_date, $pasiva[$i]->id]);

                        $opening_balance = $movements[0]->opening_balance ?? 0;
                        $period_movement = ($movements[0]->period_credit ?? 0) - ($movements[0]->period_debet ?? 0);
                        $saldo_pasiva = $opening_balance + $period_movement;
                        $total_pasiva += $saldo_pasiva;
                        @endphp
                        <td>{{ $pasiva[$i]->nomor }}</td>
                        <td>{{ $pasiva[$i]->name }}</td>
                        <td style="text-align: right;">{{ number_format($saldo_pasiva, 2) }}</td>
                        @else
                        <td></td>
                        <td></td>
                        <td></td>
                        @endif
                        </tr>
                        @endfor

                        <!-- Laba Rugi section after PASIVA data -->
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="3" style="background-color: #e0e0e0;"><strong>LABA/RUGI BERSIH</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="border-right: none;"></td>
                            <td style="padding-left: 20px;">- LABA (RUGI) PERIODE BERJALAN</td>
                            <td style="text-align: right;">{{ number_format($laba_rugi, 2) }}</td>
                        </tr>

                        <!-- Total Row -->
                        <tr style="background-color: #f0f0f0; font-weight: bold;">
                            <td colspan="2" style="text-align: right;">Total Aktiva</td>
                            <td style="text-align: right; border-top: double; border-bottom: double;">{{ number_format($total_aktiva, 2) }}</td>
                            <td colspan="2" style="text-align: right;">Total Pasiva + Laba/Rugi</td>
                            <td style="text-align: right; border-top: double; border-bottom: double;">{{ number_format($total_pasiva + $laba_rugi, 2) }}</td>
                        </tr>
        </tbody>
    </table>
</div>