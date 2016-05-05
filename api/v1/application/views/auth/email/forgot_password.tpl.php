<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>

<h3 style='text-align:center'>Forgot your password?</h3>

<p style='text-align:center'>
    Somebody requested to reset your password. If that was you, click the link below to continue.
</p>

<p style='text-align:center'>
	<a style='text-align:center;display:inline-block;background:#FF5E00;border-radius:4px;color:#fff;font-height:400;font-size:18px;width:250px;height:50px;padding:0;line-height:50px;text-decoration:none' href="<?php echo  base_url().'reset_pass/'. $forgotten_password_code;?>">
        Reset My Password
    </a>
</p>

<p style='text-align:center'>
	If, by chance, you didn't request this action, click <a href="#">here</a> to notify us immediately. We'll take care of the rest!
</p>
