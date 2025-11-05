<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_grup_head extends Model
{   
    use ModelTrait;

    protected $table    = 'm_grup_head';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_head","nama_grup","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","no_head","nama_grup","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_head:string:191","nama_grup:string:191","is_active:boolean","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["m_grup_head_d"];
    public $heirs       = ["t_premi","m_tarif_premi_bckp"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama_grup","is_active"];
    public $createable  = ["no_head","nama_grup","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["no_head","nama_grup","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","no_head","nama_grup","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_grup_head_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_grup_head_d', 'm_grup_head_id', 'id');
    }
    
    
}
