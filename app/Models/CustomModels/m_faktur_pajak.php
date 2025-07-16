<?php

namespace App\Models\CustomModels;

class m_faktur_pajak extends \App\Models\BasicModels\m_faktur_pajak
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    // public function scopeNoFaktur($model){
    //     return $model->leftJoin('m_faktur_pajak_d as det', 'det.m_faktur_pajak_id', '=', 'm_faktur_pajak.id')
    //     ->select('m_faktur_pajak.*', 'det.*');
    // }
}