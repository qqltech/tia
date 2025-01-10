<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_lokasistuffing extends Model
{   
    use ModelTrait;

    protected $table    = 'm_lokasistuffing';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["kode_lokasi","nama_lokasi","alamat","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","is_active"];

    public $columns     = ["id","kode_lokasi","nama_lokasi","alamat","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","is_active"];
    public $columnsFull = ["id:bigint","kode_lokasi:string:20","nama_lokasi:string:100","alamat:string:250","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","is_active:boolean"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = ["t_spk_lain"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama_lokasi","alamat","is_active"];
    public $createable  = ["kode_lokasi","nama_lokasi","alamat","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","is_active"];
    public $updateable  = ["kode_lokasi","nama_lokasi","alamat","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","is_active"];
    public $searchable  = ["id","kode_lokasi","nama_lokasi","alamat","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","is_active"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
