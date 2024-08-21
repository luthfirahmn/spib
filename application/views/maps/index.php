<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_flu-uppy.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:17 GMT -->

<head>
    <title>Maps</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Berry is made using Bootstrap 5 design framework. Download the free admin template & use it for your project." />
    <meta name="keywords" content="Berry, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template" />
    <meta name="author" content="SPIB" />
    <link rel="icon" href="<?= base_url() . 'assets/logo_simetri.png' ?>" type="image/x-icon" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/plugins/uppy.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" id="main-font-link" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/feather.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/material.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css" id="main-style-link" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style-preset.css" id="preset-style-link" />




    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        .leaflet-popup-content-wrapper {
            min-width: 300px;
            max-width: 600px;
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

    <section class="pc-container">
        <div class="pc-content">

            <form action="<?= base_url('Station/tambah_proses') ?>" method="post" enctype="multipart/form-data">
                <div class="row pb-3">
                    <div id="mapid" style="width: 100%;  height: 80vh;"></div>
                </div>

            </form>
        </div>

    </section>

    <footer class="pc-footer float-end" style="display: none;">
        <div class="footer-wrapper container-fluid">
            Copyright © <?php echo date("Y"); ?> <a class="text-primary" href="http://sistemtelemetri.com">SIMETRI</a>
        </div>
    </footer>
</body>
<script src="<?= base_url() ?>assets/js/plugins/popper.min.js"></script>
<script src="<?= base_url() ?>assets/js/plugins/simplebar.min.js"></script>
<script src="<?= base_url() ?>assets/js/plugins/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/config.js"></script>
<script src="<?= base_url() ?>assets/js/pcoded.js"></script>
<script src="<?= base_url() ?>assets/js/plugins/feather.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>

<script src="<?= base_url() ?>assets/js/customizer.js"></script>
<script src="<?= base_url() ?>assets/js/geotz.js"></script>
<script type="text/javascript">
    var mymap = L.map('mapid').setView([-0.789275, 113.921327], 5);
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);
    var marker;
    <?php
    $colors = array('blue', 'red', 'green', 'lightblue', 'yellow', 'purple', 'pink', 'blue-dot', 'red-dot', 'green-dot', 'lightblue-dot', 'yellow-dot', 'purple-dot', 'pink-dot', 'blue', 'red', 'green', 'lightblue', 'yellow', 'purple', 'pink',);
    $i = 0;
    foreach ($station as $stasiun) :
    ?>
        var legend = L.marker([<?= $stasiun->latitude ?>, <?= $stasiun->longitude ?>], {
            icon: new L.Icon({
                iconUrl: 'https://maps.google.com/mapfiles/ms/micons/<?= $colors[$i] ?>.png'
            })
        }).addTo(mymap);
        legend.bindTooltip("<?= $stasiun->nama_stasiun ?>", {
            permanent: true,
            direction: 'top'
        }).openTooltip();

        marker = new L.marker([<?= $stasiun->latitude ?>, <?= $stasiun->longitude ?>], {
            icon: new L.Icon({
                iconUrl: 'https://maps.google.com/mapfiles/ms/micons/<?= $colors[$i] ?>.png'
            })
        }).addTo(mymap).on('click', function(e) {
            var latlng = e.latlng;
            var popupContent = '';
            getTimeAtLocation(latlng, function(time) {
                popupContent = `
            <div class="row">
                <div class="col-5">
                    <img id="stationImage" src="<?= base_url('assets/upload/station/') . $stasiun->foto ?>" style="width: 100%;">
                </div>
                <div class="col-7">
                    <span class="fs-5">STA. <b><?= $stasiun->nama_stasiun ?><br>
                    <?= $stasiun->site_name ?></b><br>
                    <a href="https://www.google.com/maps/search/?api=1&query=<?= $stasiun->latitude ?>,<?= $stasiun->longitude ?>" target="_blank" class="fs-6"><?= number_format($stasiun->longitude, 6) . " " . number_format($stasiun->latitude, 6) ?></a>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center text-muted">
                <p class=" fs-6">` + time + `</p>
                </div>
                <div class="col-12 text-center text-muted">
                    <table class="table" style="width:100%">
                        <tr class="bg-light">
                            <th>Sensor</th>
                            <th>Nilai</th>
                        </tr>
                        <?php
                        if (!empty($stasiun->sensor_data)) {
                            foreach ($stasiun->sensor_data as $row) {
                                echo '
                                        <tr>
                                            <td>' . $row->jenis_sensor . ' (' . $row->unit_sensor . ') </td>
                                            <td>' . $row->data_jadi . '</td>
                                        </tr>';
                            }
                        } else {
                            echo '
                                    <tr>
                                        <td colspan="2">Tidak ada data</td>
                                    </tr>';
                        }
                        ?>
                        
                </table>
                </div>
            </div>
            <a href="<?= base_url() ?>/data" type="button" class="btn btn-light-primary mb-3 btn-sm">Detail</a>
            `;

                var popup = L.popup()
                    .setLatLng(latlng)
                    .setContent(popupContent)
                    .openOn(mymap);
            });

            var popup = L.popup()
                .setLatLng(e.latlng)
                .setContent(popupContent)
                .openOn(mymap);
        });


    <?php
        $i = ($i + 1) % count($colors);
    endforeach; ?>

    function getTimeAtLocation(latlng, callback) {
        GeoTZ.find(latlng.lat, latlng.lng)
            .then(function(timezone) {
                var currentDate = new Date().toLocaleDateString('en-US', {
                    timeZone: timezone,
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                var currentTime = new Date().toLocaleTimeString('en-US', {
                    timeZone: timezone,
                    hour: '2-digit',
                    minute: '2-digit'
                });
                var formattedDateTime = currentDate + ', ' + currentTime;
                callback(formattedDateTime);
            })
            .catch(function(error) {
                console.error('Error finding timezone:', error);
                callback('Error');
            });
    }
</script>

<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_flu-uppy.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:18 GMT -->

</html>