<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_ganti_solar extends Model
{   
    use ModelTrait;

    protected $table    = 't_ganti_solar';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_spk_angkutan_id","status","tgl","no_container_1","no_container_2","nominal","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","t_spk_angkutan_id","status","tgl","no_container_1","no_container_2","nominal","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_spk_angkutan_id:integer","status:string:191","tgl:date","no_container_1:string:191","no_container_2:string:191","nominal:decimal","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_spk_angkutan.id=t_ganti_solar.t_spk_angkutan_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_spk_angkutan_id","no_container_1","no_container_2"];
    public $createable  = ["t_spk_angkutan_id","status","tgl","no_container_1","no_container_2","nominal","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["t_spk_angkutan_id","status","tgl","no_container_1","no_container_2","nominal","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","t_spk_angkutan_id","status","tgl","no_container_1","no_container_2","nominal","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_spk_angkutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_spk_angkutan', 't_spk_angkutan_id', 'id');
    }
}
