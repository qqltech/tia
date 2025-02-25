<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_tagihan_lain_lain extends Model
{   
    use ModelTrait;

    protected $table    = 't_tagihan_lain_lain';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_tagihan_lain_lain","customer","status","ppn","total_amount_ppn","total_amount_non_ppn","total_ppn","grand_total_amount","piutang","catatan","creator_id","last_editor_id","delete_id","delete_at","tgl","tgl_nota","no_buku_order"];

    public $columns     = ["id","no_draft","no_tagihan_lain_lain","customer","status","ppn","total_amount_ppn","total_amount_non_ppn","total_ppn","grand_total_amount","piutang","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","tgl","tgl_nota","no_buku_order"];
    public $columnsFull = ["id:bigint","no_draft:string:40","no_tagihan_lain_lain:string:200","customer:integer","status:string:10","ppn:decimal","total_amount_ppn:decimal","total_amount_non_ppn:decimal","total_ppn:decimal","grand_total_amount:decimal","piutang:decimal","catatan:string:500","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","tgl:datetime","tgl_nota:datetime","no_buku_order:integer"];
    public $rules       = [];
    public $joins       = ["m_customer.id=t_tagihan_lain_lain.customer","t_buku_order.id=t_tagihan_lain_lain.no_buku_order"];
    public $details     = ["t_tagihan_d_lain"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["no_draft","no_tagihan_lain_lain","customer","status","ppn","total_amount_ppn","total_amount_non_ppn","total_ppn","grand_total_amount","piutang","catatan","tgl","tgl_nota","no_buku_order"];
    public $createable  = ["no_draft","no_tagihan_lain_lain","customer","status","ppn","total_amount_ppn","total_amount_non_ppn","total_ppn","grand_total_amount","piutang","catatan","creator_id","last_editor_id","delete_id","delete_at","tgl","tgl_nota","no_buku_order"];
    public $updateable  = ["no_draft","no_tagihan_lain_lain","customer","status","ppn","total_amount_ppn","total_amount_non_ppn","total_ppn","grand_total_amount","piutang","catatan","creator_id","last_editor_id","delete_id","delete_at","tgl","tgl_nota","no_buku_order"];
    public $searchable  = ["id","no_draft","no_tagihan_lain_lain","customer","status","ppn","total_amount_ppn","total_amount_non_ppn","total_ppn","grand_total_amount","piutang","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","tgl","tgl_nota","no_buku_order"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_tagihan_d_lain() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_tagihan_d_lain', 't_tagihan_lain_lain_id', 'id');
    }
    
    
    public function customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'customer', 'id');
    }
    public function no_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 'no_buku_order', 'id');
    }
}
