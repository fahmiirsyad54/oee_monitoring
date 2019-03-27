<div class="row">
	<div class="col-md-12">
		<div class="box"> 
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div> 
 
			<div class="box-body">
				<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
					<div class="row">
						<div class="col-md-3 hidden">
							<div class="form-group">
								<label>Code</label>
								<input type="text" name="vckode" placeholder="Code" class="form-control" id="vckode" required value="<?=$vckode?>" readonly />
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Date</label>
								<input type="text" name="dttanggal" placeholder="Date order" class="form-control" id="datepicker" required value="<?=$dttanggal?>" />
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Building</label>
								<select name="intgedung" class="form-control select2" id="intgedung">
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

						<div class="col-md-2">
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
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Machine</label>
								<div class="input-group">
								      <span class="input-group-btn">
								        <a href="javascript:void(0);" onclick="addMesin()" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i></a>
								      </span>
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
						</div>
					</div>

					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Machine Type</label>
									<select name="intdtmesin_type" class="form-control" id="intdtmesin_type">
										<option data-nama="" value="0">-- Select Machine Type --</option>
										<?php
											foreach ($listdtmesin_type as $opt) {
												$selected = ($intdtmesin_type == $opt->intid) ? 'selected' : '' ;
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
								<label>Operator</label>
								<div class="input-group">
								      <span class="input-group-btn">
								        <a href="javascript:void(0);" onclick="addKaryawan(3)" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i></a>
								      </span>
										<select name="intoperator" class="form-control select2" id="intoperator">
											<option data-nama="" value="0">-- Select Operator --</option>
											<?php
												foreach ($listoperator as $opt) {
													$selected = ($intoperator == $opt->intid) ? 'selected' : '' ;
													$_vckode = ($opt->vckode == '') ? '' : $opt->vckode . ' - ';
											?>
											<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$_vckode . $opt->vcnama?></option>
											<?php
												}
											?>
										</select>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							
							<div class="form-group">
								<label>Leader</label>
								<div class="input-group">
								      <span class="input-group-btn">
								        <a href="javascript:void(0);" onclick="addKaryawan(1)" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i></a>
								      </span>
										<select name="intleader" class="form-control select2" id="intleader">
											<option data-nama="" value="0">-- Select Leader --</option>
											<?php
												foreach ($listleader as $opt) {
													$selected = ($intleader == $opt->intid) ? 'selected' : '' ;
													$_vckode  = ($opt->vckode == '') ? '' : $opt->vckode . ' - ';
											?>
											<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$_vckode . $opt->vcnama?></option>
											<?php
												}
											?>
										</select>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Type Downtime</label>
								<select name="inttype_downtime" class="form-control" id="inttype_downtime">
									<option data-nama="" value="0">-- Select Type Downtime --</option>
									<?php
										foreach ($listtype as $opt) {
											$selected = ($inttype_downtime == $opt->intid) ? 'selected' : '' ;
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
								<label>Type List</label>
								<select name="inttype_list" class="form-control select2" id="inttype_list">
									<option data-nama="" value="0">-- Select Type --</option>
									<?php
										foreach ($listtypelist as $opt) {
											$selected = ($inttype_list == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="col-md-1 dtimemesin">
							<div class="form-group">
								<label>Stop</label>
								<input type="text" name="dtstop" placeholder="Stop" class="form-control datetimepicker1" id="dtstop" required value="<?=$dtstop?>" onblur="dtstopchange()"/>
		                	</div>
						</div>

						<div class="col-md-1 dtimemesin">
							<div class="form-group">
				                <label>Start</label>
					            <input type="text" name="dtstart" placeholder="Start" class="form-control datetimepicker1" id="dtstart" required value="<?=$dtstart?>" onblur="dtstartchange()"/>
							</div>
						</div>

						<div class="col-md-1 dtimemesin">
							<div class="form-group">
				                <label>Finish</label>
					            <input type="text" name="dtfinish" placeholder="Finish" class="form-control datetimepicker1" id="dtfinish" required value="<?=$dtfinish?>" onblur="dtfinishchange()"/>
							</div>
						</div>

						<div class="col-md-1 dtimemesin">
							<div class="form-group">
				                <label>Run</label>
					            <input type="text" name="dtrun" placeholder="Run" class="form-control datetimepicker1" id="dtrun" required value="<?=$dtrun?>" onblur="dtrunchange()"/>
							</div>
						</div>

						<div class="col-md-2 dtimeproses hidden">
							<div class="form-group">
				                <label>Material Empty</label>
					            <input type="text" name="dtmaterialkosong" placeholder="Material Empty" class="form-control datetimepicker1" id="dtmaterialkosong" required value="<?=$dtmaterialkosong?>" onblur="dtmaterialemptychange()"/>
							</div>
						</div>

						<div class="col-md-2 dtimeproses hidden">
							<div class="form-group">
				                <label>Material Ready</label>
					            <input type="text" name="dtmaterialtersedia" placeholder="Material Ready" class="form-control datetimepicker1" id="dtmaterialtersedia" required value="<?=$dtmaterialtersedia?>" onblur="dtmaterialtersediachange()"/>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Problem</label>
								<input type="text" name="vcmasalah" placeholder="Problem" class="form-control" id="vcmasalah" required value="<?=$vcmasalah?>" />
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Solution</label>
								<input type="text" name="vcsolusi" placeholder="Solution" class="form-control" id="vcsolusi" required value="<?=$vcsolusi?>" />
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Mechanic</label>
								<div class="input-group">
								      <span class="input-group-btn">
								        <a href="javascript:void(0);" onclick="addKaryawan(2)" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i></a>
								      </span>
										<select name="intmekanik" class="form-control select2" id="intmekanik">
											<option data-nama="" value="0">-- Select Mechanic --</option>
											<?php
												foreach ($listmekanik as $opt) {
													$selected = ($intmekanik == $opt->intid) ? 'selected' : '' ;
													$_vckode  = ($opt->vckode == '') ? '' : $opt->vckode . ' - ';
											?>
											<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$_vckode . $opt->vcnama?></option>
											<?php
												}
											?>
										</select>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Sparepart</label>
								<select name="intsparepart" class="form-control select2" id="intsparepart">
									<option data-nama="" value="0">-- Select Sparepart --</option>
									<?php
										foreach ($listsparepart as $opt) {
											$selected = ($intsparepart == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="col-md-1">
							<div class="form-group">
								<label>Qty</label>
								<input type="text" name="intjumlahsparepart" placeholder="Qty" class="form-control" id="intjumlahsparepart" required value="<?=$intjumlahsparepart?>" />
							</div>
						</div>
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

<div id="modalMesin" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content" id="datamesin">
		</div>
	</div>
</div>

<div id="modalKaryawan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<!-- Modal content-->
		<div class="modal-content" id="datakaryawan">
		</div>
	</div>
</div>

<div id="alertModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Same Name</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped table-hover">
        	<thead>
        		<tr>
        			<th>NIK</th>
        			<th>Name</th>
        			<th>Position</th>
        			<th></th>
        		</tr>
        	</thead>
        	<tbody id="dtkaryawan">
        		
        	</tbody>
        </table><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button class="btn btn-success" onclick="createnew()">Create New</button>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2();

	    var inttype_downtime = $('#inttype_downtime').val();
	    if (inttype_downtime == 2) {
			$('.dtimeproses').removeClass('hidden');
			$('.dtimemesin').addClass('hidden');
			$('#dtstop').val('');
			$('#dtstart').val('');
			$('#dtfinish').val('');
			$('#dtrun').val('');
		} else {
			$('.dtimeproses').addClass('hidden');
			$('.dtimemesin').removeClass('hidden');
			$('#dtmaterialtersedia').val('');
			$('#dtmaterialkosong').val('');
		}
	});
	function simpanData(action) {
		var intgedung          = $('#intgedung').val();
		var intcell            = $('#intcell').val();
		var intmesin           = $('#intmesin').val();
		var inttype_downtime   = $('#inttype_downtime').val();
		var inttype_list       = $('#inttype_list').val();
		var dtstop             = $('#dtstop').val();
		var dtstart            = $('#dtstart').val();
		var dtfinish           = $('#dtfinish').val();
		var dtrun              = $('#dtrun').val();
		var dtmaterialkosong   = $('#dtmaterialkosong').val();
		var dtmaterialtersedia = $('#dtmaterialtersedia').val();
		var vcmasalah          = $('#vcmasalah').val();
		var vcsolusi           = $('#vcsolusi').val();
		var intmekanik         = $('#intmekanik').val();
		var intsparepart       = $('#intsparepart').val();
		var intjumlahsparepart = $('#intjumlahsparepart').val();


		if (action == 'Add') {
			var base_url     = '<?=base_url($controller)?>';
			if (inttype_downtime == 1) {
				if (intsparepart > 0) {
					var formrequired = {'intgedung': intgedung, 
										'intcell': intcell, 
										'intmesin': intmesin, 
										'inttype_downtime': inttype_downtime, 
										'inttype_list': inttype_list, 
										'dtstop': dtstop, 
										'dtstart': dtstart, 
										'dtfinish': dtfinish, 
										'dtrun': dtrun, 
										'vcmasalah': vcmasalah, 
										'vcsolusi': vcsolusi,
										'intjumlahsparepart': intjumlahsparepart
									}; 
				} else {
					var formrequired = {'intgedung': intgedung, 
										'intcell': intcell, 
										'intmesin': intmesin, 
										'inttype_downtime': inttype_downtime, 
										'inttype_list': inttype_list, 
										'dtstop': dtstop, 
										'dtstart': dtstart, 
										'dtfinish': dtfinish, 
										'dtrun': dtrun, 
										'vcmasalah': vcmasalah, 
										'vcsolusi': vcsolusi
									};
				}
			} else if (inttype_downtime == 2) {
				if (intsparepart > 0) {
					var formrequired = {'intgedung': intgedung, 
										'intcell': intcell, 
										'inttype_downtime': inttype_downtime, 
										'inttype_list': inttype_list, 
										'dtmaterialkosong': dtmaterialkosong, 
										'dtmaterialtersedia': dtmaterialtersedia,
										'vcmasalah': vcmasalah, 
										'vcsolusi': vcsolusi,
										'intjumlahsparepart': intjumlahsparepart
									}; 
				} else {
					var formrequired = {'intgedung': intgedung, 
										'intcell': intcell, 
										'inttype_downtime': inttype_downtime, 
										'inttype_list': inttype_list, 
										'dtmaterialkosong': dtmaterialkosong, 
										'dtmaterialtersedia': dtmaterialtersedia,
										'vcmasalah': vcmasalah, 
										'vcsolusi': vcsolusi
									}; 
				}
			} else {
				var formrequired = {'intgedung': intgedung, 
									'intcell': intcell, 
									'intmesin': intmesin, 
									'inttype_downtime': inttype_downtime, 
									'inttype_list': inttype_list,
									'vcmasalah': vcmasalah, 
									'vcsolusi': vcsolusi
								};
			}
			var formdata     = {};
			console.log(formrequired, intsparepart);
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
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vckode + ' - ' + jsonData[i].vcnama + '</option>';
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

	$('#inttype_downtime').change(function(){ 
		var intid    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_typelist_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Type --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
			}
			$('#inttype_list').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

		if (intid == 2) {
			$('.dtimeproses').removeClass('hidden');
			$('.dtimemesin').addClass('hidden');
			$('#dtstop').val('');
			$('#dtstart').val('');
			$('#dtfinish').val('');
			$('#dtrun').val('');
		} else {
			$('.dtimeproses').addClass('hidden');
			$('.dtimemesin').removeClass('hidden');
			$('#dtmaterialtersedia').val('');
			$('#dtmaterialkosong').val('');
		}
	});

	$(function (){
		$('#datepicker').datepicker({
	      	autoclose: true,
	      	format: 'dd-mm-yyyy'
    	})

		$('.datetimepicker1').datetimepicker({
			format: 'HH:mm'
		});
		
		$('#datetimepicker2').datetimepicker({
			format: 'HH:mm'
		});
	});

	function addMesin() {
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/addMesin',
			method: "GET"
		})
		.done(function( data ) {
			$('#datamesin').html(data);
			$('#modalMesin').modal('show');
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	$(function (resultmesin) {
	   var jsonData = JSON.parse(resultmesin);
	   html = jsonData.intid;

	   $('#intmesin').html(html);
	});

	function addKaryawan(intjabatan) {
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/addKaryawan/' + intjabatan,
			method: "GET"
		})
		.done(function( data ) {
			$('#datakaryawan').html(data);
			$('#modalKaryawan').modal('show');
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	$(function (dataresult2) {
	   	var jsonData     = JSON.parse(dataresult2);
		var datakaryawan = jsonData.datakaryawan;
		var intjabatan = jsonData.intjabatan;

		if (intjabatan == 1) {
			$('#intleader').html(html);									
		} else if (intjabatan == 2) {
			$('#intmekanik').html(html);									
		} else if (intjabatan == 3) {
			$('#intoperator').html(html);									
		}
	});

	function dtstopchange(){
		var _dtstop = $('#dtstop').val();
		$('#dtstart').val(_dtstop);
	}

	function dtstartchange(){
		var _dtstart = $('#dtstart').val();
		$('#dtfinish').val(_dtstart);
	}

	function dtfinishchange(){
		var _dtfinish = $('#dtfinish').val();
		$('#dtrun').val(_dtfinish);
	}

	function dtmaterialemptychange(){
		var _dtmaterialkosong = $('#dtmaterialkosong').val();
		$('#dtmaterialtersedia').val(_dtmaterialkosong);
	}

	function dtfinishchange(){
		var _dtfinish = $('#dtfinish').val();
		$('#dtrun').val(_dtfinish);
	}

	function replace(intid) {
		var base_url   = '<?=base_url($controller)?>';
		var vckode     = $('#vckode3').val();
		var vcnama     = $('#vcnama3').val();
		var intjabatan = $('#intjabatan').val();
		var intgedung  = $('#intgedung3').val();

		var datapost = {
						'vckode'    :vckode,
						'vcnama'    :vcnama,
						'intjabatan':intjabatan,
						'intgedung' :intgedung
					};
		$.ajax({
			url: base_url + '/aksi/EditKaryawan/'+intid,
			method: "POST",
			data: datapost
		})
		.done(function( data ) {
			var jsonData     = JSON.parse(data);
			var datakaryawan = jsonData.datakaryawan;
			var intkaryawan  = jsonData.intkaryawan;
			var intjabatan   = jsonData.intjabatan;
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
			$('#alertModal').modal('hide');
			$('#modalKaryawan').modal('hide');
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	function createnew(intid) {
		var base_url   = '<?=base_url($controller)?>';
		var vckode     = $('#vckode3').val();
		var vcnama     = $('#vcnama3').val();
		var intjabatan = $('#intjabatan').val();
		var intgedung  = $('#intgedung3').val();

		var datapost = {
						'vckode'    :vckode,
						'vcnama'    :vcnama,
						'intjabatan':intjabatan,
						'intgedung' :intgedung
					};
		$.ajax({
			url: base_url + '/addKaryawanBaru',
			method: "POST",
			data: datapost
		})
		.done(function( data ) {
			var jsonData     = JSON.parse(data);
			var datakaryawan = jsonData.datakaryawan;
			var intkaryawan  = jsonData.intkaryawan;
			var intjabatan   = jsonData.intjabatan;
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
			$('#alertModal').modal('hide');
			$('#modalKaryawan').modal('hide');
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}
</script>
