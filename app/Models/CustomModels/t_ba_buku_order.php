<?php

namespace App\Models\CustomModels;

class t_ba_buku_order extends \App\Models\BasicModels\t_ba_buku_order
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

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $newData=[
            "no_draft" => $this->helper->generateNomor("Draft BA Buku Order"),
            "no_ba_buku_order" => $this->helper->generateNomor("No BA Buku Order"),
            "status" => "DRAFT",
            "tanggal" => date("Y-m-d")
        ];
      $newArrayData  = array_merge( $arrayData,$newData );
      return [
          "model"  => $model,
          "data"   => $newArrayData,
          // "errors" => ['error1']
      ];
    }

    public function transformRowData( array $row )
    {
        $req = app()->request;
        if($req->getBukuOrder){
            $result = t_buku_order::where('id',$row['t_buku_order_id'])->first();
            $row['no_buku_order'] = $result['no_buku_order'];
        }
        return array_merge( $row, [] );
    }


    public function custom_send_approval_form()
    {
        $id = request('id');
        $app = $this->createAppTicket($id);
        if (!$app) {
            return $this->helper->customResponse(
                "Terjadi kesalahan, coba kembali nanti",
                400
            );
        }

        if (app()->request->header("Source") != "mobile") {
            $spd = t_ba_buku_order::find($id);
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
            $spd = t_ba_buku_order::find(req("id"));
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
        $trx = \DB::table('t_ba_buku_order')->find($tempId);
        $conf = [
            "app_name" => "APPROVAL BERITA ACARA BUKU ORDER",
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan Berita Acara Buku Order",
            "form_name" => "t_ba_buku_order",
            "trx_nomor" => $trx->no_ba_buku_order,
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
            $result = t_ba_buku_order::where('id',$app->trx_id)->first();
            $t_buku_order_id = $result->t_buku_order_id;
            // trigger_error(json_encode($result));

            if ($app->status) {
                $data = $this->find($app->trx_id);
                if ($app->finish) {
                    $data->update([
                        "status" => $req->type
                    ]);
                    t_buku_order::where('id',$t_buku_order_id)->update([
                        "status"=>"DRAFT"
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
    
    
    
}