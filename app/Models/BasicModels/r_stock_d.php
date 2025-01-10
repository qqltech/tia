<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class r_stock_d extends Model
{   
    use ModelTrait;

    protected $table    = 'r_stock_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["r_stock_id","ref_table","ref_id","typemin","m_item_id","qty_awal","qty_in","qty_out","price","price_old","note","creator_id","last_editor_id"];

    public $columns     = ["id","r_stock_id","ref_table","ref_id","typemin","m_item_id","qty_awal","qty_in","qty_out","price","price_old","note","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","r_stock_id:integer","ref_table:string:100","ref_id:bigint","typemin:integer","m_item_id:integer","qty_awal:integer","qty_in:integer","qty_out:integer","price:decimal","price_old:decimal","note:text","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["r_stock.id=r_stock_d.r_stock_id","m_item.id=r_stock_d.m_item_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["ref_table","ref_id","typemin","m_item_id","qty_awal","price","price_old"];
    public $createable  = ["r_stock_id","ref_table","ref_id","typemin","m_item_id","qty_awal","qty_in","qty_out","price","price_old","note","creator_id","last_editor_id"];
    public $updateable  = ["r_stock_id","ref_table","ref_id","typemin","m_item_id","qty_awal","qty_in","qty_out","price","price_old","note","creator_id","last_editor_id"];
    public $searchable  = ["id","r_stock_id","ref_table","ref_id","typemin","m_item_id","qty_awal","qty_in","qty_out","price","price_old","note","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function r_stock() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\r_stock', 'r_stock_id', 'id');
    }
    public function m_item() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_item', 'm_item_id', 'id');
    }
}
