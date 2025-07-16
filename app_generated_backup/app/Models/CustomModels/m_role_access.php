<?php

namespace App\Models\CustomModels;

class m_role_access extends \App\Models\BasicModels\m_role_access
{
    public function __construct()
    {
        parent::__construct();
    }

    public $fileColumns = [
        /*file_column*/
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function relationRole(){
        return $this->belongsTo('App\Models\BasicModels\m_role', 'm_role_id', 'id');
    }

    public function custom_saveRole($req)
    {
        $id = (int)$req->id;
        $this->where('user_id', $id)->delete();
        foreach ($req->detail as $d) {
            $this->create(['user_id' => $id, 'm_role_id' => $d['m_role_id']]);
        }
        return response(["message" => "Add Role Access successfully"]);
    }

    public function scopeGetRoleAccess()
    {
        $id = request('user_id');
        return $this->where('user_id', $id)->with('relationRole')->with('user');
    }

}