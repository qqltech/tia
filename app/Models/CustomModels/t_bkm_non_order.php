<?php

namespace App\Models\CustomModels;
use Illuminate\Support\Facades\Validator;

class t_bkm_non_order extends \App\Models\BasicModels\t_bkm_non_order
{
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }

    public $fileColumns = [
        /*file_column*/
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft BKM Non Order"),
            "no_bkm" => $this->helper->generateNomor("Nomor BKM Non Order"),
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

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        $this->autoJurnal($id);
        return ["success" => true, "message" => "Post Data Berhasil"];
    }

    public function custom_multiple_post($req)
    {
        $validator = Validator::make($req->all(), [
            "items" => "required|array",
            "items.*" => "integer|exists:t_bkm_non_order,id",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Validasi gagal",
                    "errors" => $validator->errors(),
                ],
                422
            );
        }

        $validated = $validator->validated();
        $success = [];
        $failed = [];

        foreach ($validated["items"] as $id) {
            try {
                $update = $this->where("id", $id)->update(["status" => "POST"]);

                if ($update) {
                    $this->autoJurnal($id);
                    $success[] = $id;
                } else {
                    $failed[] = [
                        "id" => $id,
                        "reason" =>
                            "Update status gagal atau data tidak ditemukan",
                    ];
                }
            } catch (\Exception $e) {
                $failed[] = [
                    "id" => $id,
                    "reason" => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            "success" => true,
            "message" => "Multiple post data berhasil!",
            "total" => count($validated["items"]),
            "sukses" => count($success),
            "gagal" => count($failed),
            "detail_gagal" => $failed,
        ]);
    }

    private function autoJurnal($id)
    {
        $trx = \DB::selectOne(
            "select a.* from t_bkm_non_order a where a.id = ?",
            [$id]
        );
        if (!$trx) {
            return ["status" => true];
        }

        $getcredit = \DB::select(
            "select cbmd.m_coa_id, cbmd.nominal as amount, cbmd.keterangan from t_bkm_non_order cbm
        join t_bkm_non_order_d cbmd on cbmd.t_bkm_non_order_id = cbm.id
        where cbm.id = ?",
            [$id]
        );

        $seq = 1;
        $creditArr = [];
        $amount = 0;

        foreach ($getcredit as $cr) {
            $creditArr[] = (object) [
                "m_coa_id" => $cr->m_coa_id,
                "seq" => $seq + 1,
                "credit" => (float) $cr->amount,
                "desc" => $cr->keterangan,
            ];
            $amount += (float) $cr->amount;
            $seq++;
        }

        $debetArr = [];

        $debet = new \stdClass();
        $debet->m_coa_id = $trx->m_akun_pembayaran_id;
        $debet->seq = 1;
        $debet->debet = (float) @$amount ?? 0;
        $debet->desc = $trx->keterangan;
        $debetArr[] = $debet;

        $obj = [
            "date" => $trx->tanggal,
            "form" => "BKM Non Order",
            "ref_table" => "t_bkm_non_order",
            "ref_id" => $trx->id,
            "ref_no" => $trx->no_bkm,
            // 'm_cust_id'         => $trx->m_cust_id,
            "m_business_unit_id" => $trx->m_business_unit_id,
            "no_reference" => $trx->no_ref,
            "desc" => $trx->keterangan,
            "detail" => array_merge($debetArr, $creditArr),
        ];

        $r_gl = new r_gl();
        $data = $r_gl->autoJournal($obj);

        return ["status" => true];
    }
}
