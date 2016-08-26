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

<h2 style='text-align:center;margin:auto;min-width:450px;width:50%'>Welcome on board</h2>
<br>
<p style='text-align:center;margin:auto;min-width:450px;width:50%'><strong>Congratulations on joining a community that's redefining the world of advertisements.</strong></p><br>
<p style='text-align:center;margin:auto;min-width:450px;width:50%'><strong>Together, we create ads that people like you and me want to see, and we're earning money while we do it.</strong></p><br>
<br>

<!-- Orange header -->
<p style='text-align:center;margin:auto;min-width:450px;width:50%;border-bottom:2px solid #019F6E'></p><br>
<!-- Bordered box -->

<h4 style='text-align:center;margin:auto;min-width:450px;width:50%'>Fabel Guidelines</h4>
<br>
<p style='text-align:justify;margin:auto;min-width:450px;width:50%'>Before you get started, be sure to read this information that all users follow.</p>
<br>
<p style='text-align:justify;margin:auto;min-width:450px;width:50%'>1 ) <?php echo anchor('api/v1/analytics/click?'.http_build_query($query_string), 'Verify your email!'); ?></p><br>
<p style='text-align:justify;margin:auto;min-width:450px;width:50%'>2 ) Read the submission guide before you begin submitting. It's pretty much essential to create an awesome. The guide can be found on every contest brief page or through <a href="https://tappyn.com/guide">this link</a>.</p><br>
<p style='text-align:justify;margin:auto;min-width:450px;width:50%'>3 ) Always read the contest's Target Audience and Creative Direction. This info can be easily found in a company's brief on the left side of any contest page. Companies are often looking for specific content, and this information will increase the chances of your ad being purchased.</p>
<br>
<!-- End Bordered-box -->

<!-- Orange header -->
<p style='text-align:center;margin:auto;min-width:450px;width:50%;border-bottom:2px solid #019F6E'></p><br>

<!-- End Email Content -->

<p style='text-align:justify;margin:auto;min-width:450px;width:50%'>
    Thanks for using Fabel!
</p>
<br>
<p style='text-align:justify;margin:auto;min-width:450px;width:50%'>
    -Austin
    <br>
    Co-Founder - Fabel <a href="<?php echo base_url(); ?>">www.fabel.us</a>
</p>
