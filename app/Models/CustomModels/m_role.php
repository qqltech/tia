<?php

namespace App\Models\CustomModels;


class m_role extends \App\Models\BasicModels\m_role
{    
    // private $helper;
    public function __construct()
    {
        parent::__construct();
        // $this->helper = getCore("Helper");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    // public function createBefore( $model, $arrayData, $metaData, $id=null )
    // {
    //   $newArrayData  = array_merge( $arrayData,[
    //     // "kode"=> $this->helper->generateNomor("Role")
    //     // "kode"=> $this->helper->generateNomor("Role",true,"kode")
    //   ] );
    //   return [
    //       "model"  => $model,
    //       "data"   => $newArrayData,
    //       // "errors" => ['error1']
    //   ];
    // }

    public function scopeDetail($model){
        return $model->with('m_role_d');
    }
    
}