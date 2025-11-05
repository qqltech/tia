@php
$req = app()->request;
$businessUnitId = $req->m_business_unit_id;
$params = [];
$whereClause = "1=1";

if ($businessUnitId) {
    $whereClause .= " AND rgl.m_business_unit_id = ?";
    $params[] = $businessUnitId;
}

// Ambil data dari r_gl dengan filter kas keluar (debet entries)
$data = \DB::select("
    SELECT 
        rgl.id,
        rgl.date as tanggal,
        rgl.type,
        rgl.ref_table,
        rgl.ref_id,
        rgl.ref_no,
        rgl.desc as keterangan,
        rgl.status,
        rgl.no_reference,
        SUM(rgld.debet) as total_amt
    FROM r_gl rgl
    LEFT JOIN r_gl_d rgld ON rgl.id = rgld.r_gl_id AND rgld.delete_at IS NULL
    WHERE {$whereClause} 
        AND rgl.status = 'POST' 
        AND not rgld.debet = 0
    GROUP BY rgl.id, rgl.date, rgl.type, rgl.ref_table, rgl.ref_id, rgl.ref_no, 
             rgl.desc, rgl.status, rgl.no_reference
    ORDER BY rgl.date DESC
", $params);

$grand_total = 0;
@endphp

<style>
    .cash-out-report-table {
        border-collapse: collapse;
        width: 100%;
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    .cash-out-report-table th {
        background-color: #f8d7da;
        color: black;
        padding: 10px;
        text-align: center;
        font-weight: bold;
        border: 1px solid #ccc;
    }

    .cash-out-report-table td {
        padding: 8px;
        border: 1px solid #ccc;
        vertical-align: top;
    }

    .amount-cell {
        text-align: right;
        font-family: 'Courier New', monospace;
        font-weight: 500;
    }

    .center-text {
        text-align: center;
    }

    .grand-total {
        font-weight: bold;
        background-color: #f8d7da;
        border-top: 2px solid #dc3545;
    }

    .no-data {
        font-style: italic;
        color: #666;
        text-align: center;
    }

    .cash-out-header {
        color: #dc3545;
        margin-bottom: 20px;
        border-bottom: 2px solid #dc3545;
        padding-bottom: 10px;
    }

    .status-badge {
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .status-draft {
        background-color: #ffc107;
        color: #212529;
    }

    .status-approved {
        background-color: #28a745;
        color: white;
    }

    .status-posted {
        background-color: #007bff;
        color: white;
    }
</style>

<div style="margin: 20px 0;">
    <h2 class="cash-out-header">Laporan General Ledger - Kas Keluar</h2>
    <table class="cash-out-report-table">
        <thead>
            <tr>
                <th style="width: 100px;">Tanggal</th>
                <th style="width: 80px;">Status</th>
                <th style="width: 120px;">No Referensi</th>
                <th style="width: 120px;">Tipe Transaksi</th>
                <th style="width: 150px;">COA (Akun)</th>
                <th style="width: 150px;">Penerima</th>
                <th style="width: 200px;">Keterangan</th>
                <th style="width: 120px;">Jumlah Keluar</th>
                <th style="width: 100px;">Tabel Sumber</th>
                <th style="width: 120px;">Ref External</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $dt)
            @php
            // Ambil detail GL berdasarkan r_gl_id untuk debet entries (kas keluar)
            $detail = \DB::select("
                SELECT rgld.*, 
                       c.nama_coa as coa_nama,
                       c.nomor as coa_kode
                FROM r_gl_d rgld
                LEFT JOIN m_coa c ON c.id = rgld.m_coa_id
                WHERE rgld.r_gl_id = ? 
                    AND rgld.delete_at IS NULL 
                    AND rgld.debet > 0
                ORDER BY rgld.seq, rgld.id
            ", [$dt->id]);

            $span = count($detail);
            $grand_total += $dt->total_amt;

            // Tentukan class status
            $statusClass = 'status-draft';
            if (strtoupper($dt->status) == 'APPROVED') {
                $statusClass = 'status-approved';
            } elseif (strtoupper($dt->status) == 'POSTED') {
                $statusClass = 'status-posted';
            }

            // Format tipe transaksi
            $tipeTransaksi = ucwords(str_replace('_', ' ', $dt->type));
            
            // Format tabel sumber
            $tabelSumber = ucwords(str_replace('_', ' ', $dt->ref_table));
            @endphp

            @if ($span > 0)
                @foreach ($detail as $index => $det)
                <tr>
                    @if ($index === 0)
                    <td rowspan="{{ $span }}" class="center-text">
                        {{ date('d/m/Y', strtotime($dt->tanggal)) }}
                    </td>
                    <td rowspan="{{ $span }}" class="center-text">
                        <span class="status-badge {{ $statusClass }}">{{ $dt->status }}</span>
                    </td>
                    <td rowspan="{{ $span }}" class="center-text">{{ $dt->ref_no ?? '-' }}</td>
                    <td rowspan="{{ $span }}" class="center-text">{{ $tipeTransaksi }}</td>
                    @endif
                    
                    <td>
                        <strong>{{ $det->coa_kode ?? '' }} - {{ $det->coa_nama ?? 'COA Tidak Ditemukan' }}</strong>
                        @if($det->desc)
                        <br><small style="color: #666;">{{ $det->desc }}</small>
                        @endif
                    </td>
                    
                    @if ($index === 0)
                    <td rowspan="{{ $span }}">{{ $dt->nama_penerima ?? '-' }}</td>
                    @endif
                    
                    <td>{{ $det->desc ?: $dt->keterangan ?: '-' }}</td>
                    
                    <td class="amount-cell" style="color: #dc3545; font-weight: bold;">
                        {{ 'Rp ' . number_format($det->debet, 2, ',', '.') }}
                    </td>
                    
                    @if ($index === 0)
                    <td rowspan="{{ $span }}" class="center-text">{{ $tabelSumber }}</td>
                    <td rowspan="{{ $span }}" class="center-text">{{ $dt->no_reference ?? '-' }}</td>
                    @endif
                </tr>
                @endforeach
            @else
            <tr>
                <td class="center-text">{{ date('d/m/Y', strtotime($dt->tanggal)) }}</td>
                <td class="center-text">
                    <span class="status-badge {{ $statusClass }}">{{ $dt->status }}</span>
                </td>
                <td class="center-text">{{ $dt->ref_no ?? '-' }}</td>
                <td class="center-text">{{ $tipeTransaksi }}</td>
                <td class="no-data">Tidak ada detail COA</td>
                <td>{{ $dt->nama_penerima ?? '-' }}</td>
                <td class="no-data">{{ $dt->keterangan ?? 'Tidak ada detail transaksi' }}</td>
                <td class="amount-cell" style="color: #dc3545; font-weight: bold;">
                    {{ 'Rp ' . number_format($dt->total_amt, 2, ',', '.') }}
                </td>
                <td class="center-text">{{ $tabelSumber }}</td>
                <td class="center-text">{{ $dt->no_reference ?? '-' }}</td>
            </tr>
            @endif
            @endforeach

            @if(count($data) == 0)
            <tr>
                <td colspan="10" class="no-data" style="padding: 30px;">
                    Tidak ada data General Ledger untuk kas keluar pada periode ini
                </td>
            </tr>
            @endif

            <!-- Grand Total Row -->
            @if(count($data) > 0)
            <tr class="grand-total">
                <td colspan="7" style="text-align: right; font-size: 14px;">
                    <strong>TOTAL KAS KELUAR</strong>
                </td>
                <td class="amount-cell" style="font-size: 14px; color: #dc3545;">
                    <strong>Rp {{ number_format($grand_total, 2, ',', '.') }}</strong>
                </td>
                <td colspan="2"></td>
            </tr>
            @endif
        </tbody>
    </table>

    @if(count($data) > 0)
    <div style="margin-top: 20px; padding: 15px; background-color: #f8d7da; border-left: 4px solid #dc3545;">
        <h4 style="margin: 0; color: #721c24;">Ringkasan General Ledger - Kas Keluar</h4>
        <p style="margin: 5px 0; color: #721c24;">
            Total Transaksi: <strong>{{ count($data) }}</strong> | 
            Total Kas Keluar: <strong style="color: #dc3545;">Rp {{ number_format($grand_total, 2, ',', '.') }}</strong>
        </p>
        <p style="margin: 5px 0; font-size: 12px; color: #666;">
            <em>Data diambil dari tabel General Ledger (r_gl) dengan filter debet entries untuk kas keluar</em>
        </p>
    </div>
    @endif
</div>
