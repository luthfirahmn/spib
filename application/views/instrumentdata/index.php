<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->

<head>
	<title>Instrument</title>
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
	<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css" />
	<style>
		/* Set table width and borders */
		#instrumentTable {
			border-collapse: collapse;
			width: 100%;
		}

		#instrumentTable th,
		#instrumentTable td {
			border-bottom: 1px solid #ddd;
			padding: 12px;
		}

		#instrumentTable th {
			background-color: #f2f2f2;
			color: #333;
			text-align: left;
		}

		/* Zebra striping */

		#instrumentTable tr:hover {
			background-color: #f1f1f1;
		}

		/* Pagination controls */
		.dataTables_paginate .paginate_button {
			padding: 5px 10px;
			margin: 2px;
			border: 1px solid #ddd;
			border-radius: 4px;
			background-color: #fff;
			color: #333;
		}

		.dataTables_paginate .paginate_button:hover {
			background-color: #ddd;
		}

		/* Search input */
		.dataTables_filter input {
			border: 1px solid #ddd;
			border-radius: 4px;
			padding: 5px;
		}
	</style>
	<style>
		#ModalEdit2 {
			position: fixed;
			top: 0;
			left: 0;
			background: rgba(0, 0, 0, .9);
			height: 100%;
			width: 100%;
		}

		#ModalBody {
			margin: 0 auto;
			/* position: absolute;
				top: 20%;
				left: 40%;
				height: 50%;
				width: 50%; */
		}
	</style>
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
								<h5 class="m-b-10">Instrument</h5>
							</div>
							<ul class="breadcrumb">
								<li class="breadcrumb-item">
									<a href="<?= base_url() ?>">Home</a>
								</li>
								<li class="breadcrumb-item" aria-current="page">Instrument</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-header">
							<h5>Data Instrument</h5>
						</div>
						<div class="card-body table-border-style">
							<div class="row  mb-3">
								<div class="col-md-9">
									<?php if ($hak_akses->insert == '1') { ?>
										<a href="<?= base_url('InstrumentData/tambah') ?>" type="button" class="btn btn-light-primary mb-3">
											<i class="ti ti-plus"></i>Add Instrument
										</a>
									<?php } ?>
								</div>
								<div class="col-md-3">
									<select class="form-control" name="ms_regions_id" id="ms_regions_id">
										<?php foreach ($region as $reg) { ?>
											<option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div id="list_data_filter" class="table-responsive">
								<table id="instrumentTable" class="display" style="width:100%">
									<thead>
										<tr>
											<th>Region Name</th>
											<th>Instrument Code</th>
											<th>Instrument Name</th>
											<th>Instrument Type</th>
											<th>Station</th>
											<th>Total Data</th>
											<th>Last Data</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<!-- Data will be populated by DataTables -->
									</tbody>
								</table>
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
	<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
	<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
	<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
	<script>
		function Createtable() {
			var ms_regions_id = $("#ms_regions_id").val();

			$('#instrumentTable').DataTable({
				"processing": true,
				"serverSide": true,
				"responsive": true,

				"ajax": {
					"url": "<?php echo base_url('InstrumentData/list'); ?>",
					"type": "POST",
					"data": function(d) {
						d.ms_regions_id = ms_regions_id;
					},
					"dataSrc": function(json) {
						return json.data;
					}
				},
				"columns": [{
						"data": "region_name"
					},
					{
						"data": "kode_instrument"
					},
					{
						"data": "nama_instrument"
					},
					{
						"data": "instrument_type"
					},
					{
						"data": "nama_stasiun"
					},
					{
						"data": "total_data"
					},
					{
						"data": "data_terakhir_masuk"
					},
					{
						"data": null,
						"defaultContent": "",
						"orderable": false,
						"render": function(data, type, row) {
							var editUrl = "<?php echo base_url('InstrumentData/edit?id='); ?>" + row.id;
							var deleteUrl = "<?php echo base_url('InstrumentData/hapus?id='); ?>" + row.id;

							var editBtn = '<?php if ($hak_akses->update == '1') { ?>' +
								'<a class="dropdown-item" href="' + editUrl + '"> <i class="ti ti-edit"></i> Edit</a>' +
								'<?php } ?>';

							var deleteBtn = '<?php if ($hak_akses->delete == '1') { ?>' +
								'<a class="dropdown-item" href="' + deleteUrl + '" onclick="return confirm(\'Are you sure?\')"> <i class="ti ti-trash"></i> Delete</a>' +
								'<?php } ?>';

							return '<div class="btn-group" role="group" aria-label="Button group with nested dropdown">' +
								'<div class="btn-group" role="group">' +
								'<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action </button>' +
								'<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">' +
								editBtn +
								deleteBtn +
								'</div>' +
								'</div>' +
								'</div>';
						}
					}
				],
				"paging": true,
				"searching": true,
				"ordering": true,
				"info": true
			});
		}

		$(document).ready(function() {
			Createtable();
		});

		$("#ms_regions_id").change(function() {
			var table = $('#instrumentTable').DataTable();
			table.destroy();
			Createtable();
		});
	</script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->

</html>