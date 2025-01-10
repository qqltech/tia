<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pembayaran_piutang_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_pembayaran_piutang_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_pembayaran_piutang_id","t_tagihan_id","bayar","sisa_piutang","total_bayar","catatan","creator_id","last_editor_id","delete_id","delete_at","bukti_potong","total_pph","pph_id"];

    public $columns     = ["id","t_pembayaran_piutang_id","t_tagihan_id","bayar","sisa_piutang","total_bayar","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","bukti_potong","total_pph","pph_id"];
    public $columnsFull = ["id:bigint","t_pembayaran_piutang_id:bigint","t_tagihan_id:bigint","bayar:decimal","sisa_piutang:decimal","total_bayar:decimal","catatan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","bukti_potong:text","total_pph:decimal","pph_id:bigint"];
    public $rules       = [];
    public $joins       = ["t_pembayaran_piutang.id=t_pembayaran_piutang_d.t_pembayaran_piutang_id","t_tagihan.id=t_pembayaran_piutang_d.t_tagihan_id","set.m_general.id=t_pembayaran_piutang_d.pph_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_tagihan_id"];
    public $createable  = ["t_pembayaran_piutang_id","t_tagihan_id","bayar","sisa_piutang","total_bayar","catatan","creator_id","last_editor_id","delete_id","delete_at","bukti_potong","total_pph","pph_id"];
    public $updateable  = ["t_pembayaran_piutang_id","t_tagihan_id","bayar","sisa_piutang","total_bayar","catatan","creator_id","last_editor_id","delete_id","delete_at","bukti_potong","total_pph","pph_id"];
    public $searchable  = ["id","t_pembayaran_piutang_id","t_tagihan_id","bayar","sisa_piutang","total_bayar","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","bukti_potong","total_pph","pph_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_pembayaran_piutang() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_pembayaran_piutang', 't_pembayaran_piutang_id', 'id');
    }
    public function t_tagihan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_tagihan', 't_tagihan_id', 'id');
    }
    public function pph() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'pph_id', 'id');
    }
}
