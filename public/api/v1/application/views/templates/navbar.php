
<html>
    <head>
        <title>Tappyn</title>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href='https://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
		<link href="<?php echo base_url().'public/css/app.css' ?>" rel="stylesheet">
        <link href="<?php echo base_url().'public/css/admin.css' ?>" rel='stylesheet'>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url().'public/css/bootstrap-datepicker.css'; ?>" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
        <script src="<?php echo base_url().'public/js/bootstrap-datepicker.js'; ?>"></script>

    </head>
    <header>
	        <div class="col-sm-3">
	            <div class="logo">
	                <a href="<?php echo base_url().'#/home' ?>">
	                	<img src="<?php echo base_url().'public/img/TappynLogo2.png' ?>" width='70'>
	                </a>
	            </div>
	        </div>
	        <div class="col-sm-9">
	            <div id="nav-icon4" data-toggle='example-dropdown'>
	                <span></span>
	                <span></span>
	                <span></span>
	            </div>

	            <nav>
	                <ul>
	                	<li><a href="<?php echo base_url().'admin/users' ?>">Users</a></li>
	                    <li><a href="<?php echo base_url().'admin/contests' ?>">Contests</a></li>
                        <li><a href="<?php echo base_url().'admin/submissions' ?>">Submissions</a></li>
                        <li><a href="<?php echo base_url().'admin/payments' ?>">Payouts</a></li>
                        <li><a href="<?php echo base_url().'admin/vouchers' ?>">Vouchers</a></li>

	                    <!-- <li style='float:right;' uib-dropdown>
                            <a href uib-dropdown-toggle>{{user.first_name}}<span class="caret"></span></a>
                            <ul uib-dropdown-menu role="menu">
                                <li role='menuitem'><a href="<?php echo base_url().'#/payment' ?>">Payment</a></li>
	                            <li role='menuitem'><a href="<?php echo base_url().'#/profile' ?>">Profile</a></li>
	                            <li role='menuitem'><a href="<?php echo base_url().'auth/logout' ?>">Log out</a></li>
                            </ul>
	                    </li> -->

                        <li class='pull-right'><a href="#"><?php echo $this->ion_auth->user()->row()->first_name.' '.$this->ion_auth->user()->row()->last_name; ?></a></li>
                        <li class='pull-right'><a href="<?php echo base_url().'#/register' ?>">LOGOUT</a></li>

	                </ul>
	            </nav>
	        </div>
	</header>
    <div class='container-fluid navbar-fixed-top'>
        <div class='alert alert-warning'>
            This Administration Panel has been deprecated. Please start using the <a href="https://admin.tappyn.com">new panel going forward!</a>
        </div>
