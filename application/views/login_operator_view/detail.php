<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Login Operator Data</h4>
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
						<td><label>Id Machine</label></td>
						<td><?=$dataMain[0]->vcmesin?></td>
					</tr>

					<tr>
						<td><label>Operator Name</label></td>
						<td><?=$dataMain[0]->vcoperator?></td>
					</tr>

					<tr>
						<td><label>Shift</label></td>
						<td><?=$dataMain[0]->vcshift?></td>
					</tr>

					<tr>
						<td><label>Log</label></td>
						<td><?=$dataMain[0]->intlogin?></td>
					</tr>

					<tr>
						<td><label>Datetime</label></td>
						<td><?=$dataMain[0]->dtlogin?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<a href="<?=base_url($controller . '/edit/' . $dataMain[0]->intid)?>" class="btn btn-warning"><i class="fa fa-pencil"></i>Edit</a>
	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i>Close</button>
</div>