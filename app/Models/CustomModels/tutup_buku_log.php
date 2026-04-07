<?php

namespace App\Models\CustomModels;

class tutup_buku_log extends \App\Models\BasicModels\tutup_buku_log
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}