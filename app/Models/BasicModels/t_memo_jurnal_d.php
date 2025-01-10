<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_memo_jurnal_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_memo_jurnal_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_memo_jurnal_id","m_coa_id","catatan","debit","credit","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_memo_jurnal_id","m_coa_id","catatan","debit","credit","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_memo_jurnal_id:integer","m_coa_id:integer","catatan:text","debit:decimal","credit:decimal","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_memo_jurnal.id=t_memo_jurnal_d.t_memo_jurnal_id","m_coa.id=t_memo_jurnal_d.m_coa_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["t_memo_jurnal_id","m_coa_id","catatan","debit","credit","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_memo_jurnal_id","m_coa_id","catatan","debit","credit","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_memo_jurnal_id","m_coa_id","catatan","debit","credit","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_memo_jurnal() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_memo_jurnal', 't_memo_jurnal_id', 'id');
    }
    public function m_coa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_coa_id', 'id');
    }
}
