<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class temp_no_buku_terkecil extends Model
{   
    use ModelTrait;

    protected $table    = 'set.temp_no_buku_terkecil';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["no_buku_order","id_terkecil","semua_id"];

    public $columns     = ["no_buku_order","id_terkecil","semua_id"];
    public $columnsFull = ["no_buku_order:string:20","id_terkecil:bigint","semua_id:text"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["no_buku_order","id_terkecil","semua_id"];
    public $updateable  = ["no_buku_order","id_terkecil","semua_id"];
    public $searchable  = ["no_buku_order","id_terkecil","semua_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
