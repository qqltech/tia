<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_users_d extends Model
{   
    use ModelTrait;

    protected $table    = 'set.m_users_d';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["default_users_id","m_responsibility_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];

    public $columns     = ["id","default_users_id","m_responsibility_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","default_users_id:integer","m_responsibility_id:integer","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["default_users.id=set.m_users_d.default_users_id","set.m_responsibility.id=set.m_users_d.m_responsibility_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_responsibility_id"];
    public $createable  = ["default_users_id","m_responsibility_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $updateable  = ["default_users_id","m_responsibility_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $searchable  = ["default_users_id","m_responsibility_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function default_users() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'default_users_id', 'id');
    }
    public function m_responsibility() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_responsibility', 'm_responsibility_id', 'id');
    }
}
