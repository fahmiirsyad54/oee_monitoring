<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-body">
				<?php
				 	$mesin =1;
				    if ($mesin == 1) {
				      $comelz = 'active';
				      $laser = '';
				    } elseif ($mesin == 2) {
				      $comelz = '';
				      $laser = 'active';
				    }
				  ?>
				<div class="row">
					<div class="col-md-12">
						<div class="box-body">
							<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
									<li class="<?=$comelz?>"><a href="#comelz" data-toggle="tab">Comelz</a></li>
			              			<li class="<?=$laser?>"><a href="#laser" data-toggle="tab">Laser</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane <?=$comelz?>" id="comelz">
										<div class="table-responsive">
											<table class="table table-bordered table-hover table-striped">
												<thead>
													<tr>
														<th>No</th>
														<th>Machine Name</th>
														<th>Building</th>
														<th>Sort OEE Building</th>
														<th>Sort All</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php
														$jmldata = count($dataP);
														if ($jmldata === 0) {
													?>
														<tr>
															<td colspan="6" align="center">Data Not found</td>
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
															<td><?=$data->vcnama?></td>
															<td><?=$data->vcgedung?></td>
															<td><?=$data->intsort?></td>
															<td><?=$data->intsortall?></td>
															<td><a href="<?=base_url($controller . '/edit/' . $data->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i>Edit</a></a></td>
														</tr>
													<?php
															}
														}
													?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane <?=$laser?>" id="laser">
										<div class="table-responsive">
											<table class="table table-bordered table-hover table-striped">
												<thead>
													<tr>
														<th>No</th>
														<th>Machine Name</th>
														<th>Building</th>
														<th>Sort OEE Building</th>
														<th>Sort All</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php
														$jmldata = count($dataP2);
														if ($jmldata === 0) {
													?>
														<tr>
															<td colspan="6" align="center">Data Not found</td>
														</tr>
													<?php
														} else {
															$no = $firstnum;
															foreach ($dataP2 as $data2) {
																if ($data2->intstatus == 0) {
																	$colorstatus = 'success';
																	$tooltiptext = 'Activate';
																} elseif ($data2->intstatus == 1) {
																	$colorstatus = 'danger';
																	$tooltiptext = 'De-Activate';
																}
													?>
														<tr>
															<td><?=++$no?></td>
															<td><?=$data2->vcnama?></td>
															<td><?=$data2->vcgedung?></td>
															<td><?=$data2->intsort?></td>
															<td><?=$data2->intsortall?></td>
															<td><a href="<?=base_url($controller . '/edit/' . $data2->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i>Edit</a></td>
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
				</div>
				<!-- <?php
					$link = base_url($controller . '/view');
					echo pagination($halaman, $link, $jmlpage, $keyword);
				?> -->
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