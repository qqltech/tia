<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_tarif_komisi extends Model
{   
    use ModelTrait;

    protected $table    = 'm_tarif_komisi';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["kode","is_active","is_container_tarif_20","container_tarif_20","is_container_tarif_40","container_tarif_40","is_tarif_dokumen","tarif_dokumen","is_tarif_order","tarif_order","is_invoice_minimal","invoice_minimal","tarif_umkm","selisih_ppn_pph","kurs","catatan","creator_id","last_editor_id","tipe_komisi","m_customer_id","tipe_order"];

    public $columns     = ["id","kode","is_active","is_container_tarif_20","container_tarif_20","is_container_tarif_40","container_tarif_40","is_tarif_dokumen","tarif_dokumen","is_tarif_order","tarif_order","is_invoice_minimal","invoice_minimal","tarif_umkm","selisih_ppn_pph","kurs","catatan","creator_id","last_editor_id","created_at","updated_at","tipe_komisi","m_customer_id","tipe_order"];
    public $columnsFull = ["id:bigint","kode:string:20","is_active:boolean","is_container_tarif_20:boolean","container_tarif_20:decimal","is_container_tarif_40:boolean","container_tarif_40:decimal","is_tarif_dokumen:boolean","tarif_dokumen:decimal","is_tarif_order:boolean","tarif_order:decimal","is_invoice_minimal:boolean","invoice_minimal:decimal","tarif_umkm:decimal","selisih_ppn_pph:decimal","kurs:decimal","catatan:text","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime","tipe_komisi:string:191","m_customer_id:bigint","tipe_order:bigint"];
    public $rules       = [];
    public $joins       = ["m_customer.id=m_tarif_komisi.m_customer_id","set.m_general.id=m_tarif_komisi.tipe_order"];
    public $details     = [];
    public $heirs       = ["t_komisi"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["kode","is_container_tarif_20","is_container_tarif_40","is_tarif_dokumen","is_tarif_order","is_invoice_minimal"];
    public $createable  = ["kode","is_active","is_container_tarif_20","container_tarif_20","is_container_tarif_40","container_tarif_40","is_tarif_dokumen","tarif_dokumen","is_tarif_order","tarif_order","is_invoice_minimal","invoice_minimal","tarif_umkm","selisih_ppn_pph","kurs","catatan","creator_id","last_editor_id","tipe_komisi","m_customer_id","tipe_order"];
    public $updateable  = ["kode","is_active","is_container_tarif_20","container_tarif_20","is_container_tarif_40","container_tarif_40","is_tarif_dokumen","tarif_dokumen","is_tarif_order","tarif_order","is_invoice_minimal","invoice_minimal","tarif_umkm","selisih_ppn_pph","kurs","catatan","creator_id","last_editor_id","tipe_komisi","m_customer_id","tipe_order"];
    public $searchable  = ["id","kode","is_active","is_container_tarif_20","container_tarif_20","is_container_tarif_40","container_tarif_40","is_tarif_dokumen","tarif_dokumen","is_tarif_order","tarif_order","is_invoice_minimal","invoice_minimal","tarif_umkm","selisih_ppn_pph","kurs","catatan","creator_id","last_editor_id","created_at","updated_at","tipe_komisi","m_customer_id","tipe_order"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'm_customer_id', 'id');
    }
    public function tipe_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_order', 'id');
    }
}
