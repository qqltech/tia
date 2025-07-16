<?php

namespace App\Models\CustomModels;

class t_pemakaian_stok_d extends \App\Models\BasicModels\t_pemakaian_stok_d
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}