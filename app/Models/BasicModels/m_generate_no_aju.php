<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_generate_no_aju extends Model
{   
    use ModelTrait;

    protected $table    = 'm_generate_no_aju';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["tipe","kode","tgl_pembuatan","periode","tahun","bulan","no_awal","no_akhir","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","tipe","kode","tgl_pembuatan","periode","tahun","bulan","no_awal","no_akhir","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","tipe:string:100","kode:string:100","tgl_pembuatan:date","periode:string:100","tahun:string:100","bulan:string:100","no_awal:string:100","no_akhir:string:100","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["m_generate_no_aju_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= ["t_ppjk"];
    public $unique      = [];
    public $required    = ["tipe","kode","tgl_pembuatan","periode","tahun","bulan","no_awal","no_akhir"];
    public $createable  = ["tipe","kode","tgl_pembuatan","periode","tahun","bulan","no_awal","no_akhir","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["tipe","kode","tgl_pembuatan","periode","tahun","bulan","no_awal","no_akhir","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","tipe","kode","tgl_pembuatan","periode","tahun","bulan","no_awal","no_akhir","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_generate_no_aju_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_generate_no_aju_d', 'm_generate_no_aju_id', 'id');
    }
    
    
}
