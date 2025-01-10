<?php

namespace App\Models\CustomModels;
use Carbon\Carbon;

class t_pembayaran_piutang extends \App\Models\BasicModels\t_pembayaran_piutang
{   
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    function onPost($tagihan_id, $arrayData){
        \DB::table('t_tagihan')->where('id', $tagihan_id)->update([
            'piutang' => $arrayData['sisa_piutang']
        ]);
    }

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $req = app()->request;
        
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft P Piutang"),
            "no_pembayaran" => $this->helper->generateNomor("No P Piutang"),
            "tanggal" => date("Y-m-d"),
            "status" => $req->is_post ? "POST" :"DRAFT",
        ];

        if($req->is_post){
            $detail = $req->t_pembayaran_piutang_d;
            foreach($detail as $dt){
                $this->onPost($dt['t_tagihan_id'], $dt);
            }
        }

        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function updateBefore( $model, $arrayData, $metaData, $id=null )
    {
        $req = app()->request;

        $newArrayData  = array_merge( $arrayData,[
            "status" => $req->is_post ? "POST" : $arrayData['status'],
        ] );
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function custom_post($req){
        $data = $this->where('id', $req->id)->with('t_pembayaran_piutang_d')->first()->toArray();
        $detail = $data['t_pembayaran_piutang_d'];
        foreach($detail as $dt){
            $this->onPost($dt['t_tagihan_id'], $dt);
        }

        $this->where('id', $req->id)->update([
            'status' => 'POST'
        ]);
    }
    

    // function get_tagihan($cusId){
    //     $tagihan = \DB::table('t_tagihan')->whereId($custId)->first();
    //     return $tagihan;
    // }

    // public function custom_customer($req)
    // {
    //     $id = Request('id');
    //     $method = $req->getMethod();

    //     // GET ALL DATA
    //     if($id === 'customer'){
    //         $cust = \DB::table('m_customer')
    //         ->leftJoin('t_pembayaran_piutang as tpp', 'tpp.customer', '=', 'm_customer.id')
    //         ->select('m_customer.id', 'm_customer.nama_perusahaan', 'tpp.no_pembayaran', 'tpp.tanggal', 'tpp.tipe_pembayaran', 
    //         'tpp.total_amt', 'tpp.status', 'tpp.catatan')
    //         ->orderBy('m_customer.id', 'desc')
    //         ->get();

    //         return ["data"=> $cust];

    //     // BY ID
    //     } else {

    //     // EDIT DATA
    //         if($method == 'POST' || $method == 'PUT'){
    //             $tpp = $this->where('customer', $id)->first();
    //             $is_update = $tpp ? true : false;
    //             $req_data = $req->all();

    //             $required = [
    //                 'tanggal'=> isset($req_data['tanggal']) || $req_data['tanggal'] != null,
    //                 'tanggal_pembayaran'=> isset($req_data['tanggal_pembayaran']) || $req_data['tanggal'] != null,
    //                 'm_akun_pembayaran_id'=> isset($req_data['m_akun_pembayaran_id']) || $req_data['tanggal'] != null,
    //                 'tipe_pembayaran'=> isset($req_data['tipe_pembayaran']) || $req_data['tanggal'] != null
    //             ];

    //             if(!$required['tanggal'] || !$required['tanggal_pembayaran'] || !$required['m_akun_pembayaran_id'] || 
    //                !$required['tipe_pembayaran']){
    //                 $errors = [];

    //                 if (!$required['tanggal']) {
    //                     $errors['tanggal'] = ['Bidang ini wajib di isi'];
    //                 }
    //                 if (!$required['tanggal_pembayaran']) {
    //                     $errors['tanggal_pembayaran'] = ['Bidang ini wajib di isi'];
    //                 }
    //                 if (!$required['m_akun_pembayaran_id']) {
    //                     $errors['m_akun_pembayaran_id'] = ['Bidang ini wajib di isi'];
    //                 }
    //                 if (!$required['tipe_pembayaran']) {
    //                     $errors['tipe_pembayaran'] = ['Bidang ini wajib di isi'];
    //                 }

    //                 return response()->json([
    //                     'code' => 422,
    //                     'message' => $errors,
    //                     'resource' => 't_pembayaran_piutang'
    //                 ], 422);
    //             }

    //             $edited_data = [
    //                 'no_draft' => $req_data['no_draft'] ?? null,
    //                 'no_pembayaran' => $req_data['no_pembayaran'] ?? null,
    //                 'status' => $req_data['status'] ?? null,
    //                 'tanggal' => !$is_update ? Carbon::createFromFormat('d/m/Y', $req_data['tanggal'])->format('Y-m-d') : $req_data['tanggal'],
    //                 'tanggal_pembayaran' => !$is_update ? Carbon::createFromFormat('d/m/Y', $req_data['tanggal_pembayaran'])->format('Y-m-d') : $req_data['tanggal_pembayaran'],
    //                 'tipe_pembayaran' => $req_data['tipe_pembayaran'] ?? null,
    //                 'total_amt' => isset($req_data['total_amt']) && $req_data['total_amt'] !== '' ? $req_data['total_amt'] : null,
    //                 'm_akun_pembayaran_id' => $req_data['m_akun_pembayaran_id'] ?? null,
    //                 'customer' => $id,
    //                 'catatan' => $req_data['catatan'] ?? null,
    //             ];
                
    //             $result = ['id' => $id];
    //             if($is_update){
    //                 $edited_data['id'] = $tpp->id;
    //                 $affected = $this->where('id', $tpp->id)->update($edited_data);
    //                 $result['tpp_id'] = $tpp->id;
    //             } else {
    //                 unset($edited_data['id']);
    //                 $edited_data['status'] = 'DRAFT';
    //                 $edited_data['no_draft'] = $this->helper->generateNomor("Draft P Piutang");
    //                 $edited_data['no_pembayaran'] = $this->helper->generateNomor("No P Piutang");
    //                 $affected = $this->insertGetId($edited_data);
    //                 $result['tpp_id'] = $affected;
    //             }

    //             $tpp_d = isset($req_data['t_pembayaran_piutang_d']) ? $req_data['t_pembayaran_piutang_d'] : [];
    //             \DB::table('t_pembayaran_piutang_d')->where('t_pembayaran_piutang_id', '=', $result['tpp_id'])->delete();
                
    //             foreach($tpp_d as $det){
    //                 $detail = [
    //                     't_pembayaran_piutang_id' => $result['tpp_id'],
    //                     't_tagihan_id' => $det['t_tagihan_id'],
    //                     'bayar' => isset($det['bayar']) && $det['bayar'] !== '' ? $det['bayar'] : null,
    //                     'sisa_piutang' => isset($det['sisa_piutang']) && $det['sisa_piutang'] !== '' ? $det['sisa_piutang'] : null,
    //                     'total_bayar' => isset($det['total_bayar']) && $det['total_bayar'] !== '' ? $det['total_bayar'] : null,                            
    //                     'catatan' => isset($det['catatan']) ? $det['catatan'] : null
    //                 ];
                    
    //                 \DB::table('t_pembayaran_piutang_d')->insert($detail);
    //             }
                
    //             return response()->json(["message"=> "update data berhasil",
    //                    "success"=> ["SUCCESS: data update in t_pembayaran_piutang id: {$result['tpp_id']} for customer id: {$result['id']}"],
    //                     "id"=> $result['tpp_id']], 200);

    //         // GET DATA
    //         } else {
    //             $data = ['id'=> $id];
    //             $tpp = $this->where('customer', $id)->join('m_customer', 'm_customer.id', '=', 'customer')
    //             ->select('t_pembayaran_piutang.*', 'm_customer.top')
    //             ->first();

    //             if($tpp){
    //                 $data = $tpp;
    //                 $data['t_pembayaran_piutang_id'] = $tpp->id;
    //                 $data['id'] = $tpp->customer;

    //                 $detail = [];

    //                 $tpp_d = \DB::table('t_tagihan')->where('customer', $id)
    //                 ->leftJoin('t_pembayaran_piutang_d as tpp_d', 'tpp_d.t_tagihan_id', '=', 't_tagihan.id')
    //                 ->select('tpp_d.*', 'total_amount as nilai_piutang', 'no_tagihan', 'tgl as tanggal_tagihan', 't_tagihan.id as t_tagihan_id')
    //                 ->get();

    //                 foreach($tpp_d as $det){
    //                     $tanggal_jt = Carbon::parse($det->tanggal_tagihan)->addDays($tpp->top);
    //                     $det->tanggal_jt = $tanggal_jt->format('Y-m-d');
    //                     $det->t_pembayaran_piutang_id = $data['t_pembayaran_piutang_id'];
    //                     $det->customer = $data['id'];
    //                     $detail = array_merge($detail, [$det]);
    //                 }

    //                 $data['t_pembayaran_piutang_d'] = $detail;
    //             } else {
    //                 $data = ['id'=> $id, 'customer'=> $id];
    //                 $data['t_pembayaran_piutang_d'] = [];
    //             }
    //             return response()->json(['data'=> $data], 200);
    //         }
    //     }
    // }
}