<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<h3 style='text-align:center'>Boom. You're registered.</h3>

<h3 style='text-align:center'>You just Tapped In.</h3>

<p style='text-align:center'>
    Great news - your Tappyn account has successfully been created!
</p>
<br>
<table style='max-width:500px' align='center'>
    <tr>
        <td>
            <p style='max-width:500px;text-align:left;font-weight:700'>
                <?php echo $headline; ?>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p style='max-width: 500px;text-align:justify;'>
                <?php echo $text; ?>
            </p>
        </td>
    </tr>
</table>
<h4 style='text-align:center'><?php echo $contest. ' by ' . $company; ?></h4>
<p style='text-align:center'>
    In order to collect your winnings and keep up with the contests,<br>
    login to your account here: <br>
</p>
<p style='text-align:center'>
    <a style='display:inline-block;background:#FF5E00;border-radius:4px;color:#fff;font-height:400;font-size: 18px;width:250px;height:50px;padding:0;line-height:50px;text-decoration:none' href="<?php echo base_url().'dashboard'; ?>">
        Dashboard
    </a>
<p>

<p style='text-align:center'>If at any time you want to stop receiving e-mails, you can <a href="<?php echo base_url().'unsubscribe?email=rob@ihsdigital.com'; ?>">Unsubscribe Here</a><p>
