<?php
if (!function_exists("pre")) {
    function pre($param = array())
    {
        echo "<PRE>";
        print_r($param);
        exit;
    }
}


function formula($type_instrument_name, $data_jadi, $data, $koefisien)
{
    try {
        $hasil = [];

        $instrument_functions = [
            "Pressure - AWLR" => "pressureawlr",
            "Ultrasonic - AWLR" => "ultrasonicawlr",
            "Ultrasonic - Evaporation" => "ultrasonicevaporation",
            "Hall Effect" => "hall_effect",
            "Standard" => "standard",
            "Tipping Bucket" => "tipping_bucket",
            "Vnotch" => "vnotch",
            "Pressure - VNotch" => "vnotch",
            "Open Stand Pipe" => "open_stand_pipe",
            "Piezometer" => "piezometer",
            "Pressure Cell" => "pressurecell",
            "Thermometer" => "thermometer",
            "Strain Meter Rosette" => "strainmeterrosette",
            "Tiltmeter" => "tiltmeter",
            "Tiltmeter 0510" => "tiltmeter",
            "Tiltmeter 1530" => "tiltmeter1530",
            "Seismograph" => "seismograph",
            "Accelerometer" => "accelerometer",

        ];
        if (!array_key_exists($type_instrument_name, $instrument_functions)) {
            throw new Exception();
        }

        $function_name = $instrument_functions[$type_instrument_name];

        $data_tambahan = array();
        foreach ($data_jadi as $row) {
            $data_mentah = $data["data_mentah"];


            $action = $function_name($row, $data_mentah, $koefisien, $data_tambahan);
            if (!$action) {
                throw new Exception();
            }
            $hasil[] = $action;
        }


        return $hasil;
    } catch (Exception $e) {
        return false;
    }
}

function piezometer($data_jadi, $data_mentah, $koefisien, &$data_tambahan)
{
    try {
        switch ($data_jadi->kode_sensor_jadi) {
            case "tekanan_air_kpa":
                $hitung = (((float)$koefisien['faktor_a'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ((float)$koefisien['faktor_b'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + (float)$koefisien['faktor_c']) - ((float)$koefisien['tct'] * (((float)$data_mentah['suhu'] + (float)$koefisien['kalibrasi_suhu']) - (float)$koefisien['t0'])));
                $data_tambahan['tekanan_air_kpa'] = null;
                $data_tambahan['tekanan_air_kpa'] += $hitung;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "tekanan_air_kgcm2":
                $hitung = (((float)$koefisien['faktor_a'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ((float)$koefisien['faktor_b'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + (float)$koefisien['faktor_c']) - ((float)$koefisien['tct'] * (((float)$data_mentah['suhu'] + (float)$koefisien['kalibrasi_suhu']) - (float)$koefisien['t0'])));
                $data_tambahan['tekanan_air_kgcm2'] = null;
                $data_tambahan['tekanan_air_kgcm2'] += $hitung;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "tekanan_air_mh2o":
                if ($data_tambahan['tekanan_air_kpa'] !== null) {
                    $hitung = $data_tambahan['tekanan_air_kpa'] * 0.1022;
                } else if ($data_tambahan['tekanan_air_kgcm2']  !== null) {
                    $hitung = $data_tambahan['tekanan_air_kgcm2'] * 10.0003;
                } else {
                    $hitung = 0;
                }
                $data_tambahan['tekanan_air_mh2o'] = 0;
                $data_tambahan['tekanan_air_mh2o'] += $hitung;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "level_freatik_air":
                $hitung = (float)$koefisien['elevasi_sensor'] + $data_tambahan['tekanan_air_mh2o'];
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            default:
                // return ['tes'];
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function pressurecell($data_jadi, $data_mentah, $koefisien, &$data_tambahan)
{

    try {

        switch ($data_jadi->kode_sensor_jadi) {
            case "tekanan_tanah_kgcm2":
                $hitung = (((float)$koefisien['faktor_a'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ((float)$koefisien['faktor_b'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + (float)$koefisien['faktor_c']) - ((float)$koefisien['tct'] * (((float)$data_mentah['suhu'] + (float)$koefisien['kalibrasi_suhu']) - (float)$koefisien['t0'])));
                $data_tambahan['tekanan_tanah_kgcm2'] = 0;
                $data_tambahan['tekanan_tanah_kgcm2'] += $hitung;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "tekanan_tanah_mpa":
                $data_tambahan['tekanan_tanah_kgcm2'] = 0;
                $hitung = $data_tambahan['tekanan_tanah_kgcm2'] * 0.098;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "tekanan_air_mh2o":
                $hitung = $data_tambahan['tekanan_tanah_kgcm2'] * 10.0003;
                $data_tambahan['tekanan_air_mh2o'] = 0;
                $data_tambahan['tekanan_air_mh2o'] += $hitung;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "level_freatik_air":
                $hitung = (float)$koefisien['elevasi_sensor'] + $data_tambahan['tekanan_air_mh2o'];
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function pressureawlr($data_jadi, $data_mentah, $koefisien)
{
    try {
        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        $elevasi_sensor = isset($koefisien['elevasi_sensor']) ? (float)$koefisien['elevasi_sensor'] : 0;
        switch ($data_jadi->kode_sensor_jadi) {
            case "tinggi_muka_air":
                $hitung = (float)$data_mentah["ketinggian_air"] + $kalibrasi + $elevasi_sensor;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}
function ultrasonicawlr($data_jadi, $data_mentah, $koefisien)
{
    try {
        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        $elevasi_sensor = isset($koefisien['elevasi_sensor']) ? (float)$koefisien['elevasi_sensor'] : 0;
        switch ($data_jadi->kode_sensor_jadi) {
            case "tinggi_muka_air":
                $hitung = $elevasi_sensor - ((float)$data_mentah["ketinggian_air"] + $kalibrasi);
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}
function ultrasonicevaporation($data_jadi, $data_mentah, $koefisien)
{
    try {
        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        $tinggi_sensor = isset($koefisien['tinggi_sensor']) ? (float)$koefisien['tinggi_sensor'] : 0;
        switch ($data_jadi->kode_sensor_jadi) {
            case "evaporation":
                $hitung = $tinggi_sensor - ((float)$data_mentah["evaporation"] +  $kalibrasi);
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function hall_effect($data_jadi, $data_mentah, $koefisien)
{
    try {
        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        switch ($data_jadi->kode_sensor_jadi) {
            case "wind_direction":
                $arah_mata_angin = (float)$data_mentah["derajat_arah_angin"] + $kalibrasi;

                if ($arah_mata_angin > 337.5 || $arah_mata_angin < 22.5) {
                    $arah_angin = "Utara";
                } elseif ($arah_mata_angin >= 22.5 && $arah_mata_angin < 67.5) {
                    $arah_angin = "Timur Laut";
                } elseif ($arah_mata_angin >= 67.5 && $arah_mata_angin < 112.5) {
                    $arah_angin = "Timur";
                } elseif ($arah_mata_angin >= 112.5 && $arah_mata_angin < 157.5) {
                    $arah_angin = "Tenggara";
                } elseif ($arah_mata_angin >= 157.5 && $arah_mata_angin < 202.5) {
                    $arah_angin = "Selatan";
                } elseif ($arah_mata_angin >= 202.5 && $arah_mata_angin < 247.5) {
                    $arah_angin = "Barat Daya";
                } elseif ($arah_mata_angin >= 247.5 && $arah_mata_angin < 292.5) {
                    $arah_angin = "Barat";
                } elseif ($arah_mata_angin >= 292.5 && $arah_mata_angin < 337.5) {
                    $arah_angin = "Barat Laut";
                }

                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => $arah_angin];
                break;
            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function standard($data_jadi, $data_mentah, $koefisien)
{
    try {
        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        switch ($data_jadi->kode_sensor_jadi) {
            case "wind_speed":
                $hitung = (float)$data_mentah["wind_speed"] + $kalibrasi;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "wind_direction":
                $hitung = (float)$data_mentah["wind_direction"] + $kalibrasi;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "air_temperature":
                $hitung = (float)$data_mentah["air_temperature"] + $kalibrasi;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "air_humidity":
                $hitung = (float)$data_mentah["air_humidity"] + $kalibrasi;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "air_pressure":
                $hitung = (float)$data_mentah["air_pressure"] + $kalibrasi;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "solar_radiation":
                $hitung = (float)$data_mentah["solar_radiation"] + $kalibrasi;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "ketinggian_air":
                $hitung = (float)$data_mentah["ketinggian_air"] + $kalibrasi;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "accelerometer":
                $hitung = (float)$data_mentah["accelerometer"] + $kalibrasi;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "seismometer":
                $hitung = (float)$data_mentah["seismometer"] + $kalibrasi;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "status_siaga":
                $status_siaga = (float)$data_mentah["status_siaga"] + $kalibrasi;
                if ($status_siaga >= 4) {
                    $status = "Awas";
                } elseif ($status_siaga < 4 and $status_siaga >= 3) {
                    $status = "Siaga";
                } elseif ($status_siaga < 3 and $status_siaga >= 2) {
                    $status = "Waspada";
                } elseif ($status_siaga < 2 and $status_siaga >= 1) {
                    $status = "Normal";
                } elseif ($status_siaga < 1) {
                    $status = "-";
                }
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => $status];
                break;
            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function tipping_bucket($data_jadi, $data_mentah, $koefisien)
{
    try {
        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        $resolusi_sensor = isset($koefisien['resolusi_sensor']) ? (float)$koefisien['resolusi_sensor'] : 0;
        switch ($data_jadi->kode_sensor_jadi) {
            case "rainfall":
                $hitung = ((float)$data_mentah["knock"] + $kalibrasi) * $resolusi_sensor;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function vnotch($data_jadi, $data_mentah, $koefisien)
{
    try {
        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        switch ($data_jadi->kode_sensor_jadi) {
            case "debit_rembesan":
                $hitung = (float)$koefisien["konstanta_v"] * pow(((float)$data_mentah["ketinggian_air"] + $kalibrasi), 2.5) * 1000;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function open_stand_pipe($data_jadi, $data_mentah, $koefisien)
{
    try {
        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        switch ($data_jadi->kode_sensor_jadi) {
            case "tinggi_muka_air":
                $hitung = (float)$koefisien['elevasi_top_pipa'] - ((float)$data_mentah["ketinggian_air"] + $kalibrasi);
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function thermometer($data_jadi, $data_mentah, $koefisien)
{
    try {
        switch ($data_jadi->kode_sensor_jadi) {
            case "soil_temperature":
                $hitung = (((float)$koefisien['faktor_a'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ((float)$koefisien['faktor_b'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + (float)$koefisien['faktor_c']));
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function strainmeterrosette($data_jadi, $data_mentah, $koefisien)
{
    try {
        switch ($data_jadi->kode_sensor_jadi) {
            case "strain":
                $hitung = (((float)$koefisien['faktor_a'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ((float)$koefisien['faktor_b'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + (float)$koefisien['faktor_c']));
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function tiltmeter($data_jadi, $data_mentah, $koefisien)
{
    try {
        switch ($data_jadi->kode_sensor_jadi) {
            case "sudut_kemiringan":
                $sudut_inisial = ((float)$koefisien['v0'] - (float)$koefisien['offset']) / (float)$koefisien['sensitivity'];
                $current_angle = ((float)$data_mentah['tegangan_mems'] - (float)$koefisien['offset']) / (float)$koefisien['sensitivity'];
                $hitung =  $current_angle - $sudut_inisial;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function tiltmeter1530($data_jadi, $data_mentah, $koefisien)
{
    try {
        switch ($data_jadi->kode_sensor_jadi) {
            case "sudut_kemiringan":
                $sudut_inisial = ((float)$koefisien['faktor_a'] * pow((float)$koefisien['v0'], 3)) + ((float)$koefisien['faktor_b'] * pow((float)$koefisien['v0'], 2)) + ((float)$koefisien['faktor_c'] * (float)$koefisien['v0']) + (float)$koefisien['faktor_d'];
                $current_angle = ((float)$koefisien['faktor_a'] * pow((float)$data_mentah['tegangan_mems'], 3)) + ((float)$koefisien['faktor_b'] * pow((float)$data_mentah['tegangan_mems'], 2)) + ((float)$koefisien['faktor_c'] * (float)$data_mentah['tegangan_mems']) + (float)$koefisien['faktor_d'];
                $hitung =  $current_angle - $sudut_inisial;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function accelerometer($data_jadi, $data_mentah, $koefisien)
{
    try {
        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        switch ($data_jadi->kode_sensor_jadi) {
            case "accelerometer":
                $hitung = (float)$data_mentah["accelerometer"] +  $kalibrasi;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function seismometer($data_jadi, $data_mentah, $koefisien)
{
    try {
        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        switch ($data_jadi->kode_sensor_jadi) {
            case "seismometer":
                $hitung = (float)$data_mentah["seismometer"] +  $kalibrasi;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}





//==============================================
