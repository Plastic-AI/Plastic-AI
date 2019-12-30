<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport">

	<title>PlasticAI - <?php print isset($title) ? $title : 'Untitled';?></title>
	<link href="<?php print base_url('files/images/favicon.png');?>" rel="shortcut icon">

	<link rel="stylesheet" href="<?php print base_url('assets/lib/Ionicons/css/ionicons.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/perfect-scrollbar/css/perfect-scrollbar.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/jquery-switchbutton/jquery.switchButton.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/select2/css/select2.min.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/SpinKit/spinkit.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/summernote/summernote-bs4.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/noty/noty.css');?>" >
	<link rel="stylesheet" href="<?php print base_url('assets/lib/noty/themes/bootstrap-v4.css');?>" >
	<link rel="stylesheet" href="<?php print base_url('assets/css/bracket.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/css/costom.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/css/animate.css');?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.32.0/sweetalert2.css">

	<script src="<?php print base_url('assets/lib/jquery/jquery.js');?>"></script>
	<script src="<?php print base_url('assets/lib/jquery-ui/jquery-ui.js');?>"></script>
	<script src="<?php print base_url('assets/lib/chart.js/Chart.js');?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.32.0/sweetalert2.js"></script>
	<script type="text/javascript">
		var request = {
			base_url 	: '<?php print base_url();?>',
			ajax_url 	: '<?php print base_url('request/');?>',
			csrf_name 	: '<?php print $this->security->get_csrf_token_name();?>',
			csrf_hash 	: '<?php print $this->security->get_csrf_hash();?>'
		};
		function logoutConfirm(link){
			Swal({
				text: 'Are you sure you want to logout?',
				animation: false,
  				customClass: 'animated tada',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Yes, logout now!',
				cancelButtonText: 'Cancel',
			}).then((result) => {
				if (result.value) {
					location.href = link;
				}
			})
		}
	</script>


</head>
<body>

	<!-- Content begin here-->
	<div class="br-logo tx-center">
		<a href="<?php print base_url('dashboard');?>">
			<span>[</span>Plastic<i>AI</i><span>]</span></a>
		</a>
	</div>
	<div class="br-sideleft overflow-y-auto">
		<ul class="br-sideleft-menu">
			<?php if (isset($this->role->dashboard)): ?>
			<li class="br-menu-item">
				<a class="br-menu-link" href="<?php print base_url('dashboard');?>">
					<i class="menu-item-icon ion ion-md-grid tx-24"></i>
					<span class="menu-item-label">Dashboard</span>
				</a>
			</li>
			<?php endif;?>

			<?php if (isset($this->role->detect_plastic)): ?>
			<li class="br-menu-item">
				<a class="br-menu-link" href="<?php print base_url('detect_plastic');?>">
					<i class="menu-item-icon icon ion ion-md-trash tx-24"></i>
					<span class="menu-item-label">Detect Plastic</span>
				</a>
			</li>
			<?php endif;?>

			<?php if (isset($this->role->apidocs)): ?>
				<li class="br-menu-item">
					<a class="br-menu-link" href="<?php print base_url('apidocs');?>">
						<i class="menu-item-icon icon ion ion-md-bonfire tx-24"></i>
						<span class="menu-item-label">API Documentation</span>
					</a>
				</li>
			<?php endif;?>

			<?php if (isset($this->role->detection_logs)): ?>
			<li class="br-menu-item">
				<a class="br-menu-link" href="<?php print base_url('detection_logs');?>">
					<i class="menu-item-icon icon ion ion-md-walk tx-24"></i>
					<span class="menu-item-label">Detection Logs</span>
				</a>
			</li>
			<?php endif;?>

			<?php if (isset($this->role->user->change_password)): ?>
			<li class="br-menu-item">
				<a class="br-menu-link" href="<?php print base_url('user/change_password');?>">
					<i class="menu-item-icon icon ion ion-md-unlock tx-24"></i>
					<span class="menu-item-label">Change Password</span>
				</a>
			</li>
			<?php endif;?>

			<li class="br-menu-item">
				<a class="br-menu-link" target="_blank" href="<?php print CONTACT;?>">
					<i class="menu-item-icon icon ion ion-md-chatboxes tx-24"></i>
					<span class="menu-item-label">Support</span>
				</a>
			</li>

			<?php if (isset($this->role->setting) || isset($this->role->change_logs)): ?>
				<label class="sidebar-label pd-x-10 mg-t-25 mg-b-20 tx-primary">Site Management</label>

				<?php if (isset($this->role->setting)): ?>
				<li class="br-menu-item">
					<a class="br-menu-link" href="<?php print base_url('setting');?>">
						<i class="menu-item-icon icon ion-ios-paper tx-24"></i>
						<span class="menu-item-label">Setting</span>
					</a>
				</li>
				<?php endif;?>

				<?php if (isset($this->role->change_logs)): ?>
				<li class="br-menu-item">
					<a class="br-menu-link" href="<?php print base_url('change_logs');?>">
						<i class="menu-item-icon icon ion-ios-stats tx-24"></i>
						<span class="menu-item-label">Change Logs</span>
					</a>
				</li>
				<?php endif;?>
			<?php endif;?>

			<?php if (isset($this->role->user) && isset($this->role->user_group)): ?>
				<?php if (isset($this->role->user)): ?>
				<li class="br-menu-item">
					<a class="br-menu-link" href="<?php print base_url('user');?>">
						<i class="menu-item-icon icon ion-ios-person tx-24"></i>
						<span class="menu-item-label">User</span>
					</a>
				</li>
				<?php endif;?>

				<?php if (isset($this->role->user_group)): ?>
				<li class="br-menu-item">
					<a class="br-menu-link" href="<?php print base_url('user_group');?>">
						<i class="menu-item-icon icon ion-ios-people tx-24"></i>
						<span class="menu-item-label">User Group</span>
					</a>
				</li>
				<?php endif;?>
			<?php endif;?>

		</ul>
	</div>
	<div class="br-header">
		<div class="navicon-left hidden-md-down">
			<a id="btnLeftMenu" href="#">
				<i class="icon ion-md-menu"></i>
			</a>
		</div>
		<div class="navicon-left hidden-lg-up">
			<a id="btnLeftMenuMobile" href="#">
				<i class="icon ion-md-menu"></i>
			</a>
		</div>
		<div class="br-header-right">
			<nav class="nav">
				<a class="btn btn-outline-danger btn-oblong" href="javascript:;" onclick="return logoutConfirm('<?php print base_url('logout');?>');" ><i class="icon ion ion-md-power tx-18"></i> Logout</a>
			</nav>
		</div>
	</div>
	<div class="br-mainpanel">
		<div class="br-pagetitle">
			<h4><?php print isset($title) ? $title : 'Untitled';?></h4>
		</div>
		<div class="br-pagebody">

			<?php if (isset($page)): ?>
				<?php $this->load->view($page);?>
			<?php endif;?>

			<footer class="br-footer">
				<div class="footer-left">
				</div>
				<div class="footer-right d-flex align-items-center">
				</div>
			</footer>
		</div>
		<?php $this->load->view('layouts/message');?>
	</div>

	<script src="<?php print base_url('assets/lib/popper.js/popper.js');?>"></script>
	<script src="<?php print base_url('assets/lib/bootstrap/bootstrap.js');?>"></script>
	<script src="<?php print base_url('assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js');?>"></script>
	<script src="<?php print base_url('assets/lib/moment/moment.js');?>"></script>
	<script src="<?php print base_url('assets/lib/select2/js/select2.min.js');?>"></script>
	<script src="<?php print base_url('assets/lib/jquery.mask/jquery.mask.js');?>"></script>
	<script src="<?php print base_url('assets/lib/summernote/summernote-bs4.min.js');?>"></script>
	<script src="<?php print base_url('assets/lib/noty/noty.js');?>" type="text/javascript"></script>
	<script src="<?php print base_url('assets/js/bracket.js');?>"></script>
	<script src="<?php print base_url('assets/js/script.js');?>"></script>

</body>
</html>