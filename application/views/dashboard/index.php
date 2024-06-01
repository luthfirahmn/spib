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
    <meta name="author" content="SPIB" />
    <link rel="icon" href="<?= base_url() . 'assets/logo_simetri.png' ?>" type="image/x-icon" />
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


    <div class="pc-container ">
        <div class="pc-content">
            <div class="d-flex justify-content-between">
                <div>
                    <h3>
                        <?= $nama_region->site_name ?>
                    </h3>
                </div>
                <div class="form-group d-flex align-items-center mb-2">
                    <label class="form-label me-2 mb-0 w-50">Pilih Site</label>
                    <select class="form-control" name="ms_regions_id" id="ms_regions_id">
                        <option value="" selected disabled>Pilih Site</option>
                        <?php foreach ($region as $reg) { ?>
                            <option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="carouselContent">

                <?php
                foreach ($station as $index => $row) {

                ?>
                    <div class="card " style="margin-bottom: 5px;">
                        <div class="card-body">
                            <div id="carouselExample<?= $index ?>" class="carousel carousel-dark slide" data-bs-ride="carousel">
                                <div class="carousel-indicators" style="margin-bottom: -0.5rem;">
                                    <?php
                                    $indicatorCount = count($row);
                                    for ($i = 0; $i < $indicatorCount; $i++) {
                                    ?>
                                        <button type="button" data-bs-target="#carouselExample<?= $index ?>" data-bs-slide-to="<?= $i ?>" <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?> aria-label="Slide <?= $i + 1 ?>"></button>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="carousel-inner">
                                    <?php
                                    $firstStasiun = true;
                                    foreach ($row as $indexDetailStasiun => $detailStasiun) {
                                    ?>
                                        <div class="carousel-item <?= $firstStasiun ? 'active' : '' ?>">
                                            <h5 class="mb-2">STASIUN <?= $indexDetailStasiun ?></h5>
                                            <div class="row">
                                                <?php
                                                foreach ($detailStasiun as $details) {
                                                ?>
                                                    <div class="col-md-2">
                                                        <div class="card bg-light bg-gradient">
                                                            <div class="card-body py-1 px-2 d-flex align-items-center">
                                                                <img width="30" height="30" src="<?= base_url('assets/upload/sensor/') . $details->icon ?>" alt="Gambar" class="me-2 order-1">
                                                                <div class="order-2">
                                                                    <span class="text-sm"><?= $details->instrument ?></span>
                                                                    <br> <!-- Untuk line break -->
                                                                    <span class="text-sm font-semibold"><?= isset($details->value) ? $details->value : 0 ?></span>
                                                                    <br>
                                                                    <span class="fs-6 font-light"><i class="ti ti-clock"></i> <?= $details->last_update ?> </span>

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
                                <!-- <button class="carousel-control-next" type="button" data-bs-target="#carouselExample<?= $index ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button> -->
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>

            </div>

        </div>

        <div class="mb-1">
            <hr>
        </div>
    </div>
    <?php $this->load->view('include/footer.php'); ?>


    <script src="<?= base_url() ?>assets/js/plugins/popper.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/simplebar.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/config.js"></script>
    <script src="<?= base_url() ?>assets/js/pcoded.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/feather.min.js"></script>
    <script src="<?= base_url() ?>assets/js/customizer.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        function refreshContent() {
            $("#carouselContent").load(window.location.href + " #carouselContent > *");
        }

        $(document).ready(function() {
            setInterval(refreshContent, 60000); // Refresh every 60000 milliseconds (1 minute)
        });
        $(document).ready(function() {
            document.body.style.zoom = 1.0
            var scale = 'scale(1)';
            document.body.style.webkitTransform = scale; // Chrome, Opera, Safari
            document.body.style.msTransform = scale; // IE 9
            document.body.style.transform = scale;
        });
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