<?php

namespace App\Cores;

use Carbon\Carbon;
use DB;
use App\Models\CustomModels\generate_num;
use App\Models\CustomModels\generate_num_type;
use App\Models\CustomModels\generate_num_log;
use App\Models\CustomModels\generate_num_det;
use App\Models\CustomModels\inv_card_stock;
use \stdClass;

class Helper
{
    function __construct()
    {
        $this->timestamp = \Carbon\Carbon::now();
    }

    public function generateNomor($nama, $counter = true, $static = null)
    {
        // check header config
        $generate_num = generate_num::where("nama", $nama)
            ->where("is_active", true)
            ->first();

        if (!$static && !$generate_num) {
            trigger_error("Format penomoran tidak ditemukan");
        }

        DB::beginTransaction();

        try {
            // check details config and assemble code
            $temporaryCode = "";

            if ($static) {
                $generate_num_det = $static;
            } else {
                $generate_num_det = generate_num_det::where(
                    "generate_num_id",
                    $generate_num->id
                )
                    ->orderBy("seq", "asc")
                    ->get();
            }

            foreach ($generate_num_det as $tnd) {
                $trx_type = generate_num_type::find(
                    @$tnd["generate_num_type_id"]
                );

                if ($trx_type) {
                    if ($trx_type->ref_type === "text") {
                        // type text
                        $temporaryCode .= (string) $trx_type->value;
                    } elseif (
                        in_array($trx_type->ref_type, ["day", "month", "year"])
                    ) {
                        // type dating
                        $temporaryCode .= date($trx_type->value);
                    } elseif ($trx_type->ref_type === "seq") {
                        // type seq
                        $table = "generate_num";
                        $length = (int) $trx_type->value ?? 6;
                        $lastDataQuery = generate_num_log::where(
                            "nama",
                            @$generate_num->nama
                        )
                            ->where("table", $table)
                            ->orderBy("created_at", "DESC");

                        $latest = $lastDataQuery->pluck("seq")->first();

                        if (!$latest) {
                            $latest = "";

                            for ($i = 0; $i < $length; $i++) {
                                $latest .= "0";
                            }
                        }

                        $latest = sprintf("%0" . $length . "d", $latest + 1);
                        $temporaryCode .= $latest;

                        if ($counter && !$static) {
                            if ($lastDataQuery->exists()) {
                                generate_num_log::where("table", $table)
                                    ->where("nama", $generate_num->nama)
                                    ->update([
                                        "value" => $temporaryCode,
                                        "seq" => $latest,
                                    ]);
                            } else {
                                generate_num_log::create([
                                    "table" => $table,
                                    "nama" => $generate_num->nama,
                                    "value" => $temporaryCode,
                                    "seq" => $latest,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseCatch($e);
        }

        return $temporaryCode;
    }

    public function formatTime($time) {
        return date("H:i", strtotime($time));
    }

    public function formatTanggalIndonesia($tanggal) {
        $bulan = [
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
            'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
            'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
            'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
        ];

        $date = new \DateTime($tanggal);
        $formattedDate = $date->format('d F Y'); // Contoh: 05 February 2025
        return strtr($formattedDate, $bulan); // Mengubah bulan ke bahasa Indonesia
    }

    public function monthToRoman($month)
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

    public function terbilang($x)
    {
        $angka = [
            "",
            "Satu",
            "Dua",
            "Tiga",
            "Empat",
            "Lima",
            "Enam",
            "Tujuh",
            "Delapan",
            "Sembilan",
            "Sepuluh",
            "Sebelas",
        ];

        if ($x < 12) {
            return " " . $angka[$x];
        } elseif ($x < 20) {
            return $this->terbilang($x - 10) . " Belas ";
        } elseif ($x < 100) {
            return $this->terbilang($x / 10) .
                " Puluh " .
                $this->terbilang($x % 10);
        } elseif ($x < 200) {
            return "Seratus" . $this->terbilang($x - 100);
        } elseif ($x < 1000) {
            return $this->terbilang($x / 100) .
                " Ratus" .
                $this->terbilang($x % 100);
        } elseif ($x < 2000) {
            return "Seribu" . $this->terbilang($x - 1000);
        } elseif ($x < 1000000) {
            return $this->terbilang($x / 1000) .
                " Ribu " .
                $this->terbilang($x % 1000);
        } elseif ($x < 1000000000) {
            return $this->terbilang($x / 1000000) .
                " Juta " .
                $this->terbilang($x % 1000000);
        }
    }

    public function responseValidate($validator)
    {
        $err = [];
        $errText = "";
        $error = $validator->messages()->toArray();

        foreach ($error as $key => $value) {
            $err[$key] = $value[0];
            if (count($error) > 1) {
                $errText .= $value[0] . "<br>";
            } else {
                $errText .= $value[0];
            }
        }

        $data = [
            "errors" => $err,
            "errorText" => $errText,
        ];

        return response(
            [
                "timestamp" => Carbon::now()->format("d-m-Y H:i:s"),
                "code" => 422,
                "message" => "Cek kembali form yang anda kirim.",
                "data" => $data,
            ],
            422
        );
    }

    public function customResponse(
        $message = "OK",
        $code = 200,
        $basic = null,
        $noData = true
    ) {
        if (!in_array($code, [200, 201])) {
            $err = [];
            $errText = "";
            $error = [$basic ?? $message];

            if (!$basic) {
                foreach ($error as $key => $value) {
                    $err[$key] = $value;
                    if ($key != 0) {
                        $errText .= $value . "<br>";
                    } else {
                        $errText .= $value;
                    }
                }

                $data = [
                    "errors" => $err,
                    "errorText" => $errText,
                ];
            } else {
                $data = $basic ?? [$message];
            }
        } else {
            if (!$noData) {
                $data = [
                    "data" => $basic ?? [$message],
                ];
            } else {
                $data = $basic ?? [$message];
            }
        }

        return response(
            [
                "timestamp" => Carbon::now()->format("d-m-Y H:i:s"),
                "code" => $code,
                "message" => $message,
                "data" => $data,
            ],
            $code
        );
    }

    public function responseCatch($e)
    {
        return response(
            [
                "timestamp" => Carbon::now()->format("d-m-Y H:i:s"),
                "code" => 400,
                "message" => $e->getMessage(),
                "data" => [
                    "errors" => [
                        $e->getMessage() .
                        "-" .
                        $e->getLine() .
                        "-" .
                        $e->getFile(),
                    ],
                    "errorText" => $e->getMessage(),
                ],
            ],
            400
        );
    }

    public function snakeCaseToCapitalize($str)
    {
        $words = explode('_', $str);
        $capitalizedWords = array_map('ucfirst', $words);
        $result = implode(' ', $capitalizedWords);

        return $result;
    }

   public function midtransConn($data, $url) {
        $serverKey = 'SB-Mid-server-0VQPFdz3p3o84RcwVrWCFE4i';
        // $url = 'https://app.sandbox.midtrans.com/snap/v1/transactions';
        // $url = 'https://api.sandbox.midtrans.com/v2/charge';
        $auth = base64_encode($serverKey. ':');
        $headers = array(
            'Accept: application/json',
            'Authorization: Basic ' . $auth,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

}
