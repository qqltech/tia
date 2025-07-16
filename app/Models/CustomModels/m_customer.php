<?php

namespace App\Models\CustomModels;

class m_customer extends \App\Models\BasicModels\m_customer
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
        $kode = trim($arrayData["kode"]);
        $result = $model->where("kode", $kode)->first();

        if ($result) {
            return ["errors" => ["Kode Customer sudah dipakai!"]];
        }

        $newArrayData = array_merge($arrayData, []);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function updateBefore($model, $arrayData, $metaData, $id = null)
    {

        $kode = trim($arrayData["kode"]);
        $kodeBeforeUpdate = trim($model->kode);
        $result = $model->where("kode", $kode)->first();
        // trigger_error(json_encode($kode));
        if($kode != $kodeBeforeUpdate){
            if ($result) {
                return ["errors" => ["Kode Customer sudah dipakai!"]];
            }
        }
        // if ($result) {
        //     return ["errors" => ["Kode Customer sudah dipakai!"]];
        // }

        $newArrayData = array_merge($arrayData, []);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    // public function custom_isCodeUsed(){
    //     $req = request('kode_customer');

    //     $result = m_customer::where('kode',$req);

    //     trigger_error($result);

    // }

    public function scopeWithTarifPPJK($model)
    {
        $req = app()->request;
        if ($req->tipe_tarif) {
            $model
                ->select(
                    "m_customer.*",
                    \DB::raw(
                        "COALESCE(latest_tarif.tarif_ppjk, 0) as tarif_ppjk"
                    ),
                    \DB::raw(
                        "COUNT(latest_tarif.m_customer_id) > 0 as is_in_tarif"
                    )
                )
                ->leftJoin("m_tarif as latest_tarif", function ($join) {
                    $join
                        ->on("latest_tarif.m_customer_id", "=", "m_customer.id")
                        ->whereIn("latest_tarif.id", function ($query) {
                            $req = app()->request;
                            $query
                                ->select(\DB::raw("max(id)"))
                                ->from("m_tarif")
                                ->groupBy("m_customer_id")
                                ->where("tipe_tarif", $req->tipe_tarif);
                        });
                })
                ->groupBy(
                    "m_customer.id",
                    "latest_tarif.tarif_ppjk",
                    "m_customer_group.id",
                    "jabatan1.id",
                    "jabatan2.id",
                    "coa_piutang.id"
                );
        }
    }

    // public function scopeIsTarif($model){
    //  $model->select('m_customer.*',)
    //     ->leftJoin('m_tarif', 'm_tarif.m_customer_id', '=', 'm_customer.id')
    //     ->groupBy('m_customer.id');
    // }
    public function scopeCustomerActive($model)
    {
        return $model->where("m_customer.is_active", "true");
    }

    // public function transformRowData( array $row )
    // {
    //     $newData=[
    //         "jenis_nama_perusahaan"=> $row['jenis_perusahaan'].' '.$row['nama_perusahaan']
    //     ];
    //     return array_merge( $row, $newData );
    // }

    public function scopeGetPerusahaan($model)
    {
        return $model->select(
            \DB::raw(
                "CONCAT(jenis_perusahaan,' ',nama_perusahaan) as jenis_nama_perusahaan",
            ),
            'm_customer.*'
        );
    }

    public function scopeGetCustomerNPWP($model){
        return $model->with(['m_customer_d_npwp']);
    }

    public function scopeCustomerPpjk($model)
    {
        $req = request('buku_order_id', 0);

        return $model
        ->leftjoin('t_ppjk','m_customer.id','t_ppjk.m_customer_id')
        ->select('m_customer.id', 'm_customer.kode', 'm_customer.nama_perusahaan', 'm_customer.alamat', 'm_customer.kota')
        ->where('t_ppjk.t_buku_order_id', $req)
        ->distinct()
        ->orderBy('m_customer.nama_perusahaan');
    }
}
