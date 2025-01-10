<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_premi extends Model
{   
    use ModelTrait;

    protected $table    = 't_premi';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_spk_angkutan_id","tol","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","status","total_premi","no_draft","no_premi","tgl","tarif_premi","hutang_supir","hutang_dibayar","total_premi_diterima"];

    public $columns     = ["id","t_spk_angkutan_id","tol","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","status","total_premi","no_draft","no_premi","tgl","tarif_premi","hutang_supir","hutang_dibayar","total_premi_diterima"];
    public $columnsFull = ["id:bigint","t_spk_angkutan_id:integer","tol:decimal","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","status:string:20","total_premi:decimal","no_draft:string:40","no_premi:string:40","tgl:date","tarif_premi:decimal","hutang_supir:decimal","hutang_dibayar:decimal","total_premi_diterima:decimal"];
    public $rules       = [];
    public $joins       = ["t_spk_angkutan.id=t_premi.t_spk_angkutan_id"];
    public $details     = ["t_premi_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tol","catatan"];
    public $createable  = ["t_spk_angkutan_id","tol","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","status","total_premi","no_draft","no_premi","tgl","tarif_premi","hutang_supir","hutang_dibayar","total_premi_diterima"];
    public $updateable  = ["t_spk_angkutan_id","tol","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","status","total_premi","no_draft","no_premi","tgl","tarif_premi","hutang_supir","hutang_dibayar","total_premi_diterima"];
    public $searchable  = ["id","t_spk_angkutan_id","tol","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","status","total_premi","no_draft","no_premi","tgl","tarif_premi","hutang_supir","hutang_dibayar","total_premi_diterima"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_premi_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_premi_d', 't_premi_id', 'id');
    }
    
    
    public function t_spk_angkutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_spk_angkutan', 't_spk_angkutan_id', 'id');
    }
}
