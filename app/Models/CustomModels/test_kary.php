<?php

namespace App\Models\CustomModels;

class test_kary extends \App\Models\BasicModels\test_kary
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ 'foto_kary' ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}