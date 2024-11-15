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

		<style>
    #ModalEdit2
			{
				position:fixed;
				top:0;
				left:0;
				background:rgba(0,0,0,.9);
				height:100%;
				width:100%;
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
									<h5 class="m-b-10">Instrument Type</h5>
								</div>
								<ul class="breadcrumb">
									<li class="breadcrumb-item">
										<a href="<?= base_url()?>">Home</a>
									</li>
									<li class="breadcrumb-item" aria-current="page">Instrument Type</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-12">
						<div class="card">
							<div class="card-header">
								<h5>Data Instrument Type</h5>
							</div>
							<div class="card-body table-border-style">
								<?php if($hak_akses->insert=='1'){?>
									<a href="<?= base_url('Instrument/tambah')?>" type="button" class="btn btn-light-primary mb-3">
										<i class="ti ti-plus"></i>Tambah Data
									</a>
								<?php } ?>
								<div class="table-responsive">
									<table class="table" id="pc-dt-simple">
										<thead>
											<tr>
												<th>Site</th>
												<th>Nama Instrument</th>
												<th>Type</th>
												<th>Parameter</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($instrument as $rec){?>
											<tr>
												<td>
													<?php 
														foreach($rec['region'] as $reg){
															echo $reg->site_name.'</br>';
														} 
													?>
												</td>
												<td><?= $rec['name'] ?></td>
												<td><?= $rec['type'] ?></td>									
												<td>
													<button type="button" data-id="<?= $rec['id'] ?>" data-name="<?= $rec['name'] ?>" class="open-AddBookDialog btn btn-outline-info d-inline-flex" data-bs-toggle="modal" >
														<i class="ti ti-info-circle me-1"></i>Parameter
													</button>

												</td>
												<td>
													<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
														<div class="btn-group" role="group">
															<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action </button>
															<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
																<?php if($hak_akses->update=='1'){?>
																	<a class="dropdown-item" href="<?= base_url('Instrument/edit?id='.$rec['id'])?>"> <i class="ti ti-edit"></i> Edit</a>
																<?php } ?>
																<?php if($hak_akses->delete=='1'){?>
																	<a class="dropdown-item" href="<?= base_url('Instrument/hapus?id='.$rec['id'])?>" onclick="return confirm('Are you sure?')"> <i class="ti ti-trash"></i> Delete</a>
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

		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="judul"></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form action="<?= base_url('Instrument/simpan_parameter')?>" method="post" enctype="multipart/form-data">
						<div class="modal-body">
								<input type="text" id="idInstrument" name="idInstrument" hidden>				
								<div style="text-align:right; cursor:pointer;" onclick="add_item('tbl_create', 'create')">
									<i class="fas fa-plus-square" style="color:green;"></i> Tambah Parameter
								</div>
								<div class="form-group">
									<table id="tbl_create" width="100%">
										<tr>
											<th>Nama Parameter</th>
											<th></th>
										</tr>
									</table>
								</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
							<button type="submit" class="btn btn-primary">Simpan</button>
						</div>
					</form>
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
		<script src="<?= base_url()?>assets/js/plugins/popper.min.js"></script>
		<script src="<?= base_url()?>assets/js/plugins/simplebar.min.js"></script>
		<script src="<?= base_url()?>assets/js/plugins/bootstrap.min.js"></script>
		<script src="<?= base_url()?>assets/js/config.js"></script>
		<script src="<?= base_url()?>assets/js/pcoded.js"></script>
		<script src="<?= base_url()?>assets/js/plugins/feather.min.js"></script>
		<script src="<?= base_url()?>assets/js/customizer.js"></script>
		<script src="<?= base_url()?>assets/js/plugins/simple-datatables.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>
			const dataTable = new simpleDatatables.DataTable('#pc-dt-simple');
			var cell_number = 0;

			$(document).on("click", ".open-AddBookDialog", function () {
				var idInstrument = $(this).data('id');
				var nama_instrument = $(this).data('name');
				var judul = document.getElementById("judul");
				judul.innerHTML=nama_instrument;


				document.getElementById('idInstrument').value = idInstrument;

				//cek data 
				var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							
							
							// DATA DETAIL
							
							var result = JSON.parse(xhttp.responseText).result;
							
							var table = document.getElementById('tbl_create');
							
							table.innerHTML = '';
							
							var header = table.insertRow(0);
							
							var hdr1 = header.insertCell(0);
							hdr1.innerHTML = 'Nama Parameter'; 
							
							var hdr2 = header.insertCell(1);
							hdr2.innerHTML = '';
							
							for(i=0; i<result.length; i++)
							{
								cell_number++;
								
								var row = table.insertRow(1);

								var cell0 = row.insertCell(0);
								cell0.innerHTML = '<input type="text" name="nama_parameter[]" id="nama_parameter_'+cell_number+'"  value="'+result[i].parameter_name+'" class="form-control" required>';				
								
								var cell1 = row.insertCell(1);
								cell1.innerHTML = '<button type="button" class="btn btn-icon btn-danger d-inline-flex" onclick="deleteRow(this)"><i class="ti ti-trash"></i></button>';
								cell1.style.padding = '10px';
								cell1.style.textAlign = 'center';
								cell1.style.width = '2%';
							}
						}
					};
					xhttp.open("GET", "<?php echo base_url('Instrument/parameter'); ?>"+'?id='+idInstrument, true);
					xhttp.send();

				$('#exampleModal').modal('show');
			});


			function add_item(tbl_name, act)
			{
				cell_number++;				
				var table = document.getElementById(tbl_name);
				var row = table.insertRow(1);

				var cell0 = row.insertCell(0);
				cell0.innerHTML = '<input type="text" name="nama_parameter[]" id="nama_parameter_'+cell_number+'" class="form-control" required>';				
				
				var cell1 = row.insertCell(1);
				cell1.innerHTML = '<button type="button" class="btn btn-icon btn-danger d-inline-flex"  onclick="deleteRow(this)"><i class="ti ti-trash"></i></button>';
				cell1.style.padding = '10px';
				cell1.style.textAlign = 'center';
				cell1.style.width = '2%';

				
			}

			function deleteRow(param)
			{
				var row = param.parentNode.parentNode;
				row.parentNode.removeChild(row);
			}
		</script>
	</body>
	<!-- Mirrored from berrydashboard.io/bootstrap/default/table/tbl_dt-simple.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:43:21 GMT -->
</html>