<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->

<head>
    <title>CCTV</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.7/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.7/sweetalert2.min.js"></script>
</head>

<style>
    .btn-smaller {
        font-size: 11px;
    }
</style>

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
                                <h5 class="m-b-10">CCTV</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url() ?>">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">CCTV</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-lg-flex px-4 py-1 justify-content-between">
                            <div class="w-50 my-2">
                                <h5>CCTV</h5>
                            </div>
                            <div class="ms-auto  align-items-center mt-2 w-100 ">
                                <div class="form-group  d-lg-flex  align-items-center">
                                    <div class="d-lg-flex align-items-center w-100 text-nowrap">
                                        <label class="form-label me-3 " for="ms_regions_id">Select Region</label>
                                        <select class="form-control  me-2 mb-2 mb-lg-0 " style="min-width: 200px ;" name="ms_regions_id" id="ms_regions_id">
                                            <?php foreach ($region as $reg) { ?>
                                                <option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <select class="form-control mb-2 mb-lg-0   me-2" style="width: 60%;" id="updateInterval">
                                        <option disabled>Interval Update Capture</option>
                                        <option value="1" selected>1 Minute</option>
                                        <option value="5">5 Minute</option>
                                        <option value="10">10 Minute</option>
                                        <option value="30">30 Minute</option>
                                        <option value="60">60 Minute</option>
                                    </select>

                                    <button type="button" class="btn btn-outline-info d-inline-flex w-75 me-2 mb-2 mb-lg-0 " onclick="refreshCCTV()">
                                        <i class="ti ti-refresh me-1"></i>Refresh CCTV

                                    </button>
                                    <button type="button" class="btn btn-outline-primary d-inline-flex w-75" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                        <i class="ti ti-plus me-2"></i>Add CCTV
                                    </button>


                                </div>

                            </div>

                        </div>
                        <div class="card-body table-border-style">
                            <div id="listCCTV" class="row"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Add CCTV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formcctv">
                        <select class="form-control mb-3" name="add_regions_id" id="add_regions_id">
                            <?php foreach ($region as $reg) { ?>
                                <option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
                            <?php } ?>
                        </select>
                        <input type="text" class="form-control mb-3" name="lokasi" id="lokasi" placeholder="CCTV Name">
                        <input type="url" class="form-control mb-3" name="url" id="url" placeholder="URL">
                        <input type="url_live" class="form-control" name="url_live" id="url_live" placeholder="URL Live View">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="add_data">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit CCTV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formcctvedit">
                        <input type="hidden" name="id_edit" id="id_edit">
                        <input type="text" class="form-control mb-3" name="lokasi_edit" id="lokasi_edit" placeholder="CCTV Name">
                        <input type="url" class="form-control mb-3" name="url_edit" id="url_edit" placeholder="URL">
                        <input type="url_live" class="form-control" name="url_live_edit" id="url_live_edit" placeholder="URL Live View">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="edit_data">Edit</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="width: 100%; height:100%;">
                        <embed id="modalImage" width="460" height="280" type="image/jpeg">
                    </div>
                </div>
            </div>
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
    <script src="<?= base_url() ?>assets/js/plugins/simple-datatables.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
    <script src="
	https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
    </script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->
<script>
    function refreshCCTV() {

        const url = `https://admin:telemetri2020@103.141.188.245:231/cgi-bin/snapshot.cgi?channel=1`;
        window.open(url, '_blank');
        location.reload()
    }


    $(document).ready(function() {
        var region_id = $("#ms_regions_id").val();
        getData(region_id);
        setIntervals(region_id)

    });

    function setIntervals(region_id) {
        var updateInterval = $("#updateInterval").val() * 60000;
        setInterval(function() {
            getData(region_id);
        }, updateInterval);
    }


    $("#ms_regions_id").on("change", function() {
        var region_id = $(this).val()
        getData(region_id);
        setIntervals(region_id)
    })

    function resetForm(form) {
        $(':input', form)
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .prop('checked', false)
            .prop('selected', false);
    }
    $('#btnTambah').click(function() {
        $('#modalTambah').modal('show');
        $("#formcctv")[0].reset()
    });


    $("#add_data").click(function() {
        var formData = $("#formcctv").serialize();

        $.ajax({
            type: "POST",
            url: "<?= base_url("CCTV/add_data") ?>",
            dataType: "json",
            data: formData,
            success: function(response) {
                if (response.error) {
                    toastr.error(response.message)
                } else {

                    toastr.success(response.message)
                    var region_id = $("#ms_regions_id").val();
                    getData(region_id);

                    $('#modalTambah').modal('hide');
                }

            },
            error: function(xhr, status, error) {

                toastr.error(response.error)
            }
        });
    });


    $("#edit_data").click(function() {
        var formData = $("#formcctvedit").serialize();

        $.ajax({
            type: "POST",
            url: "<?= base_url("CCTV/edit_data") ?>",
            dataType: "json",
            data: formData,
            success: function(response) {
                if (response.error) {
                    toastr.error(response.message)
                } else {

                    toastr.success(response.message)
                    var region_id = $("#ms_regions_id").val();
                    getData(region_id);

                    $('#modalEdit').modal('hide');
                }

            },
            error: function(xhr, status, error) {

                toastr.error(response.error)
            }
        });
    });

    function getData(regionId) {
        var dataHtml = "";
        if (regionId != '') {
            $.ajax({
                url: "<?= base_url('CCTV/list/') ?>" + regionId,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    if (data.error === true) {
                        dataHtml = `<div class="container-fluid bg-light d-flex align-items-center justify-content-center">
							<div class="bg-grey-100 text-center p-4">
							<h4 class="">` + data.message + `</h1>
							</div>
						</div>`;
                    } else {

                        data.data.forEach(function(item) {
                            dataHtml += `<div class="col-md-4" >
						<div class="d-flex justify-content-between align-items-center">
							<p class="mt-3">` + item.lokasi + `</p>

							<div class="ms-auto">
								<button type="button" class="btn btn-sm btn-outline-info btn-smaller" onclick="edit(` + item.id + `,'` + item.lokasi + `','` + item.url + `','` + item.url_live + `')">Edit</button>
								<button type="button" class="btn btn-sm btn-outline-primary btn-smaller" onclick="preview('` + item.url + `','` + item.lokasi + `')">Preview</button>
								<button type="button" class="btn btn-sm btn-outline-success btn-smaller" onclick="redirect('` + item.url_live + `')">Live View</button>
								<button type="button" class="btn btn-sm btn-outline-danger btn-smaller" onclick="deleteData(` + item.id + `)">Delete</button>
							</div>
						</div>
                            <embed class="img-cctv" style="border: 1.7px solid #cacaca; border-radius:10px"  src="` +
                                item.url + `"  width="100%" height="250" type="image/jpeg" />
					</object>
					</div>
					`;
                        });
                    }

                    $("#listCCTV").html(dataHtml)


                    // <embed class="img-cctv"  src="` + '<?= base_url("CCTV/redirecting/") ?>' +
                    //             item.url + `"  width="380" height="250" type="image/jpeg" />
                    // function reloadImage() {
                    //     $('.img-cctv').attr('src', function(i, src) {
                    //         return src.split('?')[0] + '?' + new Date().getTime();
                    //     });
                    // }

                    // setInterval(reloadImage, 3000);

                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        } else {
            dataHtml = `<div class="container-fluid bg-light d-flex align-items-center justify-content-center ">
							<div class="bg-grey-100 text-center p-4">
							<h4 class="">Tidak Ada Data</h1>
							</div>
						</div>`;
            $("#listCCTV").html(dataHtml)
        }
    }


    function edit(id, lokasi, urls, url_live) {
        $('#modalEdit').modal('show');
        $('#id_edit').val(id);
        $('#lokasi_edit').val(lokasi);
        $('#url_edit').val(urls);
        $('#url_live_edit').val(url_live);
    }


    function preview(url, lokasi) {
        $('#modalImage').attr('src', url);
        $('#modalTitle').html(lokasi);

        $('#imageModal').modal('show');

    }


    function redirect(urls) {
        window.open(urls, '_blank');
    }

    function deleteData(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: "<?= base_url('CCTV/delete_data/') ?>" + id,
                    type: "POST",
                    dataType: "json",
                    success: function(response) {
                        if (response.error) {
                            toastr.error(response.message)
                        } else {

                            var region_id = $("#ms_regions_id").val();
                            getData(region_id);
                            toastr.success(response.message)
                        }

                    },
                    error: function(xhr, status, error) {

                        toastr.error(response.error)
                    }
                })
            }
        })
    }
</script>

</html>