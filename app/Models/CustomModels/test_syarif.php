<?php

namespace App\Models\CustomModels;

class test_syarif extends \App\Models\BasicModels\test_syarif
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function scopeWith(){
        return $this->with('test_syarif_d');
    }

    public function transformRowData( array $row )
    {
        $newData = [
            "new data" => '1'
        ];
        return array_merge( $row, $newData );
    }

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $new = [
            "nama_barang" => $arrayData['nama_barang'] . " +1"
        ];

        $newArrayData  = array_merge( $arrayData,$new );
        return [
          "model"  => $model,
          "data"   => $newArrayData,
          // "errors" => ['error1']
        ];
    }
        
}