<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/widget/w_chart.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:41:46 GMT -->

<head>
    <title>Grafik</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Berry is made using Bootstrap 5 design framework. Download the free admin template & use it for your project." />
    <meta name="keywords" content="Berry, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template" />
    <meta name="author" content="SPIB" />
    <link rel="icon" href="<?= base_url() . 'assets/logo_simetri.png' ?>" type="image/x-icon" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" id="main-font-link" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/feather.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/material.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css" id="main-style-link" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style-preset.css" id="preset-style-link" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <style>
        .select2 {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <?php $this->load->view('include/header.php'); ?>
    <?php $this->load->view('include/sidebar.php'); ?>
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Grafik</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url() ?>">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Grafik</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="form-filter">
                                <div class="row" style="width: 100%;">
                                    <div class="form-group col-md-2">
                                        <select class="select2" name="ms_regions_id" id="ms_regions_id">
                                            <option value="">Select Region</option>
                                            <?php foreach ($region as $reg) { ?>
                                                <option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <select class="select2" name="stasiun" id="stasiun">
                                            <option value="">Select Station</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="stasiun_type" name="stasiun_type">
                                    <div class="form-group col-md-2">
                                        <select class="select2" name="elevasi" id="elevasi" disabled>
                                            <option value="" selected>Select Elevation</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <select class="select2" name="status" id="status" disabled>
                                            <option value="" selected>Select Status</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <select class="select2" name="tipe_data" id="tipe_data">
                                            <option value="OTOMATIS" selected>OTOMATIS</option>
                                            <option value="MANUAL">MANUAL</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <button class="btn btn-sm btn-warning" type="button" onclick="zoomImage()"><i class="ti ti-maximize me-1"></i>Preview Layout</button>
                                    </div>
                                    <div class="form-group col-md-2 ">
                                        <select class="pilih_instrument pb-5" name="pilih_instrument" id="pilih_instrument" " style=" width: 100%">
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2 ">
                                        <select class="select_data pb-5" name="select_data" id="select_data" style="width: 100%">
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2 ">
                                        <select class="select_data_tambah" name="data_tambah[]" id="data_tambah" multiple="multiple" style="width: 100%" disabled>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <select class="select2" name="periode" id="periode">
                                            <option value="Jam">Harian</option>
                                            <option value="Harian">Bulanan</option>
                                            <!-- <option value="Bulanan">Bulanan</option> -->
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <input type="date" class="form-control p-1" id="waktu" name="waktu" value="<?php date_default_timezone_set('Asia/Jakarta');
                                                                                                                    echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="filter()"><i class="ti ti-search me-1"></i>Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <style>
                            .chart-layout {
                                min-height: 40vh;
                            }

                            @media (min-width: 1800px) {
                                .chart-layout {
                                    height: 45vh;
                                }
                            }

                            @media (min-width: 1900px) {
                                .chart-layout {
                                    height: 50vh;
                                }
                            }

                            @media (min-width: 2000px) {
                                .chart-layout {
                                    height: 60vh;
                                }
                            }
                        </style>
                        <div class="col-xl-12 overflow-x-auto">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status" id="loading" style="display:none">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="fs-1 text-center" id="nama_site_text"></div>
                            <div class="chart-layout" style="max-width: 100%; overflow-x: auto;overflow-y: hidden; ">
                                <div id="chart"></div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php $this->load->view('include/footer.php'); ?>
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" width="100%" src="" class="img-fluid" alt="Stasiun Image">
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url() ?>assets/js/plugins/popper.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/simplebar.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/config.js"></script>
    <script src="<?= base_url() ?>assets/js/pcoded.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/feather.min.js"></script>
    <script src="<?= base_url() ?>assets/js/customizer.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/apexcharts.min.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
    <script src="
	https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".select2").select2();
            $("#stasiun").select2();
            $(".pilih_instrument").select2({
                placeholder: "Select Instrument",
                allowClear: true
            });
            $(".select_data").select2({
                placeholder: "Select Data",
                allowClear: true
            });
            $(".select_data_tambah").select2({
                placeholder: "Select Additional Data",
                allowClear: true
            });

        });

        function zoomImage() {
            // var regions_id = $("#ms_regions_id").val();
            var stasiun = $("#stasiun").val();

            if (stasiun == "") {
                toastr.error("Site harus diisi");
                $(this).val('');
                return
            }

            $.ajax({
                url: "<?= base_url('Grafik/getImageStasiun/'); ?>" + stasiun,
                type: "GET",
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    $('#modalImage').attr('src', response.imageUrl);
                    $('#imageModal').modal('show');
                }
            })

        }

        $("#periode").change(function() {
            var option = $(this).val();
            switch (option) {
                case "Jam":
                    $("#waktu").attr("type", "date");
                    $("#waktu").val('<?= date("Y-m-d"); ?>');
                    break;
                case "Harian":
                    $("#waktu").attr("type", "month");
                    $("#waktu").val('<?= date("Y-m"); ?>');
                    break;
                default:
                    $("#waktu").attr("type", "date");
                    break;
            }
        });

        $("#ms_regions_id").change(function() {
            var ms_regions_id = $(this).val();
            reset_field()
            $.ajax({
                url: "<?= base_url('Grafik/getSensor/?ms_regions_id='); ?>" + ms_regions_id,
                type: "GET",
                dataType: "json",
                success: function(result) {
                    var stasiun = $('#stasiun');
                    var html = '';
                    if (result.length > 0) {
                        html += '<option value="">Pilih Stasiun</option>';
                        for (i = 0; i < result.length; i++) {
                            html += '<option data-id="' + result[i].stasiun_type + '" value=' + result[i].id + '>' + result[i].nama_stasiun +
                                '</option>';
                        }
                    } else {
                        html = '<option>--Tidak Ada Data--</option>';
                    }

                    stasiun.html(html);
                }
            })

        })


        function reset_field() {
            $("#data_tambah").empty();
            $("#pilih_instrument").empty();
            $("#select_data").empty();
            $("#pilih_instrument").empty();
        }

        $("#stasiun").change(function() {
            var stasiun = $(this).val();
            var stasiun_type = $("#stasiun").select2().find(":selected").data("id");
            var regions_id = $("#ms_regions_id").val();

            if (regions_id == "") {
                toastr.error("Site harus diisi");
                $(this).val('');
                return
            }

            $("#elevasi").prop('disabled', true);
            $("#status").prop('disabled', true);
            $("#data_tambah").prop('disabled', true);

            reset_field();

            switch (stasiun_type) {
                case 'HIDROLOGI':
                    $("#data_tambah").prop('disabled', false);
                    $("#data_tambah").html('<option value="Tinggi Muka Air">Tinggi Muka Air</option>' +
                        '<option value="Rainfall">Rainfall</option>' +
                        '<option value="Elevasi Puncak">Elevasi Puncak</option>' +
                        '<option value="Elevasi Spillway">Elevasi Spillway</option>' +
                        '<option value="Batas Kritis">Batas Kritis</option>');

                    get_instrument('by_station');
                    break;
                case 'EWS':
                    $("#data_tambah").prop('disabled', false);
                    $("#data_tambah").html('<option value="Tinggi Muka Air">Tinggi Muka Air</option>' +
                        '<option value="Rainfall">Rainfall</option>' +
                        '<option value="Elevasi Puncak">Elevasi Puncak</option>' +
                        '<option value="Elevasi Spillway">Elevasi Spillway</option>' +
                        '<option value="Batas Kritis">Batas Kritis</option>');
                    get_instrument('by_station');
                    break;
                case 'GEOLOGI':
                    $("#elevasi").prop('disabled', false);
                    $("#status").prop('disabled', false);
                    $("#status").html('<option value="2" selected>Pasca Konstruksi</option>' +
                        '<option value="1">Konstruksi</option>');
                    $('#status').trigger('change');
                    step_geologi(regions_id, stasiun)
                    break;
                default:
                    $("#elevasi").prop('disabled', true);
                    $("#status").prop('disabled', true);
                    $("#data_tambah").prop('disabled', true);
                    get_instrument('by_station');

                    break;
            }
        })


        function step_geologi(regions_id, stasiun) {
            $.ajax({
                url: "<?= base_url('Grafik/getElevation'); ?>",
                type: "GET",
                dataType: "json",
                data: {
                    stasiun: stasiun,
                    regions_id: regions_id,
                },
                success: function(response) {
                    if (response.error !== false) {
                        toastr.error(response.msg);
                    }

                    var options = '<option value="">Select Elevation</option>';
                    $.each(response.data, function(index, item) {
                        options += '<option value="' + item.elevasi_sensor + '">' + item.elevasi_sensor +
                            '</option>';
                    });

                    $("#elevasi").html(options);
                }

            })
        }

        $("#elevasi").change(function() {
            var elevasi = $("#elevasi").val();
            get_instrument('by_elevasi', elevasi)
        })

        function get_instrument(flag, elevasi = 0) {
            var regions_id = $("#ms_regions_id").val();
            var stasiun = $("#stasiun").val();
            $.ajax({
                url: "<?= base_url('Grafik/getInstrument'); ?>",
                type: "GET",
                dataType: "json",
                data: {
                    stasiun: stasiun,
                    regions_id: regions_id,
                    flag: flag,
                    elevasi: elevasi
                },
                success: function(response) {
                    if (response.error !== false) {
                        toastr.error(response.msg);
                    }

                    var options = '<option value="">Select Instrument</option>';
                    $.each(response.data, function(index, item) {
                        options += '<option data-id="' + item.kode_instrument + '" value="' + item.id + '">' + item.nama_instrument +
                            '</option>';
                    });

                    $("#pilih_instrument").html(options);

                }

            })
        }

        $("#pilih_instrument").change(function() {
            var instrument_id = $("#pilih_instrument").val();

            $("#select_data").empty();

            $.ajax({
                url: "<?= base_url('Grafik/getDataJadi'); ?>",
                type: "GET",
                dataType: "json",
                data: {
                    instrument_id: instrument_id,
                },
                success: function(response) {
                    if (response.error !== false) {
                        toastr.error(response.msg);
                    }

                    var options = '';
                    $.each(response.data, function(index, item) {
                        options += '<option value="' + item.id + '">' + item.jenis_sensor + ' (' + item.unit_sensor + ')' +
                            '</option>';
                    });

                    $("#select_data").html(options);

                }

            })
        })


        $("#status").change(function() {
            var id = $(this).val();
            switch (id) {
                case "1":
                    $("#data_tambah").prop('disabled', false);
                    $("#data_tambah").html('<option value="Rainfall">Rainfall</option>' +
                        '<option value="Elevasi Spillway">Elevasi Spillway</option>');
                    break;
                case "2":
                    $("#data_tambah").prop('disabled', false);
                    $("#data_tambah").html('<option value="Batas Kritis">Batas Kritis</option>' +
                        '<option value="Tinggi Muka Air">Tinggi Muka Air</option>' +
                        '<option value="Elevasi Puncak">Elevasi Puncak</option>' +
                        '<option value="Elevasi Spillway">Elevasi Spillway</option>' +
                        '<option value="Rainfall">Rainfall</option>');
                    break;
                default:
                    $("#data_tambah").prop('disabled', true);
                    $("#data_tambah").empty();
                    break;
            }
        });

        function filter() {
            $.ajax({
                url: "<?= base_url('Grafik/filter'); ?>",
                type: "POST",
                dataType: "json",
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData($('#form-filter')[0]),
                beforeSend: function() {
                    $("#loading").removeAttr('style');
                },
                success: function(response) {
                    $("#loading").attr('style', 'display:none');
                    if (response.error !== false) {
                        $('#chart').empty()
                        toastr.error(response.msg);
                    }
                    if ($('#stasiun').val() !== '') {
                        $('#nama_site_text').html($('#stasiun option:selected').html())
                    }
                    generateChart(response.data, response.periode, response.data_tambah)
                },
                complete: function() {
                    $("#loading").attr('style', 'display:none');
                }
            })
        }

        function generateSeries(data, data_tambah) {
            var series = data.map(function(sensor) {
                var namaSensor = sensor.nama_instrument + ' - ' + sensor.jenis_sensor;
                var unitSensor = ' (' + sensor.unit_sensor + ')';
                return {
                    name: namaSensor + unitSensor,
                    data: sensor.detail.map(function(detail) {
                        return detail.data_jadi;
                    })
                };
            });

            // Add data_tambah series if it exists
            if (data_tambah && data_tambah.length > 0) {
                data_tambah.forEach(function(sensor, index) {
                    var namaSensor = sensor.nama_instrument + ' - ' + sensor.jenis_sensor;
                    var unitSensor = ' (' + sensor.unit_sensor + ')';
                    series.push({
                        name: namaSensor + unitSensor,
                        data: sensor.detail.map(function(detail) {
                            return detail.data_jadi;
                        }),
                        yAxisIndex: 1
                    });
                });
            }
            return series;
        }

        function generateXAxis(data, periode) {
            if (periode === "Jam") {
                return data[0].detail.map(function(d) {
                    return d.jam;
                });
            } else if (periode === "Harian") {
                return data[0].detail.map(function(d) {
                    return d.tanggal;
                });
            }
        }

        function calculateOverallMinMax(data) {
            let allValues = [];
            data.forEach(item => {
                item.detail.forEach(detailItem => {
                    allValues.push(parseFloat(detailItem.data_jadi));
                });
            });

            let minValue = Math.min(...allValues);
            let maxValue = Math.max(...allValues);
            return {
                min: minValue,
                max: Math.round(maxValue)
            };
        }

        var chart;

        function generateChart(data, periode, data_tambah) {

            var region = $('#ms_regions_id option:selected').html()
            var kode_instrument = $('#pilih_instrument option:selected').attr('data-id')
            var nama_instrument = $('#pilih_instrument option:selected').html()
            var waktu = $('#waktu').val()

            var filename = region + '_' + kode_instrument + '_' + nama_instrument + '_' + waktu



            // Menghitung nilai minimum dan maksimum dari semua data_jadi dalam data
            let leftY = calculateOverallMinMax(data);

            var yAxisConfig = [];
            data.forEach(function(item, index) {
                let showYAxis = index === 0 ? true : false;
                yAxisConfig.push({
                    title: {
                        text: item.unit_sensor,
                        style: {
                            fontWeight: 'normal',
                            fontSize: '16px'
                        }
                    },
                    min: 0,
                    // max: Math.ceil(leftY.max / 200) * 200,
                    max: leftY.max,
                    tickAmount: 5,
                    show: showYAxis
                });
            });
            // if (data[0].nama_instrument.toUpperCase() === "RAINFALL") {
            //     yAxisConfig[0].reversed = true;
            // }
            if (data_tambah && data_tambah.length > 0) {

                let rightY = calculateOverallMinMax(data_tambah);
                var newConfig = {
                    opposite: true,
                    reversed: true,
                    yAxisIndex: 1,
                    title: {
                        text: data_tambah[0].unit_sensor,
                        style: {
                            fontWeight: 'normal',
                            fontSize: '16px'
                        }
                    },

                    // min: 0,
                    // max: rightY.max
                };
                // if (data_tambah[0].nama_instrument.toUpperCase() === "RAINFALL") {
                //     newConfig.reversed = true;
                //     newConfig.yAxisIndex = 1;
                // }
                yAxisConfig.push(newConfig);

            }
            console.log(yAxisConfig);

            var options = {
                chart: {
                    type: 'line',
                    height: '100%',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,

                        },
                        export: {
                            csv: {
                                filename: filename,
                            },
                            svg: {
                                filename: filename,
                            },
                            png: {
                                filename: filename,
                            }
                        }
                    }
                },
                series: generateSeries(data, data_tambah),
                xaxis: {
                    categories: generateXAxis(data, periode),
                    title: {
                        text: 'Periode Monitoring',
                        style: {
                            fontWeight: 'normal',
                            fontSize: '16px'
                        }
                    }
                },
                yaxis: yAxisConfig,
                stroke: {
                    curve: 'smooth'
                },
                markers: {
                    size: 4
                },
                legend: {
                    show: true,
                    onItemClick: {
                        toggleDataSeries: true
                    },
                    showForSingleSeries: true
                },


            };

            if (chart && chart.destroy) {
                chart.destroy();
            }

            chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }
    </script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/widget/w_chart.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:41:47 GMT -->

</html>