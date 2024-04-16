<?php foreach($parameter as $param){ ?>
	<div class="row">
		<div class="form-group col-md-12">
			<label class="form-label" for="<?= $param->parameter_name?>"><?= $param->parameter_name?></label>
			<input type="text" class="form-control parameter" id="<?= $param->parameter_name?>" name="<?= $param->parameter_name?>" required>
		</div>
	</div>
<?php } ?>