<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class r_stock extends Model
{   
    use ModelTrait;

    protected $table    = 'r_stock';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["date","type","ref_table","ref_id","ref_no","note","creator_id","last_editor_id"];

    public $columns     = ["id","date","type","ref_table","ref_id","ref_no","note","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","date:date","type:string:100","ref_table:string:100","ref_id:bigint","ref_no:string:100","note:text","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["r_stock_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["date","type","ref_table","ref_id","ref_no"];
    public $createable  = ["date","type","ref_table","ref_id","ref_no","note","creator_id","last_editor_id"];
    public $updateable  = ["date","type","ref_table","ref_id","ref_no","note","creator_id","last_editor_id"];
    public $searchable  = ["id","date","type","ref_table","ref_id","ref_no","note","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function r_stock_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\r_stock_d', 'r_stock_id', 'id');
    }
    
    
}
