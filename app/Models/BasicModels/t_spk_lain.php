<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_spk_lain extends Model
{   
    use ModelTrait;

    protected $table    = 't_spk_lain';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","status","no_spk","tanggal","genzet","no_container","setting_temperatur","keluar_lokasi_tanggal","keluar_lokasi_jam","keluar_lokasi_temperatur","tiba_lokasi_tanggal","tiba_lokasi_jam","tiba_lokasi_temperatur","lokasi_stuffing","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","t_buku_order_id","m_customer_id","ukuran"];

    public $columns     = ["id","no_draft","status","no_spk","tanggal","genzet","no_container","setting_temperatur","keluar_lokasi_tanggal","keluar_lokasi_jam","keluar_lokasi_temperatur","tiba_lokasi_tanggal","tiba_lokasi_jam","tiba_lokasi_temperatur","lokasi_stuffing","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","t_buku_order_id","m_customer_id","ukuran"];
    public $columnsFull = ["id:bigint","no_draft:string:191","status:string:191","no_spk:string:191","tanggal:date","genzet:integer","no_container:integer","setting_temperatur:string:100","keluar_lokasi_tanggal:date","keluar_lokasi_jam:time","keluar_lokasi_temperatur:string:100","tiba_lokasi_tanggal:date","tiba_lokasi_jam:time","tiba_lokasi_temperatur:string:100","lokasi_stuffing:text","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","t_buku_order_id:integer","m_customer_id:integer","ukuran:integer"];
    public $rules       = [];
    public $joins       = ["m_supplier.id=t_spk_lain.genzet","t_buku_order_d_npwp.id=t_spk_lain.no_container","t_buku_order.id=t_spk_lain.t_buku_order_id","m_customer.id=t_spk_lain.m_customer_id","set.m_general.id=t_spk_lain.ukuran"];
    public $details     = ["t_spk_lain_d"];
    public $heirs       = ["t_bon_spk_lain"];
    public $detailsChild= [];
    public $detailsHeirs= ["t_bon_spk_lain_d"];
    public $unique      = [];
    public $required    = ["genzet","no_container","setting_temperatur"];
    public $createable  = ["no_draft","status","no_spk","tanggal","genzet","no_container","setting_temperatur","keluar_lokasi_tanggal","keluar_lokasi_jam","keluar_lokasi_temperatur","tiba_lokasi_tanggal","tiba_lokasi_jam","tiba_lokasi_temperatur","lokasi_stuffing","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","t_buku_order_id","m_customer_id","ukuran"];
    public $updateable  = ["no_draft","status","no_spk","tanggal","genzet","no_container","setting_temperatur","keluar_lokasi_tanggal","keluar_lokasi_jam","keluar_lokasi_temperatur","tiba_lokasi_tanggal","tiba_lokasi_jam","tiba_lokasi_temperatur","lokasi_stuffing","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","t_buku_order_id","m_customer_id","ukuran"];
    public $searchable  = ["id","no_draft","status","no_spk","tanggal","genzet","no_container","setting_temperatur","keluar_lokasi_tanggal","keluar_lokasi_jam","keluar_lokasi_temperatur","tiba_lokasi_tanggal","tiba_lokasi_jam","tiba_lokasi_temperatur","lokasi_stuffing","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","t_buku_order_id","m_customer_id","ukuran"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_spk_lain_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_spk_lain_d', 't_spk_lain_id', 'id');
    }
    
    
    public function genzet() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'genzet', 'id');
    }
    public function no_container() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order_d_npwp', 'no_container', 'id');
    }
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function m_customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'm_customer_id', 'id');
    }
    public function ukuran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'ukuran', 'id');
    }
}
