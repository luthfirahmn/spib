<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_flu-uppy.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:17 GMT -->

<head>
	<title>SPIB</title>
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

	<script src="http://demo.itsolutionstuff.com/plugin/jquery.js"></script>
	<link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/bootstrap-3.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>

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
									<a href="<?= base_url() ?>">Home</a>
								</li>
								<li class="breadcrumb-item">
									<a href="<?= base_url('Dokumen') ?>">Documentation</a>
								</li>
								<li class="breadcrumb-item" aria-current="page">edit</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header">
							<h5>Edit Dokumen</h5>
						</div>

						<div class="card-body">

							<div class="container">
								<div class="row">
									<div class="col-md-12">
										<form action="<?= base_url('Dokumen/imageUploadPost') ?>" enctype="multipart/form-data" class="dropzone" id="image-upload" method="POST">
										</form>
									</div>
								</div>
							</div>

							</br>
							<form action="<?= base_url('Dokumen/edit_proses') ?>" method="post" enctype="multipart/form-data">
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="title">Judul</label>
										<input type="text" class="form-control" id="title" name="title" value="<?= $dokumen->title ?>" required>
										<input type="text" class="form-control" id="id" name="id" value="<?= $dokumen->id ?>" hidden>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="ms_regions_id">Site</label>
										<select class="form-control" name="ms_regions_id" id="ms_regions_id">
											<?php foreach ($region as $reg) { ?>
												<option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="date">Tanggal</label>
										<input type="date" class="form-control" id="date" name="date" required value="<?= $dokumen->date ?>">
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="category_id">Kategori</label>
										<select class="form-control" name="category_id" id="category_id">
											<?php foreach ($kategori as $kat) { ?>
												<option value="<?= $kat->lookup_code ?>"><?= $kat->lookup_name ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-12">
										<label class="form-label" for="description">Deskripsi</label>
										<textarea class="form-control" id="description" name="description" rows="3"><?= $dokumen->description ?></textarea>
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
					<p class="m-0">Copyright &copy; <a href="#">SPIB</a>
					</p>
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
	<script type="text/javascript">
		Dropzone.options.imageUpload = {
			maxFilesize: 1
		};
	</script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_flu-uppy.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:18 GMT -->

</html>