<?php

namespace App\Models\CustomModels;

class t_confirm_asset extends \App\Models\BasicModels\t_confirm_asset
{
    private $helper;
    private $approval;
    protected $approvalConf;

    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
        $this->approval = getCore("Approval");

        $this->approvalConf = [
            "app_name" => "APPROVAL ASSET CONFIRMATION",
            "trx_id" => null,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan Asset Confirmation",
            "form_name" => getTableOnly($this->getTable()),
            "trx_nomor" => null,
            "trx_date" => Date("Y-m-d"),
            "trx_creator_id" => null,
        ];
    }

    public $fileColumns = [
        /*file_column*/
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    private $statusGroup = "STATUS ASSET CONFIRMATION";

    public function seeder()
    {
        m_general::insert([
            [
                "group" => $this->statusGroup,
                "key1" => "DRAFT",
                "value1" => "Draft",
            ],
            [
                "group" => $this->statusGroup,
                "key1" => "PROCESS",
                "value1" => "In Process",
            ],
            [
                "group" => $this->statusGroup,
                "key1" => "POST",
                "value1" => "In Approval",
            ],
            [
                "group" => $this->statusGroup,
                "key1" => "APPROVE",
                "value1" => "Approved",
            ],
            [
                "group" => $this->statusGroup,
                "key1" => "COMPLETE",
                "value1" => "Completed",
            ],
            [
                "group" => $this->statusGroup,
                "key1" => "REJECTED",
                "value1" => "Rejected",
            ],
            [
                "group" => $this->statusGroup,
                "key1" => "REVISE",
                "value1" => "Revise",
            ],
            [
                "group" => $this->statusGroup,
                "key1" => "CANCEL",
                "value1" => "Canceled",
            ],
        ]);
    }

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $this->helper->checkIsPeriodClosed($arrayData['tanggal']);
        $newData = [
            "no_draft" => $this->helper->generateNomor(
                "Draft Asset Confirmation"
            ),
            "no_asset_confirmation" => $this->helper->generateNomor(
                "No Asset Confirmation"
            ),
            "kode_asset" => $this->cek_tipe($arrayData["tipe_asset"]),
            "status_id" => $this->helper->getGeneralId(
                $this->statusGroup,
                "key1",
                "DRAFT"
            ),
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

    public function cek_tipe($data)
    {
        $cek_tipe_asset = "";
        if (strtolower($data) == "tabung") {
            $cek_tipe_asset = $this->helper->generateNomor(
                "Kode Asset Confirmation Tabung"
            );
        } elseif (strtolower($data) == "kendaraan") {
            $cek_tipe_asset = $this->helper->generateNomor(
                "Kode Asset Confirmation Kendaraan"
            );
        } elseif (strtolower($data) == "tanah") {
            $cek_tipe_asset = $this->helper->generateNomor(
                "Kode Asset Confirmation Tanah"
            );
        } elseif (strtolower($data) == "bangunan") {
            $cek_tipe_asset = $this->helper->generateNomor(
                "Kode Asset Confirmation Bangunan"
            );
        } elseif (strtolower($data) == "inventaris") {
            $cek_tipe_asset = $this->helper->generateNomor(
                "Kode Asset Confirmation Inventaris Kantor"
            );
        } elseif (strtolower($data) == "mesin") {
            $cek_tipe_asset = $this->helper->generateNomor(
                "Kode Asset Confirmation Asset Mesin"
            );
        } elseif (strtolower($data) == "chasis") {
            $cek_tipe_asset = $this->helper->generateNomor(
                "Kode Asset Confirmation Chasis"
            );
        } elseif (strtolower($data) == "tangki") {
            $cek_tipe_asset = $this->helper->generateNomor(
                "Kode Asset Confirmation Asset Tangki"
            );
        } elseif (strtolower($data) == "cradle") {
            $cek_tipe_asset = $this->helper->generateNomor(
                "Kode Asset Confirmation Asset Cradle"
            );
        } elseif (strtolower($data) == "lain") {
            $cek_tipe_asset = $this->helper->generateNomor(
                "Kode Asset Confirmation Lain-lain"
            );
        }
        return $cek_tipe_asset;
    }

    public function transformRowData(array $row)
    {
        $data = [];

        if (app()->request->detail_asset) {
            $detailAsset = \DB::table("t_confirm_asset")
                ->join(
                    "t_confirm_asset_d",
                    "t_confirm_asset_d.t_confirm_asset_id",
                    "=",
                    "t_confirm_asset.id"
                )
                ->get();

            $data = [
                "t_confirm_asset_d" => $detailAsset,
            ];
        }

        return array_merge($row, $data);
    }

    public function custom_generateDepreciation($assetData)
    {
        $entries = [];

        $masaManfaat = $assetData["masa_manfaat"];
        $tglAwalSusut = \Carbon\Carbon::createFromFormat(
            "d/m/Y",
            $assetData["tgl_awal"]
        );
        $nilaiPenyusutan = round($assetData["nilai_penyusutan"], 2);
        $hargaPerolehan = round($assetData["harga_perolehan"], 2);
        $nilaiMinimal = round($assetData["nilai_min"], 2);
        // return $nilaiMinimal;
        $akumPenyusutan = isset($assetData["akum_penyusutan"])
            ? round($assetData["akum_penyusutan"], 2)
            : 0;
        $nilaiBuku = round($hargaPerolehan - $akumPenyusutan, 2);

        for ($i = 0; $i < $masaManfaat; $i++) {
            $tanggalPenyusutan = $tglAwalSusut
                ->copy()
                ->addMonths($i)
                ->format("Y-m-d");

            $nilaiAkunSebelum = round($akumPenyusutan, 2);
            $nilaiBukuSebelum = round($nilaiBuku, 2);

            if ($nilaiBukuSebelum <= $nilaiMinimal) {
                $nilaiPenyusutan = 0;
                $nilaiBuku = $nilaiMinimal;
            } else {
                if (
                    $i == $masaManfaat - 1 ||
                    $nilaiBukuSebelum - $nilaiPenyusutan < $nilaiMinimal
                ) {
                    $nilaiPenyusutan = $nilaiBukuSebelum - $nilaiMinimal;
                }

                $akumPenyusutan = round($akumPenyusutan + $nilaiPenyusutan, 2);
                $nilaiBuku = round($hargaPerolehan - $akumPenyusutan, 2);

                if ($nilaiBuku < $nilaiMinimal) {
                    $nilaiBuku = $nilaiMinimal;
                }
            }

            $entries[] = [
                "no" => $i + 1,
                "tanggal_penyusutan" => $tanggalPenyusutan,
                "nilai_akun_sebelum" => $nilaiAkunSebelum,
                "nilai_buku_sebelum" => $nilaiBukuSebelum,
                "nilai_penyusutan" => $nilaiPenyusutan,
                "nilai_akun_setelah" => $akumPenyusutan,
                "nilai_buku_setelah" => $nilaiBuku,
                "status" => "NEW",
            ];
        }

        return $entries;
    }
}
