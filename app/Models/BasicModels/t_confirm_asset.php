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
    protected $fillable = ["t_lpb_id","pic","tgl_asset","masa_manfaat","m_item_id","m_perkiraan_akun_penyusutan","harga_perolehan","kategori_id","m_perkiraan_asset_id","m_perkiraan_by_akun_penyusutan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_lpb_id","pic","tgl_asset","masa_manfaat","m_item_id","m_perkiraan_akun_penyusutan","harga_perolehan","kategori_id","m_perkiraan_asset_id","m_perkiraan_by_akun_penyusutan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_lpb_id:integer","pic:integer","tgl_asset:date","masa_manfaat:integer","m_item_id:integer","m_perkiraan_akun_penyusutan:integer","harga_perolehan:decimal","kategori_id:integer","m_perkiraan_asset_id:integer","m_perkiraan_by_akun_penyusutan:integer","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_lpb.id=t_confirm_asset.t_lpb_id","set.m_kary.id=t_confirm_asset.pic","m_item.id=t_confirm_asset.m_item_id","m_coa.id=t_confirm_asset.m_perkiraan_akun_penyusutan","set.m_general.id=t_confirm_asset.kategori_id","m_coa.id=t_confirm_asset.m_perkiraan_asset_id","m_coa.id=t_confirm_asset.m_perkiraan_by_akun_penyusutan"];
    public $details     = ["t_confirm_asset_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_lpb_id","pic","tgl_asset","masa_manfaat","m_item_id","m_perkiraan_akun_penyusutan","harga_perolehan","kategori_id","m_perkiraan_asset_id","m_perkiraan_by_akun_penyusutan"];
    public $createable  = ["t_lpb_id","pic","tgl_asset","masa_manfaat","m_item_id","m_perkiraan_akun_penyusutan","harga_perolehan","kategori_id","m_perkiraan_asset_id","m_perkiraan_by_akun_penyusutan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_lpb_id","pic","tgl_asset","masa_manfaat","m_item_id","m_perkiraan_akun_penyusutan","harga_perolehan","kategori_id","m_perkiraan_asset_id","m_perkiraan_by_akun_penyusutan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_lpb_id","pic","tgl_asset","masa_manfaat","m_item_id","m_perkiraan_akun_penyusutan","harga_perolehan","kategori_id","m_perkiraan_asset_id","m_perkiraan_by_akun_penyusutan","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_confirm_asset_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_confirm_asset_d', 't_confirm_asset_id', 'id');
    }
    
    
    public function t_lpb() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_lpb', 't_lpb_id', 'id');
    }
    public function pic() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_kary', 'pic', 'id');
    }
    public function m_item() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_item', 'm_item_id', 'id');
    }
    public function m_perkiraan_akun_penyusutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_perkiraan_akun_penyusutan', 'id');
    }
    public function kategori() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'kategori_id', 'id');
    }
    public function m_perkiraan_asset() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_perkiraan_asset_id', 'id');
    }
    public function m_perkiraan_by_akun_penyusutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_perkiraan_by_akun_penyusutan', 'id');
    }
}
