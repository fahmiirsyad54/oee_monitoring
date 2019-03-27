<div class="row">
	<div class="col-md-12">
		<div class="box"> 
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div> 

			<div class="box-body">
				<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Downtime</label>
								<select name="intdowntime" class="form-control select2" id="intdowntime">
									<option data-nama="" value="0">-- Select Downtime --</option>
									<?php
										foreach ($listdowntime as $opt) {
											$selected = ($intdowntime == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcmesin?>" value="<?=$opt->intid?>">(<?=$opt->vcmesin?>) <?=date('d m Y',strtotime($opt->dttanggal))?> - <?=$opt->vcshift?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
					</div>

					<div class="row hidden" id="loading">
						<div class="col-md-12 text-center">
							<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
						</div>
					</div>

					<div class="hidden" id="formoee">
						<div id="detailoee">
							
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
									<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
									<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i> Cancel</a>
								</div>
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
		var vccontroller = $('#vccontroller').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {'vckode' : vckode};
			var formdata = {'vckode' : vckode};

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

	$('#intdowntime').change(function(){
		var base_url = '<?=base_url($controller)?>';
		var intid    = $(this).val();
		$('#loading').removeClass('hidden');
		$.ajax({
			url: base_url + '/get_oee_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			$('#loading').addClass('hidden');
			$('#formoee').removeClass('hidden');
			if (intid == 0) {
				$('#formoee').addClass('hidden');
			}
			// var jsonData = JSON.parse(data);
			$('#detailoee').html(data);
			
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

	});
	
</script>
