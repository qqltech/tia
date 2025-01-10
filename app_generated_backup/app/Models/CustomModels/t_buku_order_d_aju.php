<?php

namespace App\Models\CustomModels;

class t_buku_order_d_aju extends \App\Models\BasicModels\t_buku_order_d_aju
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}