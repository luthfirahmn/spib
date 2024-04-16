<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->

<head>
    <title>Data</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description"
        content="Berry is made using Bootstrap 5 design framework. Download the free admin template & use it for your project." />
    <meta name="keywords"
        content="Berry, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template" />
    <meta name="author" content="CodedThemes" />
    <link rel="icon" href="https://berrydashboard.io/bootstrap/default/assets/images/favicon.svg" type="image/x-icon" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap"
        id="main-font-link" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/feather.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/material.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css" id="main-style-link" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style-preset.css" id="preset-style-link" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
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

            <?php if ($this->session->flashdata('warning')) : ?>
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <?php echo $this->session->flashdata('warning'); ?>
            </div>
            <?php endif; ?>

            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Master Data</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url() ?>">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Master Data</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Master Data</h5>
                        </div>
                        <div class="card-body table-border-style">

                            <div class="d-flex flex-wrap gap-2" bis_skin_checked="1">


                                <button type="button" class="btn btn-outline-primary d-inline-flex"
                                    data-bs-toggle="modal" data-bs-target="#modalTambah">
                                    <i class="ti ti-plus"></i>Tambah
                                </button>


                                <button type="button" class="btn btn-outline-secondary d-inline-flex">
                                    <i class="ti ti-cloud-upload"></i>Upload
                                </button>
                                <button type="button" class="btn btn-outline-success d-inline-flex" id="download_all">
                                    <i class="ti ti-download"></i>Download All
                                </button>
                                <button type="button" class="btn btn-outline-danger d-inline-flex">
                                    <i class="ti ti-trash"></i>Delete All
                                </button>
                            </div>
                            </br>
                            <div class="row">
                                <!-- <div class="form-group col-md-2">
                                    <label class="form-label" for="keyword">Keyword:</label>
                                    <input type="text" class="form-control" id="keyword" name="keyword">
                                </div> -->
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
                                    <label class="form-label" for="instrument">Instrument</label>
                                    <select class="form-control" name="instrument" id="instrument">
                                        <option>--Pilih Instrument--</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-label" for="type">Type</label>
                                    <select class="form-control" name="type" id="type">
                                        <option value="MANUAL">Manual</option>
                                        <option value="OTOMATIS">Otomatis</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label class="form-label" for="date">Tanggal</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <!-- <div class="form-group col-md-2" style="margin-top:2rem">
                                    <button type="button" class="btn btn-info d-inline-flex" id="tombol_cari">
                                        <i class="ti ti-search"></i>Search
                                    </button>
                                </div> -->

                            </div>


                            <div class="table-responsive">
                                <table class="table" id="pc-dt-simple">

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col my-1">
                    <p class="m-0">Copyright &copy; <a href="#">Codedthemes</a>
                    </p>
                </div>
                <div class="col-auto my-1">
                    <ul class="list-inline footer-link mb-0">
                        <li class="list-inline-item">
                            <a href="#">Home</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">Privacy Policy</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">Contact us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalInfoLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <!-- Ubah ukuran modal dari lg ke xl -->
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoLabel">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="site">Site</label>
                                <select class="form-control" id="add_site" name="add_site">
                                    <option value="" selected>--- Pilih Site ---</option>
                                    <?php foreach ($region as $reg) { ?>
                                    <option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instrument">Instrument</label>
                                <select class="form-control" id="add_instrument" name="add_instrument">
                                    <option value="" selected>--Pilih Instrument--</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" class="form-control" id="add_tanggal" name="add_tanggal" max="<?php date_default_timezone_set('Asia/Jakarta');
																													echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam">Jam</label>
                                <input type="time" class="form-control" id="add_jam" name="add_jam">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div id="add_sensor"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="add_data">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
    <script src="<?= base_url() ?>assets/js/plugins/simple-datatables.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
    <script src="
	https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
    $("#add_data").click(function() {
        var add_instrument = $("#add_instrument").val();
        var add_site = $("#add_site").val();
        var add_tanggal = $("#add_tanggal").val();
        var add_jam = $("#add_jam").val();
        var hitung_sensor = [];
        var isAllValueExist = true;
        $(".hitung_sensor").each(function() {
            if ($(this).val() != "") {
                hitung_sensor.push({
                    id: $(this).attr('id'),
                    value: $(this).val()
                });
            } else {
                isAllValueExist = false;
            }
        });
        if (add_instrument == "" || add_site == "" || add_tanggal == "" || add_jam == "" || isAllValueExist ==
            false) {
            toastr.info('Harap isi semua kolom yang tersedia')
            return;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Data/add_data'); ?>",
            dataType: "json",
            data: {
                add_instrument: add_instrument,
                add_site: add_site,
                add_tanggal: add_tanggal,
                add_jam: add_jam,
                hitung_sensor: hitung_sensor
            },
            success: function(response) {
                if (!response.error) {

                    toastr.success('Data berhasil ditambahkan');
                    $('#modalTambah').modal('hide');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Gagal menambahkan data');
            }
        });
    })

    $("#instrument").change(function() {
        var instrument_id = $(this).val();
        var ms_regions_id = $("#ms_regions_id").val();

        $.ajax({
            url: "<?php echo base_url('Data/list'); ?>",
            type: "POST",
            data: {
                instrument_id: instrument_id,
                ms_regions_id: ms_regions_id
            },
            dataType: "json",
            success: function(response) {
                console.log(response);

                if (response.recordsTotal > 0) {
                    var columns = response.columns;
                    var data = response.data;

                    var thead = "<thead><tr>";
                    columns.forEach(function(column) {
                        thead += "<th>" + column + "</th>";
                    });
                    thead += "</tr></thead>";
                    $("#pc-dt-simple").html(thead);

                    var tbody = "<tbody>";
                    data.forEach(function(row) {
                        tbody += "<tr>";
                        columns.forEach(function(column) {
                            tbody += "<td>" + row[column] + "</td>";
                        });
                        tbody += "</tr>";
                    });
                    tbody += "</tbody>";
                    $("#pc-dt-simple").append(tbody);

                    var config = {
                        "processing": true,
                        "serverSide": true,
                        "order": [
                            [0, "desc"]
                        ],
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ]
                    };

                    var datatable = new simpleDatatables.DataTable("#pc-dt-simple",
                        config);
                } else {
                    $("#pc-dt-simple").html(
                        "<thead><tr><th style='text-align: center;'>Tidak ada data</th></tr></thead>"
                    );
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });




    $("#ms_regions_id").change(function() {
        var ms_regions_id = $(this).val();

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var result = JSON.parse(xhttp.responseText).result;
                var html = "<option>--- Pilih Instrument ---</option>";
                var instrument = document.getElementById('instrument');

                if (result.length > 0) {
                    for (i = 0; i < result.length; i++) {
                        html += '<option value=' + result[i].id + '>' + result[i].nama_instrument +
                            '</option>';
                    }
                } else {
                    html = '<option>--Tidak Ada Data--</option>';
                }

                instrument.innerHTML = html;
            }
        };
        xhttp.open("GET", "<?php echo base_url('Data/instrument'); ?>" + '?ms_regions_id=' + ms_regions_id,
            true);
        xhttp.send();
    })


    $("#add_site").change(function() {
        var ms_regions_id = $(this).val();

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var result = JSON.parse(xhttp.responseText).result;
                var html = "<option>--- Pilih Instrument ---</option>";
                var instrument = document.getElementById('add_instrument');

                if (result.length > 0) {
                    for (i = 0; i < result.length; i++) {
                        html += '<option value=' + result[i].id + '>' + result[i].nama_instrument +
                            '</option>';
                    }
                } else {
                    html = '<option>--Tidak Ada Data--</option>';
                }

                instrument.innerHTML = html;
            }
        };
        xhttp.open("GET", "<?php echo base_url('Data/instrument'); ?>" + '?ms_regions_id=' + ms_regions_id,
            true);
        xhttp.send();
    });


    $("#add_instrument").change(function() {
        var add_sensor = document.getElementById('add_sensor');
        var instrument_id = $(this).val();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var result = JSON.parse(xhttp.responseText).result;

                if (result.length > 0) {
                    var html = '';
                    for (i = 0; i < result.length; i++) {
                        html += `
						<div class="row">
									<div class="mb-2 col-md-6">
										<label>` + result[i].jenis_sensor + `</label>
										<input type="number" class="form-control" id="jenis_sensor_id_` + result[i].id + "_" + result[i]
                            .id_sensor + '_' + result[i].flag +
                            `" value="0" ">
									</div>
									<div class="mb-2 col-md-6">
										<label>Hitung ` + result[i].jenis_sensor + `</label>
										<input type="number" class="form-control hitung_sensor" id="hitung_` + result[i].id + "_" + result[i]
                            .id_sensor + '_' + result[i].flag +
                            `" onclick="hitung(this.id, 'jenis_sensor_id_` + result[i].id + "_" + result[i]
                            .id_sensor + '_' + result[i].flag + `')" readonly>
									</div>  </div>`
                    }
                    add_sensor.innerHTML = html;
                } else {
                    var html = `<div>
										<label>Tidak ada data sensor</label>
									</div> `
                    add_sensor.innerHTML = html;
                }
            }
        };
        xhttp.open("GET", "<?php echo base_url('Data/sensor'); ?>" + '?instrument_id=' + instrument_id,
            true);
        xhttp.send();
    })

    function hitung(id, targetId) {
        document.getElementById(id).value = document.getElementById(targetId).value * 1;
    }
    </script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->

</html>