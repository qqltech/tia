<?php

namespace App\Models\CustomModels;

class t_bkk_non_order_d extends \App\Models\BasicModels\t_bkk_non_order_d
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}