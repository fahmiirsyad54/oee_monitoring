<div class="row">
  <div class="col-md-12" id="loadingoee">
    <div class="box">
      <div class="box-body text-center">
        <i class="fa fa-spinner fa-pulse fa-5x fa-fw" style="margin: 20px;"></i>
      </div>
    </div>
  </div>
</div>
<div id="oeegedung">
</div>
<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-knob/js/jquery.knob.js"></script>
<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>

<script>
  $(function () {
    var base_url  = '<?=base_url('dashboard')?>';
    var intgedung = <?= $intgedung?>;
	  $.ajax({
      url: base_url + '/getmesinajax/' + intgedung,
      method: "GET"
    })
    .done(function( data ) {
      $('#loadingoee').addClass('hidden');
      $('#oeegedung').html(data);
    })
    .fail(function( jqXHR, statusText ) {
      alert( "Request failed: " + jqXHR.status );
    });

    setInterval(function(){
      getdataoee();
    }, 60000);

  });

  function getdataoee(){
    var base_url  = '<?=base_url('dashboard')?>';
    var intgedung = <?= $intgedung?>;
    $('#loadingoee').removeClass('hidden');
    $('#oeegedung').addClass('hidden');
	  $.ajax({
      url: base_url + '/getmesinajax/' + intgedung,
      method: "GET"
    })
    .done(function( data ) {
      $('#loadingoee').addClass('hidden');
      $('#oeegedung').html(data);
      $('#oeegedung').removeClass('hidden');
    })
    .fail(function( jqXHR, statusText ) {
      alert( "Request failed: " + jqXHR.status );
    });
  }
</script>