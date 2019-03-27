<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Output Data</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<label>Data</label>
			<label class="pull-right">Status : <span class="label label-<?=$dataMain[0]->vcstatuswarna?>"><?=$dataMain[0]->vcstatus?></span></label>
		</div>

		<div class="col-md-6">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Date</label></td>
						<td><?=date('d M Y', strtotime($dataMain[0]->dttanggal))?></td>
					</tr>

					<tr>
						<td><label>Building</label></td>
						<td><?=$dataMain[0]->vcgedung?></td>
					</tr>

					<tr>
						<td><label>Cell</label></td>
						<td><?=$dataMain[0]->vccell?></td>
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
						<td><label>Leader</label></td>
						<td><?=$dataMain[0]->vcleader?></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="col-md-6">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Shift</label></td>
						<td><?='Shift ' . $dataMain[0]->intshift?></td>
					</tr>

					<tr>
						<td><label>Models</label></td>
						<td><?=$dataMain[0]->vcmodel?></td>
					</tr>

					<tr>
						<td><label>Component</label></td>
						<td><?=$dataMain[0]->vckomponen?></td>
					</tr>

					<tr>
						<td><label>Cycle Time</label></td>
						<td><?=$dataMain[0]->decct?></td>
					</tr>

					<tr>
						<td><label>Actual</label></td>
						<td><?=$dataMain[0]->intpasang?></td>
					</tr>

					<tr>
						<td><label>Reject</label></td>
						<td><?=$dataMain[0]->intreject?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<hr style="margin-top: 0px; margin-bottom: 10px;">

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
	<a href="<?=base_url($controller . '/edit/' . $dataMain[0]->intid)?>" class="btn btn-warning"><i class="fa fa-pencil"></i> Edit</a>
	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
</div>