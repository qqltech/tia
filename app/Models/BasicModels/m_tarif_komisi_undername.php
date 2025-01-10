<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_tarif_komisi_undername extends Model
{   
    use ModelTrait;

    protected $table    = 'm_tarif_komisi_undername';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["kode_tarif_komisi_undername","is_active","tipe_tarif","m_cust_id","tarif_komisi","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","kode_tarif_komisi_undername","is_active","tipe_tarif","m_cust_id","tarif_komisi","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","kode_tarif_komisi_undername:string:50","is_active:boolean","tipe_tarif:string:50","m_cust_id:integer","tarif_komisi:decimal","keterangan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_customer.id=m_tarif_komisi_undername.m_cust_id"];
    public $details     = ["m_tarif_komisi_undername_d"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tipe_tarif","m_cust_id","tarif_komisi"];
    public $createable  = ["kode_tarif_komisi_undername","is_active","tipe_tarif","m_cust_id","tarif_komisi","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["kode_tarif_komisi_undername","is_active","tipe_tarif","m_cust_id","tarif_komisi","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","kode_tarif_komisi_undername","is_active","tipe_tarif","m_cust_id","tarif_komisi","keterangan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_tarif_komisi_undername_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_tarif_komisi_undername_d', 'm_tarif_komisi_undername_id', 'id');
    }
    
    
    public function m_cust() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'm_cust_id', 'id');
    }
}
