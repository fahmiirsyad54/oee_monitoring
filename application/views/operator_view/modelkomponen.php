<div class="row formmodelkomponen">
    <div class="col-md-4">
        <div class="form-group">
            <label>Model</label>
            <select name="intmodel" class="form-control select2" id="intmodel">
                <option data-nama="" value="0">-- Select Model --</option>
                <?php
                    foreach ($listmodels as $opt) {
                ?>
                <option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
                <?php
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Component</label>
            <select name="intkomponen" class="form-control select2" id="intkomponen">
                <option data-ct="0" value="0">-- Select Component --</option>
            </select>
            <input type="hidden" name="decct" id="decct" class="form-control">
        </div>
    </div>

    <div class="col-md-2">
    <a href="#" class="remove_field">Remove</a>
    </div>
</div>