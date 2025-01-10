<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_komisi extends Model
{   
    use ModelTrait;

    protected $table    = 't_komisi';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["tipe_komisi","no_komisi","m_tarif_komisi_id","m_customer_id","t_buku_order_awal_id","t_buku_order_akhir_id","is_pph","status","catatan","creator_id","last_editor_id","grandtotal"];

    public $columns     = ["id","tipe_komisi","no_komisi","m_tarif_komisi_id","m_customer_id","t_buku_order_awal_id","t_buku_order_akhir_id","is_pph","status","catatan","creator_id","last_editor_id","created_at","updated_at","grandtotal"];
    public $columnsFull = ["id:bigint","tipe_komisi:string:50","no_komisi:string:50","m_tarif_komisi_id:integer","m_customer_id:integer","t_buku_order_awal_id:integer","t_buku_order_akhir_id:integer","is_pph:boolean","status:string:20","catatan:text","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime","grandtotal:decimal"];
    public $rules       = [];
    public $joins       = ["m_tarif_komisi.id=t_komisi.m_tarif_komisi_id","m_customer.id=t_komisi.m_customer_id","t_buku_order.id=t_komisi.t_buku_order_awal_id","t_buku_order.id=t_komisi.t_buku_order_akhir_id"];
    public $details     = ["t_komisi_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tipe_komisi","m_tarif_komisi_id","m_customer_id","t_buku_order_awal_id","t_buku_order_akhir_id","is_pph"];
    public $createable  = ["tipe_komisi","no_komisi","m_tarif_komisi_id","m_customer_id","t_buku_order_awal_id","t_buku_order_akhir_id","is_pph","status","catatan","creator_id","last_editor_id","grandtotal"];
    public $updateable  = ["tipe_komisi","no_komisi","m_tarif_komisi_id","m_customer_id","t_buku_order_awal_id","t_buku_order_akhir_id","is_pph","status","catatan","creator_id","last_editor_id","grandtotal"];
    public $searchable  = ["id","tipe_komisi","no_komisi","m_tarif_komisi_id","m_customer_id","t_buku_order_awal_id","t_buku_order_akhir_id","is_pph","status","catatan","creator_id","last_editor_id","created_at","updated_at","grandtotal"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_komisi_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_komisi_d', 't_komisi_id', 'id');
    }
    
    
    public function m_tarif_komisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_tarif_komisi', 'm_tarif_komisi_id', 'id');
    }
    public function m_customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'm_customer_id', 'id');
    }
    public function t_buku_order_awal() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_awal_id', 'id');
    }
    public function t_buku_order_akhir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_akhir_id', 'id');
    }
}
