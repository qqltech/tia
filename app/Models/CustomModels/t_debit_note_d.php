<?php

namespace App\Models\CustomModels;

class t_debit_note_d extends \App\Models\BasicModels\t_debit_note_d
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public function scopeGetData($model){
        $id = request('id_param');
        return $model->where('t_debit_note_id',$id);
    }

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}