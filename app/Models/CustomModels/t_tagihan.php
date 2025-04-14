<?php

namespace App\Models\CustomModels;

class t_tagihan extends \App\Models\BasicModels\t_tagihan
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

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $checkDuplicate = $this->IsDuplicate($arrayData);
        $checkDuplicate2 = $this->IsDuplicate2($arrayData);
        if($checkDuplicate) return ['errors' => ["No Buku Order Sudah Pernah Dibuat"]];
        if($checkDuplicate2) return ['errors' => ["No Faktur Pajak Sudah Pernah Dibuat"]];

        $status = "DRAFT";
        $req = app()->request;
        if($req->post){
            $status = "POST";
        }

        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft Tagihan"),
            "no_tagihan" => $this->helper->generateNomor("Tagihan"),
            // "total_cost" => $totalCost
            "status"=>$status
        ];

        $newArrayData = array_merge($arrayData, $newData);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function updateBefore( $model, $arrayData, $metaData, $id=null )
    {
        $checkDuplicate = $this->IsDuplicate($arrayData);
        if($checkDuplicate) return ['errors' => ["Data Sudah Pernah Dibuat"]];

        $req = app()->request;
        $status = $req->post ? "POST" : $arrayData['status'];

        $newData=[
            "status" => $status
        ];
        $newArrayData  = array_merge( $arrayData,$newData );
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }
    
    private function IsDuplicate($data){
        $IdBukuOrder = $data['no_buku_order'];
        $getTagihan = $this->where('no_buku_order',$IdBukuOrder)->where('status','POST')->first();
        if($getTagihan){
            return true;
        }else{
            return false;
        }
    }

    public function scopeGetTarifDP($model){
        
    }

    private function IsDuplicate2($data){
        $IdFP = @$data['no_faktur_pajak'];
        $getFP = $this->where('no_faktur_pajak',$IdFP)->where('status','POST')->first();
        if($getFP){
            return true;
        }else{
            return false;
        }
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'm_tarif_id');
    }

    // public function custom_calculate_tagihan($req){
    //     // return $req;
    //     $tagihanKontainer = $req['detailArr'];
    //     $tagihanJasa = $req['detailArr1'];
    //     $tagihanPpjk = $req['detailArr2'];
    //     $tagihanLain = $req['detailArr3'];
    //     $tarifdp = $req['total_tarif_dp'];
    //     $idBukuOrder = $req['t_buku_order_id'];
    //     $ppn = $req['ppn'];
    //     $countKontainer = collect($req['detailArr'])->count();

    //     $nominalPpjk = $req['detailArr'][0]['tarif'][0]['tarif_ppjk'] ?? 0;

    //     $totalKontainer = $this->kontainer($tagihanKontainer);
    //     $totalKontainerPPN = $totalKontainer * ($ppn / 100);
    //     $grandTotalKontainer = $totalKontainer + $totalKontainerPPN;

    //     $totalJasa = $this->jasa($tagihanJasa, $ppn, $countKontainer);
    //     $totalPpjk =  $this->ppjk($tagihanPpjk, $nominalPpjk, $ppn);
    //     $totalLainArray = $this->lain($tagihanLain, $ppn); 
    //     $totalLain = $totalLainArray['total'] - $tarifdp;


    //     return [
    //         'total_jasa_cont_ppjk' => $totalKontainer + $totalPpjk['total_non_ppn'],
    //         'total_lain2_ppn' => $totalLainArray['total_ppn'],
    //         'total_ppn' => $totalKontainerPPN + $totalJasa['total_ppn'] + $totalLainArray['total_ppn'] + $totalPpjk['total_ppn'] ,
    //         'total_jasa_angkutan' => $totalJasa['total_non_ppn'],
    //         'total_lain_non_ppn' => $totalLainArray['total_non_ppn'],
    //         'grand_total' => $grandTotalKontainer + $totalJasa['total'] + $totalPpjk['total'] + $totalLain,
    //     ];
    // }


    private function kontainer($data){
        $calculateTotalKontainer = 0;
        $collect = collect($data);

        $groupByUkuran = $collect->groupBy('ukuran');

        foreach ($groupByUkuran as $singleUkuran) {
            $calculateUkuran = 0;
            $groupBySektor = $singleUkuran->groupBy('sektor');

            foreach ($groupBySektor as $singleTipe) {
                $calculateTipe = 0;
                $groupByTipe = $singleTipe->groupBy('jenis');

                foreach ($groupByTipe as $singleKontainer) {
                    $calculateKontainer = 0;
                    $singleKontainerCount = $singleKontainer->count();

                    if ($singleKontainerCount < 2) {
                        foreach ($singleKontainer as $single) {
                            // $setKontainer = $singleKontainer->pluck('tarif')->pluck('m_tarif_d_kontainer')->unique()->flatten(1);
                            $getTarifSektor = $single['tarif'][0]['tarif_sewa'] ?? 0;
                            // $getTipeKontainer = $setKontainer->firstWhere('value', $single['tipe'])['tarif'] ?? 0;
                            $calculateKontainer += $getTarifSektor;
                        }
                    } else {
                        foreach ($singleKontainer as $index => $single) {
                            // $setKontainer = $singleKontainer->pluck('tarif')->flatten(1);
                            if ($index == 0) {
                                $getTarifSektor = $single['tarif'][0]['tarif_sewa'] ?? 0;
                            } else {
                                $getTarifSektor = $single['tarif'][0]['tarif_sewa_diskon'] ?? 0;
                            }
                            // $getTipeKontainer = $setKontainer->firstWhere('value', $single['tipe'])['tarif'] ?? 0;
                            $calculateKontainer += $getTarifSektor;
                        }
                    }
                    $calculateTipe += $calculateKontainer;
                }

                $calculateUkuran += $calculateTipe;
            }

            $calculateTotalKontainer += $calculateUkuran;
        }
        return $calculateTotalKontainer;
    }

    private function jasa($tagihan, $ppn, $count)
    {
        $totalTagihanJasa = 0;
        $grandTotalPpn = 0;
        $totalNotPpn = 0;

        foreach($tagihan as $single) {
            $totalPpn = 0;
            $totalJasa = 0;
                if (@$single['is_ppn']) {
                
                    $totalPpn = $single['tarif'] * ($ppn / 100);
                    $grandTotalPpn += $totalPpn;

                    $totalJasa = $single['tarif'] * $count;
                    $totalNotPpn += $totalJasa;

                    $totalTagihanJasa += $totalJasa + $totalPpn; 

                } else {
                    $totalJasa = $single['tarif'] * $count;

                    $totalNotPpn += $totalJasa;
                    $totalTagihanJasa += $totalJasa;
                }
        }

        return [
            'total_ppn' => $grandTotalPpn,
            'total_non_ppn' => $totalNotPpn,
            'total' => $totalTagihanJasa,
        ];
    }


    // private function kontainerYoga($data){
    //     $comb = [];
    //     $count =[];
    //     $text = "";

    //     foreach($data as $one){
    //         $text = (string)($one['ukuran']) .",".(string)($one['sektor']);
    //         if(!in_array($text,$comb)){
    //             array_push($comb,$text);
    //             $count[$text] = 1;
    //         }else{
    //             $count[$text] += 1;
    //         }
    //     }

    //     $total_harga = [];
    //     foreach($comb as $one){
    //         $sektor = explode(',',$one);
    //         $harga = sektor($sektor[1],$count[$one]);
    //         $harga_tipe = $count[$one] * getTipe($sektor[0]);
    //         $total_harga[$one] = $harga_sektor + $harga_tipe;
    //     }
    // }

    // private function sektor($value,$jumlah){
    //     $harga = 0;
    //     $sektor1 = getSektor($value,1);
    //     if($jumlah > 0){
    //         $harga += 100;
    //     }
    //     if($jumlah > 1){
    //         $sektor2 = getSektor($value,2);
    //         $harga += ($jumlah-1) * $sektor2['tarif'];	
    //     }
    //     return $harga;
    // }

    private function ppjk($ppjk,$tarif,$ppn){
        $calculatePpjk = 0;
        $grandTotalPpn = 0;
        $totalNotPpn = 0;

        $collect = collect($ppjk);
        $count = $collect->count();
        $totalNotPpn = $tarif * $count;
        $grandTotalPpn = $tarif * $count * ($ppn / 100);
        $calculatePpjk = $totalNotPpn + $grandTotalPpn;
        return [
            'total_ppn' => $grandTotalPpn,
            'total_non_ppn' => $totalNotPpn,
            'total' => $calculatePpjk,
        ];
    }

    // private function coo($coo,$idBukuOrder){
    //     $calculateCoo = 0 ;
    //     $getBukuOrder = t_buku_order::where('id',$idBukuOrder)->first();
    //     $jumlahCoo = $getBukuOrder->jumlah_coo;
    //     $jumlahCooUlang = $getBukuOrder->jumlah_coo_ulang;
    //     $jumlahSemuaCoo = $jumlahCoo + $jumlahCooUlang;
    //     $calculateCoo = $jumlahSemuaCoo * $coo;
    //     return $calculateCoo;
    // }

    // private function lain($data, $ppn){
    //     $calculateLain = 0;
    //     $grandTotalPpn = 0;
    //     $totalNotPpn = 0;

    //     foreach($data as $single){
    //         if(@$single['is_ppn']){
    //             $totalPpn = 0;
    //             $totalPpn = $single['tarif_realisasi'] * ($ppn / 100) * $single['qty'];;
    //             $grandTotalPpn += $totalPpn;
                
    //             $totalLain = ($single['tarif_realisasi'] ?? 0) * ($single['qty'] ?? 0);
    //             $totalNotPpn += $calculateLain;	                
                
    //             $calculateLain += $totalLain;
    //             $calculateLain += $totalPpn;

    //         }else{
    //             $totalLain = ($single['tarif_realisasi'] ?? 0) * ($single['qty'] ?? 0);

    //             $totalNotPpn += $totalLain;
    //             $calculateLain += $totalLain;

    //         }
    //     }

    //     return [
    //         'total_ppn' => $grandTotalPpn,
    //         'total_non_ppn' => $totalNotPpn,
    //         'total' => $calculateLain,
    //     ];
    // }

    // coba function-custom dan function-lain
    public function custom_calculate_tagihan($req){
        $tagihanKontainer = $req['detailArr'];
        $tagihanJasa = $req['detailArr1'];
        $tagihanPpjk = $req['detailArr2'];
        $tagihanLain = $req['detailArr3'];
        $tarifdp = $req['total_tarif_dp'];
        $idBukuOrder = $req['t_buku_order_id'];
        $ppn = $req['ppn'];
        $countKontainer = collect($req['detailArr'])->count();

        $nominalPpjk = $req['detailArr'][0]['tarif'][0]['tarif_ppjk'] ?? 0;
        
        $totalKontainer = $this->kontainer($tagihanKontainer);
        $totalJasa = $this->jasa($tagihanJasa, $ppn, $countKontainer);
        $totalPpjk = $this->ppjk($tagihanPpjk, $nominalPpjk, $ppn);
        $totalLainArray = $this->lain($tagihanLain, $ppn); 
        $totalLain = $totalLainArray['total'] - $tarifdp;
        
        // Menghitung Total PPN 
        $totalLainPPN = $totalLainArray['total_ppn'];
        $totalPPN = ($totalLainPPN * ($ppn / 100)) + ($totalKontainer * ($ppn / 100)) + ($totalPpjk['total_non_ppn'] * ($ppn / 100));
        
        return [
            'total_jasa_cont_ppjk' => $totalKontainer + $totalPpjk['total_non_ppn'],
            'total_lain2_ppn' => $totalLainPPN,
            'total_ppn' => $totalPPN + $totalJasa['total_ppn'],
            'total_jasa_angkutan' => $totalJasa['total_non_ppn'],
            'total_lain_non_ppn' => $totalLainArray['total_non_ppn'],
            'grand_total' => $totalKontainer + $totalPpjk['total_non_ppn'] + $totalLain + $totalPPN + $totalJasa['total'],
        ];
    }

    private function lain($data, $ppn){ 
        $calculateLain = 0;
        $grandTotalPpn = 0;
        $totalNotPpn = 0;

        foreach($data as $single){
            $totalLain = ($single['tarif_realisasi'] ?? 0) * ($single['qty'] ?? 0);
            
            if(!empty($single['is_ppn'])) { // PPN (true)
                $grandTotalPpn += $totalLain; // Simpan total Lain yang terkena PPN
            } else { // PPN (false)
                $totalNotPpn += $totalLain;
            }
            $calculateLain += $totalLain;
        }

        return [
            'total_ppn' => $grandTotalPpn,
            'total_non_ppn' => $totalNotPpn,
            'total' => $calculateLain,
        ];
    }

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true];
    }

    public function transformRowData( array $row )
    {
        $data = [];
        if(app()->request->piutang){
            $data = [
                // jasa = ppjk+cont+angkut
                // lain = lain-lain
                'piutang_jasa' => $this->getPiutang($row['id'])['piutang_jasa'], 
                'piutang_lain_lain' => $this->getPiutang($row['id'])['piutang_lain_lain'],

                'tagihan_jasa' => $this->getTagihan($row['id'])['tagihan_jasa'],
                'tagihan_lain_lain' => $this->getTagihan($row['id'])['tagihan_lain_lain'],
            ];
        }
        return array_merge( $row, $data );
    }
    

    private function getPiutang($tagihanId)
    {
        $piutangJasa = \DB::table('t_pembayaran_piutang_d')
            ->join('t_pembayaran_piutang', 't_pembayaran_piutang_d.t_pembayaran_piutang_id', '=', 't_pembayaran_piutang.id')
            ->where('t_pembayaran_piutang_d.t_tagihan_id', $tagihanId)
            ->where('t_pembayaran_piutang.tipe_piutang', '=', \DB::table('set.m_general')->where('deskripsi', 'JASA')->value('id'))
            ->sum('t_pembayaran_piutang_d.total_bayar');

        $piutangReimburse = \DB::table('t_pembayaran_piutang_d')
            ->join('t_pembayaran_piutang', 't_pembayaran_piutang_d.t_pembayaran_piutang_id', '=', 't_pembayaran_piutang.id')
            ->where('t_pembayaran_piutang_d.t_tagihan_id', $tagihanId)
            ->where('t_pembayaran_piutang.tipe_piutang', '=', \DB::table('set.m_general')->where('deskripsi', 'REIMBURSE')->value('id'))
            ->sum('t_pembayaran_piutang_d.total_bayar');

        return [
            'piutang_jasa' => (float) ($piutangJasa ?? 0),
            'piutang_lain_lain' => (float) ($piutangReimburse ?? 0),
        ]; 
    }

    private function getTagihan($tagihanId)
    {
        $tagihan = \DB::table('t_tagihan')
            ->where('id', $tagihanId)
            ->selectRaw('total_jasa_cont_ppjk + total_jasa_angkutan + total_lain2_ppn AS tagihan_jasa')
            ->selectRaw('total_lain_non_ppn AS tagihan_lain_lain')
            ->first();

        return [
            'tagihan_jasa' => (float) ($tagihan->tagihan_jasa ?? 0),
            'tagihan_lain_lain' => (float) ($tagihan->tagihan_lain_lain ?? 0),
        ]; 
    }

    // Perhitungan tagihan konsolidator
    public function custom_calculate_tagihan_konsolidator($req){
        $tagihanKontainer = $req['detailArr'];
        $tagihanJasa = $req['detailArr1'];
        $tagihanPpjk = $req['detailArr2'];
        $tagihanLain = $req['detailArr3'];
        $tarifdp = $req['total_tarif_dp'];
        $idBukuOrder = $req['t_buku_order_id'];
        $ppn = $req['ppn'];
        $countKontainer = collect($req['detailArr'])->count();

        $persentaseKonsolidatorKont = $req['persentase_konsolidator_kont']; // t_tagihan
        // $persentaseKonsolidatorJasa = $tagihanJasa[0]['persentase_konsolidator_jasa'] ?? 0; // t_tagihan_d _tarif jika 1 data
        // total jasa angkutan berdasarkan per item
        $totalJasaAngkutan = 0;
        foreach ($tagihanJasa as $jasaItem) {
            $persen = $jasaItem['persentase_konsolidator_jasa'] ?? 0;
            $tarif = $jasaItem['tarif'] ?? 0;
            $totalJasaAngkutan += ($tarif * ($persen / 100));
        }
        
        $nominalPpjk = $req['detailArr'][0]['tarif'][0]['tarif_ppjk'] ?? 0;
        
        $totalKontainer = $this->kontainer($tagihanKontainer);
        $totalJasa = $this->jasa($tagihanJasa, $ppn, $countKontainer);
        $totalPpjk = $this->ppjk($tagihanPpjk, $nominalPpjk, $ppn);
        $totalLainArray = $this->lain($tagihanLain, $ppn); 
        $totalLain = $totalLainArray['total'] - $tarifdp;
        
        // Menghitung Total PPN 
        $totalLainPPN = $totalLainArray['total_ppn'];
        $totalPPN = ($totalLainPPN * ($ppn / 100)) + ($totalKontainer * ($ppn / 100)) + ($totalPpjk['total_non_ppn'] * ($ppn / 100));
        
        return [
            'total_jasa_cont_ppjk' => ($totalKontainer + $totalPpjk['total_non_ppn']) * ($persentaseKonsolidatorKont / 100),
            'total_lain2_ppn' => $totalLainPPN,
            'total_ppn' => $totalPPN + $totalJasa['total_ppn'],
            'total_jasa_angkutan' => $totalJasaAngkutan,
            'total_lain_non_ppn' => $totalLainArray['total_non_ppn'],
            'grand_total' =>  (
                (($totalKontainer + $totalPpjk['total_non_ppn']) * ($persentaseKonsolidatorKont / 100)) +
                $totalLainPPN +
                ($totalPPN + $totalJasa['total_ppn']) +
                $totalJasaAngkutan +
                $totalLainArray['total_non_ppn'] - $tarifdp
            ),

            // 'total_jasa_angkutan' => $totalJasa['total'] * ($persentaseKonsolidatorJasa / 100),
            // 'grand_total' => $totalKontainer + $totalPpjk['total_non_ppn'] + $totalPPN + $totalJasa['total'],
            
            // 'total_jasa_cont_ppjk' => $totalKontainer + $totalPpjk['total_non_ppn'],
            // 'total_lain2_ppn' => $totalLainPPN,
            // 'total_ppn' => $totalPPN + $totalJasa['total_ppn'],
            // 'total_jasa_angkutan' => $totalJasa['total_non_ppn'],
            // 'total_lain_non_ppn' => $totalLainArray['total_non_ppn'],
            // 'grand_total' => $totalKontainer + $totalPpjk['total_non_ppn'] + $totalLain + $totalPPN + $totalJasa['total'],
        ];
    }

}
?>