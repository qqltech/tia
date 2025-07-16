<?php

namespace App\Models\CustomModels;

class m_tarif_komisi_undername extends \App\Models\BasicModels\m_tarif_komisi_undername
{    
    private $helper;
    public function __construct()
    {
        $this->helper = getCore("Helper");
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $newData = [
            "kode_tarif_komisi_undername" => $this->helper->generateNomor("No Tarif Komisi Undername")
        ];
      $newArrayData  = array_merge( $arrayData,$newData );
      return [
          "model"  => $model,
          "data"   => $newArrayData,
          // "errors" => ['error1']
      ];
    }
}