<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bkk_non_order_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_bkk_non_order_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_bkk_non_order_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_bkk_non_order_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_bkk_non_order_id:integer","m_coa_id:integer","nominal:decimal","keterangan:string:250","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_bkk_non_order.id=t_bkk_non_order_d.t_bkk_non_order_id","m_coa.id=t_bkk_non_order_d.m_coa_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_coa_id","nominal","keterangan"];
    public $createable  = ["t_bkk_non_order_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_bkk_non_order_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_bkk_non_order_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_bkk_non_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_bkk_non_order', 't_bkk_non_order_id', 'id');
    }
    public function m_coa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_coa_id', 'id');
    }
}
