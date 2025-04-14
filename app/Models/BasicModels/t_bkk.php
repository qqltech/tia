<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bkk extends Model
{   
    use ModelTrait;

    protected $table    = 't_bkk';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_bkk","no_draft","status","tipe_bkk","t_buku_order_id","m_business_unit_id","tanggal","m_coa_id","total_amt","tipe_pembayaran","m_akun_pembayaran_id","m_akun_bank_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","nama_penerima"];

    public $columns     = ["id","no_bkk","no_draft","status","tipe_bkk","t_buku_order_id","m_business_unit_id","tanggal","m_coa_id","total_amt","tipe_pembayaran","m_akun_pembayaran_id","m_akun_bank_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","nama_penerima"];
    public $columnsFull = ["id:bigint","no_bkk:string:20","no_draft:string:20","status:string:100","tipe_bkk:string:100","t_buku_order_id:integer","m_business_unit_id:integer","tanggal:date","m_coa_id:integer","total_amt:decimal","tipe_pembayaran:integer","m_akun_pembayaran_id:integer","m_akun_bank_id:integer","keterangan:text","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","nama_penerima:string:100"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_bkk.t_buku_order_id","set.m_business_unit.id=t_bkk.m_business_unit_id","m_coa.id=t_bkk.m_coa_id","set.m_general.id=t_bkk.tipe_pembayaran","m_coa.id=t_bkk.m_akun_pembayaran_id","m_coa.id=t_bkk.m_akun_bank_id"];
    public $details     = ["t_bkk_d"];
    public $heirs       = ["t_bon_dinas_luar","t_buku_penyesuaian"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["status","tipe_bkk","t_buku_order_id","m_business_unit_id","tanggal","m_coa_id","total_amt","tipe_pembayaran","m_akun_pembayaran_id"];
    public $createable  = ["no_bkk","no_draft","status","tipe_bkk","t_buku_order_id","m_business_unit_id","tanggal","m_coa_id","total_amt","tipe_pembayaran","m_akun_pembayaran_id","m_akun_bank_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","nama_penerima"];
    public $updateable  = ["no_bkk","no_draft","status","tipe_bkk","t_buku_order_id","m_business_unit_id","tanggal","m_coa_id","total_amt","tipe_pembayaran","m_akun_pembayaran_id","m_akun_bank_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","nama_penerima"];
    public $searchable  = ["id","no_bkk","no_draft","status","tipe_bkk","t_buku_order_id","m_business_unit_id","tanggal","m_coa_id","total_amt","tipe_pembayaran","m_akun_pembayaran_id","m_akun_bank_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","nama_penerima"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_bkk_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_bkk_d', 't_bkk_id', 'id');
    }
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function m_business_unit() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_business_unit', 'm_business_unit_id', 'id');
    }
    public function m_coa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_coa_id', 'id');
    }
    public function tipe_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_pembayaran', 'id');
    }
    public function m_akun_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_akun_pembayaran_id', 'id');
    }
    public function m_akun_bank() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_akun_bank_id', 'id');
    }
}
