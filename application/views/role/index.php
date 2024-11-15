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
		<link rel="icon" href="https://berrydashboard.io/bootstrap/default/assets/images/favicon.svg" type="image/x-icon" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" id="main-font-link" />
		<link rel="stylesheet" href="<?= base_url()?>assets/fonts/tabler-icons.min.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/fonts/feather.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/fonts/fontawesome.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/fonts/material.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/style.css" id="main-style-link" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/style-preset.css" id="preset-style-link" />
		
		
		<style>
			.container {
			max-width: 800px;
			width: 100%;
			margin: 0 auto;
			font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
			}
		</style>
		<link rel="stylesheet" href="<?= base_url()?>assets/loader/css-loader.css">

	</head>
	<body onload="load_data('1')">
		<div class="loader loader-default" id="loading_progress"></div>
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
									<li class="breadcrumb-item" aria-current="page">Role management</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
					<form action="<?= base_url('Role/edit_proses')?>" method="post" enctype="multipart/form-data">
						<div class="card">
							<div class="card-header">
								<h5>Role Management</h5>
								<div class="row">
									<div class="form-group col-md-6">
										
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="username">Role Name:</label>
										<select class="form-control" name="ms_roles_id" id="ms_roles_id" onchange="load_data(this.value)">
											<?php foreach($role as $role){?>
											<option value="<?= $role->id ?>"><?= $role->role_name ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								
							</div>
							<div class="card-body" id="data_akses">

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
		<script src="<?= base_url()?>assets/js/plugins/choices.min.js"></script>
		<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
		<script>
			function load_data(role_id) {
				var loading_progress = $('#loading_progress');
				var data_akses = $('#data_akses');
				$.ajax({
					url : "<?php echo base_url('Role/detail');?>",
					method : "POST",
					data : {ms_roles_id: role_id},
					beforeSend:function(){
						loading_progress.addClass('is-active');
					},
					success: function(data){
						var json = data,
						obj = JSON.parse(json);
						loading_progress.removeClass('is-active');
						data_akses.html(obj.tabel);
					}
				});
			};

		</script>
	</body>
	<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_choices.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:42:46 GMT -->
</html>