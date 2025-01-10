<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_asset_d extends Model
{   
    use ModelTrait;

    protected $table    = 'm_asset_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_asset_id","m_supplier_id","catatan","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","m_asset_id","m_supplier_id","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_asset_id:integer","m_supplier_id:integer","catatan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_asset.id=m_asset_d.m_asset_id","m_supplier.id=m_asset_d.m_supplier_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["m_asset_id","m_supplier_id","catatan","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["m_asset_id","m_supplier_id","catatan","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","m_asset_id","m_supplier_id","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_asset() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_asset', 'm_asset_id', 'id');
    }
    public function m_supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'm_supplier_id', 'id');
    }
}
