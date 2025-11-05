<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class temp_id_no_buku extends Model
{   
    use ModelTrait;

    protected $table    = 'set.temp_id_no_buku';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["no_buku_order","id_terkecil","id_lainnya"];

    public $columns     = ["no_buku_order","id_terkecil","id_lainnya"];
    public $columnsFull = ["no_buku_order:string:20","id_terkecil:bigint","id_lainnya:text"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["no_buku_order","id_terkecil","id_lainnya"];
    public $updateable  = ["no_buku_order","id_terkecil","id_lainnya"];
    public $searchable  = ["no_buku_order","id_terkecil","id_lainnya"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
