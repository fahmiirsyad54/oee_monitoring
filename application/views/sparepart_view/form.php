<div class="row">
	<div class="col-md-12">
		<div class="box"> 
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div>

			<div class="panel-body">
				<div class="row">
					<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
						<div class="col-md-6">
							<div class="form-group">
								<label>Name</label>
								<input type="text" name="vcnama" placeholder="Name" class="form-control" id="vcnama" required value="<?=$vcnama?>" />
							</div>
							<div class="form-group">
								<label>Spesification</label>
								<input type="text" name="vcspesifikasi" placeholder="Specification" class="form-control" id="vcspesifikasi" required value="<?=$vcspesifikasi?>" />
							</div>
							<div class="form-group">
								<label>Part Number</label>
								<input type="text" name="vcpart" placeholder="Part Number" class="form-control" id="vcpart" required value="<?=$vcpart?>" />
							</div>
							<div class="form-group">
								<label>Type</label>
								<select name="intjenis" class="form-control" id="intjenis">
									<option data-nama="" value="0">-- Select Type --</option>
									<?php
										foreach ($listjenis as $opt) {
											$selected = ($intjenis == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
								<input type="hidden" name="vcunit" id="vcunit">
							</div>
							<div class="form-group">
								<label>Unit</label>
								<select name="intunit" class="form-control" id="intunit">
									<option data-nama="" value="0">-- Select Unit --</option>
									<?php
										foreach ($listunit as $opt) {
											$selected = ($intunit == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
								<input type="hidden" name="vcunit" id="vcunit">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
								<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i> Cancel</a>
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
		var intjumlahcell= $('#intjumlahcell').val();

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
	$('#intunit').change(function(){
		var vcunit    = $(this).children('option:selected').data('nama');
		$('#vcunit').val(vcunit);

	});
</script>