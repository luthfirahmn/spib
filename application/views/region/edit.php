<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form_elements.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:42:43 GMT -->

<head>
	<title>Region</title>
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
		.container {
			max-width: 800px;
			width: 100%;
			margin: 0 auto;
			font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		}
	</style>
	<link rel="stylesheet" href="<?= base_url() ?>assets/loader/css-loader.css">
</head>

<body>
	<div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
	</div>
	<div class="loader loader-default" id="loading_progress"></div>
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
									<a href="<?= base_url('Region') ?>">Region Management</a>
								</li>
								<li class="breadcrumb-item" aria-current="page">edit</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h5>Edit Region</h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<form action="<?= base_url('Region/edit_proses') ?>" method="post" enctype="multipart/form-data">
										<div class="row mb-3">
											<label for="site_name" class="col-sm-3 col-form-label">Region Name</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="site_name" name="site_name" value="<?= $regional->site_name ?>" required />
												<input type="text" class="form-control" id="id" name="id" value="<?= $regional->id ?>" hidden />
											</div>
										</div>
										<div class="row mb-3">
											<label for="app_name" class="col-sm-3 col-form-label">Nama Aplikasi</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="app_name" name="app_name" value="<?= $regional->app_name ?>" required />
											</div>
										</div>
										<div class="row mb-3">
											<label for="logo_site" class="col-sm-3 col-form-label">Logo Site</label>
											<div class="col-sm-9">
												<div class="input-group mb-3">
													<input type="file" class="form-control" id="logo_site" name="logo_site" accept="image/*">
												</div>
											</div>
										</div>
										<div class="row mb-3">
											<label for="database_name" class="col-sm-3 col-form-label">Nama Database</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="database_name" name="database_name" value="<?= $regional->database_name ?>" required />
											</div>
										</div>
										<div class="row mb-3">
											<label for="database_host" class="col-sm-3 col-form-label">Host Database</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="database_host" name="database_host" value="<?= $regional->database_host ?>" required />
											</div>
										</div>
										<div class="row mb-3">
											<label for="database_port" class="col-sm-3 col-form-label">Port Database</label>
											<div class="col-sm-9">
												<input type="number" class="form-control" id="database_port" name="database_port" value="<?= $regional->database_port ?>" required />
											</div>
										</div>
										<div class="row mb-3">
											<label for="database_username" class="col-sm-3 col-form-label">Username Database</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="database_username" name="database_username" value="<?= $regional->database_username ?>" required />
											</div>
										</div>
										<div class="row mb-3">
											<label for="database_password" class="col-sm-3 col-form-label">Password Database</label>
											<div class="col-sm-9">
												<input type="password" class="form-control" id="database_password" name="database_password" required />
											</div>
										</div>
										<button type="button" id="test_koneksi" class="btn btn-warning mb-3">Test Connection</button>
										<button type="submit" class="btn btn-primary mb-3">Submit</button>
										<a href="<?= base_url('Region') ?>" type="button" class="btn btn-danger mb-3">Cancel</a>
									</form>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</section>
	<?php $this->load->view('include/footer.php'); ?>

	<!-- Toast notifications -->
	<div style="position: absolute; top: 20px; right: 20px;">
		<div class="toast toast1 toast-bootstrap" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header">
				<i class="fa fa-newspaper-o"> </i>
				<strong class="mr-auto m-l-sm">Notification</strong>
				<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="toast-body" id="keterangan">

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
	<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
	<script>
		$('#test_koneksi').click(function() {
			var loading_progress = $('#loading_progress');
			var keterangan = $('#keterangan');
			var database_name = $('#database_name').val();
			var database_host = $('#database_host').val();
			var database_port = $('#database_port').val();
			var database_username = $('#database_username').val();
			var database_password = $('#database_password').val();

			$.ajax({
				url: "<?php echo base_url('Koneksi'); ?>",
				method: "POST",
				data: {
					database_name: database_name,
					database_host: database_host,
					database_port: database_port,
					database_username: database_username,
					database_password: database_password
				},
				beforeSend: function() {
					document.getElementById("test_koneksi").disabled = true;
					loading_progress.addClass('is-active');
				},
				success: function(data) {
					var json = data,
						obj = JSON.parse(json);

					document.getElementById("test_koneksi").disabled = false;

					loading_progress.removeClass('is-active');

					alert(obj.err_desc);
					// if(obj.rc!=='00'){
					// 	toast1.toast('show');
					// 	keterangan.html(obj.err_desc);
					// }else{
					// 	data_tagihan.html(obj.err_desc);
					// }
				}
			});
		});
	</script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form_elements.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:42:43 GMT -->

</html>