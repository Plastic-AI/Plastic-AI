<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport">
	<title>PlasticAI - Authenticate</title>
	<link href="<?php print base_url('files/images/favicon.png');?>" rel="shortcut icon">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/Ionicons/css/ionicons.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/SpinKit/spinkit.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/css/bracket.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/css/particle.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/css/costom.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/css/animate.css');?>">

	<script src="<?php print base_url('assets/lib/jquery/jquery.js');?>"></script>
	<script type="text/javascript">
		var request = {
			base_url 	: '<?php print base_url();?>',
			csrf_name 	: '<?php print $this->security->get_csrf_token_name();?>',
			csrf_hash 	: '<?php print $this->security->get_csrf_hash();?>'
		};
	</script>
</head>
<body>
	<div id="particles">
		<div id="webcoderskull" class="single-layout d-flex align-items-center justify-content-center ht-100v">

			<?php echo form_open(NULL, array('id' => 'login', 'class' => 'login-wrapper wd-300 wd-xs-400 pd-25 pd-xs-40 bg-white rounded shadow-base animated bounceIn')); ?>

			<div class="signin-logo tx-center tx-30 tx-bold tx-inverse">
				<!-- <img src="<?php print base_url('files/images/logo.png');?>" width="100"> -->
				<span class="tx-normal">[</span> Plastic<span class="tx-primary">AI</span> <span class="tx-normal">]</span>
			</div>

			<div class="mg-y-20">
				<?php $this->load->view('layouts/message');?>
			</div>

			<div class="text-center mg-b-10">
				<div class="mg-b-10">Please Scan Barcode Below</div>
				<div>
					<img class="img img-thumbnail img-responsive" src="<?php print $img_qr_code;?>">
				</div>
			</div>

			<div class="form-group">
				<input class="form-control" type="text" name="code" value="<?php print set_value('code');?>" placeholder="Authenticator Code">
				<ul class="field-message parsley-errors-list filled">
					<?php if(isset($_POST['code'])) echo form_error('code', '<li class="tx-danger-100">', '</li>'); ?>
				</ul>
				<a target="_blank" href="<?php print AUTH_HELP;?>" class="tx-primary tx-12 d-block mg-t-10">What is this?</a>
			</div>

			<button class="btn btn-primary btn-block" type="submit">Submit</button>
			<div class="text-center mg-t-25">
				<a href="<?php print base_url('logout');?>" class="btn btn-outline-danger rounded">
					<i class="ion ion-md-swap"></i>
					<span>Change Account</span>
				</a>
			</div>

			<?php print form_close();?>

		</div>
	</div>

	<script src="<?php print base_url('assets/lib/jquery-ui/jquery-ui.js');?>"></script>
	<script src="<?php print base_url('assets/lib/popper.js/popper.js');?>"></script>
	<script src="<?php print base_url('assets/lib/bootstrap/bootstrap.js');?>"></script>
	<script src="<?php print base_url('assets/lib/jquery-validation-1.17.0/dist/jquery.validate.min.js');?>"></script>
	<script src="<?php print base_url('assets/js/bracket.js');?>"></script>
	<script src="<?php print base_url('assets/js/particles.js');?>"></script>
</body>
</html>
