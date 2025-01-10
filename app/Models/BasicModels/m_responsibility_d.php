<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_responsibility_d extends Model
{   
    use ModelTrait;

    protected $table    = 'set.m_responsibility_d';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["m_role_id","keterangan","m_responsibility_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];

    public $columns     = ["id","m_role_id","keterangan","m_responsibility_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_role_id:integer","keterangan:string:200","m_responsibility_id:integer","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["set.m_role.id=set.m_responsibility_d.m_role_id","set.m_responsibility.id=set.m_responsibility_d.m_responsibility_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_role_id","keterangan"];
    public $createable  = ["m_role_id","keterangan","m_responsibility_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $updateable  = ["m_role_id","keterangan","m_responsibility_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $searchable  = ["m_role_id","keterangan","m_responsibility_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_role() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_role', 'm_role_id', 'id');
    }
    public function m_responsibility() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_responsibility', 'm_responsibility_id', 'id');
    }
}
