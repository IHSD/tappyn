
<html>
    <head>
        <title>Tappyn</title>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="google-site-verification" content="4pmD1O3gQ8tvABnuXFko0Vn1L0MozLiFTyduIv6D_Xk" />
        <link href='https://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
        <link href="<?php echo base_url().'public/css/foundation.css' ?>" rel="stylesheet">
		<link href="<?php echo base_url().'public/css/app.css' ?>" rel="stylesheet">
		<link href="<?php echo base_url().'public/css/slick.css' ?>" rel="stylesheet">
		<link href="<?php echo base_url().'public/css/jcf.css' ?>" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="<?php echo base_url().'public/js/custom-select.js' ?>" type='text/javascript'></script>
		<script src="<?php echo base_url().'public/js/foundation.min.js'?>" type='text/javascript'></script>
        <script src="<?php echo base_url().'public/js/vendor/facebook.js' ?>" type='text/javascript'></script>
        <script src="<?php echo base_url().'public/js/vendor/google_analytics.js' ?>" type='text/javascript'></script>
        <script src="<?php echo base_url().'public/js/vendor/hotjar.js' ?>" type='text/javascript'></script>
        <script src="<?php echo base_url().'public/js/vendor/lucky_orange.js' ?>" type='text/javascript'></script>
	</head>

	<header>
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=879448002090308";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
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
	                    	<?php if($this->ion_auth->logged_in()) : ?>
	                    	<li><a href="<?php echo base_url().'dashboard'; ?>">Dashboard</a></li>
	                   		<?php endif ?>
	                        <li><a href="<?php echo base_url().'contests'; ?>">Contests</a></li>
	                        <li><a href="<?php echo base_url().'contests/create'; ?>">Launch</a></li>
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
