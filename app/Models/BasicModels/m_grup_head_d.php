<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_grup_head_d extends Model
{   
    use ModelTrait;

    protected $table    = 'm_grup_head_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_grup_head_id","no_head_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","m_grup_head_id","no_head_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_grup_head_id:integer","no_head_id:integer","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_grup_head.id=m_grup_head_d.m_grup_head_id","set.m_general.id=m_grup_head_d.no_head_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["m_grup_head_id","no_head_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["m_grup_head_id","no_head_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","m_grup_head_id","no_head_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_grup_head() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_grup_head', 'm_grup_head_id', 'id');
    }
    public function no_head() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'no_head_id', 'id');
    }
}
