<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_surat_jalan extends Model
{   
    use ModelTrait;

    protected $table    = 't_surat_jalan';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_surat_jalan","t_buku_order_id","tanggal","tanggal_berangkat","status","tipe_surat_jalan","pelabuhan","kapal","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","foto_berkas","lokasi_stuffing","depo","t_buku_order_d_npwp_id","jenis_kontainer","ukuran_kontainer","jenis_sj","is_edit_berkas","nw","gw","no_seal","tare","foto_surat_jalan"];

    public $columns     = ["id","no_draft","no_surat_jalan","t_buku_order_id","tanggal","tanggal_berangkat","status","tipe_surat_jalan","pelabuhan","kapal","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","foto_berkas","lokasi_stuffing","depo","t_buku_order_d_npwp_id","jenis_kontainer","ukuran_kontainer","jenis_sj","is_edit_berkas","nw","gw","no_seal","tare","foto_surat_jalan"];
    public $columnsFull = ["id:bigint","no_draft:string:40","no_surat_jalan:string:40","t_buku_order_id:integer","tanggal:date","tanggal_berangkat:date","status:string:191","tipe_surat_jalan:string:191","pelabuhan:string:191","kapal:string:191","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","foto_berkas:string:191","lokasi_stuffing:string:191","depo:string:191","t_buku_order_d_npwp_id:integer","jenis_kontainer:string:191","ukuran_kontainer:integer","jenis_sj:integer","is_edit_berkas:boolean","nw:string:191","gw:string:191","no_seal:string:191","tare:string:191","foto_surat_jalan:string:191"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_surat_jalan.t_buku_order_id","t_buku_order_d_npwp.id=t_surat_jalan.t_buku_order_d_npwp_id","set.m_general.id=t_surat_jalan.jenis_sj"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_buku_order_id","tanggal_berangkat"];
    public $createable  = ["no_draft","no_surat_jalan","t_buku_order_id","tanggal","tanggal_berangkat","status","tipe_surat_jalan","pelabuhan","kapal","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","foto_berkas","lokasi_stuffing","depo","t_buku_order_d_npwp_id","jenis_kontainer","ukuran_kontainer","jenis_sj","is_edit_berkas","nw","gw","no_seal","tare","foto_surat_jalan"];
    public $updateable  = ["no_draft","no_surat_jalan","t_buku_order_id","tanggal","tanggal_berangkat","status","tipe_surat_jalan","pelabuhan","kapal","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","foto_berkas","lokasi_stuffing","depo","t_buku_order_d_npwp_id","jenis_kontainer","ukuran_kontainer","jenis_sj","is_edit_berkas","nw","gw","no_seal","tare","foto_surat_jalan"];
    public $searchable  = ["id","no_draft","no_surat_jalan","t_buku_order_id","tanggal","tanggal_berangkat","status","tipe_surat_jalan","pelabuhan","kapal","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","foto_berkas","lokasi_stuffing","depo","t_buku_order_d_npwp_id","jenis_kontainer","ukuran_kontainer","jenis_sj","is_edit_berkas","nw","gw","no_seal","tare","foto_surat_jalan"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function t_buku_order_d_npwp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order_d_npwp', 't_buku_order_d_npwp_id', 'id');
    }
    public function jenis_sj() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis_sj', 'id');
    }
}
