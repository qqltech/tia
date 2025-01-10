<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_jurnal_angkutan extends Model
{   
    use ModelTrait;

    protected $table    = 't_jurnal_angkutan';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","tgl","no_jurnal","m_supplier_id","catatan","status","no_nota_piutang","creator_id","last_editor_id","delete_id","delete_at","grand_total","ppn","dpp"];

    public $columns     = ["id","no_draft","tgl","no_jurnal","m_supplier_id","catatan","status","no_nota_piutang","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","grand_total","ppn","dpp"];
    public $columnsFull = ["id:bigint","no_draft:string:20","tgl:date","no_jurnal:string:20","m_supplier_id:integer","catatan:text","status:string:20","no_nota_piutang:string:20","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","grand_total:decimal","ppn:decimal","dpp:decimal"];
    public $rules       = [];
    public $joins       = ["m_supplier.id=t_jurnal_angkutan.m_supplier_id"];
    public $details     = ["t_jurnal_angkutan_d"];
    public $heirs       = ["t_pembayaran_hutang_d","t_rencana_pembayaran_hutang_d"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tgl","m_supplier_id","status"];
    public $createable  = ["no_draft","tgl","no_jurnal","m_supplier_id","catatan","status","no_nota_piutang","creator_id","last_editor_id","delete_id","delete_at","grand_total","ppn","dpp"];
    public $updateable  = ["no_draft","tgl","no_jurnal","m_supplier_id","catatan","status","no_nota_piutang","creator_id","last_editor_id","delete_id","delete_at","grand_total","ppn","dpp"];
    public $searchable  = ["id","no_draft","tgl","no_jurnal","m_supplier_id","catatan","status","no_nota_piutang","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","grand_total","ppn","dpp"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_jurnal_angkutan_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_jurnal_angkutan_d', 't_jurnal_angkutan_id', 'id');
    }
    
    
    public function m_supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'm_supplier_id', 'id');
    }
}
