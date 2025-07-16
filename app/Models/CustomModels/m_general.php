<?php

namespace App\Models\CustomModels;

class m_general extends \App\Models\BasicModels\m_general
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

    public function getByGroup($nama_group)
    {
        return $this->where("group", $nama_group)->get();
    }
    public function scopeActive($model)
    {
        return $model->where("is_active", 1);
    }

    //2 function dibawah sama aja (untuk nge-group kontainer)
    public function scopeGroup()
    {
        // return $model->whereIn('group',['UKURAN KONTAINER','JENIS KONTAINER','TIPE KONTAINER'])->select(\DB::raw('DISTINCT (group)'))->groupBy('group');
        return $this->select(\DB::raw("distinct(m_general.group)"))
            ->where("$this->table.kode", "KONTAINER")
            ->groupBy("$this->table.group")
            ->orderBy("$this->table.group");
    }
    public function custom_testapi($req)
    {
        return $this->select(\DB::raw("distinct(m_general.group) as group_gen"))
            ->where("$this->table.kode", "KONTAINER")
            ->groupBy("$this->table.group")
            ->orderBy("$this->table.group")
            ->get();
    }

    //ngambil spesifik data dari dalam groupBy
    public function scopeEditKontainer()
    {
        $reqGroup = request("group");
        return $this->where("group", $reqGroup);
    }
    // public function custom_saveKontainer()
    // {
    //     $reqGroup = app()->request;
    //     $details = $reqGroup["detail"];

    //     $formatDataUpsert = [];

    //     foreach ($details as $detail) {
    //         $record = [
    //             "kode" => $detail["kode"],
    //             "group" => $detail["group"],
    //             "deskripsi" => $detail["deskripsi"],
    //             "status" => $detail["status"],
    //         ];

    //         if (isset($detail["id"])) {
    //             $record["id"] = $detail["id"];
    //         }
    //         $formatDataUpsert[] = $record;
    //     }
    //     $this->upsert(
    //         $formatDataUpsert,
    //         ["id"],
    //         ["kode","group","deskripsi", "status"]
    //     );
    //     return response()->json(["message" => "Data saved successfully"]);
    // }

    public function custom_saveKontainer()
    {
        $reqGroup = app()->request;

        // Extract details from request
        $details = $reqGroup["detail"];

        // Prepare the data for update and insert separately
        $dataToUpdate = [];
        $dataToInsert = [];

        foreach ($details as $detail) {
            $record = [
                "kode" => $detail["kode"],
                "group" => $detail["group"],
                "deskripsi" => $detail["deskripsi"],
                "is_active" => $detail["is_active"],
            ];

            if (isset($detail["id"])) {
                $record["id"] = $detail["id"];
                $dataToUpdate[] = $record;
            } else {
                $dataToInsert[] = $record;
            }
        }

        // Perform update for records with id
        foreach ($dataToUpdate as $record) {
            $existingRecord = $this->where("id", $record["id"])->first();
            if ($existingRecord) {
                $existingRecord->update($record);
            } else {
                $this->insert($record);
            }
        }

        // Perform insert for records without id
        $this->insert($dataToInsert);

        return response()->json(["message" => "Data saved successfully"]);
    }
}
