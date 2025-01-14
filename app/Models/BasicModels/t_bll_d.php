<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bll_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_bll_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_bll_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at"];

    public $columns     = ["id","t_bll_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_bll_id:integer","m_coa_id:integer","nominal:decimal","keterangan:text","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_bll.id=t_bll_d.t_bll_id","m_coa.id=t_bll_d.m_coa_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_bll_id","m_coa_id","nominal"];
    public $createable  = ["t_bll_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $updateable  = ["t_bll_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $searchable  = ["id","t_bll_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_bll() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_bll', 't_bll_id', 'id');
    }
    public function m_coa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_coa_id', 'id');
    }
}
