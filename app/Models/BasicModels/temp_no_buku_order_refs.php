<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class temp_no_buku_order_refs extends Model
{   
    use ModelTrait;

    protected $table    = 'temp_no_buku_order_refs';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["id_or_order_number","source_table"];

    public $columns     = ["id_or_order_number","source_table"];
    public $columnsFull = ["id_or_order_number:integer","source_table:text"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["id_or_order_number","source_table"];
    public $updateable  = ["id_or_order_number","source_table"];
    public $searchable  = ["id_or_order_number","source_table"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
