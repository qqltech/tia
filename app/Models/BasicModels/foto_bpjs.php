<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class foto_bpjs extends Model
{   
    use ModelTrait;

    protected $table    = 'set.foto_bpjs';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["nip","nama","no_id","alamat_ktp","alamat_domisili","rt","rw","kota_lahir","tgl_lahir","agama","no_tlp","is_active","divisi","jenis_kelamin","kota_ktp","kota_domisili","kecamatan","status_perkawinan","tgl_mulai","email","catatan","foto_kary","creator_id","last_editor_id","created_at","updated_at","delete_id","delete_at","foto_ktp","foto_kk","foto_bpjs","no_rek","bank_id","foto_bpjs_ktj"];

    public $columns     = ["id","nip","nama","no_id","alamat_ktp","alamat_domisili","rt","rw","kota_lahir","tgl_lahir","agama","no_tlp","is_active","divisi","jenis_kelamin","kota_ktp","kota_domisili","kecamatan","status_perkawinan","tgl_mulai","email","catatan","foto_kary","creator_id","last_editor_id","created_at","updated_at","delete_id","delete_at","foto_ktp","foto_kk","foto_bpjs","no_rek","bank_id","foto_bpjs_ktj"];
    public $columnsFull = ["id:bigint","nip:string:20","nama:string:100","no_id:string:100","alamat_ktp:string:100","alamat_domisili:string:100","rt:string:10","rw:string:10","kota_lahir:string:20","tgl_lahir:date","agama:string:20","no_tlp:string:20","is_active:boolean","divisi:string:20","jenis_kelamin:string:10","kota_ktp:string:20","kota_domisili:string:20","kecamatan:string:20","status_perkawinan:string:30","tgl_mulai:date","email:string:100","catatan:text","foto_kary:string:191","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime","delete_id:integer","delete_at:datetime","foto_ktp:string:191","foto_kk:string:191","foto_bpjs:string:191","no_rek:string:191","bank_id:integer","foto_bpjs_ktj:string:191"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=set.foto_bpjs.bank_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","no_id","alamat_ktp","alamat_domisili","rt","rw","kota_lahir","tgl_lahir","agama","no_tlp","is_active","divisi","jenis_kelamin","kota_ktp","kota_domisili","kecamatan","status_perkawinan","email","no_rek","bank_id"];
    public $createable  = ["nip","nama","no_id","alamat_ktp","alamat_domisili","rt","rw","kota_lahir","tgl_lahir","agama","no_tlp","is_active","divisi","jenis_kelamin","kota_ktp","kota_domisili","kecamatan","status_perkawinan","tgl_mulai","email","catatan","foto_kary","creator_id","last_editor_id","created_at","updated_at","delete_id","delete_at","foto_ktp","foto_kk","foto_bpjs","no_rek","bank_id","foto_bpjs_ktj"];
    public $updateable  = ["nip","nama","no_id","alamat_ktp","alamat_domisili","rt","rw","kota_lahir","tgl_lahir","agama","no_tlp","is_active","divisi","jenis_kelamin","kota_ktp","kota_domisili","kecamatan","status_perkawinan","tgl_mulai","email","catatan","foto_kary","creator_id","last_editor_id","created_at","updated_at","delete_id","delete_at","foto_ktp","foto_kk","foto_bpjs","no_rek","bank_id","foto_bpjs_ktj"];
    public $searchable  = ["nip","nama","no_id","alamat_ktp","alamat_domisili","rt","rw","kota_lahir","tgl_lahir","agama","no_tlp","is_active","divisi","jenis_kelamin","kota_ktp","kota_domisili","kecamatan","status_perkawinan","tgl_mulai","email","catatan","foto_kary","creator_id","last_editor_id","created_at","updated_at","delete_id","delete_at","foto_ktp","foto_kk","foto_bpjs","no_rek","bank_id","foto_bpjs_ktj"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function bank() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'bank_id', 'id');
    }
}
