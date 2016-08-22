<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * Template for a successful submission.
 *
 * Sent after every submission
 * @param string $contest  Name of the contest they submitted to
 * @param string $company  Name of the company that owns the contest
 * @param string $text     Body text of the submission
 * @param integer $eid     ID of the email, for tracking purposes
 * @param string $headline Headline the user submitted  | Optional
 */

if(!isset($contest) ||
   !isset($company) ||
   !isset($text))
   {
       error_log("Creating email with missing data!");
      // throw new Exception("Tried creating email with missing data");
   }
?>

<br>
<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>

<h2 style='text-align:center;margin:auto;min-width:450px;width:50%'>Boom. You're confirmed.</h2>

<p style='text-align:center;margin:auto;width:600px'>Great news - your ad to Tappyn has been confirmed. Yup - it's go time.</p><br>

<p style='text-align:center;margin:auto;width:600px'>For your records, here is a copy of the ad you submitted to us:</p><br>

<p style='text-align:center;margin:auto;width:600px;border-bottom:2px solid #FF5E00'></p>
<h4 style='text-align:center'><?php echo $company; ?></h4>

<table style='max-width:500px' align='center'>
    <?php if(isset($headline)): ?>
    <tr>
        <td>
            <p style='max-width:600px;text-align:left;font-weight:700'>
                <?php echo $headline; ?>
            </p>
        </td>
    </tr>
    <?php endif; ?>
    <?php if(!is_null($thumbnail_url)): ?>
        <tr>
            <td>
                <img src="<?php echo $thumbnail_url; ?>">
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>
            <p style='max-width:600px;text-align:justify;'>
                <?php echo $text; ?>
            </p>
        </td>
    </tr>
</table>
<br>

<p style='text-align:center;margin:auto;width:600px;border-bottom:2px solid #FF5E00'></p>
<p style='text-align:left;margin:auto;width:600px'>To keep up with your ads, your up-votes, and of course, the <strong>$$$</strong> in your account: <br></p>
<p style='text-align:center'>
    <a style='display:inline-block;background:#FF5E00;border-radius:4px;color:#fff;font-height:400;font-size: 18px;width:250px;height:50px;padding:0;line-height:50px;text-decoration:none' href="<?php echo base_url().'#/dashboard'; ?>">
        Visit Your Dashboard
    </a>
<p>

<!-- Begin footer -->
<p style='text-align:left;margin:auto;width:600px'>
    Feel free to hit me up with feedback or questions!
</p>
<br>
<p style='text-align:left;margin:auto;width:600px'>
    -Austin
    <br>
    Co-Founder Tappyn
    <br>
    <a href="<?php echo base_url(); ?>">www.tappyn.com</a>
</p>
<!-- End footer -->
