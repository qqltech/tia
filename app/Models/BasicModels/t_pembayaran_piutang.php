<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pembayaran_piutang extends Model
{   
    use ModelTrait;

    protected $table    = 't_pembayaran_piutang';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_pembayaran","status","tanggal","tanggal_pembayaran","tipe_pembayaran","total_amt","m_akun_pembayaran_id","customer","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","m_akun_bank_id","tipe_piutang"];

    public $columns     = ["id","no_draft","no_pembayaran","status","tanggal","tanggal_pembayaran","tipe_pembayaran","total_amt","m_akun_pembayaran_id","customer","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","m_akun_bank_id","tipe_piutang"];
    public $columnsFull = ["id:bigint","no_draft:string:20","no_pembayaran:string:20","status:string:20","tanggal:date","tanggal_pembayaran:date","tipe_pembayaran:integer","total_amt:decimal","m_akun_pembayaran_id:integer","customer:integer","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","m_akun_bank_id:integer","tipe_piutang:integer"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=t_pembayaran_piutang.tipe_pembayaran","m_coa.id=t_pembayaran_piutang.m_akun_pembayaran_id","m_customer.id=t_pembayaran_piutang.customer","m_coa.id=t_pembayaran_piutang.m_akun_bank_id","set.m_general.id=t_pembayaran_piutang.tipe_piutang"];
    public $details     = ["t_pembayaran_piutang_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tanggal_pembayaran","tipe_pembayaran","m_akun_pembayaran_id","customer"];
    public $createable  = ["no_draft","no_pembayaran","status","tanggal","tanggal_pembayaran","tipe_pembayaran","total_amt","m_akun_pembayaran_id","customer","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","m_akun_bank_id","tipe_piutang"];
    public $updateable  = ["no_draft","no_pembayaran","status","tanggal","tanggal_pembayaran","tipe_pembayaran","total_amt","m_akun_pembayaran_id","customer","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","m_akun_bank_id","tipe_piutang"];
    public $searchable  = ["id","no_draft","no_pembayaran","status","tanggal","tanggal_pembayaran","tipe_pembayaran","total_amt","m_akun_pembayaran_id","customer","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","m_akun_bank_id","tipe_piutang"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_pembayaran_piutang_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_pembayaran_piutang_d', 't_pembayaran_piutang_id', 'id');
    }
    
    
    public function tipe_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_pembayaran', 'id');
    }
    public function m_akun_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_akun_pembayaran_id', 'id');
    }
    public function customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'customer', 'id');
    }
    public function m_akun_bank() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_akun_bank_id', 'id');
    }
    public function tipe_piutang() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_piutang', 'id');
    }
}
