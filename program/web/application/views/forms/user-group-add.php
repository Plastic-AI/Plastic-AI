<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
	<div class="col-md-12">

		<?php print form_open(current_url(), array('id' => 'form-user-group-update', 'class' => 'card shadow-base bd-0'));?>

		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group mg-b-0">
						<label class="form-control-label">Name <span class="tx-danger">*</span></label>
						<input type="text" name="txtGroupName" class="form-control" required autofocus>
						<ul class="fields-message"></ul>
					</div>

				</div>

				<div class="col-md-6">
					<div class="form-group mg-b-0">
						<label class="form-control-label">Status <span class="tx-danger">*</span></label>
						<select class="form-control select-two" name="cbStatus" data-placeholder="Select Status" required>
							<option></option>
							<option value="<?php print STATUS_ENABLE;?>">Enable</option>
							<option value="<?php print STATUS_DISABLE;?>">Disable</option>
						</select>
						<ul class="fields-message"></ul>
					</div>
				</div>

				<div class="col-md-6 pd-t-15">
					<div class="form-group">
						<label class="ckbox ckbox-danger">
							<input type="checkbox" value="1" name="cbDefaultGroup" <?php echo set_checkbox('cbDefaultGroup', '1'); ?>>
							<span>Is Default Group</span>
						</label>
						<ul class="fields-message">
						</ul>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group mg-t-20">
						<div class="row">

							<?php foreach ($arr_available_role as $controller => $value): ?>

							<div class="col-md-4">
								<h6 class="tx-inverse tx-uppercase tx-bold tx-14 mg-b-20"></h6>
								<label class="ckbox ckbox-danger">
									<input type="checkbox" class="mark-all-ingroup"><span class="tx-inverse tx-uppercase tx-bold tx-14 mg-b-20"><?php print $value->name;?></span>
								</label>

								<ul class="role-group-list list-group list-group-striped">
									<?php foreach ($value->methods as $method): ?>
									<li class="list-group-item rounded-top-0">
										<label class="ckbox mg-b-0">
											<input type="checkbox" name="cbxGroupRoles[<?php print $controller;?>][<?php print $method->key;?>]"  value="1" <?php echo set_checkbox('cbxGroupRoles[' . $controller . '][' . $method->key . ']', '1', ($this->input->get('cbxGroupRoles[' . $controller . '][' . $method->key . ']') == '1')); ?> >
											<span><strong class="tx-inverse tx-medium"><?php print $method->name;?></strong></span>
										</label>
									</li>
									<?php endforeach;?>
								</ul>
							</div>

							<?php endforeach;?>

						</div>
					</div>
				</div>
			</div>
			
		</div>
		<div class="card-footer bd-color-gray-lighter text-right">
			<button type="submit" class="btn btn-primary tx-size-xs ">Submit</button>
		</div>

		<?php print form_close();?>

	</div>
</div>


<script type="text/javascript">
$(document).ready(function() {
	$('#form-user-group-update').on('submit', function(event){
		event.preventDefault();
		var request 	= '<?php print $this->req_controller;?>/<?php print $this->action;?>',
			form 		= $(this);

		loading(form, 'show');
		ajax_post(request, form.serialize(), function(result) {

			init_meta(result.meta);
			loading(form, 'hide');
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
});
</script>