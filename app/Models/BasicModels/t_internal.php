<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_internal extends Model
{   
    use ModelTrait;

    protected $table    = 't_internal';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_pemakaian","status","date","catatan"];

    public $columns     = ["id","no_pemakaian","status","date","catatan","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_pemakaian:string:100","status:boolean","date:date","catatan:string:100","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["t_internal_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["no_pemakaian","status","date","catatan"];
    public $updateable  = ["no_pemakaian","status","date","catatan"];
    public $searchable  = ["id","no_pemakaian","status","date","catatan","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_internal_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_internal_d', 't_internal_id', 'id');
    }
    
    
}
