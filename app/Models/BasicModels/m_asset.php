<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_asset extends Model
{   
    use ModelTrait;

    protected $table    = 'm_asset';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["kode","nama","catatan","is_active","tanggal","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","kode","nama","catatan","is_active","tanggal","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","kode:string:20","nama:string:100","catatan:text","is_active:boolean","tanggal:date","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["m_asset_d"];
    public $heirs       = ["t_asset_disposal"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","is_active","tanggal"];
    public $createable  = ["kode","nama","catatan","is_active","tanggal","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["kode","nama","catatan","is_active","tanggal","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","kode","nama","catatan","is_active","tanggal","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_asset_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_asset_d', 'm_asset_id', 'id');
    }
    
    
}
