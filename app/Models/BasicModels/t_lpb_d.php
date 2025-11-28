<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_lpb_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_lpb_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_lpb_id","m_item_id","t_po_d_id","qty","catatan","creator_id","last_editor_id","deleted_id","deleted_at","harga","uom_id","is_bundling"];

    public $columns     = ["id","t_lpb_id","m_item_id","t_po_d_id","qty","catatan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","harga","uom_id","is_bundling"];
    public $columnsFull = ["id:bigint","t_lpb_id:integer","m_item_id:integer","t_po_d_id:integer","qty:integer","catatan:text","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","harga:decimal","uom_id:bigint","is_bundling:boolean"];
    public $rules       = [];
    public $joins       = ["t_lpb.id=t_lpb_d.t_lpb_id","m_item.id=t_lpb_d.m_item_id","t_purchase_order_d.id=t_lpb_d.t_po_d_id","set.m_general.id=t_lpb_d.uom_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_item_id","t_po_d_id","qty","harga","uom_id"];
    public $createable  = ["t_lpb_id","m_item_id","t_po_d_id","qty","catatan","creator_id","last_editor_id","deleted_id","deleted_at","harga","uom_id","is_bundling"];
    public $updateable  = ["t_lpb_id","m_item_id","t_po_d_id","qty","catatan","creator_id","last_editor_id","deleted_id","deleted_at","harga","uom_id","is_bundling"];
    public $searchable  = ["id","t_lpb_id","m_item_id","t_po_d_id","qty","catatan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","harga","uom_id","is_bundling"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_lpb() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_lpb', 't_lpb_id', 'id');
    }
    public function m_item() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_item', 'm_item_id', 'id');
    }
    public function t_po_d() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_purchase_order_d', 't_po_d_id', 'id');
    }
    public function uom() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'uom_id', 'id');
    }
}
