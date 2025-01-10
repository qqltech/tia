<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_role extends Model
{   
    use ModelTrait;

    protected $table    = 'set.m_role';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["name","is_superadmin","is_active","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];

    public $columns     = ["id","name","is_superadmin","is_active","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","name:string:191","is_superadmin:boolean","is_active:boolean","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["set.m_role_d"];
    public $heirs       = ["set.m_role_access","set.m_responsibility_d","set.m_approval_det","set.generate_approval_det"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["name","is_superadmin","is_active"];
    public $createable  = ["name","is_superadmin","is_active","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $updateable  = ["name","is_superadmin","is_active","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $searchable  = ["name","is_superadmin","is_active","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_role_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_role_d', 'm_role_id', 'id');
    }
    
    
}
