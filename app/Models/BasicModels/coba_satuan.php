<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class coba_satuan extends Model
{   
    use ModelTrait;

    protected $table    = 'coba_satuan';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nama_satuan","id_barang","harga","status"];

    public $columns     = ["id","nama_satuan","id_barang","harga","status","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nama_satuan:string:255","id_barang:bigint","harga:float","status:smallint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["coba_master_barang.id=coba_satuan.id_barang"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama_satuan","harga","status"];
    public $createable  = ["nama_satuan","id_barang","harga","status"];
    public $updateable  = ["nama_satuan","id_barang","harga","status"];
    public $searchable  = ["id","nama_satuan","id_barang","harga","status","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function id_barang() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\coba_master_barang', 'id_barang', 'id');
    }
}
