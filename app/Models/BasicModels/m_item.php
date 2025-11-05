<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_item extends Model
{   
    use ModelTrait;

    protected $table    = 'm_item';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["kode","tanggal","nama_item","tipe_item","is_active","creator_id","last_editor_id","deleted_id","deleted_at","uom_id"];

    public $columns     = ["id","kode","tanggal","nama_item","tipe_item","is_active","creator_id","last_editor_id","created_at","updated_at","deleted_id","deleted_at","uom_id"];
    public $columnsFull = ["id:bigint","kode:string:191","tanggal:date","nama_item:string:100","tipe_item:string:30","is_active:boolean","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime","deleted_id:integer","deleted_at:datetime","uom_id:bigint"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=m_item.uom_id"];
    public $details     = [];
    public $heirs       = ["r_stock_d","t_confirm_asset","t_lpb_d","t_pemakaian_stok_d","t_purchase_order_d","tes_d"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [
    "kode"=> "unique:m_item,kode"
	];
    public $required    = ["tanggal","nama_item","tipe_item","is_active","uom_id"];
    public $createable  = ["kode","tanggal","nama_item","tipe_item","is_active","creator_id","last_editor_id","deleted_id","deleted_at","uom_id"];
    public $updateable  = ["kode","tanggal","nama_item","tipe_item","is_active","creator_id","last_editor_id","deleted_id","deleted_at","uom_id"];
    public $searchable  = ["id","kode","tanggal","nama_item","tipe_item","is_active","creator_id","last_editor_id","created_at","updated_at","deleted_id","deleted_at","uom_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function uom() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'uom_id', 'id');
    }
}
