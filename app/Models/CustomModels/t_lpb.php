<?php

namespace App\Models\CustomModels;

class t_lpb extends \App\Models\BasicModels\t_lpb
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
            "no_lpb" => $this->helper->generateNomor("Nomor LPB"),
            "tanggal" => date("Y-m-d"),
            "status" => "DRAFT",
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
        $newData = [
            "tanggal" => date("Y-m-d"),
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function custom_post()
    {
        $id = request("id");
        \DB::beginTransaction();
        try{
            
            // INSERT STOCK
            $data = $this->where('id', $id)->first();
            $detail = \DB::table('t_lpb_d')->where('t_lpb_id', $id)->get();

            $r_stock = r_stock::create([
                "date" => date("Y-m-d"),
                "type" => "LPB",
                "ref_table" => $this->getTable(),
                "ref_id" => $data->id,
                "ref_no" => $data->no_lpb,
                "note" => $data->catatan,
            ]);

            foreach($detail as $dt){
                $view_stock = \DB::table('v_stock_item')->where('m_item_id', $dt->m_item_id)->first();
                $r_stock_d = r_stock_d::create([
                    "r_stock_id" => $r_stock->id,
                    "ref_table" => $this->getTable(),
                    "ref_id" => $data->id,
                    "typemin" => 1, // 1 = IN (STOK MASUK), 0 = OUT (STOK KELUAR)
                    "m_item_id" => $dt->m_item_id,
                    "qty_awal" => $view_stock ? $view_stock->qty_stock : 0,
                    "qty_in" => $dt->qty,
                    "price" => $dt->harga,
                    "price_old" => $view_stock ? $view_stock->price : 0,
                    "note" => $dt->catatan
                ]);
            }
            // END INSERTSTOCK
            $status = $this->where("id", $id)->update(["status" => "POST"]);

            \DB::commit();
            return ["success" => true];
            
        } catch( \Exception $e){
            \DB::rollback();
            return response(["error" => true, "success" => false], 422);
        }
    }

    public function scopeGetAmount($model)
{
    // Menggabungkan tabel-tabel yang diperlukan
    $model->join('t_lpb_d', 't_lpb_d.t_lpb_id', '=', 't_lpb.id')
          ->join('m_item', 't_lpb_d.m_item_id', '=', 'm_item.id');

    // Memilih data yang diperlukan, termasuk harga perolehan dan item terkait
    $model->select(
        't_lpb.id as t_lpb_id',       
        't_lpb.no_lpb as no_lpb',        // ID dari t_lpb
        't_lpb.no_sj_supplier as no_sj',    // Nomor Surat Jalan dari t_lpb
        't_lpb_d.m_item_id',                // ID item dari t_lpb_d
        'm_item.nama_item as item_name',    // Nama item dari m_item (disesuaikan dengan nama kolom)
        't_lpb_d.harga as harga_perolehan', // Harga perolehan dari t_lpb_d
        't_lpb_d.qty as qty',               // Jumlah barang yang dipesan
        // DB::raw('t_lpb_d.harga * t_lpb_d.qty as total_perolehan') // Total perolehan (harga * qty)
    );
}

    
}