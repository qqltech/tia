<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_purchase_invoice extends Model
{   
    use ModelTrait;

    protected $table    = 't_purchase_invoice';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","status","tanggal","no_pi","t_po_id","no_faktur_pajak","m_supplier_id","t_lpb_id","jenis_ppn","persen_ppn","jenis_pph","persen_pph","catatan","total_pph","total_ppn","grand_total","creator_id","last_editor_id","deleted_id","deleted_at","utang","tipe_pembayaran_id"];

    public $columns     = ["id","no_draft","status","tanggal","no_pi","t_po_id","no_faktur_pajak","m_supplier_id","t_lpb_id","jenis_ppn","persen_ppn","jenis_pph","persen_pph","catatan","total_pph","total_ppn","grand_total","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","utang","tipe_pembayaran_id"];
    public $columnsFull = ["id:bigint","no_draft:string:20","status:string:20","tanggal:date","no_pi:string:20","t_po_id:integer","no_faktur_pajak:string:255","m_supplier_id:integer","t_lpb_id:integer","jenis_ppn:integer","persen_ppn:float","jenis_pph:integer","persen_pph:float","catatan:text","total_pph:decimal","total_ppn:decimal","grand_total:decimal","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","utang:decimal","tipe_pembayaran_id:integer"];
    public $rules       = [];
    public $joins       = ["t_purchase_order.id=t_purchase_invoice.t_po_id","m_supplier.id=t_purchase_invoice.m_supplier_id","t_lpb.id=t_purchase_invoice.t_lpb_id","set.m_general.id=t_purchase_invoice.jenis_ppn","set.m_general.id=t_purchase_invoice.jenis_pph","set.m_general.id=t_purchase_invoice.tipe_pembayaran_id"];
    public $details     = ["t_purchase_invoice_d"];
    public $heirs       = ["t_pembayaran_hutang_d","t_rencana_pembayaran_hutang_d","t_credit_note_d","t_debit_note_d","t_sub_debit_note","t_sub_credit_note"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["no_draft","status","tanggal","no_pi","t_po_id","no_faktur_pajak","m_supplier_id","t_lpb_id","jenis_ppn","persen_ppn","jenis_pph","persen_pph","total_pph","total_ppn","grand_total"];
    public $createable  = ["no_draft","status","tanggal","no_pi","t_po_id","no_faktur_pajak","m_supplier_id","t_lpb_id","jenis_ppn","persen_ppn","jenis_pph","persen_pph","catatan","total_pph","total_ppn","grand_total","creator_id","last_editor_id","deleted_id","deleted_at","utang","tipe_pembayaran_id"];
    public $updateable  = ["no_draft","status","tanggal","no_pi","t_po_id","no_faktur_pajak","m_supplier_id","t_lpb_id","jenis_ppn","persen_ppn","jenis_pph","persen_pph","catatan","total_pph","total_ppn","grand_total","creator_id","last_editor_id","deleted_id","deleted_at","utang","tipe_pembayaran_id"];
    public $searchable  = ["id","no_draft","status","tanggal","no_pi","t_po_id","no_faktur_pajak","m_supplier_id","t_lpb_id","jenis_ppn","persen_ppn","jenis_pph","persen_pph","catatan","total_pph","total_ppn","grand_total","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","utang","tipe_pembayaran_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_purchase_invoice_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_purchase_invoice_d', 't_purchase_invoice_id', 'id');
    }
    
    
    public function t_po() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_purchase_order', 't_po_id', 'id');
    }
    public function m_supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'm_supplier_id', 'id');
    }
    public function t_lpb() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_lpb', 't_lpb_id', 'id');
    }
    public function jenis_ppn() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis_ppn', 'id');
    }
    public function jenis_pph() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis_pph', 'id');
    }
    public function tipe_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_pembayaran_id', 'id');
    }
}
