<div class="row">
  <div class="col-md-12">
    <div class="alert alert-info" style="border-radius: 0px; background-color: #357ca5 !important; border-color: #357ca5 !important">
      <span id="realtime"></span>
      <div class="pull-right">
        <span style="padding-right: 20px; ">ID Machine : <?=$this->session->vckodemesin?></span>
        <span style="padding-right: 20px; ">Operator : <?=$this->session->vckaryawan . ' (' . $this->session->vcnik . ')'?></span>
        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPesan"><span style="padding-right: 20px; "><i class="fa fa-envelope"></i> Catatan</span></a>
        <a href="<?=base_url('akses/logoutop')?>"><i class="fa fa-sign-out"></i> Log Out</a>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
	<div class="row">
    <div class="col-md-3">
	    <div class="box box-solid">
	       <div class="box-header">
	          <h3 class="box-title text-danger">Availability</h3>
	        </div>
	        <div class="box-body text-center">
	         	<input type="text" class="knob" value="<?=round($availabilityfactor*100,2)?>%" data-width="120" data-height="120" data-fgColor="#0000FF" readonly>
	       	</div>
	       	<div class="box-header" >
            <?php
              $runtimebar = (($runtime == 0 || $plannedproduction == 0) ? 0 : $runtime / $plannedproduction) * 100;
            ?>
	       		<h5 > Run Time
              <small class="pull-right"><?=$runtime?> min</small>
            </h5>
            <div class="progress xs">
              <div class="progress-bar progress-bar-success" style="width: <?=$runtimebar?>%" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="<?=$plannedproduction?>">
              </div>
            </div>
            <h5> Planned Production Time
              <small class="pull-right"><?=$plannedproduction?> min</small>
            </h5>
            <div class="progress xs">
              <div class="progress-bar progress-bar-aqua" style="width: 100%" role="progressbar" aria-valuenow="6000" aria-valuemin="0" aria-valuemax="6000">
              </div>
            </div>
	       		<h3> Availability
              <small class="pull-right"><?=round($availabilityfactor*100,2)?>%</small>
            </h3>
            <div class="progress xs">
              <div class="progress-bar progress-bar-red" style="width: <?=round($availabilityfactor*100,2)?>%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
              </div>
            </div>
          </div>
      </div>
    </div>

    <div class="col-md-3">
    	<div class="box box-solid">
      	<div class="box-header">
        	<h3 class="box-title text-info">Performance</h3>
      	</div>
      	<div class="box-body text-center">
       		<input type="text" class="knob" value="<?=round($performancefactor*100,2)?>%" data-width="120" data-height="120" data-fgColor="#7FFF00" readonly>
     		</div>
     		<div class="box-header" >
          <?php
            $actualoutputbar = (($actualoutput == 0) ? 0 : $actualoutput / $theoriticaloutput) * 100;
          ?>
       		<h5> Actual Output
            <small class="pull-right"><?=$actualoutput?> pcs</small>
          </h5>
          <div class="progress xs">
            <div class="progress-bar progress-bar-success" style="width: <?=$actualoutputbar?>%" role="progressbar" aria-valuenow="6000" aria-valuemin="0" aria-valuemax="<?=$theoriticaloutput?>">
            </div>
          </div>
       			<h5> Theoretical Output
              <small class="pull-right"><?=$theoriticaloutput?> pcs</small>
            </h5>
            <div class="progress xs">
              <div class="progress-bar progress-bar-aqua" style="width: 100%" role="progressbar" aria-valuenow="6000" aria-valuemin="0" aria-valuemax="6000">
              </div>
            </div>
            <h3> Performance
              <small class="pull-right"><?=round($performancefactor*100,2)?>%</small>
            </h3>
            <div class="progress xs">
              <div class="progress-bar progress-bar-green" style="width: <?=round($performancefactor*100,2)?>%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
              </div>
            </div>
       		</div>
        </div>
    </div>

    <div class="col-md-3">
    	<div class="box box-solid">
      	<div class="box-header">
          <h3 class="box-title text-success">Quality</h3>
        </div>
      	<div class="box-body text-center">
       		<input type="text" class="knob" value="<?=round($qualityfactor*100,2)?>%" data-width="120" data-height="120" data-fgColor="#000080" readonly>
     		</div>
     		<div class="box-header" >
     			<h5 > Actual Output
            <small class="pull-right"><?=$actualoutput?> pcs</small>
          </h5>
          <div class="progress xs">
            <div class="progress-bar progress-bar-success" style="width: <?=$actualoutputbar?>%" role="progressbar" aria-valuenow="6000" aria-valuemin="0" aria-valuemax="<?=$theoriticaloutput?>">
            </div>
          </div>
     			<h5> Defective Product
            <small class="pull-right"><?=$defectiveproduct?></small>
          </h5>
          <div class="progress xs">
            <div class="progress-bar progress-bar-aqua" style="width: 0%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="6000">
            </div>
          </div>
       			<h3> Quality
              <small class="pull-right"><?=round($qualityfactor*100,2)?>%</small>
            </h3>
            <div class="progress xs">
              <div class="progress-bar progress-bar-navy" style="width: <?=round($qualityfactor*100,2)?>%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
              </div>
            </div>
       		</div>
        </div>
    </div>

    <div class="col-md-3">
    	<div class="box box-solid">
      	<div class="box-header">
        	<h3 class="box-title text-warning">OEE</h3>
      	</div>
      	<div class="box-body text-center">
       		<input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=round($oee*100,2)?>%" data-width="120" data-height="120" data-fgColor="#FF0000" readonly>
     		</div>
     		<div class="box-header" >
     			<h3>
            OEE
            <small class="pull-right"><?=round($oee*100,2)?>%</small>
          </h3>
          <div class="progress xs">
            <div class="progress-bar progress-bar-danger" style="width: <?=round($oee*100,2)?>%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
     		</div>
      </div>
    </div>
  </div>
</div>


<script src="../../bower_components/jquery-knob/js/jquery.knob.js"></script>
<script src="../../bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script type="text/javascript">
  setTimeout(function(){
    // $.ajax({
    //   url : '<?=base_url($controller)?>/insertoeeop',
    //   methode : "GET"
    //   })

     location.reload();
    },60000);

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
    drawDocSparklines();
    drawMouseSpeedDemo();

  });
</script>