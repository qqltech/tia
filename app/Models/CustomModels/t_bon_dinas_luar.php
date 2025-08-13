<?php

namespace App\Models\CustomModels;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Http\Request;



class t_bon_dinas_luar extends \App\Models\BasicModels\t_bon_dinas_luar
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

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft Bon Dinas Luar"),
            "no_bon_dinas_luar" => $this->helper->generateNomor(
                "No Bon Dinas Luar"
            ),
            "status" => "DRAFT",
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
        ];
    }

    public function updateBefore($model, $arrayData, $metaData, $id = null)
    {
        $newData = [
            "tanggal" => date("Y-m-d"),
        ];
        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
        ];
    }

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        $this->autoJurnal($id);
        return ["success" => true, "message" => "Post Data Berhasil"];
    }

    public function custom_multiple_post($req)
    {
        $validator = Validator::make($req->all(), [
            "items" => "required|array",
            "items.*" => "integer|exists:t_bon_dinas_luar,id",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Validasi gagal",
                    "errors" => $validator->errors(),
                ],
                422
            );
        }

        $validated = $validator->validated();
        $success = [];
        $failed = [];

        foreach ($validated["items"] as $id) {
            try {
                $update = $this->where("id", $id)->update(["status" => "POST"]);

                if ($update) {
                    $this->autoJurnal($id);
                    $success[] = $id;
                } else {
                    $failed[] = [
                        "id" => $id,
                        "reason" =>
                            "Update status gagal atau data tidak ditemukan",
                    ];
                }
            } catch (\Exception $e) {
                $failed[] = [
                    "id" => $id,
                    "reason" => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            "success" => true,
            "message" => "Multiple post data berhasil!",
            "total" => count($validated["items"]),
            "sukses" => count($success),
            "gagal" => count($failed),
            "detail_gagal" => $failed,
        ]);
    }

    // private function autoJurnal($id)
    // {
    //     $trx = \DB::selectOne(
    //         "select a.* from t_bon_dinas_luar a where a.id = ?",
    //         [$id]
    //     );
        
    //     if (!$trx) {
    //         return ["status" => true];
    //     }

    //     $getdebet = \DB::select(
    //         "select d.t_buku_order_id, sum(d.sub_total) as amount from t_bon_dinas_luar b
    //     join t_bon_dinas_luar_d d on d.t_bon_dinas_luar_id = b.id
    //     where b.id = ?
    //     group by d.t_buku_order_id",
    //         [$id]
    //     );

    //     $seq = 0;
    //     $debetArr = [];
    //     $amount = 0;

    //     foreach ($getdebet as $dbt) {
    //         if (!$dbt->t_buku_order_id) {
    //             return [
    //                 "status" => false,
    //                 "message" => "t_buku_order_id is required",
    //             ];
    //         }

    //         $debetArr[] = (object) [
    //             "m_coa_id" => $dbt->t_buku_order_id,
    //             "seq" => $seq + 1,
    //             "debet" => (float) $dbt->amount,
    //             "desc" => $trx->keterangan ?? "-",
    //         ];
    //         $amount += (float) $dbt->amount;
    //         $seq++;
    //     }

    //     $creditArr = [];

    //     if (!$trx->tipe_kategori_id) {
    //         return [
    //             "status" => false,
    //             "message" => "tipe_kategori_id is required",
    //         ];
    //     }

    //     $credit = new \stdClass();
    //     $credit->m_coa_id = $trx->tipe_kategori_id;
    //     $credit->seq = 1;
    //     $credit->credit = (float) @$amount ?? 0;
    //     $credit->desc = $trx->catatan ?? "-";
    //     $creditArr[] = $credit;

    //     $obj = [
    //         "date" => $trx->tanggal,
    //         "form" => "Bon Dinas Luar",
    //         "ref_table" => "t_bon_dinas_luar",
    //         "ref_id" => $trx->id,
    //         "ref_no" => $trx->no_bon_dinas_luar,
    //         "desc" => $trx->catatan,
    //         "detail" => array_merge($debetArr, $creditArr),
    //     ];

    //     $r_gl = new \App\Models\CustomModels\r_gl();
    //     $data = $r_gl->autoJournal($obj);

    //     return ["status" => true];
    // }

    



    private function autoJurnal($id)
    {
        $trx = \DB::selectOne(
            "select a.* from t_bon_dinas_luar a where a.id = ?", [$id]);
        
        if (!$trx) {
            return ["status" => true];
        }

        $getdebet = \DB::select(
            "select b.tipe_kategori_id, d.sub_total as amount, d.keterangan from t_bon_dinas_luar b
        join t_bon_dinas_luar_d d on d.t_bon_dinas_luar_id = b.id
        where b.id = ?",
            [$id]
        );

        $seq = 0;
        $debetArr = [];
        $amount = 0;

        foreach ($getdebet as $dbt) {
            if (!$dbt->tipe_kategori_id) {
                return [
                    "status" => false,
                    "message" => "tipe_kategori_id is required",
                ];
            }

            $debetArr[] = (object) [
                "m_coa_id" => $dbt->tipe_kategori_id,
                "seq" => $seq + 1,
                "debet" => (float) $dbt->amount,
                "desc" => $dbt->keterangan ?? "-",
            ];
            $amount += (float) $dbt->amount;
            $seq++;
        }

        $creditArr = [];

        if (!$trx->tipe_kategori_id) {
            return [
                "status" => false,
                "message" => "tipe_kategori_id is required",
            ];
        }


        $kasCoa = \DB::table('m_coa')->where('nama_coa', 'KAS')->first();
        if (!$kasCoa) return ["status" => false, "message" => "Akun KAS tidak ditemukan"];


        $credit = new \stdClass();
        $credit->m_coa_id = $kasCoa->id;
        $credit->seq = 1;
        $credit->credit = (float) @$amount ?? 0;
        $credit->desc = $trx->catatan ?? "-";
        $creditArr[] = $credit;

        $obj = [
            "date" => $trx->tanggal,
            "form" => "Bon Dinas Luar",
            "ref_table" => "t_bon_dinas_luar",
            "ref_id" => $trx->id,
            "ref_no" => $trx->no_bon_dinas_luar,
            "desc" => $trx->catatan,
            "detail" => array_merge($debetArr, $creditArr),
        ];

        $r_gl = new \App\Models\CustomModels\r_gl();
        $data = $r_gl->autoJournal($obj);

        return ["status" => true];
    }


    
    

    public function custom_update_status(Request $request)
    {
        $id = $request->query('id');
        if (!$id) {
            return ["success" => false, "message" => "ID dan Status wajib diisi"];
        }

        $exists = $this->where("id", $id)->exists();
        if (!$exists) {
            return ["success" => false, "message" => "Data tidak ditemukan"];
        }

        $this->where("id", $id)->update(["status" => strtoupper("PRINTED")]);
        return ["success" => true, "message" => "Status berhasil diubah menjadi PRINTED"];
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
            $req = app()->request;
            $trx = $this->find($req->id);
            if ($trx) {
                $trx->update([
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
        $trx = $this->find($id);
        if (!$trx) return false;

        $conf = [
            "app_name" => "APPROVAL BON DINAS LUAR",
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan Bon Dinas Luar",
            "form_name" => "t_bon_dinas_luar",
            "trx_nomor" => $trx->no_bon_dinas_luar,
            "trx_date" => date("Y-m-d"),
            "trx_creator_id" => $trx->creator_id,
        ];

        $app = $this->approval->approvalCreateTicket($conf);
        return $app ? true : false;
    }

    public function custom_progress($req)
    {
        \DB::beginTransaction();
        try {
            $conf = [
                "app_id" => $req->id,
                "app_type" => $req->type, // APPROVED, REVISED, REJECTED
                "app_note" => $req->note,
            ];

            $app = $this->approval->approvalProgress($conf, true);
            if ($app->status) {
                $data = $this->find($app->trx_id);
                if ($app->finish) {
                    $data->update([
                        "status" => $req->type,
                    ]);
                    // if ($req->type == "APPROVED") {
                    //     $this->autoJurnal($data->id);
                    // }
                } else {
                    $data->update([
                        "status" => "IN APPROVAL",
                    ]);
                }
            }

            if($req->type=="APPROVED"){
                $get_trx_id = generate_approval::where('id',$req->id)->first();
                $t_bon_dinas_luar = t_bon_dinas_luar::where('id',$get_trx_id->trx_id)->first();
                $update_status_print = t_bon_dinas_luar::where('id',$get_trx_id->trx_id)->update(["is_printed"=>0]);
            }

            \DB::commit();
            return $this->helper->customResponse("Proses approval berhasil");
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }
    }



    // public function custom_getPrintData(){
    //     $req = app()->request;

    //     $user = \Auth::user();
    //     $user_print = \DB::table('default_users as du')->where('du.id', $user->id)
    //     ->leftJoin("set.m_kary as kary", 'kary.id', '=', 'du.m_employee_id')
    //     ->select('du.name', 'kary.nip')->first();

    //     // $user_print = (object) [
    //         // "name" => @$user->name,
    //         // "nip" => @$emp->name
    //     // ];

    //     $data = \DB::select("SELECT tsa.*,
    //     mg1.kode as chasis1_kode, mg2.kode as chasis2_kode, mg3.deskripsi as ukuran1_deskripsi,
    //     mk.nama as supir_nama, mk.nip as supir_nip,

    //     tbo.no_buku_order as no_buku_order,
    //     mcu.nama_perusahaan as customer_nama_perusahaan,
    //     mcu.kode as customer_kode,

    //     tbo2.no_buku_order as no_buku_order2,
    //     mcu2.nama_perusahaan as customer_nama_perusahaan2,
    //     mcu2.kode as customer_kode2,

    //     mg8.deskripsi as sektor1_deskripsi, mg9.deskripsi as sektor2_deskripsi,
    //     mg5.kode as trip_kode, mg4.kode as head_kode, mg10.deskripsi as waktu_in_deskripsi,
    //     mg11.deskripsi as waktu_out_deskripsi,
    //     mg12.deskripsi as isi_container_1_deskripsi,
    //     mg13.deskripsi as isi_container_2_deskripsi,
    //     tsa.jumlah_print,
    //     to_char(tsa.tanggal_in, 'DD/MM/YYYY') AS tanggal_in,
    //     to_char(tsa.tanggal_out, 'DD/MM/YYYY') AS tanggal_out

    //     FROM t_spk_angkutan tsa
    //     LEFT JOIN set.m_general mg1 ON tsa.chasis = mg1.id
    //     left join set.m_general mg2 on tsa.chasis2 = mg2.id
    //     left join set.m_kary mk on tsa.supir = mk.id

    //     left join t_buku_order_d_npwp tbod on tsa.t_detail_npwp_container_1_id = tbod.id
    //     left join t_buku_order tbo on tbod.t_buku_order_id = tbo.id
    //     left join m_customer mcu on tbo.m_customer_id = mcu.id

    //     left join t_buku_order_d_npwp tbod2 on tsa.t_detail_npwp_container_2_id = tbod2.id
    //     left join t_buku_order tbo2 on tbod2.t_buku_order_id = tbo2.id
    //     left join m_customer mcu2 on tbo2.m_customer_id = mcu2.id

    //     left join set.m_general mg3 on tbod.ukuran = mg3.id
    //     left join set.m_general mg4 on tsa.head = mg4.id
    //     left join set.m_general mg5 on tsa.trip_id = mg5.id

    //     left join set.m_general mg8 on tsa.sektor1 = mg8.id
    //     left join set.m_general mg9 on tsa.sektor2 = mg9.id
    //     left join set.m_general mg10 on tsa.waktu_in = mg10.id
    //     left join set.m_general mg11 on tsa.waktu_out = mg11.id
    //     left join set.m_general mg12 on tsa.isi_container_1 = mg12.id
    //     left join set.m_general mg13 on tsa.isi_container_2 = mg13.id

    //     WHERE tsa.id = ?", [@$req->t_spk_id ?? 0]);

    //     $nospkd = \DB::select("SELECT tsbd.*
    //     from t_spk_bon_detail tsbd
    //     WHERE tsbd.t_spk_angkutan_id = ?", [@$req->t_spk_id ?? 0]);


    //     $count_spk = count(@$data ?? []);
    //     $currentDate = date("d/m/Y");
    //     $currentTime = date("H:i:s");

    //     return [
    //         "user_print" => @$user_print,
    //         "data" => @$data,
    //         "nospkd" => @$nospkd,
    //         "count_spk" => @$count_spk,
    //         "currentDate" => @$currentDate,
    //         "currentTime" => @$currentTime
    //     ];
    // }


    // public function custom_getPrintData()
    // {
    //     $req = app()->request;

    //     $user = \Auth::user();
    //     $user_print = \DB::table('default_users as du')
    //         ->where('du.id', $user->id)
    //         ->leftJoin("set.m_kary as kary", 'kary.id', '=', 'du.m_employee_id')
    //         ->select('du.name', 'kary.nip')
    //         ->first();

    //     $id = $req->t_bon_dinas_luar_id;
    //     $trx = \DB::table('t_bon_dinas_luar as bdl')
    //         ->where('bdl.id', $id)
    //         ->leftJoin('public.m_supplier as supplier', 'supplier.id', '=', 'bdl.m_supplier_id')
    //         ->leftJoin('set.m_kary as kary', 'kary.id', '=', 'bdl.m_kary_id')  
    //         ->leftJoin('t_bon_dinas_luar_d as bdl_d', 'bdl_d.t_bon_dinas_luar_id', '=', 'bdl.id') 
    //         ->leftJoin('t_buku_order as tbo', 'tbo.id', '=', 'bdl_d.t_buku_order_id')  
    //         ->leftJoin('m_coa as c',  'c.id', '=', 'bdl.tipe_kategori_id')
    //         ->leftJoin('m_supplier as s',  's.id', '=', 'bdl.m_supplier_id')
    //         ->select(
    //             'bdl.*',
    //             'bdl_d.*',
    //             'c.*',
    //             's.*',
    //             'tbo.*',
    //             'kary.*',
    //             'supplier.*',
    //             's.nama as nama_supplier',
    //             'kary.nama as nama_karyawan'
    //         )
    //         ->first();

    //     if (!$trx) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Data tidak ditemukan'
    //         ], 404);
    //     }


    //     return response()->json([
    //         'success' => true,
    //         'data' => $trx
    //     ]);
    // }

    public function custom_getPrintData()
    {
        $req = app()->request;
        $id = $req->t_bon_dinas_luar_id;

        $user = \Auth::user();
        $user_print = \DB::table('default_users as du')->where('du.id', $user->id)
        ->leftJoin("set.m_kary as kary", 'kary.id', '=', 'du.m_employee_id')
        ->select('du.name', 'kary.nip')->first();

        $header = \DB::table('t_bon_dinas_luar as bdl')
            ->where('bdl.id', $id)
            ->leftJoin('m_coa as coa', 'coa.id', '=', 'bdl.tipe_kategori_id')
            ->leftJoin('set.m_general as gen', 'gen.id', '=', 'bdl.tipe_order_id')
            ->leftJoin('set.m_kary as kary', 'kary.id', '=', 'bdl.m_kary_id')
            ->leftJoin('public.m_supplier as supplier', 'supplier.id', '=', 'bdl.m_supplier_id')
            ->leftJoin('m_coa as bank', 'bank.id', '=', 'bdl.m_akun_bank_id')
            ->leftJoin('t_bkk as bkk', 'bkk.id', '=', 'bdl.t_bkk_id')
            ->select(
                'bdl.*',
                \DB::raw('to_char(bdl.created_at, \'DD/MM/YYYY HH24:MI\') as created_at'),
                \DB::raw('to_char(bdl.updated_at, \'DD/MM/YYYY HH24:MI\') as updated_at'),
                'coa.* as tipe_kategori',
                'kary.* as m_kary',
                'supplier.* as m_supplier',
                'bank.* as m_akun_bank',
                'bkk.* as t_bkk',
                'gen.* as gen',
                'bdl.total_amt as bdl_total_amt',
                'bdl.catatan as bdl_catatan',
                'coa.nama_coa as nama_coa',
                \DB::raw('to_char(bdl.tanggal,\'DD/MM/YYYY\') as bdl_tanggal'),
            )
            ->first();

        if (!$header) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        $details = \DB::table('t_bon_dinas_luar_d as bdl_d')
            ->where('bdl_d.t_bon_dinas_luar_id', $id)
            ->leftJoin('t_buku_order as tbo', 'tbo.id', '=', 'bdl_d.t_buku_order_id')
            ->select(
                'bdl_d.*',
                'tbo.* as t_buku_order'
            )
            ->get()
            ->map(function ($row) {
                $detail = (array) $row;
                $detail['meta_read'] = true;
                $detail['meta_update'] = true;
                $detail['meta_delete'] = true;
                $detail['meta_create'] = true;
                return $detail;
            });

        return response()->json([
            'data' => array_merge(
                (array) $header,
                // (array)$user_print,
                [
                    't_bon_dinas_luar_d' => $details,
                    'user_print'=>$user_print
                ]
            ),
        ]);
    }


    public function custom_updatePrintData(){
     
        $t_bon_dinas_luar_id = request('t_bon_dinas_luar_id');

        $get_jumlah_print = t_bon_dinas_luar::where('id',$t_bon_dinas_luar_id)->first();
        
        $new_jumlah_print = ($get_jumlah_print->jumlah_print + 1);
        $update_jumlah_print = t_bon_dinas_luar::where('id',$t_bon_dinas_luar_id)->update([
            "jumlah_print"=>$new_jumlah_print,
            "is_printed"=>1,
            "status" =>"PRINTED"
            ]);

        return "PRINTED SUCCESS";
    }
     
}

