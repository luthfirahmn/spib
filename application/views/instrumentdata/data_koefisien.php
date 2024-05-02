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
					<th>Jenis Sensor Data Mentah</th>
					<th>Jenis Sensor Data Jadi</th>
				</tr>
			</thead>
			<tbody class="">
				<tr>
					<td>
						<?php

						$parameter = json_decode($koe['parameter']);

						foreach ($parameter as $key => $value) {
							echo $key . ":" . $value . "<br>";
						}

						?>
					</td>
					<td>
						<?php
						foreach ($koe['jenis_sensor_mentah'] as $sns2) {
							if (!empty($sns2->jenis_sensor)) {
								echo $sns2->jenis_sensor . '</br>';
							}
						}
						?>
					</td>
					<td>
						<?php
						foreach ($koe['jenis_sensor_jadi'] as $sns1) {
							if (!empty($sns1->jenis_sensor)) {
								echo $sns1->jenis_sensor . '</br>';
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