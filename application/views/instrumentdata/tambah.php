<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/elements/bc_toasts.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:42:27 GMT -->

<head>
	<title>Instrument</title>
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

</head>

<body>
	<div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
	</div>
	<?php $this->load->view('include/header.php'); ?>
	<?php $this->load->view('include/sidebar.php'); ?>
	<div class="pc-container">
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
									<a href="<?= base_url('InstrumentData') ?>">Instrument</a>
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
							<h5>Tambah Instrument</h5>
						</div>

						<div class="card-body">

							<form action="<?= base_url('InstrumentData/tambah_proses') ?>" method="post">
								<label class="form-label" for="name"><b>Informasi Umum</b></label>
								<hr>
								<div class="row">
									<div class="form-group col-md-4">
										<label class="form-label" for="ms_regions_id">Site</label>
										<select class="form-control" name="ms_regions_id" id="ms_regions_id">
											<option>--Pilih Site--</option>
											<?php foreach ($region as $rg) { ?>
												<option value="<?= $rg->id ?>"><?= $rg->site_name ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label class="form-label" for="kode_instrument">Kode Instrument</label>
										<input type="text" class="form-control" id="kode_instrument" name="kode_instrument" required>
									</div>
									<div class="form-group col-md-4">
										<label class="form-label" for="nama_instrument">Nama Instrument</label>
										<input type="text" class="form-control" id="nama_instrument" name="nama_instrument" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-4">
										<label class="form-label" for="tr_instrument_type_id">Type Instrument</label>
										<select class="form-control" name="tr_instrument_type_id" id="tr_instrument_type_id">
											<option>--Pilih Type Instrument--</option>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label class="form-label" for="ms_stasiun_id">Station</label>
										<select class="form-control" name="ms_stasiun_id" id="ms_stasiun_id">
											<option>--Pilih Station--</option>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label class="form-label" for="tahun_pembuatan">Tahun Pembuatan</label>
										<input type="text" class="form-control" id="tahun_pembuatan" name="tahun_pembuatan" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-4">
										<label class="form-label" for="longitude">Longitude</label>
										<input type="text" class="form-control" id="longitude" name="longitude" placeholder="Masukan Longitude atau Koordinate X">
									</div>
									<div class="form-group col-md-4">
										<label class="form-label" for="latitude">Latitude</label>
										<input type="text" class="form-control" id="latitude" name="latitude" placeholder="Masukan Longitude atau Koordinate Y">
									</div>
									<div class="col-md-4" id="convertKoor">

									</div>
								</div>

								<label class="form-label" for="name"><b>Informasi Sensor</b></label>
								<hr>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="nama_sensor">Nama Sensor</label>
										<input type="text" class="form-control" id="nama_sensor" name="nama_sensor" required>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="serial_number">Serial Number</label>
										<input type="text" class="form-control" id="serial_number" name="serial_number" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label class="form-label" for="range">Range</label>
										<input type="text" class="form-control" id="range" name="range" required>
									</div>
									<div class="form-group col-md-6">
										<label class="form-label" for="type">Output</label>
										<input type="text" class="form-control" id="output" name="output" required>
									</div>
								</div>


								<div id="form_vwp" style="display: none;">
									<label class="form-label" for="name"><b>Informasi Instalasi</b></label>
									<hr>
									<div class="row">
										<div class="form-group col-md-6">
											<label class="form-label" for="zona_pemasangan">Zona Pemasangan</label>
											<select class="form-control" name="zona_pemasangan" id="zona_pemasangan">
												<option value="Pondasi">Pondasi</option>
												<option value="Timbunan">Timbunan</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label class="form-label" for="tanggal_rekalibrasi">Tanggal Rekalibrasi</label>
											<input type="date" class="form-control" id="tanggal_rekalibrasi" name="tanggal_rekalibrasi">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label class="form-label" for="tanggal_instalasi">Tanggal Instalasi</label>
											<input type="date" class="form-control" id="tanggal_instalasi" name="tanggal_instalasi">
										</div>
										<div class="form-group col-md-6">
											<label class="form-label" for="tanggal_zero_reading">Tanggal Zero Reading</label>
											<input type="date" class="form-control" id="tanggal_zero_reading" name="tanggal_zero_reading">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label class="form-label" for="elevasi_puncak">Elevasi Puncak</label>
											<input type="number" step="0.00001" class="form-control" id="elevasi_puncak" name="elevasi_puncak">
										</div>
										<div class="form-group col-md-6">
											<label class="form-label" for="elevasi_permukaan_saat_ini">Elevasi Permukaan Saat Ini</label>
											<input type="number" step="0.00001" class="form-control" id="elevasi_permukaan_saat_ini" name="elevasi_permukaan_saat_ini">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label class="form-label" for="elevasi_sensor">Elevasi Sensor</label>
											<input type="number" step="0.00001" class="form-control" id="elevasi_sensor" name="elevasi_sensor">
										</div>
										<div class="form-group col-md-6">
											<label class="form-label" for="elevasi_ground_water_level">Elevasi Ground Water Level</label>
											<input type="number" step="0.00001" class="form-control" id="elevasi_ground_water_level" name="elevasi_ground_water_level">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label class="form-label" for="kedalaman_sensor">Kedalaman Sensor</label>
											<input type="number" step="0.00001" class="form-control" id="kedalaman_sensor" name="kedalaman_sensor">
										</div>
									</div>

								</div>

								<div id="form_kalibrasi">
									<label class="form-label" for="name"><b>Informasi Kalibrasi</b></label>
									<hr>
									<div class="row">
										<div class="form-group col-md-12">
											<button type="button" class="btn btn-outline-info d-inline-flex" data-bs-toggle="modal" style="float: right;" onclick="tambahTempKoefisien()">
												<i class="ti ti-info-circle me-1"></i>Tambah Koefisien
											</button>
										</div>
									</div>


									<div id="data_tagihan"></div>
								</div>

								<hr>
								<button type="submit" class="btn btn-primary">Submit</button>
								<button type="button" class="btn btn-danger" onclick="history.back()">Cancel</button>
						</div>

						</form>
					</div>
				</div>
			</div>

			<div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
				<div id="liveToast" class="toast text-white bg-danger fade hide" role="alert" aria-live="assertive" aria-atomic="true">
					<div class="d-flex" bis_skin_checked="1">
						<div class="toast-body" bis_skin_checked="1" id="isiAlert"> Hello, world! This is a toast message. </div>
						<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="judul">Tambah Koefisien</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="formProduk" method="POST" enctype='multipart/form-data'>
					<div id="formBodyCreateOriginal">
						<div class="modal-body" id="formBodyCreate">
							<input class="form-control" name="modal_type" id="modal_type_create" hidden>
							<!-- <div id="modal_vwp_create" style="display: none;">
								<div class="row">
									<div class="form-group col-md-12">
										<label class="form-label" for="modal_sensor_create">Jenis Sensor</label>
										<select class="form-control" name="modal_sensor[]" id="modal_sensor_create" placeholder="This is a placeholder" multiple>

											<?php foreach ($sensor as $sns1) { ?>
												<option value="<?= $sns1->id ?>"><?= $sns1->nama_sensor ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div> -->
							<div id="modal_nonvwp_create" style="display: none;">
								<div class="row">
									<div class="form-group col-md-12">
										<label class="form-label" for="modal_data_mentah_create">Jenis Sensor Data Mentah</label>
										<select class="form-control" name="modal_data_mentah[]" id="modal_data_mentah_create" placeholder="This is a placeholder" multiple>

											<?php foreach ($sensor as $sns2) { ?>
												<option value="<?= $sns2->id ?>"><?= $sns2->nama_sensor ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-12">
										<label class="form-label" for="modal_data_jadi_create">Jenis Sensor Data Jadi</label>
										<select class="form-control" name="modal_data_jadi[]" id="modal_data_jadi_create" placeholder="This is a placeholder" multiple>

											<?php foreach ($sensor as $sns3) { ?>
												<option value="<?= $sns3->id ?>"><?= $sns3->nama_sensor ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<label class="form-label"><b>Parameter</b></label>
							<hr>
							<div id="modal_parameter_create"></div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button class="btn btn-primary" id="btn_update">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- modal edit koefisien -->
	<div class="modal fade" id="exampleModalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="judul_edit">Edit Koefisien</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="formProdukEdit" method="POST" enctype='multipart/form-data'>
					<input type="text" class="form-control" id="idTemptEdit" name="idTemptEdit" hidden>
					<div class="modal-body">
						<input class="form-control" name="modal_type" id="modal_type_edit" hidden>
						<!-- <div class="row">
							<div class="form-group col-md-6">
								<label class="form-label" for="modal_type_edit">Instrument Type</label>
								<select class="form-control" name="modal_type" id="modal_type_edit" onchange="getNameType(this, 'edit');">
									<option value="" selected>--Pilih Instrument Type--</option>
									<?php foreach ($type as $tp) { ?>
										<option value="<?= $tp->id ?>"><?= $tp->name ?></option>
									<?php } ?>
								</select>
							</div>
						</div> -->
						<div id="modal_vwp_edit" style="display: none;">
							<div class="row">
								<div class="form-group col-md-12">
									<label class="form-label" for="modal_sensor_edit">Jenis Sensor</label>
									<select class="form-control" name="modal_sensor[]" id="modal_sensor_edit" placeholder="This is a placeholder" multiple>
									</select>
								</div>
							</div>
						</div>
						<div id="modal_nonvwp_edit" style="display: none;">
							<div class="row">
								<div class="form-group col-md-12">
									<label class="form-label" for="modal_data_mentah_edit">Jenis Sensor Data Mentah</label>
									<select class="form-control" name="modal_data_mentah[]" id="modal_data_mentah_edit" placeholder="This is a placeholder" multiple>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label class="form-label" for="modal_data_jadi_edit">Jenis Sensor Data Jadi</label>
									<select class="form-control" name="modal_data_jadi[]" id="modal_data_jadi_edit" placeholder="This is a placeholder" multiple>
									</select>
								</div>
							</div>
						</div>
						<label class="form-label"><b>Parameter</b></label>
						<hr>
						<div id="modal_parameter_edit"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary" id="btn_updateEdit">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>

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
	<script src="<?= base_url() ?>assets/js/plugins/choices.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.7.5/proj4.js"></script>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// var multipleCancelButton = new Choices('#modal_sensor_create', {
			// 	removeItemButton: true
			// });

			var multipleCancelButton = new Choices('#modal_data_mentah_create', {
				removeItemButton: true
			});

			var multipleCancelButton = new Choices('#modal_data_jadi_create', {
				removeItemButton: true
			});
		});
	</script>
	<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
	<script>
		// var choices = new Choices('#modal_sensor_edit', {
		// 	removeItemButton: true,
		// 	uniqueItemText: true
		// });

		var choicesJadi = new Choices('#modal_data_jadi_edit', {
			removeItemButton: true,
			uniqueItemText: true
		});

		var choicesMentah = new Choices('#modal_data_mentah_edit', {
			removeItemButton: true,
			uniqueItemText: true
		});

		//tambah koefisien
		function tambahTempKoefisien() {
			var instrument_type_create = document.getElementById('tr_instrument_type_id');
			getNameType(instrument_type_create, 'create');
			document.getElementById('modal_type_create').value = instrument_type_create.value;
			$('#exampleModal').modal('show');
		};

		var originalModal = $('#formBodyCreate').clone();
		$('#exampleModal').on('hidden.bs.modal', function(e) {
			$('#formBodyCreate').remove();
			var myClone = originalModal.clone();
			$('#formBodyCreateOriginal').append(myClone);

			// var multipleCancelButton = new Choices('#modal_sensor_create', {
			// 	removeItemButton: true
			// });

			var multipleCancelButton = new Choices('#modal_data_mentah_create', {
				removeItemButton: true
			});

			var multipleCancelButton = new Choices('#modal_data_jadi_create', {
				removeItemButton: true
			});
		});

		$("#ms_regions_id").change(function() {
			var kode = $(this).val();

			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var type = JSON.parse(xhttp.responseText).type;
					var html = "<option>--Pilih Type Instrument--</option>";
					var tr_instrument_type_id = document.getElementById('tr_instrument_type_id');

					if (type.length > 0) {
						for (i = 0; i < type.length; i++) {
							html += '<option value=' + type[i].id + '>' + type[i].name + '</option>';
						}
					} else {
						html = '<option>--Tidak Ada Data--</option>';
					}

					tr_instrument_type_id.innerHTML = html;

					//station
					var station = JSON.parse(xhttp.responseText).station;
					var html_station = "<option>--Pilih Station--</option>";
					var ms_stasiun_id = document.getElementById('ms_stasiun_id');
					if (station.length > 0) {
						for (x = 0; x < station.length; x++) {
							html_station += '<option value=' + station[x].id + '>' + station[x].nama_stasiun + '</option>';
						}
					} else {
						html_station = '<option>--Tidak Ada Data--</option>';
					}

					ms_stasiun_id.innerHTML = html_station;



				}
			};
			xhttp.open("GET", "<?php echo base_url('InstrumentData/getTypeStation'); ?>" + '?kode=' + kode, true);
			xhttp.send();
		});

		$("#tr_instrument_type_id").change(function() {

			if ($('#latitude').prop('readonly') === false) {
				document.getElementById('latitude').value = "";
				document.getElementById('longitude').value = "";
			}
			var idtype = $(this).val();

			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var nama_type = JSON.parse(xhttp.responseText).type;

					if (nama_type == 'VWP') {
						$('#latitude').prop('readonly', false);
						$('#longitude').prop('readonly', false);
						$("#form_vwp").show();
						$("#convertKoor").html('<button type="button" id="convertButton" class="btn btn-sm btn-primary" style="margin-top: 35px;">Convert Coordinates</button>');
					} else {
						$('#latitude').prop('readonly', true);
						$('#longitude').prop('readonly', true);
						$("#form_vwp").hide();
						$("#convertKoor").empty();
					}
				}
			};
			xhttp.open("GET", "<?php echo base_url('InstrumentData/getNameType'); ?>" + '?kode=' + idtype, true);
			xhttp.send();
		});

		$(document).on("click", "#convertButton", function() {
			// Ambil nilai Easting dan Northing dari input
			var easting = parseFloat($("#longitude").val());
			var northing = parseFloat($("#latitude").val());

			var lonlat = convertUTMToLongLat(easting, northing, 51);
			// Tampilkan hasil konversi pada input longitude dan latitude
			$("#longitude").val(lonlat[0]);
			$("#latitude").val(lonlat[1]);
		});

		function convertUTMToLongLat(utmX, utmY, utmZone) {
			// Definisikan proyeksi UTM dan WGS84 (longlat)
			var utmProjection = '+proj=utm +zone=' + utmZone + ' +ellps=WGS84 +datum=WGS84 +units=m +no_defs';
			var longLatProjection = '+proj=longlat +ellps=WGS84 +datum=WGS84 +no_defs';

			// Transformasikan koordinat UTM ke Longlat
			var utmCoords = proj4(utmProjection, longLatProjection, [utmX, utmY]);

			return utmCoords;
		}

		$("#ms_stasiun_id").change(function() {
			document.getElementById('latitude').value = "";
			document.getElementById('longitude').value = "";
			var kode = $(this).val();
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {

					if ($('#latitude').prop('readonly') === true) {

						document.getElementById('latitude').value = JSON.parse(xhttp.responseText).latitude;
						document.getElementById('longitude').value = JSON.parse(xhttp.responseText).longitude;
					}

				}
			};
			xhttp.open("GET", "<?php echo base_url('InstrumentData/getPositionStation'); ?>" + '?kode=' + kode, true);
			xhttp.send();
		});


		function validateParameter(action) {
			var isValid = true;
			$('.parameter-' + action).each(function() {
				if ($(this).val().trim() === '') {
					isValid = false;

					notiftoast("Please ensure all parameters are filled in.");
					return false;
				}
			});
			return isValid;
		}

		function validateJenisSensor(action) {
			if ($('#modal_vwp_' + action).is(':visible')) {
				if ($('#modal_sensor_' + action).val().length === 0) {
					notiftoast('Jenis sensor is required');


					return false;
				}
			} else if ($('#modal_nonvwp_' + action).is(':visible')) {
				if ($('#modal_data_mentah_' + action).val().length === 0) {
					notiftoast('Jenis sensor data mentah is required');
					return false;
				}
				if ($('#modal_data_jadi_' + action).val().length === 0) {
					notiftoast('Jenis sensor data jadi is required');
					return false;
				}
			}
			return true;
		}




		//simpan modal
		$("#btn_update").click(function(e) {
			e.preventDefault();

			var modal_type = $('#modal_type_create').val()

			if (!modal_type) {
				// alert('Instrument type is required');
				notiftoast('Instrument type is required');
				return
			}

			var isJenisSensorValid = validateJenisSensor('create');

			if (!isJenisSensorValid) {
				return;
			}

			var isParameterValid = validateParameter('create');

			if (!isParameterValid) {
				return;
			}


			var form = $('#formProduk')[0];
			console.log(form);
			var data = new FormData(form);
			$.ajax({
				url: '<?= base_url("InstrumentData/tempKoefisien") ?>',
				type: 'post',
				enctype: 'multipart/form-data',
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				success: function(data) {
					if (data == 'Sukses') {
						$('#exampleModal').modal('hide');
						$("#formProduk")[0].reset();
						getDataTempKoefisien();
					} else {
						alert(data);
					}

				}
			});
		});


		//edit modal
		$("#btn_updateEdit").click(function(e) {
			e.preventDefault();

			var action = 'edit';
			// var modal_type = $('#modal_type_create').val()
			var modal_type = $('#modal_type_' + action).val()

			// if (!modal_type) {
			// 	// alert('Instrument type is required');
			// 	notiftoast('Instrument type is required');
			// 	return
			// }

			var isJenisSensorValid = validateJenisSensor('edit');

			if (!isJenisSensorValid) {
				return;
			}

			var isParameterValid = validateParameter('edit');

			if (!isParameterValid) {
				return;
			}


			var form = $('#formProdukEdit')[0];
			console.log(form);
			var data = new FormData(form);
			$.ajax({
				url: '<?= base_url("InstrumentData/editTempKoefisien") ?>',
				type: 'post',
				enctype: 'multipart/form-data',
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				success: function(data) {
					if (data == 'Sukses') {
						$('#exampleModalEdit').modal('hide');
						$("#formProdukEdit")[0].reset();
						getDataTempKoefisien();
					} else {
						alert(data);
					}
				}
			});
		});


		//tampilkan data temp
		function getDataTempKoefisien() {
			var data_tagihan = $('#data_tagihan');
			$.ajax({
				url: "<?php echo base_url('InstrumentData/dataTempKoefisien'); ?>",
				method: "POST",
				data: {},
				beforeSend: function() {},
				success: function(data) {
					var json = data,
						obj = JSON.parse(json);

					data_tagihan.html(obj.tabel);
				}
			});
		};

		function hapusTempKoefisien(id) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					getDataTempKoefisien();
				}
			};
			xhttp.open("GET", "<?php echo base_url('InstrumentData/hapusTempKoefisien'); ?>" + '?id=' + id, true);
			xhttp.send();
		};


		//edit koefisien
		function editTempKoefisien(id) {
			// choices.removeActiveItems();
			// choices.setChoiceByValue("");

			choicesJadi.removeActiveItems();
			choicesJadi.setChoiceByValue("");

			choicesMentah.removeActiveItems();
			choicesMentah.setChoiceByValue("");

			document.getElementById('idTemptEdit').value = id;
			//cek data 
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var action = 'edit';
					var type = JSON.parse(xhttp.responseText).type;
					// var html_type = "<option>--Pilih Instrument Type--</option>";
					// var modal_type_edit = document.getElementById('modal_type_' + action);
					// if (type.length > 0) {
					// 	for (x = 0; x < type.length; x++) {
					// 		html_type += '<option value=' + type[x].id + ' ' + type[x].pilih + '>' + type[x].name + '</option>';
					// 	}
					// } else {
					// 	html_type = '<option>--Tidak Ada Data--</option>';
					// }
					// console.log(modal_type_edit);
					// modal_type_edit.innerHTML = html_type;

					//modal parameter
					var modal_parameter = document.getElementById('modal_parameter_' + action);
					var tabel_parameter = JSON.parse(xhttp.responseText).tabel_parameter;

					modal_parameter.innerHTML = tabel_parameter;



					var nama_type = JSON.parse(xhttp.responseText).temp_koefisien.type;
					// if (nama_type == 'VWP') {
					// 	choices.setChoices(JSON.parse(xhttp.responseText).nama_sensor, 'value', 'label', true);

					// 	$("#modal_vwp_" + action).show();
					// 	$("#modal_nonvwp_" + action).hide();
					// } else if (nama_type == 'NON_VWP') {
					choicesJadi.setChoices(JSON.parse(xhttp.responseText).jenis_sensor_jadi, 'value', 'label', true);

					choicesMentah.setChoices(JSON.parse(xhttp.responseText).jenis_sensor_mentah, 'value', 'label', true);

					// $("#modal_vwp_" + action).hide();
					$("#modal_nonvwp_" + action).show();
					// } else {
					// 	$("#modal_vwp_" + action).hide();
					// 	$("#modal_nonvwp_" + action).hide();
					// }

				}
			};
			xhttp.open("GET", "<?php echo base_url('InstrumentData/detailTempKoefisien'); ?>" + '?id=' + id, true);
			xhttp.send();
			$('#modal_type_edit').val($('#tr_instrument_type_id').val())
			$('#exampleModalEdit').modal('show');
		};
	</script>
	<script>
		function getNameType(sel, action) {
			var idtype = sel.value;

			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var nama_type = JSON.parse(xhttp.responseText).type;
					$("#modal_nonvwp_" + action).show();
					// if (nama_type == 'VWP') {
					// 	$("#modal_vwp_" + action).show();
					// 	$("#modal_nonvwp_" + action).hide();
					// } else if (nama_type == 'NON_VWP') {
					// 	$("#modal_vwp_" + action).hide();
					// 	$("#modal_nonvwp_" + action).show();
					// } else {
					// 	$("#modal_vwp_" + action).hide();
					// 	$("#modal_nonvwp_" + action).hide();
					// }
				}
			};
			xhttp.open("GET", "<?php echo base_url('InstrumentData/getNameType'); ?>" + '?kode=' + idtype, true);
			xhttp.send();

			//ambil data parameter
			var modal_parameter = $('#modal_parameter_' + action);
			$.ajax({
				url: "<?php echo base_url('InstrumentData/parameter'); ?>",
				method: "POST",
				data: {
					tr_instrument_type_id: idtype,
					action: action
				},
				beforeSend: function() {},
				success: function(data) {
					var json = data,
						obj = JSON.parse(json);

					modal_parameter.html(obj.tabel);
				}
			});
		}

		function notiftoast(isiNotif) {
			var f = document.getElementById('liveToast');
			var isiAlert = document.getElementById('isiAlert');
			isiAlert.innerHTML = isiNotif;
			var a = new bootstrap.Toast(f);
			a.show();
		}
	</script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/elements/bc_toasts.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:42:27 GMT -->

</html>