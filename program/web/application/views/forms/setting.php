<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
	<div class="col-md-7 col-sm-12">

		<?php print form_open(current_url(), array('id' => 'form-botsetting', 'class' => 'card shadow-base bd-0'));?>

		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label class="form-control-label">Telegram API <span class="tx-danger">*</span></label>
						<input class="form-control" type="text" name="txtTelegramApi" value="<?php echo set_value('txtTelegramApi', !empty($arr_setting->telegram_api) ? $arr_setting->telegram_api : ''); ?>" placeholder="Ex: 558611721:AAH3mGcecXdx_6FCLgR8LKWoEiZziXXXXXX" required>
						<ul class="fields-message">
							<?php echo form_error('txtTelegramApi', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label class="form-control-label">Telegram Chat ID <span class="tx-danger">*</span></label>
						<input class="form-control" type="text" name="txtTelegramChatid" value="<?php echo set_value('txtTelegramChatid', !empty($arr_setting->telegram_chatid) ? $arr_setting->telegram_chatid : ''); ?>" placeholder="Ex: -123XXXXXX" required>
						<ul class="fields-message">
							<?php echo form_error('txtTelegramChatid', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label class="form-control-label">Detail <span class="tx-danger">*</span></label>
						<textarea type="text" name="txtDetail" class="form-control" required><?php echo $arr_setting->notification_detail;?></textarea>
						<ul class="fields-message"></ul>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label class="form-control-label">Status <span class="tx-danger">*</span></label>
						<select class="form-control select-two" name="cbStatus" data-placeholder="-- Select Status --">
							<option></option>
							<option value="<?php print STATUS_ENABLE;?>" <?php echo set_select('cbStatus', STATUS_ENABLE, (STATUS_ENABLE == $arr_setting->notification_status)); ?>>Enable</option>
							<option value="<?php print STATUS_DISABLE;?>" <?php echo set_select('cbStatus', STATUS_DISABLE, (STATUS_DISABLE == $arr_setting->notification_status)); ?>>Disable</option>
						</select>
						<ul class="fields-message">
							<?php echo form_error('cbStatus', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
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

<?php if (!empty($arr_setting->updated) && !empty($arr_setting->updated_by) && !empty($arr_setting->created) && !empty($arr_setting->created_by)): ?>
<div class="card shadow-base bd-0 mg-b-20">
	<div class="card-body bg-transparent pd-0 bd-gray-200 mg-t-auto">
		<div class="row no-gutters tx-center">
			<?php if (!empty($arr_setting->updated) && !empty($arr_setting->updated_by)): ?>
			<div class="col pd-y-15">
				<p class="mg-b-5 tx-uppercase tx-12 tx-mont tx-semibold">Terakhir Diubah</p>
				<h4 class="tx-16 tx-bold mg-b-0 tx-inverse">
					<?php print strtoupper($arr_setting->updated_by);?>
				</h4>
				<span class="tx-12 tx-primary tx-roboto">
					<?php print timestamp_to_date($arr_setting->updated);?>
				</span>
			</div>
			<?php endif;?>


			<div class="col pd-y-15 bd-l bd-gray-200">
				<?php if (!empty($arr_setting->created) && !empty($arr_setting->created_by)): ?>
				<p class="mg-b-5 tx-uppercase tx-12 tx-mont tx-semibold">Dibuat</p>
				<h4 class="tx-16 tx-inverse tx-bold mg-b-0">
					<?php print strtoupper($arr_setting->created_by);?>
				</h4>
				<span class="tx-12 tx-primary tx-roboto">
					<?php print timestamp_to_date($arr_setting->created);?>
				</span>
			</div>
			<?php endif;?>
		</div>
	</div>
</div>
<?php endif;?>

<?php $this->load->view('layouts/change-log');?>
</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
get_action_log();

	$('#form-botsetting').on('submit', function(event){
		event.preventDefault();
		var request 	= '<?php print $this->req_controller;?>/<?php print $this->action;?>',
			form 		= $(this);

		loading(form, 'show');
		ajax_post(request, form.serialize(), function(result) {

			init_meta(result.meta);
			get_action_log();
			loading(form, 'hide');
		});
	});

	$('.fc-datepicker').datepicker({
		showOtherMonths: true,
		selectOtherMonths: true
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