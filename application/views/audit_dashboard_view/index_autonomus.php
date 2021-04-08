<?php
  error_reporting(0);
?>

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
                              <th rowspan="2" style="text-align: center; vertical-align: middle;">Area</th>
                              <th rowspan="2" style="text-align: center; vertical-align: middle;">Machine Total</th>
                              <th colspan="2" style="text-align: center; vertical-align: middle;">Form</th>
                              <th colspan="2" style="text-align: center; vertical-align: middle;">Implementation</th>
                            </tr>

                            <tr>
                              <th style="text-align: center; vertical-align: middle;">Machine</th>
                              <th style="text-align: center; vertical-align: middle;">%</th>
                              <th style="text-align: center; vertical-align: middle;">Machine</th>
                              <th style="text-align: center; vertical-align: middle;">%</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php
                              $totalmesin    = 0;
                              $totaldisiplin = 0;
                              $totalpeduli   = 0;
                              foreach ($cell[$loop] as $datacell) {
                                $decjumlahmesin    = ($datacell[0]->decjumlahmesin > 0) ? $datacell[0]->decjumlahmesin : 0;
                                $decjumlahdisiplin = ($datacell[0]->decjumlahdisiplin > 0) ? $datacell[0]->decjumlahdisiplin : 0;
                                $persendisiplin    = ($datacell[0]->persendisiplin > 0) ? $datacell[0]->persendisiplin : 0;
                                $decjumlahpeduli   = ($datacell[0]->decjumlahpeduli > 0) ? $datacell[0]->decjumlahpeduli : 0;
                                $persenpeduli      = ($datacell[0]->persenpeduli > 0) ? $datacell[0]->persenpeduli : 0;
                                
                                $persenpeduliok    = round(($persenpeduli/$decjumlahmesin) * 100, 1) .'%';
                                $persendisiplinok  = round(($persendisiplin/$decjumlahmesin) * 100, 1) .'%';

                                $totalmesin    = $totalmesin + $decjumlahmesin;
                                $totaldisiplin = $totaldisiplin + $decjumlahdisiplin;
                                $totalpeduli   = $totalpeduli + $decjumlahpeduli;
                            ?>
                            <tr>
                              <td><?=$datacell[0]->vccell?></td>
                              <td><?=$decjumlahmesin?></td>
                              <td><?=$decjumlahdisiplin?></td>
                              <td><?=$persendisiplinok?></td>
                              <td><?=$decjumlahpeduli?></td>
                              <td><?=$persenpeduliok?></td>
                            </tr>
                            <?php
                              }
                                $totalpersendisiplin = ($totaldisiplin > 0) ? round(($totaldisiplin/$totalmesin) * 100, 1) . '%' : '';
                                $totalpersenpeduli   = ($totalpeduli > 0) ?round(($totalpeduli/$totalmesin) * 100, 1) . '%' : '';
                            ?>
                          </tbody>
                          <tfoot>
                            <tr>
                              <th>Total</th>
                              <th><?=$totalmesin?></th>
                              <th><?=$totaldisiplin?></th>
                              <th><?=$totalpersendisiplin?></th>
                              <th><?=$totalpeduli?></th>
                              <th><?=$totalpersenpeduli?></th>
                            </tr>
                            <tr>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
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
            <!-- <div id="chart"></div> -->
            <canvas id="amdisiplin"></canvas>
            <canvas id="ampeduli"></canvas>
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
    // var base_url = "<?=base_url('audit_dashboard')?>";
    // var intgedung = <?=$gedung[0]->intid?>;
    // var intbulan = <?=$dtmonth?>;
    // var inttahun = <?=$dttahun?>;
    // $.ajax({
    //     url: base_url + '/grafikmonthampercell/' + intgedung + '/' + intbulan + '/' + inttahun,
    //     method: "GET"
    // })
    // .done(function( data ) {
    //     var result = JSON.parse(data);
    //     new Chart(document.getElementById("amdisiplin"), {
    //       type: 'bar',
    //       data: {
    //         labels: result.cell,
    //         datasets: [result.disiplin]
    //       },
    //       options: {
    //         responsive: true,
    //         legend: { display: false, position: 'bottom' },
    //         title: {
    //           display: true,
    //           text: 'Discipline Of Filling  Check List'
    //         },
    //         scales: {
    //             yAxes: [{
    //                     display: true,
    //                     ticks: {
    //                         beginAtZero: true,
    //                         steps: 10,
    //                         stepValue: 5,
    //                         min:0
    //                     }
    //                 }]
    //         }
    //       }
    //     });

    //     new Chart(document.getElementById("ampeduli"), {
    //       type: 'bar',
    //       data: {
    //         labels: result.cell,
    //         datasets: [result.peduli]
    //       },
    //       options: {
    //         responsive: true,
    //         legend: { display: false, position: 'bottom' },
    //         title: {
    //           display: true,
    //           text: 'Cleaning Machine Concern'
    //         },
    //         scales: {
    //             yAxes: [{
    //                     display: true,
    //                     ticks: {
    //                         beginAtZero: true,
    //                         steps: 10,
    //                         stepValue: 5,
    //                         min:0
    //                     }
    //                 }]
    //         }
    //       }
    //     });
    // })
    // .fail(function( jqXHR, statusText ) {
    //     alert( "Request failed: " + jqXHR.status );
    // });
  });
</script>

<script type="text/javascript">
  function getchart(intgedung){
    var base_url = "<?=base_url('audit_dashboard')?>";
    var intbulan = <?=$dtmonth?>;
    var inttahun = <?=$dttahun?>;
    $.ajax({
        url: base_url + '/grafikmonthampercell/' + intgedung + '/' + intbulan + '/' + inttahun,
        method: "GET"
    })
    .done(function( data ) {
        var result = JSON.parse(data);
        console.log(result.disiplin);
        new Chart(document.getElementById("amdisiplin"), {
          type: 'bar',
          data: {
            labels: result.cell,
            datasets: [result.disiplin, result.targetam]
          },
          options: {
            responsive: true,
            legend: { display: false, position: 'bottom' },
            title: {
              display: true,
              text: 'Discipline Of Filling  Check List'
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

        new Chart(document.getElementById("ampeduli"), {
          type: 'bar',
          data: {
            labels: result.cell,
            datasets: [result.peduli, result.targetam]
          },
          options: {
            responsive: true,
            legend: { display: false, position: 'bottom' },
            title: {
              display: true,
              text: 'Cleaning Machine Concern'
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
    window.location = "<?=base_url('audit_dashboard/autonomus/')?>"+intbulan+'/'+inttahun; 
  });

  $('.dttahun').change(function(){
    var inttahun = $(this).val();
    var intbulan = $('.dtmonth').val();
    window.location = "<?=base_url('audit_dashboard/autonomus/')?>"+intbulan+'/'+inttahun; 
  });
</script>