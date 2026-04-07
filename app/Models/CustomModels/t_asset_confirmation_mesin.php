<?php

namespace App\Models\CustomModels;

class t_asset_confirmation_mesin extends \App\Models\BasicModels\t_asset_confirmation_mesin
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}