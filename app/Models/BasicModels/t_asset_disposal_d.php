<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_asset_disposal_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_asset_disposal_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_asset_disposal_id","periode","penyusutan","nilai_buku","akumulasi","status","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","t_asset_disposal_id","periode","penyusutan","nilai_buku","akumulasi","status","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_asset_disposal_id:integer","periode:date","penyusutan:decimal","nilai_buku:decimal","akumulasi:decimal","status:boolean","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_asset_disposal.id=t_asset_disposal_d.t_asset_disposal_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["periode","penyusutan","nilai_buku","akumulasi","status"];
    public $createable  = ["t_asset_disposal_id","periode","penyusutan","nilai_buku","akumulasi","status","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["t_asset_disposal_id","periode","penyusutan","nilai_buku","akumulasi","status","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","t_asset_disposal_id","periode","penyusutan","nilai_buku","akumulasi","status","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_asset_disposal() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_asset_disposal', 't_asset_disposal_id', 'id');
    }
}
