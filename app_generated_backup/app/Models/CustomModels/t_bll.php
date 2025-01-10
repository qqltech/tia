<?php

namespace App\Models\CustomModels;

class t_bll extends \App\Models\BasicModels\t_bll
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
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft BLL"),
            "no_bll" => $this->helper->generateNomor("Nomor BLL"),
            "tanggal" => date("Y-m-d"),
            "status" => "DRAFT",
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
        $newData = [
            "tanggal" => date("Y-m-d"),
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