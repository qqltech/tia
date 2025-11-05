<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_tagihan extends Model
{   
    use ModelTrait;

    protected $table    = 't_tagihan';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_tagihan","no_buku_order","customer","status","tgl","tipe_tagihan","total_amount","ppn","grand_total_amount","catatan","creator_id","last_editor_id","delete_id","delete_at","grand_total","total_kontainer","total_lain","total_ppn","total_setelah_ppn","total_tarif_jasa","piutang","no_faktur_pajak","total_jasa_cont_ppjk","total_lain2_ppn","total_jasa_angkutan","total_lain_non_ppn","tgl_nota","persentase_konsolidator_kont"];

    public $columns     = ["id","no_draft","no_tagihan","no_buku_order","customer","status","tgl","tipe_tagihan","total_amount","ppn","grand_total_amount","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","grand_total","total_kontainer","total_lain","total_ppn","total_setelah_ppn","total_tarif_jasa","piutang","no_faktur_pajak","total_jasa_cont_ppjk","total_lain2_ppn","total_jasa_angkutan","total_lain_non_ppn","tgl_nota","persentase_konsolidator_kont"];
    public $columnsFull = ["id:bigint","no_draft:string:191","no_tagihan:string:191","no_buku_order:integer","customer:integer","status:string:10","tgl:date","tipe_tagihan:string:191","total_amount:decimal","ppn:decimal","grand_total_amount:decimal","catatan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","grand_total:decimal","total_kontainer:decimal","total_lain:decimal","total_ppn:decimal","total_setelah_ppn:decimal","total_tarif_jasa:decimal","piutang:decimal","no_faktur_pajak:string:191","total_jasa_cont_ppjk:decimal","total_lain2_ppn:decimal","total_jasa_angkutan:decimal","total_lain_non_ppn:decimal","tgl_nota:date","persentase_konsolidator_kont:decimal"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_tagihan.no_buku_order","m_customer.id=t_tagihan.customer"];
    public $details     = ["t_tagihan_d_lain","t_tagihan_d_tarif","t_tagihan_d_npwp"];
    public $heirs       = ["t_credit_note_d","t_pembayaran_piutang_d","t_debit_note_d","t_sub_debit_note","t_sub_credit_note"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["no_buku_order","customer","status","tgl","ppn"];
    public $createable  = ["no_draft","no_tagihan","no_buku_order","customer","status","tgl","tipe_tagihan","total_amount","ppn","grand_total_amount","catatan","creator_id","last_editor_id","delete_id","delete_at","grand_total","total_kontainer","total_lain","total_ppn","total_setelah_ppn","total_tarif_jasa","piutang","no_faktur_pajak","total_jasa_cont_ppjk","total_lain2_ppn","total_jasa_angkutan","total_lain_non_ppn","tgl_nota","persentase_konsolidator_kont"];
    public $updateable  = ["no_draft","no_tagihan","no_buku_order","customer","status","tgl","tipe_tagihan","total_amount","ppn","grand_total_amount","catatan","creator_id","last_editor_id","delete_id","delete_at","grand_total","total_kontainer","total_lain","total_ppn","total_setelah_ppn","total_tarif_jasa","piutang","no_faktur_pajak","total_jasa_cont_ppjk","total_lain2_ppn","total_jasa_angkutan","total_lain_non_ppn","tgl_nota","persentase_konsolidator_kont"];
    public $searchable  = ["id","no_draft","no_tagihan","no_buku_order","customer","status","tgl","tipe_tagihan","total_amount","ppn","grand_total_amount","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","grand_total","total_kontainer","total_lain","total_ppn","total_setelah_ppn","total_tarif_jasa","piutang","no_faktur_pajak","total_jasa_cont_ppjk","total_lain2_ppn","total_jasa_angkutan","total_lain_non_ppn","tgl_nota","persentase_konsolidator_kont"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_tagihan_d_lain() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_tagihan_d_lain', 't_tagihan_id', 'id');
    }
    public function t_tagihan_d_tarif() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_tagihan_d_tarif', 't_tagihan_id', 'id');
    }
    public function t_tagihan_d_npwp() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_tagihan_d_npwp', 't_tagihan_id', 'id');
    }
    
    
    public function no_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 'no_buku_order', 'id');
    }
    public function customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'customer', 'id');
    }
}
