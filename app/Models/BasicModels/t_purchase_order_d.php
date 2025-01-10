<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_purchase_order_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_purchase_order_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_purchase_order_id","m_item_id","quantity","harga","disc1","disc2","disc_amt","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","is_bundling"];

    public $columns     = ["id","t_purchase_order_id","m_item_id","quantity","harga","disc1","disc2","disc_amt","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","is_bundling"];
    public $columnsFull = ["id:bigint","t_purchase_order_id:integer","m_item_id:integer","quantity:integer","harga:decimal","disc1:decimal","disc2:decimal","disc_amt:decimal","catatan:string:100","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","is_bundling:boolean"];
    public $rules       = [];
    public $joins       = ["t_purchase_order.id=t_purchase_order_d.t_purchase_order_id","m_item.id=t_purchase_order_d.m_item_id"];
    public $details     = [];
    public $heirs       = ["t_lpb_d","t_purchase_invoice_d"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_item_id","quantity","harga"];
    public $createable  = ["t_purchase_order_id","m_item_id","quantity","harga","disc1","disc2","disc_amt","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","is_bundling"];
    public $updateable  = ["t_purchase_order_id","m_item_id","quantity","harga","disc1","disc2","disc_amt","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","is_bundling"];
    public $searchable  = ["id","t_purchase_order_id","m_item_id","quantity","harga","disc1","disc2","disc_amt","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","is_bundling"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_purchase_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_purchase_order', 't_purchase_order_id', 'id');
    }
    public function m_item() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_item', 'm_item_id', 'id');
    }
}
