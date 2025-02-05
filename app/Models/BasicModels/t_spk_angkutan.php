<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_spk_angkutan extends Model
{   
    use ModelTrait;

    protected $table    = 't_spk_angkutan';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_spk","tipe_spk","status","t_buku_order_1_id","t_detail_npwp_container_1_id","isi_container_1","no_container_1","t_buku_order_2_id","t_detail_npwp_container_2_id","isi_container_2","no_container_2","tanggal_spk","supir","sektor1","head","chasis","sangu","tanggal_out","tanggal_in","catatan","creator_id","last_editor_id","delete_id","delete_at","total_sangu","depo","m_supplier_id","trip_id","sektor2","is_con_edit","chasis2","nama_customer","nama_customer_2","dari","ke","waktu_out","waktu_in"];

    public $columns     = ["id","no_spk","tipe_spk","status","t_buku_order_1_id","t_detail_npwp_container_1_id","isi_container_1","no_container_1","t_buku_order_2_id","t_detail_npwp_container_2_id","isi_container_2","no_container_2","tanggal_spk","supir","sektor1","head","chasis","sangu","tanggal_out","tanggal_in","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","total_sangu","depo","m_supplier_id","trip_id","sektor2","is_con_edit","chasis2","nama_customer","nama_customer_2","dari","ke","waktu_out","waktu_in"];
    public $columnsFull = ["id:bigint","no_spk:string:50","tipe_spk:integer","status:string:191","t_buku_order_1_id:integer","t_detail_npwp_container_1_id:integer","isi_container_1:integer","no_container_1:string:191","t_buku_order_2_id:integer","t_detail_npwp_container_2_id:integer","isi_container_2:integer","no_container_2:string:191","tanggal_spk:date","supir:integer","sektor1:integer","head:integer","chasis:integer","sangu:decimal","tanggal_out:date","tanggal_in:date","catatan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","total_sangu:decimal","depo:integer","m_supplier_id:integer","trip_id:integer","sektor2:integer","is_con_edit:boolean","chasis2:integer","nama_customer:bigint","nama_customer_2:bigint","dari:string:100","ke:string:100","waktu_out:bigint","waktu_in:bigint"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=t_spk_angkutan.tipe_spk","t_buku_order.id=t_spk_angkutan.t_buku_order_1_id","t_buku_order_d_npwp.id=t_spk_angkutan.t_detail_npwp_container_1_id","set.m_general.id=t_spk_angkutan.isi_container_1","t_buku_order.id=t_spk_angkutan.t_buku_order_2_id","t_buku_order_d_npwp.id=t_spk_angkutan.t_detail_npwp_container_2_id","set.m_general.id=t_spk_angkutan.isi_container_2","set.m_kary.id=t_spk_angkutan.supir","set.m_general.id=t_spk_angkutan.sektor1","set.m_general.id=t_spk_angkutan.head","set.m_general.id=t_spk_angkutan.chasis","set.m_general.id=t_spk_angkutan.depo","m_supplier.id=t_spk_angkutan.m_supplier_id","set.m_general.id=t_spk_angkutan.trip_id","set.m_general.id=t_spk_angkutan.sektor2","set.m_general.id=t_spk_angkutan.chasis2","m_customer.id=t_spk_angkutan.nama_customer","m_customer.id=t_spk_angkutan.nama_customer_2","set.m_general.id=t_spk_angkutan.waktu_out","set.m_general.id=t_spk_angkutan.waktu_in"];
    public $details     = ["t_spk_bon_detail"];
    public $heirs       = ["t_angkutan_d","t_ganti_solar","t_premi"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["no_spk","tipe_spk","status","t_buku_order_1_id","t_detail_npwp_container_1_id","isi_container_1","no_container_1","t_buku_order_2_id","t_detail_npwp_container_2_id","isi_container_2","no_container_2","tanggal_spk","supir","sektor1","head","chasis","sangu","tanggal_out","tanggal_in","catatan","creator_id","last_editor_id","delete_id","delete_at","total_sangu","depo","m_supplier_id","trip_id","sektor2","is_con_edit","chasis2","nama_customer","nama_customer_2","dari","ke","waktu_out","waktu_in"];
    public $updateable  = ["no_spk","tipe_spk","status","t_buku_order_1_id","t_detail_npwp_container_1_id","isi_container_1","no_container_1","t_buku_order_2_id","t_detail_npwp_container_2_id","isi_container_2","no_container_2","tanggal_spk","supir","sektor1","head","chasis","sangu","tanggal_out","tanggal_in","catatan","creator_id","last_editor_id","delete_id","delete_at","total_sangu","depo","m_supplier_id","trip_id","sektor2","is_con_edit","chasis2","nama_customer","nama_customer_2","dari","ke","waktu_out","waktu_in"];
    public $searchable  = ["id","no_spk","tipe_spk","status","t_buku_order_1_id","t_detail_npwp_container_1_id","isi_container_1","no_container_1","t_buku_order_2_id","t_detail_npwp_container_2_id","isi_container_2","no_container_2","tanggal_spk","supir","sektor1","head","chasis","sangu","tanggal_out","tanggal_in","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","total_sangu","depo","m_supplier_id","trip_id","sektor2","is_con_edit","chasis2","nama_customer","nama_customer_2","dari","ke","waktu_out","waktu_in"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_spk_bon_detail() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_spk_bon_detail', 't_spk_angkutan_id', 'id');
    }
    
    
    public function tipe_spk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_spk', 'id');
    }
    public function t_buku_order_1() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_1_id', 'id');
    }
    public function t_detail_npwp_container_1() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order_d_npwp', 't_detail_npwp_container_1_id', 'id');
    }
    public function isi_container_1() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'isi_container_1', 'id');
    }
    public function t_buku_order_2() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_2_id', 'id');
    }
    public function t_detail_npwp_container_2() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order_d_npwp', 't_detail_npwp_container_2_id', 'id');
    }
    public function isi_container_2() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'isi_container_2', 'id');
    }
    public function supir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_kary', 'supir', 'id');
    }
    public function sektor1() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'sektor1', 'id');
    }
    public function head() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'head', 'id');
    }
    public function chasis() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'chasis', 'id');
    }
    public function depo() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'depo', 'id');
    }
    public function m_supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'm_supplier_id', 'id');
    }
    public function trip() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'trip_id', 'id');
    }
    public function sektor2() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'sektor2', 'id');
    }
    public function chasis2() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'chasis2', 'id');
    }
    public function nama_customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'nama_customer', 'id');
    }
    public function nama_customer_2() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'nama_customer_2', 'id');
    }
    public function waktu_out() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'waktu_out', 'id');
    }
    public function waktu_in() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'waktu_in', 'id');
    }
}
