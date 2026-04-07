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
    protected $fillable = ["t_internal_id","m_item_id","uom_id","qty_stock","usage","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];

    public $columns     = ["id","t_internal_id","m_item_id","uom_id","qty_stock","usage","catatan","creator_id","last_editor_id","created_at","updated_at","deleted_id","deleted_at"];
    public $columnsFull = ["id:bigint","t_internal_id:bigint","m_item_id:bigint","uom_id:bigint","qty_stock:decimal","usage:decimal","catatan:string:191","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime","deleted_id:integer","deleted_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_internal.id=t_internal_d.t_internal_id","m_item.id=t_internal_d.m_item_id","set.m_general.id=t_internal_d.uom_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["t_internal_id","m_item_id","uom_id","qty_stock","usage","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $updateable  = ["t_internal_id","m_item_id","uom_id","qty_stock","usage","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $searchable  = ["id","t_internal_id","m_item_id","uom_id","qty_stock","usage","catatan","creator_id","last_editor_id","created_at","updated_at","deleted_id","deleted_at"];
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
    public function uom() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'uom_id', 'id');
    }
}
