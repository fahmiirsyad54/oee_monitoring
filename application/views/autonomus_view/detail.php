<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Building Data</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<label>Data</label>
		</div>

		<div class="col-md-6">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td>Date</td>
						<td><?=date('d M Y',strtotime($dataMain[0]->dttanggal))?></td>
					</tr>
					<tr>
						<td>Building</td>
						<td><?=$dataMain[0]->vcgedung?></td>
					</tr>
					<tr>
						<td>Cell</td>
						<td><?=$dataMain[0]->vccell?></td>
					</tr>
					<tr>
						<td>Machine</td>
						<td><?=$dataMain[0]->vckodemesin . ' - ' . $dataMain[0]->vcmesin?></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="col-md-6">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td>Operator</td>
						<td><?=$dataMain[0]->vckodeoperator . ' - ' . $dataMain[0]->vcoperator?></td>
					</tr>
					<tr>
						<td>Form</td>
						<td><?=$dataMain[0]->intformterisi?></td>
					</tr>
					<tr>
						<td>Cell</td>
						<td><?=$dataMain[0]->intimplementasi?></td>
					</tr>
					<tr>
						<td>Remaks</td>
						<td><?=$dataMain[0]->vcketerangan?></td>
					</tr>
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