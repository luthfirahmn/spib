<?php foreach($parameter as $keyval=>$val){ ?>
	<div class="row">
		<div class="form-group col-md-12">
			<label class="form-label" for="<?= $keyval?>"><?= $keyval?></label>
			<input type="text" class="form-control parameter" id="<?= $keyval?>" name="<?= $keyval?>" value="<?= $val?>" required>
		</div>
	</div>
<?php } ?>