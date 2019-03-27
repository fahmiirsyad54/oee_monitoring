<div class="table-responsive">
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th rowspan="2" style="vertical-align: middle; text-align: center;" width="2%">No</th>
				<th rowspan="2" style="vertical-align: middle; text-align: center;">Code</th>
				<th rowspan="2" style="vertical-align: middle; text-align: center;">Sparepart</th>
				<th rowspan="2" style="vertical-align: middle; text-align: center;">Specification</th>
				<th rowspan="2" style="vertical-align: middle; text-align: center;">Part Number</th>
				<th rowspan="2" style="vertical-align: middle; text-align: center;">Unit</th>
				<th colspan="4" style="vertical-align: middle; text-align: center;">Quantity</th>
			</tr>
			<tr>
				<th style="vertical-align: middle; text-align: center;">Awal</th>
				<th style="vertical-align: middle; text-align: center;">Masuk</th>
				<th style="vertical-align: middle; text-align: center;">Keluar</th>
				<th style="vertical-align: middle; text-align: center;">Akhir</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$jmldata = count($report);
				if ($jmldata === 0) {
			?>
				<tr>
					<td colspan="10" align="center">Data Not found</td>
				</tr>
			<?php
				} else {
					$no = $firstnum;
					foreach ($report as $data) {
			?>
				<tr>
					<td><?=++$no?></td>
					<td><?=$data->vckodesparepart?></td>
					<td><?=$data->vcsparepart?></td>
					<td><?=$data->vcspesifikasi?></td>
					<td><?=$data->vcpart?></td>
					<td><?=$data->vcunit?></td>
					<td><?=$data->awal?></td>
					<td><?=$data->masuk?></td>
					<td><?=$data->keluar?></td>
					<td><?=$data->akhir?></td>
				</tr>
			<?php
					}
				}
			?>
		</tbody>
	</table>
</div>