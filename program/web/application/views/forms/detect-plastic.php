<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
	<div class="col-md-7 col-sm-12">

		<?php print form_open(current_url(), array('id' => 'from-suspect-add', 'class' => 'card shadow-base bd-0'));?>

		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group mg-b-0">
						<label class="form-control-label">Plastic Waste Photo <span class="tx-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" name="image_file" class="custom-file-input" id="customFile" placeholder="Choose file" required autofocus>
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
						<ul class="fields-message"></ul>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer bd-color-gray-lighter text-right">
			<button type="submit" class="btn btn-primary tx-size-xs ">Submit</button>
		</div>

		<?php print form_close();?>

	</div>
	
	<div class="col-md-5 col-sm-12 mg-t-20 mg-md-t-0">
		<div class="card shadow-base bd-0">
			<div class="card-header bd-gray-200 mg-t-auto">
				<span>Batasan Program/Masalah</span>
			</div>
			<div class="card-body pd-0">
				<div class="col-md-12 bd-b d-xs-flex align-items-center justify-content-start pd-15">
					<p class="card card-body bg-primary tx-white bd-0">
						<img src="<?php print base_url("files/images/plastic_plot.png");?>" width="100%" /><br />
						1. Program saat ini memiliki akurasi <?php print ALGO_ACCURACCY;?> dengan <?php print ALGO_EPOCH;?> iterasi/epoch.<br />
						2. Tidak dapat mendeteksi banyak object sekaligus, dirokemendasikan hanya ada 1 objek pada gambar yang akan diupload.<br />
						3. Background gambar berpengaruh jadi harus dilakukan pembersihan pada backgroud gambar, dalam artian backgroud object yang akan dideteksi hanya mempunyai satu warna.
					</p>
				</div>
			</div>
		</div>

		<div id="elm-log-row" style="display:none;">
			<span class="col time"></span>
			<span class="col created-by"></span>
			<span class="col text-right ip-address"></span>
		</div>
	</div>
</div>


<script type="text/javascript">
$(document).ready(function() {
	$('#from-suspect-add').on('submit', function(event){
		event.preventDefault();
		var request = '<?php print base_url().'request/'.$this->req_controller;?>/<?php print $this->action;?>',
            form    = $(this),
            data    = new FormData(this);

		loading(form, 'show');
        $.ajax({
            type: 'POST',
            url: request,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                init_meta(result.meta);
                loading(form, 'hide');
            }
		});
	});

	$('.role-group-list').each(function(i, obj) {
		container = $(this).closest('div');
		if($(obj).find('input[type=checkbox]').not(':checked').length > 0) {
			container.find('.mark-all-ingroup').prop('checked', false);
		}else{
			container.find('.mark-all-ingroup').prop('checked', true);
		}
	});

	$('.role-group-list input[type=checkbox]').on('change', function() {
		container = $(this).closest('div');
		if(container.find('.role-group-list input[type=checkbox]:checked').length < container.find('.role-group-list input[type=checkbox]').length) {
			console.log(container.find('.mark-all-ingroup').prop('checked', false));
		}else{
			container.find('.mark-all-ingroup').prop('checked', true);
		}
	});


	$('input.mark-all-ingroup').on('change', function() {
		li = $(this).closest('div').find('li.list-group-item');
		//console.log(li.find('input[type=checkbox]:checked').length == li.find('input[type=checkbox]'));
		if( li.find('input[type=checkbox]:checked').length == li.find('input[type=checkbox]').length ) {
			li.find('input[type=checkbox]').prop('checked', false);
		} else {
			li.find('input[type=checkbox]').prop('checked', true);
		}
	});
		
	$("[type=file]").on("change", function() {
		// Name of file and placeholder
		var str = this.files[0].name;
		var file = str.substr(1, 36);
		var dflt = $(this).attr("placeholder");
		if ($(this).val() != "") {
			$(this).next().text(file);
		} else {
			$(this).next().text(dflt);
		}
	});
});
</script>