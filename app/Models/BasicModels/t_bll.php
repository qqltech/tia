<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bll extends Model
{   
    use ModelTrait;

    protected $table    = 't_bll';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_bll","no_draft","status","t_buku_order_id","tanggal","m_coa_id","total_amt","m_akun_pembayaran_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at"];

    public $columns     = ["id","no_bll","no_draft","status","t_buku_order_id","tanggal","m_coa_id","total_amt","m_akun_pembayaran_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_bll:string:20","no_draft:string:20","status:string:191","t_buku_order_id:integer","tanggal:date","m_coa_id:integer","total_amt:decimal","m_akun_pembayaran_id:integer","keterangan:text","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_bll.t_buku_order_id","m_perkiraan.id=t_bll.m_coa_id","m_coa.id=t_bll.m_akun_pembayaran_id"];
    public $details     = ["t_bll_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_buku_order_id","tanggal","m_coa_id","total_amt","m_akun_pembayaran_id"];
    public $createable  = ["no_bll","no_draft","status","t_buku_order_id","tanggal","m_coa_id","total_amt","m_akun_pembayaran_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $updateable  = ["no_bll","no_draft","status","t_buku_order_id","tanggal","m_coa_id","total_amt","m_akun_pembayaran_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $searchable  = ["id","no_bll","no_draft","status","t_buku_order_id","tanggal","m_coa_id","total_amt","m_akun_pembayaran_id","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_bll_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_bll_d', 't_bll_id', 'id');
    }
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function m_coa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_perkiraan', 'm_coa_id', 'id');
    }
    public function m_akun_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_akun_pembayaran_id', 'id');
    }
}
