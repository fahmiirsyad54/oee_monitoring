<div class="control-group">
	    <div class="row">
			<div class="col-md-3">	
				<div class="form-group">
					<label>Models</label>
					<select name="intmodel[]" class="form-control intmodel select2" id="intmodel">
						<option value="">-- Select Models --</option>
						<?php
							foreach ($listmodels as $opt) {
						?>
						<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
						<?php
							}
						?>
					</select>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<label>Total Cell</label>
					<input type="text" name="intcell[]" placeholder="" class="form-control intcell" id="intcell" value="" required />
				</div>
			</div>
			
			<div class="col-md-2 margin-top-25">
				<div class="form-group">
					<a href="javascript:void(0)" class="btn btn-danger remove"><i class="fa fa-remove"></i></a>
					<!-- <button class="btn btn-danger remove margin-top-25" type="button"><i class="glyphicon glyphicon-remove"></i></button> -->
				</div>
			</div>
		</div>
</div>

<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});
</script>