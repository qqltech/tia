<?php

namespace App\Models\CustomModels;

class m_tarif_d_lain_lain extends \App\Models\BasicModels\m_tarif_d_lain_lain
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];
}