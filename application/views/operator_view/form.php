<div class="row">
	<div class="col-md-12">
		<div class="alert alert-info" style="border-radius: 0px; background-color: #357ca5 !important; border-color: #357ca5 !important">
			<span id="realtime"></span>
			<div class="pull-right">
				<span style="padding-right: 20px; ">ID Machine : <?=$this->session->vckodemesin?></span>
				<span style="padding-right: 20px; ">Operator : <?=$this->session->vckaryawan?></span>
				<a href="javascript:void(0)" data-toggle="modal" data-target="#modalPesan"><span style="padding-right: 20px; "><i class="fa fa-envelope"></i> Catatan</span></a>
				<a href="<?=base_url('akses/logoutop')?>"><i class="fa fa-sign-out"></i> Log Out</a>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					Downtime
				</div>

				<div class="box-body">
					<form method="POST" id="savedowntime" action="<?=base_url($controller . '/add_downtime')?>">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Downtime Problem</label>
									<select name="inttype_list" class="form-control select2" id="inttype_list">
										<option data-inttype_downtime="0" value="0">-- Select Downtime Problem --</option>
										<?php
											foreach ($listdowntime as $opt) {
										?>
										<option data-inttype_downtime="<?=$opt->intheader?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
									<input type="hidden" name="inttype_downtime" id="inttype_downtime">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<a href="javascript:void()" onclick="start()" id="btnstart" class="btn btn-primary btn-block btn-lg margin-top-15">Start</a>
									<input type="hidden" name="dtmulai" id="dtmulai">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<a href="javascript:void()" id="btnfinish" class="btn btn-primary btn-block btn-lg margin-top-15">Finish</a>
									<input type="hidden" name="dtselesai" id="dtselesai">
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label>Mechanics</label>
									<select name="intmekanik" class="form-control select2" id="intmekanik">
										<option data-nama="" value="0">-- Select Mechanics --</option>
										<?php
											foreach ($listmekanik as $opt) {
												// $selected = ($inttype_list == $opt->intid) ? 'selected' : '' ;
										?>
										<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Sparepart</label>
									<select name="intsparepart" class="form-control select2" id="intsparepart">
										<option data-nama="" value="0">-- Select Sparepart --</option>
										<?php
											foreach ($listsparepart as $opt) {
										?>
										<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Qty</label>
									<input type="number" name="intjumlah" id="intjumlah" class="form-control" />
								</div>
							</div>

							<div class="col-md-3">
								<a href="javascript:void(0)" onclick="simpandowntime()" class="btn btn-success btn-block margin-top-25"><i class="fa fa-save"></i> Save</a>
							</div>

							<div class="col-md-3">
								<a href="javascript:void(0)" onclick="resetdowntime()" class="btn btn-danger btn-block margin-top-25"><i class="fa fa-refresh"></i> Reset</a>
							</div>
						</div>
					</form>

					<hr>

					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>Downtime</th>
									<th>Start</th>
									<th>Finish</th>
									<th>Mechanics</th>
									<th>Sparepart</th>
									<th>Qty</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if (count($datadowntime) == 0) {
								?>
								<tr>
									<td colspan="6" align="center">Data Not Found</td>
								</tr>
								<?php
									} else {
										foreach ($datadowntime as $downtime) {
								?>
								<tr>
									<td><?=$downtime->vcdowntime?></td>
									<td><?=$downtime->dtmulai?></td>
									<td><?=$downtime->dtselesai?></td>
									<td><?=$downtime->vcmekanik?></td>
									<td><?=$downtime->vcsparepart?></td>
									<td><?=$downtime->intjumlah?></td>
								</tr>
								<?php
									}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					Output
				</div>

				<div class="box-body">
					<form method="POST" id="saveoutput" action="<?=base_url($controller . '/add_output')?>">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Model</label>
									<select name="intmodel" class="form-control select2" id="intmodel">
										<option data-nama="" value="0">-- Select Model --</option>
										<?php
											foreach ($listmodels as $opt) {
										?>
										<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label>Component</label>
									<select name="intkomponen" class="form-control select2" id="intkomponen">
										<option data-ct="0" value="0">-- Select Component --</option>
									</select>
									<input type="hidden" name="decct" id="decct" class="form-control">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Pairs</label>
									<input type="number" name="intpasang" class="form-control">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Reject</label>
									<input type="number" name="intreject" class="form-control">
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-2 col-md-offset-8">
								<a href="javascript:void(0)" onclick="simpanoutput()" class="btn btn-success btn-block"><i class="fa fa-save"></i> Save</a>
							</div>

							<div class="col-md-2">
								<a href="javascript:void(0)" class="btn btn-danger btn-block"><i class="fa fa-refresh"></i> Reset</a>
							</div>
						</div>
					</form>

					<hr>

					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>Model</th>
									<th>Komponen</th>
									<th>Pairs</th>
									<th>Reject</th
								</tr>
							</thead>
							<tbody>
								<?php
									if (count($dataoutput) == 0) {
								?>
								<tr>
									<td colspan="6" align="center">Data Not Found</td>
								</tr>
								<?php
									} else {
										foreach ($dataoutput as $output) {
								?>
								<tr>
									<td><?=$output->vcmodel?></td>
									<td><?=$output->vckomponen?></td>
									<td><?=$output->intpasang?></td>
									<td><?=$output->intreject?></td>
								</tr>
								<?php
									}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div id="modalPesan" class="modal fade" role="dialog">
	<div class="modal-dialog ">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Note</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<textarea class="form-control" name="vcpesan" placeholder="Tambahkan catatan di sini" rows="5"></textarea>
					</div>
					<div class="col-md-12">
						<button class="btn btn-success margin-top-15" type="submit"><i class="fa fa-save"></i> Save</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
	    $('.select2').select2()
	});

	$('#inttype_list').change(function(){
		var inttype_downtime = $(this).children('option:selected').data('inttype_downtime');
		$('#inttype_downtime').val(inttype_downtime);
	});

	function start(){
		var datenow = new Date();
		var minutes = datenow.getMinutes();
		if (minutes < 10) { minutes = '0' + minutes}
		var time    = datenow.getHours() + ":" + minutes;
		$('#btnstart').text(time);
		$('#btnstart').removeAttr('onclick');
		$('#dtmulai').val(time);
		$('#btnfinish').click(finish);
	}

	function finish(){
		var datenow = new Date();
		var minutes = datenow.getMinutes();
		if (minutes < 10) { minutes = '0' + minutes}
		var time    = datenow.getHours() + ":" + minutes;
		$('#btnfinish').text(time);
		$('#btnfinish').removeAttr('onclick');
		$('#dtselesai').val(time);
	}

	function simpandowntime(){
		var inttype_list     = $('#inttype_list').val();
		var dtmulai          = $('#dtmulai').val();
		var dtselesai        = $('#dtselesai').val();
		var intsparepart     = $('#intsparepart').val();
		var intjumlah        = $('#intjumlah').val();

		if (inttype_list == 0) {
			swal({
					type: 'error',
					title: 'Downtime Problem Belum Terisi'
				});
		} else if (dtmulai == '') {
			swal({
					type: 'error',
					title: 'Waktu awal belum ditentukan'
				});
		} else if (dtmulai == '') {
			swal({
					type: 'error',
					title: 'Waktu awal belum ditentukan'
				});
		} else if (dtselesai == '') {
			swal({
					type: 'error',
					title: 'Waktu akhir belum ditentukan'
				});
		} else if (intsparepart > 0 && (intjumlah == '' || intjumlah == 0)) {
			swal({
					type: 'error',
					title: 'Masukkan jumlah sparepart'
				});
		} else {
			$('#savedowntime').submit();
		}

	}

	function resetdowntime(){
		$('#inttype_list').val(0);
		$('#btnstart').text('Start');
		$('#btnstart').click(start);
		$('#btnfinish').text('Finish');
		$('#btnfinish').removeAttr('onclick');
		$('#dtmulai').val('');
		$('#dtselesai').val('');
		$('#intmekanik').val(0);
		$('#intsparepart').val(0);
		$('#intjumlah').val('');
		$('#inttype_list').select2().select2('val', '');
		$('#intmekanik').select2().select2('val', '');
		$('#intsparepart').select2().select2('val', '');

	}

	$('#intmodel').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url("operator")?>';
		$.ajax({
			url: base_url + '/getkomponen_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option data-nama="" value="0">-- Select Component --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option data-ct="' + jsonData[i].deccycle_time + '" value="' + jsonData[i].intkomponen + '">' + jsonData[i].vckomponen + '</option>'
			}

			$('#intkomponen').html(html)
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	function simpanoutput(){
		$('#saveoutput').submit();
	}

	$('#intkomponen').change(function(){
		var intid    = $(this).val();
		var decct    = $(this).children('option:selected').data('ct');

		$('#decct').val(decct)
	});

    function date_time(id){
        date = new Date;
        year = date.getFullYear();
        month = date.getMonth();
        months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
        d = date.getDate();
        day = date.getDay();
        days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        h = date.getHours();
        if(h<10)
        {
                h = "0"+h;
        }
        m = date.getMinutes();
        if(m<10)
        {
                m = "0"+m;
        }
        s = date.getSeconds();
        if(s<10)
        {
                s = "0"+s;
        }
        result = ''+days[day]+', '+d+' '+months[month]+' '+year+' '+h+':'+m+':'+s;
        document.getElementById(id).innerHTML = result;
        setTimeout('date_time("'+id+'");','1000');
        return true;
    }

    window.onload = date_time('realtime');
</script>