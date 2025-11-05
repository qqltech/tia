<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_internal_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_internal_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_internal_id","m_item_id","m_item_d_id","usage","catatan","satuan_id","is_bundling"];

    public $columns     = ["id","t_internal_id","m_item_id","m_item_d_id","usage","catatan","created_at","updated_at","satuan_id","is_bundling"];
    public $columnsFull = ["id:bigint","t_internal_id:bigint","m_item_id:bigint","m_item_d_id:bigint","usage:decimal","catatan:string:15","created_at:datetime","updated_at:datetime","satuan_id:bigint","is_bundling:boolean"];
    public $rules       = [];
    public $joins       = ["t_internal.id=t_internal_d.t_internal_id","m_item.id=t_internal_d.m_item_id","m_item_d.id=t_internal_d.m_item_d_id","m_general.id=t_internal_d.satuan_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["t_internal_id","m_item_id","m_item_d_id","usage","catatan","satuan_id","is_bundling"];
    public $updateable  = ["t_internal_id","m_item_id","m_item_d_id","usage","catatan","satuan_id","is_bundling"];
    public $searchable  = ["id","t_internal_id","m_item_id","m_item_d_id","usage","catatan","created_at","updated_at","satuan_id","is_bundling"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_internal() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_internal', 't_internal_id', 'id');
    }
    public function m_item() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_item', 'm_item_id', 'id');
    }
    public function m_item_d() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_item_d', 'm_item_d_id', 'id');
    }
    public function satuan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'satuan_id', 'id');
    }
}
