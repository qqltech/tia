<?php

namespace App\Models\CustomModels;

class t_dinas_luar_d extends \App\Models\BasicModels\t_dinas_luar_d
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
            "no_order" => $this->helper->generateNomor("Nomor Order Dinas Luar"),
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }
}