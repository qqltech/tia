<?php

namespace App\Models\CustomModels;

class t_angkutan_d extends \App\Models\BasicModels\t_angkutan_d
{
    public function __construct()
    {
        parent::__construct();
    }

    public $fileColumns = [
        /*file_column*/
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function transformRowData( array $row )
    {
        if($row['t_spk_id']== null){
            $row['t_spk.no_spk'] = "Angkutan Luar";
        }        
        return array_merge( $row, []);
    }
    


}
