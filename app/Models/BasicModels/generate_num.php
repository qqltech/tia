<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class generate_num extends Model
{   
    use ModelTrait;

    protected $table    = 'generate_num';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nama","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","nama","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nama:string:191","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["default_users.id=generate_num.creator_id","default_users.id=generate_num.last_editor_id"];
    public $details     = ["generate_num_det"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama"];
    public $createable  = ["nama","is_active","creator_id","last_editor_id"];
    public $updateable  = ["nama","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","nama","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function generate_num_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\generate_num_det', 'generate_num_id', 'id');
    }
    
    
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
}
