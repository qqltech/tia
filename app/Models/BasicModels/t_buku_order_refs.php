<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_buku_order_refs extends Model
{   
    use ModelTrait;

    protected $table    = 't_buku_order_refs';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["table_name","column_name","value","matched_id"];

    public $columns     = ["table_name","column_name","value","matched_id"];
    public $columnsFull = ["table_name:text","column_name:text","value:integer","matched_id:integer"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["table_name","column_name","value","matched_id"];
    public $updateable  = ["table_name","column_name","value","matched_id"];
    public $searchable  = ["table_name","column_name","value","matched_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
