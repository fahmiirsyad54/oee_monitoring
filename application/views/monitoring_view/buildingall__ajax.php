<script>
  $(function () {
    $('.datepicker').datepicker({
      autoclose: true
    })
  }) 
</script>

<div class="row">
  <div class="col-md-12">
    <div class="alert alert-info" style="border-radius: 0px; background-color: #b7fd35 !important; border-color: #b7fd35 !important">
      <span id="realtime" style="color: #000"></span>
      <div class="pull-right">
        <a href="<?=base_url('oee_monitoring/buildingall_/'.$intgedung)?>" style="padding-right: 20px; color: #000">Home</a>
        <!-- <span style="padding-right: 20px; "></span> -->
        <!-- <span style="padding-right: 20px; ">Operator : <?=$this->session->vckaryawan . ' (' . $this->session->vcnik . ')'?></span> -->
        <!-- <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPesan"><span style="padding-right: 20px; "><i class="fa fa-envelope"></i> Catatan</span></a> -->
        <a href="<?=base_url('akses/logoutoee')?>" style="color: #000"><i class="fa fa-sign-out"></i> Log Out</a>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-1">
      <h4 class="" style="font-weight: bold; color: #000000"><?=$gedung[0]->vcnama?></h4>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <div class="btn-group" role="group">
          <a href="<?=base_url('oee_monitoring/buildingall_/'.$intgedung)?>" class="btn <?=$btnreal?>">Real Time</a>
          <a href="<?=base_url('oee_monitoring/buildingall_/'.$intgedung.'/'.date('Y-m-d',strtotime($datest)).'/'.date('Y-m-d',strtotime($datefs)))?>" class="btn <?=$btnhistory?>">History Time</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <a href="javascript:void()" onclick="window.history.go(-1); return false;" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
      <a href="<?=base_url('oee_monitoring/buildingall_/'.$intgedung)?>" class="btn btn-default"><i class="fa fa-home"></i></a>
      <a href="javascript:void()" onclick="window.history.go(1); return false;" class="btn btn-default"><i class="fa fa-arrow-right"></i></a>
    </div>

    <div class="col-md-2 <?=$hidedate?>">
      <div class="form-group">
        <input type="text" name="from" placeholder="From" class="form-control datepicker" id="from" value="<?=$datest?>" />
      </div>
    </div>

    <div class="col-md-2 col-md-offset-1 <?=$hidedate?>">
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
  <?php
    if ($avgaf >= 80) {
        $warnaaf = '#00ff00';
        $baraf = '#00ff00';
    } elseif ($avgaf >= 60) {
        $warnaaf = '#ffff00';
        $baraf = '#ffff00';
    } else{
        $warnaaf = '#ff0000';
        $baraf = '#ff0000';
    }

    if ($avgpf >= 80) {
        $warnapf = '#00ff00';
        $barpf = '#00ff00';
    } elseif ($avgpf >= 60) {
        $warnapf = '#ffff00';
        $barpf = '#ffff00';
    } else{
        $warnapf = '#ff0000';
        $barpf = '#ff0000';
    }

    if ($avgqf >= 80) {
        $warnaqf = '#00ff00';
        $barqf = '#00ff00';
    } elseif ($avgqf >= 60) {
        $warnaqf = '#ffff00';
        $barqf = '#ffff00';
    } else{
        $warnaqf = '#ff0000';
        $barqf = '#ff0000';
    }

    if ($avgoee >= 80) {
        $warnaoee = '#00ff00';
        $baroee = '#00ff00';
    } elseif ($avgoee >= 60) {
        $warnaoee = '#ffff00';
        $baroee = '#ffff00';
    } else{
        $warnaoee = '#ff0000';
        $baroee = '#ff0000';
    }
  ?>

  <?php
    if ($avgaf2 >= 80) {
        $warnaaf2 = '#00ff00';
        $baraf2 = '#00ff00';
    } elseif ($avgaf2 >= 60) {
        $warnaaf2 = '#ffff00';
        $baraf2 = '#ffff00';
    } else{
        $warnaaf2 = '#ff0000';
        $baraf2 = '#ff0000';
    }

    if ($avgpf2 >= 80) {
        $warnapf2 = '#00ff00';
        $barpf2 = '#00ff00';
    } elseif ($avgpf2 >= 60) {
        $warnapf2 = '#ffff00';
        $barpf2 = '#ffff00';
    } else{
        $warnapf2 = '#ff0000';
        $barpf2 = '#ff0000';
    }

    if ($avgqf2 >= 80) {
        $warnaqf2 = '#00ff00';
        $barqf2 = '#00ff00';
    } elseif ($avgqf2 >= 60) {
        $warnaqf2 = '#ffff00';
        $barqf2 = '#ffff00';
    } else{
        $warnaqf2 = '#ff0000';
        $barqf2 = '#ff0000';
    }

    if ($avgoee2 >= 80) {
        $warnaoee2 = '#00ff00';
        $baroee2 = '#00ff00';
    } elseif ($avgoee2 >= 60) {
        $warnaoee2 = '#ffff00';
        $baroee2 = '#ffff00';
    } else{
        $warnaoee2 = '#ff0000';
        $baroee2 = '#ff0000';
    }
  ?>
 
  <div class="row">
    <a href="<?=base_url('oee_monitoring/building_/') . $gedung[0]->intid?>">
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-solid" style="background-color: #000000">
              <div class="box-header">
                <h3 class="box-title" style="font-weight: bold; color: #ffffff">OEE COMELZ</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgoee?>%" data-width="200" data-height="200" data-fgColor="<?=$warnaoee?>" readonly>
              </div>
              <div class="box-header">
                <h4 style="color: #ffffff;" >
                  OEE
                  <small class="pull-right" style="color: #ffffff;"><?=$avgoee?>%</small>
                </h4>
                <div class="progress xs">
                    <div class="progress-bar progress-bar-success" style="width: <?=$avgoee?>%; background-color: <?=$baroee?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="box box-solid" style="background-color: #000080">
              <div class="box-header">
                <h3 class="box-title" style="font-weight: bold; color: #ffffff">Availability Factor</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgaf?>%" data-width="100" data-height="100" data-fgColor="<?=$warnaaf?>" readonly>
              </div>
              <div class="box-header">
                <h4 style="color: #ffffff;" >
                  <small class="pull-right" style="color: #ffffff;"><?=$avgaf?>%</small> <br>
                </h4>
                <div class="progress xs">
                    <div class="progress-bar progress-bar-success" style="width: <?=$avgaf?>%; background-color: <?=$baraf?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="box box-solid" style="background-color: #000080">
              <div class="box-header">
                <h3 class="box-title" style="font-weight: bold; color: #ffffff">Performance Factor</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgpf?>%" data-width="100" data-height="100" data-fgColor="<?=$warnapf?>" readonly>
              </div>
              <div class="box-header">
                <h4 style="color: #ffffff;" >
                  <small class="pull-right" style="color: #ffffff;"><?=$avgpf?>%</small> <br>
                </h4>
                <div class="progress xs">
                    <div class="progress-bar progress-bar-success" style="width: <?=$avgpf?>%; background-color: <?=$barpf?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="box box-solid" style="background-color: #000080">
              <div class="box-header">
                <h3 class="box-title" style="font-weight: bold; color: #ffffff">Quality Factor</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgqf?>%" data-width="100" data-height="100" data-fgColor="<?=$warnaqf?>" readonly>
              </div>
              <div class="box-header">
                <h4 style="color: #ffffff;" >
                  <small class="pull-right" style="color: #ffffff;"><?=$avgqf?>%</small> <br>
                </h4>
                <div class="progress xs">
                    <div class="progress-bar progress-bar-success" style="width: <?=$avgqf?>%; background-color: <?=$barqf?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>

    <a href="<?=base_url('oee_monitoring2/building_/') . $gedung[0]->intid?>">
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-solid" style="background-color: #000000">
              <div class="box-header">
                <h3 class="box-title" style="font-weight: bold; color: #ffffff">OEE LASER</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgoee2?>%" data-width="200" data-height="200" data-fgColor="<?=$warnaoee2?>" readonly>
              </div>
              <div class="box-header">
                <h4 style="color: #ffffff;" >
                  OEE
                  <small class="pull-right" style="color: #ffffff;"><?=$avgoee2?>%</small>
                </h4>
                <div class="progress xs">
                    <div class="progress-bar progress-bar-success" style="width: <?=$avgoee2?>%; background-color: <?=$baroee2?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="box box-solid" style="background-color: #000080">
              <div class="box-header">
                <h3 class="box-title" style="font-weight: bold; color: #ffffff">Availability Factor</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgaf2?>%" data-width="100" data-height="100" data-fgColor="<?=$warnaaf2?>" readonly>
              </div>
              <div class="box-header">
                <h4 style="color: #ffffff;" >
                  <small class="pull-right" style="color: #ffffff;"><?=$avgaf2?>%</small> <br>
                </h4>
                <div class="progress xs">
                    <div class="progress-bar progress-bar-success" style="width: <?=$avgaf2?>%; background-color: <?=$baraf2?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="box box-solid" style="background-color: #000080">
              <div class="box-header">
                <h3 class="box-title" style="font-weight: bold; color: #ffffff">Performance Factor</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgpf2?>%" data-width="100" data-height="100" data-fgColor="<?=$warnapf2?>" readonly>
              </div>
              <div class="box-header">
                <h4 style="color: #ffffff;" >
                  <small class="pull-right" style="color: #ffffff;"><?=$avgpf2?>%</small> <br>
                </h4>
                <div class="progress xs">
                    <div class="progress-bar progress-bar-success" style="width: <?=$avgpf2?>%; background-color: <?=$barpf2?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="box box-solid" style="background-color: #000080">
              <div class="box-header">
                <h3 class="box-title" style="font-weight: bold; color: #ffffff">Quality Factor</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgqf2?>%" data-width="100" data-height="100" data-fgColor="<?=$warnaqf2?>" readonly>
              </div>
              <div class="box-header">
                <h4 style="color: #ffffff;" >
                  <small class="pull-right" style="color: #ffffff;"><?=$avgqf2?>%</small> <br>
                </h4>
                <div class="progress xs">
                    <div class="progress-bar progress-bar-success" style="width: <?=$avgqf2?>%; background-color: <?=$barqf2?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
</div>


<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-knob/js/jquery.knob.js"></script>
<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script type="text/javascript">
  var _intrealtime = <?=$intrealtime?>

  if (_intrealtime == 1) {
    $(function () {
      setTimeout(function(){
           location.reload();
          },180000);
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
    var _date1           = $('#from').val();
    var _date2           = $('#to').val();
    var base_url         = '<?=base_url()?>';
    var intgedung        = <?=$intgedung?>;
    window.location.href = base_url + 'oee_monitoring/building/'+ intgedung + '/' + convertdate(_date1) + '/' + convertdate(_date2);
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

  $(function () {
    window.onload = date_time('realtime');
    console.log(date_time('realtime'));
    /* jQueryKnob */

    $(".knob").knob({
      'min': 0,
      'max': 100,
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

  });
</script>