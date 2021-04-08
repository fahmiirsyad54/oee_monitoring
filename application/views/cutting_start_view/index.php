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
					<!-- <div class="col-md-2 <?=$hideaction?>">
						<a href="<?=base_url($controller . '/add')?>" class="btn btn-primary"><i class="fa fa-plus"></i>Add New Data</a>
					</div> -->
					<div class="col-md-12">
						<div class="row">
							<form method="GET" action="<?=base_url($controller . '/view')?>">
								<div class="col-md-4">
									<div class="form-group">
										<input type="text" name="from" placeholder="Select date" class="form-control datepicker" id="from" value="<?=$from_input?>" />
									</div>
								</div>
								<!-- <div class="col-md-2">
									<div class="form-group">
										<input type="text" name="to" placeholder="To" class="form-control datepicker" id="to" value="<?=$to_input?>" />
									</div>
								</div> -->
								
								<div class="col-md-2">
									<select name="intgedung" class="form-control select2" id="intgedung">
										<option value="0">-- Select Building --</option>
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
									<select name="intshift" class="form-control select2" id="intshift" required>
										<option value="">-- Choose Shift --</option>
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

								<div class="col-md-1">
									<button class="btn btn-default btn-block" type="sbumit"><i class="fa fa-search"></i></button>
								</div>
								<div class="col-md-1">
									<a href="javascript:void();" onclick="exportexcel()" class="btn btn-success btn-block"><i class="fa fa-file-excel-o"></i></a>
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
								<th>Name</th>
								<th>Cell</th>
								<th>Shift</th>
								<th>Login Start</th>
								<th>Cutting Start</th>
								<th>Duration</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$jmldata = count($dataP);
								if ($jmldata === 0) {
							?>
								<tr>
									<td colspan="10" align="center">Data Not found</td>
								</tr>
							<?php
								} else {
									$no = $firstnum;
									$GAP = 0;
									foreach ($dataP as $data) {
										$durasi = abs(strtotime($data->dtmulai) - strtotime(date('H:i:s', strtotime($data->dtlogin))))  ;
										//$durasi = ($decdurasitemp * 60);
										//Untuk menghitung jumlah satuan jam  
										$jumlah_jam = floor($durasi/3600);
										//Untuk menghitung jumlah dalam satuan menit:
										$sisa = $durasi% 3600;
										$jumlah_menit = floor($sisa/60);
										//Untuk menghitung jumlah dalam satuan detik:
										$sisa = $sisa % 60;
										$jumlah_detik = floor($sisa/1);

										$GAP = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=date('d M Y', strtotime($data->dttanggal))?></td>
									<td><?=$data->vcmesin?></td>
									<td><?=$data->vcnamamesin?></td>
									<td><?=$data->vccell?></td>
									<td><?=$data->vcshift?></td>
									<td><?=date('H:i:s', strtotime($data->dtlogin))?></td>
									<td><?=$data->dtmulai?></td>
									<td><?=$GAP?></td>
									<td>
										<div class="<?=$hideaction?>">
											<!-- <a href="javascript:void(0);" onclick="detailData(<?=$data->intid?>)" class="btn btn-xs btn-info"><i class="fa fa-info"></i> Detail</a> -->

											<!-- <a href="<?=base_url($controller . '/edit/' . $data->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i>Edit</a> -->
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
					echo pagination5($halaman, $link, $jmlpage, $from, $from, $intmesin, $intshift);
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

	function exportexcel(){
		var base_url  = '<?=base_url($controller)?>';
		var from      = $('#from').val();
		var to        = $('#to').val();
		var intgedung = $('#intgedung').val();
		var intmesin  = $('#intmesin').val();
		var intshift  = $('#intshift').val();
		window.open(base_url + '/exportexcelnew?from=' + from + '&to=' + to + '&intmesin=' + intmesin + '&intgedung=' + intgedung + '&intshift=' + intshift);
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