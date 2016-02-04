
<html>
    <head>
        <title>Raw</title>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="public/css/foundation.css" rel="stylesheet">
		<link href="public/css/app.css" rel="stylesheet">
		<link href="public/css/slick.css" rel="stylesheet">
		<link href="public/css/jcf.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src='public/js/custom-select.js' type='text/javascript'></script>
		<script src='public/js/foundation.min.js' type='text/javascript'></script>
	</head>

	<header>
	    <div class="row">
	        <div class="column medium-12">
	            <div class="columns small-3 medium-1">
	                <div class="logo">
	                    <a href="<?php echo base_url(); ?>">
	                    	<img src='public/img/logo.png' width='68' height='33'>
	                    </a>
	                </div>
	            </div>
	            <div class="columns small-9 medium-11">
	                <div id="nav-icon4">
	                    <span></span>
	                    <span></span>
	                    <span></span>
	                </div>
	                <nav>
	                    <ul>
	                        <li><a href=''>Home</a></li>	                        
	                        <li><a href=''>Contests</a></li>	                        
	                        <li><a href=''>Launch</a></li>	                        
	                        <li><a href=''>How it Works</a></li>	                        
	                        <li><a href=''>Contact Us</a></li>
	                        <li><a href=''>FAQS</a></li>
	                        <?php if($this->ion_auth->logged_in()) : ?>
								<li class="login-li">
	                                <div class="login-box">
	                                    <ul class="dropdown menu" data-dropdown-menu>
	                                        <li>
	                                            <a href="#0" class="username">N</a>
	                                            <ul class="menu">
	                                                <li><a href="">Profile</a></li>
	                                                <li><a href="">Log out</a></li>
	                                            </ul>
	                                        </li>
	                                    </ul>
	                                </div>
	                            </li>
	                        <?php else : ?>
	                          	<li class="login-li">
	                                <div class="login-box">
	                                    <a href="<?php echo base_url().'login'; ?>">LOGIN</a>
	                                    <a href="<?php echo base_url().'signup'; ?>">SIGN UP</a>    
	                                </div>
	                            </li>
                            <?php endif ?>                       
	                    </ul>
	                </nav>
	            </div>
	        </div>
	    </div>
	</header>

