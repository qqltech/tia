<?php

namespace App\Models\CustomModels;

class v_stock_item extends \App\Models\BasicModels\v_stock_item
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function scopeGetRstock($query)
    {
        return $query
            ->join('m_item as mi', 'mi.id', '=', 'v_stock_item.m_item_id')
            ->join('r_stock_d as rsd', 'rsd.m_item_id', '=', 'mi.id')
            ->join('r_stock as rs', 'rs.id', '=', 'rsd.r_stock_id')
            ->select(
                'v_stock_item.*',
                'rsd.note as catatan'
            );
    }
    
}