<?php

namespace App\Models\CustomModels;

class m_supplier extends \App\Models\BasicModels\m_supplier
{    
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }
    
    // public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        
        $newData = [
            "kode" => $this->helper->generateNomor("Supplier"),
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function transformRowData( array $row )
    {
        $data = [];
        if(app()->request->view_angkutan){
            $data = [
                "data" => $this->getAngkutan($row['id'])
            ];
        }

        if(app()->request->view_hutang){
            $jurnalAngkutan = $this->getJurnalAngkutan($row['id'])->toArray();
            $purchaseInvoice = $this->getPurchaseInvoice($row['id'])->toArray();
            $arrayMerge = array_merge($jurnalAngkutan, $purchaseInvoice);
            $data = [
                "data" => $arrayMerge,
            ];
        }
        return array_merge( $row, $data );
    }

    private function getJurnalAngkutan($id){
        $data = t_jurnal_angkutan::where('m_supplier_id',$id)->where('status','POST')->get();
        return $data;
    }

    private function getPurchaseInvoice($id){
        $data = t_purchase_invoice::where('m_supplier_id',$id)->where('status','POST')->get();
        return $data;
    }
    

    private function getAngkutan($id){
        $getAngkutan = t_angkutan_d::where('nama_angkutan_id',$id)->join('t_angkutan as ta','ta.id','t_angkutan_d.t_angkutan_id')
        ->select('t_angkutan_d.*','ta.*','t_angkutan_d.id')
        ->where('ta.status','POST')->get();
        $dpp = 0;
        $ppn = 0;
        $grandTotal = 0;

        $getAngkutan = $getAngkutan->transform(function($item) use(&$grandTotal){
            $explode = explode('-', $item->no_container);
            $getKontainer = $this->getKontainer($item->t_buku_order_id, $explode);
            $item->kontainer = $getKontainer;
            $item->tarif = $this->getTarifAngkutan($getKontainer);
            $grandTotal += $this->getTarifAngkutan($getKontainer)['total_harga'];
            return $item;
        });

        $data = [
            'angkutan' => $getAngkutan,
            'grandTotal' => $grandTotal,
            'ppn' => $ppn = $grandTotal * 11/100,
            'dpp' => $grandTotal - $ppn,
        ];
        return $data;
    }

    private function getKontainer($id, $explode){
        $detail =  t_buku_order_d_npwp::where('t_buku_order_id',$id)
                                ->where('no_prefix',$explode[0])
                                ->where('no_suffix',$explode[1] ?? null)
                                ->join('set.m_general as mg_jenis','mg_jenis.id','t_buku_order_d_npwp.jenis')
                                ->join('set.m_general as mg_sektor','mg_sektor.id','t_buku_order_d_npwp.sektor')
                                ->select('t_buku_order_d_npwp.*', 'mg_sektor.deskripsi as sektor_deskripsi',
                                'mg_jenis.deskripsi as jenis_deskripsi',
                                )
                                ->first();
        //join angel                        
        $getBukuOrder = t_buku_order::where('id',$id)->first()->tipe ?? null;
        $deskripsi = m_general::where('id',$getBukuOrder)->first()->deskripsi ?? null;
        if($detail){
            $detail->tipe = $getBukuOrder;
            $detail->tipe_deskripsi = $deskripsi;
        }
        
        return $detail;
    }

    private function getTarifAngkutan($data){  
        $getTarifAngkutan = m_tarif_angkutan::where('ukuran', $data['ukuran'] ?? null)
                    ->where('jenis', $data['jenis'] ?? null)
                    ->where('sektor', $data['sektor'] ?? null)
                    ->select( 'tarif','tarif_stapel')
                    ->first();

        $tarifData = [];

        if ($getTarifAngkutan) {
            $tarifData['tarif'] = $getTarifAngkutan->tarif;
            $tarifData['tarif_stapel'] = $getTarifAngkutan->tarif_stapel;
        } else {
            $tarifData['tarif'] = 0;
            $tarifData['tarif_stapel'] = 0;
        }

        $tarifData['total_harga'] = $tarifData['tarif'] + $tarifData['tarif_stapel'];

        return $tarifData;
        }

    public function scopeGetRph($model)
    {
        $id = request('rp_hutang_id');
        return $model
        ->join('t_rencana_pembayaran_hutang_d as rph_d','rph_d.m_supplier_id','m_supplier.id')
        ->join('t_rencana_pembayaran_hutang as rph', 'rph.id', '=', 'rph_d.t_rencana_pembayaran_hutang_id')
                    ->select(
                        'rph_d.*',
                        'rph_d.id as rph_id',
                        'm_supplier.nama as nama_supplier',
                        'm_supplier.alamat',
                        'm_supplier.negara',
                        'm_supplier.kode',
                    )
                    ->distinct('m_supplier.id')
                    ->where('rph_d.t_rencana_pembayaran_hutang_id',$id);
    }
}