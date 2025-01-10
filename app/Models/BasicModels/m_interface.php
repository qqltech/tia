<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_interface extends Model
{   
    use ModelTrait;

    protected $table    = 'm_interface';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["divisi","tipe","catatan","is_active","variable","grp","creator_id","last_editor_id","deleted_id","deleted_at"];

    public $columns     = ["id","divisi","tipe","catatan","is_active","variable","grp","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","divisi:bigint","tipe:bigint","catatan:text","is_active:boolean","variable:bigint","grp:bigint","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=m_interface.divisi","set.m_general.id=m_interface.tipe","set.m_general.id=m_interface.variable","set.m_general.id=m_interface.grp"];
    public $details     = ["m_interface_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["divisi","tipe","is_active","variable","grp"];
    public $createable  = ["divisi","tipe","catatan","is_active","variable","grp","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $updateable  = ["divisi","tipe","catatan","is_active","variable","grp","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $searchable  = ["id","divisi","tipe","catatan","is_active","variable","grp","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_interface_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_interface_d', 'm_interface_id', 'id');
    }
    
    
    public function divisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'divisi', 'id');
    }
    public function tipe() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe', 'id');
    }
    public function variable() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'variable', 'id');
    }
    public function grp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'grp', 'id');
    }
}
