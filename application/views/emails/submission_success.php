<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<br>
<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>

<h3 style='text-align:center'>Boom. You just Tapped In.</h3>

<p style='text-align:center'>
    Great news - your submission to Tappyn has been confirmed. Yup - it's go time.
</p>
<p style='text-align:center'>
    For your records, here is a copy your submission:
</p>
<p style='text-align:center;margin:auto;width:500px;border-bottom:2px solid #FF5E00'></p>
<h4 style='text-align:center'><?php echo $contest. ' by ' . $company; ?></h4>

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
<br>
<p style='text-align:center;margin:auto;width:500px;border-bottom:2px solid #FF5E00'></p>
<p style='text-align:center'>
    In order to collect your winnings and keep up with the contests,<br>
    login to your account here: <br>
</p>
<p style='text-align:center'>
    <a style='display:inline-block;background:#FF5E00;border-radius:4px;color:#fff;font-height:400;font-size: 18px;width:250px;height:50px;padding:0;line-height:50px;text-decoration:none' href="<?php echo base_url().'#/dashboard'; ?>">
        Dashboard
    </a>
<p>
