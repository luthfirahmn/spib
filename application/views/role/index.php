<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_choices.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:42:46 GMT -->

<head>
	<title>Role</title>
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


	<style>
		.container {
			max-width: 800px;
			width: 100%;
			margin: 0 auto;
			font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		}
	</style>
	<link rel="stylesheet" href="<?= base_url() ?>assets/loader/css-loader.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.7/sweetalert2.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.7/sweetalert2.min.js"></script>
</head>

<body onload="load_data('1')">

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
								<li class="breadcrumb-item" aria-current="page">Role management</li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<form action="<?= base_url('Role/edit_proses') ?>" method="post" enctype="multipart/form-data">
						<div class="card">
							<div class="card-header">
								<h5>Role Management</h5>
								</br>

								<div class="row">
									<div class="form-group col-md-6">
										<a type="button" class="btn btn-light-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
											<i class="ti ti-plus"></i>Add Role
										</a>
									</div>
									<div class="form-group col-md-6">
										<div class="row align-items-center">
											<div class="col-2">
												<label class=" form-label " for=" username">Role Name</label>
											</div>
											<div class="col-8">
												<select class="form-control" name="ms_roles_id" id="ms_roles_id" onchange="load_data(this.value)">
													<?php foreach ($role as $role) { ?>
														<option value="<?= $role->id ?>"><?= $role->role_name ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
								</div>

							</div>
							<div class=" mx-4 mt-3 d-flex">
								<h3 class="me-3 role_name" style="text-transform: capitalize;"></h3>
								<button type="button" class="btn btn-light-success  rounded-circle btn-sm me-1" id="btn_edit_role">
									<i class="ti ti-pencil"></i>
								</button>
								<button type="button" class="btn btn-light-danger  rounded-circle btn-sm" id="btn_delete_role" onclick="deleteRole()">
									<i class="ti ti-trash"></i>
								</button>
							</div>

							<div class="card-body" id="data_akses">

							</div>
						</div>

					</form>


				</div>
			</div>
		</div>
	</section>

	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form action="<?= base_url('Role/tambah_proses') ?>" method="post" enctype="multipart/form-data">
					<div class="modal-body">

						<div class="form-group">
							<label for="recipient-name" class="col-form-label">Nama Role:</label>
							<input type="text" class="form-control" id="nama_role" name="nama_role" required />
						</div>
						<div class="form-group">
							<label for="status" class="col-form-label">Status:</label>
							<select class="form-control" name="status" id="status">
								<option value="1">Aktif</option>
								<option value="0">Non Aktif</option>
							</select>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form action="<?= base_url('Role/edit_role') ?>" method="post" enctype="multipart/form-data">
					<div class="modal-body">

						<input type="hidden" class="form-control" id="id_role_edit" name="id_role_edit" required />
						<div class="form-group">
							<label for="recipient-name" class="col-form-label">Nama Role:</label>
							<input type="text" class="form-control" id="nama_role_edit" name="nama_role_edit" required />
						</div>
						<div class="form-group">
							<label for="status" class="col-form-label">Status:</label>
							<select class="form-control" name="status_edit" id="status_edit">
								<option value="1">Aktif</option>
								<option value="0">Non Aktif</option>
							</select>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>


	<?php $this->load->view('include/footer.php'); ?>
	<script src="<?= base_url() ?>assets/js/plugins/popper.min.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/simplebar.min.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/bootstrap.min.js"></script>
	<script src="<?= base_url() ?>assets/js/config.js"></script>
	<script src="<?= base_url() ?>assets/js/pcoded.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/feather.min.js"></script>

	<script src="<?= base_url() ?>assets/js/customizer.js"></script>
	<script src="<?= base_url() ?>assets/js/plugins/choices.min.js"></script>
	<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>

	<script src="
	https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#btn_edit_role').on('click', function() {
				$.ajax({
					url: "<?php echo base_url('Role/get_data_role'); ?>",
					method: "GET",
					dataType: "JSON",
					data: {
						ms_roles_id: $('#ms_roles_id').val()
					},
					success: function(response) {
						$('#id_role_edit').val(response.data.id)
						$('#nama_role_edit').val(response.data.role_name)
						$('#status_edit').val(response.data.status)
						$('#editRoleModal').modal('show')
					}
				});
			})
		});

		function load_data(role_id) {
			if (role_id == 1) {
				$('#btn_edit_role').prop('disabled', true);
				$('#btn_delete_role').prop('disabled', true);
			} else {
				$('#btn_edit_role').prop('disabled', false);
				$('#btn_delete_role').prop('disabled', false);
			}

			$('.role_name').html($('#ms_roles_id option:selected').text());

			var data_akses = $('#data_akses');
			$.ajax({
				url: "<?php echo base_url('Role/detail'); ?>",
				method: "POST",
				data: {
					ms_roles_id: role_id
				},

				success: function(data) {
					var json = data,
						obj = JSON.parse(json);
					data_akses.html(obj.tabel);
				}
			});
		};

		function deleteRole() {
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Delete',
				cancelButtonText: 'Cancel'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: "<?php echo base_url('Role/delete'); ?>",
						method: "POST",
						dataType: "JSON",
						data: {
							ms_roles_id: $('#ms_roles_id').val()
						},
						success: function(response) {
							console.log(response);
							if (!response.error) {
								toastr.success(response.msg, '', {
									progressBar: true,
									timeOut: 1500,
									onHidden: function() {
										location.reload();
									}
								});
							} else {
								toastr.error(response.msg)
							}
						}
					});
				}
			})
		}
	</script>
</body>
<!-- Mirrored from berrydashboard.io/bootstrap/default/forms/form2_choices.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:42:46 GMT -->

</html>