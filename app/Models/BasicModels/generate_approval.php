<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class generate_approval extends Model
{   
    use ModelTrait;

    protected $table    = 'set.generate_approval';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["nomor","m_approval_id","trx_id","trx_name","form_name","trx_table","trx_nomor","trx_date","trx_object","trx_creator_id","status","last_approve_det_id","creator_id","last_editor_id","created_at","updated_at","last_approve_id"];

    public $columns     = ["id","nomor","m_approval_id","trx_id","trx_name","form_name","trx_table","trx_nomor","trx_date","trx_object","trx_creator_id","status","last_approve_det_id","creator_id","last_editor_id","created_at","updated_at","last_approve_id"];
    public $columnsFull = ["id:bigint","nomor:string:191","m_approval_id:bigint","trx_id:bigint","trx_name:string:191","form_name:string:191","trx_table:string:191","trx_nomor:string:191","trx_date:date","trx_object:string:191","trx_creator_id:bigint","status:string:191","last_approve_det_id:bigint","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","last_approve_id:bigint"];
    public $rules       = [];
    public $joins       = ["set.m_approval.id=set.generate_approval.m_approval_id","set.generate_approval_det.id=set.generate_approval.last_approve_det_id","default_users.id=set.generate_approval.creator_id","default_users.id=set.generate_approval.last_editor_id","default_users.id=set.generate_approval.last_approve_id"];
    public $details     = [];
    public $heirs       = ["set.generate_approval_log","set.generate_approval_det"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["trx_id","trx_name","trx_table","trx_date"];
    public $createable  = ["nomor","m_approval_id","trx_id","trx_name","form_name","trx_table","trx_nomor","trx_date","trx_object","trx_creator_id","status","last_approve_det_id","creator_id","last_editor_id","created_at","updated_at","last_approve_id"];
    public $updateable  = ["nomor","m_approval_id","trx_id","trx_name","form_name","trx_table","trx_nomor","trx_date","trx_object","trx_creator_id","status","last_approve_det_id","creator_id","last_editor_id","created_at","updated_at","last_approve_id"];
    public $searchable  = ["nomor","m_approval_id","trx_id","trx_name","form_name","trx_table","trx_nomor","trx_date","trx_object","trx_creator_id","status","last_approve_det_id","creator_id","last_editor_id","created_at","updated_at","last_approve_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_approval() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_approval', 'm_approval_id', 'id');
    }
    public function last_approve_det() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.generate_approval_det', 'last_approve_det_id', 'id');
    }
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
    public function last_approve() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_approve_id', 'id');
    }
}
