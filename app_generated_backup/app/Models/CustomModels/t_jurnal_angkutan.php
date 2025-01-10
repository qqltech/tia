<?php

namespace App\Models\CustomModels;

class t_jurnal_angkutan extends \App\Models\BasicModels\t_jurnal_angkutan
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
            "no_draft" => $this->helper->generateNomor("Draft JA"),
            "no_jurnal" => $this->helper->generateNomor("No JA"),
            "no_nota_piutang" => $this->helper->generateNomor("Nota JA")
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }
    
}