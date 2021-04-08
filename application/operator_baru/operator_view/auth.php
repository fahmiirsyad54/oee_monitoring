<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TMP System 2.0 - <?=$title?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/bower_components/Ionicons/css/ionicons.min.css">

  <!-- Date Picker -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/bower_components/select2/dist/css/select2.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/dist/css/style.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/dist/css/skins/_all-skins.min.css">

  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- jQuery 3 -->
  <script src="<?=BASE_URL_PATH?>assets/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="<?=BASE_URL_PATH?>assets/dist/js/sweetalert2/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/dist/js/sweetalert2/sweetalert2.min.css">

  <!-- Bootstrap 3.3.7 -->
  <script src="<?=BASE_URL_PATH?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- Select2 -->
  <script src="<?=BASE_URL_PATH?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style>
    .modal {
        text-align: center;
    }

    @media screen and (min-width: 768px) { 
    .modal:before {
        display: inline-block;
        vertical-align: middle;
        content: " ";
        height: 100%;
    }
    }

    .modal-dialog {
        display: inline-block;
        text-align: center;
        vertical-align: middle;
    }
  </style>
</head>
<body style="background-color: #ecf0f5">
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-sm">
      <div class="modal-body text-center">
      <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
      </div>
    </div>

  </div>
</div>
<script>
    // Set Default Page
	$(function () {
	    $('#myModal').modal('show');
        $('.select2').select2();

        var _session = JSON.parse(localStorage.getItem('session'));
        if (_session == null) {
            window.location.replace("<?=base_url('akses/loginop')?>");
        } else {
            window.location.replace("<?=base_url('operator/downtime')?>");
        }

		// var timeCounting = localStorage.getItem('timeCounting');
		// var datacounting = JSON.parse(timeCounting);
		// if (datacounting.counttipe == 1) {
		// 	$('#btnoutput').addClass('hidden');
		// 	$('#btnstartdowntime').text(datacounting.dtstart);
		// 	$('#btndowntimefinish').removeClass('hidden');
		// 	$('#dtmulai').val(datacounting.dtstart);
		// } else {
		// 	$('#btnoutput').removeClass('hidden');
		// 	$('#btnstartoutput').text(datacounting.dtstart);
		// 	$('#btndowntimefinish').addClass('hidden');
		// 	$('#dtmulaioutput').val(datacounting.dtstart);
		// 	$('#btndowntime').removeAttr('onclick');
		// }
	});
</script>

<!-- jQuery 3 -->

<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- Morris.js charts -->
<script src="<?=BASE_URL_PATH?>assets/bower_components/raphael/raphael.min.js"></script>
<script src="<?=BASE_URL_PATH?>assets/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?=BASE_URL_PATH?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=BASE_URL_PATH?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?=BASE_URL_PATH?>assets/bower_components/moment/min/moment.min.js"></script>
<script src="<?=BASE_URL_PATH?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?=BASE_URL_PATH?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?=BASE_URL_PATH?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="<?=BASE_URL_PATH?>assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<!-- Slimscroll -->
<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?=BASE_URL_PATH?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?=BASE_URL_PATH?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

</body>
</html>
