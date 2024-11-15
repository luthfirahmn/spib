<!DOCTYPE html>
<html lang="en">
	<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->
	<head>
		<title>Simple DataTable | Berry Bootstrap 5 Admin Template</title>
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

			<?php if ($this->session->flashdata('warning')): ?>
				<div class="alert alert-warning d-flex align-items-center" role="alert">
					<?php echo $this->session->flashdata('warning'); ?>
				</div>
			<?php endif; ?>

				<div class="page-header">
					<div class="page-block">
						<div class="row align-items-center">
							<div class="col-md-12">
								<div class="page-header-title">
									<h5 class="m-b-10">Documentation</h5>
								</div>
								<ul class="breadcrumb">
									<li class="breadcrumb-item">
										<a href="<?= base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item" aria-current="page">Documentation</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-12">
						<div class="card">
							<div class="card-header">
								<h5>Data Documentation</h5>
							</div>
							<div class="card-body table-border-style">
								<?php if($hak_akses->insert=='1'){?>
									<a href="<?= base_url('Dokumen/tambah')?>" type="button" class="btn btn-light-primary mb-3">
										<i class="ti ti-plus"></i>Tambah Data
									</a>
								<?php } ?>
								<div class="table-responsive">
									<table class="table" id="pc-dt-simple">
										<thead>
											<tr>
												<th>Nama</th>
												<th>Kategori</th>
												<th>Tanggal</th>
												<th>Site</th>
												<th>Lampiran</th>
												<th>Deskripsi</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($dokumen as $rec){?>
											<tr>
												<td><?= $rec->title ?></td>
												<td><?= $rec->jenis ?></td>
												<td><?= $rec->date ?></td>									
												<td><?= $rec->site_name ?></td>
												<td><a href="<?= base_url('Dokumen/createzip?id='.$rec->id)?>"> <i class="ti ti-arrow-bar-to-down"></i> Download</a></td>
												<td><?= substr($rec->description,0, 50) ?></td>
												<td>
													<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
														<div class="btn-group" role="group">
															<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action </button>
															<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
																<?php if($hak_akses->update=='1'){?>
																	<a class="dropdown-item" href="<?= base_url('Dokumen/edit?id='.$rec->id)?>"> <i class="ti ti-edit"></i> Edit</a>
																<?php } ?>
																<?php if($hak_akses->delete=='1'){?>
																	<a class="dropdown-item" href="<?= base_url('Dokumen/hapus?id='.$rec->id)?>" onclick="return confirm('Are you sure?')"> <i class="ti ti-trash"></i> Delete</a>
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
		<script src="<?= base_url()?>assets/js/plugins/simple-datatables.js"></script>
		<script>
			const dataTable = new simpleDatatables.DataTable('#pc-dt-simple');
		</script>
	</body>
	<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->
</html>