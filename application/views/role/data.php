<div class="row">
	<div class="col-sm-10 col-md-10">

	</div>
	<div class="col-sm-2 col-md-2">
		<div class="form-check">
			<input class="form-check-input" id="full_akses" type="checkbox" onchange="centang_semua(this)">
			<label class="form-check-label" for="full_akses"> <b>Full Access</b> </label>
		</div>
	</div>
</div>
</br>
<?php foreach ($akses as $rec) { ?>
	<div class="row mb-2 ">
		<div class="col-form-label col-md-2 col-sm-2 pt-0  fw-semibold"><?= $rec->menu_name ?></div>
		<div class="col-sm-2 col-md-2">
			<div class="form-check">
				<input class="form-check-input" type="checkbox" id="view_<?= $rec->id ?>" name="view[<?= $rec->id ?>]" <?= ($rec->view == '1') ? 'checked' : ''; ?>>
				<label class="form-check-label" for="view_<?= $rec->id ?>"> View </label>
			</div>
		</div>
		<div class="col-sm-2 col-md-2">
			<div class="form-check">
				<input class="form-check-input" type="checkbox" id="insert_<?= $rec->id ?>" name="insert[<?= $rec->id ?>]" <?= ($rec->insert == '1') ? 'checked' : ''; ?>>
				<label class="form-check-label" for="insert_<?= $rec->id ?>"> Insert </label>
			</div>
		</div>
		<div class="col-sm-2 col-md-2">
			<div class="form-check">
				<input class="form-check-input" type="checkbox" id="update_<?= $rec->id ?>" name="update[<?= $rec->id ?>]" <?= ($rec->update == '1') ? 'checked' : ''; ?>>
				<label class="form-check-label" for="update_<?= $rec->id ?>"> Update </label>
			</div>
		</div>
		<div class="col-sm-2 col-md-2">
			<div class="form-check">
				<input class="form-check-input" type="checkbox" id="delete_<?= $rec->id ?>" name="delete[<?= $rec->id ?>]" <?= ($rec->delete == '1') ? 'checked' : ''; ?>>
				<label class="form-check-label" for="delete_<?= $rec->id ?>"> Delete </label>
			</div>
		</div>
		<div class="col-sm-2 col-md-2">
			<div class="form-check">
				<input class="form-check-input" type="checkbox" id="all_<?= $rec->id ?>" name="all[<?= $rec->id ?>]" <?= ($rec->view == '1' && $rec->insert == '1' && $rec->update == '1' && $rec->delete == '1') ? 'checked' : ''; ?> onchange="checkAll(this, '<?= $rec->id ?>')">
				<label class="form-check-label" for="all_<?= $rec->id ?>"> All Access</label>
			</div>
		</div>
	</div>
<?php } ?>
</br>
<button type="submit" class="btn btn-primary">Submit</button>
<script>
	function checkAll(ele, id_ele) {

		var checkboxes_view = document.getElementById('view_' + id_ele);
		var checkboxes_insert = document.getElementById('insert_' + id_ele);
		var checkboxes_update = document.getElementById('update_' + id_ele);
		var checkboxes_delete = document.getElementById('delete_' + id_ele);
		if (ele.checked) {
			checkboxes_view.checked = true;
			checkboxes_insert.checked = true;
			checkboxes_update.checked = true;
			checkboxes_delete.checked = true;
		} else {
			checkboxes_view.checked = false;
			checkboxes_insert.checked = false;
			checkboxes_update.checked = false;
			checkboxes_delete.checked = false;
		}
	}

	function centang_semua(ele) {
		var checkboxes = document.getElementsByTagName('input');
		if (ele.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = true;
				}
			}
		} else {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = false;
				}
			}
		}
	}
</script>