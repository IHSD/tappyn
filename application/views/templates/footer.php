<div id="footer">
	<footer>
	    <div class="row">
	        <div class="columns medium-6">
	            <ul class="footer-nav">
	                <li><a href="<?php echo base_url().'contests'; ?>">Contests</a></li>
	                <li><a href="<?php echo base_url().'contact'; ?>">Contact Us</a></li>
	                <li><a href="<?php echo base_url().'faq'; ?>">FAQ</a></li>
	                <li><a href="<?php echo base_url().'terms'; ?>">Terms of Use</a></li>
	            </ul>
	        </div>
	        <div class="column medium-6">
	            <ul class="socials">
	                <li>
	                    <a href="#"><img src="<?php echo base_url().'public/img/ico-facebook.svg' ?>"></a>
	                </li>
	                <li>
	                    <a href="#"><img src="<?php echo base_url().'public/img/ico-twitter.svg' ?>"></a>
	                </li>
	                <li>
	                    <a href="#"><img src="<?php echo base_url().'public/img/ico-linkedin.svg' ?>"></a>
	                </li>
	                <li>
	                    <a href="#"><img src="<?php echo base_url().'public/img/ico-mail.svg' ?>"></a>
	                </li>
	            </ul>
	        </div>
	    </div>
	    <div class="row">
	        <div class="medium-12 column">
	            <span class="copyrights">&copy; Tappyn2015. All rights reserved.</span>
	        </div>
	    </div>
	<?php if($this->ion_auth->logged_in()): ?>
		<script>
		 window.intercomSettings = {
			app_id: "qj6arzfj",
			name: "<?php echo $this->ion_auth->user()->row()->first_name.' '.$this->ion_auth->user()->row()->last_name ?>", // Full name
			email: "<?php echo $this->ion_auth->user()->row()->email ?>", // Email address
			created_at: <?php echo $this->ion_auth->user()->row()->created_on ?> // Signup date as a Unix timestamp
		};
		</script>
		<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/qj6arzfj';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
	<?php endif; ?>
	</footer>
</div>
</html>
