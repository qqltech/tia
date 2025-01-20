<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bkm_non_order extends Model
{   
    use ModelTrait;

    protected $table    = 't_bkm_non_order';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_bkm","status","t_buku_order_id","tanggal","m_akun_pembayaran_id","m_business_unit_id","tipe_pembayaran","m_akun_bank_id","total_amt","no_ref","keterangan","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","no_draft","no_bkm","status","t_buku_order_id","tanggal","m_akun_pembayaran_id","m_business_unit_id","tipe_pembayaran","m_akun_bank_id","total_amt","no_ref","keterangan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_draft:string:20","no_bkm:string:20","status:string:10","t_buku_order_id:integer","tanggal:date","m_akun_pembayaran_id:integer","m_business_unit_id:integer","tipe_pembayaran:integer","m_akun_bank_id:integer","total_amt:decimal","no_ref:string:20","keterangan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_bkm_non_order.t_buku_order_id","m_coa.id=t_bkm_non_order.m_akun_pembayaran_id","set.m_business_unit.id=t_bkm_non_order.m_business_unit_id","set.m_general.id=t_bkm_non_order.tipe_pembayaran","m_coa.id=t_bkm_non_order.m_akun_bank_id"];
    public $details     = ["t_bkm_non_order_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["status","tanggal","m_business_unit_id","tipe_pembayaran","total_amt"];
    public $createable  = ["no_draft","no_bkm","status","t_buku_order_id","tanggal","m_akun_pembayaran_id","m_business_unit_id","tipe_pembayaran","m_akun_bank_id","total_amt","no_ref","keterangan","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["no_draft","no_bkm","status","t_buku_order_id","tanggal","m_akun_pembayaran_id","m_business_unit_id","tipe_pembayaran","m_akun_bank_id","total_amt","no_ref","keterangan","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","no_draft","no_bkm","status","t_buku_order_id","tanggal","m_akun_pembayaran_id","m_business_unit_id","tipe_pembayaran","m_akun_bank_id","total_amt","no_ref","keterangan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_bkm_non_order_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_bkm_non_order_d', 't_bkm_non_order_id', 'id');
    }
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function m_akun_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_akun_pembayaran_id', 'id');
    }
    public function m_business_unit() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_business_unit', 'm_business_unit_id', 'id');
    }
    public function tipe_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_pembayaran', 'id');
    }
    public function m_akun_bank() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_akun_bank_id', 'id');
    }
}
