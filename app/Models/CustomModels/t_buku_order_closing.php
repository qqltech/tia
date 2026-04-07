<?php

namespace App\Models\CustomModels;

use Illuminate\Support\Facades\DB;

class t_buku_order_closing extends \App\Models\BasicModels\t_buku_order_closing
{
    public function __construct()
    {
        parent::__construct();
    }

    public $fileColumns = [
        /*file_column*/
    ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function getOutstandingClosing($m_bu_id, $tahun)
    {
        try {
            $query = DB::table("t_buku_order as trx")
                //->where("trx.m_bu_id", $m_bu_id)
                ->whereYear("trx.tgl", $tahun)
                ->whereNull("trx.deleted_at")
                ->whereNotIn("trx.status", ["POST", "PRINTED", "CLOSED"]);

            $records = $query
                ->select([
                    "trx.id",
                    DB::raw("trx.tgl as date"),
                    DB::raw("trx.status as status_name"),
                    DB::raw("trx.no_buku_order as no"),
                ])
                ->orderBy("trx.tgl", "desc")
                ->get()
                ->values();

            $outstandingList = [];

            if ($records->count() > 0) {
                $outstandingList[] = [
                    "modul" => "buku_order",
                    "nama_transaksi" => "Buku Order",
                    "jumlah" => $records->count(),
                    "details" => $records->map(function ($r) {
                        return (array) $r;
                    }),
                ];
            }

            return [
                "outstanding" => array_values($outstandingList),
            ];
        } catch (\Exception $e) {
            throw new \Exception(
                "Error getOutstandingClosing: " . $e->getMessage()
            );
        }
    }

    public function custom_get_outstanding($req)
    {
        try {
            $m_bu_id = $req->m_bu_id;
            $tahun = $req->periode_tahun;

            if (!$m_bu_id || !$tahun) {
                throw new \Exception("Lengkapi filter data (BU dan Tahun)");
            }

            $helper = new \App\Cores\Helper();

            $closing = DB::table("t_buku_order_closing")
                ->where("m_bu_id", $m_bu_id)
                ->where("periode_tahun", $tahun)
                ->where("is_closed", 1)
                ->whereNull("deleted_at")
                ->first();

            if ($closing) {
                return $helper->customResponse(
                    "OK",
                    200,
                    [
                        "is_closed" => true,
                        "alasan_closing" => $closing->alasan_closing ?? null,
                        "outstanding" => [], // kosongkan
                    ],
                    true
                );
            }

            $result = $this->getOutstandingClosing($m_bu_id, $tahun);

            return $helper->customResponse(
                "OK",
                200,
                [
                    "outstanding" => $result["outstanding"],
                ],
                true
            );
        } catch (\Exception $e) {
            return (new \App\Cores\Helper())->responseCatch($e);
        }
    }

    public function custom_closing()
    {
        try {
            $tahun = request("periode_tahun");

            if (!$tahun) {
                throw new \Exception("Parameter tidak lengkap (Tahun)");
            }

            // $check = $this->getOutstandingClosing($m_bu_id, $tahun);

            // if (count($check["outstanding"]) > 0) {
            //     throw new \Exception(
            //         "Masih ada transaksi outstanding, tidak bisa closing!"
            //     );
            // }

            DB::table("t_buku_order")
                ->whereYear("tgl", $tahun)
                ->whereNull("deleted_at")
                ->whereIn("status", ["POST", "PRINTED"])
                ->update([
                    "status" => "CLOSED",
                    "is_closed" => 1,
                ]);

            DB::table("t_buku_order_closing")
                ->where("periode_tahun", $tahun)
                ->whereNull("deleted_at")
                ->update([
                    "is_closed" => 1,
                ]);

            DB::commit();

            return (new \App\Cores\Helper())->customResponse(
                "OK",
                200,
                [
                    "message" =>
                        "Closing berhasil, semua transaksi di-set CLOSED",
                ],
                true
            );
        } catch (\Exception $e) {
            return (new \App\Cores\Helper())->responseCatch($e);
        }
    }
}
