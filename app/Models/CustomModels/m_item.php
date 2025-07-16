<?php

namespace App\Models\CustomModels;

class m_item extends \App\Models\BasicModels\m_item
{    
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        
        $newData = [
            "kode" => $this->helper->generateNomor("Item"),
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function scopeGetQTY($model){
        $model->leftJoin('v_stock_item', 'v_stock_item.m_item_id', '=', 'm_item.id')->addSelect('v_stock_item.qty_stock');
    }

    public function scopeGetLPB($model){
        $model->leftJoin('t_lpb_d', 't_lpb_d.m_item_id', '=', 'm_item.id')->addSelect('t_lpb_d.*');
    }
}