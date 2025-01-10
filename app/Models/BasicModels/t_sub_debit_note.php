<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_sub_debit_note extends Model
{   
    use ModelTrait;

    protected $table    = 't_sub_debit_note';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_debit_note_d_id","no_urut","m_coa_id","amount","t_tagihan_id","t_purchase_invoice_id","tipe_perkiraan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_debit_note_d_id","no_urut","m_coa_id","amount","t_tagihan_id","t_purchase_invoice_id","tipe_perkiraan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_debit_note_d_id:integer","no_urut:integer","m_coa_id:integer","amount:decimal","t_tagihan_id:integer","t_purchase_invoice_id:integer","tipe_perkiraan:integer","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_debit_note_d.id=t_sub_debit_note.t_debit_note_d_id","m_coa.id=t_sub_debit_note.m_coa_id","t_tagihan.id=t_sub_debit_note.t_tagihan_id","t_purchase_invoice.id=t_sub_debit_note.t_purchase_invoice_id","set.m_general.id=t_sub_debit_note.tipe_perkiraan"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["no_urut","m_coa_id","amount"];
    public $createable  = ["t_debit_note_d_id","no_urut","m_coa_id","amount","t_tagihan_id","t_purchase_invoice_id","tipe_perkiraan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_debit_note_d_id","no_urut","m_coa_id","amount","t_tagihan_id","t_purchase_invoice_id","tipe_perkiraan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_debit_note_d_id","no_urut","m_coa_id","amount","t_tagihan_id","t_purchase_invoice_id","tipe_perkiraan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_debit_note_d() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_debit_note_d', 't_debit_note_d_id', 'id');
    }
    public function m_coa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_coa_id', 'id');
    }
    public function t_tagihan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_tagihan', 't_tagihan_id', 'id');
    }
    public function t_purchase_invoice() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_purchase_invoice', 't_purchase_invoice_id', 'id');
    }
    public function tipe_perkiraan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_perkiraan', 'id');
    }
}
