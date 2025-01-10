<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_role_access extends Model
{   
    use ModelTrait;

    protected $table    = 'set.m_role_access';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["user_id","m_role_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];

    public $columns     = ["id","user_id","m_role_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","user_id:bigint","m_role_id:bigint","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["default_users.id=set.m_role_access.user_id","set.m_role.id=set.m_role_access.m_role_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["user_id","m_role_id"];
    public $createable  = ["user_id","m_role_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $updateable  = ["user_id","m_role_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $searchable  = ["user_id","m_role_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function user() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'user_id', 'id');
    }
    public function m_role() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_role', 'm_role_id', 'id');
    }
}
