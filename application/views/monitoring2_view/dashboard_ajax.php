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
        <!-- <span style="padding-right: 20px; ">ID Machine : <?=$this->session->vckodemesin?></span>
        <span style="padding-right: 20px; ">Operator : <?=$this->session->vckaryawan . ' (' . $this->session->vcnik . ')'?></span> -->
        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPesan" style="color: #000"><span style="padding-right: 20px;"><i class="fa fa-user"></i> Change Password</span></a>
        <a href="<?=base_url('akses/logoutoee2')?>" style="color: #000"><i class="fa fa-sign-out"></i> Log Out</a>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2">
      <div class="form-group">
        <div class="btn-group" role="group">
          <a href="<?=base_url('oee_monitoring2')?>" class="btn <?=$btnreal?>">Real Time</a>
          <a href="<?=base_url('oee_monitoring2/dashboard/'.date('Y-m-d',strtotime($datest)).'/'.date('Y-m-d',strtotime($datefs)))?>" class="btn <?=$btnhistory?>">History Time</a>
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
  <?php
    if ($avgaf >= 70) {
        $warnaaf = '#00ff00';
        $baraf = '#00ff00';
    } elseif ($avgaf >= 60) {
        $warnaaf = '#ffff00';
        $baraf = '#ffff00';
    } else{
        $warnaaf = '#ff0000';
        $baraf = '#ff0000';
    }

    if ($avgpf >= 70) {
        $warnapf = '#00ff00';
        $barpf = '#00ff00';
    } elseif ($avgpf >= 60) {
        $warnapf = '#ffff00';
        $barpf = '#ffff00';
    } else{
        $warnapf = '#ff0000';
        $barpf = '#ff0000';
    }

    if ($avgqf >= 70) {
        $warnaqf = '#00ff00';
        $barqf = '#00ff00';
    } elseif ($avgqf >= 60) {
        $warnaqf = '#ffff00';
        $barqf = '#ffff00';
    } else{
        $warnaqf = '#ff0000';
        $barqf = '#ff0000';
    }

    if ($avgoee >= 70) {
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
  <div class="row">
    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #ffc266">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #000000">OEE</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgoee?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaoee?>" readonly>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #FFFFFF">Availability Factor</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgaf?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaaf?>" readonly>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #FFFFFF">Performance Factor</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgpf?>%" data-width="120" data-height="120" data-fgColor="<?=$warnapf?>" readonly>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #FFFFFF">Quality Factor</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgqf?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaqf?>" readonly>
        </div>
      </div>
    </div>
  </div>
  <hr style="border: 2px solid #000000; margin-top: -30px; margin-bottom: 10px;">
	<div class="row">
    <?php
      foreach ($oee as $gedung) {
        if ($gedung['avgoee'] >= 70) {
            $warna = '#00ff00';
            $bar = '#00ff00';
        } elseif ($gedung['avgoee'] >= 60) {
            $warna = '#ffff00';
            $bar = '#ffff00';
        } else{
            $warna = '#ff0000';
            $bar = '#ff0000';
        }
    ?>
    <a href="<?=base_url('oee_monitoring2/building/') . $gedung['intgedung']?>">
      <div class="col-md-3">
      	<div class="box box-solid" style="background-color: #000000">
        	<div class="box-header">
          	<h3 class="box-title" style="color: #ffffff; font-weight: bold; ">OEE <?=$gedung['vcgedung']?></h3>
        	</div>
        	<div class="box-body text-center">
         		<input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$gedung['avgoee']?>%" data-width="120" data-height="120" data-fgColor="<?=$warna?>" readonly>
       		</div>
       		<div class="box-header" >
       			<h4 style="color: #ffffff;" >
              OEE
              <small class="pull-right" style="color: #ffffff;"><?=$gedung['avgoee']?>%</small>
            </h4>
            <div class="progress xs">
              <div class="progress-bar progress-bar-success" style="width: <?=$gedung['avgoee']?>%; background-color: <?=$bar?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
              </div>
            </div>
       		</div>
        </div>
      </div>
    </a>
    <?php
      }
    ?>
  </div>
</div>

<div id="modalPesan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Change Password</h4>
			</div>
			<div class="modal-body">
        <input type="password" class="form-control" id="oldpassword" placeholder="Old Password"><br>
        <input type="password" class="form-control" id="newpassword" placeholder="New Password"><br>
        <div class="row">
          <div class="col-md-12">
            <div class="pull-right">
              <button type="button" class="btn btn-success " onclick="changepassword()">Change</button>
              <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
			</div>
		</div>
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
    var _date1 = $('#from').val();
    var _date2 = $('#to').val();
    var base_url = '<?=base_url()?>';
    window.location.href = base_url + 'oee_monitoring2/dashboard/' + convertdate(_date1) + '/' + convertdate(_date2);
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

<script>
  function changepassword(){
    var _oldpassword = $('#oldpassword').val();
    var _newpassword = $('#newpassword').val();

    var base_url = '<?=base_url('oee_monitoring2')?>';
    var formrequired = {};
    var formdata = {'vcpassword' : _oldpassword};
    if (_oldpassword == '' || _newpassword == '') {
      swal({
          type: 'error',
          title: 'There is something wrong',
          text: 'Sorry, Your form is Null'
        });
    } else {

      $.ajax({
        url: base_url + '/validasi_password/' + _oldpassword,
        method: "GET"
        })
      .done(function( data ) {
        var jsonData = JSON.parse(data);
        if (jsonData[0].intpasswordcek == 0) {
          swal({
            type: 'error',
            title: 'There is something wrong',
            text: 'Sorry, your password is not same'
          });
        } else {
          $.ajax({
            url: base_url + '/change_password/' + _newpassword,
            method: "GET"
            })
          .done(function( data ) {
            $('#oldpassword').val('');
            $('#newpassword').val('');
            $('#modalPesan').modal('hide');
            swal({
                type: 'success',
                title: 'Congrats',
                text: 'the password was successfully replaced'
              });
          })
          .fail(function( jqXHR, statusText ) {
            alert( "Request failed: " + jqXHR.status );
          });
        }
      })
      .fail(function( jqXHR, statusText ) {
        alert( "Request failed: " + jqXHR.status );
      });
    }
  }
</script>