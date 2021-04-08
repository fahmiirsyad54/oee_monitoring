<div class="row">
	<div class="col-lg-6 col-lg-12">  
    <div class="info-box" style="background: #333;">
      <span class="info-box-icon bg-red" style="background: #b30000;">
          <i class="fa fa-gear"></i>
      </span>

      <div class="info-box-content" style="background: #333; color: #ffffff;">
        <a href="<?=base_url('audit_dashboard/autonomus')?>" style="color: #ffffff;">
          <span class="info-box-text">Autonomus</span>
          <span class="info-box-number">Discipline : <?=round($autonomus_disiplin,1)?> %</span>
          <span class="info-box-number">Concern &nbsp;&nbsp;&nbsp;: <?=round($autonomus_peduli,1)?> %</span>
        </a>
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-lg-12">  
    <div class="info-box" style="background: #333;">
      <span class="info-box-icon bg-blue"><i class="fa fa-hourglass-half"></i></span>

      <div class="info-box-content">
        <a href="<?=base_url('audit_dashboard/sme2')?>" style="color: #ffffff;">
          <span class="info-box-text">SME Level 2</span>
          <span class="info-box-number"><?=$smelevel2?></span>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Autonomus Maintenance Chart</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body chart-responsive">
        <canvas id="amdisiplin" height="150" width="auto"></canvas> 
        <!-- <div class="chart" id="amdisiplin" style="height: 150px;"></div><br> -->
        <!-- <div class="chart" id="ampeduli" style="height: 150px;"></div> -->
        <canvas id="ampeduli" height="150" width="auto"></canvas> 
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">SME Level 2 Chart</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body chart-responsive">
        <!-- <div class="chart" id="sme2" style="height: 350px;"></div> -->
        <canvas id="sme2" height="300" width="auto"></canvas> 
      </div>
    </div>
  </div>
  

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<!-- <script type="text/javascript" src="<?=BASE_URL_PATH?>assets/plugins/chartjs/Chart.min.js"></script> -->
<script type="text/javascript" src="<?=BASE_URL_PATH?>assets/plugins/toastr/toastr.js"></script>

<script>
  $(function (){
    var base_url = "<?=base_url('audit_dashboard')?>";
    // AM Chart
    $.ajax({
        url: base_url + '/grafikmontham',
        method: "GET"
    })
    .done(function( data ) {
        var result = JSON.parse(data);
        new Chart(document.getElementById("amdisiplin"), {
          type: 'bar',
          data: {
            labels: result.bulan,
            datasets: result.amdisiplin
          },
          options: {
            responsive: true,
            legend: { display: true, position: 'bottom' },
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
                            min:0
                        }
                    }]
            }
          }
        });

        new Chart(document.getElementById("ampeduli"), {
          type: 'bar',
          data: {
            labels: result.bulan,
            datasets: result.ampeduli
          },
          options: {
            responsive: true,
            legend: { display: true, position: 'bottom' },
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
                            min:0
                        }
                    }]
            }
          }
        });
    })
    .fail(function( jqXHR, statusText ) {
        alert( "Request failed: " + jqXHR.status );
    });

    // SME2 Chart
    $.ajax({
        url: base_url + '/grafikmonthsme2',
        method: "GET"
    })
    .done(function( data ) {
        var result = JSON.parse(data);
        new Chart(document.getElementById("sme2"), {
          type: 'bar',
          data: {
            labels: result.bulan,
            datasets: result.week
          },
          options: {
            responsive: true,
            legend: { display: true, position: 'bottom' },
            title: {
              display: false,
              text: 'Discipline Of Filling  Check List'
            },
            scales: {
                yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true,
                            steps: 10,
                            stepValue: 5,
                            min:0
                        }
                    }]
            }
          }
        });
    })
    .fail(function( jqXHR, statusText ) {
        alert( "Request failed: " + jqXHR.status );
    });
  });

  // new Chart(document.getElementById("sme2"), {
  //         type: 'bar',
  //         data: {
  //           labels: ['Sept','Oct','Nov','Dec','Jan','Feb'],
  //           datasets: [
  //         {
  //           label: "Week 1",
  //           backgroundColor : '#3399ff',
  //           data: [89, 86, 83, 89, 86, 83]
  //         },
  //         {
  //           label: "Week 2",
  //           backgroundColor: '#33cc33',
  //           data: [89, 86, 83, 89, 86, 83]
  //         },
  //         {
  //           label: "Week 3",
  //           backgroundColor : '#ff4d4d',
  //           data: [89, 86, 83, 89, 86, 83]
  //         },
  //         {
  //           label: "Week 4",
  //           backgroundColor : '#ffcc00',
  //           data: [89, 86, 83, 89, 86, 83]
  //         }
  //       ]
  //         },
  //         options: {
  //           responsive: true,
  //           legend: { display: true, position: 'bottom' },
  //           title: {
  //             display: true,
  //             text: 'Cleaning Machine Concern'
  //           },
  //           scales: {
  //               yAxes: [{
  //                       display: true,
  //                       ticks: {
  //                           beginAtZero: true,
  //                           steps: 10,
  //                           stepValue: 5,
  //                           min:0
  //                       }
  //                   }]
  //           }
  //         }
  //       });
</script>