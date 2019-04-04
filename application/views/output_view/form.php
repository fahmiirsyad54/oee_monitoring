	<div class="row">
	<div class="col-md-12">
		<div class="box"> 
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div> 
 
			<div class="box-body">
				<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Date</label>
								<input type="text" name="dttanggal" placeholder="Date order" class="form-control form_datetime" id="" required value="<?=$dttanggal?>" />
							</div>
						</div>

						<div class="col-md-2">
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
								<label>Machine</label>
								<select name="intmesin" class="form-control select2" id="intmesin">
									<option data-nama="" value="0">-- Select Machine --</option>
									<?php
										foreach ($listmesin as $opt) {
											$selected = ($intmesin == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vckode?>" value="<?=$opt->intid?>"><?=$opt->vckode . ' - ' . $opt->vcnama?></option>
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
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vckode . ' - ' . $opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="col-md-2">
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

					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Models</label>
								<select name="intmodel" class="form-control select2" id="intmodel">
									<option data-nama="" value="0">-- Select Models --</option>
									<?php
										foreach ($listmodels as $opt) {
											$selected = ($intmodel == $opt->intid) ? 'selected' : '' ;
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
								<label>Start</label>
								<input type="text" name="dtmulai" placeholder="Start" class="form-control datetimepicker1" id="" required value="<?=$dtmulai?>" />
		                	</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
				                <label>Finish</label>
					            <input type="text" name="dtselesai" placeholder="Finish" class="form-control datetimepicker1" id="" required value="<?=$dtselesai?>" />
							</div>
						</div>
					</div>

					<div class="after-add-more">
						<?php
							if ($intkomponen == 0) {
						?>
						<div class="row control-group">
							<div class="col-md-3">
								<div class="form-group">
									<label>Component</label>
									<select name="intkomponen[]" class="form-control select2" id="intkomponen">
										<option data-nama="" value="0">-- Select Komponent --</option>
										<?php
											foreach ($listkomponen as $opt) {
												$selected = ($intkomponen == $opt->intkomponen) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->intkomponen?>"><?=$opt->vckomponen?></option>
										
										<?php
											}
										?>
									</select>
									<input type="hidden" name="decct[]" id="decct" class="form-control" value="<?=$decct?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Pair</label>
									<input type="number" name="intpasang[]" id="intpasang" class="form-control" value="<?=$intpasang?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Reject</label>
									<input type="number" name="intreject[]" id="intreject" class="form-control" value="<?=$intreject?>">
								</div>
							</div>

							<div class="col-md-1">
								<div class="form-group">
									<button class="btn btn-success margin-top-25" onclick="addmore()" type="button"><i class="glyphicon glyphicon-plus"></i></button>
								</div>
							</div>
						</div>

						<?php
							} else {
								foreach ($datacombine as $combine) {
						?>

						<div class="row control-group">
							<input type="hidden" name="intidcombine[]" value="<?=$combine->intid?>">
							<div class="col-md-3">
								<div class="form-group">
									<label>Component</label>
									<select name="intkomponen[]" class="form-control select2" id="intkomponen">
										<option data-nama="" value="0">-- Select Komponent --</option>
										<?php
											foreach ($listkomponen as $opt) {
												$selected = ($combine->intkomponen == $opt->intkomponen) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->intkomponen?>"><?=$opt->vckomponen?></option>
										
										<?php
											}
										?>
									</select>
									<input type="hidden" name="decct[]" id="decct" class="form-control" value="<?=$combine->decct?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Pair</label>
									<input type="number" name="intpasang[]" id="intpasang" class="form-control" value="<?=$combine->intpasang?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Reject</label>
									<input type="number" name="intreject[]" id="intreject" class="form-control" value="<?=$combine->intreject?>">
								</div>
							</div>
						</div>

						<?php
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
	    $('.select2').select2();
	    $(".form_datetime").datetimepicker({locale: 'id'});
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

	$('#intmodel').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url("output")?>';
		$.ajax({
			url: base_url + '/getkomponen_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option data-nama="" value="0">-- Select Component --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option data-ct="' + jsonData[i].deccycle_time + '" value="' + jsonData[i].intkomponen + '">' + jsonData[i].vckomponen + '</option>'
			}

			$('#intkomponen').html(html)
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intkomponen').change(function(){
		var intid    = $(this).val();
		var decct    = $(this).children('option:selected').data('ct');

		$('#decct').val(decct)
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
				url: base_url + '/form_detail_output',
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
