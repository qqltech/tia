<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_buku_penyesuaian extends Model
{   
    use ModelTrait;

    protected $table    = 't_buku_penyesuaian';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_buku_penyesuaian","no_draft","t_buku_order_id","tanggal_buku_penyesuaian","no_bkk_id","total_amt","m_akun_pembayaran_id","status","keterangan","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","no_buku_penyesuaian","no_draft","t_buku_order_id","tanggal_buku_penyesuaian","no_bkk_id","total_amt","m_akun_pembayaran_id","status","keterangan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_buku_penyesuaian:string:40","no_draft:string:40","t_buku_order_id:bigint","tanggal_buku_penyesuaian:date","no_bkk_id:bigint","total_amt:decimal","m_akun_pembayaran_id:bigint","status:string:10","keterangan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_buku_penyesuaian.t_buku_order_id","t_bkk.id=t_buku_penyesuaian.no_bkk_id","m_coa.id=t_buku_penyesuaian.m_akun_pembayaran_id"];
    public $details     = ["t_buku_penyesuaian_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tanggal_buku_penyesuaian","total_amt"];
    public $createable  = ["no_buku_penyesuaian","no_draft","t_buku_order_id","tanggal_buku_penyesuaian","no_bkk_id","total_amt","m_akun_pembayaran_id","status","keterangan","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["no_buku_penyesuaian","no_draft","t_buku_order_id","tanggal_buku_penyesuaian","no_bkk_id","total_amt","m_akun_pembayaran_id","status","keterangan","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","no_buku_penyesuaian","no_draft","t_buku_order_id","tanggal_buku_penyesuaian","no_bkk_id","total_amt","m_akun_pembayaran_id","status","keterangan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_buku_penyesuaian_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_buku_penyesuaian_d', 't_buku_penyesuaian_id', 'id');
    }
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function no_bkk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_bkk', 'no_bkk_id', 'id');
    }
    public function m_akun_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_akun_pembayaran_id', 'id');
    }
}
