@php
$data = \DB::select("SELECT * FROM r_gl order by date desc");
@endphp

<table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">
  <thead>
    <tr>
      <th>Tanggal</th>
      <th>Form</th>
      <th>CoA</th>
      <th>Debet</th>
      <th>Credit</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $dt)
      @php
      $detail = \DB::select("SELECT b.*, c.nomor, c.nama_coa FROM r_gl_d b 
                             LEFT JOIN m_coa c ON c.id = b.m_coa_id 
                             WHERE b.r_gl_id = ? order by b.seq", [$dt->id]);
      $span = count($detail);
      @endphp

      @if($span > 0)
        @foreach($detail as $index => $det)
        <tr>
          @if($index === 0)
            <td rowspan="{{ $span }}">{{ $dt->date ?? '' }}</td>
            <td rowspan="{{ $span }}">{{ $dt->type ?? '' }}</td>
          @endif
          <td>{{ $det->nomor ?? '' }} - {{ $det->nama_coa ?? '' }}</td>
          <td>{{ number_format($det->debet, 2) ?? '' }}</td>
          <td>{{ number_format($det->credit, 2) ?? '' }}</td>
        </tr>
        @endforeach
      @else
        <tr>
          <td>{{ $dt->date ?? '' }}</td>
          <td>{{ $dt->type ?? '' }}</td>
          <td colspan="3" style="text-align: center;">Tidak ada data</td>
        </tr>
      @endif
    @endforeach
  </tbody>
</table>
