<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class r_gl_d extends Model
{   
    use ModelTrait;

    protected $table    = 'r_gl_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["r_gl_id","seq","m_coa_id","debet","credit","desc","closed_at","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","r_gl_id","seq","m_coa_id","debet","credit","desc","closed_at","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","r_gl_id:bigint","seq:integer","m_coa_id:bigint","debet:decimal","credit:decimal","desc:text","closed_at:datetime","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["r_gl.id=r_gl_d.r_gl_id","m_coa.id=r_gl_d.m_coa_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["seq","m_coa_id"];
    public $createable  = ["r_gl_id","seq","m_coa_id","debet","credit","desc","closed_at","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["r_gl_id","seq","m_coa_id","debet","credit","desc","closed_at","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","r_gl_id","seq","m_coa_id","debet","credit","desc","closed_at","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function r_gl() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\r_gl', 'r_gl_id', 'id');
    }
    public function m_coa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_coa_id', 'id');
    }
}
