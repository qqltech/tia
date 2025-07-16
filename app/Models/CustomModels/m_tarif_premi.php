<?php

namespace App\Models\CustomModels;

class m_tarif_premi extends \App\Models\BasicModels\m_tarif_premi
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

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $newData = [
            "no_tarif_premi" => $this->helper->generateNomor("Tarif Premi"),
            
        ];
      $newArrayData  = array_merge( $arrayData,$newData );
      return [
          "model"  => $model,
          "data"   => $newArrayData,
          // "errors" => ['error1']
      ];
    }

        public function custom_get_tarif_premi($req){
            if(!$req->spk_id) return [
                'error'=> 'error spk id tidak ditemukan'
            ];
            $data_spk = t_spk_angkutan::where('t_spk_angkutan.id', $req->spk_id)
            ->join('t_buku_order_d_npwp', 't_buku_order_d_npwp.id', '=', 't_spk_angkutan.t_detail_npwp_container_1_id')
            ->select(
                't_spk_angkutan.sektor1 as sektor_id', 
                't_buku_order_d_npwp.jenis as tipe_kontainer',
                't_spk_angkutan.head as no_head', 
                't_buku_order_d_npwp.ukuran as ukuran_container',
                't_spk_angkutan.trip_id as trip', 
            )
            ->first();

        if (!$data_spk) {
                return [
                    'error' => 'Data SPK tidak ditemukan'
                ];
            }

            $tarif_premi = m_tarif_premi::where('sektor_id', $data_spk->sektor_id)
            ->where('tipe_kontainer', $data_spk->tipe_kontainer)
            ->where('no_head', $data_spk->no_head)
            ->where('ukuran_container', $data_spk->ukuran_container)
            ->where('trip', $data_spk->trip)
            ->where('is_active', true)
            ->select('tagihan', 'premi')
            ->first();

            if (!$tarif_premi) {
                return [
                    'error' => 'Tarif premi tidak ditemukan untuk data yang diberikan'
                ];
            }

            return $tarif_premi;
        }
    
    
}