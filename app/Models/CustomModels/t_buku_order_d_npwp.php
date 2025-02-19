<?php

namespace App\Models\CustomModels;

class t_buku_order_d_npwp extends \App\Models\BasicModels\t_buku_order_d_npwp
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function transformRowData( array $row )
    {
        $data = [];
        $req = app()->request;
        if(app()->request->view_tarif){
            $getBukuOrder = t_buku_order::where('id',$row['t_buku_order_id'])->first();
            $getTarif = $this->tarif_kontainer($getBukuOrder['m_customer_id'], $row['ukuran'],strtolower($getBukuOrder['tipe_order']),$row['jenis'])->toArray();
            $data = [
                "tarif" => $getTarif
            ];
        }
        if(app()->request->no_cont){
            $data = ["no_cont"=>$row['no_prefix'].$row['no_suffix']];
        }
        if(app()->request->getCustomer){
            $result = m_customer::where('id',$row['t_buku_order.m_customer_id'])->first();
            $row['t_buku_order.m_customer_kode'] = $result->kode;
        }

        // if(app()->request->is_transform){
        //     $getBukuOrder = t_buku_order::where('id', $row['t_buku_order_id'])->first();
        //     $data = [
        //         "no_buku_order" => $getBukuOrder['no_buku_order']
        //     ];
        // }
        return array_merge($row, $data);
    }

    

    public function tarif_kontainer($getCustomerId, $ukuran_id, $tipe_tarif, $jenis){
        // $getIdUkuran = m_general::where('deskripsi',$ukuran)->where('group','UKURAN KONTAINER')->first()->id ?? 0;
        // return m_tarif::where('m_customer_id', $getCustomerId)
        // ->where('ukuran_kontainer', $getIdUkuran)
        // ->where('is_active', true)
        // ->select('jenis', 'tarif_sewa', 'sektor', 'tarif_sewa_diskon')
        // ->first();

        $getIdUkuran = \DB::table('m_tarif as mt')
            ->leftJoin('set.m_general as mg', 'mt.ukuran_kontainer', '=', 'mg.id')
            ->leftJoin('set.m_general as mg2', 'mt.jenis', '=', 'mg2.id')
            ->leftJoin('set.m_general as mg3', 'mt.sektor', '=', 'mg3.id')
            ->select(
                'mt.id',
                'mt.tipe_tarif',
                'mt.jenis as jenis_id',
                'mt.tarif_sewa',
                'mt.sektor as sektor_id',
                'mt.tarif_sewa_diskon',
                'mt.ukuran_kontainer as ukuran_kontainer_id',
                'mt.is_active',
                'mg.deskripsi as ini_ukuran_kontainer',
                'mg2.deskripsi as ini_jenis',
                'mg3.deskripsi as ini_sektor',
                'mt.tarif_ppjk',
            )
            ->where('mt.m_customer_id', $getCustomerId)
            ->where('mt.ukuran_kontainer', $ukuran_id)
            ->where('mt.is_active', true)
            ->whereRaw('LOWER(mt.tipe_tarif) = ?', [$tipe_tarif])
            ->where('mt.jenis', $jenis)
            ->get();

            $getIdUkuran = $getIdUkuran->transform(function($item){
            
            $item->jasa = \DB::table('m_tarif_d_jasa')
                ->where('m_tarif_id', $item->id)->join('m_jasa','m_jasa.id','m_tarif_d_jasa.m_jasa_id')
                ->get();

            $item->lain = \DB::table('m_tarif_d_lain_lain')
                ->where('m_tarif_id', $item->id)->get();
            return $item;
            });

        return $getIdUkuran;
        // return m_tarif::where('m_customer_id',$getCustomerId)->where('ukuran_kontainer',$getIdUkuran)->where('is_active',true);
        //     // ->with(['m_tarif_d_kontainer' => function ($query){
        //     //     $query->join('set.m_general as mg_jenis','mg_jenis.id','m_tarif_d_kontainer.jenis')->join('set.m_general as mg_value','mg_value.id','m_tarif_d_kontainer.value')
        //     //     ->select('m_tarif_d_kontainer.*','mg_jenis.deskripsi as nama_jenis','mg_value.deskripsi as nama_value');
        //     // }])->first();
    }

    public function scopeGetNoOrder($model){
        $results = $model
        ->leftJoin('t_buku_order as tbo', "$this->table.t_buku_order_id", '=', 'tbo.id')
        ->leftJoin('set.m_general as mg2', 'mg2.id', '=', "$this->table.jenis")
        ->leftJoin('set.m_general as mg3', 'mg3.id', '=', "$this->table.tipe")
        ->where('tbo.status', 'POST')
        ->whereNotIn("$this->table.id", function($query) {
            $query->select(\DB::raw('distinct tsa.t_detail_npwp_container_1_id'))
                ->from('t_spk_angkutan as tsa');
        })
        ->whereNotIn("$this->table.id", function($query) {
            $query->select(\DB::raw('distinct tsa.t_detail_npwp_container_2_id'))
                ->from('t_spk_angkutan as tsa')
                ->whereNotNull('tsa.t_detail_npwp_container_2_id');
        })
        ->select(
            'tbo.id as buku_order_id',
            'tbo.no_buku_order',
            "$this->table.id",
            "$this->table.no_prefix",
            "$this->table.no_suffix",
            "$this->table.ukuran",
            'mg2.deskripsi as deskripsi_jenis',
            'mg3.deskripsi as deskripsi_tipe'
        )
        ->get();
    }
    

    public function scopegetCodeCustomer($model){
        return $model
        ->leftJoin('t_buku_order as tb','tb.id','t_buku_order_d_npwp.t_buku_order_id')
        ->leftJoin('m_customer as mc','mc.id','tb.m_customer_id')
        ->leftjoin('set.m_general as mg','mg.id','t_buku_order_d_npwp.ukuran')
        ->leftjoin('set.m_general as mg2','mg2.id','t_buku_order_d_npwp.jenis')
        ->select(
            'tb.id',
            'tb.no_buku_order as t_buku_order.no_buku_order',
            't_buku_order_d_npwp.no_prefix',
            't_buku_order_d_npwp.no_suffix',
            'mg.deskripsi as ukuran.deskripsi',
            'mg2.deskripsi as jenis.deskripsi',
            'mc.kode as m_customer.kode',
            'tb.m_customer_id as t_buku_order.m_customer_id'
            )
        ;
    }

    public function scopeGetCustomer($model){
        return $model
        ->leftJoin('m_customer as mc','mc.id','t_buku_order.m_customer_id')
        ->addSelect(
            'mc.nama_perusahaan',
            'mc.kode',
            'mc.id as customer_id',
            \DB::raw("CONCAT(COALESCE(t_buku_order_d_npwp.no_prefix,'-'), COALESCE(t_buku_order_d_npwp.no_suffix,'-')) as no_container")
            )
        ;
    }
}