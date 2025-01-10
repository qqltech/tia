<?php

namespace App\Models\CustomModels;

class m_generate_no_aju extends \App\Models\BasicModels\m_generate_no_aju
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    // public function scopeNoPPJK($model){
    //     return $model->leftJoin('m_generate_no_aju_d as det', 'det.m_generate_no_aju_id', '=', 'm_generate_no_aju.id')
    //     ->select('m_generate_no_aju.*', 'det.*');
    // }
}