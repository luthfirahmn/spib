<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_choices.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:42:46 GMT -->

<head>
	<title>Site</title>
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

	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="<?= base_url() ?>assets/imageupload/style.css">
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
							<ul class="breadcrumb">
								<li class="breadcrumb-item">
									<a href="<?= base_url() ?>">Home</a>
								</li>
								<li class="breadcrumb-item" aria-current="page">Site</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header">
							<h5>Konfigurasi Data Bendungan</h5>
						</div>
						<div class="card-body">
							<form action="<?= base_url('Site/edit_proses') ?>" method="post" enctype="multipart/form-data">
								<div class="row">
									<div class="form-group col-md-12">
										<label class="form-label" for="site_name">Nama</label>
										<input type="text" class="form-control" id="site_name" name="site_name" value="<?= $site->site_name ?>" required>
										<input type="text" class="form-control" id="idsite" name="idsite" value="<?= $site->id ?>" hidden>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="provinsi">Provinsi</label>
										<select class="form-control" name="provinsi" id="provinsi">
											<option>--- Pilih Provinsi ---</option>
											<?php foreach ($provinsi as $prov) { ?>
												<option value="<?= $prov->kode ?>" <?= ($prov->kode == $site->provinsi) ? 'selected' : ''; ?>><?= $prov->nama ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="kota">Kabupaten/Kota</label>
										<select class="form-control" name="kota" id="kota">
											<option>--- Pilih Kabupaten/Kota ---</option>
											<?php foreach ($kota as $kota) { ?>
												<option value="<?= $kota->kode ?>" <?= ($kota->kode == $site->kabupaten) ? 'selected' : ''; ?>><?= $kota->nama ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="kecamatan">Kecamatan</label>
										<select class="form-control" name="kecamatan" id="kecamatan">
											<option>--- Pilih Kecamatan ---</option>
											<?php foreach ($kecamatan as $kec) { ?>
												<option value="<?= $kec->kode ?>" <?= ($kec->kode == $site->kecamatan) ? 'selected' : ''; ?>><?= $kec->nama ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="kelurahan">Desa/Kelurahan</label>
										<select class="form-control" name="kelurahan" id="kelurahan">
											<option>--- Pilih Desa/Kelurahan ---</option>
											<?php foreach ($kelurahan as $kel) { ?>
												<option value="<?= $kel->kode ?>" <?= ($kel->kode == $site->desa) ? 'selected' : ''; ?>><?= $kel->nama ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="elev_tanggul_utama">Elevasi Tanggul Utama</label>
										<input type="number" step=".001" class="form-control" id="elev_tanggul_utama" name="elev_tanggul_utama" value="<?= $site->elev_tanggul_utama ?>" required>
									</div>
									<div class="form-group col-md-6">
										<label for="elev_tanggul_pembantu" class="form-label">Elevasi Tanggul Pembantu</label>
										<input type="number" step=".001" class="form-control" id="elev_tanggul_pembantu" name="elev_tanggul_pembantu" value="<?= $site->elev_tanggul_pembantu ?>" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="elev_pelimpah">Elevasi Pelimpah</label>
										<input type="number" step=".001" class="form-control" id="elev_pelimpah" name="elev_pelimpah" value="<?= $site->elev_pelimpah ?>" required>
									</div>
									<div class="form-group col-md-6">
										<label for="elev_pelimpah_pembantu" class="form-label">Elevasi Pelimpah Pembantu</label>
										<input type="number" step=".001" class="form-control" id="elev_pelimpah_pembantu" name="elev_pelimpah_pembantu" value="<?= $site->elev_pelimpah_pembantu ?>" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="elev_normal">Elevasi Normal</label>
										<input type="number" step=".001" class="form-control" id="elev_normal" name="elev_normal" value="<?= $site->elev_normal ?>" required>
									</div>
									<div class="form-group col-md-6">
										<label for="elev_siaga1" class="form-label">Elevasi Siaga 1</label>
										<input type="number" step=".001" class="form-control" id="elev_siaga1" name="elev_siaga1" value="<?= $site->elev_siaga1 ?>" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="elev_siaga2">Elevasi Siaga 2</label>
										<input type="number" step=".001" class="form-control" id="elev_siaga2" name="elev_siaga2" value="<?= $site->elev_siaga2 ?>" required>
									</div>
									<div class="form-group col-md-6">
										<label for="elev_siaga3" class="form-label">Elevasi Siaga 3</label>
										<input type="number" step=".001" class="form-control" id="elev_siaga3" name="elev_siaga3" value="<?= $site->elev_siaga3 ?>" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="batas_kritis_vwp">Batas kritis VWP</label>
										<input type="number" step=".001" class="form-control" id="batas_kritis_vwp" name="batas_kritis_vwp" value="<?= $site->batas_kritis_vwp ?>" required>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="file">Foto Bendungan/Layout</label>
										<input type="file" id="file" name="file" accept="image/*" hidden>
										<div class="img-area" data-img="">
											<i class='bx bxs-cloud-upload icon'></i>
											<h3>Upload Foto Bendungan/Layout</h3>
											<p>Image size must be less than <span>2MB</span></p>
										</div>
										<button type="button" class="select-image">Select Foto Bendungan/Layout</button>
									</div>
								</div>

								<a href="<?= base_url('Site')?>" type="button" class="btn btn-danger">Cancel</a>
								<button type="submit" class="btn btn-primary">Submit</button>
							</form>
						</div>
					</div>




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
	<script src="<?= base_url() ?>assets/imageupload/script.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/popper.min.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/simplebar.min.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/bootstrap.min.js"></script>
	<script src="<?= base_url() ?>assets/js/config.js"></script>
	<script src="<?= base_url() ?>assets/js/pcoded.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/feather.min.js"></script>
	<script src="<?= base_url() ?>assets/js/customizer.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/choices.min.js"></script>
	<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
				removeItemButton: true
			});
		});
	</script>

	<script>
		$("#provinsi").change(function() {
			var kode = $(this).val();

			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var result = JSON.parse(xhttp.responseText).result;
					var html = "<option>--- Pilih Kabupaten/Kota ---</option>";
					var kota = document.getElementById('kota');

					if (result.length > 0) {
						for (i = 0; i < result.length; i++) {
							html += '<option value=' + result[i].kode + '>' + result[i].nama + '</option>';
						}
					} else {
						html = '<option>--Tidak Ada Data--</option>';
					}

					kota.innerHTML = html;
				}
			};
			xhttp.open("GET", "<?php echo base_url('Site/kota'); ?>" + '?kode=' + kode, true);
			xhttp.send();
		});

		$("#kota").change(function() {
			var kode = $(this).val();

			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var result = JSON.parse(xhttp.responseText).result;
					var html = "<option>--Pilih Kecamatan--</option>";
					var kecamatan = document.getElementById('kecamatan');

					if (result.length > 0) {
						for (i = 0; i < result.length; i++) {
							html += '<option value=' + result[i].kode + '>' + result[i].nama + '</option>';
						}
					} else {
						html = '<option>--Tidak Ada Data--</option>';
					}

					kecamatan.innerHTML = html;
				}
			};
			xhttp.open("GET", "<?php echo base_url('Site/kecamatan'); ?>" + '?kode=' + kode, true);
			xhttp.send();
		});

		$("#kecamatan").change(function() {
			var kode = $(this).val();
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var result = JSON.parse(xhttp.responseText).result;
					var html = "<option>--Pilih Desa/Kelurahan--</option>";
					var kelurahan = document.getElementById('kelurahan');

					if (result.length > 0) {
						for (i = 0; i < result.length; i++) {
							html += '<option value=' + result[i].kode + '>' + result[i].nama + '</option>';
						}
					} else {
						html = '<option>--Tidak Ada Data--</option>';
					}

					kelurahan.innerHTML = html;
				}
			};
			xhttp.open("GET", "<?php echo base_url('Site/kelurahan'); ?>" + '?kode=' + kode, true);
			xhttp.send();
		});
	</script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_choices.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:42:46 GMT -->

</html>