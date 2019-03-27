<div class="table-responsive">
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th rowspan="2" style="vertical-align: middle; text-align: center;" width="2%">No</th>
				<th rowspan="2" style="vertical-align: middle; text-align: center;">Date</th>
				<th colspan="2" style="vertical-align: middle; text-align: center;">Quantity</th>
				<th rowspan="2" style="vertical-align: middle; text-align: center;">Remaks</th>
			</tr>
			<tr>
				<th style="vertical-align: middle; text-align: center;">Masuk</th>
				<th style="vertical-align: middle; text-align: center;">Keluar</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$jmldata = count($report);
				if ($jmldata === 0) {
			?>
				<tr>
					<td colspan="8" align="center">Data Not found</td>
				</tr>
			<?php
				} else {
					$no = $firstnum;
					foreach ($report as $data) {
			?>
				<tr>
					<td><?=++$no?></td>
					<td><?=date('d M Y', strtotime($data->dtinout))?></td>
					<td><?=$data->decqtymasuk?></td>
					<td><?=$data->decqtykeluar?></td>
					<td><?=$data->vcketerangan?></td>
				</tr>
			<?php
					}
				}
			?>
		</tbody>
	</table>
</div>