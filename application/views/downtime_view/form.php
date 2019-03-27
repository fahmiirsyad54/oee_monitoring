<div class="row">
	<div class="col-md-12">
		<div class="box"> 
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div> 
 
			<div class="box-body">
				<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
					<div class="row">
						<div class="col-md-3 hidden">
							<div class="form-group">
								<label>Code</label>
								<input type="text" name="vckode" placeholder="Code" class="form-control" id="vckode" required value="<?=$vckode?>" readonly />
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Date</label>
								<input type="text" name="dttanggal" placeholder="Date order" class="form-control" id="datepicker" required value="<?=$dttanggal?>" />
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Building</label>
								<select name="intgedung" class="form-control select2" id="intgedung">
									<option data-nama="" value="0">-- Select Building --</option>
									<?php
										foreach ($listgedung as $opt) {
											$selected = ($intgedung == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Cell</label>
								<select name="intcell" class="form-control select2" id="intcell">
									<option data-nama="" value="0">-- Select Cell --</option>
									<?php
										foreach ($listcell as $opt) {
											$selected = ($intcell == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Machine</label>
								<select name="intmesin" class="form-control select2" id="intmesin">
									<option data-nama="" value="0">-- Select Machine --</option>
									<?php
										foreach ($listmesin as $opt) {
											$selected = ($intmesin == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vckode?>" value="<?=$opt->intid?>"><?=$opt->vckode?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Shift</label>
								<select name="intshift" class="form-control" id="intshift">
									<option data-nama="" value="0">-- Select Shift --</option>
									<?php
										foreach ($listshift as $opt) {
											$selected = ($intshift == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							
							<div class="form-group">
								<label>Operator</label>
								<select name="intoperator" class="form-control select2" id="intoperator">
									<option data-nama="" value="0">-- Select Operator --</option>
									<?php
										foreach ($listoperator as $opt) {
											$selected = ($intoperator == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							
							<div class="form-group">
								<label>Leader</label>
								<select name="intleader" class="form-control select2" id="intleader">
									<option data-nama="" value="0">-- Select Leader --</option>
									<?php
										foreach ($listleader as $opt) {
											$selected = ($intleader == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
					</div>

					<hr>

					<div class="after-add-more">
						<?php
							if (count($dataDowntime) == 0) {
						?>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label>Type Downtime</label>
									<select name="inttype_downtime[]" class="form-control" id="inttype_downtime">
										<option data-nama="" value="0">-- Select Type Downtime --</option>
										<?php
											foreach ($listtype as $opt) {
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
									<label>Type List</label>
									<select name="inttype_list[]" class="form-control select2" id="inttype_list">
										<option data-nama="" value="0">-- Select Type --</option>
										<?php
											foreach ($listtypelist as $opt) {
										?>
										<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-1">
								<div class="form-group">
									<label>Start</label>
									<input type="text" name="dtmulai[]" placeholder="Start" class="form-control datetimepicker1" id="" required value="" />
			                	</div>
							</div>

							<div class="col-md-1">
								<div class="form-group">
					                <label>Finish</label>
						            <input type="text" name="dtselesai[]" placeholder="Finish" class="form-control datetimepicker1" id="" required value="" />
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label>Problem</label>
									<input type="text" name="vcmasalah[]" placeholder="Problem" class="form-control" id="vcmasalah" required value="" />
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Solution</label>
									<input type="text" name="vctindakan[]" placeholder="Solution" class="form-control" id="vctindakan" required value="" />
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Mechanic</label>
									<select name="intmekanik[]" class="form-control select2" id="intmekanik">
										<option data-nama="" value="0">-- Select Mechanic --</option>
										<?php
											foreach ($listmekanik as $opt) {
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label>Sparepart</label>
									<select name="intsparepart[]" class="form-control select2" id="intsparepart">
										<option data-nama="" value="0">-- Select Sparepart --</option>
										<?php
											foreach ($listsparepart as $opt) {
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-1">
								<div class="form-group">
									<label>Qty</label>
									<input type="text" name="intjumlah[]" placeholder="Qty" class="form-control" id="intjumlah" required value="" />
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group margin-top-25">
									<a href="javascript:void(0)" class="btn btn-success" onclick="addmore()"><i class="fa fa-plus"></i></a>
								</div>
							</div>
						</div>
						
						<hr style="margin-top: 0px; margin-bottom: 10px;">
						<?php
							} else {

							$loop = 0;
							foreach ($dataDowntime as $downtime) {
								$hideadd = ($loop == 0) ? '' : 'hidden' ;
								$hideremove = ($loop == 0) ? 'hidden' : '' ;
						?>
						<div class="control-group input-group">
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label>Type Downtime</label>
										<select name="inttype_downtime[]" class="form-control" id="inttype_downtime">
											<option data-nama="" value="0">-- Select Type Downtime --</option>
											<?php
												foreach ($listtype[$loop] as $opt) {
													$selected = ($opt->intid == $downtime->inttype_downtime) ? 'selected' : '' ;
											?>
											<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
											<?php
												}
											?>
										</select>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label>Type List</label>
										<select name="inttype_list[]" class="form-control select2" id="inttype_list">
											<option data-nama="" value="0">-- Select Type --</option>
											<?php
												foreach ($listtypelist[$loop] as $opt) {
													$selected = ($opt->intid == $downtime->inttype_list) ? 'selected' : '' ;
											?>
											<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
											<?php
												}
											?>
										</select>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label>Start</label>
										<input type="text" name="dtmulai[]" placeholder="Start" class="form-control datetimepicker1" id="" required value="<?=$downtime->dtmulai?>" />
				                	</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
						                <label>Finish</label>
							            <input type="text" name="dtselesai[]" placeholder="Finish" class="form-control datetimepicker1" id="" required value="<?=$downtime->dtselesai?>" />
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label>Problem</label>
										<input type="text" name="vcmasalah[]" placeholder="Problem" class="form-control" id="vcmasalah" required value="<?=$downtime->vcmasalah?>" />
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Solution</label>
										<input type="text" name="vctindakan[]" placeholder="Solution" class="form-control" id="vctindakan" required value="<?=$downtime->vctindakan?>" />
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label>Mechanic</label>
										<select name="intmekanik[]" class="form-control select2" id="intmekanik">
											<option data-nama="" value="0">-- Select Mechanic --</option>
											<?php
												foreach ($listmekanik as $opt) {
													$selected = ($opt->intid == $downtime->intmekanik) ? 'selected' : '' ;

											?>
											<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
											<?php
												}
											?>
										</select>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label>Sparepart</label>
										<select name="intsparepart[]" class="form-control select2" id="intsparepart">
											<option data-nama="" value="0">-- Select Sparepart --</option>
											<?php
												foreach ($listsparepart as $opt) {
													$selected = ($opt->intid == $downtime->intsparepart) ? 'selected' : '' ;
											?>
											<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
											<?php
												}
											?>
										</select>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label>Qty</label>
										<input type="text" name="intjumlah[]" placeholder="Qty" class="form-control" id="intjumlah" required value="<?=$downtime->intjumlah?>" />
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group margin-top-25">
										<a href="javascript:void(0)" class="btn btn-success <?=$hideadd?>" onclick="addmore()"><i class="fa fa-plus"></i></a>
										<a href="javascript:void(0)" class="btn btn-danger <?=$hideremove?> remove"><i class="fa fa-remove"></i></a>
									</div>
								</div>
							</div>
							
							<hr style="margin-top: 0px; margin-bottom: 10px;">
						</div>
						<?php
								$loop++;
							}
						}
						?>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
								<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i>Close</a>
							</div>
						</div>						
					</div>
				</form>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});
	function simpanData(action) {
		var vckode       = $('#vckode').val();
		var vcnama       = $('#vcnama').val();
		var vccontroller = $('#vccontroller').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {};
			var formdata = {};

			$.ajax({
				url: base_url + '/validasiform/required',
				method: "POST",
				data : formrequired
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				if (jsonData.length > 0) {
					var html = '';
					for (var i = 0; i < jsonData.length; i++) {
						html += '' + jsonData[i].error + '<br/>';
					}

					swal({
						type: 'error',
						title: 'There is something wrong',
						html: html
					});
				} else {
					$.ajax({
						url: base_url + '/validasiform/data',
						method: "POST",
						data : formdata
					})
					.done(function( data ) {
						var jsonData = JSON.parse(data);
						if (jsonData.length > 0) {
							var html = '';
							for (var i = 0; i < jsonData.length; i++) {
								html += '' + jsonData[i].error + '<br/>';
							}

							swal({
								type: 'error',
								title: 'There is something wrong',
								html: html
							});
						} else {
							$('#postdata').submit()
						}
					})
					.fail(function( jqXHR, statusText ) {
						alert( "Request failed: " + jqXHR.status );
					});
				}
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		} else if (action == 'Edit') {
			$('#postdata').submit();
		}
	}

	$('#intgedung').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_cell_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Cell --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
			}
			$('#intcell').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intcell').change(function(){ 
		var intid    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_mesin_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Machine --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vckode + '</option>';
			}
			$('#intmesin').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intgedung').change(function(){
		var intgedung    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_karyawan_ajax/' + intgedung + '/3',
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Operator --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
			}
			$('#intoperator').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

		$.ajax({
			url: base_url + '/get_karyawan_ajax/' + intgedung + '/1',
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Leader --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
			}
			$('#intleader').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

		$.ajax({
			url: base_url + '/get_karyawan_ajax/' + intgedung + '/2',
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Operator --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
			}
			$('#intmekanik').html(html);
		})
		
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#inttype_downtime').change(function(){ 
		var intid    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_typelist_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Type --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
			}
			$('#inttype_list').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$(function (){
		$('#datepicker').datepicker({
      	autoclose: true
    	})

		$('.datetimepicker1').datetimepicker({
			format: 'HH:mm'
		});
		
		$('#datetimepicker2').datetimepicker({
			format: 'HH:mm'
		});
	});

	function addmore(){
	var html = $(".copy-fields").html();
  		// $(".after-add-more").append(html);
  		var base_url = '<?=base_url($controller)?>';
  		$.ajax({
			url: base_url + '/form_detail_downtime',
			method: "GET"
		})
		.done(function( data ) {
			$(".after-add-more").append(data);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}
	$(document).ready(function() {
		//here first get the contents of the div with name class copy-fields and add it to after "after-add-more" div class.
	  	$(".add-more").click(function(){ 
	    	var html = $(".copy-fields").html();
	      	$(".after-add-more").append(html);
	  	});
		//here it will remove the current value of the remove button which has been pressed
	  	$("body").on("click",".remove",function(){ 
	      	$(this).parents(".control-group").remove();
	  	});
	});
</script>
