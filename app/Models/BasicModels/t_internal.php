<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_internal extends Model
{   
    use ModelTrait;

    protected $table    = 't_internal';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_pemakaian","status","tanggal","m_kary_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at","filter_tahun"];

    public $columns     = ["id","no_pemakaian","status","tanggal","m_kary_id","catatan","creator_id","last_editor_id","created_at","updated_at","deleted_id","deleted_at","filter_tahun"];
    public $columnsFull = ["id:bigint","no_pemakaian:string:100","status:string:191","tanggal:date","m_kary_id:bigint","catatan:string:100","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime","deleted_id:integer","deleted_at:datetime","filter_tahun:date"];
    public $rules       = [];
    public $joins       = ["set.m_kary.id=t_internal.m_kary_id"];
    public $details     = ["t_internal_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["no_pemakaian","status","tanggal","m_kary_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at","filter_tahun"];
    public $updateable  = ["no_pemakaian","status","tanggal","m_kary_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at","filter_tahun"];
    public $searchable  = ["id","no_pemakaian","status","tanggal","m_kary_id","catatan","creator_id","last_editor_id","created_at","updated_at","deleted_id","deleted_at","filter_tahun"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_internal_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_internal_d', 't_internal_id', 'id');
    }
    
    
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_kary', 'm_kary_id', 'id');
    }
}
