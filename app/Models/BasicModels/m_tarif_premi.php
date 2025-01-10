<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_tarif_premi extends Model
{   
    use ModelTrait;

    protected $table    = 'm_tarif_premi';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_tarif_premi","sektor_id","tipe_kontainer","no_head","ukuran_container","trip","tagihan","premi","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","no_tarif_premi","sektor_id","tipe_kontainer","no_head","ukuran_container","trip","tagihan","premi","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_tarif_premi:string:50","sektor_id:integer","tipe_kontainer:integer","no_head:integer","ukuran_container:integer","trip:integer","tagihan:decimal","premi:decimal","is_active:boolean","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=m_tarif_premi.sektor_id","set.m_general.id=m_tarif_premi.tipe_kontainer","set.m_general.id=m_tarif_premi.no_head","set.m_general.id=m_tarif_premi.ukuran_container","set.m_general.id=m_tarif_premi.trip"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["sektor_id","tipe_kontainer","no_head","ukuran_container","trip","tagihan","premi"];
    public $createable  = ["no_tarif_premi","sektor_id","tipe_kontainer","no_head","ukuran_container","trip","tagihan","premi","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["no_tarif_premi","sektor_id","tipe_kontainer","no_head","ukuran_container","trip","tagihan","premi","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","no_tarif_premi","sektor_id","tipe_kontainer","no_head","ukuran_container","trip","tagihan","premi","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function sektor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'sektor_id', 'id');
    }
    public function tipe_kontainer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'tipe_kontainer', 'id');
    }
    public function no_head() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'no_head', 'id');
    }
    public function ukuran_container() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'ukuran_container', 'id');
    }
    public function trip() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'trip', 'id');
    }
}
