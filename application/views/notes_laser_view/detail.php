<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Notes Data</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<label>Data</label>
		</div>

		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Date</label></td>
						<td><?=date('d M Y H:i:s', strtotime($dataMain[0]->dttanggal))?></td>
					</tr>

					<tr>
						<td><label>Building</label></td>
						<td><?=$dataMain[0]->vcgedung?></td>
					</tr>

					<tr>
						<td><label>Machine</label></td>
						<td><?=$dataMain[0]->vcmesin?></td>
					</tr>

					<tr>
						<td><label>Operator</label></td>
						<td><?=$dataMain[0]->vcoperator?></td>
					</tr>

					<tr>
						<td><label>Notes</label></td>
						<td><?=$dataMain[0]->vcpesan?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<hr style="margin-top: 0px; margin-bottom: 10px;">
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
</div>