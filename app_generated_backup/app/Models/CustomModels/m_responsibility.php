<?php

namespace App\Models\CustomModels;

class m_responsibility extends \App\Models\BasicModels\m_responsibility
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function scopeDetail($model){
        return $model->with('m_responsibility_d');
    }
}