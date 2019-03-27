<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Add Employee</h4>
</div>
<div class="modal-body">
	<div class="row">
		<form method="POST" id="postkaryawan" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
			<div class="col-md-12">
				<div class="form-group">
					<label>Code</label>
					<input type="text" name="vckode" placeholder="Code" class="form-control" id="vckode3" required value="<?=$vckode?>" />
				</div>

				<div class="form-group">
					<label>Name</label>
					<input type="text" name="vcnama" placeholder="Name" class="form-control" id="vcnama3" required value="<?=$vcnama?>" />
				</div>

				<div class="form-group">
					<label>Position</label>
					<select name="intjabatan" class="form-control" id="intjabatan">
						<option data-nama="" value="0">-- Select Position --</option>
						<?php
							foreach ($listjabatan as $opt) {
								$selected = ($intjabatan == $opt->intid) ? 'selected' : '' ;
						?>
						<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
						<?php
							}
						?>
					</select>
				</div>

				<div class="form-group">
					<label>Building</label>
					<select name="intgedung" class="form-control" id="intgedung3">
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

			<div class="col-md-6">
				
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
					<a href="javascript:void(0);" onclick="simpanData3('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
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

	function simpanData3(action) {
		var vckode       = $('#vckode3').val();
		var vcnama       = $('#vcnama3').val();

		if (action == 'AddKaryawan') {
			var base_url = '<?=base_url('karyawan')?>';
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
							var base_url   = '<?=base_url($controller)?>';
							var vckode3    = $('#vckode3').val();
							var vcnama3    = $('#vcnama3').val();
							var intjabatan = $('#intjabatan').val();
							var intgedung3 = $('#intgedung3').val();

							var datapost = {
											'vckode'    :vckode3,
											'vcnama'    :vcnama3,
											'intjabatan':intjabatan,
											'intgedung' :intgedung3
										};
							$.ajax({
								url: base_url + '/aksi/AddKaryawan/0',
								method: "POST",
								data: datapost
							})
							.done(function( data ) {
								var jsonData     = JSON.parse(data);
								var datakaryawan = jsonData.datakaryawan;
								var intkaryawan  = jsonData.intkaryawan;
								var intjabatan   = jsonData.intjabatan;
								var datanamasama = jsonData.datanamasama;
								var html         = '<option value="0">-- Select Karyawan --</option>';
								
								for (var i = 0; i < datakaryawan.length; i++) {
									var _selected = (datakaryawan[i].intid == intkaryawan) ? 'selected' : '';
									var _vckode = (datakaryawan[i].vckode == '') ? '' : datakaryawan[i].vckode + ' - ';
									html += '<option ' + _selected + ' value="' + datakaryawan[i].intid + '">' + _vckode + datakaryawan[i].vcnama + '</option>';
								}


								if (intjabatan == 1) {
									$('#intleader').html(html);									
								} else if (intjabatan == 2) {
									$('#intmekanik').html(html);									
								} else if (intjabatan == 3) {
									$('#intoperator').html(html);									
								}
								if (intkaryawan == 0) {
									$('#alertModal').modal('show');
									for (var i = 0; i < datanamasama.length; i++) {
										var _html = '';
										_html += '<tr>';
										_html += '<td>'+datanamasama[i].vckode+'</td>';
										_html += '<td>'+datanamasama[i].vcnama+'</td>';
										_html += '<td></td>';
										_html += '<td><button class="btn btn-primary  btn-xs" type="button" onclick="replace('+datanamasama[i].intid+')">Replace</button></td>';
										_html += '</tr>';
									}
									$('#dtkaryawan').html(_html);
								} else {
									$('#modalKaryawan').modal('hide');
								}
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