<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bkk_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_bkk_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_bkk_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","t_buku_order_id"];

    public $columns     = ["id","t_bkk_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","t_buku_order_id"];
    public $columnsFull = ["id:bigint","t_bkk_id:integer","m_coa_id:integer","nominal:decimal","keterangan:text","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","t_buku_order_id:integer"];
    public $rules       = [];
    public $joins       = ["t_bkk.id=t_bkk_d.t_bkk_id","m_coa.id=t_bkk_d.m_coa_id","t_buku_order.id=t_bkk_d.t_buku_order_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_coa_id","nominal"];
    public $createable  = ["t_bkk_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","t_buku_order_id"];
    public $updateable  = ["t_bkk_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","t_buku_order_id"];
    public $searchable  = ["id","t_bkk_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at","t_buku_order_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_bkk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_bkk', 't_bkk_id', 'id');
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
