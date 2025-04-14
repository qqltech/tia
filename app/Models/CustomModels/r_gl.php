<?php

namespace App\Models\CustomModels;

class r_gl extends \App\Models\BasicModels\r_gl
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function autoJournal($obj)
    {
        $date = $obj['date'] ?? date('Y-m-d');
        $type = $obj['form'];
        $ref_table = $obj['ref_table'];
        $ref_id = $obj['ref_id'];
        $ref_no = $obj['ref_no'];
        $m_cust_id = @$obj['m_cust_id'] ?? null;
        $m_supp_id = @$obj['m_supp_id'] ?? null;
        $desc = @$obj['desc'] ?? '';
        $status = 'POST';

        $check = $this->where('type', $type)->where('ref_table', $ref_table)->where('ref_id', $ref_id)->exists();

        if($check) return trigger_error('Transaksi ini sudah melewati proses Journal');

        try{
            \DB::beginTransaction();
            $r_gl = $this->create([
                'date' => $date,
                'type' => $type,
                'ref_table' => $ref_table,
                'ref_id' => $ref_id,
                'ref_no' => $ref_no,
                'm_cust_id' => $m_cust_id,
                'm_supp_id' => $m_supp_id,
                'desc' => $desc,
                'status' => $status,
                'creator_id' => @auth()->user()->id
            ]);

            foreach(@$obj['detail'] ?? [] as $idx => $d){
                $debet = ((float)@$d->debet) ?? null;
                $credit = ((float)@$d->credit) ?? null;

                r_gl_d::create([
                    'r_gl_id' => $r_gl->id,
                    'seq' => $idx+1,
                    'm_coa_id' => $d->m_coa_id,
                    'debet' => $debet,
                    'credit' => $credit,
                    "desc" => @$d->desc
                ]);
            }
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            trigger_error($e->getMessage().'-'.$e->getLine());
        }
    }
}