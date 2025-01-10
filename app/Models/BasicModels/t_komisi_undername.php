<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_komisi_undername extends Model
{   
    use ModelTrait;

    protected $table    = 't_komisi_undername';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_komisi_undername","tanggal","t_buku_order_id","tipe_komisi","nilai_invoice","kurs","nilai_pabean","nilai_pajak_komisi","tarif_komisi","total_komisi","persentase","status_id","catatan","customer_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","tanggal_pelunasan"];

    public $columns     = ["id","no_komisi_undername","tanggal","t_buku_order_id","tipe_komisi","nilai_invoice","kurs","nilai_pabean","nilai_pajak_komisi","tarif_komisi","total_komisi","persentase","status_id","catatan","customer_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","tanggal_pelunasan"];
    public $columnsFull = ["id:bigint","no_komisi_undername:string:50","tanggal:date","t_buku_order_id:integer","tipe_komisi:string:50","nilai_invoice:decimal","kurs:decimal","nilai_pabean:decimal","nilai_pajak_komisi:decimal","tarif_komisi:decimal","total_komisi:decimal","persentase:decimal","status_id:string:20","catatan:text","customer_id:bigint","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","tanggal_pelunasan:date"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_komisi_undername.t_buku_order_id","m_customer.id=t_komisi_undername.customer_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tanggal","t_buku_order_id","tipe_komisi","nilai_invoice","kurs","nilai_pabean","nilai_pajak_komisi","tarif_komisi","total_komisi","persentase"];
    public $createable  = ["no_komisi_undername","tanggal","t_buku_order_id","tipe_komisi","nilai_invoice","kurs","nilai_pabean","nilai_pajak_komisi","tarif_komisi","total_komisi","persentase","status_id","catatan","customer_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","tanggal_pelunasan"];
    public $updateable  = ["no_komisi_undername","tanggal","t_buku_order_id","tipe_komisi","nilai_invoice","kurs","nilai_pabean","nilai_pajak_komisi","tarif_komisi","total_komisi","persentase","status_id","catatan","customer_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","tanggal_pelunasan"];
    public $searchable  = ["id","no_komisi_undername","tanggal","t_buku_order_id","tipe_komisi","nilai_invoice","kurs","nilai_pabean","nilai_pajak_komisi","tarif_komisi","total_komisi","persentase","status_id","catatan","customer_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","tanggal_pelunasan"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'customer_id', 'id');
    }
}
