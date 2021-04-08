<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-md-2 margin-bottom-10">
						<a href="<?=base_url($controller . '/add')?>" class="btn btn-primary"><i class="fa fa-plus"></i>Add New Data</a>
					</div>

					<div class="col-md-10">
						<div class="row">
							<form method="GET" action="<?=base_url($controller . '/view')?>">
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

								<div class="col-md-3">
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
									<select class="form-control" name="intlogin" id="intlogin">
										<option value="0">-- All Log --</option>
										<?php
											foreach ($listlog as $key => $value) {
												$selected = ($key == $intlogin) ? 'selected' : '';
										?>
										<option <?=$selected?> value="<?=$key?>"><?=$value?></option>
										<?php
											}
										?>
									</select>
								</div>
								<div class="col-md-3">
									<input type="text" class="form-control" name="key" placeholder="Enter data for search">
								</div>
								<div class="col-md-2">
									<button class="btn btn-default btn-block" type="sbumit"><i class="fa fa-search"></i></button>
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
								<th>Id Machine</th>
								<th>Name Operator</th>
								<th>Shift</th>
								<th>Building</th>
								<th>Log</th>
								<th>Datetime</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$jmldata = count($dataP);
								if ($jmldata === 0) {
							?>
								<tr>
									<td colspan="4" align="center">Data Not found</td>
								</tr>
							<?php
								} else {
									$no = $firstnum;
									foreach ($dataP as $data) {
										if ($data->intlogin == 1) {
											$vclog = 'Login';
										} elseif ($data->intlogin == 2) {
											$vclog = 'Logout';
										}
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=$data->vcmesin?></td>
									<td><?=$data->vcoperator?></td>
									<td><?=$data->vcshift?></td>
									<td><?=$data->vcgedung?></td>
									<td><?=$vclog?></td>
									<td><?=$data->dtlogin?></td>
									<td>
										<a href="javascript:void(0);" onclick="detailData(<?=$data->intid?>)" class="btn btn-xs btn-info"><i class="fa fa-info"></i> Detail</a>
										<a href="<?=base_url($controller . '/edit/' . $data->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i>Edit</a>
										<a href="javascript:void(0);" onclick="hapusData(<?=$data->intid?>)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>
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
					echo pagination8($halaman, $link, $jmlpage, $keyword, $intshift, $intgedung, $intlogin);
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

	function hapusData(intid){
		swal({
			title: 'Data will Delete ?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Delete',
			cancelButtonText: 'Cancel'
		}).then((result) => {
		  if (result.value) {
		    var base_url = '<?=base_url($controller)?>';
			$.ajax({
				url: base_url + '/aksi/hapus/' + intid,
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