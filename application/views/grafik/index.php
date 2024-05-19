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
    <meta name="author" content="CodedThemes" />
    <link rel="icon" href="https://berrydashboard.io/bootstrap/default/assets/images/favicon.svg" type="image/x-icon" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" id="main-font-link" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/feather.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/material.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css" id="main-style-link" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style-preset.css" id="preset-style-link" />
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
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label class="form-label" for="ms_regions_id">Site:</label>
                                    <select class="form-control" name="ms_regions_id" id="ms_regions_id">
                                        <option>--- Pilih Site ---</option>
                                        <?php foreach ($region as $reg) { ?>
                                            <option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label class="form-label" for="stasiun">Stasiun</label>
                                    <select class="form-control" name="stasiun" id="stasiun">
                                        <option>--Pilih Stasiun--</option>
                                        <?php foreach ($station as $row) { ?>
                                            <option value="<?= $row->lookup_code ?>"><?= $row->lookup_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label class="form-label" for="stasiun">Elevasi</label>
                                    <select class="form-control" name="elevasi" id="elevasi">
                                        <option value="" selected>Pilih Elevasi</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-label" for="stasiun">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="" selected>Pilih Status</option>
                                        <option value="1">Konstruksi</option>
                                        <option value="2">Pasca Konstruksi</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-xl-12 overflow-x-auto">
                            <div class="" id="account-chart"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col my-1">
                    <p class="m-0">Copyright &copy; <a href="#">SPIB</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="<?= base_url() ?>assets/js/plugins/popper.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/simplebar.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/config.js"></script>
    <script src="<?= base_url() ?>assets/js/pcoded.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/feather.min.js"></script>
    <script src="<?= base_url() ?>assets/js/customizer.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/apexcharts.min.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
    <script>
        $("#ms_regions_id").change(function() {
            var ms_regions_id = $(this).val();

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(xhttp.responseText).result;
                    var html = "<option>--- Pilih Stasiun ---</option>";
                    var stasiun = document.getElementById('stasiun');

                    if (result.length > 0) {
                        for (i = 0; i < result.length; i++) {
                            html += '<option value=' + result[i].id + '>' + result[i].nama_stasiun +
                                '</option>';
                        }
                    } else {
                        html = '<option>--Tidak Ada Data--</option>';
                    }

                    stasiun.innerHTML = html;
                }
            };
            xhttp.open("GET", "<?php echo base_url('Data/stasiun'); ?>" + '?ms_regions_id=' + ms_regions_id,
                true);
            xhttp.send();
        });


        $("#stasiun").change(function() {
            var ms_stasiun_id = $(this).val();


            $.ajax({
                url: "<?php echo base_url('Grafik/getStasiunChange'); ?>" + '?ms_stasiun_id=' + ms_stasiun_id,
                type: "GET",
                dataType: "json",
                success: function(response) {

                }
            })
        });

        $("#instrument").change(function() {

            var chartEl = document.querySelector('#account-chart');
            if (chartEl) {
                chartEl.innerHTML = '';
            }

            var instrument = $(this).val();
            var region_id = $("#ms_regions_id").val();

            $.ajax({
                url: "<?= base_url('Grafik/getInstrumentData'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    instrument: instrument,
                    region_id: region_id,
                },
                success: function(response) {

                    var newSeries = [];

                    // Loop melalui data yang diterima dari AJAX
                    $.each(response, function(index, item) {
                        newSeries.push({
                            name: item.jenis_sensor,
                            data: item.data.map(function(data) {
                                return parseFloat(data.val_sensor);
                            })
                        });
                    });

                    var options1 = {
                        series: newSeries,
                        chart: {
                            height: 350,
                            type: 'line',
                            dropShadow: {
                                enabled: true,
                                color: '#000',
                                top: 18,
                                left: 7,
                                blur: 10,
                                opacity: 0.2
                            },
                            zoom: {
                                enabled: false
                            },
                            toolbar: {
                                show: false
                            }
                        },
                        colors: ['#77B6EA', '#545454'],
                        dataLabels: {
                            enabled: true,
                        },
                        stroke: {
                            curve: 'smooth'
                        },
                        title: {
                            text: 'Grafik',
                            align: 'left'
                        },
                        grid: {
                            borderColor: '#e7e7e7',
                            row: {
                                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                                opacity: 0.5
                            },
                        },
                        markers: {
                            size: 1
                        },
                        xaxis: {
                            categories: ['00', '01', '02', '03', '04', '05', '06', '07', '08', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'],
                            title: {
                                text: 'Periode'
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Data'
                            }
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            floating: true,
                            offsetY: -25,
                            offsetX: -5
                        }
                    };

                    new ApexCharts(document.querySelector('#account-chart'), options1).render();
                }
            })
        });


        function shuffle(array) {
            var tmp, current, top = array.length;
            if (top)
                while (--top) {
                    current = Math.floor(Math.random() * (top + 1));
                    tmp = array[current];
                    array[current] = array[top];
                    array[top] = tmp;
                }
            return array;
        };
    </script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/widget/w_chart.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:41:47 GMT -->

</html>