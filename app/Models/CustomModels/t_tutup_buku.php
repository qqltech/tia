<?php

namespace App\Models\CustomModels;

use Carbon\Carbon;

class t_tutup_buku extends \App\Models\BasicModels\t_tutup_buku
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

    public function custom_get_outstanding($req)
    {
        try {
            $m_bu_id = $req->m_bu_id;
            $tahun = $req->periode_tahun;

            if (!$m_bu_id || !$tahun) {
                throw new \Exception("Lengkapi filter data (BU dan Tahun)");
            }

            $helper = new \App\Cores\Helper();
            $result = $helper->getOutstandingClosing($m_bu_id, $tahun);

            $resultPayload = [
                "outstanding" => $result["outstanding"],
                "saldo_coa" => $this->generateSaldoCOA($m_bu_id, $tahun),
            ];

            return $helper->customResponse("OK", 200, $resultPayload, true);
        } catch (\Exception $e) {
            return (new \App\Cores\Helper())->responseCatch($e);
        }
    }

    private function generateSaldoCOA($m_bu_id, $tahun)
    {
        $helper = new \App\Cores\Helper();
        
        $modules = collect($helper->closingModules())->values();
        $tables = $modules->pluck('table')->filter()->unique()->values()->toArray();

        $currentDate = Carbon::createFromDate($tahun, 1);
        $prevDate = $currentDate->copy()->subMonth();
        $prevTahun = $prevDate->year;

        $prevClose = \DB::table("t_tutup_buku")
            ->where("m_bu_id", $m_bu_id)
            ->where("periode", (string)$prevTahun)
            ->first();

        $prevBalances = [];
        if ($prevClose) {
            $prevBalances = \DB::table("t_tutup_buku_d_coa")
                ->where("t_tutup_buku_id", $prevClose->id)
                ->pluck("akhir", "m_coa_id")
                ->toArray();
        }

        $queryGl = \DB::table("r_gl_d")
            ->join("r_gl", "r_gl.id", "=", "r_gl_d.r_gl_id")
            ->where("r_gl.m_business_unit_id", $m_bu_id)
            ->whereYear("r_gl.date", $tahun);

        if (!empty($tables)) {
            $queryGl->whereIn("r_gl.ref_table", $tables);
        }

        $glMovements = $queryGl
            ->select("r_gl_d.m_coa_id", \DB::raw("SUM(debet) as total_debet"), \DB::raw("SUM(credit) as total_credit"))
            ->groupBy("r_gl_d.m_coa_id")
            ->get()
            ->keyBy("m_coa_id");

        $coas = \DB::table("m_coa")->where("is_active", true)->orderBy("nomor", "asc")->get();

        $saldoCoa = [];
        foreach ($coas as $coa) {
            // Jenis 281 assumed NERACA
            $awal = (strtoupper($coa->jenis) === '281') ? ($prevBalances[$coa->id] ?? 0) : 0;
            $debet = $glMovements[$coa->id]->total_debet ?? 0;
            $credit = $glMovements[$coa->id]->total_credit ?? 0;

            $akhir = (strtoupper($coa->debit_kredit) === "DEBIT") ? ($awal + $debet - $credit) : ($awal - $debet + $credit);

            if ($awal == 0 && $debet == 0 && $credit == 0 && $akhir == 0) continue;

            $saldoCoa[] = [
                "m_coa_id" => $coa->id,
                "nomor" => $coa->nomor,
                "nama_coa" => $coa->nama_coa,
                "awal" => (float)$awal,
                "debet" => (float)$debet,
                "credit" => (float)$credit,
                "akhir" => (float)$akhir,
            ];
        }
        return $saldoCoa;
    }

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $req = app()->request;
        $arrayData["periode"] = $arrayData["periode_tahun"] ?? $req->periode_tahun;
        
        $cek = \DB::table("t_tutup_buku")
            ->where("m_bu_id", $arrayData["m_bu_id"])
            ->where("periode", $arrayData["periode"])
            ->whereNull("deleted_at")
            ->exists();

        if ($cek) {
            throw new \Exception("Closing periode " . $arrayData["periode"] . " sudah ada. Tidak dapat diduplikasi!");
        }

        $arrayData["m_menu_id"] = null;

        return ["model" => $model, "data" => $arrayData];
    }

    public function createAfter($model, $arrayData, $metaData, $id = null)
    {
        $req = app()->request;
        $detailCoa = $req->detail_coa;

        if (is_array($detailCoa) && count($detailCoa) > 0) {
            $insertData = [];
            foreach ($detailCoa as $coa) {
                $insertData[] = [
                    "t_tutup_buku_id" => $model->id,
                    "m_coa_id" => $coa["m_coa_id"],
                    "awal" => $coa["awal"],
                    "debet" => $coa["debet"],
                    "credit" => $coa["credit"],
                    "akhir" => $coa["akhir"],
                    "created_at" => \Carbon\Carbon::now(),
                    "updated_at" => \Carbon\Carbon::now(),
                ];
            }
            \DB::table("t_tutup_buku_d_coa")->insert($insertData);
        }
    }
}