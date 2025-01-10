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

$quest = \DB::select("select ms.id, ms.question from m_survey ms order by id asc");
$order = \DB::select(" 
SELECT
  t.id,
  (
    SELECT json_agg(active_surveys)
    FROM (
      SELECT ms.id
      FROM m_survey ms
      WHERE ms.is_active 
    ) AS active_surveys
  ) AS active_surveys_json
FROM
  t_order t where t.id in(select s.t_order_id from t_survey s) order by 1;
   ");
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
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Responden</td>
      @foreach($quest as $i => $d)
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:left; background-color: #c6c6c6;">P{{$i+1}}</td>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach($order as $i => $d)
    <tr>
      <td style="border:0.5px solid black;text-align:center;">R{{ $i+1 }}</td>
      @php  
        $question = json_decode($d->active_surveys_json);
      @endphp
      @foreach($question as $q)
        @php  
          $t_survey = \DB::select("select * from t_survey t where t.t_order_id = ? and t.m_survey_id = ?", [$d->id, $q->id]);
          $point = 1;
          if(@$t_survey[0]->answer_ts){
            $point = 2;
          }elseif(@$t_survey[0]->answer_c){
            $point = 3;
          }elseif(@$t_survey[0]->answer_s){
            $point = 4;
          }elseif(@$t_survey[0]->answer_st){
            $point = 5;
          }
        @endphp
        <td style="border:0.5px solid black;text-align:center;">{{ $point }}</td>
      @endforeach
      
    </tr>
    @endforeach
  </tbody>
</table>  