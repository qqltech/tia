<?php

namespace App\Models\CustomModels;

class t_bkm_non_order extends \App\Models\BasicModels\t_bkm_non_order
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
            "no_draft" => $this->helper->generateNomor("Draft BKM Non Order"),
            "no_bkm" => $this->helper->generateNomor("Nomor BKM Non Order"),
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

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true, "message"=> "Post Data Berhasil"];
    }
}