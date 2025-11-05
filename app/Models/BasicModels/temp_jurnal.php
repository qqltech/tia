<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class temp_jurnal extends Model
{   
    use ModelTrait;

    protected $table    = 'temp_jurnal';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["perk_kode","debet","kredit","no_reference"];

    public $columns     = ["id","perk_kode","debet","kredit","no_reference"];
    public $columnsFull = ["id:integer","perk_kode:string:50","debet:float","kredit:float","no_reference:string:100"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["perk_kode","debet","kredit","no_reference"];
    public $updateable  = ["perk_kode","debet","kredit","no_reference"];
    public $searchable  = ["perk_kode","debet","kredit","no_reference"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
