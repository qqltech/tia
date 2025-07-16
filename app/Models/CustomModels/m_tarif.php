<?php

namespace App\Models\CustomModels;

class m_tarif extends \App\Models\BasicModels\m_tarif
{
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }

    public $fileColumns = [
        /*file_column*/
        "tt_elektronik"
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    // public function createBefore($model, $arrayData, $metaData, $id = null)
    // {
    //     $newData = [
    //         "no_tarif" => $this->helper->generateNomor("Tarif"),
    //     ];

    //     $newArrayData = array_merge($arrayData, $newData);
    //     return [
    //         "model" => $model,
    //         "data" => $newArrayData,
    //         // "errors" => ['error1']
    //     ];
    // }

    public function updateAfter( $model, $arrayData, $metaData, $id=null )
    {        
        $this->where('m_customer_id', $arrayData['m_customer_id'])->where('tipe_tarif', $arrayData['tipe_tarif'])->update([
            'tarif_ppjk'=> $arrayData['tarif_ppjk']
        ]);
    }
    
    public function scopeGetPPN($model){
        return $model->with('m_tarif_d_jasa');
    }

    public function rules(){
        return [
            "no_tarif" => "required"
        ];
    }

    // public function createValidator(){
    //     return $this->rules();
    // }

    // public function updateValidator(){
    //     return $this->rules();
    // }
    
    
}
