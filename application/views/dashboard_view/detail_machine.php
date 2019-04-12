<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<?php
						$gedung = $datatitle[0]->vcgedung;
						$cell = $datatitle[0]->vccell;
					?>
					<div class="col-md-11">
						<h4><?=$gedung?> - <?=$cell?></h4>
					</div>
					<div class="col-md-1 margin-bottom-10">
						<a href="<?=base_url('dashboard/machine')?>" class="btn btn-primary"> Close</a>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Code</th>
								<th>Name</th>
								<th>Brand</th>
								<th>Type</th>
								<th>Serial</th>
								<th>Location</th>
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
								<td colspan="8" align="center">Data Not found</td>
							</tr>
						<?php
							} else {

								foreach ($dataP as $data) {
									if ($data->intstatus == 0) {
										$colorstatus = 'success';
										$tooltiptext = 'Activate';
									} elseif ($data->intstatus == 1) {
										$colorstatus = 'danger';
										$tooltiptext = 'De-Activate';
									}

									if ($data->intgedung == 0 && $data->intcell == 0) {
										$vclocation = $data->vclocation;
									} else {
										$vclocation = ($data->intcell == 0) ? $data->vcgedung : $data->vccell;
									}
							?>
								<tr>
									<td><?=$data->vckode?></td>
									<td><?=$data->vcnama?></td>
									<td><?=$data->vcbrand?></td>
									<td><?=$data->vcjenis?></td>
									<td><?=$data->vcserial?></td>
									<td><?=$vclocation?></td>
									<td><span class="label label-<?=$data->vcstatuswarna?>"><?=$data->vcstatus?></span></td>
									<td>
										<a href="javascript:void(0);" onclick="detailData(<?=$data->intid?>)" class="btn btn-xs btn-info"><i class="fa fa-info"></i> Detail</a>

										<a href="<?=base_url('dashboard/edit/' . $data->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Edit</a>

										<!-- <a href="javascript:void(0);" onclick="ubahStatus(<?=$data->intid?>,<?=$data->intstatus?>)" class="btn btn-xs btn-<?=$colorstatus?>" data-toggle="tooltip" data-placement="bottom" title="<?=$tooltiptext?>">
											<i class="fa fa-gear"></i> Edit Status
										</a> -->
									</td>
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
<!-- Modal -->
<div id="modalDetail" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content" id="datadetail">
		</div>
	</div>
</div>
<!-- Modal Cetak -->

<script type="text/javascript">
	function detailData(intid) {
		var base_url = '<?=base_url("dashboard")?>';
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
		    var base_url = '<?=base_url("dashboard")?>';
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

	
</script>