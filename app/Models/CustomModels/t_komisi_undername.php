<?php

namespace App\Models\CustomModels;

class t_komisi_undername extends \App\Models\BasicModels\t_komisi_undername
{    
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $req = app()->request;
        // if ($req->post) {
        //     $status = "POST";
        // }
        $newData = [
            // "no_draft" => $this->helper->generateNomor("Draft Premi"),
            "no_komisi_undername" => $this->helper->generateNomor("Kode Komisi Undername"),
            "status_id" => 'DRAFT',
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    // public function updateBefore($model, $arrayData, $metaData, $id = null)
    // {
    //     $req = app()->request;
    //     $status = $req->post ? "POST" : $arrayData["status"];

    //     $newData = [
    //         "tanggal" => date("Y-m-d"),
    //         "status" => $status,
    //     ];
    //     $newArrayData = array_merge($arrayData, $newData);
    //     return [
    //         "model" => $model,
    //         "data" => $newArrayData,
    //         // "errors" => ['error1']
    //     ];
    // }

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status_id" => "POST"]);
        return ["success" => true];
    }

    public function custom_complete()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status_id" => "COMPLETED"]);
        return ["success" => true];
    }

    public function custom_print()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status_id" => "PRINTED"]);
        return ["success" => true];
    }

    // public function scopeGetAju($model){
    //     $buku_order_id = request('buku_order_id');
    //     $customer_id = request('customer_id');
    //     $tipe_tarif = request('tipe_tarif');
    //     return $model
    //     ->join('t_buku_order as tbo','tbo.id','t_komisi_undername.t_buku_order_id')
    //     ->join('m_customer','m_customer.id','t_komisi_undername.customer_id')
    //     ->join('t_ppjk','t_ppjk.m_customer_id','m_customer.id')
    //     ->join('m_generate_no_aju_d','m_generate_no_aju_d.id','t_ppjk.no_ppjk_id')
    //     ->where('t_ppjk.t_buku_order_id',$buku_order_id)
    //     ->where('t_ppjk.m_customer_id',$customer_id)
    //     ->where('t_komisi_undername.tipe_komisi',$tipe_tarif)
    //     ->select('m_generate_no_aju_d.no_aju')
    //     ;
    // }
}