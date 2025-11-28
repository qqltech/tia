<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class default_users extends Model
{   
    use ModelTrait;

    protected $table    = 'default_users';
    protected $guarded  = ['id'];
    protected $casts    = ['created_at'=> 'datetime:d-m-Y','updated_at'=>'datetime:d-m-Y'];
    protected $fillable = ["name","email","username","email_verified_at","password","comp_id","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","remember_token","created_at","updated_at","m_employee_id","tipe","catatan"];

    public $columns     = ["id","name","email","username","email_verified_at","password","comp_id","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","remember_token","created_at","updated_at","m_employee_id","tipe","catatan"];
    public $columnsFull = ["id:bigint","name:string:191","email:string:191","username:string:60","email_verified_at:datetime","password:string:191","comp_id:bigint","is_active:boolean","creator_id:integer","last_editor_id:integer","edited_at:datetime","deletor_id:integer","deleted_at:datetime","remember_token:string:100","created_at:datetime","updated_at:datetime","m_employee_id:integer","tipe:string:15","catatan:text"];
    public $rules       = [];
    public $joins       = ["set.m_kary.id=default_users.m_employee_id"];
    public $details     = ["default_users_socialite","set.m_users_d"];
    public $heirs       = ["generate_num","generate_num","generate_num_det","generate_num_det","generate_num_log","generate_num_log","generate_num_type","generate_num_type","set.generate_approval","set.generate_approval","set.generate_approval","set.generate_approval_det","set.generate_approval_det","set.generate_approval_det","set.generate_approval_det","set.generate_approval_log","set.generate_approval_log","set.generate_approval_log","set.m_approval_det","set.m_approval_det","set.m_approval_det","set.m_role_access"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [
    "email"=> "unique:default_users,email",
    "username"=> "unique:default_users,username"
	];
    public $required    = ["is_active","tipe"];
    public $createable  = ["name","email","username","email_verified_at","password","comp_id","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","remember_token","created_at","updated_at","m_employee_id","tipe","catatan"];
    public $updateable  = ["name","email","username","email_verified_at","password","comp_id","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","remember_token","created_at","updated_at","m_employee_id","tipe","catatan"];
    public $searchable  = ["name","email","username","email_verified_at","password","comp_id","is_active","creator_id","last_editor_id","edited_at","deletor_id","deleted_at","remember_token","created_at","updated_at","m_employee_id","tipe","catatan"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function default_users_socialite() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\default_users_socialite', 'default_users_id', 'id');
    }
    public function m_users_d() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_users_d', 'default_users_id', 'id');
    }
    
    
    public function m_employee() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\set.m_kary', 'm_employee_id', 'id');
    }
}
