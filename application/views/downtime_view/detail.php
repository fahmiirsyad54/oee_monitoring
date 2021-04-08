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

		<div class="col-md-6">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Code</label></td>
						<td><?=$dataMain[0]->vckode?></td>
					</tr>

					<tr>
						<td><label>Date</label></td>
						<td><?=date('d M Y', strtotime($dataMain[0]->dttanggal))?></td>
					</tr>

					<tr>
						<td><label>Bulding</label></td>
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
						<td><label>Durasi</label></td>
						<td><?=$dataMain[0]->decjumlah_durasi?></td>
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
	</div>

	<hr style="margin-top: 0px; margin-bottom: 10px;">
	
	<?php
		foreach ($dataDowntime as $downtime) {
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tbody>
						<tr>
							<th>Downtime Type</th>
							<th>Downtime Name</th>
							<th>Mechanic</th>
							<th>Start</th>
							<th>Finish</th>
						</tr>

						<tr>
							<td><?=$downtime->vcdowntime_type?></td>
							<td><?=$downtime->vclist_type?></td>
							<td><?=$downtime->vcmekanik?></td>
							<td><?=$downtime->dtmulai?></td>
							<td><?=$downtime->dtselesai?></td>
						</tr>

						<tr>
							<th>Durasi</th>
							<th>Problem</th>
							<th>Solve</th>
							<th>Sparepart</th>
							<th>Qty</th>
						</tr>

						<tr>
							<td><?=$downtime->decdurasi?></td>
							<td><?=$downtime->vcmasalah?></td>
							<td><?=$downtime->vctindakan?></td>
							<td><?=$downtime->vcsparepart?></td>
							<td><?=$downtime->intjumlah?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<hr style="margin-top: 0px; margin-bottom: 10px;">
	<?php
		}
	?>

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
							<td><?=$history->pengguna?></td>
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