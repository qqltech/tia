<?php

namespace App\Models\CustomModels;

class m_coa extends \App\Models\BasicModels\m_coa
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
        if (isset($arrayData['nomor']) && !empty($arrayData['nomor'])) {
        $existingRecord = $model::where('nomor', $arrayData['nomor'])->first();
            if ($existingRecord) {
                return [
                    "errors" => [
                        'nomor' => 'The nomor "' . $arrayData['nomor'] . '" already exists in the database'
                    ]
                ];
            }
        }

        $newData = [
            "kode_perkiraan" => $this->helper->generateNomor("Kode Coa"),
        ];

        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    // public function scopeWithTagihan($model)
    // {
    //     $cn_d_id = request("cn_d_id");

    //     // Retrieve the related 't_tagihan' record
    //     $id_tagihan = t_credit_note_d::where("id", $cn_d_id)
    //         ->select("t_tagihan_id")
    //         ->first();

    //     $getTagihan = t_tagihan::where("id", $id_tagihan->t_tagihan_id)
    //         ->select("no_tagihan","id as tagihan_id")
    //         ->first();

    //     // Add the tagihan data as custom attributes
    //     return $model->addSelect([
    //         "id_tagihan" => \DB::raw($id_tagihan->t_tagihan_id), // Correct integer handling
    //         "nomor_tagihan" => \DB::raw("'{$getTagihan->no_tagihan}'"), // Correct string handling
    //     ]);
    // }
}
