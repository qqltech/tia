<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_kontainer extends Model
{   
    use ModelTrait;

    protected $table    = 'm_kontainer';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["kode_kontainer","ukuran","jenis","tipe","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","status"];

    public $columns     = ["id","kode_kontainer","ukuran","jenis","tipe","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","status"];
    public $columnsFull = ["id:bigint","kode_kontainer:string:20","ukuran:string:5","jenis:string:250","tipe:string:30","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","status:boolean"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["status"];
    public $createable  = ["kode_kontainer","ukuran","jenis","tipe","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","status"];
    public $updateable  = ["kode_kontainer","ukuran","jenis","tipe","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","status"];
    public $searchable  = ["id","kode_kontainer","ukuran","jenis","tipe","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","status"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
