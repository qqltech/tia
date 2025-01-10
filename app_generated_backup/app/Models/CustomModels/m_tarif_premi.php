<?php

namespace App\Models\CustomModels;

class m_tarif_premi extends \App\Models\BasicModels\m_tarif_premi
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

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $newData = [
            "no_tarif_premi" => $this->helper->generateNomor("Tarif Premi"),
            "kode_jalan" => $this->helper->generateNomor("Kode Jalan"),
        ];
      $newArrayData  = array_merge( $arrayData,$newData );
      return [
          "model"  => $model,
          "data"   => $newArrayData,
          // "errors" => ['error1']
      ];
    }
    
    
}