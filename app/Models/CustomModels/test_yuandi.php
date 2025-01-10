<?php

namespace App\Models\CustomModels;

class test_yuandi extends \App\Models\BasicModels\test_yuandi
{
    public function __construct()
    {
        parent::__construct();
    }

    public $fileColumns = [
        /*file_column*/
    ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function scopeWith()
    {
        return $this->with("test_yuandi_2");
    }


    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $new = [
            "nama_barang" => $arrayData["nama_barang"] . " +1",
        ];
        $newArrayData = array_merge($arrayData, $new);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }
}
