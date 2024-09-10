<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->

<head>
	<title>User</title>
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
								<h5 class="m-b-10">User Management</h5>
							</div>
							<ul class="breadcrumb">
								<li class="breadcrumb-item">
									<a href="<?= base_url() ?>">Home</a>
								</li>
								<li class="breadcrumb-item" aria-current="page">User Management</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-header">
							<h5>Data User</h5>
						</div>
						<div class="card-body table-border-style">
							<?php if ($hak_akses->insert == '1') { ?>
								<a href="<?= base_url('User/tambah') ?>" type="button" class="btn btn-light-primary mb-3">
									<i class="ti ti-plus"></i>Add User
								</a>
							<?php } ?>
							<div class="table-responsive">
								<table class="table" id="pc-dt-simple">
									<thead>
										<tr>
											<th>Nama</th>
											<th>Username</th>
											<th>Position</th>
											<th>Role</th>
											<th>Region</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($user as $rec) { ?>
											<tr>
												<td><?= $rec->nama ?></td>
												<td><?= $rec->username ?></td>
												<td><?= $rec->jabatan ?></td>
												<td><?= $rec->role_name ?></td>
												<?php
												$regionName = $rec->region_name;

												$formattedRegionName = str_replace(',', ',<br>', $regionName);

												$formattedRegionName = rtrim($formattedRegionName, ',') . '<br>';
												?>

												<td><?= $formattedRegionName ?></td>
												<td>
													<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
														<div class="btn-group" role="group">
															<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action </button>
															<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
																<?php if ($hak_akses->update == '1') { ?>
																	<a class="dropdown-item" href="<?= base_url('User/edit?id=' . $rec->id) ?>"> <i class="ti ti-edit"></i> Edit</a>
																<?php } ?>
																<?php if ($hak_akses->delete == '1') { ?>
																	<a class="dropdown-item" href="<?= base_url('User/hapus?id=' . $rec->id) ?>" onclick="return confirm('Are you sure?')"> <i class="ti ti-trash"></i> Delete</a>
																<?php } ?>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php } ?>
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
	<script src="<?= base_url() ?>assets/js/plugins/simple-datatables.js"></script>
	<script>
		const dataTable = new simpleDatatables.DataTable('#pc-dt-simple');
	</script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->

</html>