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
								<label>Name</label>
								<input type="text" name="vcnama" placeholder="Downtime Name" class="form-control" id="vcnama" required value="<?=$vcnama?>" />
							</div>

							<div class="form-group">
								<label>Downtime Type</label>
								<select name="inttype" class="form-control" id="inttype">
									<option data-nama="" value="0">-- Select Type --</option>
									<?php
										foreach ($listtype as $opt) {
											$selected = ($inttype == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group">
								<label>Machine Type</label>
								<select class="form-control" name="intautocutting" id="intautocutting" >
									<option data-nama="" value="">-- Select Machine Type --</option>
									<?php
										foreach ($listmachine as $key => $value) {
											$selected = ($key == $intautocutting) ? 'selected' : '';
									?>
									<option <?=$selected?> value="<?=$key?>"><?=$value?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group">
								<label>Used Autocutting Type</label> <br>
								<?php
									$comelzaktif = ($intcomelz == 1) ? 'checked' : '' ;
									$comelztidakaktif = ($intcomelz == 0) ? 'checked' : '' ;
									$laseraktif = ($intlaser == 1) ? 'checked' : '' ;
									$lasertidakaktif = ($intlaser == 0) ? 'checked' : '' ;
								?>
								
								<label>Comelz Machine </label> &nbsp;&nbsp;&nbsp;
								<label><input type="radio" name="intcomelz" value="1" <?=$comelzaktif?>> Yes</label> &nbsp;&nbsp;&nbsp;
								<label><input type="radio" name="intcomelz" value="0" <?=$comelztidakaktif?>> No</label>
								&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
								<label>Laser Machine </label> &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
								<label><input type="radio" name="intlaser" value="1" <?=$laseraktif?>> Yes</label> &nbsp;&nbsp;&nbsp;
								<label><input type="radio" name="intlaser" value="0" <?=$lasertidakaktif?>> No</label> <br>

							</div>

							<div class="form-group">
								<label>Planned Type</label>
								<select class="form-control" name="intplanned" id="intplanned" >
									<option data-nama="" value="">-- Select Machine Type --</option>
									<?php
										foreach ($listplanned as $key => $value) {
											$selected = ($key == $intplanned) ? 'selected' : '';
									?>
									<option <?=$selected?> value="<?=$key?>"><?=$value?></option>
									<?php
										}
									?>
								</select>
							</div>

							
							<br>
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
	function simpanData(action) {
		var vckode       = $('#vckode').val();
		var vcnama       = $('#vcnama').val();
		var vccontroller = $('#vccontroller').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {'vckode' : vckode, 'vcnama' : vcnama};
			var formdata = {'vckode' : vckode, 'vcnama' : vcnama, 'vccontroller' : vccontroller};

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

	$('#inthakakses').change(function(){
		var intid = $(this).val();
		var vcnama = $(this).children('option:selected').data('nama');

		$('#vchakakses').val(vcnama);
	});

</script>