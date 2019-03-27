<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">User Data</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<label>Data</label>
			<label class="pull-right">Status : <span class="label label-<?=$dataMain[0]->vcstatuswarna?>"><?=$dataMain[0]->vcstatus?></span></label>
		</div>

		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Code</label></td>
						<td><?=$dataMain[0]->vckode?></td>
					</tr>

					<tr>
						<td><label>Name</label></td>
						<td><?=$dataMain[0]->vcnama?></td>
					</tr>

					<tr>
						<td><label>Username</label></td>
						<td><?=$dataMain[0]->vcusername?></td>
					</tr>

					<tr>
						<td><label>Access Rights</label></td>
						<td><?=$dataMain[0]->vchakakses?></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="col-md-6">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<label>History</label>
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Date</th>
							<th>User</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($dataHistory as $history) {
						?>
						<tr>
							<td><?=date('d-m-Y H:i:s',strtotime($history->dtupdate))?></td>
							<td><?=$history->user?></td>
							<td><?=$history->aksi?></td>
						</tr>
						<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<a href="<?=base_url($controller . '/edit/' . $dataMain[0]->intid)?>" class="btn btn-warning"><i class="fa fa-pencil"></i>Edit</a>
	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i>Close</button>
</div>