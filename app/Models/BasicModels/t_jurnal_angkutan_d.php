<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_jurnal_angkutan_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_jurnal_angkutan_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_jurnal_angkutan_id","t_angkutan_id","kode_supplier","nama_supplier","sektor","tipe","jenis","ukuran","nominal"];

    public $columns     = ["id","t_jurnal_angkutan_id","t_angkutan_id","kode_supplier","nama_supplier","sektor","tipe","jenis","ukuran","nominal","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_jurnal_angkutan_id:integer","t_angkutan_id:integer","kode_supplier:integer","nama_supplier:integer","sektor:integer","tipe:integer","jenis:integer","ukuran:integer","nominal:decimal","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_jurnal_angkutan.id=t_jurnal_angkutan_d.t_jurnal_angkutan_id","t_angkutan.id=t_jurnal_angkutan_d.t_angkutan_id","m_supplier.id=t_jurnal_angkutan_d.kode_supplier","m_supplier.id=t_jurnal_angkutan_d.nama_supplier","set.m_general.id=t_jurnal_angkutan_d.sektor","set.m_general.id=t_jurnal_angkutan_d.tipe","set.m_general.id=t_jurnal_angkutan_d.jenis","set.m_general.id=t_jurnal_angkutan_d.ukuran"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nominal"];
    public $createable  = ["t_jurnal_angkutan_id","t_angkutan_id","kode_supplier","nama_supplier","sektor","tipe","jenis","ukuran","nominal"];
    public $updateable  = ["t_jurnal_angkutan_id","t_angkutan_id","kode_supplier","nama_supplier","sektor","tipe","jenis","ukuran","nominal"];
    public $searchable  = ["id","t_jurnal_angkutan_id","t_angkutan_id","kode_supplier","nama_supplier","sektor","tipe","jenis","ukuran","nominal","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_jurnal_angkutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_jurnal_angkutan', 't_jurnal_angkutan_id', 'id');
    }
    public function t_angkutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_angkutan', 't_angkutan_id', 'id');
    }
    public function kode_supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'kode_supplier', 'id');
    }
    public function nama_supplier() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'nama_supplier', 'id');
    }
    public function sektor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'sektor', 'id');
    }
    public function tipe() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe', 'id');
    }
    public function jenis() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis', 'id');
    }
    public function ukuran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'ukuran', 'id');
    }
}
