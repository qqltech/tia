<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_spk_bon_detail extends Model
{   
    use ModelTrait;

    protected $table    = 't_spk_bon_detail';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_spk_angkutan_id","keterangan","nominal","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","t_spk_angkutan_id","keterangan","nominal","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_spk_angkutan_id:integer","keterangan:string:250","nominal:decimal","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_spk_angkutan.id=t_spk_bon_detail.t_spk_angkutan_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["keterangan","nominal"];
    public $createable  = ["t_spk_angkutan_id","keterangan","nominal","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["t_spk_angkutan_id","keterangan","nominal","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","t_spk_angkutan_id","keterangan","nominal","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_spk_angkutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_spk_angkutan', 't_spk_angkutan_id', 'id');
    }
}
