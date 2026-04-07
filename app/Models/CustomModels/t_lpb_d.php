<?php

namespace App\Models\CustomModels;

class t_lpb_d extends \App\Models\BasicModels\t_lpb_d
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

   public function scopeGetHargaBarang($query)
    {
        $lpbId = request('t_lpb_id'); 

        return $query

        ->leftJoin('t_lpb as lpb', 'lpb.id', '=', 't_lpb_d.t_lpb_id')
        
        ->leftJoin('t_purchase_order', 't_purchase_order.id', '=', 'lpb.t_po_id')
        
        ->leftJoin('t_purchase_order_d', function($join){
            $join->on('t_purchase_order_d.t_purchase_order_id', '=', 't_purchase_order.id');
            $join->on('t_purchase_order_d.m_item_id', '=', 't_lpb_d.m_item_id');
        })
        ->where('t_lpb_d.t_lpb_id', $lpbId)
        ->addSelect(['t_purchase_order_d.harga as harga_per_barang']);
    }
    
}