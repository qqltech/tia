<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class temp_r_gl_backup extends Model
{   
    use ModelTrait;

    protected $table    = 'temp_r_gl_backup';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["date","type","ref_table","ref_id","ref_no","m_cust_id","m_supp_id","desc","status","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","m_business_unit_id","no_reference"];

    public $columns     = ["id","date","type","ref_table","ref_id","ref_no","m_cust_id","m_supp_id","desc","status","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","m_business_unit_id","no_reference"];
    public $columnsFull = ["id:bigint","date:date","type:string:200","ref_table:text","ref_id:bigint","ref_no:text","m_cust_id:bigint","m_supp_id:bigint","desc:text","status:string:20","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","m_business_unit_id:integer","no_reference:string:100"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["date","type","ref_table","ref_id","ref_no","m_cust_id","m_supp_id","desc","status","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","m_business_unit_id","no_reference"];
    public $updateable  = ["date","type","ref_table","ref_id","ref_no","m_cust_id","m_supp_id","desc","status","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","m_business_unit_id","no_reference"];
    public $searchable  = ["date","type","ref_table","ref_id","ref_no","m_cust_id","m_supp_id","desc","status","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","m_business_unit_id","no_reference"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
