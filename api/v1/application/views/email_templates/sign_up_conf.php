<?php defined("BASEPATH") or exit('No direct script access allowed');

$requirements = array('uid', 'activation', 'query_string');
foreach($requirements as $req)
{
    if(!isset($$req))
    {
        throw new Exception("Email data missing {$req}");
    }
}
$query_string['redirect'] = 'api/v1/auth/activate/'.$uid.'/'.$activation;

?>

<?php $this->load->view('email_templates/header', array('query_string', $query_string)); ?>

<!-- Start Email Content -->

<h2 style='text-align:center;margin:auto;width:50%'>Welcome on board</h2>
<br>
<p style='text-align:justify;margin:auto;width:50%'>Congratulations on joining a community that's redefining the world of advertisements.</p>
<p style='text-align:justify;margin:auto;width:50%'>Together, we create ads that people like you and me want to see,</p>
<p style='text-align:justify;margin:auto;width:50%'>and we're earning big payouts while we do it.</p>
<br>

<!-- Orange header -->
<p style='text-align:center;margin:auto;width:75%;border-bottom:2px solid #FF5E00'></p><br>
<!-- Bordered box -->

<h4 style='text-align:center;margin:auto;width:50%'>Tappyn Guidelines</h4>
<p style='text-align:justify;margin:auto;width:50%'>Before you get started, be sure to read this information that all users follow.</p>
<br>
<p style='text-align:justify;margin:auto;width:50%'>1 ) Reaed the submission guide before you begin submitting. It's pretty much essential to create a winning ad. The guide can be found on every contest brief page or through <a href="https://tappyn.com/guide">this link</a></p>
<p style='text-align:justify;margin:auto;width:50%'>2 ) Always read the contest's Target Audience and Creative Direction. This info can be easily found in a company's brief on the left side of any contest page. Companies are often looking for specific contetn, and this information will increase your chances of winning.</p>

<!-- End Bordered-box -->

<!-- Orange header -->
<p style='text-align:center;margin:auto;width:75%;border-bottom:2px solid #FF5E00'></p><br>
<p style='text-align:justify;margin:auto;width:50%'>
	Good luck!
</p>

<!-- End Email Content -->

<?php $this->load->view('email_templates/austin_footer'); ?>
