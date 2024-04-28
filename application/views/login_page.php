<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/pages/login-v1.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:41:39 GMT -->

<head>
	<title>Login | Berry Bootstrap 5 Admin Template</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="Berry is made using Bootstrap 5 design framework. Download the free admin template & use it for your project." />
	<meta name="keywords" content="Berry, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template" />
	<meta name="author" content="CodedThemes" />
	<link rel="icon" href="https://berrydashboard.io/bootstrap/default/assets/images/favicon.svg" type="image/x-icon" />
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
	<div class="auth-main">
		<div class="auth-wrapper v1">
			<div class="auth-form">
				<div class="card mt-5">
					<div class="card-body">
						<div class="row">
							<div class="d-flex justify-content-between">
								<div>
									<h2 class="text-secondary">
										<b>Hi, Welcome Back</b>
									</h2>
									<h4>Login in to your account</h4>
								</div>
							</div>
						</div>
						<?php echo form_open('secure', array('id' => 'FormLogin')); ?>
						<div class="form-floating mb-3">
							<input type="text" class="form-control" name="username" id="username" placeholder="Username" />
							<label for="username">Username</label>
						</div>
						<div class="form-floating mb-3">
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" />
							<label for="password">Password</label>
						</div>
						<div class="d-flex mt-1 justify-content-between">
							<div class="form-check">
								<input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="" />
								<label class="form-check-label text-muted" for="customCheckc1">Remember me</label>
							</div>
						</div>
						<div class="d-grid mt-4">
							<button type="submit" class="btn btn-secondary">Sign In</button>
						</div>
						</br>
						<i class="float-end">Powered by SIMETRI</i>
						<?php echo form_close(); ?>
						<div id='ResponseInput'></div>
					</div>
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
	<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
	<script>
		$(function() {
			//------------------------Proses Login Ajax-------------------------//
			$('#FormLogin').submit(function(e) {
				e.preventDefault();
				$.ajax({
					url: $(this).attr('action'),
					type: "POST",
					cache: false,
					data: $(this).serialize(),
					dataType: 'json',
					success: function(json) {
						//response dari json_encode di controller

						if (json.status == 1) {
							window.location.href = json.url_home;
						}
						if (json.status == 0) {
							$('#ResponseInput').html(json.pesan);
						}
						if (json.status == 2) {
							$('#ResponseInput').html(json.pesan);
							$('#InputPassword').val('');
						}
					}
				});
			});

			//-----------------------Ketika Tombol Reset Diklik-----------------//
			$('#ResetData').click(function() {
				$('#ResponseInput').html('');
			});
		});
	</script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/pages/login-v1.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:41:39 GMT -->

</html>