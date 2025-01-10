<?php

namespace App\Models\CustomModels;

class t_pembayaran_hutang_d extends \App\Models\BasicModels\t_pembayaran_hutang_d
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}