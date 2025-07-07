<?php

namespace App\Models\CustomModels;
use Carbon\Carbon;



class t_internal extends \App\Models\BasicModels\t_internal
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];


public function createAfter($model, $arrayData, $metaData, $id = null)
{
    try {
        // Cek apakah ada data detail yang dikirim
        if (isset($arrayData['t_internal_d']) && is_array($arrayData['t_internal_d'])) {
            // Ambil semua m_item_d_id yang tidak null
            $detailIds = collect($arrayData['t_internal_d'])
                ->pluck('m_item_d_id')
                ->filter() // hapus yang null
                ->unique()
                ->values()
                ->toArray();

            if (!empty($detailIds)) {
                \DB::table('m_item_d')
                    ->whereIn('id', $detailIds)
                    ->where('used', false)
                    ->update([
                        'used' => true,
                        'updated_at' => Carbon::now()
                    ]);

                \Log::info("Updated m_item_d used status to true for IDs: " . implode(',', $detailIds));
            }
        }

    } catch (\Exception $e) {
        \Log::error("Error updating m_item_d used status: " . $e->getMessage());
        // throw $e; // Uncomment if you want the error to bubble up
    }
}

    
}