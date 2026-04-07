<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_asset_confirmation_chasis extends Model
{   
    use ModelTrait;

    protected $table    = 't_asset_confirmation_chasis';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_confirm_asset_id","dimensi","jumlah_ban","warna_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_confirm_asset_id","dimensi","jumlah_ban","warna_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_confirm_asset_id:integer","dimensi:string:100","jumlah_ban:integer","warna_id:bigint","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_confirm_asset.id=t_asset_confirmation_chasis.t_confirm_asset_id","set.m_general.id=t_asset_confirmation_chasis.warna_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["dimensi","jumlah_ban","warna_id"];
    public $createable  = ["t_confirm_asset_id","dimensi","jumlah_ban","warna_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_confirm_asset_id","dimensi","jumlah_ban","warna_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_confirm_asset_id","dimensi","jumlah_ban","warna_id","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_confirm_asset() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_confirm_asset', 't_confirm_asset_id', 'id');
    }
    public function warna() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'warna_id', 'id');
    }
}
