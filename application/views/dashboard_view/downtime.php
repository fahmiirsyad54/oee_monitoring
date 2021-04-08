
  <?php
    error_reporting(0);
  ?>

<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <?=$title?>
      </div>

      <div class="box-body">
        <div class="row">
          <div class="col-md-7">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <?php
                  $click = 0;
                  foreach ($gedung as $data) {
                  $aktif = ($data->vckode == $gedung[0]->vckode) ? 'active' :'0' ;
                ?>
                <li class="<?=$aktif?>"><a href="#<?=$data->vckode?>" data-toggle="tab" onclick="getchart(<?=$click?>)">
                  <?=$data->vcnama?>
                </a></li>
                <?php
                    $click++;
                  }
                ?>
              </ul>
              <div class="tab-content">
                <?php
                  $loop = 0;
                  $forchart = 0;
                  foreach ($gedung as $tab) {
                    $forchart++;
                    $aktif = ($tab->vckode == $gedung[0]->vckode) ? 'active' :'0' ;
                 
                ?>
                <div class="tab-pane <?=$aktif?>" id="<?=$tab->vckode?>">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                          <thead>

                            <tr>
                              <th rowspan="2" style="text-align: center; vertical-align: middle;">Area</th>
                              <th colspan="2" style="text-align: center; vertical-align: middle;">Stitching</th>
                              <th colspan="2" style="text-align: center; vertical-align: middle;">Assembly</th>
                            </tr>

                            <tr>
                              <th style="text-align: center; vertical-align: middle;">DT Machine</th>
                              <th style="text-align: center; vertical-align: middle;">DT Process</th>
                              <th style="text-align: center; vertical-align: middle;">DT Machine</th>
                              <th style="text-align: center; vertical-align: middle;">DT Process</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php
                              $totalmachinestitching = 0;
                              $totalprocesstitching  = 0;
                              $totalmachineassembly  = 0;
                              $totalprocessassembly  = 0;

                              foreach ($cell[$loop] as $datacell) {

                                $totalmachinestitching = $totalmachinestitching + $datacell['dtmachinestitching'];
                                $totalprocesstitching  = $totalprocesstitching + $datacell['dtprocesstitching'];
                                $totalmachineassembly  = $totalmachineassembly + $datacell['dtmachineassembly'];
                                $totalprocessassembly  = $totalprocessassembly + $datacell['dtprocesassembly'];
                            ?>
                            <tr>
                              <td><?=$datacell['vccell']?></td>
                              <td><?=$datacell['dtmachinestitching']?></td>
                              <td><?=$datacell['dtprocesstitching']?></td>
                              <td><?=$datacell['dtmachineassembly']?></td>
                              <td><?=$datacell['dtprocesassembly']?></td>
                            </tr>
                            <?php
                              }
                            ?>
                          </tbody>
                          <tfoot>
                            <?php
                              $jamkerja                = 21 * 8 * 60;
                              $percentmachinestitching = ($totalmachinestitching > 0) ? ($totalmachinestitching / $jamkerja) * 100 : 0;
                              $percentprocesstitching  = ($totalprocesstitching > 0) ? ($totalprocesstitching / $jamkerja) * 100 : 0;
                              $percentmachineassembly  = ($totalmachineassembly > 0) ? ($totalmachineassembly / $jamkerja) * 100 : 0;
                              $percentprocessassembly  = ($totalprocessassembly > 0) ? ($totalprocessassembly / $jamkerja) * 100 : 0;
                            ?>
                            <tr>
                              <th>Total (Minutes)</th>
                              <th><?=$totalmachinestitching?></th>
                              <th><?=$totalprocesstitching?></th>
                              <th><?=$totalmachineassembly?></th>
                              <th><?=$totalprocessassembly?></th>
                            </tr>
                            <tr>
                              <th>%</th>
                              <th><?=round($percentmachinestitching,2)?> %</th>
                              <th><?=round($percentprocesstitching,2)?> %</th>
                              <th><?=round($percentmachineassembly,2)?> %</th>
                              <th><?=round($percentprocessassembly,2)?> %</th>
                            </tr>
                          </tfoot>
                        </table>

                        <input type="hidden" name="totalmachinestitching" id="totalmachinestitching<?=$loop?>" value="<?=$totalmachinestitching?>">
                        <input type="hidden" name="totalprocesstitching" id="totalprocesstitching<?=$loop?>" value="<?=$totalprocesstitching?>">
                        <input type="hidden" name="totalmachineassembly" id="totalmachineassembly<?=$loop?>" value="<?=$totalmachineassembly?>">
                        <input type="hidden" name="totalprocessassembly" id="totalprocessassembly<?=$loop?>" value="<?=$totalprocessassembly?>">
                      </div>
                    </div>
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

          <div class="col-md-5">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control dtmonth" name="dtmonth" id="dtmonth">
                  <?php
                    foreach ($bulan as $key => $value) {
                      $selected = ($key == $dtmonth) ? 'selected' : '';
                  ?>
                    <option <?=$selected?> value="<?=$key?>"><?=$value?></option>
                  <?php
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control dttahun" name="dttahun" id="dttahun">
                  <?php
                    foreach ($tahun as $key => $value) {
                      $selected = ($value == $dttahun) ? 'selected' : '';
                  ?>
                    <option <?=$selected?> value="<?=$value?>"><?=$value?></option>
                  <?php
                    }
                  ?>
                </select>
              </div>
            </div>
            <div id="chart"></div>
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var totalmachinestitching = $('#totalmachinestitching0').val();
  var totalprocesstitching  = $('#totalprocesstitching0').val();
  var totalmachineassembly  = $('#totalmachineassembly0').val();
  var totalprocessassembly  = $('#totalprocessassembly0').val();
  
  var chart = c3.generate({
      bindto: '#chart',
      data: {
          columns: [
              ['DT Machine Stitching', totalmachinestitching],
              ['DT Process Stitching', totalprocesstitching],
              ['DT Machine Assembly', totalmachineassembly],
              ['DT Process Assembly', totalprocessassembly]
          ],
          type: 'pie'
      },
      pie: {
          label: {
              format: function (value, ratio, id) {
                  return d3.format('')(value);
              }
          }
      }
  });
</script>

<script type="text/javascript">
  function getchart(intloop){
    var totalmachinestitching = $('#totalmachinestitching'+intloop).val();
    var totalprocesstitching  = $('#totalprocesstitching'+intloop).val();
    var totalmachineassembly  = $('#totalmachineassembly'+intloop).val();
    var totalprocessassembly  = $('#totalprocessassembly'+intloop).val();
    
    var chart = c3.generate({
        bindto: '#chart',
        data: {
            columns: [
                ['DT Machine Stitching', totalmachinestitching],
                ['DT Process Stitching', totalprocesstitching],
                ['DT Machine Assembly', totalmachineassembly],
                ['DT Process Assembly', totalprocessassembly]
            ],
            type: 'pie'
        },
        pie: {
            label: {
                format: function (value, ratio, id) {
                    return d3.format('')(value);
                }
            }
        }
    });
  }

  $('.dtmonth').change(function(){
    var intbulan = $(this).val();
    var inttahun = $('.dttahun').val();
    window.location = "<?=base_url('dashboard/downtime/')?>"+intbulan+'/'+inttahun; 
  });

  $('.dttahun').change(function(){
    var inttahun = $(this).val();
    var intbulan = $('.dtmonth').val();
    window.location = "<?=base_url('dashboard/downtime/')?>"+intbulan+'/'+inttahun; 
  });
</script>