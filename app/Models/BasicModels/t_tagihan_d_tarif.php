<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_tagihan_d_tarif extends Model
{   
    use ModelTrait;

    protected $table    = 't_tagihan_d_tarif';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_tagihan_id","m_jasa_id","m_tarif_id","satuan","tarif","catatan","ppn","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_tagihan_id","m_jasa_id","m_tarif_id","satuan","tarif","catatan","ppn","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_tagihan_id:integer","m_jasa_id:integer","m_tarif_id:integer","satuan:float","tarif:decimal","catatan:text","ppn:boolean","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_tagihan.id=t_tagihan_d_tarif.t_tagihan_id","m_jasa.id=t_tagihan_d_tarif.m_jasa_id","m_tarif.id=t_tagihan_d_tarif.m_tarif_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_jasa_id"];
    public $createable  = ["t_tagihan_id","m_jasa_id","m_tarif_id","satuan","tarif","catatan","ppn","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_tagihan_id","m_jasa_id","m_tarif_id","satuan","tarif","catatan","ppn","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_tagihan_id","m_jasa_id","m_tarif_id","satuan","tarif","catatan","ppn","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_tagihan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_tagihan', 't_tagihan_id', 'id');
    }
    public function m_jasa() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_jasa', 'm_jasa_id', 'id');
    }
    public function m_tarif() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_tarif', 'm_tarif_id', 'id');
    }
}
