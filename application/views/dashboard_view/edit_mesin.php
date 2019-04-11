<div class="row">
	<div class="col-md-12">
		<div class="box"> 
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div> 

			<div class="box-body">
				<div class="row">
					<form method="POST" id="postdata" action="<?=base_url('dashboard/aksi/' . $action . '/' . $intid)?>" enctype="multipart/form-data">
						<div class="col-md-6">
							<div class="form-group">
								<label>Group</label>
								<select name="intgroup" class="form-control select2" id="intgroup">
									<option data-nama="" value="0">-- Select Group --</option>
									<?php
										foreach ($listgroup as $opt) {
											$selected = ($intgroup == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group">
								<label>Code</label>
								<input type="text" name="vckode" placeholder="Code" class="form-control" id="vckode" required value="<?=$vckode?>" readonly/>
							</div>

							<div class="form-group">
								<label>Name</label>
								<input type="text" name="vcnama" placeholder="Name" class="form-control" id="vcnama" required value="<?=$vcnama?>" />
							</div>

							<div class="form-group">
								<label>Brand</label>
								<select name="intbrand" class="form-control select2" id="intbrand">
									<option data-nama="" value="0">-- Select Brand --</option>
									<?php
										foreach ($listbrand as $opt) {
											$selected = ($intbrand == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group">
								<label>Type</label>
								<input type="text" name="vcjenis" placeholder="Name" class="form-control" id="vcjenis" required value="<?=$vcjenis?>" />
							</div>

							<div class="form-group">
								<label>Serial</label>
								<input type="text" name="vcserial" placeholder="Name" class="form-control" id="vcserial" required value="<?=$vcserial?>" />
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Power</label>
								<input type="text" name="vcpower" placeholder="Name" class="form-control" id="vcpower" required value="<?=$vcpower?>" />
							</div>

							<div class="form-group">
								<label>Building</label>
								<select name="intgedung" class="form-control" id="intgedung">
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

							<div class="form-group">
								<label>Location</label>
								<input type="text" name="vclocation" placeholder="Location" class="form-control" id="vclocation" required value="<?=$vclocation?>" />
							</div>

							<div class="form-group">
								<label>Departure</label>
								<select name="intdeparture" class="form-control" id="intdeparture">
									<?php
										for ($i=2016; $i <= date('Y'); $i++) {
											$_intdeparture = ($intdeparture == 0) ? date('Y') : $intdeparture ;
											$selected = ($_intdeparture == $i) ? 'selected' : '' ;
									?>
									<option <?=$selected?> " value="<?=$i?>"><?=$i?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group" >
								<label>Machine Photo</label>
								<input type="file" class="form-control" name="vcgambar" >
								<input type="hidden" name="oldfile" value="<?=$vcgambar?>" >
								<img style="width: 500px" src="<?php echo base_url(); ?>upload/mesin/<?=$vcgambar?>">
								
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
								<a href="<?=base_url('dashboard/detail_list/' . $intgedung .'/' . $intcell)?>" class="btn btn-danger"><i class="fa fa-close"></i>Close</a>
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
	    $('.select2').select2()
	});

	function simpanData(action) {
		var vckode       = $('#vckode').val();
		var vcnama       = $('#vcnama').val();

		if (action == 'Add') {
			var base_url = '<?=base_url("dashboard")?>';
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

	$('#intgedung').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url("dashboard")?>';
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

	$('#intgroup').change(function(){
		var intid    = $(this).val();
		var _vckode  = $('#vckode').val();
		var _action  = '<?=$action?>';
		var base_url = '<?=base_url("dashboard")?>';
		$.ajax({
			url: base_url + '/getkode/' + intid + '/' + _action + '/' + _vckode,
			method: "GET"
		})
		.done(function( data ) {
			$('#vckode').val(data);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

</script>