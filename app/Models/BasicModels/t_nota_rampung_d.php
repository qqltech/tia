<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_nota_rampung_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_nota_rampung_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_nota_rampung_id","no_kontainer","ukuran","jenis","sektor","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","t_buku_order_d_npwp_id","lolo","m2","ow","m3","m4","m5","plg_mon","ge","strp_stuf","canc_doc","closing_container","batal_muat","spek_kont","vgm","mob","denda_koreksi","materai","by_adm_nr","nr","denda_sp"];

    public $columns     = ["id","t_nota_rampung_id","no_kontainer","ukuran","jenis","sektor","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","t_buku_order_d_npwp_id","lolo","m2","ow","m3","m4","m5","plg_mon","ge","strp_stuf","canc_doc","closing_container","batal_muat","spek_kont","vgm","mob","denda_koreksi","materai","by_adm_nr","nr","denda_sp"];
    public $columnsFull = ["id:bigint","t_nota_rampung_id:integer","no_kontainer:string:30","ukuran:integer","jenis:integer","sektor:integer","catatan:text","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","t_buku_order_d_npwp_id:integer","lolo:decimal","m2:decimal","ow:decimal","m3:decimal","m4:decimal","m5:decimal","plg_mon:decimal","ge:decimal","strp_stuf:decimal","canc_doc:decimal","closing_container:decimal","batal_muat:decimal","spek_kont:string:100","vgm:decimal","mob:decimal","denda_koreksi:decimal","materai:decimal","by_adm_nr:decimal","nr:string:191","denda_sp:decimal"];
    public $rules       = [];
    public $joins       = ["t_nota_rampung.id=t_nota_rampung_d.t_nota_rampung_id","set.m_general.id=t_nota_rampung_d.jenis","set.m_general.id=t_nota_rampung_d.sektor","t_buku_order_d_npwp.id=t_nota_rampung_d.t_buku_order_d_npwp_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["ukuran","jenis","sektor"];
    public $createable  = ["t_nota_rampung_id","no_kontainer","ukuran","jenis","sektor","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","t_buku_order_d_npwp_id","lolo","m2","ow","m3","m4","m5","plg_mon","ge","strp_stuf","canc_doc","closing_container","batal_muat","spek_kont","vgm","mob","denda_koreksi","materai","by_adm_nr","nr","denda_sp"];
    public $updateable  = ["t_nota_rampung_id","no_kontainer","ukuran","jenis","sektor","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","t_buku_order_d_npwp_id","lolo","m2","ow","m3","m4","m5","plg_mon","ge","strp_stuf","canc_doc","closing_container","batal_muat","spek_kont","vgm","mob","denda_koreksi","materai","by_adm_nr","nr","denda_sp"];
    public $searchable  = ["id","t_nota_rampung_id","no_kontainer","ukuran","jenis","sektor","catatan","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","t_buku_order_d_npwp_id","lolo","m2","ow","m3","m4","m5","plg_mon","ge","strp_stuf","canc_doc","closing_container","batal_muat","spek_kont","vgm","mob","denda_koreksi","materai","by_adm_nr","nr","denda_sp"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_nota_rampung() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_nota_rampung', 't_nota_rampung_id', 'id');
    }
    public function jenis() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis', 'id');
    }
    public function sektor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'sektor', 'id');
    }
    public function t_buku_order_d_npwp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order_d_npwp', 't_buku_order_d_npwp_id', 'id');
    }
}
