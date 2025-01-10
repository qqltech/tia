<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pembayaran_hutang extends Model
{   
    use ModelTrait;

    protected $table    = 't_pembayaran_hutang';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_pembayaran","status","tanggal","tanggal_pembayaran","tipe_pembayaran_id","total_amt","include_pph","m_akun_pembayaran_id","supplier_id","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","t_rencana_pembayaran_hutang_id"];

    public $columns     = ["id","no_draft","no_pembayaran","status","tanggal","tanggal_pembayaran","tipe_pembayaran_id","total_amt","include_pph","m_akun_pembayaran_id","supplier_id","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","t_rencana_pembayaran_hutang_id"];
    public $columnsFull = ["id:bigint","no_draft:string:20","no_pembayaran:string:20","status:string:40","tanggal:date","tanggal_pembayaran:date","tipe_pembayaran_id:integer","total_amt:decimal","include_pph:boolean","m_akun_pembayaran_id:integer","supplier_id:integer","keterangan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","t_rencana_pembayaran_hutang_id:integer"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=t_pembayaran_hutang.tipe_pembayaran_id","m_coa.id=t_pembayaran_hutang.m_akun_pembayaran_id","m_supplier.id=t_pembayaran_hutang.supplier_id","t_rencana_pembayaran_hutang.id=t_pembayaran_hutang.t_rencana_pembayaran_hutang_id"];
    public $details     = ["t_pembayaran_hutang_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tanggal_pembayaran","include_pph","m_akun_pembayaran_id","supplier_id"];
    public $createable  = ["no_draft","no_pembayaran","status","tanggal","tanggal_pembayaran","tipe_pembayaran_id","total_amt","include_pph","m_akun_pembayaran_id","supplier_id","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","t_rencana_pembayaran_hutang_id"];
    public $updateable  = ["no_draft","no_pembayaran","status","tanggal","tanggal_pembayaran","tipe_pembayaran_id","total_amt","include_pph","m_akun_pembayaran_id","supplier_id","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","t_rencana_pembayaran_hutang_id"];
    public $searchable  = ["id","no_draft","no_pembayaran","status","tanggal","tanggal_pembayaran","tipe_pembayaran_id","total_amt","include_pph","m_akun_pembayaran_id","supplier_id","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","t_rencana_pembayaran_hutang_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_pembayaran_hutang_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_pembayaran_hutang_d', 't_pembayaran_hutang_id', 'id');
    }
    
    
    public function tipe_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_pembayaran_id', 'id');
    }
    public function m_akun_pembayaran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_akun_pembayaran_id', 'id');
    }
    public function supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'supplier_id', 'id');
    }
    public function t_rencana_pembayaran_hutang() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_rencana_pembayaran_hutang', 't_rencana_pembayaran_hutang_id', 'id');
    }
}
