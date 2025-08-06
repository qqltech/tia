<?php

namespace App\Models\CustomModels;

class t_bkm extends \App\Models\BasicModels\t_bkm
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
            "no_draft" => $this->helper->generateNomor("DRAFT BKM"),
            "no_bkm" => $this->helper->generateNomor("Nomor BKM"),
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
    
     public function custom_print()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "PRINTED"]);
        return ["success" => true];
    }

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        $this->autoJurnal($id);
        return ["success" => true, "message"=> "Post Data Berhasil"];
    }

    private function autoJurnal($id){
        $trx = \DB::selectOne('select a.* from t_bkm a where a.id = ?', [ $id ]);
        if(!$trx)  return ['status'=>true];

        $getcredit = \DB::select("select cbmd.m_coa_id, cbmd.nominal as amount, cbmd.keterangan from t_bkm cbm
        join t_bkm_d cbmd on cbmd.t_bkm_id = cbm.id
        where cbm.id = ?", [$id]);

        $seq = 1;
        $creditArr = [];
        $amount = 0;

        foreach($getcredit as $cr){
            $creditArr[] = (object) [
                "m_coa_id" => $cr->m_coa_id,
                "seq" => $seq+1,
                "credit" => (float) $cr->amount,
                "desc" => $cr->keterangan
            ];
            $amount += (float) $cr->amount;
            $seq++;
        }


        $debetArr = [];

        $debet = new \stdClass();
        $debet->m_coa_id = $trx->m_akun_pembayaran_id;
        $debet->seq = 1;
        $debet->debet = ((float) @$amount ?? 0);
        $debet->desc = $trx->keterangan;
        $debetArr[] = $debet;

        $obj = [
            'date'              => $trx->tanggal,
            'form'              => "BKM",
            'ref_table'         => 't_bkm',
            'ref_id'            => $trx->id,
            'ref_no'            => $trx->no_bkm,
            // 'm_cust_id'         => $trx->m_cust_id,
            'desc'              => $trx->keterangan,
            'm_business_unit_id' => $trx->m_business_unit_id,
            'no_reference'      => $trx->no_reference,
            'detail'            => array_merge($debetArr, $creditArr)
        ];

        $r_gl = new r_gl;
        $data = $r_gl->autoJournal($obj);

        return ['status'=>true];
    }
}