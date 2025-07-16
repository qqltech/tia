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
        }

        $noBukuOrder = $this->helper->generateNomor($kodeTipe);
        
        $currentMonth = date('M');  
        $romanMonth = $this->convertMonthToRoman($currentMonth);

        $currentYear = date('y');

         $formattedNoBukuOrder = $noBukuOrder . $romanMonth ."-".$currentYear;

        $newData = [
            "no_buku_order" => $formattedNoBukuOrder,
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
    
    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true];
    }

    // public function scopeGetTarif($model){
    //     return $model->join('m_tarif as tf','m_customer.id','tf.m_customer_id');
    // }

    private function getTarif($customerID){
        return m_tarif::where('m_customer_id',$customerID)->where('is_active', true)->get();
    }

    public function transformRowData( array $row )
    {
        $data = [];
        if(app()->request->view_tarif){
            // $notaRampung = new t_nota_rampung;
            // $getNotaRampung = $notaRampung->grand_total($row['id']);
            // $tarif = $this->getTarif($row['m_customer.id']);
            $data = [];
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
        return $model->with('relationPpjk');
    }

    public function scopeDetailcust($model){
        $id = request("id");
        return $model->where("id",$id)->with('m_customer');
    }
    // public function scopeKontainerNR($model){
    //     $id = request("id");

    //     $model->where('id',$id)

    // }
}