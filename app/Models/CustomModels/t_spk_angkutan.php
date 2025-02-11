<?php

namespace App\Models\CustomModels;

class t_spk_angkutan extends \App\Models\BasicModels\t_spk_angkutan
{
    private $helper;
    private $approval;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
        $this->approval = getCore("Approval");
    }

    public $fileColumns = [
        /*file_column*/
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function transformRowData( array $row )
    {
        $req = app()->request;
        $data=[];
        if($req->getNoBukuOrder){
        $result1 = t_buku_order::where('id',$row['t_detail_npwp_container_1.t_buku_order_id'])->first();
        $result2 = t_buku_order::where('id',$row['t_detail_npwp_container_2.t_buku_order_id'])->first();
        // trigger_error(json_encode($result1));
        $data=[
            't_detail_npwp_container_1.no_buku_order'=>@$result1['no_buku_order'],
            't_detail_npwp_container_2.no_buku_order'=>@$result2['no_buku_order']
            ];
        }
        
        return array_merge( $row, $data );
    }
    

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        
        $kode = trim($arrayData["kode"]);
        $result = $model->where("kode", $kode)->first();

        if ($result) {
            return ["errors" => ["Kode Customer sudah dipakai!"]];
        }


        $getid = $this->get_supplier();
        // $checkDataExist1 = $this->where('t_detail_npwp_container_1_id', $arrayData['t_detail_npwp_container_1_id'])->count();
        // $checkDataExist2 = $this->where('t_detail_npwp_container_2_id', $arrayData['t_detail_npwp_container_2_id'])->count();
        
        // if($checkDataExist1 > 0){
        //     return [
        //         "errors"=>['No. Order 1 sudah terpakai']
        //     ];
        // }
        // if($arrayData['t_detail_npwp_container_2_id'] != null ){
        //     if($checkDataExist2 > 0)
        //     return [
        //         "errors"=>['No. Order 2 sudah terpakai']
        //     ];
        // }
        $newData = [
            "no_spk"=>$this->helper->generateNomor("SPK Angkutan"),
            "status"=>"DRAFT",
            "m_supplier_id"=>$getid
        ];
        $newArrayData = array_merge($arrayData,  $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    // public function updateBefore( $model, $arrayData, $metaData, $id=null )
    // {
    //     // trigger_error(json_encode($arrayData));
    //     $checkDB = $this->where('no_spk',$arrayData['no_spk'])->first();
    //     $npwp_1 = $checkDB['t_detail_npwp_container_1_id'];
    //     $npwp_2 = $checkDB['t_detail_npwp_container_2_id'];
    //     $ad_npwp_1 =  $arrayData['t_detail_npwp_container_1_id'];
    //     $ad_npwp_2 = $arrayData['t_detail_npwp_container_2_id'];
    //     $checkDataExist1 = $this->where('t_detail_npwp_container_1_id', $ad_npwp_1)->count();
    //     $checkDataExist2 = $this->where('t_detail_npwp_container_2_id', $ad_npwp_2)->count();
    //     // trigger_error($checkDataExist1);
    //     // trigger_error(json_encode($checkDB['t_detail_npwp_container_2_id']));

    //     if($npwp_1 != $ad_npwp_1 || $npwp_1 == $ad_npwp_1){
    //         if($checkDataExist1 > 1){
    //             return [
    //                 "errors"=>['No. Order 1 sudah terpakai']
    //             ];
    //         }
    //     }
    //     if($ad_npwp_2 != null){    
    //         if($npwp_2 != null || $npwp_2 != $ad_npwp_2 || $npwp_2 == $ad_npwp_2){
    //             if($checkDataExist2 > 1){
    //                 return [
    //                     "errors"=>['No. Order 2 sudah terpakai']
    //                 ];
    //             }
    //         }
    //     }
    //     $newArrayData  = array_merge( $arrayData,[] );
    //     return [
    //         "model"  => $model,
    //         "data"   => $newArrayData,
    //         // "errors" => ['error1']
    //     ];
    // }
    

    function get_supplier(){
        $getid = m_general::where('group','SUPPLIER_DEFAULT')->where('kode','SUPPLIER01')->first();
        $getsupplier = m_supplier::where('nama',$getid->deskripsi)->first();
        $result = $getsupplier->id??0;
        return $result;
    }

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true];
    }


    public function custom_send_approval()
    {
        $app = $this->createAppTicket(req("id"));
        if (!$app) {
            return $this->helper->customResponse(
                "Terjadi kesalahan, coba kembali nanti",
                400
            );
        }

        if (app()->request->header("Source") != "mobile") {
            $spd = t_spk_angkutan::find(req("id"));
            if ($spd) {
                $spd->update([
                    "status" => "IN APPROVAL",
                ]);
            }
        }

        return $this->helper->customResponse(
            "Permintaan approval berhasil dibuat"
        );
    }

    private function createAppTicket($id)
    {
        $tempId = $id;
        $trx = \DB::table('t_spk_angkutan')->find($tempId);
        $conf = [
            "app_name" => "APPROVAL SPK ANGKUTAN",
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan SPK Angkutan",
            "form_name" => "t_spk_angkutan",
            "trx_nomor" => $trx->no_spk,
            "trx_date" => Date("Y-m-d"),
            "trx_creator_id" => $trx->creator_id,
        ];

        $app = $this->approval->approvalCreateTicket($conf);
        if ($app) {
            return true;
        } else {
            return false;
        }
    }

    public function custom_progress($req)
    {
        // Start a database transaction
        \DB::beginTransaction();

        try {
            $conf = [
                "app_id" => $req->id,
                "app_type" => $req->type, // APPROVED, REVISED, REJECTED,
                "app_note" => $req->note, // alasan approve
            ];

            $app = $this->approval->approvalProgress($conf, true);
            if ($app->status) {
                $data = $this->find($app->trx_id);
                if ($app->finish) {
                    $data->update([
                        "status" => $req->type
                    ]);
                   
                } else {
                    $data->update([
                        "status" => "IN APPROVAL",
                    ]);
                }
            }

            \DB::commit();

            return $this->helper->customResponse("Proses approval berhasil");
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }
    }

    public function custom_detail($req)
    {
        $id = $req->id ?? 66;
        $data = $this->approval->approvalDetail($id);
        return $this->helper->customResponse("OK", 200, $data);
    }
    public function custom_log($req)
    {
        $conf = [
            "trx_id" => $req->id ?? 0,
            "trx_table" => $this->getTable(),
        ];
        $data = $this->approval->approvalLog($conf);
        return response($data);
    }

    public function scopeTipe($model){
        return $model->addSelect('t_buku_order_d_npwp.tipe','m_general.deskripsi')
        ->join('t_buku_order_d_npwp','t_buku_order_d_npwp.id',"$this->table.t_detail_npwp_container_1_id")
        ->join('set.m_general','m_general.id',"t_buku_order_d_npwp.tipe");
    }

    public function custom_print()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "PRINTED"]);
        return ["success" => true];
    }
     
}
