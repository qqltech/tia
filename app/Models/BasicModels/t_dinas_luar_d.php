<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_dinas_luar_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_dinas_luar_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_order","t_dinas_luar_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at"];

    public $columns     = ["id","no_order","t_dinas_luar_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_order:string:20","t_dinas_luar_id:integer","nominal:decimal","keterangan:text","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_dinas_luar.id=t_dinas_luar_d.t_dinas_luar_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nominal"];
    public $createable  = ["no_order","t_dinas_luar_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $updateable  = ["no_order","t_dinas_luar_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $searchable  = ["id","no_order","t_dinas_luar_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_dinas_luar() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_dinas_luar', 't_dinas_luar_id', 'id');
    }
}
