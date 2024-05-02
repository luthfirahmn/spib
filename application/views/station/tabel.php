
<div class="table-responsive">
	<table class="table" id="pc-dt-simple">
		<thead>
			<tr>
				<th>Site</th>
				<th>Nama Station</th>
				<th>Wilayah Sungai</th>
				<th>DAS</th>
				<th>Latitude</th>
				<th>Longitude</th>
				<th>Jumlah Instrument</th>
				<th>Jenis Komunikasi</th>
				<th>GSM/IP</th>
				<th>Foto</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($station as $rec){?>
			<tr>
				<td><?= $rec->site_name ?></td>
				<td><?= $rec->nama_stasiun ?></td>
				<td><?= $rec->wilayah_sungai ?></td>									
				<td><?= $rec->daerah_aliran_sungai ?></td>									
				<td><?= number_format($rec->latitude, 6) ?></td>
				<td><?= number_format($rec->longitude, 6) ?></td>
				<td></td>
				<td><?= $rec->komunikasi ?></td>
				<td><?= $rec->kontak_gsm ?></td>
				<td>
					<div class="d-inline-block align-middle" bis_skin_checked="1">
						<img src="<?= base_url('assets/upload/station/'.$rec->foto)?>" alt="image" class="img-radius wid-40 align-top m-r-15" onClick="viewImage('<?= $rec->foto ?>')">
					</div>
				</td>

				<td>
					<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
						<div class="btn-group" role="group">
							<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action </button>
							<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
								<?php if($hak_akses->update=='1'){?>
									<a class="dropdown-item" href="<?= base_url('Station/edit?id='.$rec->id)?>"> <i class="ti ti-edit"></i> Edit</a>
								<?php } ?>
								<?php if($hak_akses->delete=='1'){?>
									<a class="dropdown-item" href="<?= base_url('Station/hapus?id='.$rec->id)?>" onclick="return confirm('Are you sure?')"> <i class="ti ti-trash"></i> Delete</a>
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

<!-- The Modal -->
<div id="myModal" class="modal">
  <span class="close" onClick="closeImage()">&times;</span>
  <img class="modal-content" id="img01" onClick="closeImage()">
</div>



<link rel="stylesheet" href="<?= base_url()?>assets/imageupload/preview.css"/>
<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
<script>
	
	function viewImage(foto) {
		var modal = document.getElementById("myModal");
		var modalImg = document.getElementById("img01");
		modal.style.display = "block";
  		modalImg.src = "<?= base_url('assets/upload/station/')?>"+foto;

	}

	function closeImage() { 
		var modal = document.getElementById("myModal");
		modal.style.display = "none";
	}
</script>