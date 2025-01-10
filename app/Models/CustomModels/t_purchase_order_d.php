<?php

namespace App\Models\CustomModels;

class t_purchase_order_d extends \App\Models\BasicModels\t_purchase_order_d
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function transformArrayData( array $arrayData )
    {
        $req = app()->request;
        if($req->useBundling && !$req->id){
            $newArrayData = [];
            $id = 1;
            foreach($arrayData as $idx => $dt){
                if(!isset($dt['clone_id'])){
                    $dt['clone_id'] = $dt['id'];
                } else {
                    $dt['clone_id'] = $dt['clone_id'];
                }
                if($dt['is_bundling'] == false){
                    for($i = 1; $i <= $dt['quantity']; $i++){
                        $dt['qty'] = 1;
                        $dt['id'] = $id;
                        $newArrayData[] = $dt;
                        $id = $id + 1;
                    }
                } else {
                    $dt['qty'] = $dt['quantity'];
                    $dt['id'] = $id;
                    $newArrayData[] = $dt;
                    $id = $id + 1;
                }
            }
            return $newArrayData;
        } else {
            return $arrayData;
        }
    }

    // public function scopeUseBundling($model)
    // {
    //     return $model->where(function ($query) {
    //             $query->where('is_bundling', false)
    //                 ->orWhereNull('is_bundling');
    //         })->lazy()->flatMap(function ($item) {
    //             if (!$item->is_bundling) {
    //                 // Duplicate items based on quantity
    //                 return collect(range(1, $item->quantity))->map(function () use ($item) {
    //                     return (clone $item)->toArray();
    //                 });
    //             }
                
    //             // If is_bundling is true, return the item as-is
    //             return [$item];
    //         });
    //     }
}