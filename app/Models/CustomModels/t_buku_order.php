<?php

namespace App\Models\CustomModels;
use App\Models\CustomModels\t_nota_rampung;

class t_buku_order extends \App\Models\BasicModels\t_buku_order
{
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }

    public $fileColumns = [
        /*file_column*/
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    private function convertMonthToRoman($month)
    {
        $romans = [
            "Jan" => "I",
            "Feb" => "II",
            "Mar" => "III",
            "Apr" => "IV",
            "May" => "V",
            "Jun" => "VI",
            "Jul" => "VII",
            "Aug" => "VIII",
            "Sep" => "IX",
            "Oct" => "X",
            "Nov" => "XI",
            "Dec" => "XII",
        ];
        return isset($romans[$month]) ? $romans[$month] : null;
    }

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $tipeOrder = $arrayData["tipe_order"];
        $kodeTipe = "";
        if ($tipeOrder == "EKSPORT") {
            $kodeTipe = "Buku Order Exp";
        } elseif ($tipeOrder == "IMPORT") {
            $kodeTipe = "Buku Order Imp";
        } elseif ($tipeOrder == "IMPORT S") {
            $kodeTipe = "Buku Order Imps";
        } elseif ($tipeOrder == "EKSPORT S") {
            $kodeTipe = "Buku Order Exps";
        } elseif ($tipeOrder == "LOKAL") {
            $kodeTipe = "Buku Order Lokal";
        } elseif ($tipeOrder == "OL") {
            $kodeTipe = "Buku Order OL";
        } elseif ($tipeOrder == "OLS") {
            $kodeTipe = "Buku Order OLS";
        }

        $noBukuOrder = $this->helper->generateNomor($kodeTipe);
        
        $currentMonth = date('M');  
        $romanMonth = $this->convertMonthToRoman($currentMonth);

        $currentYear = date('y');

         $formattedNoBukuOrder = $noBukuOrder . $romanMonth ."-".$currentYear;

        $newData = [
            "no_buku_order" => $formattedNoBukuOrder,
            // "tgl" => date("d/m/Y")
        ];

        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function custom_generate()
    {
        $data = $this->helper->generateNomor("Buku Order") . $tipeOrder;
        return $data;
    }
    
    public function custom_post($req)
    {
        $id = request("id");
        $data_req = ['tgl', 'tipe_order', 'no_buku_order', 'm_customer_id', 'jenis_barang', 'tujuan_asal', 'moda_transportasi', 'coo', 'hc', 'status', 'tanggal_closing_doc', 'jam_closing_doc', 'tanggal_closing_cont', 'jam_closing_cont', 'tanggal_pengkont', 'tanggal_pemasukan', 'voyage', 'lokasi_stuffing','kode_pelayaran_id', 'gw', 'nw', 'nama_kapal', 'pelabuhan_id', 'tipe'];
        $data_det_req = ['no_prefix','no_suffix','ukuran','jenis','sektor','depo','m_petugas_pengkont_id','m_petugas_pemasukan_id'];

        $messages = [];
        $messages2 = [];
        $data =  $this->where("id", $id)->select('*')->first();
        $data_det =  \DB::table('t_buku_order_d_npwp')->where("t_buku_order_id", $id)->get();

        foreach($data_req as $d){
            if($data->$d == null){
                $messages[] = "$d";
            }
        }

        foreach($data_det_req as $d){
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
        
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true];
    }

    // public function scopeGetTarif($model){
    //     return $model->join('m_tarif as tf','m_customer.id','tf.m_customer_id');
    // }

    public function custom_alert(){
        $now = \Carbon::now();
        $dayBefore = $now->copy()->subDays(14);
        $data = $this->where('tgl','<=',$dayBefore)->where('status','DRAFT')->orderBy('tgl')->get(['id','no_buku_order','tgl']);
        
        return $this->helper->CustomResponse("Buku Order Outstanding", 200, $data);
    }

    private function getTarif($customerID){
        return m_tarif::where('m_customer_id',$customerID)->where('is_active', true)->get();
    }

    private function GetTarifDP($bukuOrderID){
        return t_dp_penjualan::where('t_buku_order_id',$bukuOrderID)->where('status','POST')->first();
    }

    public function transformRowData( array $row )
    {
        $data = [];
        if(app()->request->view_tarif){
            $tarifDP = $this->GetTarifDP($row['id']);
            // $notaRampung = new t_nota_rampung;
            // $getNotaRampung = $notaRampung->grand_total($row['id']);
            // $tarif = $this->getTarif($row['m_customer.id']);
            $data = [
                'tarif_dp' => $tarifDP
            ];

            // foreach($tarif as $single){
            //     $single['ukuran_kontainer'] = m_general::where('id',$single['ukuran_kontainer'])->first()->deskripsi;
            //     $data += [
            //         'tarif_id_'.$single['ukuran_kontainer'] => @$single->id,
            //         'tarif_d_jasa_'.$single['ukuran_kontainer'] => m_tarif_d_jasa::where('m_tarif_id',@$single->id)
            //         ->join('m_jasa as mj','m_tarif_d_jasa.m_jasa_id','mj.id')
            //         ->whereHas('m_tarif',function($query) use($row){
            //             $query->where('tipe_tarif',ucfirst(strtolower($row['tipe_order'])));
            //         })
            //         ->select('m_tarif_d_jasa.*', 'mj.*')->with(['m_tarif' => function($query){
            //             $query->join('set.m_general as mg','mg.id','m_tarif.ukuran_kontainer')->select('m_tarif.*','mg.deskripsi');
            //         }])->get(),
            //         // 'grand_total_nota_rampung' => $getNotaRampung
            //     ];
            // }
        }
        return array_merge( $row, $data);
    }

    public function relationPpjk(){
        return $this->hasMany('App\Models\BasicModels\t_ppjk', 't_buku_order_id', 'id');
    }
    
    public function scopeWithDetailAju($model){
        return $model->with(['relationPpjk' => function($query) {
            $query->join('m_generate_no_aju_d', 'm_generate_no_aju_d.id', '=', 't_ppjk.no_ppjk_id')
            ->join('m_customer', 'm_customer.id', '=', 't_ppjk.m_customer_id');
        }]);
    }

    public function persentaseKomisi(){
        return $this->hasMany('App\Models\BasicModels\m_tarif_komisi_undername', 'm_cust_id', 'id');
    }

    public function scopeWithDetailAju2($model)
    {
    return $model
        ->with(['relationPpjk' => function ($query) {
            $query->join('m_generate_no_aju_d', 'm_generate_no_aju_d.id', '=', 't_ppjk.no_ppjk_id')
                ->join('m_customer', 'm_customer.id', '=', 't_ppjk.m_customer_id')
                ->orderBy('t_ppjk.tanggal', 'desc') // Urutkan berdasarkan tanggal terbaru
                ->take(1); // Ambil hanya satu data
        }])
        // ->with(['persentaseKomisi' => function ($query) use ($tipe_tarif, $cust_id, $buku_order_id) {
        //     $query->join('m_tarif_komisi_undername as mtku', 'mtku.m_cust_id', '=', 't_buku_order.m_customer_id')
        //         ->join('m_tarif_komisi_undername_d as mtku_d', 'mtku_d.m_tarif_komisi_undername_id', '=', 'mtku.id')
        //         ->select(
        //             'mtku.id as m_tarif_komisi_undername_id',
        //             'mtku.m_cust_id as mtku_cust_id',
        //             'mtku.tipe_tarif',
        //             'mtku.tarif_komisi',
        //             'mtku_d.id as m_tarif_komisi_undername_d_id',
        //             'mtku_d.nilai_awal',
        //             'mtku_d.nilai_akhir',
        //             'mtku_d.persentase',
        //         )
        //         ->where('mtku.tipe_tarif', $tipe_tarif)
        //         ->where('mtku.m_cust_id', $cust_id)
        //         ->where('t_buku_order.id', $buku_order_id);
        // }])
        ;
}


    public function scopeGetPersentase($model){
        $tipe_tarif = request('tipe_tarif');
        $nilai_invoice = request('nilai_invoice');
        // $buku_order_id = request('buku_order_id');

        return $model
        ->leftJoin('m_tarif_komisi_undername as mtku', 'mtku.m_cust_id','t_buku_order.m_customer_id')
        ->leftJoin('m_tarif_komisi_undername_d as mtku_d','mtku_d.m_tarif_komisi_undername_id','mtku.id')
        ->addSelect(
            't_buku_order.id as buku_order_id',
            'mtku.id as m_tarif_komisi_undername_id',
            'mtku.m_cust_id as mtku_cust_id',
            'mtku.tipe_tarif',
            'mtku.tarif_komisi',
            'mtku_d.id as m_tarif_komisi_undername_d_id',
            'mtku_d.nilai_awal',
            'mtku_d.nilai_akhir',
            // 'mtku_d.persentase',
            )
        ->where('mtku.tipe_tarif',$tipe_tarif)
        ->where('mtku.is_active',true)
        ;
    }

    public function custom_GetPersen(){
        
        $tipe_tarif = request('tipe_tarif');
        $nilai_invoice = request('nilai_invoice');
        $customer_id = request('customer_id');

        $inputValue = 25000; // Example input value

        $results = \DB::table('m_tarif_komisi_undername as mtku')
            ->leftJoin('m_tarif_komisi_undername_d as mtkud', 'mtku.id', '=', 'mtkud.m_tarif_komisi_undername_id')
            ->select(
                'mtku.id as tarif_komisi_undername_id',
                'mtku.*',
                'mtkud.id as tarif_komisi_undername_d_id',
                'mtkud.nilai_awal',
                'mtkud.nilai_akhir',
                'mtkud.persentase'
            )
            ->where('mtku.m_cust_id', 12)
            ->where('mtku.tipe_tarif', 'NON QQ')
            ->whereRaw('? BETWEEN mtkud.nilai_awal AND mtkud.nilai_akhir', [$nilai_invoice])
            ->first();


        // $results = \DB::table('t_buku_order as tbo')
        // ->leftJoin('m_tarif_komisi_undername as mtku', 'tbo.m_customer_id', '=', 'mtku.m_cust_id')
        // ->leftJoin('m_tarif_komisi_undername_d as mtku_d', 'mtku_d.m_tarif_komisi_undername_id', '=', 'mtku.id')
        // ->select(
        //     'mtku_d.id as tarif_komisi_undername_d_id',
        //     'tbo.id as buku_order_id',
        //     'tbo.*',
        //     'mtku.id as tarif_komisi_undername_id',
        //     'mtku.*',
        //     'mtku_d.*'
        // )
        // ->where('tbo.id', $buku_order_id)
        // ->where('mtku.tipe_tarif', $tipe_tarif)
        // ->where('mtku.m_cust_id',$customer_id)
        // ->where('mtku.is_active', true)
        // ->whereRaw('? BETWEEN mtku_d.nilai_awal AND mtku_d.nilai_akhir', [$nilai_invoice])
        // ->get()
        // ;
        
        return [
            "data"=>$results
        ];

    }


    public function scopeDetailcust($model){
        $id = request("id");
        return $model->where("id",$id)->with('m_customer');
    }
    // public function scopeKontainerNR($model){
    //     $id = request("id");

    //     $model->where('id',$id)

    // }

    public function custom_print()
    {
        $id = request("id");
        // $status = $this->where("id", $id)->update(["status" => "PRINTED"]);
        return ["success" => true];
    }

    public function scopeNotDuplicate($model){
        return $model->whereNotIn('t_buku_order.id', function ($query){
            $query->select('no_buku_order')
            ->from('t_tagihan')
            ->where('status','POST');
        });
    }
}