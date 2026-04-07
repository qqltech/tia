<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_buku_order_closing extends Model
{   
    use ModelTrait;

    protected $table    = 't_buku_order_closing';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_bu_id","grup","periode","periode_tahun","alasan_closing","open_date","open_time","close_date","close_time","is_closed","status","note","creator_id","last_editor_id","delete_id","deleted_at"];

    public $columns     = ["id","m_bu_id","grup","periode","periode_tahun","alasan_closing","open_date","open_time","close_date","close_time","is_closed","status","note","creator_id","last_editor_id","delete_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_bu_id:bigint","grup:string:191","periode:string:191","periode_tahun:integer","alasan_closing:string:191","open_date:date","open_time:datetime","close_date:date","close_time:datetime","is_closed:boolean","status:string:191","note:text","creator_id:integer","last_editor_id:integer","delete_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["set.m_business_unit.id=t_buku_order_closing.m_bu_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_bu_id","grup","periode","periode_tahun","alasan_closing","open_date","open_time"];
    public $createable  = ["m_bu_id","grup","periode","periode_tahun","alasan_closing","open_date","open_time","close_date","close_time","is_closed","status","note","creator_id","last_editor_id","delete_id","deleted_at"];
    public $updateable  = ["m_bu_id","grup","periode","periode_tahun","alasan_closing","open_date","open_time","close_date","close_time","is_closed","status","note","creator_id","last_editor_id","delete_id","deleted_at"];
    public $searchable  = ["id","m_bu_id","grup","periode","periode_tahun","alasan_closing","open_date","open_time","close_date","close_time","is_closed","status","note","creator_id","last_editor_id","delete_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_bu() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_business_unit', 'm_bu_id', 'id');
    }
}
