<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<?=$title?>
			</div>

			<div class="box-body">
				<div class="row">
					<div class="col-md-2 margin-bottom-10">
						<a href="<?=base_url($controller . '/add')?>" class="btn btn-primary"><i class="fa fa-plus"></i>Add New Data</a>
					</div>

					<div class="col-md-5 col-md-offset-5">
						<form method="GET" action="<?=base_url($controller . '/view')?>" class="input-group">
					    	<input type="text" class="form-control" name="key" placeholder="Enter data for search">
					      	<span class="input-group-btn">
					        	<button class="btn btn-default" type="sbumit"><i class="fa fa-search"></i></button>
					      	</span>
						</form>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Name</th>
								<th>Downtime Type</th>
								<th>Machine Type</th>
								<th>Planned</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$jmldata = count($dataP);
								if ($jmldata === 0) {
							?>
								<tr>
									<td colspan="7" align="center">Data Not found</td>
								</tr>
							<?php
								} else {
									$no = $firstnum;
									foreach ($dataP as $data) {
										if ($data->intautocutting == 0) {
											$vcmachine = 'Non auto cutting';
										} elseif ($data->intautocutting == 1) {
											$vcmachine = 'Auto cutting';
										}

										if ($data->intplanned == 0) {
											$vcplan = 'Non Planned';
										} elseif ($data->intplanned == 1) {
											$vcplan = 'Planned';
										}
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=$data->vcnama?></td>
									<td><?=$data->vctype?></td>
									<td><?=$vcmachine?></td>
									<td><?=$vcplan?></td>
									<td>
										<!-- <a href="javascript:void(0);" onclick="detailData(<?=$data->intid?>)" class="btn btn-xs btn-info"><i class="fa fa-info"></i> Detail</a> -->

										<a href="<?=base_url($controller . '/edit/' . $data->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i>Edit</a>
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
					echo pagination($halaman, $link, $jmlpage, $keyword);
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
</script>