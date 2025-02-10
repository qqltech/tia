<?php

namespace App\Models\CustomModels;

class coba_master_barang extends \App\Models\BasicModels\coba_master_barang
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];
    public function scopeIsActive($model)
    {
        return $model->where('status', 0);
    }

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $data = $this->where('nama_barang', $arrayData['nama_barang'])->first();
        // trigger_error(json_encode($data));
        if($data) {
            return [
            "errors" => ['Data sudah ada']
        ];
        }

        $newArrayData  = array_merge( $arrayData,[] );
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => []
        ];
    }    

    public function transformRowData( array $row )
    {
        $data = [];
        if(app()->request->show_credit) {
            $data = [
                'credit' => 2000,
            ];
        } 
        return array_merge( $row, $data );
    }
    
    public function custom_save($request)
    {
        // return $request;
        $validator = \Validator::make($request->all(), [
            '*.nama_barang' => 'required|string|max:255',
            '*.img_url' => 'required|url',
            '*.qty' => 'required|integer',
            '*.status' => 'required|integer',
            '*.satuan' => 'required|array',
            '*.satuan.*.nama_satuan' => 'required|string|max:255',
            '*.satuan.*.harga' => 'required|numeric',
            '*.satuan.*.status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // return $request->all()[0];
        try {
            $barangList = [];

            foreach ($request->all() as $barangData) {
                $barang = coba_master_barang::create([
                    'nama_barang' => $barangData['nama_barang'],
                    'img_url' => $barangData['img_url'],
                    'qty' => $barangData['qty'],
                    'status' => $barangData['status'],
                ]);
    
                foreach ($barangData['satuan'] as $satuan) {
                    coba_satuan::create([
                        'nama_satuan' => $satuan['nama_satuan'],
                        'id_barang' => $barang->id,
                        'harga' => $satuan['harga'],
                        'status' => $satuan['status'],
                    ]);
                }
    
                // Menyimpan data master_barang dan satuan
                $barangWithSatuan = $barang->load('coba_satuan');
                $barangList[] = $barangWithSatuan;
            }
    
            return response()->json([
                'message' => 'Data master barang dan satuan berhasil disimpan',
                'data' => $barangList
            ], 201);
        } catch (\Exception $e) {
            // DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function custom_calculateBarang($req)
    {
       $data = $req->input('data');

       if (!$data) {
            $data = \DB::table('coba_master_barang')->get()->toArray();
       }

       if (empty($data)) {
        return [
                'Barang tidak ada'
            ];
        }

        $jumlahBarang = count($data);

        $namaBarang = array_filter(array_column($data, 'nama_barang'));

        $uniqueBarang = count(array_unique($namaBarang));

        return [
            'total barang' => $jumlahBarang,
            'total barang unik' => $uniqueBarang
        ];
    }

    public function custom_getBarangBySatuan($req)
    {
        $namaSatuan = ($req->input('nama_satuan'));

        $barang = \DB::table('coba_master_barang')
            ->join('coba_satuan', 'coba_satuan.id_barang', '=', 'coba_master_barang.id')
            ->where('coba_satuan.nama_satuan', 'ILIKE', $namaSatuan) // Case-insensitive untuk PostgreSQL
            ->select(
                'coba_master_barang.nama_barang',
                'coba_master_barang.img_url',
                'coba_master_barang.qty',
            )
            ->get();

        if ($barang->isEmpty()) {
            return response()->json([
                'message' => 'Barang dengan nama satuan tersebut tidak ditemukan',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Berikut adalah barang yang memiliki nama satuan "' . $namaSatuan . '":',
            'data' => $barang
        ], 200);
        // trigger_error(json_encode($data));
    }  
}