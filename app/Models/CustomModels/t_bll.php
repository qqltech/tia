<?php

namespace App\Models\CustomModels;

class t_bll extends \App\Models\BasicModels\t_bll
{    
    private $helper;
    private $approval;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
        $this->approval = getCore("Approval");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft BLL"),
            "no_bll" => $this->helper->generateNomor("Nomor BLL"),
            "status" => "DRAFT",
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function updateBefore($model, $arrayData, $metaData, $id = null)
    {
        $newData = [
            "tanggal" => date("Y-m-d"),
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true, "message"=> "Post Data Berhasil"];
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
            $spd = t_bll::find(req("id"));
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
        $trx = \DB::table('t_bll')->find($tempId);
        $conf = [
            "app_name" => "APPROVAL BLL",
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan BLL",
            "form_name" => "t_bll",
            "trx_nomor" => $trx->no_bll,
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
                    if($req->type == 'APPROVED'){
                        $this->autoJurnal($data->id);
                    }
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


    private function autoJurnal($id){
        $trx = \DB::table('t_bll')->find($id);
        if (!$trx) return ['status' => true];

        $getdebet = \DB::select("
            SELECT b.m_coa_id, SUM(b.nominal) as amount
            FROM t_bll_d b
            WHERE b.t_bll_id = ?
            GROUP BY b.m_coa_id
        ", [$id]);

        $seq = 0;
        $debetArr = [];
        $amount = 0;

        foreach($getdebet as $dbt){
            $debetArr[] = (object) [
                "m_coa_id" => $dbt->m_coa_id,
                "seq" => ++$seq,
                "debet" => (float) $dbt->amount,
                "desc" => $trx->keterangan
            ];
            $amount += (float) $dbt->amount;
        }

        $creditArr = [];
        $credit = new \stdClass();
        $credit->m_coa_id = $trx->m_coa_id;
        $credit->seq = ++$seq;
        $credit->credit = $amount;
        $credit->desc = $trx->keterangan;
        $creditArr[] = $credit;

        $obj = [
            'date'      => $trx->tanggal,
            'form'      => "BLL",
            'ref_table' => 't_bll',
            'ref_id'    => $trx->id,
            'ref_no'    => $trx->no_bll,
            'desc'      => $trx->keterangan,
            'detail'    => array_merge($debetArr, $creditArr)
        ];

        $r_gl = new \App\Models\CustomModels\r_gl;
        $data = $r_gl->autoJournal($obj);

        return ['status' => true];
    }

}