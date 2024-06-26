<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->

<head>
    <title>Data</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Berry is made using Bootstrap 5 design framework. Download the free admin template & use it for your project." />
    <meta name="keywords" content="Berry, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template" />
    <meta name="author" content="SPIB" />
    <link rel="icon" href="<?= base_url() . 'assets/logo_simetri.png' ?>" type="image/x-icon" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" id="main-font-link" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/feather.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/fonts/material.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css" id="main-style-link" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style-preset.css" id="preset-style-link" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.7/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.7/sweetalert2.min.js"></script>
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

                                <?php if ($hak_akses->insert == '1') { ?>
                                    <button type="button" class="btn btn-outline-primary d-inline-flex" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                        <i class="ti ti-plus"></i>Add Data
                                    </button>
                                <?php } ?>
                                <?php if ($hak_akses->insert == '1') { ?>
                                    <button type="button" class="btn btn-outline-secondary d-inline-flex" data-bs-toggle="modal" data-bs-target="#modalUpload">
                                        <i class="ti ti-cloud-upload"></i>Upload
                                    </button>
                                <?php } ?>

                                <button type="button" class="btn btn-outline-success d-inline-flex" id="download_all">
                                    <i class="ti ti-download"></i>Download All
                                </button>
                                <?php if ($hak_akses->delete == '1') { ?>
                                    <button type="button" class="btn btn-outline-danger d-inline-flex" id="deleteAll">
                                        <i class="ti ti-trash"></i>Delete All
                                    </button>
                                <?php } ?>
                            </div>
                            </br>
                            <div class="row">
                                <!-- <div class="form-group col-md-2">
                                    <label class="form-label" for="keyword">Keyword:</label>
                                    <input type="text" class="form-control" id="keyword" name="keyword">
                                </div> -->
                                <div class="form-group col-md-2">
                                    <label class="form-label" for="ms_regions_id">Region</label>
                                    <select class="form-control" name="ms_regions_id" id="ms_regions_id">
                                        <?php foreach ($region as $reg) { ?>
                                            <option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label class="form-label" for="stasiun">Station</label>
                                    <select class="form-control" name="stasiun" id="stasiun">
                                        <option>Select Station</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-label" for="instrument">Instrument</label>
                                    <select class="form-control" name="instrument" id="instrument">
                                        <option>Select Instrument</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-label" for="type">Data Type</label>
                                    <select class="form-control" name="keterangan" id="keterangan">
                                        <option value="" selected>Select Data Type</option>
                                        <option value="MANUAL">Manual</option>
                                        <option value="OTOMATIS">Otomatis</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-label" for="date">Period</label>
                                    <select class="form-control" name="waktu" id="waktu">
                                        <option value="jam" selected>Jam-Jaman</option>
                                        <option value="hari">Harian</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-label" for="date">Time</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php date_default_timezone_set('Asia/Jakarta');
                                                                                                                echo date('Y-m-d'); ?>">
                                </div>
                                <!-- <div class="form-group col-md-2" style="margin-top:2rem">
                                    <button type="button" class="btn btn-info d-inline-flex" id="tombol_cari">
                                        <i class="ti ti-search"></i>Search
                                    </button>
                                </div> -->

                            </div>


                            <div class="table-responsive">
                                <table class="table table-striped" style="width:100%" id="pc-dt-simple">

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php $this->load->view('include/footer.php'); ?>

    <!-- Modal -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalInfoLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <!-- Ubah ukuran modal dari lg ke xl -->
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoLabel">Add Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="site">Region</label>
                                <select class="form-control" id="add_site" name="add_site">
                                    <option value="" selected>Select Region</option>
                                    <?php foreach ($region as $reg) { ?>
                                        <option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stasiun">Station </label>
                                <select class="form-control" id="add_stasiun" name="add_stasiun">
                                    <option value="" selected>Select Station</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instrument">Instrument</label>
                                <select class="form-control" id="add_instrument" name="add_instrument">
                                    <option value="" selected>Select Instrument</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal">Date</label>
                                <input type="date" class="form-control" id="add_tanggal" name="add_tanggal" max="<?php date_default_timezone_set('Asia/Jakarta');
                                                                                                                    echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam">Time</label>
                                <input type="time" class="form-control" id="add_jam" name="add_jam">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div id="add_sensor"></div>
                        </div>
                        <div class="col-md-6">
                            <div id="hasil_data_jadi"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="add_data">Simpan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Upload -->
    <div class="modal fade" id="modalUpload" tabindex="-1" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <!-- Ubah ukuran modal dari lg ke xl -->
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Upload Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="site">Region</label>
                                <select class="form-control" id="upload_site" name="upload_site">
                                    <option value="" selected>Select Region</option>
                                    <?php foreach ($region as $reg) { ?>
                                        <option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stasiun">Station</label>
                                <select class="form-control" id="upload_stasiun" name="upload_stasiun">
                                    <option value="" selected>Select Station</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instrument">Instrument</label>
                                <select class="form-control" id="upload_instrument" name="upload_instrument">
                                    <option value="" selected>Select Instrument</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Upload File</label>
                                <input class="form-control" type="file" id="upload_file" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="formFile" class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary form-control" id="download_template">Download Template</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="upload_data">Simpan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
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
    <!-- <script src="<?= base_url() ?>assets/js/plugins/simple-datatables.js"></script> -->
    <script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
    <script src="
	https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $("#deleteAll").click(function() {
            var instrument_id = $("#instrument").val();
            var ms_regions_id = $("#ms_regions_id").val();
            var keterangan = $("#keterangan").val();
            var tanggal = $("#tanggal").val();
            var waktu = $("#waktu").val();



            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Data yang diperlukan untuk menghapus
                    var data = {
                        instrument_id: instrument_id,
                        ms_regions_id: ms_regions_id,
                        tanggal: tanggal,
                        keterangan: keterangan,
                        waktu: waktu,
                    };

                    $.ajax({
                        url: "<?= base_url('Data/delete_data') ?>",
                        type: "POST",
                        dataType: "json",
                        data: data, // Kirim data yang diperlukan
                        success: function(response) {
                            if (response.error) {
                                toastr.error(response.message)
                            } else {
                                Createtable()
                                toastr.success(response.message)
                            }
                        },
                        error: function(xhr, status, error) {
                            toastr.error(response.error)
                        }
                    })
                }
            })
        })

        $("#add_data").click(function() {
            var add_instrument = $("#add_instrument").val();
            var add_site = $("#add_site").val();
            var add_tanggal = $("#add_tanggal").val();
            var add_jam = $("#add_jam").val();
            var data_mentah = [];
            var data_jadi = [];
            var isAllValueExist = true;
            $(".data_mentah").each(function() {
                if ($(this).val() != "") {
                    data_mentah.push({
                        id: $(this).attr('id_sensor'),
                        value: $(this).val()
                    });
                } else {
                    isAllValueExist = false;
                }
            });

            $(".data_jadi_id_sensor").each(function() {
                if ($(this).val() != "") {
                    data_jadi.push({
                        id: $(this).attr('id_sensor'),
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
                    data_mentah: data_mentah,
                    data_jadi: data_jadi
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

        function Createtable() {
            if ($.fn.DataTable.isDataTable('#pc-dt-simple')) {
                $('#pc-dt-simple').empty();
                $('#pc-dt-simple').DataTable().destroy();
                $('#pc-dt-simple').empty();
            }

            var instrument_id = $("#instrument").val();
            var ms_regions_id = $("#ms_regions_id").val();
            var keterangan = $("#keterangan").val();
            var waktu = $("#waktu").val();
            var tanggal = $("#tanggal").val();
            $.ajax({
                url: "<?php echo base_url('Data/column'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    instrument_id: instrument_id,
                    ms_regions_id: ms_regions_id,
                    keterangan: keterangan,
                    tanggal: tanggal,
                    waktu: waktu,
                },
                success: function(response) {
                    var columns = response;
                    if (columns != "") {

                        var tableColumns = columns.map(function(columnName, index) {
                            if (index === 0) {
                                return {
                                    data: "id",
                                    title: 'No'
                                };
                            } else if (index === 1) {
                                return {
                                    data: "kode_instrument",
                                    title: 'Instrument Code'
                                };
                            } else if (index === 2) {
                                return {
                                    data: "nama_instrument",
                                    title: 'Instrument Name'
                                };
                            } else if (index === 3) {
                                return {
                                    data: "tanggal",
                                    title: 'Date'
                                };
                            } else {

                                if (columnName == 'jam') {
                                    return {
                                        data: "jam",
                                        title: "Time"
                                    };
                                } else if (columnName == 'keterangan') {
                                    return {
                                        data: "keterangan",
                                        title: 'Data Type'
                                    };
                                }
                                return {
                                    data: columnName,
                                    title: columnName
                                };
                            }
                        });

                        tableColumns.push({
                            data: null,
                            title: "Actions",
                            render: function(data, type, row) {
                                return `
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item delete-row" href="#" data-id="${row.id}">Delete</a></li>
                                </ul>
                            </div>
                        `;
                            }
                        });

                        new DataTable("#pc-dt-simple", {
                            scrollY: true,
                            responsive: false,
                            lengthChange: false,
                            autoWidth: false,
                            serverSide: true,
                            processing: true,
                            paging: true,
                            searching: false,
                            lengthMenu: [
                                [10, 25, 50, 100, -1],
                                [10, 25, 50, 100, "All"],
                            ],
                            columnDefs: [{
                                    orderable: false,
                                    searchable: false,
                                    targets: 0,
                                },
                                {
                                    width: "100px",
                                    targets: 0,
                                },
                            ],
                            dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                            pageLength: 10,
                            ajax: {
                                url: "<?php echo base_url('Data/list'); ?>",
                                type: "POST",
                                data: {
                                    instrument_id: instrument_id,
                                    ms_regions_id: ms_regions_id,
                                    keterangan: keterangan,
                                    tanggal: tanggal,
                                    waktu: waktu,
                                },
                                dataType: "json",
                                dataSrc: function(data) {
                                    return data.data.length > 0 ? data.data : null;
                                },
                            },
                            columns: tableColumns,
                            language: {
                                emptyTable: "Tidak ada data yang tersedia",
                            },
                        });


                    } else {
                        $("#pc-dt-simple").html(
                            "<thead><tr><th style='text-align: center;'>Tidak ada data</th></tr></thead>"
                        );
                    }

                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                },
            });


        }

        function reload_table() {
            $('#pc-dt-simple').ajax.reload()
        }

        $("#instrument").change(function() {
            Createtable()
        });

        $("#keterangan").change(function() {

            Createtable()
        });

        $("#waktu").change(function() {

            var option = $(this).val();

            switch (option) {
                case "jam":
                    $("#tanggal").attr("type", "date");
                    $("#tanggal").val('<?= date("Y-m-d"); ?>');
                    break;
                case "hari":
                    $("#tanggal").attr("type", "month");
                    $("#tanggal").val('<?= date("Y-m"); ?>');
                    break;
                default:
                    $("#tanggal").attr("type", "date");
                    break;
            }

            Createtable();
        });

        $("#tanggal").change(function() {
            Createtable()
        });

        $("#download_all").click(function() {
            var instrument_id = $("#instrument").val();
            var ms_regions_id = $("#ms_regions_id").val();
            var keterangan = $("#keterangan").val();
            var tanggal = $("#tanggal").val();
            var waktu = $("#waktu").val();

            // Membangun parameter query
            var query_params = "?instrument_id=" + instrument_id + "&ms_regions_id=" + ms_regions_id + "&keterangan=" + keterangan + "&tanggal=" + tanggal + "&waktu=" + waktu;

            // Mengirim permintaan AJAX untuk mengunduh data
            $.ajax({
                url: "<?= base_url('Data/download_all'); ?>" + query_params,
                type: "GET",
                success: function(data) {
                    // Jika berhasil, alihkan pengguna ke URL yang mengandung parameter query
                    window.location = data;
                },
                error: function() {
                    // Jika terjadi kesalahan, tampilka
                    toastr.error('Gagal mengunduh data');
                }
            });
        });

        $(document).ready(function() {
            $("#ms_regions_id").trigger("change");
        });

        $("#ms_regions_id").change(function() {
            var ms_regions_id = $(this).val();

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(xhttp.responseText).result;
                    var html = "<option>Select Station</option>";
                    var stasiun = document.getElementById('stasiun');

                    if (result.length > 0) {
                        for (i = 0; i < result.length; i++) {
                            html += '<option value=' + result[i].id + '>' + result[i].nama_stasiun +
                                '</option>';
                        }
                    } else {
                        html = '<option>Tidak Ada Data</option>';
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

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(xhttp.responseText).result;
                    var html = '<option value="">Select Instrument</option>';
                    var instrument = document.getElementById('instrument');

                    if (result.length > 0) {
                        for (i = 0; i < result.length; i++) {
                            html += '<option value=' + result[i].id + '>' + result[i].nama_instrument +
                                '</option>';
                        }
                    } else {
                        html = '<option value="">Tidak Ada Data</option>';
                    }

                    instrument.innerHTML = html;
                }
            };
            xhttp.open("GET", "<?php echo base_url('Data/instrument'); ?>" + '?ms_stasiun_id=' + ms_stasiun_id,
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
                        html += `<p class="fw-bold">Data Mentah</p>`
                        for (i = 0; i < result.length; i++) {
                            html += `
                                <div class="mb-1 ">
                                <label>` + result[i].jenis_sensor + `</label>
                                <input type="number" class="form-control data_mentah" id_sensor="` + result[i].id + `" id="` + result[i].var_name + `" value="0" ">
                                </div>
                            `
                        }

                        html += `
                        </div>
                        <div class="mt-2 col-md-2">
                            <label></label>
                            <button type="button" class="btn btn-primary" onclick="hitung()">Hitung</button>
                        </div>  
                        `;
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

        $("#add_site").change(function() {
            var ms_regions_id = $(this).val();

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(xhttp.responseText).result;
                    var html = "<option>Select Station</option>";
                    var stasiun = document.getElementById('add_stasiun');

                    if (result.length > 0) {
                        for (i = 0; i < result.length; i++) {
                            html += '<option value=' + result[i].id + '>' + result[i].nama_stasiun +
                                '</option>';
                        }
                    } else {
                        html = '<option>Tidak Ada Data</option>';
                    }

                    stasiun.innerHTML = html;
                }
            };
            xhttp.open("GET", "<?php echo base_url('Data/stasiun'); ?>" + '?ms_regions_id=' + ms_regions_id,
                true);
            xhttp.send();
        });

        $("#add_stasiun").change(function() {
            var ms_stasiun_id = $(this).val();

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(xhttp.responseText).result;
                    var html = '<option value="">Select Instrument</option>';
                    var instrument = document.getElementById('add_instrument');

                    if (result.length > 0) {
                        for (i = 0; i < result.length; i++) {
                            html += '<option value=' + result[i].id + '>' + result[i].nama_instrument +
                                '</option>';
                        }
                    } else {
                        html = '<option value="">Tidak Ada Data</option>';
                    }

                    instrument.innerHTML = html;
                }
            };
            xhttp.open("GET", "<?php echo base_url('Data/instrument'); ?>" + '?ms_stasiun_id=' + ms_stasiun_id,
                true);
            xhttp.send();
        });


        function hitung() {
            var instrument = $("#add_instrument").val();
            var data_mentah = {};
            $('.data_mentah').each(function() {
                var id = $(this).attr('id');
                var val = $(this).val();
                data_mentah[id] = val;
            });

            $.ajax({
                url: "<?= base_url('Data/hitung'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    instrument: instrument,
                    data_mentah: data_mentah
                },
                success: function(response) {
                    if (response.error !== true) {
                        var html = '';
                        html += `<p class="fw-bold ">Data Jadi</p>
                                <table class="table table-striped " id="data_jadi_setelah_hitung">
                                    <tbody>`
                        for (i = 0; i < response.data.length; i++) {
                            html += `
                            <input type="hidden" class="data_jadi_id_sensor" id_sensor="` + response.data[i].id_sensor + `" value="` + response.data[i].hasil + `">
                                        <tr>
                                            <td>` + response.data[i].nama_sensor + `</td>
                                            <td class="fw-bold">` + response.data[i].hasil + `</td>
                                        </tr>`;

                        }
                        html += `
                                    </tbody>
                                </table>`;
                        hasil_data_jadi.innerHTML = html;

                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Terjadi Kesalahan');
                }
            });
        }

        // UPLOAD ===========

        $("#upload_site").change(function() {
            var ms_regions_id = $(this).val();

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(xhttp.responseText).result;
                    var html = "<option>Select Station</option>";
                    var stasiun = document.getElementById('upload_stasiun');

                    if (result.length > 0) {
                        for (i = 0; i < result.length; i++) {
                            html += '<option value=' + result[i].id + '>' + result[i].nama_stasiun +
                                '</option>';
                        }
                    } else {
                        html = '<option>Tidak Ada Data</option>';
                    }

                    stasiun.innerHTML = html;
                }
            };
            xhttp.open("GET", "<?php echo base_url('Data/stasiun'); ?>" + '?ms_regions_id=' + ms_regions_id,
                true);
            xhttp.send();
        });

        $("#upload_stasiun").change(function() {
            var ms_stasiun_id = $(this).val();

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(xhttp.responseText).result;
                    var html = '<option value="">Select Instrument</option>';
                    var instrument = document.getElementById('upload_instrument');

                    if (result.length > 0) {
                        for (i = 0; i < result.length; i++) {
                            html += '<option value=' + result[i].id + '>' + result[i].nama_instrument +
                                '</option>';
                        }
                    } else {
                        html = '<option value="">Tidak Ada Data</option>';
                    }

                    instrument.innerHTML = html;
                }
            };
            xhttp.open("GET", "<?php echo base_url('Data/instrument'); ?>" + '?ms_stasiun_id=' + ms_stasiun_id,
                true);
            xhttp.send();
        });


        $("#upload_instrument").change(function() {
            if ($(this).val() !== '') {
                $('#upload_file').removeAttr('disabled')
            } else {
                $('#upload_file').attr('disabled')
            }
        })

        $("#download_template").click(function() {
            var instrument = $('#upload_instrument').val();

            if (instrument == "") {
                toastr.error('Instrument harus dipilih');
                return
            }
            $.ajax({
                url: "<?= base_url('Data/download_template/'); ?>" + instrument,
                type: "GET",
                success: function(data) {
                    window.location = data;
                },
                error: function() {
                    toastr.error('Gagal mengupload data');
                }
            });
        });

        $('#upload_data').click(function() {
            var site = $('#upload_site').val();
            var stasiun = $('#upload_stasiun').val();
            var instrument = $('#upload_instrument').val();
            var file_data = $('#upload_file').prop('files')[0];
            var form_data = new FormData();
            form_data.append('site', site);
            form_data.append('stasiun', stasiun);
            form_data.append('instrument', instrument);
            form_data.append('file', file_data);

            $.ajax({
                url: '<?php echo base_url("Data/proses_upload_data"); ?>',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (!response.error) {

                        toastr.success('Data berhasil ditambahkan');
                        $('#modalUpload').modal('hide');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Gagal mengupload data');
                }
            });
        });
    </script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->

</html>