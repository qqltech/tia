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
    protected $fillable = ["kode","group","is_active","deskripsi","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","deskripsi2","deskripsi3","deskripsi4","trx_id","trx_table"];

    public $columns     = ["id","kode","group","is_active","deskripsi","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","deskripsi2","deskripsi3","deskripsi4","trx_id","trx_table"];
    public $columnsFull = ["id:bigint","kode:string:191","group:string:191","is_active:boolean","deskripsi:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","deskripsi2:text","deskripsi3:text","deskripsi4:text","trx_id:integer","trx_table:string:191"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = ["m_coa","m_coa","m_coa","m_customer_d_nama","m_customer","m_customer","m_grup_head_d","m_interface","m_interface","m_interface","m_interface","m_item","m_tarif_angkutan","m_tarif_angkutan","m_tarif_angkutan","m_tarif_angkutan","m_tarif_angkutan","m_supplier","m_supplier","m_supplier","m_supplier","m_tarif","m_tarif","m_tarif_d_lain_lain","m_tarif_komisi","m_tarif_nota_rampung","m_tarif_nota_rampung","m_tarif_nota_rampung","m_tarif_nota_rampung","m_tarif_premi","m_tarif_premi","m_tarif_premi","m_tarif_premi","m_tarif_premi","m_tarif_premi_bckp","m_tarif_premi_bckp","m_tarif_premi_bckp","t_angkutan_d","t_angkutan_d","t_angkutan_d","t_angkutan_d","t_angkutan_d","t_angkutan_d","t_angkutan_d","t_angkutan_d","t_bkk","t_bkk_non_order","t_bkm","t_bkm_non_order","t_buku_order","t_buku_order","t_buku_order","t_buku_order","t_buku_order_d_npwp","t_buku_order_d_npwp","t_buku_order_d_npwp","t_buku_order_d_npwp","t_credit_note","t_confirm_asset","t_debit_note","t_dp_penjualan","t_jurnal_angkutan_d","t_jurnal_angkutan_d","t_jurnal_angkutan_d","t_jurnal_angkutan_d","t_memo_jurnal","t_nota_rampung","t_nota_rampung","t_nota_rampung","t_nota_rampung","t_nota_rampung","t_lpb_d","t_nota_rampung_d","t_nota_rampung_d","t_pembayaran_piutang","t_pembayaran_piutang","t_pembayaran_hutang","t_pembayaran_piutang_d","t_ppjk","t_purchase_invoice","t_purchase_invoice","t_purchase_invoice","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_spk_angkutan","t_rencana_pembayaran_hutang_d","t_spk_lain_d","t_spk_lain","t_sub_credit_note","t_sub_debit_note","t_purchase_order","t_purchase_order","t_surat_jalan","t_tagihan_d_npwp","t_tagihan_d_npwp","t_tagihan_d_npwp","t_tagihan_d_npwp","temp_t_spk_angkutan","temp_t_spk_angkutan","temp_t_spk_angkutan","temp_t_spk_angkutan","temp_t_spk_angkutan","temp_t_spk_angkutan","temp_t_spk_angkutan","temp_t_spk_angkutan","temp_t_spk_angkutan","temp_t_spk_angkutan","temp_t_spk_angkutan","set.m_kary"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["kode","group","is_active","deskripsi"];
    public $createable  = ["kode","group","is_active","deskripsi","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","deskripsi2","deskripsi3","deskripsi4","trx_id","trx_table"];
    public $updateable  = ["kode","group","is_active","deskripsi","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","deskripsi2","deskripsi3","deskripsi4","trx_id","trx_table"];
    public $searchable  = ["kode","group","is_active","deskripsi","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","deskripsi2","deskripsi3","deskripsi4","trx_id","trx_table"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
