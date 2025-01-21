<?php

namespace App\Models\CustomModels;

class t_nota_rampung extends \App\Models\BasicModels\t_nota_rampung
{
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }

    public $fileColumns = ['foto_scn'];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $status = "DRAFT";
        $req = app()->request;
        if($req->post){
            $status = "POST";
        }
        $newData = [
            "no_draft" => $this->helper->generateNomor("Draft Nota Rampung"),
            "status" => $status,
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
        // DELETE FILE BEFORE IF IT CHANGES
        $prevData = $this->where('id', $id)->first();
        if($prevData->foto_scn !== $arrayData['foto_scn']){
            $file = $prevData->foto_scn;
            $baseUrl = url('/');
            $baseUrl = rtrim($baseUrl, '/');
            $path = parse_url($file, PHP_URL_PATH);
            \File::delete($path);
        }

        $status = $arrayData['status'];
        $req = app()->request;
        if ($req->post){
            $status = "POST";
        }
        $newData=[
            "status"=>$status
        ];
        $newArrayData  = array_merge( $arrayData,$newData );
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }
    
    public function deleteBefore( $model, $arrayData, $metaData, $id=null )
    {
        $file = $arrayData['foto_scn'];
        $baseUrl = url('/');
        $baseUrl = rtrim($baseUrl, '/');
        $path = parse_url($file, PHP_URL_PATH);
        \File::delete($path);
    }
    
    

    public function custom_post()
    {
        $id = request("id");
        $status = $this->where("id", $id)->update(["status" => "POST"]);
        return ["success" => true];
    }

    // public function custom_getDetailBukuOrder(){
    //     $id = request("id");
    //     $getDetail = \DB::raw
    // }

    // public function transformRowData( array $row )
    // {
    //     $data=[];
    //     if (app()->request->grand_total){
    //         $total = $row['lolo'] + $row['m2'] + $row['ow'] + $row['m3'] + $row['m4'] + $row['m5'] + $row['plg_mon'] 
    //                 + $row['ge'] + $row['strp_stuf'] + $row['canc_doc'] + $row['closing_container'] + $row['batal_muat'] + $row['vgm']
    //                 + $row['lolo_non_sp'];
    //         $data = [
    //             "total"=> $total,
    //         ];
    //     }
    //     return array_merge( $row, $data );
    // }

    public function grand_total($id){
        $idBukuOrder = @$id ?? 0;
        $getNotaRampung = t_nota_rampung::where('t_buku_order_id',$idBukuOrder)->get();
        $grandTotal = 0;
        foreach($getNotaRampung as $single){
             $total = $single['lolo'] + $single['m2'] + $single['ow'] + $single['m3'] + $single['m4'] + $single['m5'] + $single['plg_mon'] 
                    + $single['ge'] + $single['strp_stuf'] + $single['canc_doc'] + $single['closing_container'] + $single['batal_muat'] + $single['vgm']
                    + $single['mob'] + $single['denda_koreksi'] + $single['materai'] + $single['by_adm_nr'] + $single['denda_sp'];

            $grandTotal += $total;
        }
        return $grandTotal;
    }

    function custom_get_bo_d_npwp(){
        $id = request("id");

        $result = \DB::table('t_buku_order_d_npwp as tbodn')
        ->leftJoin('t_buku_order as tbo','tbo.id','tbodn.t_buku_order_id')
        ->select(
            'tbo.id as buku_order_id',
            'tbodn.*',
            \DB::raw("CONCAT(tbodn.no_prefix,'-',tbodn.no_suffix) as no_container")
        )
        ->where('tbo.id',$id)
        ->get();

        return $result;
    }
    
    public function custom_calculate($req){
        $grandTotal = 0;
        $pelabuhan = $req['pelabuhan'];
        $arrayContainer = $req['t_nota_rampung_d'];
        // trigger_error(json_encode($req));
        foreach ($arrayContainer as $single){    
            
            //Get Data
            $getTarif = m_tarif_nota_rampung::join('set.m_general as pl', 'pl.id','m_tarif_nota_rampung.kode_pelabuhan')
            ->join('set.m_general as uk','uk.id','m_tarif_nota_rampung.ukuran_container')
            ->join('set.m_general as jk','jk.id','m_tarif_nota_rampung.jenis_container')
            ->select('m_tarif_nota_rampung.*','pl.deskripsi','uk.id','jk.id')
            ->where('pl.deskripsi',$req['pelabuhan'])
            ->where('uk.id',$single['ukuran'])
            ->where('jk.id',$single['jenis'])
            ->first();
            
            if(!$getTarif) continue;
            
            //Map
            $grandTotal += $getTarif['tarif_lolo'] * ($single['lolo'] ?? 0);
            $grandTotal += $getTarif['tarif_m2'] * ($single['m2'] ?? 0);
            $grandTotal += $getTarif['tarif_m3'] * ($single['m3'] ?? 0);
            $grandTotal += $getTarif['tarif_m4'] * ($single['m4'] ?? 0);
            $grandTotal += $getTarif['tarif_m5'] * ($single['m5'] ?? 0);
            $grandTotal += $getTarif['tarif_ow'] * ($single['ow'] ?? 0);
            $grandTotal += $getTarif['tarif_plg_mon'] * ($single['plg_mon'] ?? 0);
            $grandTotal += $getTarif['tarif_ge'] * ($single['ge'] ?? 0);
            $grandTotal += $getTarif['tarif_container_doc'] * ($single['canc_doc'] ?? 0);
            $grandTotal += $getTarif['tarif_strtp_stuff'] * ($single['strp_stuf'] ?? 0);
            $grandTotal += $getTarif['tarif_batal_muat_pindah'] * ($single['batal_muat'] ?? 0);
            $grandTotal += $getTarif['tarif_closing_container'] * ($single['closing_container'] ?? 0);

            $grandTotal += $getTarif['tarif_vgm'] * ($single['vgm'] ?? 0);
            $grandTotal += $getTarif['tarif_mob'] * ($single['mob'] ?? 0);
            $grandTotal += $getTarif['tarif_denda_koreksi'] * ($single['denda_koreksi'] ?? 0);
            $grandTotal += $getTarif['tarif_materai'] * ($single['materai'] ?? 0);
            $grandTotal += $getTarif['tarif_by_adm_nr'] * ($single['by_adm_nr'] ?? 0);
            $grandTotal += $getTarif['tarif_denda_sp'] * ($single['denda_sp'] ?? 0);

        }
        $grandTotal += $req['lolo_non_sp'] ?? 0;
        
        return[
            'grand_total' => $grandTotal
        ];
    }
}
