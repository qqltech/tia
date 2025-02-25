<?php

namespace App\Models\CustomModels;

class t_spk_lain extends \App\Models\BasicModels\t_spk_lain
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
    

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $newData = [
            "no_draft"=>$this->helper->generateNomor("Draft SPK Lain"),
            "no_spk"=>$this->helper->generateNomor("SPK Lain"),
            "status"=>"DRAFT"
        ];
        $newArrayData = array_merge($arrayData,  $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function custom_print()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "PRINTED"]);
        return ["success" => true];
    }


    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true];
    }

    public function custom_inProcess()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "IN PROCESS"]);
        return ["success" => true];
    }

    public function custom_complete()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "COMPLETED"]);
        return ["success" => true];
    }
}