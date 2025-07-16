@php
  $req = app()->request;
  $periode_from = $req->periode_from ?? date('Y-m').'-01';
  $periode_to = $req->periode_to ?? date('Y-m-d');
  $raw = \DB::select("
   select  ts.created_at, o.code, question, 
    (
      case 
        when ts.answer_sts is true 
          then 'Sangat Tidak Setuju'
        when ts.answer_ts is true 
          then 'Tidak Setuju'
        when ts.answer_c is true 
          then 'Cukup Setuju'
        when ts.answer_s is true 
          then 'Setuju'
        when ts.answer_st is true 
          then 'Sangat Setuju'
        else '-'
      end
    ) answer
      from t_survey ts 
      join t_order o on o.id = ts.t_order_id
      where date(ts.created_at) >= :periode_from and date(ts.created_at) <= :periode_to
      order by ts.created_at asc ;
", [ $periode_from, $periode_to]);
@endphp
<span style="width:100%;text-align:center;font-weight:bold;"> Rekapitulasi Survei Detail</span><br/>
@php
  $periode_from = date('d-m-Y', strtotime($periode_from));
  $periode_to = date('d-m-Y', strtotime($periode_to));
@endphp
<span style="width:100%;text-align:center; font-size:10pt"> {{ $periode_from }} - {{ $periode_to }}</span><br/>
<br/>
<table width="100%" style="font-size:12px;" cellpadding="2">
  <thead style="font-weight:semibold;">
    <tr style="">
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 150px">Tgl, Penilaian</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 150px">Nomor Invoice</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 400px">Pertanyaan</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 150px">#</td>
    </tr>
  </thead>
  <tbody>
    @foreach($raw as $i => $d)
    @php
        $backgroundColor = $i % 2 === 1 ? '#f8f8f8' : ''; // Logika untuk menentukan warna latar belakang
    @endphp
    <tr style="background-color: {{$backgroundColor}}">
      <td style="border:0.5px solid black;text-align:center;">{{ $i+1 }}</td>
      <td style="border:0.5px solid black;text-align:center;">{{ $d->created_at }}</td>
      <td style="border:0.5px solid black;text-align:center;">{{ $d->code }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->question }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->answer }}</td>
    </tr>
    @endforeach
  </tbody>
</table>  