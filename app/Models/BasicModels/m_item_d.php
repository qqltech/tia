<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_item_d extends Model
{   
    use ModelTrait;

    protected $table    = 'm_item_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_item","no_lpb","catatan","qty_stock","used"];

    public $columns     = ["id","m_item","no_lpb","catatan","qty_stock","used","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_item:bigint","no_lpb:string:191","catatan:string:191","qty_stock:decimal","used:boolean","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_item.id=m_item_d.m_item"];
    public $details     = [];
    public $heirs       = ["t_internal_d"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_item"];
    public $createable  = ["m_item","no_lpb","catatan","qty_stock","used"];
    public $updateable  = ["m_item","no_lpb","catatan","qty_stock","used"];
    public $searchable  = ["id","m_item","no_lpb","catatan","qty_stock","used","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_item() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_item', 'm_item', 'id');
    }
}
