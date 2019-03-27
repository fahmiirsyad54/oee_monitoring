<div class="row control-group">
    <div class="col-md-3 ">
        <div class="form-group">
            <label>Machine</label>
            <select class="form-control select2" name="intmesin[]" id="intmesin">
                <option value="0">-- Select Machine --</option>
                <?php
                    foreach ($listmachine as $opt) {
                        $selected = ($opt->intid == $intmesin) ? 'selected' : '';
                ?>
                <option <?=$selected?> value="<?=$opt->intid?>"><?=$opt->vckode . ' - ' . $opt->vcnama?></option>
                <?php
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="col-md-3 ">
        <div class="form-group">
            <label>Operator</label>
            <select class="form-control select2" name="intoperator[]" id="intoperator">
                <option value="0">-- Select Operator --</option>
                <?php
                    foreach ($listoperator as $opt) {
                        $selected = ($opt->intid == $intoperator) ? 'selected' : '';
                ?>
                <option <?=$selected?> value="<?=$opt->intid?>"><?=$opt->vckode . ' - ' . $opt->vcnama?></option>
                <?php
                    }
                ?>
            </select>
        </div>
    </div>
    
    <div class="col-md-1 ">
        <div class="form-group">
            <label>Form</label>
            <select class="form-control" name="intformterisi[]" id="intformterisi">
                <?php
                    foreach ($listscore as $key => $value) {
                        $selected = ($value == $intformterisi) ? 'selected' : '';
                ?>
                <option <?=$selected?> value="<?=$value?>"><?=$value?></option>
                <?php
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="col-md-1 ">
        <div class="form-group">
            <label>Implement</label>
            <select class="form-control" name="intimplementasi[]" id="intimplementasi">
                <?php
                    foreach ($listscore as $key => $value) {
                        $selected = ($value == $intimplementasi) ? 'selected' : '';
                ?>
                <option <?=$selected?> value="<?=$value?>"><?=$value?></option>
                <?php
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="col-md-2 col-sm-6 col-xs-6">
        <div class="form-group">
            <label>Remaks</label>
            <input type="text" class="form-control" name="vcketerangan[]" value="">
        </div>
    </div>

    <div class="col-md-2 col-sm-6 col-xs-6 margin-top-25">
        <button type="button" class="btn btn-danger remove"><i class="fa fa-remove"></i></button>
        <button type="button" class="btn btn-success" onclick="add_form()"><i class="fa fa-plus"></i></button>
    </div>
</div>

<script>
    $(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});
</script>