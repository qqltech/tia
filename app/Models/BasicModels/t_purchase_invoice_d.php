<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_purchase_invoice_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_purchase_invoice_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_purchase_invoice_id","t_po_id","t_po_detail_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];

    public $columns     = ["id","t_purchase_invoice_id","t_po_id","t_po_detail_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_purchase_invoice_id:integer","t_po_id:integer","t_po_detail_id:integer","catatan:text","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_purchase_invoice.id=t_purchase_invoice_d.t_purchase_invoice_id","t_purchase_order.id=t_purchase_invoice_d.t_po_id","t_purchase_order_d.id=t_purchase_invoice_d.t_po_detail_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_po_id","t_po_detail_id"];
    public $createable  = ["t_purchase_invoice_id","t_po_id","t_po_detail_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $updateable  = ["t_purchase_invoice_id","t_po_id","t_po_detail_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $searchable  = ["id","t_purchase_invoice_id","t_po_id","t_po_detail_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_purchase_invoice() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_purchase_invoice', 't_purchase_invoice_id', 'id');
    }
    public function t_po() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_purchase_order', 't_po_id', 'id');
    }
    public function t_po_detail() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_purchase_order_d', 't_po_detail_id', 'id');
    }
}
