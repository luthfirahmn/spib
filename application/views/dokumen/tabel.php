<div class="table-responsive">
	<table class="table" id="pc-dt-simple">
		<thead>
			<tr>
				<th>Nama</th>
				<th>Kategori</th>
				<!-- <th>Tanggal</th> -->
				<th>Site</th>
				<th>Lampiran</th>
				<th>Deskripsi</th>
				<th>Created By</th>
				<th>Created Date</th>
				<th>Edited By</th>
				<th>Edited Date</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($dokumen as $rec) { ?>
				<tr>
					<td><?= $rec->title ?></td>
					<td><?= $rec->jenis ?></td>
					<!-- <td><?= $rec->date ?></td> -->
					<td><?= $rec->site_name ?></td>
					<td><a href="<?= base_url('Dokumen/createzip?id=' . $rec->id) ?>"> <i class="ti ti-arrow-bar-to-down"></i> Download</a></td>
					<td><?= substr($rec->description, 0, 50) ?></td>
					<td><?= $rec->created_by ?></td>
					<td><?= date("d M Y", strtotime($rec->created_at)) ?></td>
					<td><?= $rec->updated_by ?></td>
					<td><?= date("d M Y", strtotime($rec->updated_at)) ?></td>
					<td>
						<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
							<div class="btn-group" role="group">
								<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action </button>
								<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
									<?php if ($hak_akses->update == '1') { ?>
										<a class="dropdown-item" href="<?= base_url('Dokumen/edit?id=' . $rec->id) ?>"> <i class="ti ti-edit"></i> Edit</a>
									<?php } ?>
									<?php if ($hak_akses->delete == '1') { ?>
										<a class="dropdown-item" href="<?= base_url('Dokumen/hapus?id=' . $rec->id) ?>" onclick="return confirm('Are you sure?')"> <i class="ti ti-trash"></i> Delete</a>
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