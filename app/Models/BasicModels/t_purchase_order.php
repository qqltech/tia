<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_purchase_order extends Model
{   
    use ModelTrait;

    protected $table    = 't_purchase_order';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_po","tanggal","t_purchase_order_id","m_supplier_id","b2b_link","estimasi_kedatangan","catatan","alasan_revisi","status","tipe","termin","ppn","total_amount","dpp","total_ppn","grand_total","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","tipe_po"];

    public $columns     = ["id","no_draft","no_po","tanggal","t_purchase_order_id","m_supplier_id","b2b_link","estimasi_kedatangan","catatan","alasan_revisi","status","tipe","termin","ppn","total_amount","dpp","total_ppn","grand_total","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","tipe_po"];
    public $columnsFull = ["id:bigint","no_draft:string:20","no_po:string:20","tanggal:date","t_purchase_order_id:integer","m_supplier_id:integer","b2b_link:text","estimasi_kedatangan:date","catatan:text","alasan_revisi:text","status:string:20","tipe:string:20","termin:integer","ppn:bigint","total_amount:decimal","dpp:decimal","total_ppn:decimal","grand_total:decimal","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","tipe_po:bigint"];
    public $rules       = [];
    public $joins       = ["m_supplier.id=t_purchase_order.m_supplier_id","set.m_general.id=t_purchase_order.termin","set.m_general.id=t_purchase_order.ppn","set.m_business_unit.id=t_purchase_order.tipe_po"];
    public $details     = ["t_purchase_order_d"];
    public $heirs       = ["t_purchase_invoice","t_lpb","t_purchase_invoice_d"];
    public $detailsChild= [];
    public $detailsHeirs= ["t_lpb_d","t_purchase_invoice_d"];
    public $unique      = [];
    public $required    = ["estimasi_kedatangan","termin","total_amount","dpp","total_ppn","grand_total"];
    public $createable  = ["no_draft","no_po","tanggal","t_purchase_order_id","m_supplier_id","b2b_link","estimasi_kedatangan","catatan","alasan_revisi","status","tipe","termin","ppn","total_amount","dpp","total_ppn","grand_total","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","tipe_po"];
    public $updateable  = ["no_draft","no_po","tanggal","t_purchase_order_id","m_supplier_id","b2b_link","estimasi_kedatangan","catatan","alasan_revisi","status","tipe","termin","ppn","total_amount","dpp","total_ppn","grand_total","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","tipe_po"];
    public $searchable  = ["id","no_draft","no_po","tanggal","t_purchase_order_id","m_supplier_id","b2b_link","estimasi_kedatangan","catatan","alasan_revisi","status","tipe","termin","ppn","total_amount","dpp","total_ppn","grand_total","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","tipe_po"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_purchase_order_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_purchase_order_d', 't_purchase_order_id', 'id');
    }
    
    
    public function m_supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'm_supplier_id', 'id');
    }
    public function termin() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'termin', 'id');
    }
    public function ppn() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'ppn', 'id');
    }
    public function tipe_po() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_business_unit', 'tipe_po', 'id');
    }
}
