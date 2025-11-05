<?php

namespace App\Models\CustomModels;

use App\Models\BasicModels\t_buku_order;
use App\Models\BasicModels\m_generate_no_aju_d;
use App\Models\CustomModels\t_bkk;
use App\Models\BasicModels\t_bkk_d;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 

class t_ppjk extends \App\Models\BasicModels\t_ppjk
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
        $status = "DRAFT";
        $req = app()->request;
        if ($req->post) {
            $status = "POST";
        }
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft PPJK"),
            "kode_customer" => $this->helper->generateNomor("Customer PPJK"),
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
        $req = app()->request;
        $status = $req->post ? "POST" : $arrayData["status"];

        $newData = [
            "status" => $status,
        ];

        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function rules()
    {
        return [
            "no_ppjk_id" => "required",
        ];
    }

    public function createValidator()
    {
        return $this->rules();
    }

    public function updateValidator()
    {
        return $this->rules();
    }

    // public function custom_post()
    // {
    //     $id = request("id");
    //     $getData = $this->where('id',$id)->first();
    //     $noBukuOrder = t_buku_order::where('id',$getData->t_buku_order_id)->first();

    //     $no_ppjk_update = m_generate_no_aju_d::where('id',$getData->no_ppjk_id)
    //     ->update([
    //         "is_active"=> false,
    //         "referensi" => $noBukuOrder->no_buku_order
    //     ]);

    //     $status = $this->where("id", $id)->update(["status" => "POST"]);

    //     return ["success" => true];
    // }

    // public function custom_post()
    // {
    //     $id = request("id");
    //     $getData = $this->where("id", $id)->first();

    //     if (!$getData) {
    //         return [
    //             "success" => false,
    //             "message" => "Data PPJK tidak ditemukan",
    //         ];
    //     }

    //     $noBukuOrder = t_buku_order::where(
    //         "id",
    //         $getData->t_buku_order_id
    //     )->first();
    //     if (!$noBukuOrder) {
    //         return [
    //             "success" => false,
    //             "message" => "Data Buku Order tidak ditemukan",
    //         ];
    //     }

    //     $noAjuData = m_generate_no_aju_d::where(
    //         "id",
    //         $getData->no_ppjk_id
    //     )->first();
    //     if (!$noAjuData) {
    //         return [
    //             "success" => false,
    //             "message" => "Data nomor aju tidak ditemukan",
    //         ];
    //     }

    //     $noAjuData->update([
    //         "is_active" => false,
    //         "referensi" => $noBukuOrder->no_buku_order,
    //     ]);

    //     $this->where("id", $id)->update(["status" => "POST"]);

    //     // === Generate t_bkk ===
    //     $newBkk = t_bkk::create([
    //         // "no_bkk" => null, // bisa pakai generator nomor kalau ada
    //         // "no_draft" => $getData->no_draft,
    //         "status" => "DRAFT",
    //         "tipe_bkk" => "PPJK",
    //         "nama_penerima" => null,
    //         "no_reference" => $noAjuData->no_aju, // pakai no_aju dari m_generate_no_aju_d
    //         "m_business_unit_id" => 1, // sesuaikan default-nya
    //         "tanggal" => Carbon::now(),
    //         "m_coa_id" => 376, // sesuai instruksi
    //         "total_amt" => $getData->invoice ?? 0,
    //         "tipe_pembayaran" => 298, // sesuaikan jika punya master general
    //         "m_akun_pembayaran_id" => 429,
    //         "m_akun_bank_id" => 1,
    //         "keterangan" => "Auto-generated dari PPJK #" . $id,
    //         "creator_id" => auth()->id() ?? null,
    //     ]);

    //     // === Generate t_bkk_d ===
    //     t_bkk_d::create([
    //         "t_bkk_id" => $newBkk->id,
    //         "m_coa_id" => 376,
    //         "nominal" => $getData->invoice ?? 0,
    //         "keterangan" => "Auto-generate dari PPJK #" . $id,
    //         "t_buku_order_id" => $getData->t_buku_order_id,
    //         "creator_id" => auth()->id() ?? null,
    //     ]);

    //     $bkkModel = new t_bkk();
    //     app()->request->merge(["id" => $newBkk->id]);
    //     $approvalResponse = $bkkModel->custom_send_approval();

    //     return [
    //         "success" => true,
    //         "message" => "PPJK berhasil diposting dan BKK berhasil digenerate",
    //         "t_bkk_id" => $newBkk->id,
    //         "no_aju" => $noAjuData->no_aju,
    //     ];
    // }

    // public function custom_post()
    // {
    //     $id = request("id");
    //     $getData = $this->where("id", $id)->first();

    //     if (!$getData) {
    //         return [
    //             "success" => false,
    //             "message" => "Data PPJK tidak ditemukan",
    //         ];
    //     }

    //     $noBukuOrder = t_buku_order::where(
    //         "id",
    //         $getData->t_buku_order_id
    //     )->first();
    //     if (!$noBukuOrder) {
    //         return [
    //             "success" => false,
    //             "message" => "Data Buku Order tidak ditemukan",
    //         ];
    //     }

    //     $noAjuData = m_generate_no_aju_d::where(
    //         "id",
    //         $getData->no_ppjk_id
    //     )->first();
    //     if (!$noAjuData) {
    //         return [
    //             "success" => false,
    //             "message" => "Data nomor aju tidak ditemukan",
    //         ];
    //     }

    //     $noAjuData->update([
    //         "is_active" => false,
    //         "referensi" => $noBukuOrder->no_buku_order,
    //     ]);

    //     $this->where("id", $id)->update(["status" => "POST"]);

    //     // === Generate nomor langsung tanpa mengandalkan hook ===
    //     // $helper = app()->make(\App\Helpers\Helper::class);
    //     $newData = [
    //         "no_draft" => $this->helper->generateNomor("Draft BKK"),
    //         "no_bkk" => $this->helper->generateNomor("Nomor BKK"),
    //         "status" => "DRAFT",
    //     ];

    //     // === Generate t_bkk ===
    //     $newBkk = t_bkk::create([
    //         "no_draft" => $newData["no_draft"],
    //         "no_bkk" => $newData["no_bkk"],
    //         "status" => $newData["status"],
    //         "tipe_bkk" => "PPJK",
    //         "nama_penerima" => null,
    //         "no_reference" => $noAjuData->no_aju,
    //         "m_business_unit_id" => 1,
    //         "tanggal" => Carbon::now(),
    //         "m_coa_id" => 376,
    //         "total_amt" => $getData->invoice ?? 0,
    //         "tipe_pembayaran" => 298,
    //         "m_akun_pembayaran_id" => 429,
    //         "m_akun_bank_id" => 1,
    //         "keterangan" => "Auto-generated dari PPJK #" . $id,
    //         "creator_id" => auth()->id() ?? null,
    //     ]);

    //     // === Generate t_bkk_d ===
    //     t_bkk_d::create([
    //         "t_bkk_id" => $newBkk->id,
    //         "m_coa_id" => 376,
    //         "nominal" => $getData->invoice ?? 0,
    //         "keterangan" => "Auto-generate dari PPJK #" . $id,
    //         "t_buku_order_id" => $getData->t_buku_order_id,
    //         "creator_id" => auth()->id() ?? null,
    //     ]);

    //     // === Kirim approval ===
    //     if (method_exists(t_bkk::class, "custom_send_approval")) {
    //         $bkkModel = new t_bkk();
    //         app()->request->merge(["id" => $newBkk->id]);
    //         $bkkModel->custom_send_approval();
    //     }

    //     return [
    //         "success" => true,
    //         "message" => "PPJK berhasil diposting dan BKK berhasil digenerate",
    //         "t_bkk_id" => $newBkk->id,
    //         "no_aju" => $noAjuData->no_aju,
    //         "no_bkk" => $newData["no_bkk"],
    //         "no_draft" => $newData["no_draft"],
    //     ];
    // }

    // public function custom_post()
    // {
    //     $id = request("id");
    //     $getData = $this->where("id", $id)->first();

    //     if (!$getData) {
    //         return [
    //             "success" => false,
    //             "message" => "Data PPJK tidak ditemukan",
    //         ];
    //     }

    //     $noBukuOrder = t_buku_order::find($getData->t_buku_order_id);
    //     if (!$noBukuOrder) {
    //         return [
    //             "success" => false,
    //             "message" => "Data Buku Order tidak ditemukan",
    //         ];
    //     }

    //     $noAjuData = m_generate_no_aju_d::find($getData->no_ppjk_id);
    //     if (!$noAjuData) {
    //         return [
    //             "success" => false,
    //             "message" => "Data nomor aju tidak ditemukan",
    //         ];
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // === Update nomor aju dan PPJK ===
    //         $noAjuData->update([
    //             "is_active" => false,
    //             "referensi" => $noBukuOrder->no_buku_order,
    //         ]);

    //         $this->where("id", $id)->update(["status" => "POST"]);

    //         // === Generate nomor manual ===
    //         $newData = [
    //             "no_draft" => $this->helper->generateNomor("Draft BKK"),
    //             "no_bkk" => $this->helper->generateNomor("Nomor BKK"),
    //             "status" => "DRAFT",
    //         ];

    //         // === Generate t_bkk ===
    //         $newBkk = t_bkk::create([
    //             "no_draft" => $newData["no_draft"],
    //             "no_bkk" => $newData["no_bkk"],
    //             "status" => $newData["status"],
    //             "tipe_bkk" => "PPJK",
    //             "nama_penerima" => null,
    //             "no_reference" => $noAjuData->no_aju,
    //             "m_business_unit_id" => 1,
    //             "tanggal" => Carbon::now(),
    //             "m_coa_id" => 376,
    //             "total_amt" => $getData->invoice ?? 0,
    //             "tipe_pembayaran" => 298,
    //             "m_akun_pembayaran_id" => 429,
    //             "m_akun_bank_id" => 1,
    //             "keterangan" => "Auto-generated dari PPJK #" . $id,
    //             "creator_id" => Auth::id() ?? null,
    //         ]);

    //         // === Generate t_bkk_d ===
    //         t_bkk_d::create([
    //             "t_bkk_id" => $newBkk->id,
    //             "m_coa_id" => 376,
    //             "nominal" => $getData->invoice ?? 0,
    //             "keterangan" => "Auto-generate dari PPJK #" . $id,
    //             "t_buku_order_id" => $getData->t_buku_order_id,
    //             "creator_id" => Auth::id() ?? null,
    //         ]);

    //         // === Kirim approval ===
    //         $bkkModel = new t_bkk();
    //         app()->request->replace(["id" => $newBkk->id]);

    //         $approvalId = null;

    //         if (method_exists($bkkModel, "custom_send_approval")) {
    //             $bkkModel->custom_send_approval();

    //             // Cari ID approval dari tabel generate_approval berdasarkan trx_id
    //             $approvalRecord = \DB::table("set.generate_approval") // tambahkan schema
    //                 ->where("trx_id", $newBkk->id)
    //                 ->orderByDesc("id")
    //                 ->first();

    //             $approvalId = $approvalRecord->id ?? null;
    //         }

    //         DB::commit();

    //         $approveReq = new Request([
    //             "id" => $approvalId,
    //             "type" => "APPROVED",
    //             "note" => "Auto approved by system (generated from PPJK)",
    //         ]);

    //         sleep(1);

    //         $result = $bkkModel->custom_progress($approveReq);

    //         return [
    //             "success" => true,
    //             "message" =>
    //                 "PPJK berhasil diposting, BKK berhasil digenerate dan otomatis di-approve",
    //             "t_bkk_id" => $newBkk->id,
    //             "no_aju" => $noAjuData->no_aju,
    //             "no_bkk" => $newData["no_bkk"],
    //             "no_draft" => $newData["no_draft"],
    //         ];
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return [
    //             "success" => false,
    //             "message" => "Terjadi kesalahan: " . $e->getMessage(),
    //         ];
    //     }
    // }

    public function custom_post()
    {
        $id = request("id");
        $getData = $this->where("id", $id)->first();

        if (!$getData) {
            return [
                "success" => false,
                "message" => "Data PPJK tidak ditemukan",
            ];
        }

        $noBukuOrder = t_buku_order::find($getData->t_buku_order_id);
        if (!$noBukuOrder) {
            return [
                "success" => false,
                "message" => "Data Buku Order tidak ditemukan",
            ];
        }

        $noAjuData = m_generate_no_aju_d::find($getData->no_ppjk_id);
        if (!$noAjuData) {
            return [
                "success" => false,
                "message" => "Data nomor aju tidak ditemukan",
            ];
        }

        // === Ambil nomor PPJK sebenarnya (dari m_generate_no_aju_d) ===
        $noPpjk = $noAjuData->no_aju ?? '-';

        // === Ambil nilai admin dari set.m_general.id=1256 (deskripsi) ===
        $adminRaw = null;
        $adminNominal = 0.0;
        $adminGeneral1256 = \DB::table('set.m_general')->where('id', 1256)->first();
        if ($adminGeneral1256) {
            $adminRaw = $adminGeneral1256->deskripsi ?? null;

            if (is_string($adminRaw) && $adminRaw !== '') {
                // Normalisasi angka Indonesia: buang semua karakter non-digit
                $onlyDigits = preg_replace('/\D+/', '', $adminRaw);
                if ($onlyDigits !== '' && is_numeric($onlyDigits)) {
                    $adminNominal = (float) $onlyDigits;
                }
            }
        }

        $tarifPpjk = (float) ($getData->tarif_ppjk ?? 0);
        $totalBkk  = $tarifPpjk + $adminNominal;

        \DB::beginTransaction();
        try {
            // === Update nomor aju dan PPJK ===
            $noAjuData->update([
                "is_active" => false,
                "referensi" => $noBukuOrder->no_buku_order,
            ]);

            $this->where("id", $id)->update(["status" => "POST"]);

            // === Generate nomor manual ===
            $newData = [
                "no_draft" => $this->helper->generateNomor("Draft BKK"),
                "no_bkk"   => $this->helper->generateNomor("Nomor BKK"),
                "status"   => "DRAFT",
            ];

            // === Generate t_bkk (header) ===
            $newBkk = t_bkk::create([
                "no_draft"             => $newData["no_draft"],
                "no_bkk"               => $newData["no_bkk"],
                "status"               => $newData["status"],
                "tipe_bkk"             => "PPJK",
                "nama_penerima"        => null,
                "no_reference"         => $noPpjk,
                "m_business_unit_id"   => 1,
                "tanggal"              => \Carbon\Carbon::now(),
                "m_coa_id"             => 376,
                "total_amt"            => $totalBkk,
                "tipe_pembayaran"      => 298,
                "m_akun_pembayaran_id" => 429,
                "m_akun_bank_id"       => 1,
                "keterangan"           => "Auto-generated dari PPJK #" . $id,
                "creator_id"           => \Auth::id() ?? null,
            ]);

            // === Generate t_bkk_d #1: Admin PPJK (dahulukan Admin) ===
            t_bkk_d::create([
                "t_bkk_id"        => $newBkk->id,
                "m_coa_id"        => 376, // jika ada COA khusus admin, ubah di sini
                "nominal"         => $adminNominal,
                "keterangan"      => "Admin PPJK dari No PPJK : " . $noPpjk,
                "t_buku_order_id" => $getData->t_buku_order_id,
                "admin_ppjk_id"   => $adminRaw,
                "creator_id"      => \Auth::id() ?? null,
            ]);

            // === Generate t_bkk_d #2: Tarif PPJK (setelah Admin) ===
            t_bkk_d::create([
                "t_bkk_id"        => $newBkk->id,
                "m_coa_id"        => 376,
                "nominal"         => $tarifPpjk,
                "keterangan"      => "Tarif PPJK dari No PPJK : " . $noPpjk,
                "t_buku_order_id" => $getData->t_buku_order_id,
                "admin_ppjk_id"   => $adminRaw,
                "creator_id"      => \Auth::id() ?? null,
            ]);

            // === Kirim approval ===
            $bkkModel = new t_bkk();
            app()->request->replace(["id" => $newBkk->id]);

            $approvalId = null;

            if (method_exists($bkkModel, "custom_send_approval")) {
                $bkkModel->custom_send_approval();

                $approvalRecord = \DB::table("set.generate_approval")
                    ->where("trx_id", $newBkk->id)
                    ->orderByDesc("id")
                    ->first();

                $approvalId = $approvalRecord->id ?? null;
            }

            \DB::commit();

            // === Auto approve ===
            $approveReq = new \Illuminate\Http\Request([
                "id"   => $approvalId,
                "type" => "APPROVED",
                "note" => "Auto approved by system (generated from PPJK)",
                "auto" => true
            ]);

            usleep(300000); // 0.3 detik

            $result = $bkkModel->custom_progress($approveReq);

            return [
                "success"    => true,
                "message"    => "PPJK berhasil diposting, BKK berhasil digenerate dan otomatis di-approve",
                "t_bkk_id"   => $newBkk->id,
                "no_aju"     => $noPpjk,
                "no_bkk"     => $newData["no_bkk"],
                "no_draft"   => $newData["no_draft"],
                "total_amt"  => $totalBkk,
                "tarif_ppjk" => $tarifPpjk,
                "admin_raw"  => $adminRaw,
                "admin_amt"  => $adminNominal,
            ];
        } catch (\Exception $e) {
            \DB::rollBack();
            return [
                "success" => false,
                "message" => "Terjadi kesalahan: " . $e->getMessage(),
            ];
        }
    }
}
