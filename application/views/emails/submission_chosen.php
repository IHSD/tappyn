<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<br>
<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>

<h3 style='text-align:center'>Congratulations!</h3>

<p style='text-align:center'>Your submission for <?php echo $contest->title.' by '.$company->name; ?> won $50!</p>

<p style='text-align:center'>
    In order to collect your winnings,<br>
    login to your account here: <br>
</p>
<p style='text-align:center'>
    <a style='display:inline-block;background:#FF5E00;border-radius:4px;color:#fff;font-height:400;font-size: 18px;width:250px;height:50px;padding:0;line-height:50px;text-decoration:none' href="<?php echo base_url().'#/dashboard'; ?>">
        Dashboard
    </a>
<p>
