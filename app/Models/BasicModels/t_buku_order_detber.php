<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_buku_order_detber extends Model
{   
    use ModelTrait;

    protected $table    = 't_buku_order_detber';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_buku_order_id","nama_berkas","foto_berkas","tgl","catatan","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","t_buku_order_id","nama_berkas","foto_berkas","tgl","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_buku_order_id:integer","nama_berkas:string:191","foto_berkas:string:191","tgl:date","catatan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_buku_order_detber.t_buku_order_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["t_buku_order_id","nama_berkas","foto_berkas","tgl","catatan","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["t_buku_order_id","nama_berkas","foto_berkas","tgl","catatan","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","t_buku_order_id","nama_berkas","foto_berkas","tgl","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
}
