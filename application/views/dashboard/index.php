<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->

<head>
    <title>Dashboard</title>
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


    <style>
        .dashed-line {
            flex-grow: 1;
            border-top: 10px dashed black;
            /* Ganti warna garis menjadi hitam */
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .text-container {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            /* Mengatur teks menjadi rata kanan */
        }



        .Ocean {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            width: 100%;
            height: 100px;
            overflow: hidden;
        }

        .Wave {
            width: 1200px;
            animation-name: swell;
            animation-duration: 2s;
            animation-fill-mode: forwards;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
            fill: #4FC3F7;
        }

        @keyframes swell {
            0% {
                transform: translateX(-50%);
            }

            100% {
                transform: translateX(0%);
            }
        }

        .text-with-dashed-line {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 99;
        }

        .text-with-dashed-line span {
            position: absolute;
            z-index: 99;
            background-color: #fff;
            /* Sesuaikan dengan warna latar belakang */
            padding: 0 10px;
            /* Sesuaikan jarak antara teks dan garis */
        }

        .text-with-dashed-line::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            width: 100%;
            /* Sesuaikan panjang garis */
            border-bottom: 1px dashed black;
            /* Sesuaikan warna dan gaya garis */
            z-index: 99;
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
            <div class="d-flex justify-content-between">
                <div>
                    <h3>
                        <?= $nama_region->site_name ?>
                    </h3>
                </div>
                <div class="form-group d-flex">
                    <div class="me-3">
                        <label>Pilih Site</label>
                        <select class="form-control" name="ms_regions_id" id="ms_regions_id">
                            <option value="" selected disabled>Pilih Site</option>
                            <?php foreach ($region as $reg) { ?>
                                <option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label>Set Interval</label>
                        <select class="form-control" name="setInterval" id="setInterval">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="30">30</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="row">
                <?php
                if (isset($klimatologi)) {
                    foreach ($awlr as $row) { ?>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header px-3 py-2">
                                    <h5 class="mb-0">
                                        Stasiun <?= $row->nama_stasiun ?>
                                    </h5>
                                </div>
                                <div>
                                    <div class="card-body">
                                        <div class="row ">
                                            <div class="col-md-4">
                                                <div class="card bg-gray-100">
                                                    <div class="card-header px-3 py-2 font-semibold">
                                                        Animasi Tinggi Muka Air
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="col">
                                                            <div class="text-with-dashed-line position-absolute font-semibold">
                                                                Elevasi Splitway
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="text-with-dashed-line position-absolute mt-5 font-semibold">
                                                                Elevasi Tranducer
                                                            </div>
                                                        </div>
                                                        <div class="Ocean">
                                                            <?php

                                                            $kali = isset($row->details[0]->nilai) ? $row->details[0]->nilai : 0;
                                                            if ($kali < 1) {

                                                                $level_air = 200;
                                                            } else {
                                                                $level_air = $kali * 100;
                                                            }
                                                            ?>
                                                            <svg class="Wave" viewBox="0 0 12960 <?= $level_air ?>">

                                                                <path d="M9720,320C8100,320,8100,0,6480,0S4860,320,3240,320,1620,0,0,0V1120H12960V0C11340,0,11340,320,9720,320Z">

                                                                    <animate dur="5s" repeatCount="indefinite" attributeName="d" values="
              M9720,320C8100,320,8100,0,6480,0S4860,320,3240,320,1620,0,0,0V1120H12960V0C11340,0,11340,320,9720,320Z;
              M9720,0C8100,0,8100,319,6480,319S4860,0,3240,0,1620,320,0,320v800H12960V320C11340,320,11340,0,9720,0Z;
              M9720,320C8100,320,8100,0,6480,0S4860,320,3240,320,1620,0,0,0V1120H12960V0C11340,0,11340,320,9720,320Z
            " />

                                                                </path>

                                                            </svg>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card bg-gray-100">
                                                    <div class="card-header px-3 py-2 font-semibold">
                                                        Elevasi Muka Air Waduk
                                                    </div>
                                                    <div class="card-body">
                                                        <h1 class="">
                                                            <?= isset($row->details[0]->nilai) ? $row->details[0]->nilai : 0 ?>
                                                            <span><?= $row->details[0]->unit_sensor ?> </span>
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="card bg-gray-100">
                                                    <div class="card-header px-3 py-2 font-semibold">
                                                        Status Kesiagaan
                                                    </div>
                                                    <div class="card-body d-flex justify-content-center px-1">
                                                        <?php
                                                        if ($row->details[0]->nilai <  $row->elev_siaga3) {
                                                            echo '<h1 class="">NORMAL</h1>';
                                                        } elseif ($row->details[0]->nilai >=  $row->elev_siaga3 and $row->details[0]->nilai <  $row->elev_siaga2) {
                                                            echo '<h1 class="text-green-900">SIAGA</h1>';
                                                        } elseif ($row->details[0]->nilai >=  $row->elev_siaga2 and $row->details[0]->nilai <  $row->elev_siaga1) {
                                                            echo '<h1 class="text-yellow-900">WASPADA</h1>';
                                                        } elseif ($row->details[0]->nilai >=  $row->elev_siaga3) {
                                                            echo '<h1 class="text-red-900">AWAS</h1>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card bg-gray-100">
                                                    <div class="card-header px-3 py-2 font-semibold">
                                                        Data Elevasi
                                                    </div>
                                                    <div class="card-body">
                                                        <table>
                                                            <tr>
                                                                <td>Elevasi Tranducer</td>
                                                                <td>: <?= $row->elev_tanggul_utama ?> m</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Elevasi Pelimpah</td>
                                                                <td>: <?= $row->elev_pelimpah ?> m</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Elevasi Siaga</td>
                                                                <td>: <?= $row->elev_siaga3 ?> m</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Elevasi Waspada</td>
                                                                <td>: <?= $row->elev_siaga2 ?> m</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Elevasi Awas</td>
                                                                <td>: <?= $row->elev_siaga1 ?> m</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                <?php }
                } ?>
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $firstStasiun = true;
                        foreach ($klimatologi as $row) {
                        ?>
                            <div class="carousel-item <?= $firstStasiun ? 'active' : '' ?>">
                                <h5 class="mb-2"><?= $row->nama_stasiun ?></h5>
                                <div class="row">
                                    <?php
                                    foreach ($row->details as $details) {
                                    ?>
                                        <div class="col-md-2">
                                            <div class="card">
                                                <div class="card-body py-1 px-2 d-flex align-items-center">
                                                    <img width="30" height="30" src="<?= base_url('assets/upload/sensor/') . $details->icon ?>" alt="Gambar" class="me-2 order-1">
                                                    <div class="order-2">
                                                        <span class="text-sm"><?= $details->jenis_sensor ?></span>
                                                        <br> <!-- Untuk line break -->
                                                        <span class="text-sm font-semibold"><?= isset($details->nilai) ? $details->nilai : 0 ?><?= $details->unit_sensor ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php
                            $firstStasiun = false;
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>



            </div>
        </div>
    </div>
    </div>

    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">

        </div>
    </footer>

    <script src="<?= base_url() ?>assets/js/plugins/popper.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/simplebar.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/config.js"></script>
    <script src="<?= base_url() ?>assets/js/pcoded.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/feather.min.js"></script>
    <script src="<?= base_url() ?>assets/js/customizer.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $("#ms_regions_id").change(function() {
            var ms_regions_id = $(this).val();

            $.ajax({
                url: "<?php echo base_url('Dashboard/index/'); ?>" + ms_regions_id,
                type: "GET",
                success: function() {
                    window.location.href = "<?php echo base_url('Dashboard/index/') ?>" + ms_regions_id;
                },
                error: function() {
                    toastr.error('Gagal mengupload data');
                }
            });
        });
    </script>

</body>

</html>