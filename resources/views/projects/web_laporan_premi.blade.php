@php
$helper = getCore('Helper');
  function formatUang($amount = 0) {
  return number_format($amount, 0, ',', '.');
  }

  $req = app()->request;

  $data = \DB::select('select b.no_spk, a.tgl, b.dari, b.ke, c.no_buku_order, ca.no_buku_order no_buku_order2, d.nama_perusahaan, a.no_premi,
  ukuran.deskripsi ukuran,
  trip.kode trip, f.nama supir, b.sangu, a.tarif_premi, a.tol,
  coalesce((select sum(ab.nominal) from t_premi_d ab where ab.t_premi_id = a.id group by ab.t_premi_id limit 1), 0)
  lain_lain,
  coalesce((select sum(ac.nominal) from t_ganti_solar ac where ac.t_spk_angkutan_id = b.id group by ac.t_spk_angkutan_id
  limit 1), 0) ganti_solar,
  a.catatan
  from t_premi a
  left join t_spk_angkutan b on b.id = a.t_spk_angkutan_id
  left join t_buku_order c on c.id = b.t_buku_order_1_id
  left join t_buku_order ca on c.id = b.t_buku_order_2_id
  left join m_customer d on d.id = c.m_customer_id
  left join t_buku_order_d_npwp e on e.t_buku_order_id = c.id
  left join set.m_general ukuran on ukuran.id = e.ukuran
  left join set.m_general trip on trip.id = b.trip_id
  left join set.m_kary f on f.id = b.supir
  where b.supir = ?
  group by a.id, b.id, c.id, ca.id, d.id, e.id, ukuran.id, trip.id, f.id
  order by a.created_at desc', [$req->supir_id]);

  $supir = @$data[0]->supir;

  $grandtotal = [
    "sangu" => 0,
    "tarif_premi" => 0,
    "tol" => 0,
    "lain_lain" => 0,
    "ganti_solar" => 0,
    "total" => 0,
  ];
@endphp
<div>
  <table>
    <tr>
      <td
        style="font-family: sans-serif; font-size: 14px; font-weight: bold; width: 20%; border-bottom: 0.5px solid black;">Laporan Premi</td>
    </tr>
    <tr>
      <td style="border-bottom: 0.5px solid black; width: 25%; font-weight: bold;">yang sudah dicetak tidak bisa</td>
    </tr>
    <tr>
      <td colspan="2" style="width: 100%; font-weight: bold;">Akan Di Premikan No : 50/PRMI`II/25</td>
    </tr>
    <tr>
      <td style="font-weight: bold;">Periode
        <span>:</span>
        <span>{{$helper->formatTanggalIndonesia($req->periode_awal)}} s/d {{$helper->formatTanggalIndonesia($req->periode_akhir)}}</span>
      </td>
    </tr>
    <tr>
      <td style="font-weight: bold; width: 78%">Periode Hutang
        <span>:</span>
        <span>{{$helper->formatTanggalIndonesia($req->periode_awal)}} s/d {{$helper->formatTanggalIndonesia($req->periode_akhir)}}</span>
      </td>
      <td style="font-size: 8px; font-style: italic; font-weight: bold;">Dicetak pada tanggal: 22/02/2025 jam 13:54:35
      </td>
    </tr>
  </table>
  <tr>
    <td></td>
  </tr>
  <table border="0.5" width="100%" cellpadding="top" align="center" style="font-size: 10px; border-collapse: collapse;">
    <tr>
      <td style="line-height: 20px; width:6.65%;">Order Angk.</td>
      <td style="line-height: 40px; width:6.8%;">Tanggal</td>
      <td style="line-height: 40px; width:5.88%;">Dari</td>
      <td style="line-height: 40px; width:5.88%;">Ke</td>
      <td style="line-height: 20px;">No. Order Tia</td>
      <td style="line-height: 20px; width:5.88%;">Exp/<br>Imp</td>
      <td style="line-height: 20px; width:4%;">TR <br> No.</td>
      <td style="line-height: 20px; width:4%;">20/ <br>40</td>
      <td style="line-height: 40px; width:4%;">Trip</td>
      <td style="line-height: 40px; width:5.88%;">Supir</td>
      <td style="line-height: 20px; width:6.5%;">Sangu (Rp.)</td>
      <td style="line-height: 20px; width:6.5%;">Premi &nbsp;(Rp.)</td>
      <td style="line-height: 40px; width:6.5%;">Tol (Rp.)</td>
      <td style="line-height: 20px; width:6.1%;">Lain-Lain (Rp.)</td>
      <td style="line-height: 20px; width:6%;">Tmb Solar (Rp.)</td>
      <td style="line-height: 20px; width:5.88%;">Total (Rp.)</td>
      <td style="line-height: 40px; width:7.70%;">Ket.</td>
    </tr>
    <tr style="font-style: italic;">
      <td style="width: 100%; text-align: start; line-height:20px;">&nbsp;&nbsp;Nama
        <span>:</span>
        <span>{{@$supir ?? '-'}}</span>
      </td>
    </tr>
  </table>
  <table border="0.5" width="100%" style="font-size: 8px;">
    @foreach($data as $dt)
    @php
    $total = (((float) @$dt->tarif_premi ?? 0) + ((float) @$dt->tol ?? 0) + ((float) @$dt->lain_lain ?? 0) + ((float)
    @$dt->ganti_solar ?? 0)) - ((float) @$dt->sangu ?? 0);

    $grandtotal['sangu'] += @$dt->sangu ?? 0;
    $grandtotal['tarif_premi'] += @$dt->tarif_premi ?? 0;
    $grandtotal['tol'] += @$dt->tol ?? 0;
    $grandtotal['lain_lain'] += @$dt->lain_lain ?? 0;
    $grandtotal['ganti_solar'] += @$dt->ganti_solar ?? 0;
    $grandtotal['total'] += @$total ?? 0;

    @endphp
    <tr>
      <td style="text-align: center; width:6.65%;">{{@$dt->no_spk}}</td>
      <td style="text-align: center; width:6.8%;">{{@$dt->tgl}}</td>
      <td style="text-align: center; width:5.88%;">{{@$dt->dari}}</td>
      <td style="text-align: center; width:5.88%;">{{@$dt->ke}}</td>
      <td style="text-align: center; width:5.88%;">{{@$dt->no_buku_order}} {{@$dt->no_buku_order2 ? ', ' . @$dt->no_buku_order2 : ''}}</td>
      <td style="text-align: center; width:5.88%;">{{@$dt->nama_perusahaan}}</td>
      <td style="text-align: center; width:4%;">{{@$dt->no_premi}}</td>
      <td style="text-align: center; width:4%;">{{@$dt->ukuran}}</td>
      <td style="text-align: center; width:4%;">{{@$dt->trip}}</td>
      <td style=" text-align: center; width:5.85%;">{{@$dt->supir}}</td>
      <td style="text-align: center; width:6.5%;">{{ formatUang(@$dt->sangu) }}</td>
      <td style="text-align: center; width:6.5%;">{{ formatUang(@$dt->tarif_premi) }}</td>
      <td style="text-align: center; width:6.5%;">{{ formatUang(@$dt->tol) }}</td>
      <td style="text-align: center; width:6.1%;">{{ formatUang(@$dt->lain_lain) }}</td>
      <td style="text-align: center; width:6%;">{{ formatUang(@$dt->ganti_solar) }}</td>
      <td style="text-align: center; width:5.88%;">{{ formatUang(@$total) }}</td>
      <td style="text-align: center; width:7.70%;">{{ @$dt->catatan }}</td>
    </tr>
    @endforeach
  </table>
  <table style="font-size: 8px;" width="100%">
    <tr>
      <td style="width: 10%;">Awal</td>
      <td style="width: 2%;">:</td>
      <td style="text-align: right; width: 9%;">34.357.100</td>
      <td style="width: 6.5%;"></td>
      <td></td>
      <td style="width: 7.12%;"></td>
      <td style="width: 7.2%; text-align: center;">Total :</td>
      <td style="width: 5.85%; text-align: center; border: 0.5px solid black;">{{@$supir ?? '-'}}</td>
      <td style="width: 6.5%; text-align: center; border: 0.5px solid black;">{{formatUang(@$grandtotal['sangu'])}}</td>
      <td style="width: 6.5%; text-align: center; border: 0.5px solid black;">{{formatUang(@$grandtotal['tarif_premi'])}}</td>
      <td style="width: 6.5%; text-align: center; border: 0.5px solid black;">{{formatUang(@$grandtotal['tol'])}}</td>
      <td style="width: 6.1%; text-align: center; border: 0.5px solid black;">{{formatUang(@$grandtotal['lain_lain'])}}</td>
      <td style="width: 6%; text-align: center; border: 0.5px solid black;">{{formatUang(@$grandtotal['ganti_solar'])}}</td>
      <td style="width: 5.88%;  text-align: center; border: 0.5px solid black;">{{formatUang(@$grandtotal['total'])}}</td>
    </tr>
    <tr>
      <td style="width: 10%;">Pinjaman</td>
      <td style="width: 2%;">:</td>
      <td style="text-align: right; width: 9%;">0</td>
      <td style="width: 6.5%;"></td>
      <td></td>
      <td style="width: 7.12%;"></td>
      <td style="width: 7.2%;"></td>
      <td style="width: 5.85%; text-align: center"></td>
      <td style="width: 6.5%; text-align: center"></td>
      <td style="width: 6.5%; text-align: center"></td>
      <td style="width: 6.5%; text-align: center"></td>
      <td style="width: 6.1%; text-align: center"></td>
      <td style="width: 4%; text-align: center"></td>
      <td style="width: 7.88%; text-align: right; border: 0.5px solid black;">388.000</td>
    </tr>
    <tr>
      <td style="width: 10%;">Cicilan</td>
      <td style="width: 2%;">:</td>
      <td style="text-align: right; width: 9%;">388.000</td>
      <td style="width: 6.5%;"></td>
      <td></td>
      <td style="width: 7.12%;"></td>
      <td style="width: 7.2%;"></td>
      <td style="width: 5.85%; text-align: center"></td>
      <td style="width: 6.5%; text-align: center"></td>
      <td style="width: 6.5%; text-align: center"></td>
      <td style="width: 6.5%; text-align: center"></td>
      <td style="width: 6.1%; text-align: center"></td>
      <td style="width: 4%; text-align: center"></td>
      <td style="width: 7.88%; text-align: right; border: 0.5px solid black;">350.000</td>
    </tr>
    <tr>
      <td style="width: 10%;">Saldo</td>
      <td style="width: 2%;">:</td>
      <td style="text-align: right; width: 9%;">33.969.100</td>
    </tr>
  </table>
  <tr>
    <td></td>
  </tr>
  <table style="font-size: 8px;">
    <tr>
      <td>Dicetak pada tgl : 03/03/2025 jam 10:32:15 Operator : DEWI-PC # dewi by TIA Sentosa Makmur</td>
    </tr>
  </table>
</div>