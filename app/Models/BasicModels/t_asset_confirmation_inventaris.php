<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_asset_confirmation_inventaris extends Model
{   
    use ModelTrait;

    protected $table    = 't_asset_confirmation_inventaris';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_confirm_asset_id","spesifikasi","merk_id","jenis_inventaris_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_confirm_asset_id","spesifikasi","merk_id","jenis_inventaris_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_confirm_asset_id:integer","spesifikasi:string:250","merk_id:bigint","jenis_inventaris_id:bigint","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_confirm_asset.id=t_asset_confirmation_inventaris.t_confirm_asset_id","set.m_general.id=t_asset_confirmation_inventaris.merk_id","set.m_general.id=t_asset_confirmation_inventaris.jenis_inventaris_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["spesifikasi","merk_id","jenis_inventaris_id"];
    public $createable  = ["t_confirm_asset_id","spesifikasi","merk_id","jenis_inventaris_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_confirm_asset_id","spesifikasi","merk_id","jenis_inventaris_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_confirm_asset_id","spesifikasi","merk_id","jenis_inventaris_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_confirm_asset() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_confirm_asset', 't_confirm_asset_id', 'id');
    }
    public function merk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'merk_id', 'id');
    }
    public function jenis_inventaris() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis_inventaris_id', 'id');
    }
}
