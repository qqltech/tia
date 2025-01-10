<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_role_d extends Model
{   
    use ModelTrait;

    protected $table    = 'set.m_role_d';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["m_role_id","m_menu_id","can_create","can_read","can_update","can_delete","can_verify","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];

    public $columns     = ["id","m_role_id","m_menu_id","can_create","can_read","can_update","can_delete","can_verify","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_role_id:bigint","m_menu_id:bigint","can_create:boolean","can_read:boolean","can_update:boolean","can_delete:boolean","can_verify:boolean","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["set.m_role.id=set.m_role_d.m_role_id","set.m_menu.id=set.m_role_d.m_menu_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["m_role_id","m_menu_id","can_create","can_read","can_update","can_delete","can_verify","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $updateable  = ["m_role_id","m_menu_id","can_create","can_read","can_update","can_delete","can_verify","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $searchable  = ["m_role_id","m_menu_id","can_create","can_read","can_update","can_delete","can_verify","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_role() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_role', 'm_role_id', 'id');
    }
    public function m_menu() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_menu', 'm_menu_id', 'id');
    }
}
