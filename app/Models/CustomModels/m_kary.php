<?php

namespace App\Models\CustomModels;

class m_kary extends \App\Models\BasicModels\m_kary
{
    public function __construct()
    {
        parent::__construct();
    }

    public $fileColumns = [
        "foto_kary",
        "foto_ktp",
        "foto_kk",
        "foto_bpjs_ks",
        "foto_bpjs_ktj",
    ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function transformRowData(array $row)
    {
        if (app()->request->view_buku_order_on_spk_angkutan) {
            $tbo = \DB::table("t_spk_angkutan")
                ->where("supir", $row["id"])
                ->join("t_buku_order as tbo", "tbo.id", "=", "t_spk_angkutan.t_buku_order_1_id")
                ->select("tbo.id", \DB::raw("SUM(total_sangu) as nominal"), "tbo.*")
                ->groupBy("tbo.id")
                ->get();
            return array_merge($row, ["buku_order" => $tbo]);
        }
        return $row;
    }
}
