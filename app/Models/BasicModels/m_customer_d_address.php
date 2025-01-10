<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_customer_d_address extends Model
{   
    use ModelTrait;

    protected $table    = 'm_customer_d_address';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_customer_id","lokasi_stuff","alamat","is_active","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","m_customer_id","lokasi_stuff","alamat","is_active","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_customer_id:integer","lokasi_stuff:string:191","alamat:text","is_active:boolean","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_customer.id=m_customer_d_address.m_customer_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_customer_id","lokasi_stuff","alamat","is_active"];
    public $createable  = ["m_customer_id","lokasi_stuff","alamat","is_active","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["m_customer_id","lokasi_stuff","alamat","is_active","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","m_customer_id","lokasi_stuff","alamat","is_active","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'm_customer_id', 'id');
    }
}
