<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_supplier extends Model
{   
    use ModelTrait;

    protected $table    = 'm_supplier';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["kode","is_active","nama","tipe_id","jenis_id","pajak","top","nik","npwp","alamat","pph","b2b","link_b2b","negara","provinsi","kota","kecamatan","bank","kode_bank","no_rekening","nama_rekening","no_telp1","no_telp2","email","contact_person1","no_telp_contact_person1","email_contact_person1","contact_person2","email_contact_person2","no_telp_contact_person2","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];

    public $columns     = ["id","kode","is_active","nama","tipe_id","jenis_id","pajak","top","nik","npwp","alamat","pph","b2b","link_b2b","negara","provinsi","kota","kecamatan","bank","kode_bank","no_rekening","nama_rekening","no_telp1","no_telp2","email","contact_person1","no_telp_contact_person1","email_contact_person1","contact_person2","email_contact_person2","no_telp_contact_person2","catatan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","kode:string:191","is_active:boolean","nama:string:100","tipe_id:bigint","jenis_id:bigint","pajak:string:20","top:integer","nik:string:20","npwp:string:20","alamat:string:500","pph:boolean","b2b:boolean","link_b2b:string:200","negara:string:120","provinsi:string:120","kota:string:120","kecamatan:string:120","bank:integer","kode_bank:string:10","no_rekening:string:20","nama_rekening:string:100","no_telp1:string:20","no_telp2:string:20","email:string:100","contact_person1:string:100","no_telp_contact_person1:string:20","email_contact_person1:string:100","contact_person2:string:100","email_contact_person2:string:100","no_telp_contact_person2:string:20","catatan:text","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=m_supplier.tipe_id","set.m_general.id=m_supplier.jenis_id","set.m_general.id=m_supplier.top","set.m_general.id=m_supplier.bank"];
    public $details     = [];
    public $heirs       = ["t_purchase_invoice","t_lpb","t_angkutan_d","t_angkutan_d","t_purchase_order","t_rencana_pembayaran_hutang_d","m_asset_d","m_tarif_angkutan","t_credit_note","t_spk_angkutan","t_debit_note","t_bon_dinas_luar","t_jurnal_angkutan","t_pembayaran_hutang","t_jurnal_angkutan_d","t_jurnal_angkutan_d"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [
    "kode"=> "unique:m_supplier,kode"
	];
    public $required    = ["is_active","nama","tipe_id","jenis_id","top","alamat","pph","b2b","negara","provinsi","kota","kecamatan","bank","kode_bank","no_rekening","nama_rekening"];
    public $createable  = ["kode","is_active","nama","tipe_id","jenis_id","pajak","top","nik","npwp","alamat","pph","b2b","link_b2b","negara","provinsi","kota","kecamatan","bank","kode_bank","no_rekening","nama_rekening","no_telp1","no_telp2","email","contact_person1","no_telp_contact_person1","email_contact_person1","contact_person2","email_contact_person2","no_telp_contact_person2","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $updateable  = ["kode","is_active","nama","tipe_id","jenis_id","pajak","top","nik","npwp","alamat","pph","b2b","link_b2b","negara","provinsi","kota","kecamatan","bank","kode_bank","no_rekening","nama_rekening","no_telp1","no_telp2","email","contact_person1","no_telp_contact_person1","email_contact_person1","contact_person2","email_contact_person2","no_telp_contact_person2","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $searchable  = ["id","kode","is_active","nama","tipe_id","jenis_id","pajak","top","nik","npwp","alamat","pph","b2b","link_b2b","negara","provinsi","kota","kecamatan","bank","kode_bank","no_rekening","nama_rekening","no_telp1","no_telp2","email","contact_person1","no_telp_contact_person1","email_contact_person1","contact_person2","email_contact_person2","no_telp_contact_person2","catatan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function tipe() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_id', 'id');
    }
    public function jenis() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis_id', 'id');
    }
    public function top() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'top', 'id');
    }
    public function bank() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'bank', 'id');
    }
}
