<html>
<head>
    <link href="<?php echo base_url().'public/css/bootstrap.min.css'; ?>" rel='stylesheet'>
    <link href="<?php echo base_url().'public/css/app.css'; ?>" rel='stylesheet'>
</head>
    <header>
	        <div class="col-xs-2">
	            <div class="logo">
	                <a href="#/home">
	                	<img src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>" width='70'>
	                </a>
	            </div>
	        </div>
	        <div class="col-xs-10">
	            <nav class='hidden-xs'>
	                <ul>
	                    <li><a href="<?php echo base_url().'#/contests' ?>">See Contests</a></li>
	                    <li><a href="<?php echo base_url().'#/top' ?>">Top Tapps</a></li>
	                    <li><a href="<?php echo base_url().'#/faq' ?>">FAQ</a></li>
	                </ul>
	            </nav>
	            <!-- <nav class='visible-xs'>
	                <ul>
	                	<li style='float:right;' uib-dropdown>
		                	<a href uib-dropdown-toggle> Menu <span class="caret"></span></a>
				            <ul uib-dropdown-menu role='menu'>
				            	<li ng-if='!user' class='pull-right'><a ng-click='open_login("default", "")'>Login</a></li>
		                        <li ng-if='!user' class='pull-right'><a ng-click='open_register("default", "")'>Sign Up</a></li>
				            	<li ng-hide='!user'><a href="#/dashboard">Dashboard</a></li>
			                    <li><a href="#/contests">Contests</a></li>
			                    <li ng-hide='user.type == "member" && !user.is_admin'><a href="#/launch">Launch</a></li>
			                    <li><a href="#/faq">FAQ</a></li>
                                <li ng-hide='!user'><a href="#/payment">Payment</a></li>
	                            <li ng-hide='!user'><a href="#/profile">Profile</a></li>
	                            <li ng-hide='!user'><a ng-click='log_out()'>Log out</a></li>
					        </ul>
					    </li>
	                </ul>
	            </nav> -->
	        </div>
	</header>
<body>
    <div class='container-fluid'>
