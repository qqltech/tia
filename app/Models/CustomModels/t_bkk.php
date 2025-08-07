<?php

namespace App\Models\CustomModels;

class t_bkk extends \App\Models\BasicModels\t_bkk
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
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft BKK"),
            "no_bkk" => $this->helper->generateNomor("Nomor BKK"),
            "status" => "DRAFT",
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function custom_print()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "PRINTED"]);
        return ["success" => true];
    }

    // public function updateBefore($model, $arrayData, $metaData, $id = null)
    // {
    //     $newData = [
    //         "tanggal" => date("Y-m-d"),
    //     ];
    //     $newArrayData = array_merge($arrayData, $newData);
    //     return [
    //         "model" => $model,
    //         "data" => $newArrayData,
    //         // "errors" => ['error1']
    //     ];
    // }

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true, "message" => "Post Data Berhasil"];
    }

    public function custom_send_multiple_approval_bkk($req)
    {
        $items = $req->items ?? ($req->id ?? []);

        if (!is_array($items)) {
            $items = [$items];
        }

        if (count($items) === 0) {
            return $this->helper->responseCatch(
                ["message" => "Data 'items' tidak ditemukan dalam request"],
                400
            );
        }

        \DB::beginTransaction();

        try {
            foreach ($items as $id) {
                $trx = \DB::table("t_bkk")
                    ->where("id", $id)
                    ->first();
                if (!$trx) {
                    throw new \Exception("Data ID $id tidak ditemukan");
                }

                $result = $this->createAppTicket($id);
                if (!$result) {
                    throw new \Exception("Gagal membuat approval untuk ID $id");
                }

                // Update status setelah berhasil membuat tiket approval
                \DB::table("t_bkk")
                    ->where("id", $id)
                    ->update(["status" => "IN APPROVAL"]);
            }

            \DB::commit();
            return response()->json([
                "message" => "Data yang telah dipilih berhasil diajukan approval!",
                "success_ids" => $items,
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch(
                ["message" => "Gagal mengajukan approval: " . $e->getMessage()],
                500
            );
        }
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
            $req = app()->request;
            $spd = t_bkk::find($req->id);
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
        $trx = \DB::table("t_bkk")->find($tempId);
        $conf = [
            "app_name" => "APPROVAL BKK",
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan BKK (Non Kasbon)",
            "form_name" => "t_bkk_non_kasbon",
            "trx_nomor" => $trx->no_bkk,
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

    public function custom_multi_progress($req)
    {
        \DB::beginTransaction();

        try {
            foreach ($req->items as $item) {
                $conf = [
                    "app_id" => $item["id"],
                    "app_type" => $item["type"] ?? $req->type, // fallback ke request utama
                    "app_note" => $item["note"] ?? $req->note, // fallback juga
                ];

                $app = $this->approval->approvalProgress($conf, true);
                if ($app->status) {
                    $data = $this->find($app->trx_id);
                    if ($app->finish) {
                        $data->update([
                            "status" => $conf["app_type"],
                        ]);

                        if ($conf["app_type"] == "APPROVED") {
                            $this->autoJurnal($data->id);
                        }
                    } else {
                        $data->update([
                            "status" => "IN APPROVAL",
                        ]);
                    }
                }
            }

            \DB::commit();
            return $this->helper->customResponse(
                "Proses multi-approval berhasil"
            );
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
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
                        "status" => $req->type,
                    ]);
                    if ($req->type == "APPROVED") {
                        $this->autoJurnal($data->id, false);
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

    private function autoJurnal($id, $typeApproveMulti = false)
    {
        // debet = detil, credit = header.
        $trx = \DB::selectOne("select a.* from t_bkk a where a.id = ?", [$id]);

        if (!$trx) {
            return ["status" => true];
        }

        $getdebet = \DB::select(
            "select cbkd.m_coa_id, cbkd.nominal as amount, cbkd.keterangan from t_bkk cbk
        join t_bkk_d cbkd on cbkd.t_bkk_id = cbk.id
        where cbk.id = ?",
            [$id]
        );

        $seq = 0;
        $debetArr = [];
        $amount = 0;

        foreach ($getdebet as $dbt) {
            $debetArr[] = (object) [
                "m_coa_id" => $dbt->m_coa_id,
                "seq" => $seq + 1,
                "debet" => (float) $dbt->amount,
                "desc" => $dbt->keterangan,
            ];
            $amount += (float) $dbt->amount;
            $seq++;
        }

        $creditArr = [];

        $credit = new \stdClass();
        $credit->m_coa_id = $trx->m_akun_pembayaran_id;
        $credit->seq = 1;
        $credit->credit = (float) @$amount ?? 0;
        $credit->desc = $trx->keterangan;
        $creditArr[] = $credit;

        $obj = [
            "date" => $trx->tanggal,
            "form" => "BKK (Non Kasbon)",
            "ref_table" => "t_bkk",
            "ref_id" => $trx->id,
            "ref_no" => $trx->no_bkk,
            "desc" => $trx->keterangan,
            "m_business_unit_id" => $trx->m_business_unit_id,
            "no_reference" => $trx->no_reference,
            "detail" => array_merge($debetArr, $creditArr),
        ];

        $check_r_gl = \DB::selectOne(
            "select a.* from r_gl a where a.ref_table = 't_bkk' AND a.ref_id = ?",
            [$trx->id]
        );

        if ($check_r_gl && $typeApproveMulti) {
            return ["status" => true];
        } else {
            $r_gl = new r_gl();
            $data = $r_gl->autoJournal($obj);
        }
        return ["status" => true];
    }
}
