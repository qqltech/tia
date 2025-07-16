<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class test_yuandi extends Model
{   
    use ModelTrait;

    protected $table    = 'test_yuandi';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nama_barang","img_url","qty","status"];

    public $columns     = ["id","nama_barang","img_url","qty","status","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nama_barang:string:191","img_url:text","qty:integer","status:smallint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["test_yuandi_2"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama_barang","img_url","qty","status"];
    public $createable  = ["nama_barang","img_url","qty","status"];
    public $updateable  = ["nama_barang","img_url","qty","status"];
    public $searchable  = ["id","nama_barang","img_url","qty","status","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function test_yuandi_2() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\test_yuandi_2', 'id_barang', 'id');
    }
    
    
}
