<?php

namespace App\Models\CustomModels;

class t_buku_penyesuaian extends \App\Models\BasicModels\t_buku_penyesuaian
{    
    private $helper;
    private $approval;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
        $this->approval = getCore("Approval");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $status = "DRAFT";
        $req = app()->request;
        if($req->post){
            $status = "POST";
        }
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft BP"),
            "no_buku_penyesuaian" => $this->helper->generateNomor("Kode BP"),
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
        $req = app()->request;
        $status = $req->post ? "POST" : $arrayData['status'];
        
        $newData = [
            "tanggal" => date("Y-m-d"),
            "status" => $status
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }
    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true];
    }
}