<div class="row">
  <div class="col-md-12">
    <div class="box">
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
                <li class="<?=$aktif?>"><a href="#<?=$data->vckode?>" data-toggle="tab" onclick="getchart(<?=$data->intid?>)">
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
                              <th style="text-align: center; vertical-align: middle;">Area</th>
                              <th style="text-align: center; vertical-align: middle;">Model</th>
                              <th style="text-align: center; vertical-align: middle;">Applicable</th>
                              <th style="text-align: center; vertical-align: middle;">Comply</th>
                              <th style="text-align: center; vertical-align: middle;">Score</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php
                              $terisi       = 0;
                              $totalpercent = 0;
                              foreach ($cell[$loop] as $datacell) {
                                if ($datacell['decpercentval'] > 0) {
                                  ++$terisi;
                                  $totalpercent = $totalpercent + $datacell['decpercentval'];
                                }
                            ?>
                            <tr>
                              <td><?=$datacell['vccell']?></td>
                              <td><?=$datacell['vcmodel']?></td>
                              <td><?=$datacell['intapplicable']?></td>
                              <td><?=$datacell['intcomply']?></td>
                              <td><?=$datacell['decpercent']?></td>
                            </tr>
                            <?php
                              }
                                $avgpercent = ($terisi > 0) ? ROUND(($totalpercent/$terisi),2) . '%' : '';
                            ?>
                          </tbody>
                          <tfoot>
                            <tr>
                              <th>Total</th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th><?=$avgpercent?></th>
                            </tr>
                          </tfoot>
                        </table>
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
              <div class="col-md-4">
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
              <div class="col-md-4">
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
              <div class="col-md-4">
                <select class="form-control intweek" name="intweek" id="intweek">
                  <?php
                    foreach ($listweek as $key => $value) {
                      $selected = ($key == $intweek) ? 'selected' : '';
                  ?>
                    <option <?=$selected?> value="<?=$key?>"><?=$value?></option>
                  <?php
                    }
                  ?>
                </select>
              </div>
            </div>
            <!-- <div id="chart"></div> -->
            <i class="fa fa-cog fa-spin fa-3x fa-fw hidden" id="loadsme"></i>
            <canvas id="smelevel2"></canvas>
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script type="text/javascript">
  $(function (){
    window.onload = getchart(<?=$gedung[0]->intid?>);
  });
</script>

<script type="text/javascript">
  var myChart;
  function getchart(intgedung){
    var base_url = "<?=base_url('audit_dashboard')?>";
    var intbulan = <?=$dtmonth?>;
    var inttahun = <?=$dttahun?>;
    var intweek = <?=$intweek?>;
    $('#loadsme').removeClass('hidden');
    $('#smelevel2').html('');
    
    $.ajax({
        url: base_url + '/grafikmonthsme2percell/' + intgedung + '/' + intbulan + '/' + inttahun + '/' + intweek,
        method: "GET"
    })
    .done(function( data ) {
        var result = JSON.parse(data);
        $('#loadsme').addClass('hidden');
        // destroy previous chart
        var meta = myChart && myChart.data && myChart.data.datasets[0]._meta;
         for (let i in meta) {
            if (meta[i].controller) meta[i].controller.chart.destroy();
         }
        myChart = new Chart(document.getElementById("smelevel2"), {
          type: 'bar',
          data: {
            labels: result.cell,
            // datasets: [result.sme2data, result.targetam]
            datasets: [result.sme2data]
          },
          options: {
            responsive: true,
            legend: { display: false, position: 'bottom' },
            title: {
              display: true,
              text: 'SME Level 2 Chart'
            },
            scales: {
                yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true,
                            steps: 10,
                            stepValue: 5,
                            min:0,
                            padding: 25
                        }
                    }]
            }
          }
        });
    })
    .fail(function( jqXHR, statusText ) {
        alert( "Request failed: " + jqXHR.status );
    });
  }

  $('.dtmonth').change(function(){
    var intbulan = $(this).val();
    var inttahun = $('.dttahun').val();
    var intweek  = $('.intweek').val();
    window.location = "<?=base_url('audit_dashboard/sme2/')?>"+intbulan+'/'+inttahun+'/'+intweek; 
  });

  $('.dttahun').change(function(){
    var inttahun = $(this).val();
    var intbulan = $('.dtmonth').val();
    var intweek  = $('.intweek').val();
    window.location = "<?=base_url('audit_dashboard/sme2/')?>"+intbulan+'/'+inttahun+'/'+intweek; 
  });

  $('.intweek').change(function(){
    var intweek = $(this).val();
    var intbulan = $('.dtmonth').val();
    var inttahun = $('.dttahun').val();
    window.location = "<?=base_url('audit_dashboard/sme2/')?>"+intbulan+'/'+inttahun+'/'+intweek; 
  });
</script>