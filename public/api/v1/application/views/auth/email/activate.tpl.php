<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>

<h2 style='text-align:center;margin:auto;width:600px'>Thank you for becoming a new member of Tappyn!</h2>
<br>
<p style='text-align:justify;margin:auto;width:600px'>
	Feel free to check out all of our awesome campaigns, but in order to start creating your own ads, we need you to verify your email <?php echo anchor('auth/activate/'.$id.'/'.$activation, 'here'); ?>.
</p>
<br>
<p style='text-align:justify;margin:auto;width:600px'>
	Good luck!
</p>

<br>
<p style='text-align:center;margin:auto;width:600px;'>
    <a href="<?php echo base_url(); ?>">www.tappyn.com</a>
</p>
