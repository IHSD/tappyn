<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<?php $this->load->view('templates/app_navbar.php'); ?>

<section class='innerpage'>
<div class='row' style='padding-top:10px'>
	<div class='col-xs-6 col-xs-offset-3'>
		<div class='panel' style='margin-bottom:0;'>
			<div class='panel-body text-center'>
				<h4><?php echo isset($error) ? $error : "There was an error verifying your email"; ?></h4>
                <h4>Please try again, or click <a href="#" id='resend'>here</a> to have a new one sent to you</h4>
			</div>
		</div>
	</div>
</div>


<script>
	$(document).ready(function(){
		$('#resend').click(function() {
			$.ajax({
				url : "<?php echo base_url().'auth/resend_verification'; ?>",
				method : 'post',
				data : 'json',
				success: function(response)
				{
					if(response.success) {
						alert("New verification email sent");
					} else {
						alert("There was an error sending your email");
					}
				}
			})
		})
	})
</script>
