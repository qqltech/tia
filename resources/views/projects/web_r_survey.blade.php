@php
  $req = app()->request;
  $periode_from = $req->periode_from ?? date('Y-m').'-01';
  $periode_to = $req->periode_to ?? date('Y-m-d');
  $raw = \DB::select("
   select question, 
    (select count(1) from t_survey t where t.m_survey_id  = ts.m_survey_id and t.answer_sts is true) sts,
    (select count(1) from t_survey t where t.m_survey_id  = ts.m_survey_id and t.answer_ts is true) ts,
    (select count(1) from t_survey t where t.m_survey_id  = ts.m_survey_id and t.answer_c is true) c,
    (select count(1) from t_survey t where t.m_survey_id  = ts.m_survey_id and t.answer_s is true) s,
    (select count(1) from t_survey t where t.m_survey_id  = ts.m_survey_id and t.answer_st is true) st
    from t_survey ts 
    where date(ts.created_at) >= :periode_from and date(ts.created_at) <= :periode_to
    group by m_survey_id, question order by m_survey_id asc ;
", [ $periode_from, $periode_to]);
@endphp
<span style="width:100%;text-align:center;font-weight:bold;"> Rekapitulasi Survei </span><br/>
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
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:left; background-color: #c6c6c6;">Pertanyaan</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 100px">Sangat Setuju</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 100px">Setuju</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 100px">Cukup Setuju</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 100px">Tidak Setuju</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 100px">Sangat Tidak Setuju</td>
    </tr>
  </thead>
  <tbody>
    @foreach($raw as $i => $d)
    @php
        $backgroundColor = $i % 2 === 1 ? '#f8f8f8' : ''; // Logika untuk menentukan warna latar belakang
    @endphp
    <tr style="background-color: {{$backgroundColor}}">
      <td style="border:0.5px solid black;text-align:center;">{{ $i+1 }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->question }}</td>
      <td style="border:0.5px solid black;text-align:center;">{{ $d->st }}</td>
      <td style="border:0.5px solid black;text-align:center;">{{ $d->s }}</td>
      <td style="border:0.5px solid black;text-align:center;">{{ $d->c }}</td>
      <td style="border:0.5px solid black;text-align:center;">{{ $d->ts }}</td>
      <td style="border:0.5px solid black;text-align:center;">{{ $d->sts }}</td>
    </tr>
    @endforeach
  </tbody>
</table>  