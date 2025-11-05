<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_lpb extends Model
{   
    use ModelTrait;

    protected $table    = 't_lpb';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_lpb","t_po_id","no_sj_supplier","catatan","status","tanggal_lpb","tanggal_sj_supplier","creator_id","last_editor_id","deleted_id","deleted_at","m_supplier_id"];

    public $columns     = ["id","no_lpb","t_po_id","no_sj_supplier","catatan","status","tanggal_lpb","tanggal_sj_supplier","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","m_supplier_id"];
    public $columnsFull = ["id:bigint","no_lpb:string:20","t_po_id:integer","no_sj_supplier:string:30","catatan:text","status:string:20","tanggal_lpb:date","tanggal_sj_supplier:date","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","m_supplier_id:bigint"];
    public $rules       = [];
    public $joins       = ["t_purchase_order.id=t_lpb.t_po_id","m_supplier.id=t_lpb.m_supplier_id"];
    public $details     = ["t_lpb_d"];
    public $heirs       = ["t_confirm_asset","t_purchase_invoice"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [
    "no_lpb"=> "unique:t_lpb,no_lpb"
	];
    public $required    = ["no_sj_supplier","status","tanggal_lpb","tanggal_sj_supplier"];
    public $createable  = ["no_lpb","t_po_id","no_sj_supplier","catatan","status","tanggal_lpb","tanggal_sj_supplier","creator_id","last_editor_id","deleted_id","deleted_at","m_supplier_id"];
    public $updateable  = ["no_lpb","t_po_id","no_sj_supplier","catatan","status","tanggal_lpb","tanggal_sj_supplier","creator_id","last_editor_id","deleted_id","deleted_at","m_supplier_id"];
    public $searchable  = ["id","no_lpb","t_po_id","no_sj_supplier","catatan","status","tanggal_lpb","tanggal_sj_supplier","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","m_supplier_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_lpb_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_lpb_d', 't_lpb_id', 'id');
    }
    
    
    public function t_po() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_purchase_order', 't_po_id', 'id');
    }
    public function m_supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'm_supplier_id', 'id');
    }
}
