<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_tarif_komisi_undername_d extends Model
{   
    use ModelTrait;

    protected $table    = 'm_tarif_komisi_undername_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_tarif_komisi_undername_id","nilai_awal","nilai_akhir","persentase","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","m_tarif_komisi_undername_id","nilai_awal","nilai_akhir","persentase","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_tarif_komisi_undername_id:integer","nilai_awal:decimal","nilai_akhir:decimal","persentase:decimal","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_tarif_komisi_undername.id=m_tarif_komisi_undername_d.m_tarif_komisi_undername_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["m_tarif_komisi_undername_id","nilai_awal","nilai_akhir","persentase","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["m_tarif_komisi_undername_id","nilai_awal","nilai_akhir","persentase","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","m_tarif_komisi_undername_id","nilai_awal","nilai_akhir","persentase","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_tarif_komisi_undername() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_tarif_komisi_undername', 'm_tarif_komisi_undername_id', 'id');
    }
}
