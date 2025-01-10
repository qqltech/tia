<?php

namespace App\Models\CustomModels;

class t_ba_buku_order extends \App\Models\BasicModels\t_ba_buku_order
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
        $newData=[
            "no_draft" => $this->helper->generateNomor("Draft BA Buku Order"),
            "no_ba_buku_order" => $this->helper->generateNomor("No BA Buku Order"),
            "status" => "DRAFT",
            "tanggal" => date("Y-m-d")
        ];
      $newArrayData  = array_merge( $arrayData,$newData );
      return [
          "model"  => $model,
          "data"   => $newArrayData,
          // "errors" => ['error1']
      ];
    }
    
    
}