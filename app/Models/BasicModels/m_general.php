<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_general extends Model
{   
    use ModelTrait;

    protected $table    = 'set.m_general';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["kode","group","is_active","deskripsi","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","deskripsi2","deskripsi3","deskripsi4"];

    public $columns     = ["id","kode","group","is_active","deskripsi","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","deskripsi2","deskripsi3","deskripsi4"];
    public $columnsFull = ["id:bigint","kode:string:191","group:string:191","is_active:boolean","deskripsi:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","deskripsi2:text","deskripsi3:text","deskripsi4:text"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = ["t_purchase_invoice","t_purchase_invoice","t_purchase_invoice","t_angkutan_d","t_angkutan_d","t_angkutan_d","t_angkutan_d","t_angkutan_d","t_angkutan_d","t_angkutan_d","set.m_kary","m_supplier","m_supplier","m_supplier","m_supplier","m_item","t_buku_order","t_buku_order","t_buku_order","t_buku_order","t_purchase_order","t_purchase_order","m_customer","m_customer","t_surat_jalan","t_rencana_pembayaran_hutang_d","m_customer_d_nama","m_tarif_premi","m_tarif_premi","m_tarif_premi","m_tarif_premi","m_tarif_premi","m_tarif","m_tarif","t_spk_lain","m_tarif_angkutan","m_tarif_angkutan","m_tarif_angkutan","m_tarif_angkutan","t_credit_note","t_buku_order_d_npwp","t_buku_order_d_npwp","t_buku_order_d_npwp","t_buku_order_d_npwp","t_tagihan_d_npwp","t_tagihan_d_npwp","t_tagihan_d_npwp","t_tagihan_d_npwp","m_interface","m_interface","m_interface","m_interface","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_pembayaran_piutang","t_debit_note","m_tarif_komisi","m_tarif_d_lain_lain","t_pembayaran_hutang","t_pembayaran_piutang_d","t_nota_rampung","t_nota_rampung","t_nota_rampung","t_nota_rampung","t_nota_rampung","t_jurnal_angkutan_d","t_jurnal_angkutan_d","t_jurnal_angkutan_d","t_jurnal_angkutan_d","m_coa","m_coa","m_coa","t_memo_jurnal","t_dp_penjualan","t_sub_debit_note","t_sub_credit_note","t_confirm_asset","t_bkk","t_nota_rampung_d","t_nota_rampung_d","m_tarif_nota_rampung","m_tarif_nota_rampung","m_tarif_nota_rampung","m_tarif_nota_rampung","t_bkk_non_order","t_bkm","t_bkm_non_order"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["kode","group","is_active","deskripsi"];
    public $createable  = ["kode","group","is_active","deskripsi","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","deskripsi2","deskripsi3","deskripsi4"];
    public $updateable  = ["kode","group","is_active","deskripsi","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","deskripsi2","deskripsi3","deskripsi4"];
    public $searchable  = ["kode","group","is_active","deskripsi","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","deskripsi2","deskripsi3","deskripsi4"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
