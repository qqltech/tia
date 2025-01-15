<?php

namespace App\Models\CustomModels;

class t_buku_order_detber extends \App\Models\BasicModels\t_buku_order_detber
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ "foto_berkas" ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}