<?php defined("BASEPATH") or exit('No direct script access allowed');

if(!isset($query_string))
{
    throw new Exception("Email missing query string");
}

?>

<p style='width:100%;text-align:center'>
    <img align='center' height='75' src="<?php echo base_url('api/v1/analytics/open?'.http_build_query($query_string)); ?>">
</p>
