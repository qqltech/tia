<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_spk_lain extends Model
{   
    use ModelTrait;

    protected $table    = 't_spk_lain';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_spk","kode","lokasi_stuffing","jenis","sangu_tia","sangu_free","status","tarif_genzet","ganti_solar_sangu","ganti_solar_tag","kilometer","catatan","creator_id","last_editor_id","delete_id","delete_at","buku_order_id"];

    public $columns     = ["id","no_spk","kode","lokasi_stuffing","jenis","sangu_tia","sangu_free","status","tarif_genzet","ganti_solar_sangu","ganti_solar_tag","kilometer","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","buku_order_id"];
    public $columnsFull = ["id:bigint","no_spk:string:191","kode:string:191","lokasi_stuffing:integer","jenis:integer","sangu_tia:float","sangu_free:float","status:string:20","tarif_genzet:float","ganti_solar_sangu:float","ganti_solar_tag:float","kilometer:integer","catatan:text","creator_id:integer","last_editor_id:integer","delete_id:integer","delete_at:datetime","created_at:datetime","updated_at:datetime","buku_order_id:integer"];
    public $rules       = [];
    public $joins       = ["m_lokasistuffing.id=t_spk_lain.lokasi_stuffing","set.m_general.id=t_spk_lain.jenis","t_buku_order.id=t_spk_lain.buku_order_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["no_spk","lokasi_stuffing","jenis","sangu_tia","sangu_free","status","tarif_genzet","ganti_solar_sangu","ganti_solar_tag","kilometer"];
    public $createable  = ["no_spk","kode","lokasi_stuffing","jenis","sangu_tia","sangu_free","status","tarif_genzet","ganti_solar_sangu","ganti_solar_tag","kilometer","catatan","creator_id","last_editor_id","delete_id","delete_at","buku_order_id"];
    public $updateable  = ["no_spk","kode","lokasi_stuffing","jenis","sangu_tia","sangu_free","status","tarif_genzet","ganti_solar_sangu","ganti_solar_tag","kilometer","catatan","creator_id","last_editor_id","delete_id","delete_at","buku_order_id"];
    public $searchable  = ["id","no_spk","kode","lokasi_stuffing","jenis","sangu_tia","sangu_free","status","tarif_genzet","ganti_solar_sangu","ganti_solar_tag","kilometer","catatan","creator_id","last_editor_id","delete_id","delete_at","created_at","updated_at","buku_order_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function lokasi_stuffing() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_lokasistuffing', 'lokasi_stuffing', 'id');
    }
    public function jenis() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_general', 'jenis', 'id');
    }
    public function buku_order() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_buku_order', 'buku_order_id', 'id');
    }
}
