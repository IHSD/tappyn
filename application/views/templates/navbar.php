
<html>
    <head>
        <title>Raw</title>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href='https://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
        <link href="<?php echo base_url().'public/css/foundation.css' ?>" rel="stylesheet">
		<link href="<?php echo base_url().'public/css/app.css' ?>" rel="stylesheet">
		<link href="<?php echo base_url().'public/css/slick.css' ?>" rel="stylesheet">
		<link href="<?php echo base_url().'public/css/jcf.css' ?>" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="<?php echo base_url().'public/js/custom-select.js' ?>" type='text/javascript'></script>
		<script src="<?php echo base_url().'public/js/foundation.js'?>" type='text/javascript'></script>
		<script src="<?php echo base_url().'public/js/slick.js'?>" type='text/javascript'></script>
		<script src="<?php echo base_url().'public/js/app.js'?>" type='text/javascript'></script>
	</head>

	<header>
	    <div class="row">
	        <div class="column medium-12">
	            <div class="columns small-3 medium-1">
	                <div class="logo">
	                    <a href="<?php echo base_url(); ?>">
	                    	<img src="<?php echo base_url().'public/img/Tappyn1.png'; ?>" width='70'>
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
	                        <li><a href="<?php echo base_url().'contests'; ?>">Contests</a></li>	                        
	                        <li><a href="<?php echo base_url().'contests/create'; ?>">Launch</a></li>	                        
	                        <li><a href="<?php echo base_url().'how_it_works'; ?>">How it Works</a></li>	                        
	                        <li><a href="<?php echo base_url().'contact'; ?>">Contact Us</a></li>
	                        <li><a href="<?php echo base_url().'faq'; ?>">FAQ</a></li>
	                        <?php if($this->ion_auth->logged_in()) : ?>
	                            <li><a href="<?php echo base_url().'profile'; ?>">Profile</a></li>
	                            <li><a href="<?php echo base_url().'logout'; ?>">Log out</a></li>
	                                  
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

