<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_tutup_buku extends Model
{   
    use ModelTrait;

    protected $table    = 't_tutup_buku';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_bu_id","m_menu_id","grup","periode","open_date","open_time","close_date","close_time","status","note","creator_id","last_editor_id","deletor_id","deleted_at"];

    public $columns     = ["id","m_bu_id","m_menu_id","grup","periode","open_date","open_time","close_date","close_time","status","note","creator_id","last_editor_id","created_at","updated_at","deletor_id","deleted_at"];
    public $columnsFull = ["id:bigint","m_bu_id:bigint","m_menu_id:bigint","grup:string:100","periode:string:100","open_date:date","open_time:datetime","close_date:date","close_time:datetime","status:string:20","note:text","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","deletor_id:bigint","deleted_at:datetime"];
    public $rules       = [];
    public $joins       = ["set.m_business_unit.id=t_tutup_buku.m_bu_id","set.m_menu.id=t_tutup_buku.m_menu_id","default_users.id=t_tutup_buku.creator_id","default_users.id=t_tutup_buku.last_editor_id"];
    public $details     = ["t_tutup_buku_d_coa"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_bu_id","grup","periode","open_date","open_time"];
    public $createable  = ["m_bu_id","m_menu_id","grup","periode","open_date","open_time","close_date","close_time","status","note","creator_id","last_editor_id","deletor_id","deleted_at"];
    public $updateable  = ["m_bu_id","m_menu_id","grup","periode","open_date","open_time","close_date","close_time","status","note","creator_id","last_editor_id","deletor_id","deleted_at"];
    public $searchable  = ["id","m_bu_id","m_menu_id","grup","periode","open_date","open_time","close_date","close_time","status","note","creator_id","last_editor_id","created_at","updated_at","deletor_id","deleted_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_tutup_buku_d_coa() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_tutup_buku_d_coa', 't_tutup_buku_id', 'id');
    }
    
    
    public function m_bu() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_business_unit', 'm_bu_id', 'id');
    }
    public function m_menu() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_menu', 'm_menu_id', 'id');
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
