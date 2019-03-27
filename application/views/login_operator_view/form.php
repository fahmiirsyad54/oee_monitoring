<script src="<?=BASE_URL_PATH?>assets/bootstrap-datetimepicker-0.0.11/css/bootstrap-datetimepicker.min.css"></script>
  <script src="<?=BASE_URL_PATH?>assets/bootstrap-datetimepicker-0.0.11/js/bootstrap-datetimepicker.min.js"></script>
<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div>

			<div class="box-body">
				<div class="row">
					<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
						<div class="col-md-6">
							<div class="form-group">
								<label>User</label>
								<select name="intuser" class="form-control select2" id="intuser">
									<option data-nama="" value="0">-- Select User --</option>
									<?php
										foreach ($listuser as $opt) {
											$selected = ($intuser == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcusername?> - <?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select> 
							</div>

							<div class="form-group">
								<label>Operator</label>
								<select name="intkaryawan" class="form-control select2" id="intkaryawan">
									<option data-nama="" value="0">-- Select Operator --</option>
									<?php
										foreach ($listkaryawan as $opt) {
											$selected = ($intkaryawan == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vckode?> - <?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select> 
							</div>

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

							<div class="form-group">
								<label>Log</label>
								<select name="intlogin" class="form-control" id="intlogin">
									<option data-nama="" value="0">-- Select Log --</option>
									<?php
										foreach ($listlog as $key => $value) {
											$selected = ($intlogin == $key) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$value?>" value="<?=$key?>"><?=$value?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group">
								<label>Date</label>
								<input type="text" name="dtlogin" placeholder="Date order" class="form-control form_datetime" id="" required value="<?=$dtlogin?>" />
							</div>

						</div>

						<div class="col-md-6">
							
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
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2();
	    $(".form_datetime").datetimepicker({locale: 'id'});
	});

	function simpanData(action) {
		var vcnama       = $('#vcnama').val();
		var vccontroller = $('#vccontroller').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {'vcnama' : vcnama};
			var formdata = {'vcnama' : vcnama, 'vccontroller' : vccontroller};

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

</script>