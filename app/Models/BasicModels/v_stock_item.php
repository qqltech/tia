<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class v_stock_item extends Model
{   
    use ModelTrait;

    protected $table    = 'v_stock_item';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["kode","m_item_id","nama_item","tipe_item","uom_id","uom_name","typemin","qty_stock","qty_awal","qty_in","qty_out","price","price_old","is_active"];

    public $columns     = ["id","kode","m_item_id","nama_item","tipe_item","uom_id","uom_name","typemin","qty_stock","qty_awal","qty_in","qty_out","price","price_old","is_active"];
    public $columnsFull = ["id:string","kode:string","m_item_id:string","nama_item:string","tipe_item:string","uom_id:string","uom_name:string","typemin:string","qty_stock:string","qty_awal:string","qty_in:string","qty_out:string","price:string","price_old:string","is_active:string"];
    public $rules       = "[]";
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = '[]';
    public $createable  = ["kode","m_item_id","nama_item","tipe_item","uom_id","uom_name","typemin","qty_stock","qty_awal","qty_in","qty_out","price","price_old","is_active"];
    public $updateable  = ["kode","m_item_id","nama_item","tipe_item","uom_id","uom_name","typemin","qty_stock","qty_awal","qty_in","qty_out","price","price_old","is_active"];
    public $searchable  = ["kode","m_item_id","nama_item","tipe_item","uom_id","uom_name","typemin","qty_stock","qty_awal","qty_in","qty_out","price","price_old","is_active"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
