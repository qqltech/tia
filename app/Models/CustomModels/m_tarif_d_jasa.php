<?php

namespace App\Models\CustomModels;

class m_tarif_d_jasa extends \App\Models\BasicModels\m_tarif_d_jasa
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function scopeDetailNPWP($model){
        return $model->join('t_buku_order_d_npwp');
    } 
}