<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_buku_order_d_npwp extends Model
{   
    use ModelTrait;

    protected $table    = 't_buku_order_d_npwp';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_buku_order_id","no_prefix","no_suffix","ukuran","jenis","sektor","creator_id","last_editor_id","delete_id","delete_at","depo","m_petugas_pengkont_id","m_petugas_pemasukan_id"];

    public $columns     = ["id","t_buku_order_id","no_prefix","no_suffix","ukuran","jenis","sektor","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","depo","m_petugas_pengkont_id","m_petugas_pemasukan_id"];
    public $columnsFull = ["id:bigint","t_buku_order_id:integer","no_prefix:string:20","no_suffix:string:20","ukuran:integer","jenis:integer","sektor:integer","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","depo:integer","m_petugas_pengkont_id:integer","m_petugas_pemasukan_id:integer"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_buku_order_d_npwp.t_buku_order_id","set.m_general.id=t_buku_order_d_npwp.ukuran","set.m_general.id=t_buku_order_d_npwp.jenis","set.m_general.id=t_buku_order_d_npwp.sektor","set.m_general.id=t_buku_order_d_npwp.depo","set.m_kary.id=t_buku_order_d_npwp.m_petugas_pengkont_id","set.m_kary.id=t_buku_order_d_npwp.m_petugas_pemasukan_id"];
    public $details     = [];
    public $heirs       = ["t_surat_jalan","t_spk_angkutan","t_spk_angkutan","t_nota_rampung_d"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["t_buku_order_id","no_prefix","no_suffix","ukuran","jenis","sektor","creator_id","last_editor_id","delete_id","delete_at","depo","m_petugas_pengkont_id","m_petugas_pemasukan_id"];
    public $updateable  = ["t_buku_order_id","no_prefix","no_suffix","ukuran","jenis","sektor","creator_id","last_editor_id","delete_id","delete_at","depo","m_petugas_pengkont_id","m_petugas_pemasukan_id"];
    public $searchable  = ["id","t_buku_order_id","no_prefix","no_suffix","ukuran","jenis","sektor","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","depo","m_petugas_pengkont_id","m_petugas_pemasukan_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function ukuran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'ukuran', 'id');
    }
    public function jenis() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis', 'id');
    }
    public function sektor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'sektor', 'id');
    }
    public function depo() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'depo', 'id');
    }
    public function m_petugas_pengkont() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_kary', 'm_petugas_pengkont_id', 'id');
    }
    public function m_petugas_pemasukan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_kary', 'm_petugas_pemasukan_id', 'id');
    }
}
