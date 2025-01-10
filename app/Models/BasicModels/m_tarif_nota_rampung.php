<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_tarif_nota_rampung extends Model
{   
    use ModelTrait;

    protected $table    = 'm_tarif_nota_rampung';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_tarif","is_active","kode_pelabuhan","ukuran_container","jenis_container","tarif_lolo","tarif_m2","tarif_m3","tarif_m4","tarif_m5","tarif_ow","tarif_plg_mon","tarif_ge","tarif_container_doc","tarif_strtp_stuff","tarif_batal_muat_pindah","tarif_closing_container","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];

    public $columns     = ["id","no_tarif","is_active","kode_pelabuhan","ukuran_container","jenis_container","tarif_lolo","tarif_m2","tarif_m3","tarif_m4","tarif_m5","tarif_ow","tarif_plg_mon","tarif_ge","tarif_container_doc","tarif_strtp_stuff","tarif_batal_muat_pindah","tarif_closing_container","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_tarif:string:30","is_active:boolean","kode_pelabuhan:integer","ukuran_container:integer","jenis_container:integer","tarif_lolo:decimal","tarif_m2:decimal","tarif_m3:decimal","tarif_m4:decimal","tarif_m5:decimal","tarif_ow:decimal","tarif_plg_mon:decimal","tarif_ge:decimal","tarif_container_doc:decimal","tarif_strtp_stuff:decimal","tarif_batal_muat_pindah:decimal","tarif_closing_container:decimal","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["set.m_general.id=m_tarif_nota_rampung.kode_pelabuhan","set.m_general.id=m_tarif_nota_rampung.ukuran_container","set.m_general.id=m_tarif_nota_rampung.jenis_container"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["no_tarif","is_active","kode_pelabuhan","ukuran_container","jenis_container","tarif_lolo","tarif_m2","tarif_m3","tarif_m4","tarif_m5","tarif_ow","tarif_plg_mon","tarif_ge","tarif_container_doc","tarif_strtp_stuff","tarif_batal_muat_pindah","tarif_closing_container","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $updateable  = ["no_tarif","is_active","kode_pelabuhan","ukuran_container","jenis_container","tarif_lolo","tarif_m2","tarif_m3","tarif_m4","tarif_m5","tarif_ow","tarif_plg_mon","tarif_ge","tarif_container_doc","tarif_strtp_stuff","tarif_batal_muat_pindah","tarif_closing_container","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at"];
    public $searchable  = ["id","no_tarif","is_active","kode_pelabuhan","ukuran_container","jenis_container","tarif_lolo","tarif_m2","tarif_m3","tarif_m4","tarif_m5","tarif_ow","tarif_plg_mon","tarif_ge","tarif_container_doc","tarif_strtp_stuff","tarif_batal_muat_pindah","tarif_closing_container","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function kode_pelabuhan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'kode_pelabuhan', 'id');
    }
    public function ukuran_container() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'ukuran_container', 'id');
    }
    public function jenis_container() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis_container', 'id');
    }
}
