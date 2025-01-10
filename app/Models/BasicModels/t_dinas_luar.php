<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_dinas_luar extends Model
{   
    use ModelTrait;

    protected $table    = 't_dinas_luar';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_dinas_luar","tanggal","status","total_amt","supir_id","creator_id","last_editor_id","deleted_id","deleted_at"];

    public $columns     = ["id","no_dinas_luar","tanggal","status","total_amt","supir_id","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_dinas_luar:string:20","tanggal:date","status:string:191","total_amt:decimal","supir_id:integer","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["set.m_kary.id=t_dinas_luar.supir_id"];
    public $details     = ["t_dinas_luar_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tanggal","status","total_amt","supir_id"];
    public $createable  = ["no_dinas_luar","tanggal","status","total_amt","supir_id","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $updateable  = ["no_dinas_luar","tanggal","status","total_amt","supir_id","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $searchable  = ["id","no_dinas_luar","tanggal","status","total_amt","supir_id","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_dinas_luar_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_dinas_luar_d', 't_dinas_luar_id', 'id');
    }
    
    
    public function supir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_kary', 'supir_id', 'id');
    }
}
