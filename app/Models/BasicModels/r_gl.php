<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class r_gl extends Model
{   
    use ModelTrait;

    protected $table    = 'r_gl';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["date","type","ref_table","ref_id","ref_no","m_cust_id","m_supp_id","desc","status","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","date","type","ref_table","ref_id","ref_no","m_cust_id","m_supp_id","desc","status","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","date:date","type:string:200","ref_table:text","ref_id:bigint","ref_no:text","m_cust_id:bigint","m_supp_id:bigint","desc:text","status:string:20","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_cust.id=r_gl.m_cust_id","m_supp.id=r_gl.m_supp_id"];
    public $details     = ["r_gl_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["date","type","ref_table","ref_id","ref_no","status"];
    public $createable  = ["date","type","ref_table","ref_id","ref_no","m_cust_id","m_supp_id","desc","status","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["date","type","ref_table","ref_id","ref_no","m_cust_id","m_supp_id","desc","status","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","date","type","ref_table","ref_id","ref_no","m_cust_id","m_supp_id","desc","status","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function r_gl_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\r_gl_d', 'r_gl_id', 'id');
    }
    
    
    public function m_cust() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_cust', 'm_cust_id', 'id');
    }
    public function m_supp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supp', 'm_supp_id', 'id');
    }
}
