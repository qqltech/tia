<?php

namespace App\Models\CustomModels;

class t_memo_jurnal_d extends \App\Models\BasicModels\t_memo_jurnal_d
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}