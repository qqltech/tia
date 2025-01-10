<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_dp_penjualan extends Model
{   
    use ModelTrait;

    protected $table    = 't_dp_penjualan';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_dp","no_draft","m_customer_id","t_buku_order_id","tgl_dp","tipe_dp_id","total_amount","status","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","no_dp","no_draft","m_customer_id","t_buku_order_id","tgl_dp","tipe_dp_id","total_amount","status","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_dp:string:40","no_draft:string:40","m_customer_id:bigint","t_buku_order_id:bigint","tgl_dp:date","tipe_dp_id:bigint","total_amount:decimal","status:string:10","keterangan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_customer.id=t_dp_penjualan.m_customer_id","t_buku_order.id=t_dp_penjualan.t_buku_order_id","set.m_general.id=t_dp_penjualan.tipe_dp_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tgl_dp","total_amount"];
    public $createable  = ["no_dp","no_draft","m_customer_id","t_buku_order_id","tgl_dp","tipe_dp_id","total_amount","status","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["no_dp","no_draft","m_customer_id","t_buku_order_id","tgl_dp","tipe_dp_id","total_amount","status","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","no_dp","no_draft","m_customer_id","t_buku_order_id","tgl_dp","tipe_dp_id","total_amount","status","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'm_customer_id', 'id');
    }
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function tipe_dp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_dp_id', 'id');
    }
}
