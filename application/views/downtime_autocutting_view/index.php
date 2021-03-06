<script>
  $(function () {
  	$('.datepicker').datepicker({
      autoclose: true
    })
  })  
</script>
<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-md-1 <?=$hideaction?>">
						<a href="<?=base_url($controller . '/add')?>" class="btn btn-primary"><i class="fa fa-plus"></i>Add New Data</a>
					</div>
					<div class="col-md-11">
						<div class="row">
							<form method="GET" action="<?=base_url($controller . '/view')?>">
								<div class="col-md-1">
									<div class="form-group">
										<input type="text" name="from" placeholder="From" class="form-control datepicker" id="from" value="<?=$from_input?>" />
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<input type="text" name="to" placeholder="To" class="form-control datepicker" id="to" value="<?=$to_input?>" />
									</div>
								</div>

								<div class="col-md-2">
									<select name="intgedung" class="form-control select2" id="intgedung">
										<option value="0">-- All Building --</option>
										<?php
											foreach ($listgedung as $opt) {
												$selected = ($opt->intid == $intgedung) ? 'selected' : '';
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>

								<div class="col-md-2">
									<select name="intmesin" class="form-control select2" id="intmesin">
										<option value="0">-- All Machine --</option>
										<?php
											foreach ($listmesin as $opt) {
												$selected = ($opt->intid == $intmesin) ? 'selected' : '';
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vckode . ' - ' . $opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>

								<div class="col-md-2">
									<select name="intshift" class="form-control select2" id="intshift">
										<option value="0">-- All Shift --</option>
										<?php
											foreach ($listshift as $opt) {
												$selected = ($opt->intid == $intshift) ? 'selected' : '';
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>

								<div class="col-md-2">
									<select name="intdowntime" class="form-control select2" id="intdowntime">
										<option value="0">-- All Downtime --</option>
										<?php
											foreach ($listdowntime as $opt) {
												$selected = ($opt->intid == $intdowntime) ? 'selected' : '';
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>


								<div class="col-md-1">
									<button class="btn btn-default btn-block" type="sbumit"><i class="fa fa-search"></i></button>
								</div>
								<div class="col-md-1">
									<a href="javascript:void();" onclick="selectexportexcel()" class="btn btn-success btn-block"><i class="fa fa-file-excel-o"></i></a>
								</div>
							</form>	
						</div>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Date</th>
								<th>Id Machine</th>
								<th>Cell</th>
								<th>Downtime</th>
								<th>Start</th>
								<th>Finish</th>
								<th>Sparepart</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$jmldata = count($dataP);
								if ($jmldata === 0) {
							?>
								<tr>
									<td colspan="9" align="center">Data Not found</td>
								</tr>
							<?php
								} else {
									$no = $firstnum;
									foreach ($dataP as $data) {
										$vcsparepart = ($data->vcsparepart == '') ? '' : $data->vcsparepart . ' - ' . $data->vcsparepartspek;

							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=date('d M Y', strtotime($data->dttanggal))?></td>
									<td><?=$data->vcmesin?></td>
									<td><?=$data->vccell?></td>
									<td><?=$data->vcdowntime?></td>
									<td><?=$data->dtmulai?></td>
									<td><?=$data->dtselesai?></td>
									<td><?=$vcsparepart?></td>
									<td>
										<div class="<?=$hideaction?>">
											<a href="javascript:void(0);" onclick="detailData(<?=$data->intid?>)" class="btn btn-xs btn-info"><i class="fa fa-info"></i> Detail</a>

											<a href="<?=base_url($controller . '/edit/' . $data->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i>Edit</a>
										</div>
									</td>
								</tr>
							<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>

				<?php
					$link = base_url($controller . '/view');
					echo pagination10($halaman, $link, $jmlpage, $from, $to, $intmesin, $intshift, $intdowntime);
				?>
			</div>

		</div>
	</div>
</div>
<!-- Modal -->
<div id="modalDetail" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content" id="datadetail">
		</div>
	</div>
</div>

<div id="modalExcel" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body">
				<button onclick="exportexcel()" class="btn btn-success btn-block">Verse Data List</button>
				<button onclick="exportexcelv2()" class="btn btn-success btn-block">Verse Total Data Count</button>
				<button onclick="exportexcelv3()" class="btn btn-success btn-block">Verse Total Data Duration</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});
	function detailData(intid) {
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/detail/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			$('#datadetail').html(data);
			$('#modalDetail').modal('show');
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	function ubahStatus(intid, intstatus){
		swal({
			title: 'Warning !',
			text: "Status will change",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Change',
			cancelButtonText: 'Cancel'
		}).then((result) => {
		  if (result.value) {
		    var base_url = '<?=base_url($controller)?>';
			$.ajax({
				url: base_url + '/aksi/ubahstatus/' + intid + '/' + intstatus,
				method: "GET"
			})
			.done(function( data ) {
				window.location.replace(base_url + "/view");
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		  }
		})
	}

	function selectexportexcel(){
		$('#modalExcel').modal('show');
	}

	function exportexcel(){
		var base_url    = '<?=base_url($controller)?>';
		var from        = $('#from').val();
		var to          = $('#to').val();
		var intgedung   = $('#intgedung').val();
		var intmesin    = $('#intmesin').val();
		var intshift    = $('#intshift').val();
		var intdowntime = $('#intdowntime').val();
		window.open(base_url + '/exportexcelnew?from=' + from + '&to=' + to + '&intmesin=' + intmesin + '&intgedung=' + intgedung + '&intshift=' + intshift + '&intdowntime=' + intdowntime);
	}

	function exportexcelv2(){
		var base_url    = '<?=base_url($controller)?>';
		var from        = $('#from').val();
		var to          = $('#to').val();
		var intgedung   = $('#intgedung').val();
		var intmesin    = $('#intmesin').val();
		var intshift    = $('#intshift').val();
		var intdowntime = $('#intdowntime').val();
		window.open(base_url + '/exportexcelnewv2?from=' + from + '&to=' + to + '&intmesin=' + intmesin + '&intgedung=' + intgedung + '&intshift=' + intshift + '&intdowntime=' + intdowntime);
	}

	function exportexcelv3(){
		var base_url    = '<?=base_url($controller)?>';
		var from        = $('#from').val();
		var to          = $('#to').val();
		var intgedung   = $('#intgedung').val();
		var intmesin    = $('#intmesin').val();
		var intshift    = $('#intshift').val();
		var intdowntime = $('#intdowntime').val();
		window.open(base_url + '/exportexcelnewv3?from=' + from + '&to=' + to + '&intmesin=' + intmesin + '&intgedung=' + intgedung + '&intshift=' + intshift + '&intdowntime=' + intdowntime);
	}

	$('#intgedung').change(function(){
		var base_url = '<?=base_url($controller)?>';
		var intid    = $(this).val();
		$.ajax({
			url: base_url + '/getmesinajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="">-- All Machine --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="'+jsonData[i].intid+'">'+jsonData[i].vckode+ ' - ' +jsonData[i].vcnama+'</option>';
			}
			$('#intmesin').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});
</script>