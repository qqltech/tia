<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_responsibility extends Model
{   
    use ModelTrait;

    protected $table    = 'set.m_responsibility';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["nama","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","is_active"];

    public $columns     = ["id","nama","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","is_active"];
    public $columnsFull = ["id:bigint","nama:string:100","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","is_active:boolean"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["set.m_responsibility_d"];
    public $heirs       = ["set.m_users_d"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","is_active"];
    public $createable  = ["nama","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","is_active"];
    public $updateable  = ["nama","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","is_active"];
    public $searchable  = ["nama","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","is_active"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_responsibility_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_responsibility_d', 'm_responsibility_id', 'id');
    }
    
    
}
