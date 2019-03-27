<div class="row">
	<div class="col-md-12">
		<div class="box">
            <div class="box-header with-border">
				<h3 class="box-title"><?=$action?> <?=$title?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	<form method="POST" action="">
	            	<div class="form-group row">
	            		<div class="col-md-6">
							<label>Kode</label>
							<input type="text" class="form-control" name="vckode" id="vckode" placeholder="Kode" value="<?=$vckode?>">
	            		</div>
	                </div>

	                <div class="form-group row">
	            		<div class="col-md-6">
							<label>Nama</label>
							<input type="text" class="form-control" name="vcnama" id="vcnama" placeholder="Nama" value="<?=$vcnama?>">
	            		</div>
	                </div>

	                <div class="form-group row">
	            		<div class="col-md-6">
							<button type="button" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
							<a href="" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
	            		</div>
	                </div>
            	</form>
            </div>
        </div>
	</div>
</div>