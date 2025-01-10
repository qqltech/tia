<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_tarif_d_jasa extends Model
{   
    use ModelTrait;

    protected $table    = 'm_tarif_d_jasa';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_tarif_id","m_jasa_id","tarif","catatan","ppn","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","m_tarif_id","m_jasa_id","tarif","catatan","ppn","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_tarif_id:integer","m_jasa_id:integer","tarif:decimal","catatan:text","ppn:boolean","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_tarif.id=m_tarif_d_jasa.m_tarif_id","m_jasa.id=m_tarif_d_jasa.m_jasa_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tarif"];
    public $createable  = ["m_tarif_id","m_jasa_id","tarif","catatan","ppn","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["m_tarif_id","m_jasa_id","tarif","catatan","ppn","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","m_tarif_id","m_jasa_id","tarif","catatan","ppn","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_tarif() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_tarif', 'm_tarif_id', 'id');
    }
    public function m_jasa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_jasa', 'm_jasa_id', 'id');
    }
}
