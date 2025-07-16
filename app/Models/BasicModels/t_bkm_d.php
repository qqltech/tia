<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bkm_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_bkm_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_bkm_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","t_buku_order_id"];

    public $columns     = ["id","t_bkm_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","t_buku_order_id"];
    public $columnsFull = ["id:bigint","t_bkm_id:integer","m_coa_id:integer","nominal:decimal","keterangan:text","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","t_buku_order_id:integer"];
    public $rules       = [];
    public $joins       = ["t_bkm.id=t_bkm_d.t_bkm_id","m_coa.id=t_bkm_d.m_coa_id","t_buku_order.id=t_bkm_d.t_buku_order_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_coa_id","nominal","t_buku_order_id"];
    public $createable  = ["t_bkm_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","t_buku_order_id"];
    public $updateable  = ["t_bkm_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","t_buku_order_id"];
    public $searchable  = ["id","t_bkm_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","t_buku_order_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_bkm() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_bkm', 't_bkm_id', 'id');
    }
    public function m_coa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_coa_id', 'id');
    }
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
}
