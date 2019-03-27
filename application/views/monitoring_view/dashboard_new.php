<script>
  $(function () {
    $('.datepicker').datepicker({
      autoclose: true
    })
  }) 
</script>
<style type="text/css">

</style>
<div class="row">
  <div class="col-md-12">
    <div class="alert alert-info" style="border-radius: 0px; background-color: #357ca5 !important; border-color: #357ca5 !important">
      <span id="realtime"></span>
      <div class="pull-right">
        <!-- <span style="padding-right: 20px; ">ID Machine : <?=$this->session->vckodemesin?></span>
        <span style="padding-right: 20px; ">Operator : <?=$this->session->vckaryawan . ' (' . $this->session->vcnik . ')'?></span> -->
        <!-- <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPesan"><span style="padding-right: 20px; "><i class="fa fa-envelope"></i> Catatan</span></a> -->
        <a href="<?=base_url('akses/logoutoee')?>"><i class="fa fa-sign-out"></i> Log Out</a>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2">
      <div class="form-group">
        <div class="btn-group" role="group">
          <a href="<?=base_url('oee_monitoring')?>" class="btn <?=$btnreal?>">Real Time</a>
          <a href="<?=base_url('oee_monitoring/dashboard/'.date('Y-m-d',strtotime($datest)).'/'.date('Y-m-d',strtotime($datefs)))?>" class="btn <?=$btnhistory?>">History Time</a>
        </div>
      </div>
    </div>

    <div class="col-md-2 <?=$hidedate?>">
      <div class="form-group">
        <input type="text" name="from" placeholder="From" class="form-control datepicker" id="from" value="<?=$datest?>" />
      </div>
    </div>

    <div class="col-md-2 <?=$hidedate?>">
      <div class="form-group">
        <input type="text" name="to" placeholder="To" class="form-control datepicker" id="to" value="<?=$datefs?>" />
      </div>
    </div>

    <div class="col-md-1 <?=$hidedate?>">
      <div class="form-group">
        <button class="btn btn-info btn-block" onclick="showoee()">Show</button>
      </div>
    </div>
  </div>
	<div class="row">
    <?php
      $loop = 0;
      foreach ($oee as $gedung) {
        if ($gedung['avgoee'] >= 70) {
            $warna = '#00a65a';
            $bar = 'success';
        } elseif ($gedung['avgoee'] >= 60) {
            $warna = '#f0ad4e';
            $bar = 'warning';
        } else{
            $warna = '#d9534f';
            $bar = 'danger';
        }
    ?>
    <a href="<?=base_url('oee_monitoring/building/') . $gedung['intgedung']?>">
      <div class="col-md-3">
      	<div class="box box-solid">
        	<div class="box-header">
          	<h3 class="box-title text-warning">OEE <?=$gedung['vcgedung']?></h3>
        	</div>
        	<div class="box-body text-center">
            <div id="container-speed<?=$loop?>"></div>
       		</div>
       		<div class="box-header" style="margin-top: -100px">
       			<h3>
              OEE
              <small class="pull-right"><?=$gedung['avgoee']?>%</small>
            </h3>
            <div class="progress xs">
              <div class="progress-bar progress-bar-<?=$bar?>" style="width: <?=$gedung['avgoee']?>%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
              </div>
            </div>
       		</div>
        </div>
      </div>
    </a>
    <?php
        $loop++;
      }
    ?>
  </div>
</div>

<script src="<?=BASE_URL_PATH?>assets/plugins/chart/highcharts.js"></script>
<script src="<?=BASE_URL_PATH?>assets/plugins/chart/highcharts-more.js"></script>
<script src="<?=BASE_URL_PATH?>assets/plugins/chart/solid-gauge.js"></script>

<script type="text/javascript">
  var gaugeOptions = {

    chart: {
        type: 'solidgauge',
        height: 250
        // marginTop: -100,
        // marginBottom: -300
    },

    title: null,

    pane: {
        // center: ['50%', '50%'],
        size: '100%',
        startAngle: -90,
        endAngle: 90,
        background: {
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#DF5353'], // green
            [0.6, '#DDDF0D'], // yellow
            [0.7, '#55BF3B'], // yellow
            [1, '#55BF3B'] // red
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: 0,
        title: {
            y: 70
        },
        labels: {
            y: 16
        }
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: -30,
                borderWidth: 0,
                useHTML: true
            }
        }
    }
};

// The speed gauge
var chartSpeed = Highcharts.chart('container-speed0', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: 100
    },

    credits: {
        enabled: false
    },

    series: [{
        name: 'Speed',
        data: [120],
        dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}%</span><br/>' +
                   '</div>'
        }
    }]

}));

</script>

<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-knob/js/jquery.knob.js"></script>
<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script type="text/javascript">
  var _intrealtime = <?=$intrealtime?>

  if (_intrealtime == 1) {
    $(function () {
      setTimeout(function(){
           location.reload();
          },60000);
    });
  }

  function convertdate(_date){
    now = new Date(_date);
    year = now.getFullYear();
    month = (now.getMonth() + 1);
    day = now.getDate();
    return year + "-" + month + "-" + day;
  }

  function showoee(){
    var _date1 = $('#from').val();
    var _date2 = $('#to').val();
    var base_url = '<?=base_url()?>';
    window.location.href = base_url + 'oee_monitoring/dashboard/' + convertdate(_date1) + '/' + convertdate(_date2);
  }

  function date_time(id){
      date = new Date;
      year = date.getFullYear();
      month = date.getMonth();
      months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
      d = date.getDate();
      day = date.getDay();
      days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
      h = date.getHours();
      if(h<10)
      {
              h = "0"+h;
      }
      m = date.getMinutes();
      if(m<10)
      {
              m = "0"+m;
      }
      s = date.getSeconds();
      if(s<10)
      {
              s = "0"+s;
      }
      result = ''+days[day]+', '+d+' '+months[month]+' '+year+' '+h+':'+m+':'+s;
      document.getElementById(id).innerHTML = result;
      setTimeout('date_time("'+id+'");','1000');
      return true;
  }
	window.onload = date_time('realtime');

	$(function () {
    /* jQueryKnob */

    $(".knob").knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a = this.angle(this.cv)  // Angle
              , sa = this.startAngle          // Previous start angle
              , sat = this.startAngle         // Start angle
              , ea                            // Previous end angle
              , eat = sat + a                 // End angle
              , r = true;

          this.g.lineWidth = this.lineWidth;

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3);

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value);
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3);
            this.g.beginPath();
            this.g.strokeStyle = this.previousColor;
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
            this.g.stroke();
          }

          this.g.beginPath();
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
          this.g.stroke();

          this.g.lineWidth = 2;
          this.g.beginPath();
          this.g.strokeStyle = this.o.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
          this.g.stroke();

          return false;
        }
      }
    });
    /* END JQUERY KNOB */

    //INITIALIZE SPARKLINE CHARTS
    $(".sparkline").each(function () {
      var $this = $(this);
      $this.sparkline('html', $this.data());
    });

    /* SPARKLINE DOCUMENTATION EXAMPLES http://omnipotent.net/jquery.sparkline/#s-about */
    // drawDocSparklines();
    // drawMouseSpeedDemo();

  });
</script>