<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_customer extends Model
{   
    use ModelTrait;

    protected $table    = 'm_customer';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_customer_group_id","kode","kota","kodepos","taxable","coa_piutang","no_tlp2","fax1","email","cp1","email_cp1","no_tlp_cp2","catatan","latitude","is_active","jenis_perusahaan","nama_perusahaan","alamat","kecamatan","top","tolerance","no_tlp1","no_tlp3","fax2","website","no_tlp_cp1","cp2","email_cp2","longtitude","jabatan1","jabatan2","creator_id","last_editor_id","delete_id","delete_at","custom_stuple","no_tlp_2","longtude","deleted_id","deleted_at"];

    public $columns     = ["id","m_customer_group_id","kode","kota","kodepos","taxable","coa_piutang","no_tlp2","fax1","email","cp1","email_cp1","no_tlp_cp2","catatan","latitude","is_active","jenis_perusahaan","nama_perusahaan","alamat","kecamatan","top","tolerance","no_tlp1","no_tlp3","fax2","website","no_tlp_cp1","cp2","email_cp2","longtitude","jabatan1","jabatan2","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","custom_stuple","no_tlp_2","longtude","deleted_id","deleted_at"];
    public $columnsFull = ["id:bigint","m_customer_group_id:integer","kode:string:191","kota:string:50","kodepos:string:10","taxable:boolean","coa_piutang:integer","no_tlp2:string:20","fax1:string:20","email:string:100","cp1:string:100","email_cp1:string:100","no_tlp_cp2:string:20","catatan:text","latitude:float","is_active:boolean","jenis_perusahaan:string:191","nama_perusahaan:string:191","alamat:text","kecamatan:string:20","top:integer","tolerance:integer","no_tlp1:string:20","no_tlp3:string:20","fax2:string:20","website:text","no_tlp_cp1:string:20","cp2:string:100","email_cp2:string:100","longtitude:float","jabatan1:integer","jabatan2:integer","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","custom_stuple:boolean","no_tlp_2:integer","longtude:string:1","deleted_id:string:1","deleted_at:string:1"];
    public $rules       = [];
    public $joins       = ["m_customer_group.id=m_customer.m_customer_group_id","m_coa.id=m_customer.coa_piutang","set.m_general.id=m_customer.jabatan1","set.m_general.id=m_customer.jabatan2"];
    public $details     = ["m_customer_d_address","m_customer_d_nama","m_customer_d_npwp"];
    public $heirs       = ["t_ppjk","t_asset_disposal","t_buku_order_d_aju","t_buku_order","t_komisi","m_tarif","t_credit_note","temp_t_spk_angkutan","temp_t_spk_angkutan","t_pembayaran_piutang","t_spk_angkutan","t_spk_angkutan","t_debit_note","m_tarif_komisi","m_tarif_komisi_undername","t_komisi_undername","t_dp_penjualan","t_tagihan_lain_lain","t_tagihan","t_spk_lain"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["kota","kodepos","taxable","is_active","jenis_perusahaan","nama_perusahaan","kecamatan","top","tolerance"];
    public $createable  = ["m_customer_group_id","kode","kota","kodepos","taxable","coa_piutang","no_tlp2","fax1","email","cp1","email_cp1","no_tlp_cp2","catatan","latitude","is_active","jenis_perusahaan","nama_perusahaan","alamat","kecamatan","top","tolerance","no_tlp1","no_tlp3","fax2","website","no_tlp_cp1","cp2","email_cp2","longtitude","jabatan1","jabatan2","creator_id","last_editor_id","delete_id","delete_at","custom_stuple","no_tlp_2","longtude","deleted_id","deleted_at"];
    public $updateable  = ["m_customer_group_id","kode","kota","kodepos","taxable","coa_piutang","no_tlp2","fax1","email","cp1","email_cp1","no_tlp_cp2","catatan","latitude","is_active","jenis_perusahaan","nama_perusahaan","alamat","kecamatan","top","tolerance","no_tlp1","no_tlp3","fax2","website","no_tlp_cp1","cp2","email_cp2","longtitude","jabatan1","jabatan2","creator_id","last_editor_id","delete_id","delete_at","custom_stuple","no_tlp_2","longtude","deleted_id","deleted_at"];
    public $searchable  = ["id","m_customer_group_id","kode","kota","kodepos","taxable","coa_piutang","no_tlp2","fax1","email","cp1","email_cp1","no_tlp_cp2","catatan","latitude","is_active","jenis_perusahaan","nama_perusahaan","alamat","kecamatan","top","tolerance","no_tlp1","no_tlp3","fax2","website","no_tlp_cp1","cp2","email_cp2","longtitude","jabatan1","jabatan2","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","custom_stuple","no_tlp_2","longtude","deleted_id","deleted_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_customer_d_address() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_customer_d_address', 'm_customer_id', 'id');
    }
    public function m_customer_d_nama() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_customer_d_nama', 'm_customer_id', 'id');
    }
    public function m_customer_d_npwp() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_customer_d_npwp', 'm_customer_id', 'id');
    }
    
    
    public function m_customer_group() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer_group', 'm_customer_group_id', 'id');
    }
    public function coa_piutang() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'coa_piutang', 'id');
    }
    public function jabatan1() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jabatan1', 'id');
    }
    public function jabatan2() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jabatan2', 'id');
    }
}
