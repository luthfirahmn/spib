<!DOCTYPE html>
<html lang="en">
	<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_flu-uppy.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:17 GMT -->
	<head>
		<title>Station</title>
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

		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
		<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

		<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
		<link rel="stylesheet" href="<?= base_url()?>assets/imageupload/style.css">
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
										<a href="<?= base_url('Station')?>">Station</a>
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
								<h5>Edit Station</h5>
							</div>
							
							<div class="card-body">
							<form action="<?= base_url('Station/edit_proses')?>" method="post" enctype="multipart/form-data">
								<div class="row">
									<div id="mapid" style="width: 100%; height: 400px;"></div>
								</div>

								</br>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="nama_stasiun">Nama Station</label>
										<input type="text" class="form-control" id="nama_stasiun" name="nama_stasiun" value="<?= $station->nama_stasiun?>" required>
										<input type="text" class="form-control" id="id" name="id" value="<?= $station->id?>"  hidden>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="ms_regions_id">Site</label>
										<select class="form-control" name="ms_regions_id" id="ms_regions_id">
											<?php foreach($region as $reg){?>
											<option value="<?= $reg->id ?>"  <?=($reg->id == $station->ms_regions_id) ? 'selected' :'';?>><?= $reg->site_name ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="wilayah_sungai">Wilayah Sungai</label>
										<input type="text" class="form-control" id="wilayah_sungai" name="wilayah_sungai" value="<?= $station->wilayah_sungai?>" required>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="daerah_aliran_sungai">Daerah Aliran Sungai</label>
										<input type="text" class="form-control" id="daerah_aliran_sungai" name="daerah_aliran_sungai" value="<?= $station->daerah_aliran_sungai?>" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="latitude">Latitude</label>
										<input type="text" class="form-control" id="latitude" name="latitude" value="<?= $station->latitude?>" required>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="longitude">Longitude</label>
										<input type="text" class="form-control" id="longitude" name="longitude" value="<?= $station->longitude?>" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="komunikasi">Komunikasi</label>
										<input type="text" class="form-control" id="komunikasi" name="komunikasi" value="<?= $station->komunikasi?>" required>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="kontak_gsm">Kontak Gsm</label>
										<input type="text" class="form-control" id="kontak_gsm" name="kontak_gsm" value="<?= $station->kontak_gsm?>" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="alamat_ip">Alamat IP</label>
										<input type="text" class="form-control" id="alamat_ip" name="alamat_ip" value="<?= $station->alamat_ip?>" required>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="tahun_pembuatan">Tahun Pembuatan</label>
										<input type="text" class="form-control" id="tahun_pembuatan" name="tahun_pembuatan" value="<?= $station->tahun_pembuatan?>" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="elevasi">Elevasi</label>
										<input type="number" step=".001" class="form-control" id="elevasi" name="elevasi" value="<?= $station->elevasi?>" required>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="file">Foto</label>
										<input type="file" id="file" name="foto" accept="image/*" hidden>
										<div class="img-area" data-img="">
											<i class='bx bxs-cloud-upload icon'></i>
											<h3>Upload Foto</h3>
											<p>Foto size must be less than <span>2MB</span></p>
											<img src="<?= base_url('assets/upload/station/'.$station->foto)?>">
										</div>
										<button type="button" class="select-image">Select Foto</button>
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
		<script src="<?= base_url()?>assets/imageupload/script.js"></script>
		<script src="<?= base_url()?>assets/js/plugins/popper.min.js"></script>
		<script src="<?= base_url()?>assets/js/plugins/simplebar.min.js"></script>
		<script src="<?= base_url()?>assets/js/plugins/bootstrap.min.js"></script>
		<script src="<?= base_url()?>assets/js/config.js"></script>
		<script src="<?= base_url()?>assets/js/pcoded.js"></script>
		<script src="<?= base_url()?>assets/js/plugins/feather.min.js"></script>
		
		<script src="<?= base_url()?>assets/js/customizer.js"></script>
		<script type="text/javascript">
			var mymap = L.map('mapid').setView([<?= $station->latitude?>, <?= $station->longitude?>], 13);
			L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
				attribution: '© OpenStreetMap contributors'
			}).addTo(mymap);
			var marker = L.marker([<?= $station->latitude?>, <?= $station->longitude?>]).addTo(mymap);
			mymap.on('click', function(e) {
				if (marker != null) {
					mymap.removeLayer(marker);
				}
				marker = new L.marker(e.latlng).bindPopup('Hi There!').addTo(mymap);
				$('#latitude').val(e.latlng['lat'].toFixed(6));
				$('#longitude').val(e.latlng['lng'].toFixed(6));
			});
		</script>
	</body>
	<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_flu-uppy.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:18 GMT -->
</html>