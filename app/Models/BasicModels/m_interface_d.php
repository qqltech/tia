<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_interface_d extends Model
{   
    use ModelTrait;

    protected $table    = 'm_interface_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_interface_id","kode","nama","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];

    public $columns     = ["id","m_interface_id","kode","nama","catatan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_interface_id:bigint","kode:string:20","nama:string:100","catatan:text","creator_id:integer","last_editor_id:integer","deleted_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_interface.id=m_interface_d.m_interface_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["kode","nama"];
    public $createable  = ["m_interface_id","kode","nama","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $updateable  = ["m_interface_id","kode","nama","catatan","creator_id","last_editor_id","deleted_id","deleted_at"];
    public $searchable  = ["id","m_interface_id","kode","nama","catatan","creator_id","last_editor_id","deleted_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_interface() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_interface', 'm_interface_id', 'id');
    }
}
