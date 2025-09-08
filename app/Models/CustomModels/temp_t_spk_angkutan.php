<?php

namespace App\Models\CustomModels;

class temp_t_spk_angkutan extends \App\Models\BasicModels\temp_t_spk_angkutan
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}