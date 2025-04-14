<?php

namespace App\Models\CustomModels;

class t_spk_angkutan extends \App\Models\BasicModels\t_spk_angkutan
{
    private $helper;
    private $approval;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
        $this->approval = getCore("Approval");
    }

    public $fileColumns = [
        /*file_column*/
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function transformRowData( array $row )
    {
        $req = app()->request;
        $data=[];
        if($req->getNoBukuOrder){
        $result1 = t_buku_order::where('id',$row['t_detail_npwp_container_1.t_buku_order_id'])->first();
        $result2 = t_buku_order::where('id',$row['t_detail_npwp_container_2.t_buku_order_id'])->first();
        // trigger_error(json_encode($result1));
        $data=[
            't_detail_npwp_container_1.no_buku_order'=>@$result1['no_buku_order'],
            't_detail_npwp_container_2.no_buku_order'=>@$result2['no_buku_order']
            ];
        }
        
        return array_merge( $row, $data );
    }
    

   public function createBefore($model, $arrayData, $metaData, $id = null)
{   

        if(false){

        // $id = request("id");
        $data_req = ['t_detail_npwp_container_1_id', 't_detail_npwp_container_2_id'];
        // $data_det_req = ['no_prefix','no_suffix','ukuran','jenis','sektor','depo','m_petugas_pengkont_id','m_petugas_pemasukan_id'];

        $messages = [];
        // $messages2 = [];
        $data =  $this->where("id", $id)->select('*')->first();
        $data_det =  \DB::table('t_buku_order_d_npwp')->where("t_buku_order_id", $id)->get();

        foreach($data_req as $d){
            if(@$data->$d == null){
                $messages[] = "$d";
            }
        }

        foreach(@$data_det_req ?? [] as $d){
            foreach($data_det as $dd){
                if($dd->$d == null){
                    $messages2[] = "$d";
                }
            }
        }

        $textMessage = "";
        if(count($messages)){
            foreach($messages as $d){
                $textMessage .= "$d, ";
            }
        }
        $textMessage2 = "";
        if(count($messages2)){
            foreach($messages2 as $d){
                $textMessage2 .= "$d, ";
            }
        }

        $text = " Header Perlu Diisi \n".$textMessage."Detail Perlu Diisi \n" . $textMessage2;

        if(count($messages) > 0 || count($messages2) > 0){
            return $this->helper->CustomResponse($text, 422);
        }
    // $messages = [];

    // Cek No SPK untuk container 1
    if (!empty($arrayData['t_detail_npwp_container_1_id'])) {
        $existingOrder1 = t_spk_angkutan::where('t_detail_npwp_container_1_id', $arrayData['t_detail_npwp_container_1_id'])
            ->select('no_spk')
            ->first();

        if ($existingOrder1) {
            $messages[] = "No Buku Order 1 sudah pernah dibuat di SPK No: " . $existingOrder1->no_spk;
        }
    }

    // Cek No SPK untuk container 2, kalau ada
    if (!empty($arrayData['t_detail_npwp_container_2_id'])) {
        $existingOrder2 = t_spk_angkutan::where('t_detail_npwp_container_2_id', $arrayData['t_detail_npwp_container_2_id'])
            ->select('no_spk')
            ->first();

        if ($existingOrder2) {
            $messages[] = "No Buku Order 2 sudah pernah dibuat di SPK No: " . $existingOrder2->no_spk;
        }
    }

    // Jika ada duplikasi, return dengan data kosong
    if (count($messages) > 0) {
        return [
            "error" => true,
            "message" => "Duplikasi Order Ditemukan",
            "warnings" => $messages,
            "confirm" => true,
            "data" => null // Pastikan ada key 'data' untuk menghindari undefined array key
        ];
    }
    }

    // Ambil ID supplier
    $getid = $this->get_supplier();
   
    // Siapkan data baru untuk disimpan
    $newData = [
        "no_spk" => $this->helper->generateNomor("SPK Angkutan"),
        "status" => "DRAFT",
        "m_supplier_id" => $getid
    ];
    
    $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
}




    // public function updateBefore( $model, $arrayData, $metaData, $id=null )
    // {
    //     // trigger_error(json_encode($arrayData));
    //     $checkDB = $this->where('no_spk',$arrayData['no_spk'])->first();
    //     $npwp_1 = $checkDB['t_detail_npwp_container_1_id'];
    //     $npwp_2 = $checkDB['t_detail_npwp_container_2_id'];
    //     $ad_npwp_1 =  $arrayData['t_detail_npwp_container_1_id'];
    //     $ad_npwp_2 = $arrayData['t_detail_npwp_container_2_id'];
    //     $checkDataExist1 = $this->where('t_detail_npwp_container_1_id', $ad_npwp_1)->count();
    //     $checkDataExist2 = $this->where('t_detail_npwp_container_2_id', $ad_npwp_2)->count();
    //     // trigger_error($checkDataExist1);
    //     // trigger_error(json_encode($checkDB['t_detail_npwp_container_2_id']));

    //     if($npwp_1 != $ad_npwp_1 || $npwp_1 == $ad_npwp_1){
    //         if($checkDataExist1 > 1){
    //             return [
    //                 "errors"=>['No. Order 1 sudah terpakai']
    //             ];
    //         }
    //     }
    //     if($ad_npwp_2 != null){    
    //         if($npwp_2 != null || $npwp_2 != $ad_npwp_2 || $npwp_2 == $ad_npwp_2){
    //             if($checkDataExist2 > 1){
    //                 return [
    //                     "errors"=>['No. Order 2 sudah terpakai']
    //                 ];
    //             }
    //         }
    //     }
        // $checkDuplicate = $this->IsDuplicate($arrayData);
    
        // // Kasih notifikasi kalau ada order yang duplikat, tapi tetap lanjut proses
        // if (count($checkDuplicate) > 0) {
        //     $this->helper->CustomResponse([
        //         "message" => "Peringatan: Ada No Buku Order yang sudah pernah dibuat!",
        //         "detail" => $checkDuplicate
        //     ], 200);
        // }
        
        // $newData=[
        //     "status" => $status
        // ];
        // $newArrayData = array_merge($arrayData,  $newData);
        // return [
        //     "model" => $model,
        //     "data" => $newArrayData,
        //     // "errors" => ['error1']
        // ];
    // }
    
    // public function scopeCheckOrder($model){
    //     $order1 = request('order1');
    //     $order2 = request('order2');

    //     $result1 = $model->where('t_detail_npwp_container_1_id',$order1)->get()->count();
    //     // return $result1;
        
    // }

    public function custom_checkOrder(){
        $order1 = request('order1');
        $order2 = request('order2');

        $result1 = t_spk_angkutan::where('t_detail_npwp_container_1_id',$order1)->count();
        $result2 = t_spk_angkutan::where('t_detail_npwp_container_2_id',$order1)->count();

        $result3 = t_spk_angkutan::where('t_detail_npwp_container_1_id',$order2)->count();
        $result4 = t_spk_angkutan::where('t_detail_npwp_container_2_id',$order2)->count();
        if($result1 > 1 || $result2 > 1){
            return [
                "hasil_npwp_1_1"=> $result1,
                "hasil_npwp_1_2"=>$result2,
                "hasil_npwp_2_1"=> $result3,
                "hasil_npwp_2_2"=>$result4,
                "is_used"=>true
            ];
        }
        if($result3 > 1 || $result4 > 1){
            return [
                "hasil_npwp_1_1"=> $result1,
                "hasil_npwp_1_2"=>$result2,
                "hasil_npwp_2_1"=> $result3,
                "hasil_npwp_2_2"=>$result4,
                "is_used"=>true
            ];
        }
        
    }

// public function custom_CheckBukuOrder($req)
// {
//     $id = request('id');
//     $data_req = ['t_detail_npwp_container_1_id', 't_detail_npwp_container_2_id'];

//     $data = $this->where("id", $id)->select($data_req)->first();

//     if (!$data) {
//         return ['message' => "Data tidak ditemukan"];
//     }

//     // Cek duplikasi dan kasih notifikasi kalau ada
//     $checkDuplicate = $this->IsDuplicate($data);

//     if (count($checkDuplicate) > 0) {
//         return [
//             'message' => "Peringatan: Ada No Buku Order yang sudah pernah dibuat!",
//             'detail' => $checkDuplicate
//         ];
//     }

//     return ['message' => "No Buku Order belum pernah dibuat, aman lanjut!"];
// }


// public function IsDuplicate($data)
// {
//     $messages = [];

//     foreach (['t_detail_npwp_container_1_id', 't_detail_npwp_container_2_id'] as $field) {
//         // Pastikan field ada sebelum dicek
//         if (!empty($data[$field])) {
//             $existingOrder = t_spk_angkutan::where($field, $data[$field])
//                 ->select('no_spk')
//                 ->first();

//             if ($existingOrder) {
//                 $messages[] = "No Buku Order sudah pernah dibuat di SPK No: " . $existingOrder->no_spk;
//             }
//         }
//     }

//     return $messages;
// }


    function get_supplier(){
        $getid = m_general::where('group','SUPPLIER_DEFAULT')->where('kode','SUPPLIER01')->first();
        $getsupplier = m_supplier::where('nama',$getid->deskripsi)->first();
        $result = $getsupplier->id??0;
        return $result;
    }

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true];
    }


    public function custom_send_approval()
    {
        $app = $this->createAppTicket(req("id"));
        if (!$app) {
            return $this->helper->customResponse(
                "Terjadi kesalahan, coba kembali nanti",
                400
            );
        }

        if (app()->request->header("Source") != "mobile") {
            $spd = t_spk_angkutan::find(req("id"));
            if ($spd) {
                $spd->update([
                    "status" => "IN APPROVAL",
                ]);
            }
        }

        return $this->helper->customResponse(
            "Permintaan approval berhasil dibuat"
        );
    }

    private function createAppTicket($id)
    {
        $tempId = $id;
        $trx = \DB::table('t_spk_angkutan')->find($tempId);
        $conf = [
            "app_name" => "APPROVAL SPK ANGKUTAN",
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan SPK Angkutan",
            "form_name" => "t_spk_angkutan",
            "trx_nomor" => $trx->no_spk,
            "trx_date" => Date("Y-m-d"),
            "trx_creator_id" => $trx->creator_id,
        ];

        $app = $this->approval->approvalCreateTicket($conf);
        if ($app) {
            return true;
        } else {
            return false;
        }
    }

    public function custom_progress($req)
    {
        // Start a database transaction
        \DB::beginTransaction();

        try {
            $conf = [
                "app_id" => $req->id,
                "app_type" => $req->type, // APPROVED, REVISED, REJECTED,
                "app_note" => $req->note, // alasan approve
            ];

            $app = $this->approval->approvalProgress($conf, true);
            if ($app->status) {
                $data = $this->find($app->trx_id);
                if ($app->finish) {
                    $data->update([
                        "status" => $req->type
                    ]);
                   
                } else {
                    $data->update([
                        "status" => "IN APPROVAL",
                    ]);
                }
            }

            \DB::commit();

            return $this->helper->customResponse("Proses approval berhasil");
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }
    }

    public function custom_detail($req)
    {
        $id = $req->id ?? 66;
        $data = $this->approval->approvalDetail($id);
        return $this->helper->customResponse("OK", 200, $data);
    }
    public function custom_log($req)
    {
        $conf = [
            "trx_id" => $req->id ?? 0,
            "trx_table" => $this->getTable(),
        ];
        $data = $this->approval->approvalLog($conf);
        return response($data);
    }

    public function scopeTipe($model){
        return $model->addSelect('t_buku_order_d_npwp.tipe','m_general.deskripsi')
        ->join('t_buku_order_d_npwp','t_buku_order_d_npwp.id',"$this->table.t_detail_npwp_container_1_id")
        ->join('set.m_general','m_general.id',"t_buku_order_d_npwp.tipe");
    }

    public function custom_print()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "PRINTED"]);
        return ["success" => true];
    }

    public function scopeWithDetail($model)
    {
        return $model->with('t_spk_bon_detail');
    }

    public function custom_getPrintData(){
        $req = app()->request;

        $user = \Auth::user();
        $user_print = \DB::table('default_users as du')->where('du.id', $user->id)
        ->leftJoin("set.m_kary as kary", 'kary.id', '=', 'du.m_employee_id')
        ->select('du.name', 'kary.nip')->first();

        // $user_print = (object) [
            // "name" => @$user->name,
            // "nip" => @$emp->name
        // ];

        $data = \DB::select("SELECT tsa.*,
        mg1.kode as chasis1_kode, mg2.kode as chasis2_kode, mg3.deskripsi as ukuran1_deskripsi,
        mk.nama as supir_nama, mk.nip as supir_nip,

        tbo.no_buku_order as no_buku_order,
        mcu.nama_perusahaan as customer_nama_perusahaan,
        mcu.kode as customer_kode,

        tbo2.no_buku_order as no_buku_order2,
        mcu2.nama_perusahaan as customer_nama_perusahaan2,
        mcu2.kode as customer_kode2,

        mg8.deskripsi as sektor1_deskripsi, mg9.deskripsi as sektor2_deskripsi,
        mg5.kode as trip_kode, mg4.kode as head_kode, mg10.deskripsi as waktu_in_deskripsi,
        mg11.deskripsi as waktu_out_deskripsi,
        mg12.deskripsi as isi_container_1_deskripsi,
        mg13.deskripsi as isi_container_2_deskripsi

        FROM t_spk_angkutan tsa
        LEFT JOIN set.m_general mg1 ON tsa.chasis = mg1.id
        left join set.m_general mg2 on tsa.chasis2 = mg2.id
        left join set.m_kary mk on tsa.supir = mk.id

        left join t_buku_order_d_npwp tbod on tsa.t_detail_npwp_container_1_id = tbod.id
        left join t_buku_order tbo on tbod.t_buku_order_id = tbo.id
        left join m_customer mcu on tbo.m_customer_id = mcu.id

        left join t_buku_order_d_npwp tbod2 on tsa.t_detail_npwp_container_2_id = tbod2.id
        left join t_buku_order tbo2 on tbod2.t_buku_order_id = tbo2.id
        left join m_customer mcu2 on tbo2.m_customer_id = mcu2.id

        left join set.m_general mg3 on tbod.ukuran = mg3.id
        left join set.m_general mg4 on tsa.head = mg4.id
        left join set.m_general mg5 on tsa.trip_id = mg5.id

        left join set.m_general mg8 on tsa.sektor1 = mg8.id
        left join set.m_general mg9 on tsa.sektor2 = mg9.id
        left join set.m_general mg10 on tsa.waktu_in = mg10.id
        left join set.m_general mg11 on tsa.waktu_out = mg11.id
        left join set.m_general mg12 on tsa.isi_container_1 = mg12.id
        left join set.m_general mg13 on tsa.isi_container_2 = mg13.id

        WHERE tsa.id = ?", [@$req->t_spk_id ?? 0]);

        $nospkd = \DB::select("SELECT tsbd.*
        from t_spk_bon_detail tsbd
        WHERE tsbd.t_spk_angkutan_id = ?", [@$req->t_spk_id ?? 0]);


        $count_spk = count(@$data ?? []);
        $currentDate = date("d/m/Y");
        $currentTime = date("H:i:s");

        return [
            "user_print" => @$user_print,
            "data" => @$data,
            "nospkd" => @$nospkd,
            "count_spk" => @$count_spk,
            "currentDate" => @$currentDate,
            "currentTime" => @$currentTime
        ];
    }
     
}
