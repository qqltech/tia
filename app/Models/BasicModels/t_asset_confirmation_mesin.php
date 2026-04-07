<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_asset_confirmation_mesin extends Model
{   
    use ModelTrait;

    protected $table    = 't_asset_confirmation_mesin';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_confirm_asset_id","no_mesin","tipe_mesin_id","dimensi","nomor_sertifikat","tahun_produksi","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_confirm_asset_id","no_mesin","tipe_mesin_id","dimensi","nomor_sertifikat","tahun_produksi","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_confirm_asset_id:integer","no_mesin:string:250","tipe_mesin_id:bigint","dimensi:string:250","nomor_sertifikat:string:250","tahun_produksi:string:10","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_confirm_asset.id=t_asset_confirmation_mesin.t_confirm_asset_id","set.m_general.id=t_asset_confirmation_mesin.tipe_mesin_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["no_mesin","tipe_mesin_id","dimensi","nomor_sertifikat","tahun_produksi"];
    public $createable  = ["t_confirm_asset_id","no_mesin","tipe_mesin_id","dimensi","nomor_sertifikat","tahun_produksi","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_confirm_asset_id","no_mesin","tipe_mesin_id","dimensi","nomor_sertifikat","tahun_produksi","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_confirm_asset_id","no_mesin","tipe_mesin_id","dimensi","nomor_sertifikat","tahun_produksi","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_confirm_asset() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_confirm_asset', 't_confirm_asset_id', 'id');
    }
    public function tipe_mesin() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_mesin_id', 'id');
    }
}
