<?php

namespace App\Models\CustomModels;

class t_purchase_order extends \App\Models\BasicModels\t_purchase_order
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

    public function rules() {
        return [
            'tipe_po' => 'required',
            'm_supplier_id' => 'required',
            'ppn' => 'required'
        ];
    }

    public function createValidator(){
        return $this->rules();
    }

    public function updateValidator(){
        return $this->rules();
    }

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        // $req = app()->request;
        $status = "DRAFT";

        // if($req->approval){
        //     $status = $this->custom_send_approval2($arrayData['id']);
        // }
        
        
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft Purchase Order"),
            "no_po" => $this->helper->generateNomor("Purchase Order"),
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

    public function custom_post(){
        $id = request("id");
        $status = $this->where("id",$id)->update(["status"=>"POST"]);
        return ["success"=>true];
    }



    // public function custom_send_approval2($id)
    // {
    //     $app = $this->createAppTicket(req($id));
    //     if (!$app) {
    //         return $this->helper->customResponse(
    //             "Terjadi kesalahan, coba kembali nanti",
    //             400
    //         );
    //     }

    //     if (app()->request->header("Source") != "mobile") {
    //         $spd = t_purchase_order::find(req($id));
    //         if ($spd) {
    //             $spd->update([
    //                 "status" => "IN APPROVAL",
    //             ]);
    //         }
    //     }

    //     return $this->helper->customResponse(
    //         "Permintaan approval berhasil dibuat"
    //     );
    // }

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
            $spd = t_purchase_order::find(req("id"));
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
        $trx = \DB::table('t_purchase_order')->find($tempId);
        $conf = [
            "app_name" => "APPROVAL PURCHASE ORDER",
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan Purchase Order",
            "form_name" => "t_purchase_order",
            "trx_nomor" => $trx->no_po,
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

    public function custom_multi_progress($req)
    {
        \DB::beginTransaction();

        try {
            foreach ($req->items as $item) {
                $conf = [
                    "app_id" => $item['id'],
                    "app_type" => $item['type'], // APPROVED, REVISED, REJECTED
                    "app_note" => $item['note'] ?? null,
                ];

                $app = $this->approval->approvalProgress($conf, true);

                if ($app->status) {
                    $data = $this->find($app->trx_id);

                    $data->update([
                        "status" => $app->finish ? $item['type'] : "IN APPROVAL"
                    ]);
                }

                if ($item['type'] === "APPROVED") {
                    $get_trx_id = generate_approval::find($item['id']);
                    if ($get_trx_id) {
                        $trx_id = $get_trx_id->trx_id;
                        t_purchase_order::where('id', $trx_id)->update(['status' => 'APPROVED']);
                    }
                }
            }

            \DB::commit();
            return $this->helper->customResponse("Proses multi-approval berhasil!");

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

    public function scopeCheckDouble($model)
{
    $model->whereNotIn('t_purchase_order.id', function ($sub) {
        $sub->select('t_lpb.t_po_id')
            ->from('t_lpb')
            ->whereNull('t_lpb.deleted_at');
    });
}

    public function scopeGetPOD($query)
    {
        return $query->select(
                't_purchase_order.*',
                't_purchase_order_d.*',
                'm_item.*',
                'uoms.*',
                't_lpb.*',
                // 't_lpb_d.*'
            )
            ->join('t_purchase_order_d', 't_purchase_order.id', '=', 't_purchase_order_d.t_purchase_order_id')
            ->join('m_item', 't_purchase_order_d.m_item_id', '=', 'm_item.id')
            ->join('t_lpb', 't_purchase_order.id', '=', 't_lpb.t_po_id')
            // ->join('t_lpb_d', 't_lpb.id', '=', 't_lpb_d.t_lpb_id')
            ->join('set.m_general as uoms', 'm_item.uom_id', '=', 'uoms.id');
    }


}
