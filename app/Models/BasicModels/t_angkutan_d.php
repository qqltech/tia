<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_angkutan_d extends Model
{   
    use ModelTrait;

    protected $table    = 't_angkutan_d';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_angkutan_id","t_spk_id","depo","sektor","tanggal_out","jam_out","free","tarif_los_cargo","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","waktu_out","angkutan_pelabuhan","nama_angkutan_id","tanggal_in","jam_in","waktu_in","staple","pelabuhan","no_container","biaya_lain_lain","tgl_stuffing","no_angkutan","head","catatan","trip"];

    public $columns     = ["id","t_angkutan_id","t_spk_id","depo","sektor","tanggal_out","jam_out","free","tarif_los_cargo","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","waktu_out","angkutan_pelabuhan","nama_angkutan_id","tanggal_in","jam_in","waktu_in","staple","pelabuhan","no_container","biaya_lain_lain","tgl_stuffing","no_angkutan","head","catatan","trip"];
    public $columnsFull = ["id:bigint","t_angkutan_id:integer","t_spk_id:integer","depo:integer","sektor:integer","tanggal_out:date","jam_out:time","free:integer","tarif_los_cargo:decimal","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","created_at:datetime","updated_at:datetime","waktu_out:string:20","angkutan_pelabuhan:string:40","nama_angkutan_id:integer","tanggal_in:date","jam_in:time","waktu_in:string:20","staple:string:191","pelabuhan:string:191","no_container:string:191","biaya_lain_lain:decimal","tgl_stuffing:date","no_angkutan:string:191","head:integer","catatan:text","trip:integer"];
    public $rules       = [];
    public $joins       = ["t_angkutan.id=t_angkutan_d.t_angkutan_id","t_spk_angkutan.id=t_angkutan_d.t_spk_id","set.m_general.id=t_angkutan_d.depo","set.m_general.id=t_angkutan_d.sektor","m_supplier.id=t_angkutan_d.nama_angkutan_id","set.m_general.id=t_angkutan_d.head","set.m_general.id=t_angkutan_d.trip"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["free","tarif_los_cargo"];
    public $createable  = ["t_angkutan_id","t_spk_id","depo","sektor","tanggal_out","jam_out","free","tarif_los_cargo","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","waktu_out","angkutan_pelabuhan","nama_angkutan_id","tanggal_in","jam_in","waktu_in","staple","pelabuhan","no_container","biaya_lain_lain","tgl_stuffing","no_angkutan","head","catatan","trip"];
    public $updateable  = ["t_angkutan_id","t_spk_id","depo","sektor","tanggal_out","jam_out","free","tarif_los_cargo","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","waktu_out","angkutan_pelabuhan","nama_angkutan_id","tanggal_in","jam_in","waktu_in","staple","pelabuhan","no_container","biaya_lain_lain","tgl_stuffing","no_angkutan","head","catatan","trip"];
    public $searchable  = ["id","t_angkutan_id","t_spk_id","depo","sektor","tanggal_out","jam_out","free","tarif_los_cargo","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","created_at","updated_at","waktu_out","angkutan_pelabuhan","nama_angkutan_id","tanggal_in","jam_in","waktu_in","staple","pelabuhan","no_container","biaya_lain_lain","tgl_stuffing","no_angkutan","head","catatan","trip"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_angkutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_angkutan', 't_angkutan_id', 'id');
    }
    public function t_spk() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_spk_angkutan', 't_spk_id', 'id');
    }
    public function depo() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'depo', 'id');
    }
    public function sektor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'sektor', 'id');
    }
    public function nama_angkutan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_supplier', 'nama_angkutan_id', 'id');
    }
    public function head() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'head', 'id');
    }
    public function trip() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'trip', 'id');
    }
}
