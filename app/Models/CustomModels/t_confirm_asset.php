<?php

namespace App\Models\CustomModels;

class t_confirm_asset extends \App\Models\BasicModels\t_confirm_asset
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    

      public function custom_generateDepreciation($assetData) {
        $entries = [];

        $masaManfaat = $assetData['masa_manfaat'];
        $tglAwalSusut = \Carbon\Carbon::createFromFormat('d/m/Y', $assetData['tgl_awal']);
        $nilaiPenyusutan = round($assetData['nilai_penyusutan'], 2);
        $hargaPerolehan = round($assetData['harga_perolehan'], 2);
        $nilaiMinimal = round($assetData['nilai_min'], 2);
        // return $nilaiMinimal;
        $akumPenyusutan = isset($assetData['akum_penyusutan']) ? round($assetData['akum_penyusutan'], 2) : 0;
        $nilaiBuku = round($hargaPerolehan - $akumPenyusutan, 2);

        for ($i = 0; $i < $masaManfaat; $i++) {
            $tanggalPenyusutan = $tglAwalSusut->copy()->addMonths($i)->format('Y-m-d');

            $nilaiAkunSebelum = round($akumPenyusutan, 2);
            $nilaiBukuSebelum = round($nilaiBuku, 2);

            if ($nilaiBukuSebelum <= $nilaiMinimal) {
                $nilaiPenyusutan = 0;
                $nilaiBuku = $nilaiMinimal;
            } else {
                if ($i == $masaManfaat - 1 || ($nilaiBukuSebelum - $nilaiPenyusutan) < $nilaiMinimal) {
                    $nilaiPenyusutan = $nilaiBukuSebelum - $nilaiMinimal;
                }

                $akumPenyusutan = round($akumPenyusutan + $nilaiPenyusutan, 2);
                $nilaiBuku = round($hargaPerolehan - $akumPenyusutan, 2);

                if ($nilaiBuku < $nilaiMinimal) {
                    $nilaiBuku = $nilaiMinimal;
                }
            }

            $entries[] = [
                'no' => $i + 1,
                'tanggal_penyusutan' => $tanggalPenyusutan,
                'nilai_akun_sebelum' => $nilaiAkunSebelum,
                'nilai_buku_sebelum' => $nilaiBukuSebelum,
                'nilai_penyusutan' => $nilaiPenyusutan,
                'nilai_akun_setelah' => $akumPenyusutan,
                'nilai_buku_setelah' => $nilaiBuku,
                'status' => 'NEW',
            ];
        }

        return $entries;
    }
}