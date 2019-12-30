<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="card bd-0 shadow-base pd-15">
	<div class="bg-gray-300 bd pd-15 mg-b-15 rounded">
		<form method="GET" action="<?php print base_url($this->controller . '/' . $this->action);?>">
			<div class="row row-sm">

				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">Created By</label>
						<input class="form-control" type="text" name="txtCreatedBy" value="<?php print !empty($this->input->get('txtCreatedBy')) ? $this->input->get('txtCreatedBy') : '';?>" placeholder="Created By">
					</div>
				</div>
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">Page</label>
						<input class="form-control" type="text" name="txtPage" value="<?php print !empty($this->input->get('txtPage')) ? $this->input->get('txtPage') : '';?>" placeholder="Page">
					</div>
				</div>
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">Action</label>
						<input class="form-control" type="text" name="txtAction" value="<?php print !empty($this->input->get('txtAction')) ? $this->input->get('txtAction') : '';?>" placeholder="Action">
					</div>
				</div>
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">IP Address</label>
						<input class="form-control" type="text" name="txtIP" value="<?php print !empty($this->input->get('txtIP')) ? $this->input->get('txtIP') : '';?>" placeholder="IP Address">
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
			</div>

			<div class="row">
				<div class="col-lg-12">
					<div class="d-flex mg-xs-t-0 btn-action-table">
						<button type="submit" class="btn btn-primary btn-oblong mg-r-5" >
							<div><i class="fa fa-search"></i> Search </div>
						</button>
						<a href="<?php print base_url($this->controller . '/' . $this->action);?>" class="btn btn-secondary btn-icon rounded-circle" data-toggle="tooltip" data-placement="right" title="Clear Filter">
							<div><i class="ion ion-md-trash"></i></div>
						</a>
					</div>
				</div>
			</div>
		</form>
	</div>

	<?php if (!empty($arr_log)): ?>

		<div class="bd bd-gray-300 rounded table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th class="align-middle">Date</th>
						<th class="align-middle">Created By</th>
						<th class="align-middle">Page</th>
						<th class="align-middle">Action</th>
						<th class="align-middle">IP Address</th>
					</tr>
				</thead>
				<tbody>

					<?php foreach ($arr_log as $value): ?>
					<tr>
						<td class="align-middle">
							<?php print timestamp_to_date($value->created);?>
						</td>
						<td class="align-middle">
							<?php print $value->created_by;?>
						</td>
						<td class="align-middle">
							<?php print $value->controller;?>
						</td>
						<td class="align-middle">
							<?php print $value->action;?>
						</td>
						<td class="align-middle">
							<?php print $value->ip;?>
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
</script>