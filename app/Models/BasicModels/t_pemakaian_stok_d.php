<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pemakaian_stok_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_pemakaian_stok_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_pemakaian_stok_id","m_item_id","usage","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_pemakaian_stok_id","m_item_id","usage","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_pemakaian_stok_id:integer","m_item_id:integer","usage:integer","catatan:string:250","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_pemakaian_stok.id=t_pemakaian_stok_d.t_pemakaian_stok_id","m_item.id=t_pemakaian_stok_d.m_item_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_item_id","usage","catatan"];
    public $createable  = ["t_pemakaian_stok_id","m_item_id","usage","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_pemakaian_stok_id","m_item_id","usage","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_pemakaian_stok_id","m_item_id","usage","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_pemakaian_stok() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_pemakaian_stok', 't_pemakaian_stok_id', 'id');
    }
    public function m_item() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_item', 'm_item_id', 'id');
    }
}
