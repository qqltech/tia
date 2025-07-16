<?php

namespace App\Models\CustomModels;

class t_pemakaian_stok extends \App\Models\BasicModels\t_pemakaian_stok
{
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }

    public $fileColumns = [
        /*file_column*/
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $newData = [
            "no_pemakaian_stok" => $this->helper->generateNomor("No Pemakaian Stok"),
            "tanggal" => date("Y-m-d"),
            "status" => "DRAFT"
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }
}
