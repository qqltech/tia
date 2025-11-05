<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class temp_bkk_non_orders extends Model
{   
    use ModelTrait;

    protected $table    = 'temp_bkk_non_orders';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["bukti","tgl","description","petugas","debet","kredit","kode_tr"];

    public $columns     = ["bukti","tgl","description","petugas","debet","kredit","kode_tr"];
    public $columnsFull = ["bukti:string:512","tgl:string:512","description:string:512","petugas:string:512","debet:string:512","kredit:string:512","kode_tr:string:512"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["bukti","tgl","description","petugas","debet","kredit","kode_tr"];
    public $updateable  = ["bukti","tgl","description","petugas","debet","kredit","kode_tr"];
    public $searchable  = ["bukti","tgl","description","petugas","debet","kredit","kode_tr"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
