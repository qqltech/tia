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

    // custom post t_ppjk -> t_bkk
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
    //             "total_amt" => $getData->tarif_ppjk ?? 0,
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
    //             "nominal" => $getData->tarif_ppjk ?? 0,
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
    //             "auto" => true
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

    //     // === Ambil nomor PPJK sebenarnya (dari m_generate_no_aju_d) ===
    //     $noPpjk = $noAjuData->no_aju ?? '-';

    //     // === Ambil nilai admin dari set.m_general.id=1256 (deskripsi) ===
    //     $adminRaw = null;
    //     $adminNominal = 0.0;
    //     $adminGeneral1256 = \DB::table('set.m_general')->where('id', 1256)->first();
    //     if ($adminGeneral1256) {
    //         $adminRaw = $adminGeneral1256->deskripsi ?? null;

    //         if (is_string($adminRaw) && $adminRaw !== '') {
    //             // Normalisasi angka Indonesia: buang semua karakter non-digit
    //             $onlyDigits = preg_replace('/\D+/', '', $adminRaw);
    //             if ($onlyDigits !== '' && is_numeric($onlyDigits)) {
    //                 $adminNominal = (float) $onlyDigits;
    //             }
    //         }
    //     }

    //     $tarifPpjk = (float) ($getData->tarif_ppjk ?? 0);
    //     $totalBkk  = $tarifPpjk + $adminNominal;

    //     \DB::beginTransaction();
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
    //             "no_bkk"   => $this->helper->generateNomor("Nomor BKK"),
    //             "status"   => "DRAFT",
    //         ];

    //         // === Generate t_bkk (header) ===
    //         $newBkk = t_bkk::create([
    //             "no_draft"             => $newData["no_draft"],
    //             "no_bkk"               => $newData["no_bkk"],
    //             "status"               => $newData["status"],
    //             "tipe_bkk"             => "PPJK",
    //             "nama_penerima"        => null,
    //             "no_reference"         => $noPpjk,
    //             "m_business_unit_id"   => 1,
    //             "tanggal"              => \Carbon\Carbon::now(),
    //             "m_coa_id"             => 376,
    //             "total_amt"            => $totalBkk,
    //             "tipe_pembayaran"      => 298,
    //             "m_akun_pembayaran_id" => 429,
    //             "m_akun_bank_id"       => 1,
    //             "keterangan"           => "Auto-generated dari PPJK #" . $id,
    //             "creator_id"           => \Auth::id() ?? null,
    //         ]);

    //         // === Generate t_bkk_d #1: Admin PPJK (dahulukan Admin) ===
    //         t_bkk_d::create([
    //             "t_bkk_id"        => $newBkk->id,
    //             "m_coa_id"        => 376, // jika ada COA khusus admin, ubah di sini
    //             "nominal"         => $adminNominal,
    //             "keterangan"      => "Admin PPJK dari No PPJK : " . $noPpjk,
    //             "t_buku_order_id" => $getData->t_buku_order_id,
    //             "admin_ppjk_id"   => $adminRaw,
    //             "creator_id"      => \Auth::id() ?? null,
    //         ]);

    //         // === Generate t_bkk_d #2: Tarif PPJK (setelah Admin) ===
    //         t_bkk_d::create([
    //             "t_bkk_id"        => $newBkk->id,
    //             "m_coa_id"        => 376,
    //             "nominal"         => $tarifPpjk,
    //             "keterangan"      => "Tarif PPJK dari No PPJK : " . $noPpjk,
    //             "t_buku_order_id" => $getData->t_buku_order_id,
    //             "admin_ppjk_id"   => $adminRaw,
    //             "creator_id"      => \Auth::id() ?? null,
    //         ]);

    //         // === Kirim approval ===
    //         $bkkModel = new t_bkk();
    //         app()->request->replace(["id" => $newBkk->id]);

    //         $approvalId = null;

    //         if (method_exists($bkkModel, "custom_send_approval")) {
    //             $bkkModel->custom_send_approval();

    //             $approvalRecord = \DB::table("set.generate_approval")
    //                 ->where("trx_id", $newBkk->id)
    //                 ->orderByDesc("id")
    //                 ->first();

    //             $approvalId = $approvalRecord->id ?? null;
    //         }

    //         \DB::commit();

    //         // === Auto approve ===
    //         $approveReq = new \Illuminate\Http\Request([
    //             "id"   => $approvalId,
    //             "type" => "APPROVED",
    //             "note" => "Auto approved by system (generated from PPJK)",
    //             "auto" => true
    //         ]);

    //         usleep(300000); // 0.3 detik

    //         $result = $bkkModel->custom_progress($approveReq);

    //         return [
    //             "success"    => true,
    //             "message"    => "PPJK berhasil diposting, BKK berhasil digenerate dan otomatis di-approve",
    //             "t_bkk_id"   => $newBkk->id,
    //             "no_aju"     => $noPpjk,
    //             "no_bkk"     => $newData["no_bkk"],
    //             "no_draft"   => $newData["no_draft"],
    //             "total_amt"  => $totalBkk,
    //             "tarif_ppjk" => $tarifPpjk,
    //             "admin_raw"  => $adminRaw,
    //             "admin_amt"  => $adminNominal,
    //         ];
    //     } catch (\Exception $e) {
    //         \DB::rollBack();
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

    $noAjuData = null;
    if (!empty($getData->no_ppjk_id)) {
        $noAjuData = m_generate_no_aju_d::find($getData->no_ppjk_id);
    }
    if (!$noAjuData) {
        return [
            "success" => false,
            "message" => "Data nomor aju tidak ditemukan",
        ];
    }

    // Ambil nomor PPJK
    $noPpjk = $noAjuData->no_aju ?? '-';

    // Ambil setting admin (id atau deskripsi tergantung DB)
    $adminNominal = 0.0;
    $adminRaw = null;
    $adminRecord = \DB::table('set.m_general')->where('id', 1256)->first();
    if ($adminRecord) {
        $adminRaw = $adminRecord->deskripsi ?? null;

        if (is_string($adminRaw) && $adminRaw !== '') {
            // Parsing nilai money yang mendukung format Indonesia dan internasional
            // Langkah: remove non-digit except comma and dot, detect comma-as-decimal if needed
            $s = trim($adminRaw);
            // remove currency symbol and spaces
            $s = preg_replace('/[^\d\.,-]/u', '', $s);

            // Jika ada kedua '.' dan ',' -> asumsikan '.' ribuan dan ',' desimal (ID format)
            if (strpos($s, '.') !== false && strpos($s, ',') !== false) {
                $s = str_replace('.', '', $s); // hapus ribuan
                $s = str_replace(',', '.', $s); // ubah desimal ke dot
            } elseif (substr_count($s, ',') > 0 && substr_count($s, '.') === 0) {
                // hanya ada koma => anggap koma sebagai desimal
                $s = str_replace(',', '.', $s);
            } else {
                // hanya ada titik atau hanya digits => titik sebagai decimal (atau tidak ada decimal)
                // nothing to change
            }

            // sekarang bersih untuk float parse
            if ($s !== '' && is_numeric($s)) {
                // gunakan string untuk presisi, cast ke float hanya untuk operasi sederhana
                $adminNominal = (float) $s;
            } else {
                // jika tidak numeric, biarkan 0 dan catat adminRaw
                $adminNominal = 0.0;
            }

            $adminNominal = -abs($adminNominal);
        }
    }

    // Tarif ppjk
    $tarifPpjk = (float) ($getData->tarif_ppjk ?? 0);

    // hitung total sebagai decimal string jika perlu
    // $totalBkk = $tarifPpjk + $adminNominal;
    $totalBkk = $tarifPpjk;

    // cek dependency helper
    if (!isset($this->helper) || !method_exists($this->helper, 'generateNomor')) {
        return ["success" => false, "message" => "Helper generateNomor tidak tersedia."];
    }

    try {
        \DB::beginTransaction();

        // update no aju
        $noAjuData->update([
            "is_active" => false,
            "referensi" => $noBukuOrder->no_buku_order,
        ]);

        $this->where("id", $id)->update(["status" => "POST"]);

        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft BKK"),
            "no_bkk"   => $this->helper->generateNomor("Nomor BKK"),
            "status"   => "DRAFT",
        ];

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

        // tambahkan detail hanya jika nominal > 0
        if ($adminNominal !== 0.0) {
            t_bkk_d::create([
                "t_bkk_id"        => $newBkk->id,
                "m_coa_id"        => 376,
                "nominal"         => $adminNominal,
                "keterangan"      => "Admin PPJK dari No PPJK : " . $noPpjk,
                "t_buku_order_id" => $getData->t_buku_order_id,
                // jika adminRecord memiliki id yang relevan, simpan id; jika tidak, simpan deskripsi di kolom lain
                "admin_ppjk_id"   => $adminRecord->id ?? null,
                "creator_id"      => \Auth::id() ?? null,
            ]);
        }

        if ($tarifPpjk > 0) {
            t_bkk_d::create([
                "t_bkk_id"        => $newBkk->id,
                "m_coa_id"        => 376,
                "nominal"         => $tarifPpjk,
                "keterangan"      => "Tarif PPJK dari No PPJK : " . $noPpjk,
                "t_buku_order_id" => $getData->t_buku_order_id,
                "admin_ppjk_id"   => $adminRecord->id ?? null,
                "creator_id"      => \Auth::id() ?? null,
            ]);
        }

        // kirim approval — pastikan method ada dan bekerja pada record tertentu
        // Hindari menggunakan global request replace jika memungkinkan.
        // Jika custom_send_approval mengandalkan request()->id, kita tetap gunakan, tapi cek metode ada:
        $bkkModel = new t_bkk();
        if (method_exists($bkkModel, "custom_send_approval")) {
            // beberapa implementasi membutuhkan request()->id; isi sementara
            app()->request->replace(["id" => $newBkk->id]);
            $bkkModel->custom_send_approval();
        }

        \DB::commit();

        // Ambil approval record yang baru dibuat (bisa tunda sedikit / polling kecil)
        $approvalRecord = \DB::table("set.generate_approval")
            ->where("trx_id", $newBkk->id)
            ->orderByDesc("id")
            ->first();

        $approvalId = $approvalRecord->id ?? null;

        if ($approvalId) {
            // buat request untuk progress approval
            $approveReq = new \Illuminate\Http\Request([
                "id"   => $approvalId,
                "type" => "APPROVED",
                "note" => "Auto approved by system (generated from PPJK)",
                "auto" => true
            ]);

            // panggil custom_progress pada model record yang sesuai
            // pastikan method custom_progress menerima Request seperti ini
            $bkkModel = t_bkk::find($newBkk->id);
            if ($bkkModel && method_exists($bkkModel, 'custom_progress')) {
                $result = $bkkModel->custom_progress($approveReq);
            } else {
                // fallback: coba panggil pada instance baru
                $tmp = new t_bkk();
                if (method_exists($tmp, 'custom_progress')) {
                    $result = $tmp->custom_progress($approveReq);
                }
            }
        }

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
