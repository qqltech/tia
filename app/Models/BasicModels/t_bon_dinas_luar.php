<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bon_dinas_luar extends Model
{   
    use ModelTrait;

    protected $table    = 't_bon_dinas_luar';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_bon_dinas_luar","no_draft","status","tipe_order_id","tipe_kategori_id","tanggal","t_bkk_id","no_bkk","total_amt","m_kary_id","m_supplier_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];

    public $columns     = ["id","no_bon_dinas_luar","no_draft","status","tipe_order_id","tipe_kategori_id","tanggal","t_bkk_id","no_bkk","total_amt","m_kary_id","m_supplier_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_bon_dinas_luar:string:20","no_draft:string:20","status:string:100","tipe_order_id:bigint","tipe_kategori_id:bigint","tanggal:date","t_bkk_id:bigint","no_bkk:string:100","total_amt:decimal","m_kary_id:bigint","m_supplier_id:bigint","catatan:text","creator_id:bigint","last_editor_id:bigint","deleted_id:bigint","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_gen.id=t_bon_dinas_luar.tipe_order_id","m_gen.id=t_bon_dinas_luar.tipe_kategori_id","t_bkk.id=t_bon_dinas_luar.t_bkk_id","set.m_kary.id=t_bon_dinas_luar.m_kary_id","m_supplier.id=t_bon_dinas_luar.m_supplier_id"];
    public $details     = ["t_bon_dinas_luar_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tipe_order_id","tipe_kategori_id","no_bkk","total_amt","m_kary_id","m_supplier_id"];
    public $createable  = ["no_bon_dinas_luar","no_draft","status","tipe_order_id","tipe_kategori_id","tanggal","t_bkk_id","no_bkk","total_amt","m_kary_id","m_supplier_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $updateable  = ["no_bon_dinas_luar","no_draft","status","tipe_order_id","tipe_kategori_id","tanggal","t_bkk_id","no_bkk","total_amt","m_kary_id","m_supplier_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $searchable  = ["id","no_bon_dinas_luar","no_draft","status","tipe_order_id","tipe_kategori_id","tanggal","t_bkk_id","no_bkk","total_amt","m_kary_id","m_supplier_id","catatan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_bon_dinas_luar_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_bon_dinas_luar_d', 't_bon_dinas_luar_id', 'id');
    }
    
    
    public function tipe_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_gen', 'tipe_order_id', 'id');
    }
    public function tipe_kategori() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_gen', 'tipe_kategori_id', 'id');
    }
    public function t_bkk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_bkk', 't_bkk_id', 'id');
    }
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_kary', 'm_kary_id', 'id');
    }
    public function m_supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'm_supplier_id', 'id');
    }
}
