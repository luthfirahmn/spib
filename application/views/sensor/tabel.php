<div class="table-responsive">
	<table class="table" id="pc-dt-simple">
		<thead>
			<tr>
				<th>Site</th>
				<th>Jenis Sensor</th>
				<th>Jumlah Data</th>
				<th>Unit</th>
				<th>Icon</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($sensor as $rec){?>
			<tr>
				<td><?= $rec->site_name ?></td>
				<td><?= $rec->jenis_sensor ?></td>
				<td></td>									
				<td><?= $rec->unit_sensor ?></td>
				<td></td>
				<td>
					<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
						<div class="btn-group" role="group">
							<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action </button>
							<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
								<?php if($hak_akses->update=='1'){?>
									<a class="dropdown-item" href="<?= base_url('Sensor/edit?id='.$rec->id)?>"> <i class="ti ti-edit"></i> Edit</a>
								<?php } ?>
								<?php if($hak_akses->delete=='1'){?>
									<a class="dropdown-item" href="<?= base_url('Sensor/hapus?id='.$rec->id)?>" onclick="return confirm('Are you sure?')"> <i class="ti ti-trash"></i> Delete</a>
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