<div class="row">
  <div class="col-md-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#eng" data-toggle="tab">Engeenering</a></li>
        <?php
          foreach ($gedung as $data) {
          $aktif = ($data->vckode == $gedung[0]->vckode) ? 'active' :'0' ;
        ?>
        <li><a href="#<?=$data->vckode?>" data-toggle="tab">
          <?=$data->vcnama?>
        </a></li>
        <?php
          }
        ?>
      </ul>
      <div class="tab-content">
      	<div class="tab-pane active" id="eng">
      		<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Code</th>
							<th>Sparepart</th>
							<th>Specification</th>
							<th>Part Number</th>
							<th>Unit</th>
							<th>Total Stock</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($stock as $data) {
						?> 
							<tr>
								<td><?=$data->vckodesparepart?></td>
								<td><?=$data->vcsparepart?></td>
								<td><?=$data->vcspesifikasi?></td>
								<td><?=$data->vcpart?></td>
								<td><?=$data->vcunit?></td>
								<td><?=$data->jumlah?></td>
							</tr>
						<?php
							}
						?>
					</tbody>
				</table>
			</div>
        </div>
        <?php
          $loop = 0;
          foreach ($gedung as $tab) {
            $aktif = ($tab->vckode == $gedung[0]->vckode) ? 'active' :'0' ;
         
        ?>
        <div class="tab-pane" id="<?=$tab->vckode?>">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
              	<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Code</th>
							<th>Sparepart</th>
							<th>Specification</th>
							<th>Part Number</th>
							<th>Unit</th>
							<th>Total Stock</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($stockpergedung[$loop] as $data) {
						?> 
							<tr>
								<td><?=$data->vckodesparepart?></td>
								<td><?=$data->vcsparepart?></td>
								<td><?=$data->vcspesifikasi?></td>
								<td><?=$data->vcpart?></td>
								<td><?=$data->vcunit?></td>
								<td><?=$data->jumlah?></td>
							</tr>
						<?php
							}
						?>
					</tbody>
				</table>
          </div>
        </div>

        <?php
        $loop++;
          }
        ?>
        
      </div>
      <!-- /.tab-content -->
    </div>
    <!-- nav-tabs-custom -->
  </div>
</div>

