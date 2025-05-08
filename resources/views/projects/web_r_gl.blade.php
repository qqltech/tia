@php
  $data = \DB::select("SELECT * FROM r_gl ORDER BY date DESC");
  $grand_debet = 0;
  $grand_credit = 0;
@endphp

<table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">
  <thead>
    <tr>
      <th>Tanggal</th>
      <th>Form</th>
      <th>No Transaksi</th>
      <th>CoA</th>
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
              <td rowspan="{{ $span }}">{{ $dt->date }}</td>
              <td rowspan="{{ $span }}">{{ $dt->type }}</td>
              <td rowspan="{{ $span }}">{{ $dt->ref_no }}</td>
            @endif
            <td>{{ $det->nomor }} - {{ $det->nama_coa }}</td>
            <td style="text-align: right;">{{ number_format($det->debet, 2) }}</td>
            <td style="text-align: right;">{{ number_format($det->credit, 2) }}</td>
            <td>{{ $det->desc }}</td>
            @if ($index === 0)
              <td rowspan="{{ $span }}">{{ $dt->desc }}</td>
            @endif
          </tr>
        @endforeach
      @else
        <tr>
          <td>{{ $dt->date }}</td>
          <td>{{ $dt->type }}</td>
          <td colspan="6" style="text-align: center;">Tidak ada data</td>
        </tr>
      @endif
    @endforeach

    <!-- Grand Total Row -->
    <tr style="font-weight: bold; background-color: #f2f2f2;">
      <td colspan="4" style="text-align: right;">Grand Total</td>
      <td style="text-align: right;">{{ number_format($grand_debet, 2) }}</td>
      <td style="text-align: right;">{{ number_format($grand_credit, 2) }}</td>
      <td colspan="2"></td>
    </tr>
  </tbody>
</table>
