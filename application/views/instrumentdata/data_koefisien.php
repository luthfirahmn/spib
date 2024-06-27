<?php foreach ($koefisien as $koe) {
	$id = $koe['id'];
?>
	<div class="row">
		<div class="form-group col-md-6">
			<label class="form-label">
				<h4><?= $koe['name'] ?></h4>
			</label>
		</div>
		<div class="form-group col-md-6">
			<button type="button" class="btn btn-outline-danger d-inline-flex" onclick="hapusTempKoefisien('<?= $id ?>')" style="float: right;">
				<i class="ti ti-trash"></i>Hapus
			</button>
			<button type="button" class="btn btn-outline-warning d-inline-flex" onclick="editTempKoefisien('<?= $id ?>')" style="float: right;">
				<i class="ti ti-pencil"></i>Edit
			</button>
		</div>
	</div>
	<div class="row">
		<table class="table border-2">
			<thead>
				<tr>
					<th>Data Koefisien</th>
					<th>Jenis Sensor Primer</th>
					<th>Jenis Sensor Sekunder</th>
				</tr>
			</thead>
			<tbody class="">
				<tr>
					<td>

						<table border="0">
							<tbody>
								<?php

								$parameter = json_decode($koe['parameter']);
								foreach ($parameter as $key => $value) : ?>
									<tr>
										<td><?php echo $key; ?></td>
										<td><?php echo $value; ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</td>
					<td style=" vertical-align:top">
						<?php
						foreach ($koe['jenis_sensor_mentah'] as $sns2) {
							if (!empty($sns2->jenis_sensor)) {
								$jenis_sensor = $sns2->jenis_sensor;
								// Cek apakah terdapat tanda kurung buka (
								if (strpos($jenis_sensor, '(') !== false) {
									// Tambahkan spasi di belakang tanda kurung buka
									$jenis_sensor = str_replace('(', '  (', $jenis_sensor);
								}

								echo $jenis_sensor  . '</br>';
							}
						}
						?>
					</td>
					<td style=" vertical-align:top">
						<?php
						foreach ($koe['jenis_sensor_jadi'] as $sns1) {
							if (!empty($sns1->jenis_sensor)) {
								$jenis_sensor = $sns1->jenis_sensor;
								// Cek apakah terdapat tanda kurung buka (
								if (strpos($jenis_sensor, '(') !== false) {
									// Tambahkan spasi di belakang tanda kurung buka
									$jenis_sensor = str_replace('(', '  (', $jenis_sensor);
								}

								echo $jenis_sensor  . '</br>';
							}
						}
						?>
					</td>

				</tr>
			</tbody>
		</table>
	</div>
	</br>
<?php }
?>