<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_flu-uppy.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:17 GMT -->

<head>
    <title>Maps</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description"
        content="Berry is made using Bootstrap 5 design framework. Download the free admin template & use it for your project." />
    <meta name="keywords"
        content="Berry, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template" />
    <meta name="author" content="CodedThemes" />
    <link rel="icon" href="https://berrydashboard.io/bootstrap/default/assets/images/favicon.svg" type="image/x-icon" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/plugins/uppy.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap"
        id="main-font-link" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/feather.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/material.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css" id="main-style-link" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style-preset.css" id="preset-style-link" />




    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <?php $this->load->view('include/header.php'); ?>
    <?php $this->load->view('include/sidebar.php'); ?>
    <section class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url() ?>">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url('Maps') ?>">Maps</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-body">
                            <form action="<?= base_url('Station/tambah_proses') ?>" method="post"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div id="mapid" style="width: 100%; height: 500px;"></div>
                                </div>

                        </div>

                        </form>
                    </div>
                </div>
            </div>

    </section>
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
    <script type="text/javascript">
    var mymap = L.map('mapid').setView([-0.789275, 113.921327], 5);
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);
    var marker;
    <?php
        $colors = array('blue', 'green', 'yellow', 'purple', 'orange', 'black', 'pink', 'gray', 'black', 'red');
        $i = 0;
        foreach ($station as $stasiun) :
        ?>
    marker = new L.marker([<?= $stasiun->latitude ?>, <?= $stasiun->longitude ?>], {
        icon: new L.Icon({
            iconUrl: 'https://maps.google.com/mapfiles/ms/micons/<?= $colors[$i] ?>.png'
        })
    }).addTo(mymap).on('click', function(e) {

        var popupContent =
            '<img id="stationImage" src="<?= base_url('assets/upload/station/') . $stasiun->foto ?>" style="width: 100%;">';
        popupContent += '<p style="margin-top: 10px;">Nama Stasiun: <?= $stasiun->nama_stasiun ?></p>';

        var popup = L.popup()
            .setLatLng(e.latlng)
            .setContent(popupContent)
            .openOn(mymap);


    });
    <?php
            $i = ($i + 1) % count($colors);
        endforeach; ?>
    </script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_flu-uppy.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:18 GMT -->

</html>