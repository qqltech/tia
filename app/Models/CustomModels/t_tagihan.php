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

    public function custom_calculate_tagihan($req){
        // return $req;
        $tagihanKontainer = $req['detailArr'];
        $tagihanJasa = $req['detailArr1'];
        $tagihanPpjk = $req['detailArr2'];
        $tagihanLain = $req['detailArr3'];
        // $tagihanCoo = $req['tarif_coo'];
        $tarifdp = $req['total_tarif_dp'];
        $idBukuOrder = $req['t_buku_order_id'];
        $ppn = $req['ppn'];
        $countKontainer = collect($req['detailArr'])->count();

        $nominalPpjk = $req['detailArr'][0]['tarif'][0]['tarif_ppjk'] ?? 0;

        $totalKontainer = $this->kontainer($tagihanKontainer);
        $totalKontainerPPN = $totalKontainer * ($ppn / 100);
        $grandTotalKontainer = $totalKontainer + $totalKontainerPPN;

        $totalJasa = $this->jasa($tagihanJasa, $ppn, $countKontainer);
        $totalPpjk =  $this->ppjk($tagihanPpjk, $nominalPpjk);
        $totalLainArray = $this->lain($tagihanLain, $ppn); 
        $totalLain = $totalLainArray['total'] - $tarifdp;


        return [
            'total_kontainer' => $totalKontainer + $totalJasa['total'],
            'total_lain' => $totalLainArray['total_non_ppn'],
            'grand_total' => $totalKontainer + $totalJasa['total'] + $totalLainArray['total_non_ppn'],
            'total_ppn' => $totalKontainerPPN + $totalJasa['total_ppn'] + $totalLainArray['total_ppn'],
            'total_setelah_ppn' => $grandTotalKontainer + $totalJasa['total'] + $totalLainArray['total']
        ];
    }


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

    private function ppjk($ppjk,$tarif){
        $calculatePpjk = 0;
        $collect = collect($ppjk);
        $count = $collect->count();
        $calculatePpjk = $tarif * $count;
        return $calculatePpjk;
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

    private function lain($data, $ppn){
        $calculateLain = 0;
        $grandTotalPpn = 0;
        $totalNotPpn = 0;

        foreach($data as $single){
            if(@$single['is_ppn']){
                $totalPpn = 0;
                $totalPpn = $single['tarif_realisasi'] * ($ppn / 100) * $single['qty'];;
                $grandTotalPpn += $totalPpn;
                
                $totalLain = ($single['tarif_realisasi'] ?? 0) * ($single['qty'] ?? 0);
                $totalNotPpn += $calculateLain;	                
                
                $calculateLain += $totalLain;
                $calculateLain += $totalPpn;

            }else{
                $totalLain = ($single['tarif_realisasi'] ?? 0) * ($single['qty'] ?? 0);

                $totalNotPpn += $totalLain;
                $calculateLain += $totalLain;

            }
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
}
?>
