<?php

namespace App\Models\CustomModels;

class t_premi extends \App\Models\BasicModels\t_premi
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
        $status = "DRAFT";
        $req = app()->request;
        if ($req->post) {
            $status = "POST";
        }
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft Premi"),
            "no_premi" => $this->helper->generateNomor("Kode Premi"),
            "status" => $status,
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
        $req = app()->request;
        $status = $req->post ? "POST" : $arrayData["status"];

        $newData = [
            "tanggal" => date("Y-m-d"),
            "status" => $status,
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
        return ["success" => true];
    }

    public function custom_helloworld()
    {
        return ["hello world" => true];
    }

    public function custom_print()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "PRINTED"]);
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
            $spd = t_premi::find(req("id"));
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
        $trx = \DB::table("t_premi")->find($tempId);
        $conf = [
            "app_name" => "APPROVAL PREMI",
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan Premi",
            "form_name" => "t_premi",
            "trx_nomor" => $trx->no_premi,
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
                        "status" => $req->type,
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

    public function custom_laporan()
    {
        // Ambil data dari request
        $supir_id = request("supir_id");
        $start_date = request("start_date");
        $end_date = request("end_date");
        $hutang_supir = request("hutang_supir") ?? 0;
        $hutang_dibayar = request("hutang_dibayar") ?? 0;
        $total_premi_diterima = request("total_premi_diterima") ?? 0;
        $ids = request("id"); // array id data yang dipilih

        // Validasi sederhana, tambahkan validasi sesuai kebutuhan
        if (!$supir_id || !$start_date || !$end_date || !$ids) {
            return $this->helper->customResponse(
                "Data yang dibutuhkan tidak lengkap",
                422
            );
        }

        // Loop data premi yang dipilih dan update/insert ke DB sesuai kebutuhan
        try {
            foreach ((array) $ids as $id) {
                $premi = $this->find($id);
                if ($premi) {
                    // Simpan atau update field baru untuk laporan premi
                    $premi->hutang_supir = $hutang_supir;
                    $premi->hutang_dibayar = $hutang_dibayar;
                    $premi->total_premi_diterima = $total_premi_diterima;
                    $premi->save();
                }
            }
            return $this->helper->customResponse(
                "Laporan premi berhasil disimpan",
                200
            );
        } catch (\Exception $e) {
            return $this->helper->responseCatch($e);
        }
    }

    public function custom_update_premi_terima()
    {
        $ids = request("id"); // array id data premi
        $total_premi_diterima = request("total_premi"); // nilai baru total premi

        if (!$ids || !$total_premi_diterima) {
            return $this->helper->customResponse(
                "ID dan nilai total premi harus diisi",
                422
            );
        }

        $eligiblePremis = [];
        foreach ((array) $ids as $id) {
            $premi = $this->find($id);
            if ($premi && $premi->total_premi > 0) {
                $eligiblePremis[] = $premi;
            }
        }

        if (empty($eligiblePremis)) {
            return $this->helper->customResponse(
                "Tidak ada premi yang eligible (total_premi > 0) untuk pengurangan",
                422
            );
        }

        $remaining = $total_premi_diterima;

        try {
            while ($remaining > 0 && !empty($eligiblePremis)) {
                $count = count($eligiblePremis);
                $share = $remaining / $count;
                $newEligible = [];
                foreach ($eligiblePremis as $premi) {
                    if ($premi->total_premi > $share) {
                        $premi->total_premi -= $share;
                        $remaining -= $share;
                    } else {
                        $remaining -= $premi->total_premi;
                        $premi->total_premi = 0;
                    }
                    if ($premi->total_premi > 0) {
                        $newEligible[] = $premi;
                    }
                }
                $eligiblePremis = $newEligible;
            }

            // Save all changes
            foreach ($eligiblePremis as $premi) {
                $premi->save();
            }
            // Also save those set to 0, but since they are removed, need to collect all
            // Actually, better to collect all premi from start and save at end
            // Wait, modify: collect all premi in a map or array

            // Revised: Use a map to track changes
            $premiMap = [];
            foreach ((array) $ids as $id) {
                $premi = $this->find($id);
                if ($premi) {
                    $premiMap[$id] = $premi;
                }
            }

            $eligible = array_filter($premiMap, function ($p) {
                return $p->total_premi > 0;
            });

            $remaining = $total_premi_diterima;

            while ($remaining > 0 && !empty($eligible)) {
                $count = count($eligible);
                $share = $remaining / $count;
                $newEligible = [];
                foreach ($eligible as $id => $premi) {
                    if ($premi->total_premi > $share) {
                        $premi->total_premi -= $share;
                        $remaining -= $share;
                    } else {
                        $remaining -= $premi->total_premi;
                        $premi->total_premi = 0;
                    }
                    if ($premi->total_premi > 0) {
                        $newEligible[$id] = $premi;
                    }
                }
                $eligible = $newEligible;
            }

            // Save all premi that were in ids
            foreach ($premiMap as $premi) {
                $premi->save();
            }

            return $this->helper->customResponse(
                "Total premi berhasil dikurangi dan didistribusikan sampai habis",
                200
            );
        } catch (\Exception $e) {
            return $this->helper->responseCatch($e);
        }
    }
}
