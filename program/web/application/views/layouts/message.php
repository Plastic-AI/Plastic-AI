<?php if ($this->session->flashdata('success')): ?>

	<div class="alert alert-success alert-t-fixed bd-b" role="alert">
		<button type="button" class="close tx-32" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="d-flex align-items-center justify-content-start">
			<i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
			<span>
				<strong>Success!</strong> <?php print $this->session->flashdata('success');?>
			</span>
		</div>
	</div>

<?php elseif ($this->session->flashdata('warning')): ?>

	<div class="alert alert-warning alert-t-fixed bd-b" role="alert">
		<button type="button" class="close tx-32" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="d-flex align-items-center justify-content-start">
			<i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
			<span>
				<strong>WARNING!</strong> - <?php print $this->session->flashdata('warning');?>
			</span>
		</div>
	</div>

<?php elseif ($this->session->flashdata('info')): ?>

	<div class="alert alert-info alert-t-fixed bd-b" role="alert">
		<button type="button" class="close tx-32" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="d-flex align-items-center justify-content-start">
			<i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
			<span>
				<strong>INFO!</strong> - <?php print $this->session->flashdata('info');?>
			</span>
		</div>
	</div>

<?php elseif ($this->session->flashdata('error')): ?>

	<div class="alert alert-danger alert-t-fixed bd-b" role="alert">
		<button type="button" class="close tx-32" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="d-flex align-items-center justify-content-start">
			<i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
			<span>
				<strong>ERROR!</strong> - <?php print $this->session->flashdata('error');?>
			</span>
		</div>
	</div>

<?php elseif (!empty($error)): ?>

	<div class="alert alert-danger alert-t-fixed bd-b" role="alert">
		<button type="button" class="close tx-32" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="d-flex align-items-center justify-content-start">
			<i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
			<span>
				<strong>ERROR!</strong> - <?php print $error;?>
			</span>
		</div>
	</div>

<?php elseif (!empty($message)): ?>

	<div class="alert alert-info alert-t-fixed bd-b" role="alert">
		<button type="button" class="close tx-32" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="d-flex align-items-center justify-content-start">
			<i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
			<span>
				<strong>INFO!</strong> - <?php print $message;?>
			</span>
		</div>
	</div>

<?php elseif ($this->input->get('error')): ?>

	<?php if ($this->input->get('error') == 'invalid-csrf'): ?>
		<div class="alert alert-danger alert-t-fixed bd-b" role="alert">
			<button type="button" class="close tx-32" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<div class="d-flex align-items-center justify-content-start">
				<i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
				<span>
					<strong>INFO!</strong> - Invalid CSRF Token! Please, try again.
				</span>
			</div>
		</div>
	<?php endif;?>

<?php endif;?>