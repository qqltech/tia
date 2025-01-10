<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_customer_d_tarif extends Model
{   
    use ModelTrait;

    protected $table    = 'm_customer_d_tarif';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_jasa_id","m_tarif_id","satuan","tarif","catatan","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","m_jasa_id","m_tarif_id","satuan","tarif","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_jasa_id:integer","m_tarif_id:integer","satuan:string:20","tarif:float","catatan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_jasa.id=m_customer_d_tarif.m_jasa_id","m_tarif.id=m_customer_d_tarif.m_tarif_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_jasa_id","m_tarif_id","satuan","tarif","catatan"];
    public $createable  = ["m_jasa_id","m_tarif_id","satuan","tarif","catatan","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["m_jasa_id","m_tarif_id","satuan","tarif","catatan","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","m_jasa_id","m_tarif_id","satuan","tarif","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_jasa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_jasa', 'm_jasa_id', 'id');
    }
    public function m_tarif() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_tarif', 'm_tarif_id', 'id');
    }
}
