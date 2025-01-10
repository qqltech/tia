<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_customer_group extends Model
{   
    use ModelTrait;

    protected $table    = 'm_customer_group';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["kode","nama","sisa_kredit","catatan","is_active","kredit_limit","total_kredit","creator_id","last_editor_id","delete_id","delete_at"];

    public $columns     = ["id","kode","nama","sisa_kredit","catatan","is_active","kredit_limit","total_kredit","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","kode:string:191","nama:string:100","sisa_kredit:float","catatan:text","is_active:boolean","kredit_limit:float","total_kredit:float","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = ["m_customer"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama","is_active","kredit_limit"];
    public $createable  = ["kode","nama","sisa_kredit","catatan","is_active","kredit_limit","total_kredit","creator_id","last_editor_id","delete_id","delete_at"];
    public $updateable  = ["kode","nama","sisa_kredit","catatan","is_active","kredit_limit","total_kredit","creator_id","last_editor_id","delete_id","delete_at"];
    public $searchable  = ["id","kode","nama","sisa_kredit","catatan","is_active","kredit_limit","total_kredit","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
