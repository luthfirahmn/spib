<!DOCTYPE html>
<html lang="en">
	<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_flu-uppy.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:17 GMT -->
	<head>
		<title>Uppy File Upload | Berry Bootstrap 5 Admin Template</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="description" content="Berry is made using Bootstrap 5 design framework. Download the free admin template & use it for your project." />
		<meta name="keywords" content="Berry, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template" />
		<meta name="author" content="CodedThemes" />
		<link rel="icon" href="https://berrydashboard.io/bootstrap/default/assets/images/favicon.svg" type="image/x-icon" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/plugins/uppy.min.css" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" id="main-font-link" />
		<link rel="stylesheet" href="<?= base_url()?>assets/fonts/tabler-icons.min.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/fonts/feather.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/fonts/fontawesome.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/fonts/material.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/style.css" id="main-style-link" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/style-preset.css" id="preset-style-link" />

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
										<a href="<?= base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item">
										<a href="<?= base_url('Sensor')?>">Sensor</a>
									</li>
									<li class="breadcrumb-item" aria-current="page">tambah</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-header">
								<h5>Tambah Sensor</h5>
							</div>
							
							<div class="card-body">
							<form action="<?= base_url('Sensor/tambah_proses')?>" method="post" enctype="multipart/form-data">
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="jenis_sensor">Jenis Sensor</label>
										<input type="text" class="form-control" id="jenis_sensor" name="jenis_sensor" required>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="ms_regions_id">Site</label>
										<select class="form-control" name="ms_regions_id" id="ms_regions_id">
											<?php foreach($region as $reg){?>
											<option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="unit_sensor">Satuan Sensor</label>
										<input type="text" class="form-control" id="unit_sensor" name="unit_sensor" required>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="var_name">Nama Variabel</label>
										<input type="text" class="form-control" id="var_name" name="var_name" required>
									</div>
								</div>
								<button type="submit" class="btn btn-primary">Submit</button>
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
		<script src="<?= base_url()?>assets/js/plugins/popper.min.js"></script>
		<script src="<?= base_url()?>assets/js/plugins/simplebar.min.js"></script>
		<script src="<?= base_url()?>assets/js/plugins/bootstrap.min.js"></script>
		<script src="<?= base_url()?>assets/js/config.js"></script>
		<script src="<?= base_url()?>assets/js/pcoded.js"></script>
		<script src="<?= base_url()?>assets/js/plugins/feather.min.js"></script>
		
		<script src="<?= base_url()?>assets/js/customizer.js"></script>
		<script type="text/javascript">
			Dropzone.options.imageUpload = {
				maxFilesize:1
			};
		</script>
	</body>
	<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_flu-uppy.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:18 GMT -->
</html>