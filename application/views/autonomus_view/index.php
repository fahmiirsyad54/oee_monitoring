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
						<select class="form-control" name="int1" id="intbulan">
							<?php
								foreach ($listbulan as $key => $value) {
									$selected = ($key == $intbulan) ? 'selected' : '';
							?>
							<option <?=$selected?> value="<?=$key?>"><?=$value?></option>
							<?php
								}
							?>
						</select>
					</div>

					<div class="col-md-2">
						<select class="form-control" name="int2" id="inttahun">
							<?php
								foreach ($listtahun as $key => $value) {
									$selected = ($value == $inttahun) ? 'selected' : '';
							?>
							<option <?=$selected?> value="<?=$value?>"><?=$value?></option>
							<?php
								}
							?>
						</select>
					</div>

					<div class="col-md-2">
						<select class="form-control" name="int3" id="intgedung">
							<option value="">-- All Building --</option>
							<?php
								foreach ($listgedung as $opt) {
									$selected = ($opt->intid == $intgedung) ? 'selected' : '';
							?>
							<option <?=$selected?> value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
							<?php
								}
							?>
						</select>
					</div>

					<div class="col-md-2">
						<select class="form-control" name="int4" id="intcell">
							<option value="">-- All Cell --</option>
							<?php
								foreach ($listcell as $opt) {
									$selected = ($opt->intid == $intcell) ? 'selected' : '';
							?>
							<option <?=$selected?> value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
							<?php
								}
							?>
						</select>
					</div>

					<div class="col-md-1">
					    <button class="btn btn-default btn-block" type="sbumit"><i class="fa fa-search"></i></button>
					</div>

					<div class="col-md-1">
					    <a href="javascript:void(0);" onclick="exportexcel()" class="btn btn-success btn-block"><i class="fa fa-file-excel-o"></i></a>
					</div>
					</form>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Date</th>
								<th>Building</th>
								<th>Cell</th>
								<th>Machine</th>
								<th>Operator</th>
								<th>Form</th>
								<th>Implementation</th>
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
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=date('d M Y', strtotime($data->dttanggal))?></td>
									<td><?=$data->vcgedung?></td>
									<td><?=$data->vccell?></td>
									<td><?=$data->vckodemesin . ' - ' . $data->vcmesin?></td>
									<td><?=$data->vcoperator?></td>
									<td><?=$data->intformterisi?></td>
									<td><?=$data->intimplementasi?></td>
									<td>
										<a href="javascript:void(0);" onclick="detailData(<?=$data->intid?>)" class="btn btn-xs btn-info"><i class="fa fa-info"></i> Detail</a>

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
					echo pagination7($halaman, $link, $jmlpage, $intbulan, $inttahun, $intgedung, $intcell);
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
		var base_url  = '<?=base_url($controller)?>';
		var intbulan  = $('#intbulan').val();
		var inttahun  = $('#inttahun').val();
		var intgedung = $('#intgedung').val();
		var intcell   = $('#intcell').val();
		window.open(base_url + '/exportexcel?intbulan=' + intbulan + '&inttahun=' + inttahun + '&intgedung=' + intgedung + '&intcell=' + intcell);
	}

	$('#intgedung').change(function(){
		var base_url = '<?=base_url($controller)?>';
		var intid    = $(this).val();
		$.ajax({
			url: base_url + '/getcellajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="">-- All Cell --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="'+jsonData[i].intid+'">'+jsonData[i].vcnama+'</option>';
			}
			$('#intcell').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});
</script>