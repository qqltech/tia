<?php

namespace App\Models\CustomModels;

class t_credit_note_d extends \App\Models\BasicModels\t_credit_note_d
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];
    
    public function scopeGetData($model){
        $id = request('id_param');
        return $model->where('t_credit_note_id',$id);
    }
    // public function custom_GetTagihan(){
    //     $id = request('cn_d_id');
    //     $getTagihanId = t_credit_note_d::where('id',$id)->select('t_tagihan_id')->first();
    //     $getTagihanData = t_tagihan::where("id",$getTagihanId->t_tagihan_id)->select('no_tagihan','id')->first();
    //     return $getTagihanData;
    // }
    
}