<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_credit_note extends Model
{   
    use ModelTrait;

    protected $table    = 't_credit_note';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_credit_note","tipe_credit_note","tanggal","supplier_id","perkiraan_credit","total_credit_note","catatan","status","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","no_draft","customer_id"];

    public $columns     = ["id","no_credit_note","tipe_credit_note","tanggal","supplier_id","perkiraan_credit","total_credit_note","catatan","status","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","no_draft","customer_id"];
    public $columnsFull = ["id:bigint","no_credit_note:string:20","tipe_credit_note:integer","tanggal:date","supplier_id:integer","perkiraan_credit:integer","total_credit_note:decimal","catatan:text","status:string:40","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","no_draft:string:191","customer_id:integer"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=t_credit_note.tipe_credit_note","m_supplier.id=t_credit_note.supplier_id","m_coa.id=t_credit_note.perkiraan_credit","m_customer.id=t_credit_note.customer_id"];
    public $details     = ["t_credit_note_d"];
    public $heirs       = [];
    public $detailsChild= ["t_sub_credit_note"];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tipe_credit_note"];
    public $createable  = ["no_credit_note","tipe_credit_note","tanggal","supplier_id","perkiraan_credit","total_credit_note","catatan","status","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","no_draft","customer_id"];
    public $updateable  = ["no_credit_note","tipe_credit_note","tanggal","supplier_id","perkiraan_credit","total_credit_note","catatan","status","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","no_draft","customer_id"];
    public $searchable  = ["id","no_credit_note","tipe_credit_note","tanggal","supplier_id","perkiraan_credit","total_credit_note","catatan","status","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","no_draft","customer_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_credit_note_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_credit_note_d', 't_credit_note_id', 'id');
    }
    
    
    public function tipe_credit_note() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_credit_note', 'id');
    }
    public function supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'supplier_id', 'id');
    }
    public function perkiraan_credit() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'perkiraan_credit', 'id');
    }
    public function customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'customer_id', 'id');
    }
}
