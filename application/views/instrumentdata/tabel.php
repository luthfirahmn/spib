<div class="table-responsive">
	<table class="table" id="pc-dt-simple">
		<thead>
			<tr>
				<th>Region Name</th>
				<th>Instrument Code</th>
				<th>Instrument Name</th>
				<th>Instrument Type</th>
				<th>Station</th>
				<th>Last Data</th>
				<th>Total Data</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($instrument as $rec) { ?>
				<tr>
					<td><?= $rec->site_name ?></td>
					<td><?= $rec->kode_instrument ?></td>
					<td><?= $rec->nama_instrument ?></td>
					<td><?= $rec->name ?></td>
					<td><?= $rec->nama_stasiun ?></td>
					<td><?= $rec->data_terakhir_masuk ?></td>
					<td><?= $rec->total_data ?></td>
					<td>
						<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
							<div class="btn-group" role="group">
								<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action </button>
								<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
									<?php if ($hak_akses->update == '1') { ?>
										<a class="dropdown-item" href="<?= base_url('InstrumentData/edit?id=' . $rec->id) ?>"> <i class="ti ti-edit"></i> Edit</a>
									<?php } ?>
									<?php if ($hak_akses->delete == '1') { ?>
										<a class="dropdown-item" href="<?= base_url('InstrumentData/hapus?id=' . $rec->id) ?>" onclick="return confirm('Are you sure?')"> <i class="ti ti-trash"></i> Delete</a>
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