<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_komisi_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_komisi_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_komisi_id","t_buku_order_id","creator_id","last_editor_id","total"];

    public $columns     = ["id","t_komisi_id","t_buku_order_id","creator_id","last_editor_id","created_at","updated_at","total"];
    public $columnsFull = ["id:bigint","t_komisi_id:integer","t_buku_order_id:integer","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime","total:decimal"];
    public $rules       = [];
    public $joins       = ["t_komisi.id=t_komisi_d.t_komisi_id","t_buku_order.id=t_komisi_d.t_buku_order_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_komisi_id","t_buku_order_id"];
    public $createable  = ["t_komisi_id","t_buku_order_id","creator_id","last_editor_id","total"];
    public $updateable  = ["t_komisi_id","t_buku_order_id","creator_id","last_editor_id","total"];
    public $searchable  = ["id","t_komisi_id","t_buku_order_id","creator_id","last_editor_id","created_at","updated_at","total"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_komisi() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_komisi', 't_komisi_id', 'id');
    }
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
}
