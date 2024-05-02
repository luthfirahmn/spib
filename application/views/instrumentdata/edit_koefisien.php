<div class="form-group col-md-6">
    <label class="form-label" for="modal_type_edit">Instrument Type</label>
    <select class="form-control" name="modal_type" id="modal_type_edit" onchange="getNameType(this);">
        <?php foreach ($type as $tp) { ?>
            <option value="<?= $tp->id ?>" <?= $tp->pilih ?>><?= $tp->name ?></option>
        <?php } ?>
    </select>
</div>