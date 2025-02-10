<?php

namespace App\Models\CustomModels;

class t_angkutan extends \App\Models\BasicModels\t_angkutan
{
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }

    public $fileColumns = [
        /*file_column*/
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function transformRowData(array $row)
    {
        // $party = $this->custom_Party($row["t_buku_order_id"]);
        // $newData = [
        //     "party_fly" => $party,
        // ];

        $req = app()->request;
        if($req->getCodeCustomer){
            
            $id_cust = $row['t_buku_order.m_customer_id'];
            
            $result = m_customer::where('id',$id_cust)->first();
            // trigger_error(json_encode($result));
            $row['kode_cust']= $result->kode;
        }

        return array_merge($row,[]);
    }

    // private function custom_Party($id)
    // {
    //     // $req = request("buku_order_id");

    //     $container = \DB::table("t_buku_order")
    //         ->select(
    //             \DB::raw(
    //                 "jumlah_cont_20,jumlah_cont_40,jumlah_cont_45,jumlah_cont_60"
    //             )
    //         )
    //         ->where("id", $id)
    //         ->get();

    //     $cont20 = (string) $container[0]->jumlah_cont_20;
    //     $cont40 = (string) $container[0]->jumlah_cont_40;
    //     $cont45 = (string) $container[0]->jumlah_cont_45;
    //     $cont60 = (string) $container[0]->jumlah_cont_60;

    //     $result = [];

    //     if ($cont20 > "0") {
    //         $result[] = "{$cont20}x20";
    //     }
    //     if ($cont40 > "0") {
    //         $result[] = "{$cont40}x40";
    //     }
    //     if ($cont45 > "0") {
    //         $result[] = "{$cont45}x45";
    //     }
    //     if ($cont60 > "0") {
    //         $result[] = "{$cont60}x60";
    //     }
    //     $format = implode(", ", $result);
    //     return $format;
    // }

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true];
    }

    public function custom_Party2($id)
    {
        $container = \DB::table("t_buku_order_d_npwp")
            ->leftJoin('set.m_general as mg','mg.id','t_buku_order_d_npwp.ukuran')
            ->select(\DB::raw('COUNT("ukuran") as jumlah,mg.deskripsi as ukuran_value'))
            ->where("t_buku_order_id", $id)
            ->groupBy("ukuran_value")
            ->get();

        $str = [];
        $count = 0;
        foreach ($container as $cont) {
            $str[$count] = $cont->jumlah . "x" . $cont->ukuran_value;
            $count += 1;
        }
        $format = implode(", ", $str);

        // $cont20 = (string) $container[0]->jumlah_cont_20;
        return $format;
    }

    public function custom_detailbo_container(){
        $id = request("id");

        // $result = \DB::table('t_buku_order as tbo')
        // ->leftJoin('t_buku_order_d_npwp as tbodn', 'tbo.id', '=', 'tbodn.t_buku_order_id')
        // ->leftJoin('t_spk_angkutan as tsa', 'tbodn.id', '=', 'tsa.t_buku_order_1_id')
        // ->select('tbo.id as buku_order_id', 'tbodn.id as buku_order_detail_id', 'tbodn.t_buku_order_id', 'tsa.*')
        // ->where('tbo.id', $id)
        // ->where('tsa.status', 'POST')
        // ->orderBy('tsa.t_buku_order_1_id', 'asc')
        // ->get();
        $nama_angkutan_tia=\DB::table('m_supplier as ms')->where('ms.nama','TIA SENTOSA MAKMUR')->first();
        // trigger_error(json_encode($nama_angkutan_tia->id));

        $result = \DB::table('t_buku_order_d_npwp as tbodn')
        ->leftJoin('t_buku_order as tbo', 'tbo.id', '=', 'tbodn.t_buku_order_id')
        ->leftJoin('t_spk_angkutan as tsa', 'tsa.t_detail_npwp_container_1_id', '=', 'tbodn.id')
        ->leftJoin('t_spk_angkutan as tsa2', 'tsa2.t_detail_npwp_container_2_id', '=', 'tbodn.id')
        ->leftJoin('set.m_general as mg', 'mg.id', '=', 'tbo.pelabuhan_id')
        ->leftJoin('set.m_general as mg2','mg2.id', '=', 'tsa.head')
        ->leftJoin('set.m_general as mg3','mg3.id', '=', 'tsa.trip_id')
        ->leftJoin('set.m_general as mg4','mg4.id', '=', 'tsa2.head')
        ->leftJoin('set.m_general as mg5','mg5.id', '=', 'tsa2.trip_id')
        ->leftJoin('set.m_general as mg6','mg6.id', '=', 'tbodn.ukuran')
        ->select(
            'tbo.id as buku_order_id',
            'tbo.nama_kapal','tbo.tanggal_pengkont',
            'tbodn.id as buku_order_detail_id',
            'tbodn.t_buku_order_id',
            'tbo.pelabuhan_id',
            'tbodn.no_prefix', 'tbodn.no_suffix','tbodn.depo as depo',
            'tbodn.ukuran as ukuran_cont_id',
            // 'tsa.*','tsa.trip_id as trip', 'tsa.catatan as spk_catatan',
            'mg.deskripsi as nama_pelabuhan',
            // 'mg2.deskripsi as head_desc', 'mg3.deskripsi as trip_desc',
            // 'mg4.deskripsi as head_desc', 'mg5.deskripsi as trip_desc',

            \DB::raw("CONCAT(tbodn.no_prefix, '-', tbodn.no_suffix) as no_container"),
            // \DB::raw("COALESCE(tsa.no_spk, 'Angkutan Luar') as no_spk_new"),
            // \DB::raw("to_char(tsa.tanggal_in,'DD/MM/YYYY') as tanggal_in_new"),
            // \DB::raw("to_char(tsa.tanggal_out,'DD/MM/YYYY') as tanggal_out_new"),
            \DB::raw("to_char(tbo.tanggal_pengkont,'DD/MM/YYYY') as tanggal_pengkont_new"),
            
            // Memilih head_desc berdasarkan kondisi tsa.no_spk dan tsa2.no_spk
            \DB::raw("
                CASE 
                    WHEN tsa.no_spk IS NOT NULL THEN mg2.kode 
                    WHEN tsa2.no_spk IS NOT NULL THEN mg4.kode 
                    ELSE NULL
                END AS head_kode
            "),
            // Memilih trip_desc berdasarkan kondisi tsa.no_spk dan tsa2.no_spk
            \DB::raw("
                CASE 
                    WHEN tsa.no_spk IS NOT NULL THEN mg3.deskripsi 
                    WHEN tsa2.no_spk IS NOT NULL THEN mg5.deskripsi 
                    ELSE NULL
                END AS trip_desc
            "),
            // Menentukan no_spk_new berdasarkan kondisi tsa.no_spk dan tsa2.no_spk
            \DB::raw("
                CASE 
                    WHEN tsa.no_spk IS NOT NULL THEN tsa.no_spk 
                    WHEN tsa2.no_spk IS NOT NULL THEN tsa2.no_spk 
                    ELSE 'Angkutan Luar' 
                END AS no_spk_new
            "),

            // Menentukan tanggal_in_new berdasarkan kondisi tsa.tanggal_in dan tsa2.tanggal_in
            \DB::raw("
                CASE 
                    WHEN tsa.tanggal_in IS NOT NULL THEN to_char(tsa.tanggal_in, 'DD/MM/YYYY')
                    WHEN tsa2.tanggal_in IS NOT NULL THEN to_char(tsa2.tanggal_in, 'DD/MM/YYYY')
                    ELSE NULL
                END AS tanggal_in_new
            "),
            // Menentukan tanggal_out_new berdasarkan kondisi tsa.tanggal_out dan tsa2.tanggal_out
            \DB::raw("
                CASE 
                    WHEN tsa.tanggal_out IS NOT NULL THEN to_char(tsa.tanggal_out, 'DD/MM/YYYY')
                    WHEN tsa2.tanggal_out IS NOT NULL THEN to_char(tsa2.tanggal_out, 'DD/MM/YYYY')
                    ELSE NULL
                END AS tanggal_out_new
            "),
            \DB::raw("
            CASE 
                WHEN tsa2.no_spk IS NULL THEN tsa.trip_id 
                WHEN tsa.no_spk IS NULL THEN tsa2.trip_id 
                ELSE NULL 
            END AS trip
            "),
            // Memilih head berdasarkan kondisi tsa.head dan tsa2.head
            \DB::raw("
                CASE 
                    WHEN tsa.no_spk IS NOT NULL THEN tsa.head 
                    WHEN tsa2.no_spk IS NOT NULL THEN tsa2.head 
                    ELSE NULL
                END AS head
            "),
            \DB::raw("
                CASE 
                    WHEN tsa2.no_spk IS NULL THEN tsa.catatan 
                    WHEN tsa.no_spk IS NULL THEN tsa2.catatan 
                    ELSE NULL
                END AS spk_catatan
            "),
            \DB::raw("
                CASE 
                    WHEN tsa2.no_spk IS NULL THEN tsa.id
                    WHEN tsa.no_spk IS NULL THEN tsa2.id 
                    ELSE NULL
                END AS id
            "),
            // \DB::raw("
            //     CASE 
            //         WHEN tsa2.no_spk IS NULL THEN tsa.depo
            //         WHEN tsa.no_spk IS NULL THEN tsa2.depo
            //         ELSE NULL
            //     END AS depo
            // "),
            \DB::raw("
                CASE 
                    WHEN tsa2.no_spk IS NULL THEN tsa.sektor1
                    WHEN tsa.no_spk IS NULL THEN tsa2.sektor1 
                    ELSE NULL
                END AS sektor
            "),
            \DB::raw("
                CASE 
                    WHEN tsa2.no_spk IS NULL THEN tsa.waktu_out
                    WHEN tsa.no_spk IS NULL THEN tsa2.waktu_out
                    ELSE NULL
                END AS waktu_out
            "),
            \DB::raw("
                CASE 
                    WHEN tsa2.no_spk IS NULL THEN tsa.waktu_in
                    WHEN tsa.no_spk IS NULL THEN tsa2.waktu_in
                    ELSE NULL
                END AS waktu_in
            "),
            \DB::raw("
                CASE 
                    WHEN tsa2.no_spk IS NULL THEN tsa.total_sangu
                    WHEN tsa.no_spk IS NULL THEN tsa2.total_sangu
                    ELSE NULL
                END AS total_sangu
            "),
            // \DB::raw("
            //     CASE 
            //         WHEN tsa2.no_spk IS NULL AND tsa.no_spk IS NULL THEN NULL
            //         ELSE $nama_angkutan_tia->id
            //     END AS m_supplier_id
            // "),
        )
        ->where('tbo.id', $id)
        ->where(function($query) {
            $query->where('tsa.status', 'APPROVED')
                ->orWhereNull('tsa.status')
                ->orWhere('tsa2.status', 'APPROVED')
                ->orWhereNull('tsa2.status');
        })
        ->get();

        return $result;
    }

    public function scopeGetById($model)
    {
        $id = request("id");

        return $model->with('t_angkutan_d.t_spk')->where('t_angkutan.id',$id);
    }

    
    function minusDate($tglin,$tglout){
        $date1 = \DateTime::createFromFormat('d/m/Y', $tglin);
        $date2 = \DateTime::createFromFormat('d/m/Y', $tglout);
        $diff = $date1->diff($date2);
        return $diff->days;
    }

    function dateDatabaseFormat($tanggal){
        $getData = \DateTime::createFromFormat('d/m/Y', $tanggal);
        
        if ($getData) {
            return $getData;
        } else {
            return false; // or handle the error as needed
        }
    }

    function weekDateRange($tanggal_out1, $tanggal_in1) {
        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($tanggal_out1, $interval ,$tanggal_in1);
        $countWeek = 0;
        $fmtday;
        $resultDay = [];
        foreach($daterange as $date){
            $fmtday = $date->format("D");
            $resultDay[] = $fmtday;
                
        }
        array_push($resultDay,$tanggal_in1->format("D"));

        foreach($resultDay as $single){
            if($single == "Sat"){
                $countWeek += 1;
            }
        } 


        // foreach($resultDate as $single){
        //     $fmtday = $single->format("D");
        //     $resultDay[] = $fmtday;
        // }

        // foreach($result as $single){
        //     if($single == "Mon"){
                
        //     }
        // }

        return $countWeek;
    }

    public function custom_getStaple(){
        $req = app()->request;
        $is_stuple = $req->custom_stuple;
        $get_jam_out = $req->jam_out;
        $get_jam_in = $req->jam_in;
        $jam_out = strtotime($get_jam_out);
        $jam_in = strtotime($get_jam_in);

        $tanggal_in = $req->tanggal_in;
        $tanggal_out = $req->tanggal_out;
        
        $tanggal_in1 = $this->dateDatabaseFormat($tanggal_in);
        $tanggal_out1 = $this->dateDatabaseFormat($tanggal_out);
        $week = $this->weekDateRange($tanggal_out1,$tanggal_in1);

        $time_12 = strtotime("12:00:00");
        $time_8 = strtotime("08:00:00");

        $getStaple = "";

        if($is_stuple == true){
            if($week > 0){     
                if($jam_in >= $time_12 && $jam_out >= $time_12){
                    $result = strval($this->minusDate($tanggal_in, $tanggal_out) - (3*$week));
                    $getStaple = $result > 0 ? $result : 0;
                }
                else if($jam_in < $time_12 && $jam_out > $time_8){
                    $result = strval($this->minusDate($tanggal_in, $tanggal_out) - (4*$week));
                    $getStaple = $result > 0 ? $result : 0;
                }
                else{
                    $getStaple = "-";
                }
            }
            else{
                if($jam_out > $time_12 && $jam_in <= $time_12 || $jam_out <= $time_12 && $jam_in <= $time_12 ){
                    $result = strval($this->minusDate($tanggal_in, $tanggal_out) - 3);
                    $getStaple = $result > 0 ? $result : 0;
                }
                else if($jam_out > $time_12 && $jam_in > $time_12 || $jam_out <= $time_12 && $jam_in > $time_12){
                    $result = strval($this->minusDate($tanggal_in, $tanggal_out) - 2);
                    $getStaple = $result > 0 ? $result : 0;
                }
                else{
                    $getStaple = "-";
                }
                
            }
        }
        else{
            if($jam_out > $time_12 && $jam_in <= $time_12 || $jam_out <= $time_12 && $jam_in <= $time_12 ){
                $result = strval($this->minusDate($tanggal_in, $tanggal_out));
                $getStaple = $result > 0 ? $result : 0;
            }
            else if($jam_out > $time_12 && $jam_in > $time_12 || $jam_out <= $time_12 && $jam_in > $time_12){
                $result = strval($this->minusDate($tanggal_in, $tanggal_out));
                $getStaple = $result > 0 ? $result : 0;
            }
            else{
                $getStaple = "-";
            }
        }
        return ['staple_result'=>$getStaple,
                'day_tanggal_in'=>$tanggal_in1,
                'day_tanggal_out'=>$tanggal_out1,
                'is_stuple'=>$is_stuple,
                'count_week'=>$week
                ];
    }

    
    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $party = $this->custom_Party2($arrayData["t_buku_order_id"]);
        $status = "DRAFT";
        $req = app()->request;
        if ($req->post) {
            $status = "POST";
        }
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft Angkutan"),
            "party" => $party,
            "no_angkutan" => $this->helper->generateNomor("Angkutan"),
            "status" => $status,
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function updateBefore( $model, $arrayData, $metaData, $id=null )
    {
        $party = $this->custom_Party2($arrayData["t_buku_order_id"]);
        $status = $arrayData['status'];
        $req = app()->request;
        if ($req->post) {
            $status = "POST";
        }
        $newData = [
            "status" => $status,
            "party" => $party,
        ];
        $newArrayData  = array_merge( $arrayData,$newData );
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }
    
    
}
