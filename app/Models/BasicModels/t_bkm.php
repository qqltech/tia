<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bkm extends Model
{   
    use ModelTrait;

    protected $table    = 't_bkm';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_bkm","no_draft","status","t_buku_order_id","tanggal","total_amt","m_akun_pembayaran_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","tipe_pembayaran","m_akun_bank_id","m_business_unit_id","nama_penyetor"];

    public $columns     = ["id","no_bkm","no_draft","status","t_buku_order_id","tanggal","total_amt","m_akun_pembayaran_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","tipe_pembayaran","m_akun_bank_id","m_business_unit_id","nama_penyetor"];
    public $columnsFull = ["id:bigint","no_bkm:string:20","no_draft:string:20","status:string:191","t_buku_order_id:integer","tanggal:date","total_amt:decimal","m_akun_pembayaran_id:integer","keterangan:text","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","tipe_pembayaran:integer","m_akun_bank_id:integer","m_business_unit_id:integer","nama_penyetor:string:100"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_bkm.t_buku_order_id","m_coa.id=t_bkm.m_akun_pembayaran_id","set.m_general.id=t_bkm.tipe_pembayaran","m_coa.id=t_bkm.m_akun_bank_id","set.m_business_unit.id=t_bkm.m_business_unit_id"];
    public $details     = ["t_bkm_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["status","t_buku_order_id","tanggal","total_amt","m_akun_pembayaran_id","tipe_pembayaran","m_business_unit_id"];
    public $createable  = ["no_bkm","no_draft","status","t_buku_order_id","tanggal","total_amt","m_akun_pembayaran_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","tipe_pembayaran","m_akun_bank_id","m_business_unit_id","nama_penyetor"];
    public $updateable  = ["no_bkm","no_draft","status","t_buku_order_id","tanggal","total_amt","m_akun_pembayaran_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","tipe_pembayaran","m_akun_bank_id","m_business_unit_id","nama_penyetor"];
    public $searchable  = ["id","no_bkm","no_draft","status","t_buku_order_id","tanggal","total_amt","m_akun_pembayaran_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","tipe_pembayaran","m_akun_bank_id","m_business_unit_id","nama_penyetor"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_bkm_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_bkm_d', 't_bkm_id', 'id');
    }
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function m_akun_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_akun_pembayaran_id', 'id');
    }
    public function tipe_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_pembayaran', 'id');
    }
    public function m_akun_bank() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_akun_bank_id', 'id');
    }
    public function m_business_unit() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_business_unit', 'm_business_unit_id', 'id');
    }
}
