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
					<form method="GET" action="<?=base_url($controller . '/view')?>">
						<div class="col-md-2">
							<div class="form-group">
								<input type="text" name="from" placeholder="From" class="form-control datepicker" id="from" value="<?=$from?>" />
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<input type="text" name="to" placeholder="To" class="form-control datepicker" id="to" value="<?=$to?>" />
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
							<select name="intmesin" class="form-control select2" id="intmesin" required>
								<option value="">-- All Machine --</option>
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

						<div class="col-md-1">
							<button class="btn btn-default btn-block" type="sbumit"><i class="fa fa-search"></i></button>
						</div>
						
						<div class="col-md-1">
							<a href="javascript:void();" onclick="exportexcel()" class="btn btn-success btn-block"><i class="fa fa-file-excel-o"></i></a>
						</div>
					</form>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Date</th>
								<th><a href="javascript:void(0)" onclick="getaf()" style="color: #333">Availability Factor</a></th>
								<th><a href="javascript:void(0)" onclick="getpf()" style="color: #333">Performance Factor</a></th>
								<th><a href="javascript:void(0)" onclick="getqf()" style="color: #333">Quality Factor</a></th>
								<th>OEE</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$jmldata = count($dataP);
								if ($jmldata === 0) {
							?>
								<tr>
									<td colspan="12" align="center">Data Not found</td>
								</tr>
							<?php
								} else {
									$no = 0;
									foreach ($dataP as $data) {
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=date('d M Y', strtotime($data['dttanggal']))?></td>
									<td><?=round($data['availabilityfactor'] * 100,2)?> %</td>
									<td><?=round($data['performancefactor'] * 100,2)?> %</td>
									<td><?=round($data['qualityfactor'] * 100,2)?> %</td>
									<td><?=round($data['oee'] * 100,2)?> %</td>
								</tr>
							<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>

				<?php
					// $link = base_url($controller . '/view');
					// echo pagination($halaman, $link, $jmlpage, $keyword);
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
		window.open(base_url + '/exportexcel?from=' + from + '&to=' + to + '&intgedung=' + intgedung + '&intmesin=' + intmesin + '&intshift=' + intshift);
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