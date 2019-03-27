<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div>

			<div class="box-body">
				<div class="row">
					<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
						<div class="col-md-4">
							<div class="form-group">
								<label>Date</label>
								<input type="text" name="dttanggal" placeholder="Date order" class="form-control" id="datepicker" required value="<?=$dttanggal?>" />
							</div>

							<div class="form-group">
								<label>Week</label>
								<select class="form-control" name="intweek" id="intweek">
									<option value="">-- Select Week --</option>
									<?php
										foreach ($listweek as $intid => $vcnama) {
											$selected = ($intid == $intweek) ? 'selected' : '';
									?>
									<option <?=$selected?> value="<?=$intid?>"><?=$vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group">
								<label>Building</label>
								<select class="form-control" name="intgedung" id="intgedung">
									<option value="">-- Select Building --</option>
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

							<div class="form-group">
								<label>Cell</label>
								<select class="form-control" name="intcell" id="intcell">
									<option value="">-- Select Cell --</option>
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

							<div class="form-group">
								<label>Model</label>
								<select class="form-control" name="intmodel" id="intmodel">
									<option value="">-- Select Model --</option>
									<?php
										foreach ($listmodel as $opt) {
											$selected = ($opt->intid == $intmodel) ? 'selected' : '';
									?>
									<option <?=$selected?> value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group">
								<label>Article</label>
								<input type="text" class="form-control" name="vcartikel" id="vcartikel" placeholder="Article" value="<?=$vcartikel?>">
							</div>
						</div>

						<div class="col-md-8">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>Process Group</th>
											<th>Machine / Technology</th>
											<th>Applicable</th>
											<th>Comply</th>
											<th>Remaks</th>
										</tr>
									</thead>
									<tbody id="teknologimesinmodel">
										<?php
											foreach ($listteknologimesin as $opt) {
												$applicablechecked = ($opt->intapplicable == 1) ? 'checked' : '' ;
												$teknologichecked  = ($opt->intcomply == 1) ? 'checked' : '' ;
										?>
										<tr class="teknologimesin">
											<input type="hidden" name="intprosesgroup[]" class="intprosesgroup" value="<?=$opt->intprosesgroup?>">
											<input type="hidden" name="intteknologimesin[]" class="intteknologimesin" value="<?=$opt->intteknologimesin?>">
											<input type="hidden" name="intapplicable[]" class="intapplicable" value="<?=$opt->intapplicable?>">
											<input type="hidden" name="intcomply[]" class="intcomply" value="<?=$opt->intcomply?>">
											<td><?=$opt->vcprosesgroup?></td>
											<td><?=$opt->vcteknologimesin?></td>
											<td align="center">
												<input type="checkbox" class="intapplicablecheck" name="intapplicablecheck[]" <?=$applicablechecked?>>
											</td>
											<td align="center">
												<input type="checkbox" class="intcomplycheck" name="intcomplycheck[]" <?=$teknologichecked?>>
											</td>
											<td>
												<input type="text" name="vcketerangan[]" class="form-control">
											</td>
										</tr>
										<?php
											}
										?>
									</tbody>
								</table>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
								<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i>Close</a>
							</div>
						</div>
					</form>
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
	});

	function simpanData(action) {
		var intgedung = $('#intgedung').val();
		var intcell   = $('#intcell').val();
		var intmodel  = $('#intmodel').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {'intgedung':intgedung,'intcell':intcell,'intmodel':intmodel};
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
			var html = '<option value="">-- Select Cell --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="'+jsonData[i].intid+'">'+jsonData[i].vcnama+'</option>';
			}
			$('#intcell').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intmodel').change(function(){
		var base_url = '<?=base_url($controller)?>';
		var intid    = $(this).val();
		$.ajax({
			url: base_url + '/getteknologimesin/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			// var jsonData = JSON.parse(data);
			// var html = '<option value="">-- Select Cell --</option>';
			// for (var i = 0; i < jsonData.length; i++) {
			// 	html += '<option value="'+jsonData[i].intid+'">'+jsonData[i].vcnama+'</option>';
			// }
			$('#teknologimesinmodel').html(data);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('.intapplicablecheck').change(function(){
		var row = $(this).closest(".teknologimesin");
		if ($(this).is(':checked')) {
		    row.find('.intapplicable').val(1);
		} else {
		    row.find('.intapplicable').val(0);
		}
	});

	$('.intcomplycheck').change(function(){
		var row = $(this).closest(".teknologimesin");
		if ($(this).is(':checked')) {
		    row.find('.intcomply').val(1);
		} else {
		    row.find('.intcomply').val(0);
		}
	});

</script>