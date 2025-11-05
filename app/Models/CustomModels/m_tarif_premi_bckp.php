<?php

namespace App\Models\CustomModels;
use Illuminate\Support\Facades\DB;

class m_tarif_premi_bckp extends \App\Models\BasicModels\m_tarif_premi_bckp
{    
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $newData = [
            "no_tarif_premi" => $this->helper->generateNomor("Tarif Premi BCKP"),
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    // public function scopeGetTarifPremi($query, $spkId = null)
    // {
    //     $tbl   = $this->getTable(); // m_tarif_premi_bckp
    //     $spkId = $spkId ?? request('spk_id');

    //     if (empty($spkId)) {
    //         return $query->whereRaw('1=0');
    //     }

    //     // ===== ambil data SPK dulu (untuk filtering yang lebih akurat)
    //     $spk = DB::table('t_spk_angkutan as s')
    //         ->leftJoin('t_buku_order_d_npwp as npwp', 'npwp.id', '=', 's.t_detail_npwp_container_1_id')
    //         ->leftJoin('m_grup_head_d as ghd', 'ghd.no_head_id', '=', 's.head')
    //         ->select([
    //             's.id',
    //             's.trip_id',
    //             's.sektor1',
    //             's.head',
    //             DB::raw('COALESCE(npwp.ukuran, 0) as ukuran'),
    //             DB::raw('COALESCE(ghd.m_grup_head_id, 0) as grup_head_id')
    //         ])
    //         ->where('s.id', $spkId)
    //         ->first();

    //     if (!$spk) {
    //         return $query->whereRaw('1=0');
    //     }

    //     // ===== filter langsung berdasarkan field-field SPK
    //     return $query
    //         ->select([
    //             "{$tbl}.id",
    //             "{$tbl}.no_tarif_premi",
    //             "{$tbl}.sektor_id",
    //             "{$tbl}.ukuran_container",
    //             "{$tbl}.grup_head_id",
    //             "{$tbl}.trip",
    //             "{$tbl}.tagihan",
    //             "{$tbl}.premi",
    //             "{$tbl}.sangu",
    //             DB::raw("CASE WHEN {$tbl}.sektor_id = {$spk->sektor1} THEN 1 ELSE 0 END AS match_rank"),
    //         ])
    //         ->where("{$tbl}.ukuran_container", $spk->ukuran)
    //         ->where("{$tbl}.trip", $spk->trip_id)
    //         ->where("{$tbl}.grup_head_id", $spk->grup_head_id)
    //         ->where("{$tbl}.is_active", true)
    //         ->whereNull("{$tbl}.deleted_at")
    //         ->orderByDesc('match_rank')
    //         ->limit(1);
    // }

    public function custom_get_tarif_premi($req)
    {
        if (!$req->spk_id) {
            return ["error" => "error spk id tidak ditemukan"];
        }

        // Ambil SPK + grup_head (mengikuti scope WithGrupHead) + NPWP container 1
        $data_spk = t_spk_angkutan::where('t_spk_angkutan.id', $req->spk_id)
            ->leftJoin('set.m_general as mg', 'mg.id', '=', 't_spk_angkutan.head')
            ->leftJoin('m_grup_head_d as mghd', 'mghd.no_head_id', '=', 'mg.id')
            ->leftJoin('m_grup_head as mgh', 'mgh.id', '=', 'mghd.m_grup_head_id')
            ->leftJoin('t_buku_order_d_npwp', 't_buku_order_d_npwp.id', '=', 't_spk_angkutan.t_detail_npwp_container_1_id')
            ->select(
                't_spk_angkutan.*',
                'mgh.id as grup_head_id',
                'mgh.no_head as grup_head_no',
                'mgh.nama_grup as grup_head_nama',
                't_buku_order_d_npwp.jenis as tipe_kontainer',
                't_buku_order_d_npwp.ukuran as ukuran_container'
            )
            ->first();

        if (!$data_spk) {
            return ["error" => "Data SPK tidak ditemukan"];
        }

        // Ambil grup_head_id dari hasil join (jangan pakai relasi jika tidak didefinisikan)
        $grup_head_id = $data_spk->grup_head_id ?? null;
        if (is_null($grup_head_id)) {
            return [
                "error" => "grup_head_id tidak ditemukan pada data SPK",
                "debug" => [
                    "spk_id" => $data_spk->id,
                    "head" => $data_spk->head ?? null,
                    "available_columns" => array_keys((array)$data_spk),
                ],
            ];
        }

        // Casting ke integer untuk menghindari mismatch tipe
        $sektor_id = isset($data_spk->sektor1) ? (int)$data_spk->sektor1 : null;
        // $tipe_kontainer = isset($data_spk->tipe_kontainer) ? (int)$data_spk->tipe_kontainer : null;
        $ukuran_container = isset($data_spk->ukuran_container) ? (int)$data_spk->ukuran_container : null;
        $trip = isset($data_spk->trip_id) ? (int)$data_spk->trip_id : (isset($data_spk->trip) ? (int)$data_spk->trip : null);

        // Validasi nilai penting ada
        if (is_null($sektor_id) || is_null($ukuran_container) || is_null($trip)) {
            return [
                "error" => "Nilai sektor/tipe/ukuran/trip tidak lengkap untuk pencarian tarif premi",
                "debug" => compact('sektor_id', 'tipe_kontainer', 'ukuran_container', 'trip', 'grup_head_id'),
            ];
        }

        // Query yang KETAT: wajib sertakan grup_head_id dan tipe_kontainer
        $query = m_tarif_premi_bckp::where('sektor_id', $sektor_id)
            // ->where('tipe_kontainer', $tipe_kontainer)
            ->where('grup_head_id', (int)$grup_head_id)
            ->where('ukuran_container', $ukuran_container)
            ->where('trip', $trip)
            ->where('is_active', true);

        $tarif_premi = $query->select('tagihan', 'premi', 'grup_head_id')->first();

        if (!$tarif_premi) {
            return [
                "error" => "Tarif premi tidak ditemukan untuk data yang diberikan (filter termasuk grup_head_id).",
                "debug" => [
                    "filters" => compact('sektor_id', 'ukuran_container', 'trip', 'grup_head_id'),
                    // Opsional: untuk debugging SQL, bisa tambahkan toSql() & bindings (hati-hati di production)
                    // "sql" => $query->toSql(),
                    // "bindings" => $query->getBindings(),
                ],
            ];
        }

        return [
            "premi" => $tarif_premi->premi,
            "tagihan" => $tarif_premi->tagihan,
            "grup_head_id" => $tarif_premi->grup_head_id,
        ];
    }

}