<?php

namespace App\Models\CustomModels;

class t_bkk_non_order extends \App\Models\BasicModels\t_bkk_non_order
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
            "no_bkk"=>$this->helper->generateNomor("No BKK Non Order"),
            "no_draft"=>$this->helper->generateNomor("Draft BKK Non Order"),
            "status"=>"DRAFT"
        ];

      $newArrayData  = array_merge( $arrayData,$newData );
      return [
          "model"  => $model,
          "data"   => $newArrayData,
          // "errors" => ['error1']
      ];
    }
    
    
}