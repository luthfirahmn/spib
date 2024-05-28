<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_choices.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:42:46 GMT -->

<head>
	<title>Choices | Berry Bootstrap 5 Admin Template</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="Berry is made using Bootstrap 5 design framework. Download the free admin template & use it for your project." />
	<meta name="keywords" content="Berry, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template" />
	<meta name="author" content="CodedThemes" />
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
									<a href="<?= base_url('User') ?>">Region Management</a>
								</li>
								<li class="breadcrumb-item" aria-current="page">tambah</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header">
							<h5>Tambah User</h5>
						</div>
						<div class="card-body">

							<form id="formProduk" method="POST" enctype='multipart/form-data'>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="nama">Nama</label>
										<input type="text" class="form-control" id="nama" name="nama" required>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="username">User Name</label>
										<input type="text" class="form-control" id="username" name="username" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="email">Email</label>
										<input type="email" class="form-control" id="email" name="email" required>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="password">Password</label>
										<input type="password" class="form-control" id="password" name="password" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="dinas">Dinas</label>
										<input type="text" class="form-control" id="dinas" name="dinas" required>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="jabatan">Jabatan</label>
										<input type="text" class="form-control" id="jabatan" name="jabatan" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="no_telp">No. Hp</label>
										<input type="number" class="form-control" id="no_telp" name="no_telp" required>
									</div>
									<div class="form-group col-md-6">
										<label for="foto" class="form-label">Foto</label>
										<div class="input-group">
											<input type="file" class="form-control" id="foto" name="foto" accept="image/*">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="ms_roles_id">Role</label>
										<select class="form-control" name="ms_roles_id" id="ms_roles_id">
											<?php foreach ($role as $role) { ?>
												<option value="<?= $role->id ?>"><?= $role->role_name ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="ms_regions_id">Region</label>
										<select class="form-control" name="ms_regions_id[]" id="choices-multiple-remove-button" placeholder="This is a placeholder" multiple>
											<?php foreach ($region as $rg) { ?>
												<option value="<?= $rg->id ?>"><?= $rg->site_name ?></option>
											<?php } ?>
										</select>
									</div>
								</div>

								<button type="submit" class="btn btn-primary">Submit</button>
							</form>
						</div>
					</div>




				</div>
			</div>
		</div>
	</section>

	<div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
		<div id="liveToast" class="toast text-white bg-danger fade hide" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="d-flex" bis_skin_checked="1">
				<div class="toast-body" bis_skin_checked="1" id="isiAlert"> Hello, world! This is a toast message. </div>
				<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>
	</div>


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
	<script src="<?= base_url() ?>assets/js/plugins/popper.min.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/simplebar.min.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/bootstrap.min.js"></script>
	<script src="<?= base_url() ?>assets/js/config.js"></script>
	<script src="<?= base_url() ?>assets/js/pcoded.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/feather.min.js"></script>

	<script src="<?= base_url() ?>assets/js/customizer.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/choices.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
				removeItemButton: true
			});
		});
	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
		$("form").submit(function(e) {
			e.preventDefault();
			var loading_progress = $('#loading_progress');
			var form = $('#formProduk')[0];
			var data = new FormData(form);
			$.ajax({
				url: '<?= base_url("User/tambah_proses") ?>',
				type: 'post',
				enctype: 'multipart/form-data',
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				beforeSend: function() {
					loading_progress.addClass('is-active');
				},
				success: function(data) {
					loading_progress.removeClass('is-active');
					if (data == 'Sukses') {
						window.location = "<?= base_url('User') ?>";
					} else {
						notiftoast(data);
					}
				}
			});
		});

		function notiftoast(isiNotif) {
			var f = document.getElementById('liveToast');
			var isiAlert = document.getElementById('isiAlert');
			isiAlert.innerHTML = isiNotif;
			var a = new bootstrap.Toast(f);
			a.show();
		}
	</script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_choices.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:42:46 GMT -->

</html>