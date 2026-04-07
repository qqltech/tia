<?php

namespace App\Models\CustomModels;

class r_stock_d extends \App\Models\BasicModels\r_stock_d
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function scopeWithHeader($query)
    {
        return $query->join('r_stock as stok', 'stok.id', '=', 'r_stock_d.r_stock_id')
                    ->select('r_stock_d.*', 'stok.*')
                    ->selectRaw("
                                    CASE 
                                        WHEN r_stock_d.typemin = 1 THEN 'Masuk'
                                        WHEN r_stock_d.typemin = 0 THEN 'Keluar'
                                    END as tipe_transaksi
                                ");
    }

}