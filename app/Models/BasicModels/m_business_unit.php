<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_business_unit extends Model
{   
    use ModelTrait;

    protected $table    = 'set.m_business_unit';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["kode","nama","alamat","kota","kodepos","catatan","is_active","npwp","provinsi","kecamatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];

    public $columns     = ["id","kode","nama","alamat","kota","kodepos","catatan","is_active","npwp","provinsi","kecamatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","kode:string:191","nama:string:191","alamat:text","kota:string:191","kodepos:string:191","catatan:text","is_active:boolean","npwp:string:191","provinsi:string:191","kecamatan:string:191","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = ["t_bkk","t_purchase_order","t_bkm_non_order","t_bkk_non_order","t_bkm"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","alamat","kota","kodepos","is_active","npwp","provinsi","kecamatan"];
    public $createable  = ["kode","nama","alamat","kota","kodepos","catatan","is_active","npwp","provinsi","kecamatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $updateable  = ["kode","nama","alamat","kota","kodepos","catatan","is_active","npwp","provinsi","kecamatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $searchable  = ["kode","nama","alamat","kota","kodepos","catatan","is_active","npwp","provinsi","kecamatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
