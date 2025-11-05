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
    protected $fillable = ["no_bkm","no_draft","status","tanggal","nama_penerima","total_amt","m_akun_pembayaran_id","tipe_pembayaran","m_akun_bank_id","keterangan","m_business_unit_id","creator_id","last_editor_id","deleted_id","deleted_at","no_ref","no_reference"];

    public $columns     = ["id","no_bkm","no_draft","status","tanggal","nama_penerima","total_amt","m_akun_pembayaran_id","tipe_pembayaran","m_akun_bank_id","keterangan","m_business_unit_id","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","no_ref","no_reference"];
    public $columnsFull = ["id:bigint","no_bkm:string:20","no_draft:string:20","status:string:191","tanggal:date","nama_penerima:string:100","total_amt:decimal","m_akun_pembayaran_id:integer","tipe_pembayaran:integer","m_akun_bank_id:integer","keterangan:text","m_business_unit_id:integer","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","no_ref:string:20","no_reference:string:50"];
    public $rules       = [];
    public $joins       = ["m_coa.id=t_bkm.m_akun_pembayaran_id","set.m_general.id=t_bkm.tipe_pembayaran","m_coa.id=t_bkm.m_akun_bank_id","set.m_business_unit.id=t_bkm.m_business_unit_id"];
    public $details     = ["t_bkm_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["status","tanggal","total_amt","m_akun_pembayaran_id","tipe_pembayaran","m_business_unit_id"];
    public $createable  = ["no_bkm","no_draft","status","tanggal","nama_penerima","total_amt","m_akun_pembayaran_id","tipe_pembayaran","m_akun_bank_id","keterangan","m_business_unit_id","creator_id","last_editor_id","deleted_id","deleted_at","no_ref","no_reference"];
    public $updateable  = ["no_bkm","no_draft","status","tanggal","nama_penerima","total_amt","m_akun_pembayaran_id","tipe_pembayaran","m_akun_bank_id","keterangan","m_business_unit_id","creator_id","last_editor_id","deleted_id","deleted_at","no_ref","no_reference"];
    public $searchable  = ["id","no_bkm","no_draft","status","tanggal","nama_penerima","total_amt","m_akun_pembayaran_id","tipe_pembayaran","m_akun_bank_id","keterangan","m_business_unit_id","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","no_ref","no_reference"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_bkm_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_bkm_d', 't_bkm_id', 'id');
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
