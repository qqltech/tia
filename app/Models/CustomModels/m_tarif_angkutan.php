<?php

namespace App\Models\CustomModels;

class m_tarif_angkutan extends \App\Models\BasicModels\m_tarif_angkutan
{   
    private $helper; 
    public function __construct()
    {
        parent::__construct();
        // $this->helper = getCore("Helper");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    // public $createAdditionalData = ["creator_id"=>"auth:id"];
    // public $updateAdditionalData = ["last_editor_id"=>"auth:id"];
    
    // public function createBefore( $model, $arrayData, $metaData, $id=null )
    // {
    //   $arrayData['persen_pajak'] = is_string($arrayData['persen_pajak']) ? parse : ;
    //   $newArrayData  = array_merge( $arrayData,[] );
    //   return [
    //       "model"  => $model,
    //       "data"   => $newArrayData,
    //       // "errors" => ['error1']
    //   ];
    // }
}