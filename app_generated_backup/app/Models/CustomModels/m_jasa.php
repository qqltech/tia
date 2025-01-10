<?php

namespace App\Models\CustomModels;

use App\Models\CustomModels\m_general;
class m_jasa extends \App\Models\BasicModels\m_jasa
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
        $m_gen = new m_general;
        $data_gen = $m_gen->getByGroup("KODE JASA");
        $menu="";
        foreach ($data_gen as $one) {
            // trigger_error($one['deskripsi']);
            if ($arrayData["kode_jasa"] == $one["deskripsi"]) {
                $menu = $one["deskripsi"];
                break;
            }
        }

        $newData = [
            "kode" => $this->helper->generateNomor($menu),
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }
}
