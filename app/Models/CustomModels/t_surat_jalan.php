<?php

namespace App\Models\CustomModels;

class t_surat_jalan extends \App\Models\BasicModels\t_surat_jalan
{
    private $helper;
    private $approval;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
        $this->approval = getCore("Approval");
    }

    public $fileColumns = [
        "foto_berkas","foto_surat_jalan"
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    function custom_get_no_container(){
        $id = request("id");

        $result = \DB::table('t_buku_order_d_npwp as tbodn')
        ->leftJoin('t_buku_order as tbo','tbo.id','tbodn.t_buku_order_id')
        ->select(
            'tbo.id as buku_order_id',
            'tbodn.*',
            \DB::raw("CONCAT(tbodn.no_prefix,tbodn.no_suffix) as no_cont")
        )
        ->where('tbo.id',$id)
        ->get();

        return $result;
    }

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $result = m_general::where('id',$arrayData['jenis_sj'])->first();
        $tipe_surat_jalan = $arrayData['tipe_surat_jalan'];
        $no_surat_jalan=$this->checkTypeOfSJ($tipe_surat_jalan,$result->kode);
        // trigger_error(json_encode($no_surat_jalan));

        $status = "DRAFT";
        $req = app()->request;
        if($req->post){
            $status = "POST";
        }

        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft Surat Jalan"),
            "no_surat_jalan" => $this->helper->generateNomor($no_surat_jalan),
            "status" => $status,
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function updateBefore($model, $arrayData, $metaData, $id = null)
    {
        
        // $result = m_general::where('id',$arrayData['jenis_sj'])->first();
        // $tipe_surat_jalan = $arrayData['tipe_surat_jalan'];
        // $no_surat_jalan=$this->checkTypeOfSJ($tipe_surat_jalan,$result->kode);

        $req = app()->request;
        $status = $req->post ? "POST" : $arrayData['status'];
        
        $newData = [
            "tanggal" => date("Y-m-d"),
            "status" => $status,
            // "no_surat_jalan" => $this->helper->generateNomor($no_surat_jalan),
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function checkTypeOfSJ($tipe,$jenis){

        //SJ IMPORT
        if($tipe == "IMPORT" && $jenis == "CONTAINER EMPTY"){
            $no_surat_jalan = "Surat Jalan Import Empty";
        }
        else if($tipe == "IMPORT" && $jenis == "CONTAINER FULL"){
            $no_surat_jalan = "Surat Jalan Import Full";
        }
        else if($tipe == "IMPORT" && $jenis == "CONTAINER PP"){
            $no_surat_jalan = "Surat Jalan Import PP";
        }
        //SJ EXPORT & EXPORT S
        else if(($tipe == "EKSPORT" || $tipe == "EKSPORT S" ) && $jenis == "CONTAINER EMPTY"){
            $no_surat_jalan = "Surat Jalan Export Empty";
        }
        else if(($tipe == "EKSPORT" || $tipe == "EKSPORT S" ) && $jenis == "CONTAINER FULL"){
            $no_surat_jalan = "Surat Jalan Export Full";
        }
        else if(($tipe == "EKSPORT" || $tipe == "EKSPORT S" ) && $jenis == "CONTAINER PP"){
            $no_surat_jalan = "Surat Jalan Export PP";
        }
        //SJ OL & OLS
        else if(($tipe == "OL" || $tipe == "OLS" ) && $jenis == "CONTAINER EMPTY"){
            $no_surat_jalan = "Surat Jalan OL Empty";
        }
        else if(($tipe == "OL" || $tipe == "OLS" ) && $jenis == "CONTAINER FULL"){
            $no_surat_jalan = "Surat Jalan OL Full";
        }
        else if(($tipe == "OL" || $tipe == "OLS" ) && $jenis == "CONTAINER PP"){
            $no_surat_jalan = "Surat Jalan OL PP";
        }
        //SJ LOKAL
        else if(($tipe == "LOKAL") && $jenis == "CONTAINER EMPTY"){
            $no_surat_jalan = "Surat Jalan Lokal Empty";
        }
        else if(($tipe == "LOKAL") && $jenis == "CONTAINER FULL"){
            $no_surat_jalan = "Surat Jalan Lokal Full";
        }
        else if(($tipe == "LOKAL") && $jenis == "CONTAINER PP"){
            $no_surat_jalan = "Surat Jalan Lokal PP";
        }
        else{
            $no_surat_jalan = "Surat Jalan";
        }
        return $no_surat_jalan;
    }

    public function custom_post()
    {
        $id = request("id");
        $getSJ = t_surat_jalan::where('id',$id)->first();
        $tipe_surat_jalan = $getSJ->tipe_surat_jalan;
        $result = m_general::where('id',$getSJ->jenis_sj)->first();
        $no_surat_jalan=$this->checkTypeOfSJ($tipe_surat_jalan,$result->kode);
    
        // trigger_error(json_encode($getSJ));
        $status = $this->where("id", $id)->update([
            "status" => "POST",
            "no_surat_jalan" => $this->helper->generateNomor($no_surat_jalan)
            ]);
        return ["success" => true];
    }

    public function custom_print()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "PRINTED"]);
        return ["success" => true];
    }


    public function custom_send_approval()
    {
        $app = $this->createAppTicket(req("id"));
        if (!$app) {
            return $this->helper->customResponse(
                "Terjadi kesalahan, coba kembali nanti",
                400
            );
        }

        if (app()->request->header("Source") != "mobile") {
            $spd = t_surat_jalan::find(req("id"));
            if ($spd) {
                $spd->update([
                    "status" => "IN APPROVAL",
                ]);
            }
        }

        return $this->helper->customResponse(
            "Permintaan approval berhasil dibuat"
        );
    }

    private function createAppTicket($id)
    {
        $tempId = $id;
        $trx = \DB::table('t_surat_jalan')->find($tempId);
        $conf = [
            "app_name" => "APPROVAL SURAT JALAN",
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan Surat Jalan",
            "form_name" => "t_surat_jalan",
            "trx_nomor" => $trx->no_surat_jalan,
            "trx_date" => Date("Y-m-d"),
            "trx_creator_id" => $trx->creator_id,
        ];

        $app = $this->approval->approvalCreateTicket($conf);
        if ($app) {
            return true;
        } else {
            return false;
        }
    }

    public function custom_progress($req)
    {
        // Start a database transaction
        \DB::beginTransaction();

        try {
            $conf = [
                "app_id" => $req->id,
                "app_type" => $req->type, // APPROVED, REVISED, REJECTED,
                "app_note" => $req->note, // alasan approve
            ];

            $app = $this->approval->approvalProgress($conf, true);
            if ($app->status) {
                $data = $this->find($app->trx_id);
                if ($app->finish) {
                    $data->update([
                        "status" => $req->type
                    ]);
                   
                } else {
                    $data->update([
                        "status" => "IN APPROVAL",
                    ]);
                }
            }

            \DB::commit();

            return $this->helper->customResponse("Proses approval berhasil");
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }
    }

    public function custom_detail($req)
    {
        $id = $req->id ?? 66;
        $data = $this->approval->approvalDetail($id);
        return $this->helper->customResponse("OK", 200, $data);
    }
    public function custom_log($req)
    {
        $conf = [
            "trx_id" => $req->id ?? 0,
            "trx_table" => $this->getTable(),
        ];
        $data = $this->approval->approvalLog($conf);
        return response($data);
    }

    public function custom_getPrintData(){
        $req = app()->request;
        
        $user = \Auth::user();
        $user_print = \DB::table('default_users as du')->where('du.id', $user->id)
        ->leftJoin("set.m_kary as kary", 'kary.id', '=', 'du.m_employee_id')
        ->select('du.name', 'kary.nip')->first();

        $data= \DB::select("SELECT
        tsj.no_surat_jalan,
        to_char(tsj.tanggal, 'DD/MM/YYYY') AS tanggal,
        to_char(tsj.tanggal_berangkat, 'DD/MM/YYYY') as tanggal_berangkat,
        tbo.no_buku_order,
        tbo.tipe_order as tipe_order_sj, 
        mg1.kode as pelabuhan_kode,
        tbo.nama_kapal,
        CASE 
            WHEN tbodn.no_prefix IS NULL OR tbodn.no_suffix IS NULL 
            THEN '-' 
            ELSE CONCAT(tbodn.no_prefix, tbodn.no_suffix) 
        END AS no_container,
        mg2.deskripsi as ukuran_container,
        mg3.deskripsi as jenis_container_kode,
        mg4.deskripsi as jenis_sj_deskripsi,
        tsj.lokasi_stuffing,
        mg5.kode as depo_kode,
        tsj.nw,
        tsj.gw,
        tsj.no_seal,
        tsj.tare,
        tsj.catatan,
        tsj.jumlah_print


        FROM t_surat_jalan tsj 
        LEFT JOIN t_buku_order tbo on tsj.t_buku_order_id = tbo.id
        LEFT JOIN set.m_general mg1 on tbo.pelabuhan_id = mg1.id
        LEFT JOIN t_buku_order_d_npwp tbodn on tbodn.id = tsj.t_buku_order_d_npwp_id
        LEFT JOIN set.m_general mg2 on tbodn.ukuran = mg2.id
        LEFT JOIN set.m_general mg3 on tbodn.jenis = mg3.id
        LEFT JOIN set.m_general mg4 on tsj.jenis_sj = mg4.id
        LEFT JOIN set.m_general mg5 on tbodn.depo = mg5.id

        WHERE tsj.id = ?", [@$req->t_sj_id ?? 0]);

        $count_sj = count(@$data ?? []);
        $currentDate = date("d/m/Y");
        $currentTime = date("H:i:s");

        return [
            "user_print" => @$user_print,
            "data" => @$data,
            "count_sj" => @$count_sj,
            "currentDate" => @$currentDate,
            "currentTime" => @$currentTime,
            "jumlah_print" => @$data[0]->jumlah_print
        ];
    }

    public function custom_updatePrintData(){
        $sj_id = request('t_sj_id');

        $get_jumlah_print = t_surat_jalan::where('id',$sj_id)->first();
        $new_jumlah_print = ($get_jumlah_print->jumlah_print + 1);
        $update_jumlah_print = t_surat_jalan::where('id',$sj_id)->update(["jumlah_print"=>$new_jumlah_print]);
    }
}
