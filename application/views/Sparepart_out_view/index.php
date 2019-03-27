<!-- bootstrap datepicker -->
<script src="<?=BASE_URL_PATH?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

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
					<div class="col-md-2 margin-bottom-10">
						<a href="<?=base_url($controller . '/add')?>" class="btn btn-primary"><i class="fa fa-plus"></i>Add New Data</a>
					</div>
					<form method="GET" action="<?=base_url($controller . '/view')?>">
					<div class="col-md-2">
						<div class="form-group">
							<input type="text" name="from" placeholder="From" class="form-control datepicker" id="from" value="<?=$from_input?>" />
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="text" name="to" placeholder="To" class="form-control datepicker" id="to" value="<?=$to_input?>" />
						</div>
					</div>
					
					<div class="col-md-1">
						<button class="btn btn-default btn-block" type="sbumit"><i class="fa fa-search"></i></button>
					</div>

					<div class="col-md-2">
						<a href="javascript:void(0)" onclick="exportexcel()" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</a>
					</div>

					</form>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Date Out</th>
								<th>Sparepart</th>
								<th>Specification</th>
								<th>Part Number</th>
								<th>Unit</th>
								<th>Quantity</th>
								<th>Remaks</th>
								<th>Status</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$jmldata = count($dataP);
								if ($jmldata === 0) {
							?>
								<tr>
									<td colspan="11" align="center">Data Not found</td>
								</tr>
							<?php
								} else {
									$no = $firstnum;
									foreach ($dataP as $data) {
										if ($data->intstatus == 0) {
											$colorstatus = 'success';
											$tooltiptext = 'Activate';
										} elseif ($data->intstatus == 1) {
											$colorstatus = 'danger';
											$tooltiptext = 'De-Activate';
										}
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=date('d M Y', strtotime($data->dtinout))?></td>
									<td><?=$data->vcsparepart?></td>
									<td><?=$data->vcspesifikasi?></td>
									<td><?=$data->vcpart?></td>
									<td><?=$data->vcunit?></td>
									<td><?=$data->decqtykeluar?></td>
									<td><?=$data->vcketerangan?></td>
									<td><span class="label label-<?=$data->vcstatuswarna?>"><?=$data->vcstatus?></span></td>
									<td>
										<a href="javascript:void(0);" onclick="detailData(<?=$data->intid?>)" class="btn btn-xs btn-info"><i class="fa fa-info"></i> Detail</a>

										<a href="<?=base_url($controller . '/edit/' . $data->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Edit</a>

										<a href="javascript:void(0);" onclick="ubahStatus(<?=$data->intid?>,<?=$data->intstatus?>)" class="btn btn-xs btn-<?=$colorstatus?>" data-toggle="tooltip" data-placement="bottom" title="<?=$tooltiptext?>">
											<i class="fa fa-gear"></i> Edit Status
										</a>
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
					echo pagination3($halaman, $link, $jmlpage, $from, $to, $intsparepart);
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
		var base_url = '<?=base_url($controller)?>';
		var from     = $('#from').val();
		var to       = $('#to').val();
		var key      = $('#key').val();
		window.open(base_url + '/exportexcel?from=' + from + '&to=' + to + '&intsparepart=0');
	}
</script>