<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_asset_confirmation_kendaraan extends Model
{   
    use ModelTrait;

    protected $table    = 't_asset_confirmation_kendaraan';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_confirm_asset_id","jenis_kendaraan_id","no_mesin","no_rangka","nopol","no_bpkb","tahun_produksi","merk_id","jumlah_roda","bahan_bakar_id","jumlah_cylinder","warna_id","no_faktur","tanggal_faktur","nama_pemilik","no_urut_kendaraan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_confirm_asset_id","jenis_kendaraan_id","no_mesin","no_rangka","nopol","no_bpkb","tahun_produksi","merk_id","jumlah_roda","bahan_bakar_id","jumlah_cylinder","warna_id","no_faktur","tanggal_faktur","nama_pemilik","no_urut_kendaraan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_confirm_asset_id:integer","jenis_kendaraan_id:bigint","no_mesin:string:250","no_rangka:string:250","nopol:string:250","no_bpkb:string:250","tahun_produksi:string:10","merk_id:bigint","jumlah_roda:integer","bahan_bakar_id:bigint","jumlah_cylinder:integer","warna_id:bigint","no_faktur:string:250","tanggal_faktur:date","nama_pemilik:string:250","no_urut_kendaraan:string:191","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_confirm_asset.id=t_asset_confirmation_kendaraan.t_confirm_asset_id","set.m_general.id=t_asset_confirmation_kendaraan.jenis_kendaraan_id","set.m_general.id=t_asset_confirmation_kendaraan.merk_id","set.m_general.id=t_asset_confirmation_kendaraan.bahan_bakar_id","set.m_general.id=t_asset_confirmation_kendaraan.warna_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["jenis_kendaraan_id","no_mesin","no_rangka","nopol","no_bpkb","tahun_produksi","merk_id","jumlah_roda","bahan_bakar_id","jumlah_cylinder","warna_id","no_faktur","tanggal_faktur","nama_pemilik"];
    public $createable  = ["t_confirm_asset_id","jenis_kendaraan_id","no_mesin","no_rangka","nopol","no_bpkb","tahun_produksi","merk_id","jumlah_roda","bahan_bakar_id","jumlah_cylinder","warna_id","no_faktur","tanggal_faktur","nama_pemilik","no_urut_kendaraan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_confirm_asset_id","jenis_kendaraan_id","no_mesin","no_rangka","nopol","no_bpkb","tahun_produksi","merk_id","jumlah_roda","bahan_bakar_id","jumlah_cylinder","warna_id","no_faktur","tanggal_faktur","nama_pemilik","no_urut_kendaraan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_confirm_asset_id","jenis_kendaraan_id","no_mesin","no_rangka","nopol","no_bpkb","tahun_produksi","merk_id","jumlah_roda","bahan_bakar_id","jumlah_cylinder","warna_id","no_faktur","tanggal_faktur","nama_pemilik","no_urut_kendaraan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_confirm_asset() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_confirm_asset', 't_confirm_asset_id', 'id');
    }
    public function jenis_kendaraan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis_kendaraan_id', 'id');
    }
    public function merk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'merk_id', 'id');
    }
    public function bahan_bakar() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'bahan_bakar_id', 'id');
    }
    public function warna() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'warna_id', 'id');
    }
}
