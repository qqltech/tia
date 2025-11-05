<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_premi_batal extends Model
{   
    use ModelTrait;

    protected $table    = 't_premi_batal';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_draft","no_premi_batal","t_spk_angkutan_id","tgl","status","tarif_premi","tol","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","no_draft","no_premi_batal","t_spk_angkutan_id","tgl","status","tarif_premi","tol","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_draft:string:40","no_premi_batal:string:40","t_spk_angkutan_id:integer","tgl:date","status:string:100","tarif_premi:decimal","tol:decimal","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_spk_angkutan.id=t_premi_batal.t_spk_angkutan_id"];
    public $details     = ["t_premi_batal_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tol","catatan"];
    public $createable  = ["no_draft","no_premi_batal","t_spk_angkutan_id","tgl","status","tarif_premi","tol","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["no_draft","no_premi_batal","t_spk_angkutan_id","tgl","status","tarif_premi","tol","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","no_draft","no_premi_batal","t_spk_angkutan_id","tgl","status","tarif_premi","tol","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_premi_batal_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_premi_batal_d', 't_premi_id', 'id');
    }
    
    
    public function t_spk_angkutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_spk_angkutan', 't_spk_angkutan_id', 'id');
    }
}
