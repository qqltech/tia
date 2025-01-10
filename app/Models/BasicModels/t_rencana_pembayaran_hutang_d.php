<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_rencana_pembayaran_hutang_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_rencana_pembayaran_hutang_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","t_rencana_pembayaran_hutang_id","jumlah_bayar","keterangan","creator_id","last_editor_id","delete_id","delete_at","t_jurnal_angkutan_id","t_purchase_invoice_id","m_supplier_id","tanggal_realisasi","bayar","tipe_pembayaran_id"];

    public $columns     = ["id","no_draft","t_rencana_pembayaran_hutang_id","jumlah_bayar","keterangan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","t_jurnal_angkutan_id","t_purchase_invoice_id","m_supplier_id","tanggal_realisasi","bayar","tipe_pembayaran_id"];
    public $columnsFull = ["id:bigint","no_draft:string:20","t_rencana_pembayaran_hutang_id:integer","jumlah_bayar:decimal","keterangan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","t_jurnal_angkutan_id:integer","t_purchase_invoice_id:integer","m_supplier_id:integer","tanggal_realisasi:date","bayar:decimal","tipe_pembayaran_id:integer"];
    public $rules       = [];
    public $joins       = ["t_rencana_pembayaran_hutang.id=t_rencana_pembayaran_hutang_d.t_rencana_pembayaran_hutang_id","t_jurnal_angkutan.id=t_rencana_pembayaran_hutang_d.t_jurnal_angkutan_id","t_purchase_invoice.id=t_rencana_pembayaran_hutang_d.t_purchase_invoice_id","m_supplier.id=t_rencana_pembayaran_hutang_d.m_supplier_id","set.m_general.id=t_rencana_pembayaran_hutang_d.tipe_pembayaran_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["jumlah_bayar"];
    public $createable  = ["no_draft","t_rencana_pembayaran_hutang_id","jumlah_bayar","keterangan","creator_id","last_editor_id","delete_id","delete_at","t_jurnal_angkutan_id","t_purchase_invoice_id","m_supplier_id","tanggal_realisasi","bayar","tipe_pembayaran_id"];
    public $updateable  = ["no_draft","t_rencana_pembayaran_hutang_id","jumlah_bayar","keterangan","creator_id","last_editor_id","delete_id","delete_at","t_jurnal_angkutan_id","t_purchase_invoice_id","m_supplier_id","tanggal_realisasi","bayar","tipe_pembayaran_id"];
    public $searchable  = ["id","no_draft","t_rencana_pembayaran_hutang_id","jumlah_bayar","keterangan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","t_jurnal_angkutan_id","t_purchase_invoice_id","m_supplier_id","tanggal_realisasi","bayar","tipe_pembayaran_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_rencana_pembayaran_hutang() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_rencana_pembayaran_hutang', 't_rencana_pembayaran_hutang_id', 'id');
    }
    public function t_jurnal_angkutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_jurnal_angkutan', 't_jurnal_angkutan_id', 'id');
    }
    public function t_purchase_invoice() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_purchase_invoice', 't_purchase_invoice_id', 'id');
    }
    public function m_supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'm_supplier_id', 'id');
    }
    public function tipe_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_pembayaran_id', 'id');
    }
}
