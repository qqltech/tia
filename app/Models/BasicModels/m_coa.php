<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_coa extends Model
{   
    use ModelTrait;

    protected $table    = 'm_coa';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["tipe_perkiraan","kategori","no_induk","m_induk_id","nomor","induk","nama_coa","level","jenis","debit_kredit","catatan","is_active","creator_id","last_editor_id","delete_id","delete_at","no_coa_old","no_coa_modify","column5","indux"];

    public $columns     = ["id","tipe_perkiraan","kategori","no_induk","m_induk_id","nomor","induk","nama_coa","level","jenis","debit_kredit","catatan","is_active","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","no_coa_old","no_coa_modify","column5","indux"];
    public $columnsFull = ["id:bigint","tipe_perkiraan:integer","kategori:integer","no_induk:string:30","m_induk_id:integer","nomor:string:191","induk:boolean","nama_coa:text","level:integer","jenis:integer","debit_kredit:string:191","catatan:text","is_active:boolean","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","no_coa_old:string:50","no_coa_modify:string:50","column5:string:50","indux:boolean"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=m_coa.tipe_perkiraan","set.m_general.id=m_coa.kategori","m_coa.id=m_coa.m_induk_id","set.m_general.id=m_coa.jenis"];
    public $details     = [];
    public $heirs       = ["set.m_kary","m_customer","t_credit_note","t_bkm_d","t_pembayaran_piutang","t_pembayaran_piutang","t_bkm_non_order_d","t_bkk_non_order_d","t_debit_note","t_bon_dinas_luar","t_bon_dinas_luar","t_bkk_d","t_bll","t_pembayaran_hutang","t_pembayaran_hutang","t_bll_d","m_coa","t_memo_jurnal_d","t_buku_penyesuaian_d","t_buku_penyesuaian","t_sub_debit_note","t_sub_credit_note","t_confirm_asset","t_confirm_asset","t_confirm_asset","t_bkk","t_bkk","t_bkk","t_bkk_non_order","t_bkk_non_order","t_bkm","t_bkm","t_bkm_non_order","t_bkm_non_order"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["kategori","nomor","induk","nama_coa","level","jenis","debit_kredit","is_active"];
    public $createable  = ["tipe_perkiraan","kategori","no_induk","m_induk_id","nomor","induk","nama_coa","level","jenis","debit_kredit","catatan","is_active","creator_id","last_editor_id","delete_id","delete_at","no_coa_old","no_coa_modify","column5","indux"];
    public $updateable  = ["tipe_perkiraan","kategori","no_induk","m_induk_id","nomor","induk","nama_coa","level","jenis","debit_kredit","catatan","is_active","creator_id","last_editor_id","delete_id","delete_at","no_coa_old","no_coa_modify","column5","indux"];
    public $searchable  = ["id","tipe_perkiraan","kategori","no_induk","m_induk_id","nomor","induk","nama_coa","level","jenis","debit_kredit","catatan","is_active","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","no_coa_old","no_coa_modify","column5","indux"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function tipe_perkiraan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_perkiraan', 'id');
    }
    public function kategori() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'kategori', 'id');
    }
    public function m_induk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'm_induk_id', 'id');
    }
    public function jenis() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis', 'id');
    }
}
