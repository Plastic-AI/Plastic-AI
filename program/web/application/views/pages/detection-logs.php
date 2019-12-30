<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="card bd-0 shadow-base pd-15">
	<div class="bg-gray-300 bd pd-15 mg-b-15 rounded">
		<form method="GET" action="<?php print base_url($this->controller . '/' . $this->action);?>">
			<div class="row row-sm">
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">Plastic Type</label>
						<input type="text" name="txtPlasticType" class="form-control" value="<?php print !empty($this->input->get('txtPlasticType')) ? $this->input->get('txtPlasticType') : '';?>" placeholder="Ex: HDPE">
					</div>
				</div>
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">From Date</label>
						<div class="input-group">
							<input name="txtDateFrom" autocomplete="off" type="text" class="form-control fc-datepicker" value="<?php print !empty($this->input->get('txtDateFrom')) ? $this->input->get('txtDateFrom') : '';?>" placeholder="MM/DD/YYYY">
						</div>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">To Date</label>
						<div class="input-group">
							<input name="txtDateTo" autocomplete="off" type="text" class="form-control fc-datepicker" value="<?php print !empty($this->input->get('txtDateTo')) ? $this->input->get('txtDateTo') : '';?>" placeholder="MM/DD/YYYY">
						</div>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">Status</label>
						<select name="cbStatus" class="form-control select-two" data-placeholder="-- Select --" data-allow-clear="true">
							<option value="<?php echo set_select('cbStatus', '', empty($this->input->get('cbStatus'))); ?>">All</option>
							<option value="<?php print STATUS_ENABLE;?>" <?php echo set_select('cbStatus', STATUS_ENABLE, (STATUS_ENABLE == $this->input->get('cbStatus'))); ?>>Enable</option>
							<option value="<?php print STATUS_DISABLE;?>" <?php echo set_select('cbStatus', STATUS_DISABLE, (STATUS_DISABLE == $this->input->get('cbStatus'))); ?>>Disable</option>
						</select>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">Order By</label>
						<select name="cbOrderBy" class="form-control select-two" data-placeholder="-- Select --" data-allow-clear="true">
							<option value="DESC" <?php echo set_select('cbOrderBy', 'DESC', ('DESC' == $this->input->get('cbOrderBy'))); ?>>Descending </option>
							<option value="ASC" <?php echo set_select('cbOrderBy', 'ASC', ('ASC' == $this->input->get('cbOrderBy'))); ?>>Ascending </option>
						</select>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<div class="d-flex mg-xs-t-0 btn-action-table">
						<button type="submit" class="btn btn-primary btn-oblong mg-r-5" >
							<div><i class="fa fa-search"></i> Search</div>
						</button>
						<a href="<?php print base_url($this->controller . '/' . $this->action);?>" class="btn btn-secondary btn-icon rounded-circle" data-toggle="tooltip" data-placement="right" title="Hapus Filter">
							<div><i class="ion ion-md-trash"></i></div>
						</a>
					</div>
				</div>
			</div>
		</form>
	</div>

	<div class="card-block mg-b-15">

		<?php if (isset($this->role->{$this->controller}->add)): ?>
			<a href="<?php print base_url($this->controller . '/add');?>" class="btn btn-primary">
				<i class="ion ion-md-add-circle-outline"></i> ADD
			</a>
		<?php endif;?>

	</div>

	<?php if (!empty($arr_checking_log)): ?>

		<div class="bd bd-gray-300 rounded table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th class="align-middle">Date</th>
						<th class="align-middle">Created By</th>
						<th class="align-middle">Plastic Type</th>
						<th class="align-middle">Percentage (%)</th>
						<th class="align-middle">Time Used (second)</th>
						<th class="align-middle">Preview</th>
						<th class="text-center align-middle">Status</th>
						<th class="text-center align-middle"></th>
					</tr>
				</thead>
				<tbody>

					<?php foreach ($arr_checking_log as $value): ?>
					<tr>
						<td class="align-middle">
							<?php print timestamp_to_date($value->created);?>
						</td>
						<td class="align-middle">
							<?php print $value->created_by;?>
						</td>
						<td class="align-middle">
							<?php print strtoupper($value->plastic_type);?>
						</td>
						<td class="align-middle">
							<?php print $value->plastic_percentage ? $value->plastic_percentage : '-';?>
						</td>
						<td class="align-middle">
							<?php print $value->time_used;?>
						</td>
						<td class="align-middle">
							<a target="_blank" href="<?php print base_url($value->plastic_file);?>"><img src="<?php print base_url($value->plastic_file);?>" width="60"/></a>
						</td>
						<td class="text-center align-middle">
							<?php if ($value->plastic_status == STATUS_ENABLE): ?>
								<span class="badge badge-success">Enable</span>
							<?php elseif ($value->plastic_status == STATUS_DISABLE): ?>
								<span class="badge badge-danger">Disable</span>
							<?php endif;?>
						</td>
						<td class="min-w text-center align-middle">
							<?php if (isset($this->role->{$this->controller}->detail)): ?>
								<a href="<?php print base_url($this->controller . '/detail/' . $value->request_id);?>" class="btn btn-outline-info btn-icon rounded-circle" data-toggle="tooltip" data-placement="bottom" title="Detail">
									<div class="tx-20"><i class="icon ion-md-eye"></i></div>
								</a>
							<?php endif;?>
							<?php if (isset($this->role->{$this->controller}->delete)): ?>
								<a href="javascript:;" onclick="deleteConfirm('<?php print base_url($this->controller . '/delete/' . $value->checking_log_id);?>');" class="btn btn-outline-danger btn-icon rounded-circle" data-toggle="tooltip" data-placement="bottom" title="Delete">
									<div class="tx-20"><i class="icon ion-md-trash"></i></div>
								</a>
							<?php endif;?>
						</td>
					</tr>
					<?php endforeach;?>

				</tbody>
			</table>
		</div>

		<?php if (!empty($this->pagination->create_links())): ?>

			<div class="ht-80 d-flex align-items-center justify-content-center">
				<nav aria-label="Page navigation">
					<?php print $this->pagination->create_links();?>
				</nav>
			</div>

		<?php endif;?>

	<?php else: ?>

	<div class="mg-t-30">
		<h3 class="text-center">No record found</h3>
	</div>

	<?php endif;?>

</div>

<script>
	$('.fc-datepicker').datepicker({
		showOtherMonths: true,
		selectOtherMonths: true
	});
	function deleteConfirm(link){
    	Swal({
			title: 'Are you sure?',
			text: "You will not be able to return this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete this!',
			cancelButtonText: 'Cancel',
		}).then((result) => {
  			if (result.value) {
    			location.href = link;
  			}
		})
	}
</script>