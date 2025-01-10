@php
$req = app()->request;
$data = \DB::select("
select * from t_surat_jalan tsj left join t_buku_order tbo on tsj.t_buku_order_id = tbo.id left join m_customer mc on
tbo.m_customer_id = mc.id where tsj.id = ? ",[$req['id']]
);

/*$nospk = \DB::select("select tsj.*, tbo.*, tbodn.*, tsa.*, UPPER(mg.deskripsi) as deskripsi_container, mc.*, mg2.deskripsi as
deskripsi_jenis from t_surat_jalan tsj
left join t_buku_order tbo on tsj.t_buku_order_id = tbo.id
left join m_customer mc on tbo.m_customer_id = mc.id
left join t_buku_order_d_npwp tbodn on tbodn.t_buku_order_id = tbo.id
left join t_spk_angkutan tsa on tbodn.id = tsa.t_buku_order_1_id
left join \"set\".m_general mg on tsa.isi_container_1 = mg.id
left join \"set\".m_general mg2 on tbodn.jenis = mg2.id
where tsj.id = ? and tsa.status = 'APPROVED'",[$req['id']]);*/

$nospk = \DB::select("SELECT tsj.*, tsj.depo as depo_tsj, UPPER(mg3.deskripsi) as jenis_sj_deskripsi, tbo.*, mc.*, tbodn.*,  mg2.deskripsi as deskripsi_jenis
FROM t_surat_jalan tsj
LEFT JOIN t_buku_order tbo ON tsj.t_buku_order_id = tbo.id
LEFT JOIN m_customer mc ON tbo.m_customer_id = mc.id
LEFT JOIN t_buku_order_d_npwp tbodn ON tbodn.id = tsj.t_buku_order_d_npwp_id 
LEFT JOIN \"set\".m_general mg2 ON tbodn.jenis = mg2.id
left join \"set\".m_general mg3 on tsj.jenis_sj = mg3.id
WHERE tsj.id = ?",[$req['id']]); 

$count_spk = count($nospk);

$currentDate = date("d/m/Y");
$currentTime = date("H:i:s");

@endphp
@foreach ($nospk as $n)
@php
$unixTime = strtotime($n->tanggal_berangkat);

$tanggal_berangkat = date("d/m/Y", $unixTime);

//cek surat jalan impor atau ekspor
$cekImp = "";
  if($n->tipe_surat_jalan == "IMPORT"){
  $cekImp = "IMP";
  }
  else if ($n->tipe_surat_jalan == "EKSPORT"){
  $cekImp = "EXP";
  }

//cek jenis container
$jeniskontainer = "";
  if ($n->jenis_kontainer == "DRY CONTAINER"){
  $jeniskontainer = "DC";
  }
  else if ($n->jenis_kontainer == "FLAT RACK CONTAINER"){
  $jeniskontainer = "FRC";
  }
  else if ($n->jenis_kontainer == "OPEN TOP CONTAINER"){
  $jeniskontainer = "OTC";
  }
  else if ($n->jenis_kontainer == "TUNNEL CONTAINER"){
  $jeniskontainer = "TC";
  } 
  else if ($n->jenis_kontainer == "OPEN SIDE CONTAINER"){
  $jeniskontainer = "OSC";
  } 
  else if ($n->jenis_kontainer == "REEFER CONTAINER"){
  $jeniskontainer = "RR";
  } 
  else if ($n->jenis_kontainer == "INSULATED CONTAINER"){
  $jeniskontainer = "IC";
  } 
  else if ($n->jenis_kontainer == "ISO TANK"){
  $jeniskontainer = "ISO";
  }
  else if ($n->jenis_kontainer == "CARGO STORAGE ROLL"){
  $jeniskontainer = "CSR";
  }
  else if ($n->jenis_kontainer == "HALF CONTAINER"){
  $jeniskontainer = "HC";
  }
  else if ($n->jenis_kontainer == "CAR CARRIER"){
  $jeniskontainer = "CC";
  }
  else if ($n->jenis_kontainer == "VENTILATION CONTAINER"){
  $jeniskontainer = "VC";
  }
  else if ($n->jenis_kontainer == "SPECIAL PURPOSE CONTAINER"){
  $jeniskontainer = "SPC";
  }

//$container = \DB::select("select count('ukuran') as jumlah, ukuran from t_buku_order_d_npwp where
//t_buku_order_d_npwp.t_buku_order_id = ? group by ukuran ",[$n->t_buku_order_id]);

//$str = [];
//$count = 0;
//foreach ($container as $cont) {
//$str[$count] = $cont->jumlah . "x" . $cont->ukuran . "RR";
//$count += 1;
//}
//$format = implode(", ", $str);
@endphp
<div class="container">
  <!-- <pre>{{var_dump($count_spk)}}</pre> -->
  <table>
    <thead>
      <tr>
        <th colspan="2" class="underline-2"
          style="text-decoration: underline; font-weight: bold; height:30px; text-align:left;">SURAT JALAN / PENGANTAR
        </th>
        <th colspan="2" style="text-align:right;">
          {{$n->no_surat_jalan}}
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="width: 25%;">No. Order</td>
        <td style="width: 75%;">
          <span style="font-weight: normal;">:</span>
          <span style="font-weight: bold;">{{$n->no_buku_order}}</span>
        </td>
      </tr>
      <tr>
        <td style="width: 25%;">Tgl. Berangkat</td>
        <td style="width: 75%;">
          <span style="font-weight: normal;">:</span>
          <span style="font-weight: bold;">{{$tanggal_berangkat}}</span>
        </td>
      </tr>
      <tr>
        <td style="width: 25%;">Kepada Yth</td>
        <td style="width: 75%;">
          <span style="font-weight: normal;">: {{$n->nama_perusahaan}}</span>
        </td>
      </tr>
      <tr>
        <td style="width: 25%">Alamat</td>
        <td style="width: 75%;">
          <span style="font-weight: normal;">:</span>
          <span style="font-weight: bold;">{{$n->alamat}}</span>
        </td>
      </tr>
      <tr>
        <td style="border: none; border-bottom: 1px solid black; width: 100%;"></td>
      </tr>
      <tr>
        <td style="border: none; border-bottom: 1px solid black; width: 100%;"></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td style="width: 25%;">Nama Angk.</td>
        <td style="width: 3%;">
          <span style="font-weight: normal;">:</span>
        </td>
        <td style="width: 72%; border: none; border-bottom: 1px solid black;">{{$n->angkutan}}</td>
      </tr>
      <tr>
        <td style="width: 25%;">No. Polisi</td>
        <td style="width: 3%;">
          <span style="font-weight: normal;">:</span>
        </td>
        <td style="width: 72%; border: none; border-bottom: 1px solid black;"></td>
      </tr>
      <tr>
        <td style="width: 25%;">Pelabuhan</td>
        <td style="width: 3%;">
          <span style="font-weight: normal;">:</span>
        </td>
        <td style="width: 72%; border: none; border-bottom: 1px solid black;">{{$n->pelabuhan}}</td>
      </tr>
      <tr>
        <td style="width: 25%;">Depo</td>
        <td style="width: 3%;">
          <span style="font-weight: normal;">:</span>
        </td>
        <td style="width: 72%; border: none; border-bottom: 1px solid black;">{{$n->depo_tsj}}</td>
      </tr>
      <tr>
        <td style="width: 25%;">Kapal</td>
        <td style="width: 3%;">
          <span style="font-weight: normal;">:</span>
        </td>
        <td style="width: 72%; border: none; border-bottom: 1px solid black;">{{$n->kapal}}</td>
      </tr>
      <tr>
        <td></td>
      </tr>
  </table>
  <table border="0" style="border-collapse: collapse; width: 100%;">
    <thead>
      <tr>
        <th colspan="3" style="height: 10px; border-top: 1px solid black; border-right: 1px solid black;"></th>
        <th colspan="2" style="height: 10px; border-top: 1px solid black;"></th>
      </tr>
      <tr>
        <th colspan="3" style="text-align: center; border-right: 1px solid black; border-left: none;">
          <span>Keterangan</span>
        </th>
        <th colspan="2" style="text-align: center; border-right: none; border-left: none;">
          Jenis Barang
        </th>
      </tr>
      <tr>
        <th colspan="3" style="height: 10px; border-bottom: 1px solid black; border-right: 1px solid black;"></th>
        <th colspan="2" style="height: 10px; border-bottom: 1px solid black;"></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td colspan="3" style="border-right: 1px solid black;"></td>
        <td colspan="2" style="border-left: 1px solid black;">
          {{$n->jenis_barang}}
        </td>
      </tr>
      <tr>
        <td style="width: 25%;">Type Cont</td>
        <td style="width: 3%;">
          <span style="font-weight: normal;">:</span>
        </td>
        <td style="width: 30%; border: none; border-bottom: 1px solid black; font-weight: bold;">1x{{$n->ukuran_kontainer}} {{$jeniskontainer}}</td>
        <td style="width: 2%; border: none;"></td>
        <td colspan="2" style="border-left: 1px solid black;"></td>
      </tr>
      <tr>
        <td style="width: 25%;">No. Cont</td>
        <td style="width: 3%;">
          <span style="font-weight: normal;">:</span>
        </td>
        <td style="width: 30%; border: none; border-bottom: 1px solid black; font-weight: bold;">
        </td>
        <td style="width: 2%; border: none;"></td>
        <td colspan="3" style="border-left: 1px solid black;"></td>
      </tr>
      <tr>
        <td style="width: 25%;">No. Seal</td>
        <td style="width: 3%;">
          <span style="font-weight: normal;">:</span>
        </td>
        <td style="width: 30%; border: none; border-bottom: 1px solid black; font-weight: bold;"></td>
        <td style="width: 2%; border: none;"></td>
        <td colspan="2" style="border-left: 1px solid black;"></td>
      </tr>
      <tr>
        <td style="width: 25%;"></td>
        <td style="width: 3%;">
        </td>
        <td style="width: 30%; border: none;"></td>
        <td style="width: 2%; border: none;"></td>
        <td colspan="2" style="border-left: 1px solid black;"></td>
      </tr>
      <tr>
        <td style="width: 25%;"></td>
        <td style="width: 3%;"></td>
        <td style="width: 30%; border: none;"></td>
        <td style="width: 2%; border: none;"></td>
        <td style="width: 1%; border-left: 1px solid black;"></td>
        <td colspan="2"
          style="border-left: 1px solid black; border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; text-align: center; font-weight: bold; font-size: 20pt;">
          {{$cekImp}} {{$n->jenis_sj_deskripsi}}
        </td>
      </tr>
      <tr>
        <td style="width: 25%;"></td>
        <td style="width: 3%;">
        </td>
        <td style="width: 30%; border: none;"></td>
        <td style="width: 2%; border: none;"></td>
        <td colspan="2" style="border-left: 1px solid black;"></td>
      </tr>
      <tr>
        <td style="width: 25%; border-left: 1px solid black; border-top: 1px solid black;">NW</td>
        <td style="width: 3%;  border-top: 1px solid black">
          <span style="font-weight: normal;  border-top: 1px solid black;">:</span>
        </td>
        <td
          style="width: 30%; border: none; border-bottom: 1px solid black; font-weight: bold; border-top: 1px solid black;">{{$n->nw}}</td>
        <td style="width: 1.25%; border: none;  border-top: 1px solid black; border-right: 1px solid black;"></td>
        <td style="width: 0.75%; border: none;  border-right: 1px solid black;"></td>
        <td colspan="3" style="border-left: 1px solid black;"></td>
      </tr>
      <tr>
        <td style="width: 25%; border-left: 1px solid black;">GW</td>
        <td style="width: 3%;">
          <span style="font-weight: normal;">:</span>
        </td>
        <td style="width: 30%; border: none; border-bottom: 1px solid black; font-weight: bold;">{{$n->gw}}</td>
        <td style="width: 1.25%; border: none; border-right: 1px solid black;"></td>
        <td style="width: 0.75%; border: none;  border-right: 1px solid black;"></td>
        <td colspan="3" style="border-left: 1px solid black;"></td>
      </tr>
      <tr>
        <td style="width: 25%; border-left: 1px solid black;">TARE</td>
        <td style="width: 3%;  ">
          <span style="font-weight: normal;">:</span>
        </td>
        <td style="width: 30%; border: none; border-bottom: 1px solid black; font-weight: bold; "></td>
        <td style="width: 1.25%; border: none; border-right: 1px solid black;"></td>
        <td style="width: 0.75%; border: none; border-right: 1px solid black; "></td>
        <td colspan="3" style="border-left: 1px solid black;"></td>
      </tr>
      <tr>
        <td style="width: 25%; border-left: 1px solid black;  border-bottom: 1px solid black;"></td>
        <td style="width: 3%;   border-bottom: 1px solid black;">
          <span style="font-weight: normal; border-bottom: 1px solid black;"></span>
        </td>
        <td style="width: 30%; border: none; border-bottom: 1px solid black; font-weight: bold; "></td>
        <td style="width: 1.25%; border: none; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
        <td style="width: 0.75%; border: none; border-right: 1px solid black; "></td>
        <td colspan="3" style="border-left: 1px solid black;"></td>
      </tr>
      <tr>
        <td style="width: 25%;  border-bottom: 1px solid black;"></td>
        <td style="width: 3%;   border-bottom: 1px solid black;">
          <span style="font-weight: normal; border-bottom: 1px solid black;"></span>
        </td>
        <td style="width: 30%; border: none; border-bottom: 1px solid black; font-weight: bold; "></td>
        <td style="width: 1.25%; border: none; border-bottom: 1px solid black;"></td>
        <td style="width: 0.75%; border: none; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
        <td style="width: 40%; border: none; border-bottom: 1px solid black;"></td>
      </tr>
      <tr>
        <td style="width: 100%">Barang-barang tersebut diatas harap diterima dengan baik.</td>
      </tr>
      <tr>
        <td style="text-align: center; width: 25%;">Penerima,</td>
        <td style="text-align: center; width: 43%;">Pembawa,</td>
        <td style="text-align: center; width: 30%;">Pengirim,</td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td style="text-align: center; width: 25%;">
          (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        </td>
        <td style="text-align: center; width: 43%;">
          (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        </td>
        <td style="text-align: center; width: 30%;">
          (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        </td>
      </tr>
      <tr>
        <td style="text-align: center; width: 25%;">Nama Terang</td>
        <td style="text-align: center; width: 43%;">Nama Terang</td>
        <td style="text-align: center; width: 30%;">Nama Terang</td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td style="border: none; border-bottom: 1px solid black; width: 100%;">PERHATIAN :</td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td>1. Untuk memudahkan pemeriksaan agar diisi menurut keadaan yang sebenarnya.</td>
      </tr>
      <tr>
        <td>2. Berat barang supaya ditulis dengan teliti dan jelas dalam TON.</td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td style="font-weight: bold; border: none; border-bottom: 1px solid black;">Dicetak pada tgl : {{$currentDate}}
          jam
          {{$currentTime}} Operator : DEWI-PC # dewi</td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr>
    </tbody>
  </table>

</div>
@endforeach