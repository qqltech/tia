<?php

namespace App\Models\CustomModels;

class t_tutup_buku_d_coa extends \App\Models\BasicModels\t_tutup_buku_d_coa
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}