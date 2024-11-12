<?php
if (!function_exists("pre")) {
    function pre($param = array())
    {
        echo "<PRE>";
        print_r($param);
        exit;
    }
}


function formula($type_instrument_name, $data_jadi, $data, $koefisien, $data_type)
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
            "Tiltmeter 1530" => "tiltmeter",
            "Seismograph" => "seismograph",
            "Accelerometer" => "accelerometer",
            "Joint Pint Meter" => "jointmeter",
            "In Place Inclinometer" => "ipi"

        ];
        if (!array_key_exists($type_instrument_name, $instrument_functions)) {
            throw new Exception();
        }

        $function_name = $instrument_functions[$type_instrument_name];

        $data_tambahan = array();
        foreach ($data_jadi as $row) {
            $data_mentah = array_merge($data['data_mentah'], array('instrument_id' => $data['instrument_id']));

            $action = $function_name($row, $data_mentah, $koefisien, $data_type, $data_tambahan);
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

function piezometer($data_jadi, $data_mentah, $koefisien, $data_type, &$data_tambahan)
{
    try {

        $koefisien['faktor_a'] = isset($koefisien['faktor_a']) ? (float)$koefisien['faktor_a'] : 0;
        $koefisien['faktor_b'] = isset($koefisien['faktor_b']) ? (float)$koefisien['faktor_b'] : 0;
        $koefisien['faktor_c'] = isset($koefisien['faktor_c']) ? (float)$koefisien['faktor_c'] : 0;
        $koefisien['tct'] = isset($koefisien['tct']) ? (float)$koefisien['tct'] : 0;
        $koefisien['kalibrasi_frekuensi'] = isset($koefisien['kalibrasi_frekuensi']) ? (float)$koefisien['kalibrasi_frekuensi'] : 0;
        $koefisien['kalibrasi_suhu'] = isset($koefisien['kalibrasi_suhu']) ? (float)$koefisien['kalibrasi_suhu'] : 0;
        $koefisien['t0'] = isset($koefisien['t0']) ? (float)$koefisien['t0'] : 0;
        $koefisien['elevasi_sensor'] = isset($koefisien['elevasi_sensor']) ? (float)$koefisien['elevasi_sensor'] : 0;

        if ($data_type === 'MANUAL') {
            $koefisien['kalibrasi_frekuensi'] = 0;
            $koefisien['kalibrasi_suhu'] = 0;
        }


        switch ($data_jadi->kode_sensor_jadi) {
            case "tekanan_air_kgcm2":
                $hitung = (((float)$koefisien['faktor_a'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ((float)$koefisien['faktor_b'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + (float)$koefisien['faktor_c']) - ((float)$koefisien['tct'] * (((float)$data_mentah['suhu'] + (float)$koefisien['kalibrasi_suhu']) - (float)$koefisien['t0'])));
                $data_tambahan['tekanan_air_kgcm2'] = 0;
                $data_tambahan['tekanan_air_kgcm2'] += $hitung;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;

            case "tekanan_air_kpa":
                $hitung = (((float)$koefisien['faktor_a'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ((float)$koefisien['faktor_b'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + (float)$koefisien['faktor_c']) - ((float)$koefisien['tct'] * (((float)$data_mentah['suhu'] + (float)$koefisien['kalibrasi_suhu']) - (float)$koefisien['t0'])));
                $data_tambahan['tekanan_air_kpa'] = 0;
                $data_tambahan['tekanan_air_kpa'] += $hitung;

                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;

            case "tekanan_air_mh2o":
                if (isset($data_tambahan['tekanan_air_kpa'])) {
                    $hitung = $data_tambahan['tekanan_air_kpa'] * 0.1022;
                } else if (isset($data_tambahan['tekanan_air_kgcm2'])) {
                    $hitung = $data_tambahan['tekanan_air_kgcm2'] * 10.0003;
                } else {
                    $hitung = 0;
                }

                $data_tambahan['tekanan_air_mh2o'] = 0;
                $data_tambahan['tekanan_air_mh2o'] += $hitung;

                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "level_freatik_air":
                $hitung = (float)$koefisien['elevasi_sensor'] +  $data_tambahan['tekanan_air_mh2o'];
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function pressurecell($data_jadi, $data_mentah, $koefisien, $data_type, &$data_tambahan)
{

    try {

        $koefisien['faktor_a'] = isset($koefisien['faktor_a']) ? (float)$koefisien['faktor_a'] : 0;
        $koefisien['faktor_b'] = isset($koefisien['faktor_b']) ? (float)$koefisien['faktor_b'] : 0;
        $koefisien['faktor_c'] = isset($koefisien['faktor_c']) ? (float)$koefisien['faktor_c'] : 0;
        $koefisien['tct'] = isset($koefisien['tct']) ? (float)$koefisien['tct'] : 0;
        $koefisien['kalibrasi_frekuensi'] = isset($koefisien['kalibrasi_frekuensi']) ? (float)$koefisien['kalibrasi_frekuensi'] : 0;
        $koefisien['kalibrasi_suhu'] = isset($koefisien['kalibrasi_suhu']) ? (float)$koefisien['kalibrasi_suhu'] : 0;
        $koefisien['t0'] = isset($koefisien['t0']) ? (float)$koefisien['t0'] : 0;
        $koefisien['elevasi_sensor'] = isset($koefisien['elevasi_sensor']) ? (float)$koefisien['elevasi_sensor'] : 0;
        if ($data_type === 'MANUAL') {
            $koefisien['kalibrasi_frekuensi'] = 0;
            $koefisien['kalibrasi_suhu'] = 0;
        }

        switch ($data_jadi->kode_sensor_jadi) {
            case "tekanan_tanah_kgcm2":
                $hitung = (((float)$koefisien['faktor_a'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ((float)$koefisien['faktor_b'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + (float)$koefisien['faktor_c']) - ((float)$koefisien['tct'] * (((float)$data_mentah['suhu'] + (float)$koefisien['kalibrasi_suhu']) - (float)$koefisien['t0'])));
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "tekanan_tanah_mpa":
                $init = (((float)$koefisien['faktor_a'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ((float)$koefisien['faktor_b'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + (float)$koefisien['faktor_c']) - ((float)$koefisien['tct'] * (((float)$data_mentah['suhu'] + (float)$koefisien['kalibrasi_suhu']) - (float)$koefisien['t0'])));
                $hitung = $init * 0.098;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "tekanan_air_mh2o":
                $init = (((float)$koefisien['faktor_a'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ((float)$koefisien['faktor_b'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + (float)$koefisien['faktor_c']) - ((float)$koefisien['tct'] * (((float)$data_mentah['suhu'] + (float)$koefisien['kalibrasi_suhu']) - (float)$koefisien['t0'])));
                $hitung = $init * 10.0003;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "level_freatik_air":
                $hitung = (float)$koefisien['elevasi_sensor'] + (((float)$koefisien['faktor_a'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ((float)$koefisien['faktor_b'] * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + (float)$koefisien['faktor_c']) - ((float)$koefisien['tct'] * (((float)$data_mentah['suhu'] + (float)$koefisien['kalibrasi_suhu']) - (float)$koefisien['t0']))) * 10.0003;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function pressureawlr($data_jadi, $data_mentah, $koefisien, $data_type)
{
    try {

        $koefisien['kalibrasi'] = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        $koefisien['elevasi_sensor'] = isset($koefisien['elevasi_sensor']) ? (float)$koefisien['elevasi_sensor'] : 0;

        if ($data_type === 'MANUAL') {
            $koefisien['kalibrasi'] = 0;
        }
        switch ($data_jadi->kode_sensor_jadi) {
            case "tinggi_muka_air":
                $hitung = (float)$data_mentah["ketinggian_air"] + $koefisien['kalibrasi'] + $koefisien['elevasi_sensor'];
                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;
            default:
                throw new Exception("Unknown sensor code.");
        }
    } catch (Exception $e) {
        return false;
    }
}

function ultrasonicawlr($data_jadi, $data_mentah, $koefisien, $data_type)
{
    try {

        $koefisien['kalibrasi'] = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        $koefisien['elevasi_sensor'] = isset($koefisien['elevasi_sensor']) ? (float)$koefisien['elevasi_sensor'] : 0;
        if ($data_type === 'MANUAL') {
            $koefisien['kalibrasi'] = 0;
        }

        switch ($data_jadi->kode_sensor_jadi) {
            case "tinggi_muka_air":
                $hitung = $koefisien['elevasi_sensor'] - ((float)$data_mentah["ketinggian_air"] + $koefisien['kalibrasi']);
                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;
            default:
                throw new Exception("Unknown sensor code.");
        }
    } catch (Exception $e) {
        return false;
    }
}


function ultrasonicevaporation($data_jadi, $data_mentah, $koefisien, $data_type, &$data_tambahan)
{
    try {

        $koefisien['kalibrasi'] = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        $koefisien['elevasi_sensor'] = isset($koefisien['elevasi_sensor']) ? (float)$koefisien['elevasi_sensor'] : 0;
        if ($data_type === 'MANUAL') {
            $koefisien['kalibrasi'] = 0;
        }

        switch ($data_jadi->kode_sensor_jadi) {
            case "ketinggian_air_mm":
                $hitung = $koefisien['elevasi_sensor'] - ((float)$data_mentah['ketinggian_air_total'] + $koefisien['kalibrasi']);

                if (!isset($data_tambahan['ketinggian_air_mm'])) {
                    $data_tambahan['ketinggian_air_mm'] = 0;
                }

                $data_tambahan['ketinggian_air_mm'] += $hitung;

                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;
            case "evaporation":
                $data_sebelumnya = cek_data_sebelumnya($data_mentah['instrument_id'], 'ketinggian_air_mm');

                $ketinggian_air_mm = isset($data_tambahan['ketinggian_air_mm']) ? $data_tambahan['ketinggian_air_mm'] : 0;

                $hitung = (float)$data_sebelumnya - $ketinggian_air_mm;

                $data_tambahan['evaporation'] += $hitung;
                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;
            case "total_evaporation":
                $data_sebelumnya = cek_total_data($data_mentah['instrument_id'], 'evaporation');
                if ($data_sebelumnya == 0) {
                    $hitung = $data_tambahan['evaporation'];
                } else {
                    $hitung = (float)$data_tambahan['evaporation'] + (float)$data_sebelumnya;
                }

                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;
            default:
                throw new Exception("Unknown sensor code.");
        }
    } catch (Exception $e) {
        return false;
    }
}

function hall_effect($data_jadi, $data_mentah, $koefisien, $data_type)
{
    try {

        $koefisien['kalibrasi'] = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;

        if ($data_type === 'MANUAL') {
            $koefisien['kalibrasi'] = 0;
        }

        switch ($data_jadi->kode_sensor_jadi) {
            case "wind_direction":
                $arah_mata_angin = (float)$data_mentah["derajat_arah_angin"] + $koefisien['kalibrasi'];

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

                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => $arah_angin
                ];
                break;
            default:
                throw new Exception("Unknown sensor code.");
        }
    } catch (Exception $e) {
        return false;
    }
}

function standard($data_jadi, $data_mentah, $koefisien, $data_type, &$data_tambahan)
{
    try {


        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;

        if ($data_type === 'MANUAL') {
            $kalibrasi = 0;
        }

        switch ($data_jadi->kode_sensor_jadi) {
            case "pm25":
                $hitung = (float)$data_mentah["pm25"] + $kalibrasi;
                break;
            case "pm10":
                $hitung = (float)$data_mentah["pm10"] + $kalibrasi;
                break;
            case "karbon_monoksida":
                $hitung = (float)$data_mentah["karbon_monoksida"] + $kalibrasi;
                break;
            case "nitrogen_dioksida":
                $hitung = (float)$data_mentah["nitrogen_dioksida"] + $kalibrasi;
                break;
            case "sulfur_dioksida":
                $hitung = (float)$data_mentah["sulfur_dioksida"] + $kalibrasi;
                break;
            case "ozon":
                $hitung = (float)$data_mentah["ozon"] + $kalibrasi;
                break;
            case "tss":
                $hitung = (float)$data_mentah["tss"] + $kalibrasi;
                break;
            case "turbidity":
                $hitung = (float)$data_mentah["turbidity"] + $kalibrasi;
                break;
            case "cod":
                $hitung = (float)$data_mentah["cod"] + $kalibrasi;
                break;
            case "water_ph":
                $hitung = (float)$data_mentah["water_ph"] + $kalibrasi;
                break;
            case "debit_air":
                $hitung = (float)$data_mentah["debit_air"] + $kalibrasi;
                break;
            case "wind_speed":
                $hitung = (float)$data_mentah["wind_speed"] + $kalibrasi;
                break;
            case "wind_direction":
                $hitung = (float)$data_mentah["wind_direction"] + $kalibrasi;
                break;
            case "air_temperature":
                $hitung = (float)$data_mentah["air_temperature"] + $kalibrasi;
                break;
            case "air_humidity":
                $hitung = (float)$data_mentah["air_humidity"] + $kalibrasi;
                break;
            case "air_pressure":
                $hitung = (float)$data_mentah["air_pressure"] + $kalibrasi;
                break;
            case "solar_radiation":
                $hitung = (float)$data_mentah["solar_radiation"] + $kalibrasi;
                break;
            case "ketinggian_air":
                $hitung = (float)$data_mentah["ketinggian_air"] + $kalibrasi;
                break;
            case "tinggi_muka_air":
                $hitung = (float)$data_mentah["tinggi_muka_air"] + $kalibrasi;
                break;
            case "rainfall":
                $hitung = (float)$data_mentah["rainfall"] + $kalibrasi;
                $data_tambahan['rainfall'] += $hitung;
                break;
            case "total_rainfall":
                $data_sebelumnya = cek_total_data($data_mentah['instrument_id'], 'rainfall');
                if ($data_sebelumnya == 0) {
                    $hitung = 0;
                } else {
                    $hitung = $data_sebelumnya + $data_tambahan['rainfall'];
                }
                break;
            case "accelerometer":
                $hitung = (float)$data_mentah["accelerometer"] + $kalibrasi;
                break;
            case "seismometer":
                $hitung = (float)$data_mentah["seismometer"] + $kalibrasi;
                break;
            case "status_siaga":
                $status_siaga = (float)$data_mentah["status_siaga"] + $kalibrasi;
                if ($status_siaga >= 4) {
                    $status = "Awas";
                } elseif ($status_siaga < 4 && $status_siaga >= 3) {
                    $status = "Siaga";
                } elseif ($status_siaga < 3 && $status_siaga >= 2) {
                    $status = "Waspada";
                } elseif ($status_siaga < 2 && $status_siaga >= 1) {
                    $status = "Normal";
                } else {
                    $status = "-";
                }
                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => $status
                ];
            default:
                throw new Exception();
        }
        return [
            'id_sensor' => $data_jadi->jenis_sensor_jadi,
            'nama_sensor' => $data_jadi->nama_sensor . ' (' . $data_jadi->unit_sensor . ')',
            'hasil' => number_format($hitung, 3)
        ];
    } catch (Exception $e) {
        return false;
    }
}



function tipping_bucket($data_jadi, $data_mentah, $koefisien, $data_type, &$data_tambahan)
{
    try {


        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        $resolusi_sensor = isset($koefisien['resolusi_sensor']) ? (float)$koefisien['resolusi_sensor'] : 0;

        if ($data_type === 'MANUAL') {
            $kalibrasi = 0;
        }

        switch ($data_jadi->kode_sensor_jadi) {
            case "rainfall":

                $data_sebelumnya =  (float)cek_data_sebelumnya_mentah($data_mentah['instrument_id'], 'knock');

                $hitung = (((float)$data_mentah["knock"] + $kalibrasi) - $data_sebelumnya) * $resolusi_sensor;

                $data_tambahan['rainfall'] = null;
                $data_tambahan['rainfall'] += $hitung;

                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "total_rainfall":
                $data_sebelumnya = cek_total_data($data_mentah['instrument_id'], 'rainfall');
                if ($data_sebelumnya == 0) {
                    $hitung = 0;
                } else {
                    $hitung = $data_sebelumnya + $data_tambahan['rainfall'];
                }
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}



function vnotch($data_jadi, $data_mentah, $koefisien, $data_type, &$data_tambahan)
{
    try {


        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        $konstanta_v = isset($koefisien['konstanta_v']) ? (float)$koefisien['konstanta_v'] : 0;

        if ($data_type === 'MANUAL') {
            $kalibrasi = 0;
        }

        switch ($data_jadi->kode_sensor_jadi) {
            case "ketinggian_air_mm":
                $hitung = (float)$data_mentah['ketinggian_air_total'] + $kalibrasi;

                $data_tambahan['ketinggian_air_mm'] = null;
                $data_tambahan['ketinggian_air_mm'] += $hitung;

                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;
            case "debit_rembesan":
                $hitung = $konstanta_v * pow(((float)$data_tambahan["ketinggian_air_mm"] / 1000), 2.5) * 1000;
                return ['id_sensor' => $data_jadi->jenis_sensor_jadi, 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')', 'hasil' => number_format($hitung, 3)];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}


function open_stand_pipe($data_jadi, $data_mentah, $koefisien, $data_type)
{
    try {


        $kalibrasi = isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0;
        $elevasi_top_pipa = isset($koefisien['elevasi_top_pipa']) ? (float)$koefisien['elevasi_top_pipa'] : 0;

        if ($data_type === 'MANUAL') {
            $kalibrasi = 0;
        }

        switch ($data_jadi->kode_sensor_jadi) {
            case "tinggi_muka_air":
                $hitung = $elevasi_top_pipa - ((float)$data_mentah["ketinggian_air"] + $kalibrasi);
                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}


function thermometer($data_jadi, $data_mentah, $koefisien, $data_type)
{
    try {
        $faktor_a = isset($koefisien['faktor_a']) ? (float)$koefisien['faktor_a'] : 0;
        $faktor_b = isset($koefisien['faktor_b']) ? (float)$koefisien['faktor_b'] : 0;
        $faktor_c = isset($koefisien['faktor_c']) ? (float)$koefisien['faktor_c'] : 0;

        switch ($data_jadi->kode_sensor_jadi) {
            case "soil_temperature":
                $hitung = (($faktor_a * ((float)$data_mentah['frekuensi'] + $faktor_a) ** 2) + ($faktor_b * ((float)$data_mentah['frekuensi'] + $faktor_b) + $faktor_c));
                // Return sensor data
                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}


function strainmeterrosette($data_jadi, $data_mentah, $koefisien, $data_type, &$data_tambahan)
{
    try {
        $faktor_a = isset($koefisien['faktor_a']) ? (float)$koefisien['faktor_a'] : 0;
        $faktor_b = isset($koefisien['faktor_b']) ? (float)$koefisien['faktor_b'] : 0;
        $faktor_c = isset($koefisien['faktor_c']) ? (float)$koefisien['faktor_c'] : 0;
        $initial_delta_strain = isset($koefisien['initial_delta_strain']) ? (float)$koefisien['initial_delta_strain'] : 0;


        switch ($data_jadi->kode_sensor_jadi) {
            case "strain":
                $hitung = (($faktor_a * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ($faktor_b * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + $faktor_c));


                $data_tambahan['strain'] = number_format($hitung, 3);

                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;
            case "delta_strain":

                $data_sebelumnya = $initial_delta_strain;
                // $data_sebelumnya = str_replace(',', '', cek_data_awal($data_mentah['instrument_id'], 'strain'));
                $strain = str_replace(',', '', $data_tambahan['strain']);


                $data_sebelumnya = floatval($data_sebelumnya);
                $strain = floatval($strain);

                $hitung = $strain - $data_sebelumnya;

                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;
            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}


function tiltmeter($data_jadi, $data_mentah, $koefisien, $data_type, &$data_tambahan)
{
    try {
        $v0 = isset($koefisien['v0']) ? (float)$koefisien['v0'] : 0;
        $offset = isset($koefisien['offset']) ? (float)$koefisien['offset'] : 0;
        $sensitivity = isset($koefisien['sensitivity']) ? (float)$koefisien['sensitivity'] : 0;
        $initial_angle = isset($koefisien['initial_angle']) ? (float)$koefisien['initial_angle'] : 0;

        switch ($data_jadi->kode_sensor_jadi) {
            case "angle":
                $hitung = (($v0 - $offset) / $sensitivity);

                $data_tambahan['angle'] = number_format($hitung, 3);
                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;
            case "changed_angle":
                // $changed_angle = ((float)$data_mentah['tegangan_mems'] - $offset) / $sensitivity;
                $hitung =  $data_tambahan['angle'] - $initial_angle;

                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;
            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function tiltmeter1530($data_jadi, $data_mentah, $koefisien, $data_type)
{
    try {
        $faktor_a = $data_type === 'MANUAL' ? 0 : (isset($koefisien['faktor_a']) ? (float)$koefisien['faktor_a'] : 0);
        $faktor_b = $data_type === 'MANUAL' ? 0 : (isset($koefisien['faktor_b']) ? (float)$koefisien['faktor_b'] : 0);
        $faktor_c = $data_type === 'MANUAL' ? 0 : (isset($koefisien['faktor_c']) ? (float)$koefisien['faktor_c'] : 0);
        $faktor_d = $data_type === 'MANUAL' ? 0 : (isset($koefisien['faktor_d']) ? (float)$koefisien['faktor_d'] : 0);

        switch ($data_jadi->kode_sensor_jadi) {
            case "sudut_kemiringan":
                $sudut_inisial = (($faktor_a * pow($koefisien['v0'], 3)) + ($faktor_b * pow($koefisien['v0'], 2)) + ($faktor_c * $koefisien['v0']) + $faktor_d);
                $current_angle = (($faktor_a * pow($data_mentah['tegangan_mems'], 3)) + ($faktor_b * pow($data_mentah['tegangan_mems'], 2)) + ($faktor_c * $data_mentah['tegangan_mems']) + $faktor_d);
                $hitung =  $current_angle - $sudut_inisial;
                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function accelerometer($data_jadi, $data_mentah, $koefisien, $data_type)
{
    try {
        $kalibrasi = $data_type === 'MANUAL' ? 0 : (isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0);

        switch ($data_jadi->kode_sensor_jadi) {
            case "accelerometer":
                $hitung = (float)$data_mentah["accelerometer"] +  $kalibrasi;
                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function seismometer($data_jadi, $data_mentah, $koefisien, $data_type)
{
    try {
        $kalibrasi = $data_type === 'MANUAL' ? 0 : (isset($koefisien['kalibrasi']) ? (float)$koefisien['kalibrasi'] : 0);

        switch ($data_jadi->kode_sensor_jadi) {
            case "seismometer":
                $hitung = (float)$data_mentah["seismometer"] +  $kalibrasi;
                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;

            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function jointmeter($data_jadi, $data_mentah, $koefisien, $data_type, &$data_tambahan)
{
    try {
        $faktor_a = isset($koefisien['faktor_a']) ? (float)$koefisien['faktor_a'] : 0;
        $faktor_b = isset($koefisien['faktor_b']) ? (float)$koefisien['faktor_b'] : 0;
        $faktor_c = isset($koefisien['faktor_c']) ? (float)$koefisien['faktor_c'] : 0;
        $koefisien['tct'] = isset($koefisien['tct']) ? (float)$koefisien['tct'] : 0;
        $koefisien['kalibrasi_frekuensi'] = isset($koefisien['kalibrasi_frekuensi']) ? (float)$koefisien['kalibrasi_frekuensi'] : 0;
        $koefisien['t0'] = isset($koefisien['t0']) ? (float)$koefisien['t0'] : 0;
        $initial_displacement = isset($koefisien['initial_displacement']) ? (float)$koefisien['initial_displacement'] : 0;


        switch ($data_jadi->kode_sensor_jadi) {
            case "displacement":
                $hitung = (($faktor_a * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) +
                    ($faktor_b * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + $faktor_c) -
                    ((float)$koefisien['tct'] * (((float)$data_mentah['suhu'] + (float)$koefisien['kalibrasi_suhu']) - (float)$koefisien['t0'])));


                $data_tambahan['displacement'] = number_format($hitung, 3);

                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;
            case "changed_displacement":

                // $data_sebelumnya = str_replace(',', '', cek_data_awal($data_mentah['instrument_id'], 'displacement'));

                // $displacement = str_replace(',', '', $data_tambahan['displacement']);


                // $data_sebelumnya = floatval($initial_displacement);
                // $displacement = floatval($displacement);

                // $hitung = $displacement - $data_sebelumnya;
                $hitung = $data_tambahan['displacement'] - $initial_displacement;

                return [
                    'id_sensor' => $data_jadi->jenis_sensor_jadi,
                    'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')',
                    'hasil' => number_format($hitung, 3)
                ];
                break;
            default:
                throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}


function ipi($data_jadi, $data_mentah, $koefisien, $data_type, &$data_tambahan)
{
    // try {
    //     $a_axis = isset($data_mentah['a_axis']) ? (float)$data_mentah['a_axis'] : 0;
    //     $suhu = isset($data_mentah['suhu']) ? (float)$data_mentah['suhu'] : 0;
    //     $b_axis = isset($data_mentah['b_axis']) ? (float)$data_mentah['b_axis'] : 0;


    //     $initial_angle_aaxis = isset($koefisien['initial_angle_aaxis']) ? (float)$koefisien['initial_angle_aaxis'] : 0;
    //     $initial_angle_baxis = isset($koefisien['initial_angle_baxis']) ? (float)$koefisien['initial_angle_baxis'] : 0;
    //     $gage_length = isset($koefisien['gage_length']) ? (float)$koefisien['gage_length'] : 0;


    //     switch ($data_jadi->kode_sensor_jadi) {
    //         case "strain":
    //             $hitung = (($faktor_a * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) ** 2) + ($faktor_b * ((float)$data_mentah['frekuensi'] + (float)$koefisien['kalibrasi_frekuensi']) + $faktor_c));


    //             $data_tambahan['strain'] = number_format($hitung, 3);

    //             return [
    //                 'id_sensor' => $data_jadi->jenis_sensor_jadi,
    //                 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')',
    //                 'hasil' => number_format($hitung, 3)
    //             ];
    //             break;
    //         case "delta_strain":

    //             $data_sebelumnya = $initial_delta_strain;
    //             // $data_sebelumnya = str_replace(',', '', cek_data_awal($data_mentah['instrument_id'], 'strain'));
    //             $strain = str_replace(',', '', $data_tambahan['strain']);


    //             $data_sebelumnya = floatval($data_sebelumnya);
    //             $strain = floatval($strain);

    //             $hitung = $strain - $data_sebelumnya;

    //             return [
    //                 'id_sensor' => $data_jadi->jenis_sensor_jadi,
    //                 'nama_sensor' => $data_jadi->nama_sensor  . ' (' . $data_jadi->unit_sensor . ')',
    //                 'hasil' => number_format($hitung, 3)
    //             ];
    //             break;
    //         default:
    //             throw new Exception();
    //     }
    // } catch (Exception $e) {
    //     return false;
    // }
}


//==============================================


function cek_total_data($instrument_id, $data_jadi_string)
{
    try {
        $ci = &get_instance();
        $instrument_data = $ci->db->get_where("tr_instrument", ['id' => $instrument_id])->row();
        if (!$instrument_data) {
            throw new Exception();
        }

        $jenis_sensor_jadi = $ci->db->get_where("sys_jenis_sensor", ['var_name' => $data_jadi_string])->row();
        if (!$jenis_sensor_jadi) {
            throw new Exception();
        }

        $db_site = change_connection($instrument_data->ms_regions_id);
        // Step 1: Get the latest date
        $latest_date_query = $db_site->query("SELECT MAX(t1.tanggal) as latest_date, MAX(t1.jam) latest_jam
                                      FROM data t1
                                      LEFT JOIN data_value t2 ON t1.id = t2.data_id
                                      WHERE t1.kode_instrument = '{$instrument_data->kode_instrument}'
                                      AND t2.sensor_id = {$jenis_sensor_jadi->id}
                                      AND t2.data_primer = 0 AND t2.data_jadi != ''
                                     ");
        $latest_date_result = $latest_date_query->row();
        $latest_date = $latest_date_result->latest_date;
        $latest_jam = $latest_date_query->latest_jam;

        // Step 2: Calculate the next day
        // $next_day = date('Y-m-d', strtotime($latest_date . ' +1 day'));

        // Step 3: Sum all data from 01:00 AM of the latest date to 12:00 AM of the next day
        // $sum_query = $db_site->query("SELECT SUM(t2.data_jadi) as total_sum
        //                       FROM data t1
        //                       LEFT JOIN data_value t2 ON t1.id = t2.data_id
        //                       WHERE t1.kode_instrument = '{$instrument_data->kode_instrument}'
        //                       AND t2.sensor_id = {$jenis_sensor_jadi->id}
        //                       AND t2.data_primer = 0 AND t2.data_jadi != ''
        //                       AND (
        //                           (t1.tanggal = '{$latest_date}' AND t1.jam >= '00:00:00')
        //                           OR (t1.tanggal = '{$next_day}' AND t1.jam < '00:00:00')
        //                       )
        //                      ");

        $sum_query = $db_site->query("SELECT SUM(t2.data_jadi) as total_sum
                              FROM data t1
                              LEFT JOIN data_value t2 ON t1.id = t2.data_id
                              WHERE t1.kode_instrument = '{$instrument_data->kode_instrument}'
                              AND t2.sensor_id = {$jenis_sensor_jadi->id}
                              AND t2.data_primer = 0 AND t2.data_jadi != ''
                              AND t1.tanggal = '{$latest_date}'
                              AND t1.jam != '23:00:00'
                              
                             ");
        $sum_result = $sum_query->row();
        $total_sum = $sum_result->total_sum;


        return $total_sum;
    } catch (Exception $e) {
        return false;
    }
}



function cek_data_awal($instrument_id, $data_jadi_string)
{
    try {
        $ci = &get_instance();
        $instrument_data = $ci->db->get_where("tr_instrument", ['id' => $instrument_id])->row();
        if (!$instrument_data) {
            throw new Exception();
        }

        $jenis_sensor_jadi = $ci->db->get_where("sys_jenis_sensor", ['var_name' => $data_jadi_string])->row();
        if (!$jenis_sensor_jadi) {
            throw new Exception();
        }

        $db_site = change_connection($instrument_data->ms_regions_id);

        $query = $db_site->query("SELECT t2.data_jadi, t1.jam
                                FROM data t1
                                LEFT JOIN data_value t2 ON t1.id = t2.data_id
                                WHERE t1.kode_instrument = '{$instrument_data->kode_instrument}'
                                AND t2.sensor_id = {$jenis_sensor_jadi->id}
                                AND t2.data_primer = 0 AND t2.data_jadi != ''
                                ORDER BY t1.tanggal ASC, t1.jam ASC
                                LIMIT 1
                                    ");
        $result = $query->row();

        if ($result) {
            return $result->data_jadi;
        } else {
            return 0;
        }
    } catch (Exception $e) {
        return false;
    }
}



function cek_data_sebelumnya($instrument_id, $data_jadi_string)
{
    try {
        $ci = &get_instance();
        $instrument_data = $ci->db->get_where("tr_instrument", ['id' => $instrument_id])->row();
        if (!$instrument_data) {
            throw new Exception();
        }

        $jenis_sensor_jadi = $ci->db->get_where("sys_jenis_sensor", ['var_name' => $data_jadi_string])->row();
        if (!$jenis_sensor_jadi) {
            throw new Exception();
        }

        $db_site = change_connection($instrument_data->ms_regions_id);

        $query = $db_site->query("SELECT t2.data_jadi, t1.jam
                                FROM data t1
                                LEFT JOIN data_value t2 ON t1.id = t2.data_id
                                WHERE t1.kode_instrument = '{$instrument_data->kode_instrument}'
                                AND t2.sensor_id = {$jenis_sensor_jadi->id}
                                AND t2.data_primer = 0 AND t2.data_jadi != ''
                                ORDER BY t1.tanggal DESC, t1.jam DESC, t2.id DESC
                                LIMIT 1
                                    ");
        $result = $query->row();

        if ($result) {
            // $dateTime = new DateTime($result->jam);
            // $dateTime->modify('+1 hour');
            // $newJam = $dateTime->format('H:i');

            // // Cek apakah sensor adalah total_rainfall dan jam adalah 08:00
            // // if ($jenis_sensor_jadi->var_name == 'total_rainfall' && $newJam == '08:00') {
            // //     return 0;
            // // }

            // // Cek apakah tanggal dan jam sudah lewat 1 hari pada pukul 7 pagi
            // if ($newJam == '01:00') {
            //     $currentDate = new DateTime();
            //     $currentDate->modify('-1 day');
            //     if ($dateTime->format('Y-m-d') < $currentDate->format('Y-m-d')) {
            //         return 0;
            //     }
            // }
            // // Jika bukan kasus khusus, gunakan data sebelumnya

            $dateTime = new DateTime($result->jam);
            $dateTime->modify('+1 hour');
            $newJam = $dateTime->format('H:i');

            if ($newJam == '00:00') {
                return 0;
            }
            return $result->data_jadi;
        } else {
            return 0;
        }
    } catch (Exception $e) {
        return false;
    }
}


function cek_data_sebelumnya_mentah($instrument_id, $data_jadi_string)
{
    try {
        $ci = &get_instance();
        $instrument_data = $ci->db->get_where("tr_instrument", ['id' => $instrument_id])->row();
        if (!$instrument_data) {
            throw new Exception();
        }

        $jenis_sensor_jadi = $ci->db->get_where("sys_jenis_sensor", ['var_name' => $data_jadi_string])->row();
        if (!$jenis_sensor_jadi) {
            throw new Exception();
        }
        $db_site = change_connection($instrument_data->ms_regions_id);

        $query = $db_site->query("SELECT t2.data_primer 
                                FROM data t1
                                INNER JOIN data_value t2 ON t1.id = t2.data_id
                                WHERE t1.kode_instrument = '{$instrument_data->kode_instrument}'
                                AND t2.sensor_id = {$jenis_sensor_jadi->id}
                                ORDER BY t1.tanggal DESC, t1.jam DESC, t2.id DESC
                                LIMIT 1
                                    ");
        $result = $query->row();
        if ($result) {
            $data_before = $result->data_primer;
        } else {
            $data_before = 0;
        }

        // Periksa apakah data lewat 1 hari dan jam saat itu adalah 07:00
        $currentDate = new DateTime();
        // $currentDate->modify('-1 day');
        $currentHour = (int)$currentDate->format('H');
        if ($currentHour === 0) {
            return 0;
        }

        return $data_before;
    } catch (Exception $e) {
        return false;
    }
}


function change_connection($id_regions)
{
    $ci = &get_instance();
    $query = $ci->db->query("SELECT * FROM ms_regions WHERE id = '$id_regions'");
    $result = $query->row();

    $second_db_params = switchDatabase($result->database_host, $result->database_username, $result->database_password, $result->database_name, $result->database_port);
    $ci->db2 = $ci->load->database($second_db_params, TRUE);

    if ($ci->db2->initialize()) {
        return $ci->db2;
    } else {
        return false;
    }
}

function switchDatabase($hostname, $username, $password, $database, $port)
{
    $params = array(
        'hostname' => $hostname . ':' . $port,
        'username' => $username,
        'password' => $password,
        'database' => $database,
        // Other database configuration parameters
        'dbdriver' => 'mysqli',
        'dbprefix' => '',
        'pconnect' => FALSE,
        'db_debug' => (ENVIRONMENT !== 'production'),
        'cache_on' => FALSE,
        'cachedir' => '',
        'char_set' => 'utf8',
        'dbcollat' => 'utf8_general_ci',
        'swap_pre' => '',
        'encrypt'  => FALSE,
        'compress' => FALSE,
        'stricton' => FALSE,
        'failover' => array(),
        'save_queries' => TRUE
    );

    return $params;
}


function getLocalTimezone()
{
    return date_default_timezone_get(); // Or use any other method to retrieve the timezone
}
