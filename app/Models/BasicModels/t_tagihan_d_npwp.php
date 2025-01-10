<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_tagihan_d_npwp extends Model
{   
    use ModelTrait;

    protected $table    = 't_tagihan_d_npwp';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_buku_order_id","t_tagihan_id","no_prefix","no_suffix","ukuran","tipe","jenis","sektor","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","t_buku_order_id","t_tagihan_id","no_prefix","no_suffix","ukuran","tipe","jenis","sektor","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_buku_order_id:integer","t_tagihan_id:integer","no_prefix:string:20","no_suffix:string:20","ukuran:integer","tipe:integer","jenis:integer","sektor:integer","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_buku_order.id=t_tagihan_d_npwp.t_buku_order_id","t_tagihan.id=t_tagihan_d_npwp.t_tagihan_id","set.m_general.id=t_tagihan_d_npwp.ukuran","set.m_general.id=t_tagihan_d_npwp.tipe","set.m_general.id=t_tagihan_d_npwp.jenis","set.m_general.id=t_tagihan_d_npwp.sektor"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["no_prefix","no_suffix","ukuran","tipe","jenis","sektor"];
    public $createable  = ["t_buku_order_id","t_tagihan_id","no_prefix","no_suffix","ukuran","tipe","jenis","sektor","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["t_buku_order_id","t_tagihan_id","no_prefix","no_suffix","ukuran","tipe","jenis","sektor","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","t_buku_order_id","t_tagihan_id","no_prefix","no_suffix","ukuran","tipe","jenis","sektor","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 't_buku_order_id', 'id');
    }
    public function t_tagihan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_tagihan', 't_tagihan_id', 'id');
    }
    public function ukuran() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'ukuran', 'id');
    }
    public function tipe() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe', 'id');
    }
    public function jenis() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis', 'id');
    }
    public function sektor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'sektor', 'id');
    }
}
