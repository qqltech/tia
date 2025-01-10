<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_approval extends Model
{   
    use ModelTrait;

    protected $table    = 'set.m_approval';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["kode","nama","catatan","is_active","m_menu_id","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];

    public $columns     = ["id","kode","nama","catatan","is_active","m_menu_id","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","kode:string:191","nama:string:191","catatan:text","is_active:boolean","m_menu_id:bigint","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["set.m_menu.id=set.m_approval.m_menu_id"];
    public $details     = ["set.m_approval_det"];
    public $heirs       = ["set.generate_approval"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","is_active","m_menu_id"];
    public $createable  = ["kode","nama","catatan","is_active","m_menu_id","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $updateable  = ["kode","nama","catatan","is_active","m_menu_id","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $searchable  = ["kode","nama","catatan","is_active","m_menu_id","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_approval_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_approval_det', 'm_approval_id', 'id');
    }
    
    
    public function m_menu() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_menu', 'm_menu_id', 'id');
    }
}
