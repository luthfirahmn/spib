<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->

<head>
	<title>Sensor</title>
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
								<h5 class="m-b-10">Sensor</h5>
							</div>
							<ul class="breadcrumb">
								<li class="breadcrumb-item">
									<a href="<?= base_url() ?>">Home</a>
								</li>
								<li class="breadcrumb-item" aria-current="page">Sensor</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-header">
							<h5>Data Sensor</h5>
						</div>
						<div class="card-body table-border-style">
							<?php if ($hak_akses->insert == '1') { ?>
								<a href="<?= base_url('Sensor/tambah') ?>" type="button" class="btn btn-light-primary mb-3">
									<i class="ti ti-plus"></i>Add Data
								</a>
							<?php } ?>


							<div class="row">
								<!-- <div class="form-group col-md-2">
                                    <label class="form-label" for="keyword">Keyword:</label>
                                    <input type="text" class="form-control" id="keyword" name="keyword">
                                </div> -->
								<div class="form-group col-md-3">
									<label class="form-label" for="ms_regions_id">Select Region</label>
									<select class="form-control" name="ms_regions_id" id="ms_regions_id">
										<?php foreach ($region as $reg) { ?>
											<option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
										<?php } ?>
									</select>
								</div>


							</div>

							<div id="list_data_filter">
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php $this->load->view('include/footer.php'); ?>
	<script src="<?= base_url() ?>assets/js/plugins/popper.min.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/simplebar.min.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/bootstrap.min.js"></script>
	<script src="<?= base_url() ?>assets/js/config.js"></script>
	<script src="<?= base_url() ?>assets/js/pcoded.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/feather.min.js"></script>
	<script src="<?= base_url() ?>assets/js/customizer.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/simple-datatables.js"></script>
	<script>
		const dataTable = new simpleDatatables.DataTable('#pc-dt-simple');
	</script>

	<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
	<script src="
		https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<script>
		function Createtable() {
			var ms_regions_id = $("#ms_regions_id").val();
			var list_data_filter = $('#list_data_filter');
			$.ajax({
				url: "<?php echo base_url('Sensor/list'); ?>",
				type: "POST",
				data: {
					ms_regions_id: ms_regions_id
				},
				success: function(data) {
					var json = data,
						obj = JSON.parse(json);

					console.log(obj.tabel);
					list_data_filter.html(obj.tabel);
				}
			});
		}

		$(document).ready(function() {
			Createtable()
		})

		$("#ms_regions_id").change(function() {
			Createtable()
		});
	</script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->

</html>