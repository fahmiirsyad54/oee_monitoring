<div class="row">
  <div class="col-md-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <?php
            foreach ($gedung as $data) {
            $aktif = ($data->vckode == $gedung[0]->vckode) ? 'active' :'0' ;
          ?>
          <li class="<?=$aktif?>"><a href="#<?=$data->vckode?>" data-toggle="tab">
            <?=$data->vcnama?>
          </a></li>
          <?php
            }
          ?>
        </ul>
      <div class="tab-content">
        <?php
          $loop = 0;
          foreach ($gedung as $tab) {
            $aktif = ($tab->vckode == $gedung[0]->vckode) ? 'active' :'0' ;
         
        ?>
        <div class="tab-pane <?=$aktif?>" id="<?=$tab->vckode?>">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
              <thead>
                <tr>
                  <th>Cell</th>
                  <th>Total Machine</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <?php
                foreach ($mesin[$loop] as $cell) {
                
              ?>
                <tr>
                  <td><?=$cell->vccell?></td>
                  <td><?=$cell->jumlah?></td>
                  <td><a href="<?=base_url('dashboard/detail_list/'.$tab->intid .'/'.$cell->intid)?>" class="btn btn-xs btn-primary"><i class="fa fa-list"></i> Detail List Machine</a></td>
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