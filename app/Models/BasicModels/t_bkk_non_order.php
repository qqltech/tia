<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_bkk_non_order extends Model
{   
    use ModelTrait;

    protected $table    = 't_bkk_non_order';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_bkk","status","tanggal","total_amt","no_reference","keterangan","m_perkiraan_id","tipe_bkk","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","m_business_unit_id"];

    public $columns     = ["id","no_draft","no_bkk","status","tanggal","total_amt","no_reference","keterangan","m_perkiraan_id","tipe_bkk","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","m_business_unit_id"];
    public $columnsFull = ["id:bigint","no_draft:string:20","no_bkk:string:20","status:string:20","tanggal:date","total_amt:decimal","no_reference:string:50","keterangan:text","m_perkiraan_id:integer","tipe_bkk:string:30","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","m_business_unit_id:integer"];
    public $rules       = [];
    public $joins       = ["m_coa.id=t_bkk_non_order.m_perkiraan_id","set.m_business_unit.id=t_bkk_non_order.m_business_unit_id"];
    public $details     = ["t_bkk_non_order_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tanggal","no_reference"];
    public $createable  = ["no_draft","no_bkk","status","tanggal","total_amt","no_reference","keterangan","m_perkiraan_id","tipe_bkk","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","m_business_unit_id"];
    public $updateable  = ["no_draft","no_bkk","status","tanggal","total_amt","no_reference","keterangan","m_perkiraan_id","tipe_bkk","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","m_business_unit_id"];
    public $searchable  = ["id","no_draft","no_bkk","status","tanggal","total_amt","no_reference","keterangan","m_perkiraan_id","tipe_bkk","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","m_business_unit_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_bkk_non_order_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_bkk_non_order_d', 't_bkk_non_order_id', 'id');
    }
    
    
    public function m_perkiraan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_perkiraan_id', 'id');
    }
    public function m_business_unit() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_business_unit', 'm_business_unit_id', 'id');
    }
}
