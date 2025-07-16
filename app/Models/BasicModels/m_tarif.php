<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_tarif extends Model
{   
    use ModelTrait;

    protected $table    = 'm_tarif';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_tarif","tipe_tarif","m_customer_id","sektor","jenis","is_active","ukuran_kontainer","tarif_sewa","tarif_sewa_diskon","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","tt_elektronik","tarif_ppjk"];

    public $columns     = ["id","no_tarif","tipe_tarif","m_customer_id","sektor","jenis","is_active","ukuran_kontainer","tarif_sewa","tarif_sewa_diskon","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","tt_elektronik","tarif_ppjk"];
    public $columnsFull = ["id:bigint","no_tarif:string:20","tipe_tarif:string:20","m_customer_id:integer","sektor:integer","jenis:integer","is_active:boolean","ukuran_kontainer:integer","tarif_sewa:decimal","tarif_sewa_diskon:decimal","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","tt_elektronik:string:191","tarif_ppjk:decimal"];
    public $rules       = [];
    public $joins       = ["m_customer.id=m_tarif.m_customer_id","set.m_general.id=m_tarif.sektor","set.m_general.id=m_tarif.jenis"];
    public $details     = ["m_tarif_d_jasa","m_tarif_d_lain_lain"];
    public $heirs       = ["t_tagihan_d_tarif","m_customer_d_tarif"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_customer_id","jenis","is_active","tarif_sewa","tarif_sewa_diskon"];
    public $createable  = ["no_tarif","tipe_tarif","m_customer_id","sektor","jenis","is_active","ukuran_kontainer","tarif_sewa","tarif_sewa_diskon","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","tt_elektronik","tarif_ppjk"];
    public $updateable  = ["no_tarif","tipe_tarif","m_customer_id","sektor","jenis","is_active","ukuran_kontainer","tarif_sewa","tarif_sewa_diskon","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","tt_elektronik","tarif_ppjk"];
    public $searchable  = ["id","no_tarif","tipe_tarif","m_customer_id","sektor","jenis","is_active","ukuran_kontainer","tarif_sewa","tarif_sewa_diskon","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","tt_elektronik","tarif_ppjk"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_tarif_d_jasa() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_tarif_d_jasa', 'm_tarif_id', 'id');
    }
    public function m_tarif_d_lain_lain() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_tarif_d_lain_lain', 'm_tarif_id', 'id');
    }
    
    
    public function m_customer() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_customer', 'm_customer_id', 'id');
    }
    public function sektor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'sektor', 'id');
    }
    public function jenis() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis', 'id');
    }
}
