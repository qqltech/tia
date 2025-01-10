<?php

namespace App\Models\CustomModels;

class m_asset extends \App\Models\BasicModels\m_asset
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
            "kode" => $this->helper->generateNomor("Kode Master Asset")
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
        ];
    }
}