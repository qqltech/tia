<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bon_dinas_luar_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_bon_dinas_luar_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_bon_dinas_luar_id","t_buku_order_id","keterangan","ukuran_container","sub_total","creator_id","last_editor_id","deleted_id","deleted_at"];

    public $columns     = ["id","t_bon_dinas_luar_id","t_buku_order_id","keterangan","ukuran_container","sub_total","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_bon_dinas_luar_id:bigint","t_buku_order_id:bigint","keterangan:text","ukuran_container:integer","sub_total:decimal","creator_id:bigint","last_editor_id:bigint","deleted_id:bigint","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_bon_dinas_luar.id=t_bon_dinas_luar_d.t_bon_dinas_luar_id","t_buku_order.id=t_bon_dinas_luar_d.t_buku_order_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_buku_order_id","ukuran_container","sub_total"];
    public $createable  = ["t_bon_dinas_luar_id","t_buku_order_id","keterangan","ukuran_container","sub_total","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $updateable  = ["t_bon_dinas_luar_id","t_buku_order_id","keterangan","ukuran_container","sub_total","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $searchable  = ["id","t_bon_dinas_luar_id","t_buku_order_id","keterangan","ukuran_container","sub_total","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_bon_dinas_luar() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_bon_dinas_luar', 't_bon_dinas_luar_id', 'id');
    }
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
}
