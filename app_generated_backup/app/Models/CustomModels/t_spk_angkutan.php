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

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $getid = $this->get_supplier();
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
}
