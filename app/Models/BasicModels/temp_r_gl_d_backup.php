<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class temp_r_gl_d_backup extends Model
{   
    use ModelTrait;

    protected $table    = 'temp_r_gl_d_backup';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["r_gl_id","seq","m_coa_id","debet","credit","desc","closed_at","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];

    public $columns     = ["id","r_gl_id","seq","m_coa_id","debet","credit","desc","closed_at","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","r_gl_id:bigint","seq:integer","m_coa_id:bigint","debet:decimal","credit:decimal","desc:text","closed_at:datetime","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["r_gl_id","seq","m_coa_id","debet","credit","desc","closed_at","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $updateable  = ["r_gl_id","seq","m_coa_id","debet","credit","desc","closed_at","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $searchable  = ["r_gl_id","seq","m_coa_id","debet","credit","desc","closed_at","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
