<?php

namespace App\Models\CustomModels;

class t_tagihan_lain_lain extends \App\Models\BasicModels\t_tagihan_lain_lain
{    
    public function __construct()
    {
        parent::__construct();
        // $this->helper = getCore("Helper");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $checkDuplicate = $this->IsDuplicate($arrayData);
        if($checkDuplicate) return ['errors' => ["No Buku Order Sudah Pernah Dibuat"]];

        $status = "DRAFT";
        $req = app()->request;
        if($req->post){
            $status = "POST";
        }

        $newData = [
            // "no_draft" => $this->helper->generateNomor("Draft Tagihan"),
            // "no_tagihan" => $this->helper->generateNomor("Tagihan"),
            // "total_cost" => $totalCost
            "status"=>$status
        ];

        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function updateBefore( $model, $arrayData, $metaData, $id=null )
    {
        $checkDuplicate = $this->IsDuplicate($arrayData);
        if($checkDuplicate) return ['errors' => ["Data Sudah Pernah Dibuat"]];

        $req = app()->request;
        $status = $req->post ? "POST" : $arrayData['status'];

        $newData=[
            "status" => $status
        ];
        $newArrayData  = array_merge( $arrayData,$newData );
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }
    
    private function IsDuplicate($data){
        $IdBukuOrder = $data['no_buku_order'];
        $getTagihan = $this->where('no_buku_order',$IdBukuOrder)->where('status','POST')->first();
        if($getTagihan){
            return true;
        }else{
            return false;
        }
    }

    public function custom_calculate_tagihan($req){
        $tagihanLain = $req['detailArr3'];
        $idBukuOrder = $req['t_buku_order_id'];
        $ppn = $req['ppn'];
        
        $totalLainArray = $this->lain($tagihanLain, $ppn); 
        $totalLain = $totalLainArray['total'];
        
        // hitung total ppn || non_ppn 
        $totalLainPPN = $totalLainArray['total_ppn'];
        $totalPPN = ($totalLainPPN * ($ppn / 100));
        
        return [
            'total_amount_ppn' => $totalLainPPN,
            'total_ppn' => $totalPPN,
            'total_amount_non_ppn' => $totalLainArray['total_non_ppn'],
            'grand_total_amount' => $totalLain + $totalPPN,
        ];
    }

    private function lain($data, $ppn){ 
        $calculateLain = 0;
        $grandTotalPpn = 0;
        $totalNotPpn = 0;

        foreach($data as $single){
            $totalLain = ($single['tarif_realisasi'] ?? 0) * ($single['qty'] ?? 0);
            
            if(!empty($single['is_ppn'])) { 
                $grandTotalPpn += $totalLain; 
            } else { 
                $totalNotPpn += $totalLain;
            }
            $calculateLain += $totalLain;
        }

        return [
            'total_ppn' => $grandTotalPpn,
            'total_non_ppn' => $totalNotPpn,
            'total' => $calculateLain,
        ];
    }

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true];
    }
}