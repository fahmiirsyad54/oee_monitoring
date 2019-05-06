<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TPM System | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/dist/css/AdminLTE.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="<?=BASE_URL_PATH?>assets/index2.html"><b>TPM</b>System</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="<?=base_url('akses/authop')?>" method="post">
      <?php if ($errorLogin) { ?>
      <div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?=$errorMessage?>.
      </div>
      <?php } ?>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" name="vcusername" id="vcusername" autofocus>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="vcpassword" id="vcpassword">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="NIK" name="vcnik" id="vcnik">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <select name="intshift" class="form-control" id="intshift" >
          <option data-nama="" value="0">-- Select Shift --</option>
          <?php
            foreach ($listshift as $opt) {
              $selected = ($intshift == $opt->intid) ? 'selected' : '' ;
          ?>
          <option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
          <?php
            }
          ?>
        </select>
      </div>
            

      <div class="row">
        <div class="col-xs-8">
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="button" onclick="validasi()" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?=BASE_URL_PATH?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="<?=BASE_URL_PATH?>assets/dist/js/sweetalert2/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/dist/js/sweetalert2/sweetalert2.min.css">
<script>
  function validasi(){
    var vcusername = $('#vcusername').val();
    var vcpassword = $('#vcpassword').val();
    var vcnik      = $('#vcnik').val();
    var intshift   = $('#intshift').val();
    var base_url = '<?=base_url('akses/validasiop')?>';

    $.ajax({
      url: base_url + '/' + vcusername + '/' + vcpassword + '/' + vcnik + '/' + intshift,
      method: "GET"
    })
    .done(function( data ) {
      var jsonData = JSON.parse(data);

      if (jsonData.status) {
        var datenow = new Date();
        var minutes = datenow.getMinutes();
        var seconds = datenow.getSeconds();
        if (minutes < 10) { minutes = '0' + minutes}
        if (seconds < 10) { seconds = '0' + seconds}
        var time    = datenow.getHours() + ":" + minutes + ":" + seconds;

        var timeCounting     = localStorage.getItem('timeCounting');
        var jsontimeCounting = JSON.parse(timeCounting);
        if (timeCounting == null) {
          // Set Start Time
          var timeCounting = { 'dtstart': time, 'counttipe': 1, 'intshift': jsonData.intshift, 'dttanggal':jsonData.dttanggal};
          localStorage.setItem('timeCounting', JSON.stringify(timeCounting));
        } else {
          if (jsontimeCounting.intshift == jsonData.intshift && jsontimeCounting.dttanggal == jsonData.dttanggal) {
            
          }  else {
            var timeCounting = { 'dtstart': time, 'counttipe': 1, 'intshift': jsonData.intshift, 'dttanggal':jsonData.dttanggal};
            localStorage.setItem('timeCounting', JSON.stringify(timeCounting));
          }
        }

        localStorage.setItem('session', JSON.stringify(jsonData.session));

        // set model component
        var datakomponen = [{'intmodel':0, 'intkomponen':0, 'intpasang':0, 'intreject':0}];
        localStorage.setItem('datakomponen', JSON.stringify(datakomponen));

        window.location = "<?=base_url('operator')?>";
      } else {
        swal({
          type: 'error',
          title: jsonData.message
        });

        $('#vcusername').val('');
        $('#vcpassword').val('');
        $('#vcnik').val('');
        $('#intshift').val('');
      }
    })
    .fail(function( jqXHR, statusText ) {
      alert( "Request failed: " + jqXHR.status );
    });
  }
</script>
</body>
</html>
