<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class temp_bukti extends Model
{   
    use ModelTrait;

    protected $table    = 'temp_bukti';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["bukti","status"];

    public $columns     = ["id","bukti","status"];
    public $columnsFull = ["id:integer","bukti:string:255","status:boolean"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["bukti","status"];
    public $updateable  = ["bukti","status"];
    public $searchable  = ["bukti","status"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
