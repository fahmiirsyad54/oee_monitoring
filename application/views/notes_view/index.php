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
						<div class="col-md-3">
							<div class="form-group">
								<input type="text" name="from" placeholder="From" class="form-control datepicker" id="from" value="" />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<input type="text" name="to" placeholder="To" class="form-control datepicker" id="to" value="" />
							</div>
						</div>
						<div class="col-md-3">
							<select name="key" class="form-control select2" id="intgedung">
								<option value="0">-- Select Building --</option>
								<?php
									foreach ($listgedung as $opt) {
								?>
								<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
								<?php
									}
								?>
							</select>
						</div>

						<div class="col-md-3">
							<button class="btn btn-default btn-block" type="sbumit"><i class="fa fa-search"> Search</i></button>
						</div>
					</form>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Date</th>
								<th>Id Machine</th>
								<th>Operator</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$jmldata = count($dataP);
								if ($jmldata === 0) {
							?>
								<tr>
									<td colspan="5" align="center">Data Not found</td>
								</tr>
							<?php
								} else {
									$no = $firstnum;
									foreach ($dataP as $data) {
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=date('d M Y H:i:s', strtotime($data->dttanggal))?></td>
									<td><?=$data->vcmesin?></td>
									<td><?=$data->vcoperator?></td>
									<td>
										<a href="javascript:void(0);" onclick="detailData(<?=$data->intid?>,<?=$data->intstatus?>)" class="btn btn-xs btn-info"><i class="fa fa-info"></i> Detail</a>
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
					echo pagination9($halaman, $link, $jmlpage, $from, $to, $intgedung, $keyword);
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
	function detailData(intid, intstatus) {
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/detail/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			$('#datadetail').html(data);
			$('#modalDetail').modal('show');
			$.ajax({
			url: base_url + '/aksi/detail/' + intid + '/' + intstatus,
			method: "GET"
			})
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