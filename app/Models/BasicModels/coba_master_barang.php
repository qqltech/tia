<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class coba_master_barang extends Model
{   
    use ModelTrait;

    protected $table    = 'coba_master_barang';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nama_barang","img_url","qty","status","supplier"];

    public $columns     = ["id","nama_barang","img_url","qty","status","created_at","updated_at","supplier"];
    public $columnsFull = ["id:bigint","nama_barang:string:255","img_url:text","qty:integer","status:smallint","created_at:datetime","updated_at:datetime","supplier:string:191"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["coba_satuan"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama_barang","img_url","qty","status"];
    public $createable  = ["nama_barang","img_url","qty","status","supplier"];
    public $updateable  = ["nama_barang","img_url","qty","status","supplier"];
    public $searchable  = ["id","nama_barang","img_url","qty","status","created_at","updated_at","supplier"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function coba_satuan() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\coba_satuan', 'id_barang', 'id');
    }
    
    
}
