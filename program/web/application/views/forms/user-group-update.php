<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
	<div class="col-md-7 col-sm-12">

		<?php print form_open(current_url(), array('id' => 'form-user-group-update', 'class' => 'card shadow-base bd-0'));?>

		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Name <span class="tx-danger">*</span></label>
						<input type="text" name="txtGroupName" value="<?php echo set_value('txtGroupName', $arr_user_group->user_group_name); ?>" class="form-control" required>
						<ul class="fields-message"></ul>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Status <span class="tx-danger">*</span></label>
						<select class="form-control select-two" name="cbStatus" data-placeholder="Select Status" required="required">
							<option></option>
							<option value="<?php print STATUS_ENABLE;?>" <?php echo set_select('cbStatus', STATUS_ENABLE, (STATUS_ENABLE == $arr_user_group->user_group_status)); ?>>Enable</option>
							<option value="<?php print STATUS_DISABLE;?>" <?php echo set_select('cbStatus', STATUS_DISABLE, (STATUS_DISABLE == $arr_user_group->user_group_status)); ?>>Disable</option>
						</select>
						<ul class="fields-message"></ul>
					</div>
				</div>

				<div class="col-md-6 pd-t-15">
					<div class="form-group">
						<label class="ckbox ckbox-danger">
							<input type="checkbox" value="1" name="cbDefaultGroup" <?php echo set_checkbox('cbDefaultGroup', '1', $arr_user_group->user_group_default == 1); ?>>
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
											<input type="checkbox" name="cbxGroupRoles[<?php print $controller;?>][<?php print $method->key;?>]"  value="1" <?php echo set_checkbox('cbxGroupRoles[<?php print $controller;?>][<?php print $method->key;?>]', '1', (isset($arr_role->{$controller}->{$method->key}) == '1')); ?> >
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

	<div class="col-md-5 col-sm-12 mg-t-20 mg-md-t-0">

		<div class="card shadow-base bd-0 mg-b-20">
			<?php if (!empty($arr_user_group->updated) && !empty($arr_user_group->updated_by) && !empty($arr_user_group->created) && !empty($arr_user_group->created_by)): ?>
			<div class="card-body bg-transparent pd-0 bd-gray-200 mg-t-auto">
				<div class="row no-gutters tx-center">
					<?php if (!empty($arr_user_group->updated) && !empty($arr_user_group->updated_by)): ?>
					<div class="col pd-y-15">
						<p class="mg-b-5 tx-uppercase tx-12 tx-mont tx-semibold">Last Updated</p>
						<h4 class="tx-16 tx-bold mg-b-0 tx-inverse">
							<?php print strtoupper($arr_user_group->updated_by);?>
						</h4>
						<span class="tx-12 tx-primary tx-roboto">
							<?php print timestamp_to_date($arr_user_group->updated);?>
						</span>
					</div>
					<?php endif;?>


					<div class="col pd-y-15 bd-l bd-gray-200">
						<?php if (!empty($arr_user_group->created) && !empty($arr_user_group->created_by)): ?>
						<p class="mg-b-5 tx-uppercase tx-12 tx-mont tx-semibold">Created</p>
						<h4 class="tx-16 tx-inverse tx-bold mg-b-0">
							<?php print strtoupper($arr_user_group->created_by);?>
						</h4>
						<span class="tx-12 tx-primary tx-roboto">
							<?php print timestamp_to_date($arr_user_group->created);?>
						</span>
					</div>
					<?php endif;?>
				</div>
			</div>
			<?php endif;?>
		</div>

		<?php $this->load->view('layouts/change-log');?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	get_action_log();

	$('#form-user-group-update').on('submit', function(event){
		event.preventDefault();
		var request 	= '<?php print $this->req_controller;?>/<?php print $this->action;?>/<?php print $arr_user_group->user_group_id;?>',
			form 		= $(this);

		loading(form, 'show');
		ajax_post(request, form.serialize(), function(result) {

			init_meta(result.meta);
			get_action_log();
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