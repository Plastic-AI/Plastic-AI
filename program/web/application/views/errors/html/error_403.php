<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en" class="pos-relative">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


	<title>403 Forbidden!</title>
	<link href="<?php config_item('base_url');?>assets/img/icon.png" rel="shortcut icon">

	<!-- vendor css -->
	<link href="<?php print config_item('base_url');?>assets/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link href="<?php print config_item('base_url');?>assets/lib/Ionicons/css/ionicons.css" rel="stylesheet">

	<!-- Bracket CSS -->
	<link rel="stylesheet" href="<?php print config_item('base_url');?>assets/css/bracket.css">
</head>

<body class="pos-relative">

	<div class="ht-100v d-flex align-items-center justify-content-center">
		<div class="wd-lg-70p wd-xl-50p tx-center pd-x-40">
			<h1 class="tx-100 tx-xs-140 tx-normal tx-inverse tx-roboto mg-b-0">403!</h1>
			<h5 class="tx-xs-24 tx-normal tx-info mg-b-30 lh-5">You don't have permission to access this page on this server.</h5>
			<p class="tx-16 mg-b-30">The page you are looking for has been protected by administrator.</p>
			<div class="tx-inverse tx-24 mg-b-15 align-middle">
				<i class="fa fa-linux fa-3x fa-flip-horizontal"></i>
				<a href="<?php print config_item('base_url');?>" class="btn btn-primary btn-lg mg-x-20"><i class="icon ion-ios-home-outline" aria-hidden="true"></i> Let's Back Home</a>
				<i class="fa fa-linux fa-3x"></i>
			</div>

		</div>
	</div><!-- ht-100v -->

	<script src="<?php print config_item('base_url');?>assets/lib/jquery/jquery.js"></script>
	<script src="<?php print config_item('base_url');?>assets/lib/popper.js/popper.js"></script>
	<script src="<?php print config_item('base_url');?>assets/lib/bootstrap/bootstrap.js"></script>

</body>
</html>
