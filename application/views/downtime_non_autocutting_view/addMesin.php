<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Add Machine</h4>
</div>
<div class="modal-body">
	<div class="row">
		<form method="POST" id="postmesin" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
			<div class="col-md-6">
				<div class="form-group">
					<label>Code</label>
					<input type="text" name="vckode" placeholder="Code" class="form-control" id="vckode2" required value="<?=$vckode?>" />
				</div>

				<div class="form-group">
					<label>Name</label>
					<input type="text" name="vcnama" placeholder="Name" class="form-control" id="vcnama2" required value="<?=$vcnama?>" />
				</div>

				<div class="form-group">
					<label>Brand</label> <br>
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
					<input type="text" name="vcjenis" placeholder="Type" class="form-control" id="vcjenis" required value="<?=$vcjenis?>" />
				</div>

				<div class="form-group">
					<label>Serial</label>
					<input type="text" name="vcserial" placeholder="Serial" class="form-control" id="vcserial" required value="<?=$vcserial?>" />
				</div>
			</div>

			<div class="col-md-6">

				<div class="form-group">
					<label>Power</label>
					<input type="text" name="vcpower" placeholder="Power" class="form-control" id="vcpower" required value="<?=$vcpower?>" />
				</div>

				<div class="form-group">
					<label>Building</label>
					<select name="intgedung" class="form-control intgedung" id="intgedung2">
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
					<label>Cell</label> <br>
					<select name="intcell" class="form-control select2" id="intcell2">
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
					<label>Departure</label>
					<select name="intdeparture" class="form-control" id="intdeparture">
						<?php
							for ($i=2015; $i <= date('Y'); $i++) {
								$_intdeparture = ($intdeparture == 0) ? date('Y') : $intdeparture ;
								$selected = ($_intdeparture == $i) ? 'selected' : '' ;
						?>
						<option <?=$selected?> value="<?=$i?>"><?=$i?></option>
						<?php
							}
						?>
					</select>
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
					<a href="javascript:void(0);" onclick="simpanData2('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});

	function simpanData2(action) {
		var vckode       = $('#vckode2').val();
		var vcnama       = $('#vcnama2').val();

		if (action == 'AddMesin') {
			var base_url = '<?=base_url('mesin')?>';
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
							var base_url     = '<?=base_url($controller)?>';
							var vckode2      = $('#vckode2').val();
							var vcnama2      = $('#vcnama2').val();
							var intbrand     = $('#intbrand').val();
							var intarea      = $('#intarea').val();
							var vcjenis      = $('#vcjenis').val();
							var vcserial     = $('#vcserial').val();
							var vcpower      = $('#vcpower').val();
							var intgedung2   = $('#intgedung2').val();
							var intcell2     = $('#intcell2').val();
							var intdeparture = $('#intdeparture').val();

							var datapost = {
											'vckode'      :vckode2,
											'vcnama'      :vcnama2,
											'intbrand'    :intbrand,
											'intarea'     :intarea,
											'vcjenis'     :vcjenis,
											'vcserial'    :vcserial,
											'vcpower'     :vcpower,
											'intgedung'   :intgedung2,
											'intcell'     :intcell2,
											'intdeparture':intdeparture
										};
							$.ajax({
								url: base_url + '/aksi/AddMesin/0',
								method: "POST",
								data: datapost
							})
							.done(function( data ) {
								var jsonData  = JSON.parse(data);
								var datamesin = jsonData.datamesin;
								var intmesin  = jsonData.intmesin;
								var html      = '<option value="0">-- Select Machine --</option>';
								
								for (var i = 0; i < datamesin.length; i++) {
									var _selected = (datamesin[i].intid == intmesin) ? 'selected' : '';
									html += '<option ' + _selected + ' value="' + datamesin[i].intid + '">' + datamesin[i].vckode + ' - ' + datamesin[i].vcnama + '</option>';
								}
								
								$('#intmesin').html(html);
								$('#modalMesin').modal('hide');
							})
							.fail(function( jqXHR, statusText ) {
								alert( "Request failed: " + jqXHR.status );
							});

						}
					})
				}
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
	}

	
	$('#intgedung2').change(function(){
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
			$('#intcell2').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});



</script>