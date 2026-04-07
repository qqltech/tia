<?php

namespace App\Models\CustomModels;

class t_asset_disposal extends \App\Models\BasicModels\t_asset_disposal
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $this->helper->checkIsPeriodClosed($arrayData['tanggal']);

        return [
            "model" => $model,
            "data"  => $arrayData
        ];
    }

    public function updateBefore($model, $arrayData, $metaData, $id = null)
    {
        $tanggal = $arrayData['tanggal'] ?? $model->tanggal;

        $this->helper->checkIsPeriodClosed($tanggal);

        return [
            "model" => $model,
            "data"  => $arrayData
        ];
    }
}