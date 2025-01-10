<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class tes_d extends Model
{   
    use ModelTrait;

    protected $table    = 'tes_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["tes_id","m_item_id","price","qty","subtotal","creator_id","last_editor_id"];

    public $columns     = ["id","tes_id","m_item_id","price","qty","subtotal","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","tes_id:bigint","m_item_id:bigint","price:decimal","qty:decimal","subtotal:decimal","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["tes.id=tes_d.tes_id","m_item.id=tes_d.m_item_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_item_id","price","qty","subtotal"];
    public $createable  = ["tes_id","m_item_id","price","qty","subtotal","creator_id","last_editor_id"];
    public $updateable  = ["tes_id","m_item_id","price","qty","subtotal","creator_id","last_editor_id"];
    public $searchable  = ["id","tes_id","m_item_id","price","qty","subtotal","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function tes() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\tes', 'tes_id', 'id');
    }
    public function m_item() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_item', 'm_item_id', 'id');
    }
}
