<?php

namespace App\Models\CustomModels;

class m_generate_no_aju_d extends \App\Models\BasicModels\m_generate_no_aju_d
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function scopeNoPPJK($model){
        $req = app()->request;
        if(!$req->id){
            $model->leftJoin('m_generate_no_aju as head', 'head.id', '=', 'm_generate_no_aju_d.m_generate_no_aju_id')
                ->leftJoin('t_ppjk', 't_ppjk.no_ppjk_id', '=', 'm_generate_no_aju_d.id')
                ->whereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('t_ppjk')
                        ->whereRaw('t_ppjk.no_ppjk_id = m_generate_no_aju_d.id')
                        ->where('t_ppjk.status', '=', 'POST');
                })
                ->where(function($query) {
                    $query->where('t_ppjk.status', '=', 'DRAFT')
                        ->orWhereNull('t_ppjk.status');
                })
                ->groupBy('m_generate_no_aju_d.id', 'head.id')
                ->select('head.*', 'm_generate_no_aju_d.*');
        }
    }
}