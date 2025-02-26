<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bon_spk_lain extends Model
{   
    use ModelTrait;

    protected $table    = 't_bon_spk_lain';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","status","no_bsg","tanggal","t_spk_lain_lain_id","operator","catatan","total_bon","total_tagihan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","no_draft","status","no_bsg","tanggal","t_spk_lain_lain_id","operator","catatan","total_bon","total_tagihan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_draft:string:20","status:string:191","no_bsg:string:100","tanggal:date","t_spk_lain_lain_id:integer","operator:integer","catatan:text","total_bon:decimal","total_tagihan:decimal","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_spk_lain.id=t_bon_spk_lain.t_spk_lain_lain_id","set.m_kary.id=t_bon_spk_lain.operator"];
    public $details     = ["t_bon_spk_lain_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_spk_lain_lain_id"];
    public $createable  = ["no_draft","status","no_bsg","tanggal","t_spk_lain_lain_id","operator","catatan","total_bon","total_tagihan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["no_draft","status","no_bsg","tanggal","t_spk_lain_lain_id","operator","catatan","total_bon","total_tagihan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","no_draft","status","no_bsg","tanggal","t_spk_lain_lain_id","operator","catatan","total_bon","total_tagihan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_bon_spk_lain_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_bon_spk_lain_d', 't_bon_spk_lain_id', 'id');
    }
    
    
    public function t_spk_lain_lain() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_spk_lain', 't_spk_lain_lain_id', 'id');
    }
    public function operator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_kary', 'operator', 'id');
    }
}
