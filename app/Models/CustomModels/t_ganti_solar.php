<?php

namespace App\Models\CustomModels;

class t_ganti_solar extends \App\Models\BasicModels\t_ganti_solar
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
        $this->helper->checkIsPeriodClosed($arrayData['tgl']);

        return [
            "model" => $model,
            "data"  => $arrayData
        ];
    }

    public function updateBefore($model, $arrayData, $metaData, $id = null)
    {
        $tgl = $arrayData['tgl'] ?? $model->tgl;

        $this->helper->checkIsPeriodClosed($tgl);

        return [
            "model" => $model,
            "data"  => $arrayData
        ];
    }
}