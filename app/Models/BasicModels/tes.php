<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class tes extends Model
{   
    use ModelTrait;

    protected $table    = 'tes';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","cust_name","cust_addr","subtotal","creator_id","last_editor_id"];

    public $columns     = ["id","nomor","cust_name","cust_addr","subtotal","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nomor:string:100","cust_name:string:100","cust_addr:string:200","subtotal:decimal","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = ["tes_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["cust_name","cust_addr","subtotal"];
    public $createable  = ["nomor","cust_name","cust_addr","subtotal","creator_id","last_editor_id"];
    public $updateable  = ["nomor","cust_name","cust_addr","subtotal","creator_id","last_editor_id"];
    public $searchable  = ["id","nomor","cust_name","cust_addr","subtotal","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function tes_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\tes_d', 'tes_id', 'id');
    }
    
    
}
