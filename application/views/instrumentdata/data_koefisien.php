<?php foreach($koefisien as $koe){
	$id=$koe['id'];
	if($koe['type']=='VWP'){?>
		<div class="row">
			<div class="form-group col-md-6">
				<label class="form-label"><h4><?= $koe['name'] ?></h4></label>
			</div>
			<div class="form-group col-md-6">
				<button type="button" class="btn btn-outline-danger d-inline-flex" onclick="hapusTempKoefisien('<?= $id ?>')" style="float: right;">
					<i class="ti ti-trash"></i>Hapus
				</button>
				<button type="button" class="btn btn-outline-warning d-inline-flex" onclick="editTempKoefisien('<?= $id ?>')"  style="float: right;">
					<i class="ti ti-pencil"></i>Edit
				</button>
			</div>
		</div>
		<div class="row">
			<table class="table">
				<thead>
					<tr>
						<th>Data Koefisien</th>
						<th>Data Sensor</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<?php
							
								$parameter = json_decode($koe['parameter']);

								foreach($parameter as $key=>$value){
									echo $key.":".$value."<br>";
								}
								
							?>
						</td>
						<td>
							<?php
								foreach($koe['jenis_sensor'] as $sns){
									echo $sns->jenis_sensor.'</br>';
								}
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</br>
	<?php
	}else{
	?>
		<div class="row">
			<div class="form-group col-md-6">
				<label class="form-label"><h4><?= $koe['name'] ?></h4></label>
			</div>
			<div class="form-group col-md-6">
				<button type="button" class="btn btn-outline-danger d-inline-flex" onclick="hapusTempKoefisien('<?= $id ?>')" style="float: right;">
					<i class="ti ti-trash"></i>Hapus
				</button>
				<button type="button" class="btn btn-outline-warning d-inline-flex" onclick="editTempKoefisien('<?= $id ?>')"  style="float: right;">
					<i class="ti ti-pencil"></i>Edit
				</button>
			</div>
		</div>
		<div class="row">
			<table class="table">
				<thead>
					<tr>
						<th>Data Koefisien</th>
						<th>Jenis Sensor Data Jadi</th>
						<th>Jenis Sensor Data Mentah</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<?php
							
								$parameter = json_decode($koe['parameter']);

								foreach($parameter as $key=>$value){
									echo $key.":".$value."<br>";
								}
								
							?>
						</td>
						<td>
							<?php
								foreach($koe['jenis_sensor_jadi'] as $sns1){
									echo $sns1->jenis_sensor.'</br>';
								}
							?>
						</td>
						<td>
							<?php
								foreach($koe['jenis_sensor_mentah'] as $sns2){
									echo $sns2->jenis_sensor.'</br>';
								}
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		</br>
<?php }} ?>