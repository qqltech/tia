<?php

namespace App\Models\CustomModels;

class t_tagihan_d_npwp extends \App\Models\BasicModels\t_tagihan_d_npwp
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function scopeDetailNPWP($model){
        return $model->join('t_buku_order_d_npwp');
    }
    
}