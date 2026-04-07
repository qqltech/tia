<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_confirm_asset extends Model
{   
    use ModelTrait;

    protected $table    = 't_confirm_asset';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_lpb_id","pic","masa_manfaat","catatan","creator_id","last_editor_id","deleted_at","harga_perolehan","no_draft","kode_asset","no_asset_confirmation","tipe_asset","t_lpb_d_id","tanggal","tanggal_pakai","nama_asset","nilai_minimal","coa_penyusutan","coa_by_akun_penyusutan","coa_asset","status"];

    public $columns     = ["id","t_lpb_id","pic","masa_manfaat","catatan","creator_id","last_editor_id","deleted_at","created_at","updated_at","harga_perolehan","no_draft","kode_asset","no_asset_confirmation","tipe_asset","t_lpb_d_id","tanggal","tanggal_pakai","nama_asset","nilai_minimal","coa_penyusutan","coa_by_akun_penyusutan","coa_asset","status"];
    public $columnsFull = ["id:bigint","t_lpb_id:integer","pic:integer","masa_manfaat:integer","catatan:text","creator_id:integer","last_editor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","harga_perolehan:decimal","no_draft:string:20","kode_asset:string:100","no_asset_confirmation:string:250","tipe_asset:string:50","t_lpb_d_id:bigint","tanggal:date","tanggal_pakai:date","nama_asset:string:500","nilai_minimal:decimal","coa_penyusutan:bigint","coa_by_akun_penyusutan:bigint","coa_asset:bigint","status:string:20"];
    public $rules       = [];
    public $joins       = ["t_lpb.id=t_confirm_asset.t_lpb_id","set.m_kary.id=t_confirm_asset.pic","t_lpb_d.id=t_confirm_asset.t_lpb_d_id","m_coa.id=t_confirm_asset.coa_penyusutan","m_coa.id=t_confirm_asset.coa_by_akun_penyusutan","m_coa.id=t_confirm_asset.coa_asset"];
    public $details     = ["t_asset_confirmation_inventaris","t_confirm_asset_d","t_asset_confirmation_chasis","t_asset_confirmation_kendaraan","t_asset_confirmation_mesin"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_lpb_id","pic","masa_manfaat","harga_perolehan","nilai_minimal"];
    public $createable  = ["t_lpb_id","pic","masa_manfaat","catatan","creator_id","last_editor_id","deleted_at","harga_perolehan","no_draft","kode_asset","no_asset_confirmation","tipe_asset","t_lpb_d_id","tanggal","tanggal_pakai","nama_asset","nilai_minimal","coa_penyusutan","coa_by_akun_penyusutan","coa_asset","status"];
    public $updateable  = ["t_lpb_id","pic","masa_manfaat","catatan","creator_id","last_editor_id","deleted_at","harga_perolehan","no_draft","kode_asset","no_asset_confirmation","tipe_asset","t_lpb_d_id","tanggal","tanggal_pakai","nama_asset","nilai_minimal","coa_penyusutan","coa_by_akun_penyusutan","coa_asset","status"];
    public $searchable  = ["id","t_lpb_id","pic","masa_manfaat","catatan","creator_id","last_editor_id","deleted_at","created_at","updated_at","harga_perolehan","no_draft","kode_asset","no_asset_confirmation","tipe_asset","t_lpb_d_id","tanggal","tanggal_pakai","nama_asset","nilai_minimal","coa_penyusutan","coa_by_akun_penyusutan","coa_asset","status"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_asset_confirmation_inventaris() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_asset_confirmation_inventaris', 't_confirm_asset_id', 'id');
    }
    public function t_confirm_asset_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_confirm_asset_d', 't_confirm_asset_id', 'id');
    }
    public function t_asset_confirmation_chasis() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_asset_confirmation_chasis', 't_confirm_asset_id', 'id');
    }
    public function t_asset_confirmation_kendaraan() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_asset_confirmation_kendaraan', 't_confirm_asset_id', 'id');
    }
    public function t_asset_confirmation_mesin() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_asset_confirmation_mesin', 't_confirm_asset_id', 'id');
    }
    
    
    public function t_lpb() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_lpb', 't_lpb_id', 'id');
    }
    public function pic() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_kary', 'pic', 'id');
    }
    public function t_lpb_d() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_lpb_d', 't_lpb_d_id', 'id');
    }
    public function coa_penyusutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'coa_penyusutan', 'id');
    }
    public function coa_by_akun_penyusutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'coa_by_akun_penyusutan', 'id');
    }
    public function coa_asset() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'coa_asset', 'id');
    }
}
