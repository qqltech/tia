@php
$req = app()->request;
$businessUnitId = $req->m_business_unit_id;
$params = [];
$whereClause = "1=1";

if ($businessUnitId) {
    $whereClause .= " AND m_business_unit_id = ?";
    $params[] = $businessUnitId;
}

$data = \DB::select("SELECT * FROM r_gl WHERE {$whereClause} ORDER BY date DESC", $params);
$grand_debet = 0;
$grand_credit = 0;
@endphp


<style>
    .gl-report-table {
        border-collapse: collapse;
        width: 100%;
        font-family: Arial, sans-serif;
        font-size: 8px;
    }

    .gl-report-table th {
        background-color: #f0f0f0;
        color: black;
        padding: 10px;
        text-align: center;
        font-weight: bold;
        border: 1px solid #ccc;
    }

    .gl-report-table td {
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
        background-color: #f5f5f5;
        border-top: 2px solid #333;
    }

    .no-data {
        font-style: italic;
        color: #666;
        text-align: center;
    }
</style>

<div style="margin: 20px 0;">
    <h3 style="color: #333; margin-bottom: 20px; text-align: center; font-weight: bold;">Laporan General Ledger</h3>
    <table class="gl-report-table">
        <thead>
            <tr>
                <th style="width: 10%;">Tanggal</th>
                <th>Form</th>
                <th style="width: 12.22%;">No Transaksi</th>
                <th>No Referensi</th> 
                <th>Chart of Account</th>
                <th>Debet</th>
                <th>Credit</th>
                <th>Catatan</th>
                <th>Catatan GL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $dt)
            @php
            $detail = \DB::select("
            SELECT b.*, b.desc as gl_desc, c.nomor, c.nama_coa, c.catatan
            FROM r_gl_d b
            LEFT JOIN m_coa c ON c.id = b.m_coa_id
            WHERE b.r_gl_id = ?
            ORDER BY b.seq
            ", [$dt->id]);

            $span = count($detail);
            @endphp

            @if ($span > 0)
            @foreach ($detail as $index => $det)
            @php
            $grand_debet += $det->debet;
            $grand_credit += $det->credit;
            @endphp
            <tr>
                @if ($index === 0)
                <td rowspan="{{ $span }}" class="center-text" style="line-height: 3; width: 10%;">
                    {{ date('d/m/Y', strtotime($dt->date)) }}
                </td>
                <td rowspan="{{ $span }}" class="center-text" style="line-height: 3;">{{ $dt->type }}</td>
                <td rowspan="{{ $span }}" class="center-text" style="line-height: 3; width: 12.22%;">{{ $dt->ref_no }}</td>
                <td rowspan="{{ $span }}" class="center-text" style="line-height: 3;">{{ $dt->no_reference ?? '-' }}</td>
                @endif
                <td>
                    <strong>{{ $det->nomor }}</strong> - {{ $det->nama_coa }}
                </td>
                  <td class="amount-cell" style="font-weight: bold;">
                    {{ 'Rp ' . number_format($det->debet, 2, ',', '.') }}
                </td>
                <td class="amount-cell" style="font-weight: bold;">
                    {{ 'Rp ' . number_format($det->credit, 2, ',', '.') }}
                </td>
                <td>{{ $det->desc ?: '-' }}</td>
                @if ($index === 0)
                <td rowspan="{{ $span }}">{{ $dt->desc ?: '-' }}</td>
                @endif
            </tr>
            @endforeach
            @else
            <tr>
                <td class="center-text">{{ date('d/m/Y', strtotime($dt->date)) }}</td>
                <td class="center-text">{{ $dt->type }}</td>
                <td colspan="6" class="no-data">Tidak ada detail transaksi</td>
            </tr>
            @endif
            @endforeach

            <!-- Grand Total Row -->
            <tr class="grand-total">
                <td colspan="4" style="text-align: right; font-size: 14px;">
                    <strong>GRAND TOTAL</strong>
                </td>
                <td class="amount-cell" style="font-size: 14px;">
                    <strong>Rp {{ number_format($grand_debet, 2, ',', '.') }}</strong>
                </td>
                <td class="amount-cell" style="font-size: 14px;">
                    <strong>Rp {{ number_format($grand_credit, 2, ',', '.') }}</strong>
                </td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
</div>