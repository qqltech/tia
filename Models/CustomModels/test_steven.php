<?php

namespace App\Models\CustomModels;

class test_steven extends \App\Models\BasicModels\test_steven
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function scopeWhere($model){
        $req = request('nama_barang');
        $model->where('nama_barang','LIKE','%'.$req.'%');
    }

    public function transformRowData( array $row )
    {
        $newData = [
            "data_baru" => "New Data" 
        ];
        return array_merge( $row, $newData );
    }

    public function custom_create_data(){
        $request = app()->request;
        test_steven::create($request->all());
        return [
            'message' => 'success'
        ];
    }
}