<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_memo_jurnal extends Model
{   
    use ModelTrait;

    protected $table    = 't_memo_jurnal';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_memo","tanggal_memo","divisi","status","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","no_draft","no_memo","tanggal_memo","divisi","status","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_draft:string:40","no_memo:string:40","tanggal_memo:date","divisi:bigint","status:string:20","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=t_memo_jurnal.divisi"];
    public $details     = ["t_memo_jurnal_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tanggal_memo"];
    public $createable  = ["no_draft","no_memo","tanggal_memo","divisi","status","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["no_draft","no_memo","tanggal_memo","divisi","status","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","no_draft","no_memo","tanggal_memo","divisi","status","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_memo_jurnal_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_memo_jurnal_d', 't_memo_jurnal_id', 'id');
    }
    
    
    public function divisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'divisi', 'id');
    }
}
