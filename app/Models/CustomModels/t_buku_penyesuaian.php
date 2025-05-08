<?php

namespace App\Models\CustomModels;

class t_buku_penyesuaian extends \App\Models\BasicModels\t_buku_penyesuaian
{    
    private $helper;
    private $approval;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
        $this->approval = getCore("Approval");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $status = "DRAFT";
        $req = app()->request;
        if($req->post){
            $status = "POST";
        }
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft BP"),
            "no_buku_penyesuaian" => $this->helper->generateNomor("Kode BP"),
            "status" => $status,
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
        $req = app()->request;
        $status = $req->post ? "POST" : $arrayData['status'];
        
        $newData = [
            "tanggal" => date("Y-m-d"),
            "status" => $status
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
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        $this->autoJurnal($id);
        return ["success" => true];
    }

    private function autoJurnal($id)
    {
        // debet = detail, credit = header
        $trx = \DB::selectOne('select * from t_buku_penyesuaian where id = ?', [ $id ]);
        if (!$trx) return ['status' => true];

        // ambil detail (debet)
        $getdebet = \DB::select("
            select m_coa_id, sum(nominal) as amount
            from t_buku_penyesuaian_d
            where t_buku_penyesuaian_id = ?
            group by m_coa_id
        ", [ $id ]);

        $seq = 0;
        $debetArr = [];
        $amount = 0;

        foreach ($getdebet as $dbt) {
            $debetArr[] = (object) [
                "m_coa_id" => $dbt->m_coa_id,
                "seq" => ++$seq,
                "debet" => (float) $dbt->amount,
                "desc" => $trx->keterangan ?? '-'
            ];
            $amount += (float) $dbt->amount;
        }

        // credit dari header
        $creditArr = [];

        $credit = new \stdClass();
        $credit->m_coa_id = $trx->m_akun_pembayaran_id ?? null; // pastikan kolom ini ada
        $credit->seq = ++$seq;
        $credit->credit = ((float) $amount ?? 0);
        $credit->desc = $trx->keterangan ?? '-';
        $creditArr[] = $credit;

        $obj = [
            'date'      => $trx->tanggal_buku_penyesuaian,
            'form'      => "BP",
            'ref_table' => 't_buku_penyesuaian',
            'ref_id'    => $trx->id,
            'ref_no'    => $trx->no_buku_penyesuaian,
            'desc'      => $trx->keterangan,
            'detail'    => array_merge($debetArr, $creditArr)
        ];

        $r_gl = new \App\Models\CustomModels\r_gl;
        $data = $r_gl->autoJournal($obj);

        return ['status' => true];
    }

}