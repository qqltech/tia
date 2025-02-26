<?php

namespace App\Models\CustomModels;

class t_bon_spk_lain extends \App\Models\BasicModels\t_bon_spk_lain
{    
    public $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function transformRowData( array $row )
    {
        $req = app()->request;
        $data=[];
        if($req->GetData){
            $genzet = m_supplier::where('id',$row['t_spk_lain_lain.genzet'])->first();
            $no_order = t_buku_order::where('id',$row['t_spk_lain_lain.t_buku_order_id'])->first();
            $customer = m_customer::where('id',$row['t_spk_lain_lain.m_customer_id'])->first();
            $data=[
                "genzet"=>$genzet->nama,
                "no_order"=>$no_order->no_buku_order,
                "customer" => $customer->kode
            ];
        }
        return array_merge( $row, $data );
    }
    

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $newData=[
            "no_draft"=>$this->helper->generateNomor("Draft Bon SPK Lain"),
            "no_bsg"=>$this->helper->generateNomor("BSG SPK Lain"),
            "status"=>"DRAFT"
        ];

      $newArrayData  = array_merge( $arrayData,$newData );
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
        return ["success" => true];
    }
}