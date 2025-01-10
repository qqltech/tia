<?php

namespace App\Models\CustomModels;

class t_rencana_pembayaran_hutang extends \App\Models\BasicModels\t_rencana_pembayaran_hutang
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

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $status = "DRAFT";
        $req = app()->request;
        if($req->post){
            $status = "POST";
        }

        $data = [
            "no_draft" => $this->helper->generateNomor("DRAFT RPH"),
            "no_rph" => $this->helper->generateNomor("KODE RPH"),
            "status" => $status
        ];

        $newArrayData  = array_merge( $arrayData,$data );
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true, "message"=> "Post Data Berhasil"];
    }
    
}