<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_jasa extends Model
{   
    use ModelTrait;

    protected $table    = 'm_jasa';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["kode_jasa","kode","nama_jasa","catatan","satuan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","is_active"];

    public $columns     = ["id","kode_jasa","kode","nama_jasa","catatan","satuan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","is_active"];
    public $columnsFull = ["id:bigint","kode_jasa:string:10","kode:string:20","nama_jasa:string:100","catatan:text","satuan:string:5","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","is_active:boolean"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = ["m_customer_d_tarif","m_tarif_d_jasa","t_tagihan_d_tarif"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["is_active"];
    public $createable  = ["kode_jasa","kode","nama_jasa","catatan","satuan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","is_active"];
    public $updateable  = ["kode_jasa","kode","nama_jasa","catatan","satuan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","is_active"];
    public $searchable  = ["id","kode_jasa","kode","nama_jasa","catatan","satuan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","is_active"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
