<div class="row">
	<div class="col-lg-3 col-lg-12">  
    <div class="info-box" style="background: #333;">
      <span class="info-box-icon bg-red" style="background: #b30000;">
          <i class="fa fa-gear"></i>
      </span>

      <div class="info-box-content" style="background: #333; color: #ffffff;">
        <a href="<?=base_url('dashboard/machine')?>" style="color: #ffffff;">
          <span class="info-box-text">Machine</span>
          <span class="info-box-number"><?=$mesin?></span>
        </a>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-lg-12">  
    <div class="info-box" style="background: #333;">
      <span class="info-box-icon bg-blue"><i class="fa fa-hourglass-half"></i></span>

      <div class="info-box-content">
        <a href="<?=base_url('dashboard/downtime')?>" style="color: #ffffff;">
          <span class="info-box-text">Downtime</span>
          <span class="info-box-number"><?=$downtime?></span>
        </a>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-lg-12">
    <div class="info-box" style="background: #333;">
      <span class="info-box-icon bg-yellow"><i class="fa fa-pie-chart"></i></span>

      <div class="info-box-content">
        <a href="<?=base_url('dashboard/oee')?>" style="color: #ffffff;">
          <span class="info-box-text">OEE</span>
          <span class="info-box-number hidden" id="oeeval">0%</span>
          <span class="info-box-number" id="loadingoeeval"><i class="fa fa-spinner fa-pulse fa-fw"></i></span>
        </a>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-lg-12">
    <div class="info-box" style="background: #333;">
      <span class="info-box-icon bg-green"><i class="fa fa-cogs"></i></span>

      <div class="info-box-content">
        <a href="<?=base_url('dashboard/sparepartstock')?>" style="color: #ffffff;">
          <span class="info-box-text">Sparepart Stock</span>
          <span class="info-box-number"><?=$sparepart?></span>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Downtime Chart</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body chart-responsive text-center">
        <i class="fa fa-spinner fa-pulse fa-5x fa-fw" id="loadingdowntimechart"></i>
        <canvas class="hidden" id="downtimechart" height="150" width="auto"></canvas><br>
        <!-- <div class="chart" id="downtimechart" style="height: 150px;"></div><br> -->
        <!-- <div class="chart" id="topdowntimechart" style="height: 150px;"></div> -->
        <i class="fa fa-spinner fa-pulse fa-5x fa-fw" id="loadingtopdowntimechart"></i>
        <canvas class="hidden" id="topdowntimechart" height="150" width="auto"></canvas> 
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">OEE Chart</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body chart-responsive text-center">
        <i class="fa fa-spinner fa-pulse fa-5x fa-fw" id="loadingoeechart"></i>
        <canvas class="hidden" id="oeechart" height="300" width="auto"></canvas> 
      </div>
    </div>  
    <canvas id="bar-chart" width="800" height="450"></canvas>  
  </div>
  

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script type="text/javascript" src="<?=BASE_URL_PATH?>assets/plugins/toastr/toastr.js"></script>

<script>
  $(function (){
    var base_url = "<?=base_url('dashboard')?>";

    // downtime chart
    $.ajax({
        url: base_url + '/grafikmonth',
        method: "GET"
    })
    .done(function( data ) {
        var result = JSON.parse(data);
        new Chart(document.getElementById("downtimechart"), {
          type: 'bar',
          data: {
            labels: result.bulan,
            datasets: [
              {
                label: "Downtime",
                backgroundColor: ["#0099ff", "#ff3300","#9900ff","#00ff00","#ff0066","#0000cc"],
                data: result.data
              }
            ]
          },
          options: {
            responsive: true,
            legend: { display: false },
            title: {
              display: true,
              text: 'Downtime'
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
      $('#loadingdowntimechart').addClass('hidden');
      $('#downtimechart').removeClass('hidden');

    })
    .fail(function( jqXHR, statusText ) {
        alert( "Request failed: " + jqXHR.status );
    });

    // topdowntime chart
    $.ajax({
        url: base_url + '/grafiktopdowntime',
        method: "GET"
    })
    .done(function( data ) {
        var result = JSON.parse(data);
        new Chart(document.getElementById("topdowntimechart"), {
          type: 'bar',
          data: {
            labels: result.vcdowntime,
            datasets: [
              {
                label: "Top Downtime",
                backgroundColor: ["#ff1a1a", "#ff6600","#e6e600","#99ff33","#00cc00"],
                data: result.data
              }
            ]
          },
          options: {
            responsive: true,
            legend: { display: false },
            title: {
              display: true,
              text: 'Top Downtime'
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
      $('#loadingtopdowntimechart').addClass('hidden');
      $('#topdowntimechart').removeClass('hidden');

    })
    .fail(function( jqXHR, statusText ) {
        alert( "Request failed: " + jqXHR.status );
    });

    // OEE chart
    $.ajax({
        url: base_url + '/oeetest',
        method: "GET"
    })
    .done(function( data ) {
        var result = JSON.parse(data);
        new Chart(document.getElementById("oeechart"), {
            type: 'bar',
            data: {
              labels: result.bulan,
              datasets: [
                {
                  label: "OEE",
                  backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                  data: result.data
                }
              ]
            },
            options: {
              legend: { display: false },
              title: {
                display: true,
                text: 'OEE'
              }
            }
        });
        $('#loadingoeechart').addClass('hidden');
        $('#oeechart').removeClass('hidden');
    })
    .fail(function( jqXHR, statusText ) {
        alert( "Request failed: " + jqXHR.status );
    });

    // OEE Value
    $.ajax({
        url: base_url + '/getoeeajax',
        method: "GET"
    })
    .done(function( data ) {
        $('#loadingoeeval').addClass('hidden');
        $('#oeeval').text(data);
        $('#oeeval').removeClass('hidden');
    })
    .fail(function( jqXHR, statusText ) {
        alert( "Request failed: " + jqXHR.status );
    });
    
  });
</script>