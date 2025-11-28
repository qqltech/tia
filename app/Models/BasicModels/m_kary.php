<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_kary extends Model
{   
    use ModelTrait;

    protected $table    = 'set.m_kary';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["nip","nama","no_id","alamat_ktp","alamat_domisili","rt","rw","kota_lahir","tgl_lahir","agama","no_tlp","is_active","divisi","jenis_kelamin","kota_ktp","kota_domisili","kecamatan","status_perkawinan","tgl_mulai","email","catatan","foto_kary","creator_id","last_editor_id","created_at","updated_at","delete_id","delete_at","foto_ktp","foto_kk","foto_bpjs_ks","no_rek","bank_id","foto_bpjs_ktj","bu_id","piutang_id"];

    public $columns     = ["id","nip","nama","no_id","alamat_ktp","alamat_domisili","rt","rw","kota_lahir","tgl_lahir","agama","no_tlp","is_active","divisi","jenis_kelamin","kota_ktp","kota_domisili","kecamatan","status_perkawinan","tgl_mulai","email","catatan","foto_kary","creator_id","last_editor_id","created_at","updated_at","delete_id","delete_at","foto_ktp","foto_kk","foto_bpjs_ks","no_rek","bank_id","foto_bpjs_ktj","bu_id","piutang_id"];
    public $columnsFull = ["id:bigint","nip:string:20","nama:string:100","no_id:string:100","alamat_ktp:string:100","alamat_domisili:string:100","rt:string:10","rw:string:10","kota_lahir:string:20","tgl_lahir:date","agama:string:20","no_tlp:string:20","is_active:boolean","divisi:string:20","jenis_kelamin:string:10","kota_ktp:string:20","kota_domisili:string:20","kecamatan:string:20","status_perkawinan:string:30","tgl_mulai:date","email:string:100","catatan:text","foto_kary:string:191","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime","delete_id:integer","delete_at:datetime","foto_ktp:string:191","foto_kk:string:191","foto_bpjs_ks:string:191","no_rek:string:191","bank_id:integer","foto_bpjs_ktj:string:191","bu_id:integer","piutang_id:integer"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=set.m_kary.bank_id","set.m_business_unit.id=set.m_kary.bu_id","m_coa.id=set.m_kary.piutang_id"];
    public $details     = [];
    public $heirs       = ["default_users","t_bon_dinas_luar","t_bon_spk_lain","t_buku_order_d_npwp","t_buku_order_d_npwp","t_confirm_asset","t_dinas_luar","t_spk_angkutan","temp_t_spk_angkutan"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","alamat_ktp","alamat_domisili","rt","rw","kota_lahir","tgl_lahir","agama","is_active","divisi","jenis_kelamin","kota_ktp","kota_domisili","kecamatan","status_perkawinan"];
    public $createable  = ["nip","nama","no_id","alamat_ktp","alamat_domisili","rt","rw","kota_lahir","tgl_lahir","agama","no_tlp","is_active","divisi","jenis_kelamin","kota_ktp","kota_domisili","kecamatan","status_perkawinan","tgl_mulai","email","catatan","foto_kary","creator_id","last_editor_id","created_at","updated_at","delete_id","delete_at","foto_ktp","foto_kk","foto_bpjs_ks","no_rek","bank_id","foto_bpjs_ktj","bu_id","piutang_id"];
    public $updateable  = ["nip","nama","no_id","alamat_ktp","alamat_domisili","rt","rw","kota_lahir","tgl_lahir","agama","no_tlp","is_active","divisi","jenis_kelamin","kota_ktp","kota_domisili","kecamatan","status_perkawinan","tgl_mulai","email","catatan","foto_kary","creator_id","last_editor_id","created_at","updated_at","delete_id","delete_at","foto_ktp","foto_kk","foto_bpjs_ks","no_rek","bank_id","foto_bpjs_ktj","bu_id","piutang_id"];
    public $searchable  = ["nip","nama","no_id","alamat_ktp","alamat_domisili","rt","rw","kota_lahir","tgl_lahir","agama","no_tlp","is_active","divisi","jenis_kelamin","kota_ktp","kota_domisili","kecamatan","status_perkawinan","tgl_mulai","email","catatan","foto_kary","creator_id","last_editor_id","created_at","updated_at","delete_id","delete_at","foto_ktp","foto_kk","foto_bpjs_ks","no_rek","bank_id","foto_bpjs_ktj","bu_id","piutang_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function bank() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'bank_id', 'id');
    }
    public function bu() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_business_unit', 'bu_id', 'id');
    }
    public function piutang() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_coa', 'piutang_id', 'id');
    }
}
