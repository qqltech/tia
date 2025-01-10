<?php

namespace App\Models\CustomModels;

class t_nota_rampung_d extends \App\Models\BasicModels\t_nota_rampung_d
{    
    private $helper;
    public function __construct()
    {       
        parent::__construct();
        $this->helper = getCore("Helper");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
    
}