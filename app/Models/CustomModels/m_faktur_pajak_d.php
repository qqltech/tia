<?php

namespace App\Models\CustomModels;

class m_faktur_pajak_d extends \App\Models\BasicModels\m_faktur_pajak_d
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function scopeNoFaktur($model){
        return $model->leftJoin('m_faktur_pajak as head', 'head.id', '=', 'm_faktur_pajak_d.m_faktur_pajak_id')
        ->select('head.*', 'm_faktur_pajak_d.*');
    }
    public function scopeNoFakturNew($model){
        $req = app()->request;
        if(!$req->id){
            $model->leftJoin('t_tagihan', 't_tagihan.no_faktur_pajak', '=', 'm_faktur_pajak_d.id')
                ->whereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('t_tagihan')
                        ->whereRaw('t_tagihan.no_faktur_pajak = m_faktur_pajak_d.id')
                        ->where('t_tagihan.status', '=', 'POST');
                })
                ->where(function($query) {
                    $query->where('t_tagihan.status', '=', 'DRAFT')
                        ->orWhereNull('t_tagihan.status');
                })
                ->groupBy('m_faktur_pajak_d.id', 't_tagihan.id')
                ->select('t_tagihan.*', 'm_faktur_pajak_d.*');
        }
    }

    public function scopeNoFakturPI($model){
        $req = app()->request;
        if(!$req->id){
            $model->leftJoin('t_purchase_invoice as pi', 'pi.no_faktur_pajak', '=', 'm_faktur_pajak_d.id')
                ->whereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('t_purchase_invoice as tpi')
                        ->whereRaw('tpi.no_faktur_pajak = m_faktur_pajak_d.id');
                })
                ->groupBy('m_faktur_pajak_d.id', 'pi.id')
                ->select('pi.*', 'm_faktur_pajak_d.*');
        }
    }
}