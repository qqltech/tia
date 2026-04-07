<?php

namespace App\Models\CustomModels;
use Carbon\Carbon;

class t_internal extends \App\Models\BasicModels\t_internal
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
        $this->helper->checkIsPeriodClosed($arrayData['tanggal']);
        $status = "DRAFT";
        $req = app()->request;
        if ($req->post) {
            $status = "POST";
        }
        $newData = [
            "no_pemakaian" => $this->helper->generateNomor("Internal Usage"),
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
        $tanggal = $arrayData['tanggal'] ?? $model->tanggal;

        $this->helper->checkIsPeriodClosed($tanggal);

        return [
            "model" => $model,
            "data"  => $arrayData
        ];
    }

    public function createAfter($model, $arrayData, $metaData, $id = null)
    {
        try {
            // Cek apakah ada data detail yang dikirim
            if (
                isset($arrayData["t_internal_d"]) &&
                is_array($arrayData["t_internal_d"])
            ) {
                // Ambil semua m_item_d_id yang tidak null
                $detailIds = collect($arrayData["t_internal_d"])
                    ->pluck("m_item_d_id")
                    ->filter() // hapus yang null
                    ->unique()
                    ->values()
                    ->toArray();

                if (!empty($detailIds)) {
                    \DB::table("m_item_d")
                        ->whereIn("id", $detailIds)
                        ->where("used", false)
                        ->update([
                            "used" => true,
                            "updated_at" => Carbon::now(),
                        ]);

                    \Log::info(
                        "Updated m_item_d used status to true for IDs: " .
                            implode(",", $detailIds)
                    );
                }
            }
        } catch (\Exception $e) {
            \Log::error(
                "Error updating m_item_d used status: " . $e->getMessage()
            );
            // throw $e; // Uncomment if you want the error to bubble up
        }
    }

    public function custom_post()
    {
        $id = request("id");
        \DB::beginTransaction();
        try {
            // INSERT STOCK
            $data = $this->where("id", $id)->first();
            $detail = \DB::table("t_internal_d")
                ->where("t_internal_id", $id)
                ->get();

            $r_stock = r_stock::create([
                "date" => date("Y-m-d"),
                "type" => "INTERNAL USAGE",
                "ref_table" => $this->getTable(),
                "ref_id" => $data->id,
                "ref_no" => $data->no_pemakaian,
                "note" => $data->catatan,
            ]);

            foreach ($detail as $dt) {
                \DB::table("r_stock_d")
                    ->where("m_item_id", $dt->m_item_id)
                    ->lockForUpdate()
                    ->get();

                $view_stock = \DB::table("v_stock_item")
                    ->where("m_item_id", $dt->m_item_id)
                    ->first();

                if (!$view_stock) {
                    throw new \Exception("Stok item tidak ditemukan");
                }

                $qtyOut = (int) $dt->usage;

                if ($view_stock->qty_stock < $qtyOut) {
                    throw new \Exception(
                        "Stok tidak mencukupi untuk item ID {$dt->m_item_id}"
                    );
                }

                // INSERT STOCK DETAIL
                r_stock_d::create([
                    "r_stock_id" => $r_stock->id,
                    "ref_table" => $this->getTable(),
                    "ref_id" => $data->id,
                    "typemin" => 0,
                    "m_item_id" => $dt->m_item_id,
                    "qty_awal" => (int) $view_stock->qty_stock,
                    "price" => (float) $view_stock->price,
                    "price_old" => (float) $view_stock->price_old,
                    "qty_out" => $qtyOut,
                    "qty_sisa" => $view_stock ? $view_stock->qty_sisa : 0,
                    "note" => $dt->catatan,
                ]);
            }

            // END INSERTSTOCK
            $status = $this->where("id", $id)->update(["status" => "POST"]);

            \DB::commit();
            return ["success" => true, "message" => "Data berhasil di Post."];
        } catch (\Exception $e) {
            \DB::rollback();
            return response(
                [
                    "error" => true,
                    "success" => false,
                    "message",
                    $e->getMessage(),
                ],
                422
            );
        }
    }

    public function scopeWithDetail($query)
    {
        return $query
            ->join(
                "t_internal_d as tid",
                "tid.t_internal_id",
                "=",
                "t_internal.id"
            )
            ->leftJoin("set.m_kary as mk", "mk.id", "=", "t_internal.m_kary_id")
            ->leftJoin("m_item as mi", "mi.id", "=", "tid.m_item_id")
            ->select(
                "t_internal.*",
                "t_internal.id as ti_id",
                "t_internal.catatan as cacat",
                "mk.nama",
                "mi.nama_item"
            );
    }
}
