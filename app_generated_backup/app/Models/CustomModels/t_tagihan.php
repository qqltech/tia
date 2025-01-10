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
        // // Data for service calculation
        // $basePricePerContainer = 450000; // Base price for the first container
        // $additionalPricePerContainer = 200000; // Additional price for each subsequent container
        // $sectorPrices = [
        //     7 => 1, // Multiplication factor for sector 7
        //     8 => 1  // Multiplication factor for sector 8
        // ];

        // $ppjks = $arrayData['ppjks'] ?? []; // Assuming ppjks data is passed in $arrayData

        // // Calculate total cost
        // $totalCost = 0;
        // foreach ($ppjks as $ppjk) {
        //     foreach ($ppjk['containers'] as $sector => $containers) {
        //         foreach ($containers as $index => $weight) {
        //             $price = $index == 0 ? $basePricePerContainer : $additionalPricePerContainer;
        //             $totalCost += $price * $sectorPrices[$sector];
        //         }
        //     }
        // }

        // Add the total cost and generated numbers to the data
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
        $tagihanCoo = $req['tarif_coo'];
        $idBukuOrder = $req['t_buku_order_id'];
        
        $totalKontainer = $this->kontainer($tagihanKontainer);
        // return $totalKontainer = $this->kontainerYoga($tagihanKontainer);

        $totalJasa = $this->jasa($tagihanJasa);
        $totalPpjk =  $this->ppjk($tagihanPpjk, $req['tarif_ppjk']);
        $totalLain = $this->lain($tagihanLain);
        $totalCoo = $this->coo($tagihanCoo, $idBukuOrder);
    //     $totalNotaRampung = isset($req['grand_total_nota_rampung']) && $req['grand_total_nota_rampung'] !== "" 
    // ? $req['grand_total_nota_rampung'] 
    // : 0;

        $totalPpn = ($totalKontainer + $totalPpjk + $totalLain + $totalJasa + $totalCoo) * $req['ppn'] / 100;
        $total = $totalKontainer  + $totalPpjk + $totalLain + $totalJasa + $totalCoo;
        $grandTotalPpn = $totalPpn + $total;

        return [
            'total' => $total,
            'total_setelah_ppn' => $grandTotalPpn 
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

    private function jasa($tagihan){
        $calculateJasa = 0;
        foreach($tagihan as $single){
            $calculateJasa += $single['tarif'];
        }
        return $calculateJasa;
        
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
        $calculatePpjk = 0 ;
        $collect = collect($ppjk);
        $count = $collect->count();
        $calculatePpjk = $tarif * $count;
        return $calculatePpjk;
    }

    private function coo($coo,$idBukuOrder){
        $calculateCoo = 0 ;
        $getBukuOrder = t_buku_order::where('id',$idBukuOrder)->first();
        $jumlahCoo = $getBukuOrder->jumlah_coo;
        $jumlahCooUlang = $getBukuOrder->jumlah_coo_ulang;
        $jumlahSemuaCoo = $jumlahCoo + $jumlahCooUlang;
        $calculateCoo = $jumlahSemuaCoo * $coo;
        return $calculateCoo;
    }

    private function lain($data){
        $calculateLain = 0 ;
        foreach($data as $single){
            $calculateLain += $single['nominal'];
        }
        return $calculateLain;
    }

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true];
    }
}
?>
