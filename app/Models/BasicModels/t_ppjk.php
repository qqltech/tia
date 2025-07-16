<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_ppjk extends Model
{   
    use ModelTrait;

    protected $table    = 't_ppjk';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","t_buku_order_id","status","tanggal","m_customer_id","kode_customer","no_npwp","no_peb_pib","tanggal_peb_pib","no_sppb","tanggal_sppb","invoice","ppn_pib","currency","nilai_kurs","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","no_ppjk_id"];

    public $columns     = ["id","no_draft","t_buku_order_id","status","tanggal","m_customer_id","kode_customer","no_npwp","no_peb_pib","tanggal_peb_pib","no_sppb","tanggal_sppb","invoice","ppn_pib","currency","nilai_kurs","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","no_ppjk_id"];
    public $columnsFull = ["id:bigint","no_draft:string:20","t_buku_order_id:integer","status:string:10","tanggal:date","m_customer_id:integer","kode_customer:string:191","no_npwp:string:191","no_peb_pib:string:20","tanggal_peb_pib:date","no_sppb:string:20","tanggal_sppb:date","invoice:decimal","ppn_pib:decimal","currency:string:20","nilai_kurs:decimal","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","no_ppjk_id:bigint"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_ppjk.t_buku_order_id","m_customer.id=t_ppjk.m_customer_id","m_generate_no_aju_d.id=t_ppjk.no_ppjk_id"];
    public $details     = [];
    public $heirs       = ["t_buku_order_d_aju"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_buku_order_id","m_customer_id"];
    public $createable  = ["no_draft","t_buku_order_id","status","tanggal","m_customer_id","kode_customer","no_npwp","no_peb_pib","tanggal_peb_pib","no_sppb","tanggal_sppb","invoice","ppn_pib","currency","nilai_kurs","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","no_ppjk_id"];
    public $updateable  = ["no_draft","t_buku_order_id","status","tanggal","m_customer_id","kode_customer","no_npwp","no_peb_pib","tanggal_peb_pib","no_sppb","tanggal_sppb","invoice","ppn_pib","currency","nilai_kurs","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","no_ppjk_id"];
    public $searchable  = ["id","no_draft","t_buku_order_id","status","tanggal","m_customer_id","kode_customer","no_npwp","no_peb_pib","tanggal_peb_pib","no_sppb","tanggal_sppb","invoice","ppn_pib","currency","nilai_kurs","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","no_ppjk_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function m_customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'm_customer_id', 'id');
    }
    public function no_ppjk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_generate_no_aju_d', 'no_ppjk_id', 'id');
    }
}
