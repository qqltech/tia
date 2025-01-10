<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_nota_rampung extends Model
{   
    use ModelTrait;

    protected $table    = 't_nota_rampung';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_nota_rampung","t_buku_order_id","status","tanggal","customer","pelabuhan","container1","container2","tipe1","tipe2","vgm","lolo_non_sp","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","foto_scn","no_stack","tgl_stack","no_eir","tgl_eir","grand_total"];

    public $columns     = ["id","no_draft","no_nota_rampung","t_buku_order_id","status","tanggal","customer","pelabuhan","container1","container2","tipe1","tipe2","vgm","lolo_non_sp","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","foto_scn","no_stack","tgl_stack","no_eir","tgl_eir","grand_total"];
    public $columnsFull = ["id:bigint","no_draft:string:20","no_nota_rampung:string:20","t_buku_order_id:integer","status:string:10","tanggal:date","customer:string:191","pelabuhan:string:191","container1:integer","container2:integer","tipe1:integer","tipe2:integer","vgm:decimal","lolo_non_sp:decimal","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","foto_scn:string:191","no_stack:string:50","tgl_stack:date","no_eir:string:50","tgl_eir:date","grand_total:decimal"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_nota_rampung.t_buku_order_id","set.m_general.id=t_nota_rampung.container1","set.m_general.id=t_nota_rampung.container2","set.m_general.id=t_nota_rampung.tipe1","set.m_general.id=t_nota_rampung.tipe2"];
    public $details     = ["t_nota_rampung_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_buku_order_id","grand_total"];
    public $createable  = ["no_draft","no_nota_rampung","t_buku_order_id","status","tanggal","customer","pelabuhan","container1","container2","tipe1","tipe2","vgm","lolo_non_sp","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","foto_scn","no_stack","tgl_stack","no_eir","tgl_eir","grand_total"];
    public $updateable  = ["no_draft","no_nota_rampung","t_buku_order_id","status","tanggal","customer","pelabuhan","container1","container2","tipe1","tipe2","vgm","lolo_non_sp","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","foto_scn","no_stack","tgl_stack","no_eir","tgl_eir","grand_total"];
    public $searchable  = ["id","no_draft","no_nota_rampung","t_buku_order_id","status","tanggal","customer","pelabuhan","container1","container2","tipe1","tipe2","vgm","lolo_non_sp","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","foto_scn","no_stack","tgl_stack","no_eir","tgl_eir","grand_total"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_nota_rampung_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_nota_rampung_d', 't_nota_rampung_id', 'id');
    }
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function container1() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'container1', 'id');
    }
    public function container2() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'container2', 'id');
    }
    public function tipe1() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe1', 'id');
    }
    public function tipe2() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe2', 'id');
    }
}
