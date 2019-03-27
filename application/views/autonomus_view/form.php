<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div>

			<div class="box-body">
				<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-4 ">
							<div class="form-group">
								<label>Date</label>
								<input type="text" name="dttanggal" placeholder="Date order" class="form-control" id="datepicker" required value="<?=$dttanggal?>" />
							</div>
						</div>

						<div class="col-md-4 ">
							<div class="form-group">
								<label>Building</label>
								<select class="form-control" name="intgedung" id="intgedung">
									<option>-- Select Building --</option>
									<?php
										foreach ($listgedung as $opt) {
											$selected = ($opt->intid == $intgedung) ? 'selected' : '';
									?>
									<option <?=$selected?> value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="col-md-4 ">
							<div class="form-group">
								<label>Cell</label>
								<select class="form-control" name="intcell" id="intcell">
									<option>-- Select Cell --</option>
									<?php
										foreach ($listcell as $opt) {
											$selected = ($opt->intid == $intcell) ? 'selected' : '';
									?>
									<option <?=$selected?> value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
					</div>
					
					<div id="formscore">
						<div class="row control-group">
							<div class="col-md-3 ">
								<div class="form-group">
									<label>Machine</label>
									<div id="machineloading">
										<i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
									</div>
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
									<div id="operatorloading">
										<i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
									</div>
									<select class="form-control select2" name="intoperator[]" id="intoperator">
										<option value="0">-- Select Operator --</option>
										<?php
											foreach ($listmachine as $opt) {
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
								<button type="button" class="btn btn-success" onclick="add_form()"><i class="fa fa-plus"></i></button>
							</div>
						</div>
					</div>

					<div id="formloading" class="hidden">
							<i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
					</div>
				</form>

				<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
								<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i>Close</a>
							</div>
						</div>
				</div>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		$('.select2').select2();

		$('#datepicker').datepicker({
	      	autoclose: true,
	      	format: 'dd-mm-yyyy'
    	})

		var base_url = '<?=base_url($controller)?>';

		$('.select2').addClass('hidden');
		$('#machineloading').removeClass('hidden');
		$('#operatorloading').removeClass('hidden');
		// $('#intoperator').addClass('hidden');

	    $.ajax({
			url: base_url + '/getmesinoperatorajax',
			method: "GET"
		})
		.done(function( data ) {
			var jsonData     = JSON.parse(data);
			var intmesin     = <?=$intmesin?>;
			var intoperator  = <?=$intoperator?>;
			var datamesin    = jsonData.datamesin;
			var dataoperator = jsonData.dataoperator;
			var htmlmesin    = '<option value="0">-- Select Machine --</option>';
			var htmloperator = '<option value="0">-- Select Operator --</option>';
			for (var i = 0; i < datamesin.length; i++) {
				var _selected = (datamesin[i].intid == intmesin) ? 'selected' : '';
				htmlmesin += '<option ' + _selected + ' value="'+datamesin[i].intid+'">'+ datamesin[i].vckode + ' - ' + datamesin[i].vcnama + '</option>';
			}

			for (var i = 0; i < dataoperator.length; i++) {
				var _selected = (dataoperator[i].intid == intoperator) ? 'selected' : '';
				htmloperator += '<option  ' + _selected + '  value="'+dataoperator[i].intid+'">'+ dataoperator[i].vckode + ' - ' + dataoperator[i].vcnama + '</option>';
			}

			$('#intmesin').html(htmlmesin);
			$('#intoperator').html(htmloperator);
			$('#machineloading').addClass('hidden');
			$('#operatorloading').addClass('hidden');
			$('.select2').removeClass('hidden');
			// $('#intoperator').removeClass('hidden');
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
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
		var base_url = '<?=base_url($controller)?>';
		var intid    = $(this).val();
		$.ajax({
			url: base_url + '/getcellajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option>-- Select Cell --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="'+jsonData[i].intid+'">'+jsonData[i].vcnama+'</option>';
			}
			$('#intcell').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	function add_form(){
		var base_url = '<?=base_url($controller)?>';
		$('#formloading').removeClass('hidden');
		$.ajax({
			url: base_url + '/get_formscore',
			method: "GET"
		})
		.done(function( data ) {
			$('#formloading').addClass('hidden');
			$('#formscore').append(data);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	$(document).ready(function() {
			//here it will remove the current value of the remove button which has been pressed
		  	$("body").on("click",".remove",function(){ 
		      	$(this).parents(".control-group").remove();
		  	});
		});

</script>