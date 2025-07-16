<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class test_steven_d extends Model
{   
    use ModelTrait;

    protected $table    = 'test_steven_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nama_satuan","id_barang","harga","status"];

    public $columns     = ["id","nama_satuan","id_barang","harga","status","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nama_satuan:string:191","id_barang:bigint","harga:float","status:smallint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["test_steven.id=test_steven_d.id_barang"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama_satuan","id_barang","harga","status"];
    public $createable  = ["nama_satuan","id_barang","harga","status"];
    public $updateable  = ["nama_satuan","id_barang","harga","status"];
    public $searchable  = ["id","nama_satuan","id_barang","harga","status","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function id_barang() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\test_steven', 'id_barang', 'id');
    }
}
