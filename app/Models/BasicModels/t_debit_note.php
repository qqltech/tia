<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_debit_note extends Model
{   
    use ModelTrait;

    protected $table    = 't_debit_note';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_debit_note","status","tipe_debit_note","tanggal","supplier_id","customer_id","perkiraan_debit","total_debit_note","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","no_draft","no_debit_note","status","tipe_debit_note","tanggal","supplier_id","customer_id","perkiraan_debit","total_debit_note","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_draft:string:20","no_debit_note:string:20","status:string:191","tipe_debit_note:bigint","tanggal:date","supplier_id:bigint","customer_id:integer","perkiraan_debit:bigint","total_debit_note:decimal","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=t_debit_note.tipe_debit_note","m_supplier.id=t_debit_note.supplier_id","m_customer.id=t_debit_note.customer_id","m_coa.id=t_debit_note.perkiraan_debit"];
    public $details     = ["t_debit_note_d"];
    public $heirs       = [];
    public $detailsChild= ["t_sub_debit_note"];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["status","tipe_debit_note","tanggal","perkiraan_debit","total_debit_note"];
    public $createable  = ["no_draft","no_debit_note","status","tipe_debit_note","tanggal","supplier_id","customer_id","perkiraan_debit","total_debit_note","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["no_draft","no_debit_note","status","tipe_debit_note","tanggal","supplier_id","customer_id","perkiraan_debit","total_debit_note","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","no_draft","no_debit_note","status","tipe_debit_note","tanggal","supplier_id","customer_id","perkiraan_debit","total_debit_note","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_debit_note_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_debit_note_d', 't_debit_note_id', 'id');
    }
    
    
    public function tipe_debit_note() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_debit_note', 'id');
    }
    public function supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'supplier_id', 'id');
    }
    public function customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'customer_id', 'id');
    }
    public function perkiraan_debit() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'perkiraan_debit', 'id');
    }
}
