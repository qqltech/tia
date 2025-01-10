<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_buku_penyesuaian_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_buku_penyesuaian_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_buku_penyesuaian_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","t_buku_penyesuaian_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_buku_penyesuaian_id:bigint","m_coa_id:bigint","nominal:decimal","keterangan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_buku_penyesuaian.id=t_buku_penyesuaian_d.t_buku_penyesuaian_id","m_coa.id=t_buku_penyesuaian_d.m_coa_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nominal"];
    public $createable  = ["t_buku_penyesuaian_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["t_buku_penyesuaian_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","t_buku_penyesuaian_id","m_coa_id","nominal","keterangan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_buku_penyesuaian() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_penyesuaian', 't_buku_penyesuaian_id', 'id');
    }
    public function m_coa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_coa_id', 'id');
    }
}
