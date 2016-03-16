<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * Our post contest package
 *
 */

function parse_objective($obj)
{
    return $obj;
}

function parse_audience($aud)
{
    return '25-34 Females';
}

function parse_location($loc)
{
    return $loc;
}
?>


<!-- Start Template -->
<br>
<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
</p>

<h2 style='text-align:left;margin:auto;width:600px;'>Hi <?php echo $cname; ?>,</h2>
<br>

<p style='text-align:center;margin:auto;width:600px'>Congratulations on picking your winning submission!</p><br>

<p style='text-align:center;margin:auto;width:600px;border-bottom:2px solid #FF5E00'></p>
<br>
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
<br>

<p style='text-align:center;margin:auto;width:600px'><strong>Now it's time to put your content to work!</strong></p><br>
<?php

/**
 * Switch based on platform and contest objective
 */

$audience_test = new StdClass;
$audience_test->age_range = $age;
$audience_test->gender = $gender;
$data = array(
    'objective_display_name' => parse_objective($objective),
    'audience' => parse_audience($audience_test),
    'location' => parse_location($location)
);

switch($platform) {
    case 'facebook':
        $this->load->view('emails/templates/facebook', $data);
    break;

    case 'google':
        $this->load->view('emails/templates/facebook', $data);
    break;

    case 'twitter':
        $this->load->view('emails/templates/facebook', $data);
    break;

    default:
        $this->load->view('emails/templates/facebook', $data);
}

?>
<p style='text-align:center;margin:auto;width:600px'>Unlesss it's peach vodka. Then keep it far, far away from our mailbox.</p><br><br>
<p style='text-align:center;margin:auto;width:600px'>

</p>
<br>

<!-- Begin footer -->
<p style='margin:auto;width:600px;'>
    Thanks
</p>
<br>
<p style='margin:auto;width:600px;'>
    Tappyn Team
    <br>
    <a href="<?php echo base_url(); ?>">www.tappyn.com</a>
</p>
<!-- End footer -->
