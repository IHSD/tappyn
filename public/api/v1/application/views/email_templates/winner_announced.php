<?php defined("BASEPATH") or exit('No direct script access allowed');

$requirements = array('company');
foreach ($requirements as $req) {
    if (!isset($$req)) {
        throw new Exception("Email data missing {$req}");
    }
}
$query_string['redirect'] = 'dashboard';

?>


<?php $this->load->view('email_templates/header', array('query_string', $query_string));?>

<!-- Start Email Content -->

<h2 style='text-align:center;margin:auto;min-width:450px;width:50%'><?php echo $company->name; ?>'s campaign has ended</h2>
<br>
<!-- Orange header -->
<p style='text-align:center;margin:auto;min-width:450px;width:50%;border-bottom:2px solid #FF5E00'></p><br>

<?php $query_string['redirect'] = 'ended/' . $contest->id;?>
<p style='text-align:left;margin:auto;width:600px'>Check out your ad's Click Through Rate and all of the purchased ads <?php echo anchor('api/v1/analytics/click?' . http_build_query($query_string), 'here'); ?>.</p>
<br>
<?php $query_string['redirect'] = 'guide';?>
<p style='text-align:left;margin:auto;width:600px'>Wondering why your ad wasn't purchased? Take a look at our creative guide to learn more about what makes a great ad <?php echo anchor('api/v1/analytics/click?' . http_build_query($query_string), 'here'); ?>.</p>
<br>
<?php $query_string['redirect'] = 'contests';?>
<p style='text-align:left;margin:auto;width:600px'>Or you can see some of our <?php echo anchor('api/v1/analytics/click?' . http_build_query($query_string), 'new campaigns'); ?>.<br/></p>
<br>
<!-- Orange header -->
<p style='text-align:center;margin:auto;min-width:450px;width:50%;border-bottom:2px solid #FF5E00'></p><br>

<!-- End Email Content -->

<?php $this->load->view('email_templates/austin_footer');?>
