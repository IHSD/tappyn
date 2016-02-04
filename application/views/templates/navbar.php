<?php
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
	</head>

	<header>
	    <div class="row">
	        <div class="column medium-12">
	            <div class="columns small-3 medium-1">
	                <div class="logo">
	                    <a href="#"><%= link_to image_tag('logo.png', size: '68x33'), root_url %></a>
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
	                        <li><a href=''>DASHBOARD</a></li>
	                        <li><a href=''>HOME</a></li>	                        
	                        <li><a href=''>CONTESTS</a></li>	                        
	                        <li><a href=''>LAUNCH CONTEST</a></li>	                        
	                        <li><a href=''>HOW IT WORKS</a></li>	                        
	                        <li><a href=''>CONTACT US</a></li>
	                        <li><a href=''>FAQS</a></li>	                        
                            <li class="login-li">
                                <div class="login-box">
                                    <ul class="dropdown menu" data-dropdown-menu>
                                        <li>
                                            <a href="#0" class="username">Name</a>
                                            <ul class="menu">
                                                <li><a href=''>Profile</a></li>
                                                <li><a href=''>Log out</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="login-li">
                                <div class="login-box">
                                    <a href=''>LOGIN</a>
                                    <a href=''>SIGN UP</a>    
                                </div>
                            </li>	                       
	                    </ul>
	                </nav>
	            </div>
	        </div>
	    </div>
	</header>

?>