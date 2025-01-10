<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_generate_no_aju_d extends Model
{   
    use ModelTrait;

    protected $table    = 'm_generate_no_aju_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_generate_no_aju_id","no_aju","is_active","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","m_generate_no_aju_id","no_aju","is_active","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_generate_no_aju_id:bigint","no_aju:string:100","is_active:boolean","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_generate_no_aju.id=m_generate_no_aju_d.m_generate_no_aju_id"];
    public $details     = [];
    public $heirs       = ["t_ppjk"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["is_active"];
    public $createable  = ["m_generate_no_aju_id","no_aju","is_active","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["m_generate_no_aju_id","no_aju","is_active","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","m_generate_no_aju_id","no_aju","is_active","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_generate_no_aju() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_generate_no_aju', 'm_generate_no_aju_id', 'id');
    }
}
