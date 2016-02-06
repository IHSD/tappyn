

<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php echo $this->session->flashdata('error') ? $this->session->flashdata('error') : ''; ?>
<div class='row'>	
	<div class='medium-3 small-12 columns'>
		<div class='contest-box'>
			<h3>
			<?php if(isset($contest)) : ?>
				<?php echo $contest->title; ?> by <?php echo $contest->owner; ?>
			<?php endif; ?>
			</h3>
			<h4 class='text-center'></h4>
			<h4 class='text-center'>$50 Reward</h4>
			<div style='width:100%'>
                <div class="progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
                    <span class="progress-meter" style="width:<?php echo $contest->submission_count; ?>%"></span>
                </div>
                <p class="progress-meter-text"><?php echo $contest->submission_count; ?> of 50 submissions</p>
            </div>
            <div><h4 class='text-center'>Ends <?php echo date('D, M d', strtotime($contest->stop_time));?></h4></div>
            <?php if($contest->submission_count < 50) : ?>
                <div class='text-center'><button id='submit_it' style='width:100%;cursor:pointer;text-decoration:none;' class='btn tiny'>Submit Now!</button></div>
            <?php endif ?>
		</div>
	</div>
	<div id='submissions' class='medium-9 small-12 columns'>
		<div class='contest-box'>	
			<?php if(isset($submissions) && count($submissions) > 0) : ?>
				<h3>Other submissions</h3>
            	<?php foreach($submissions as $submission): ?>
            		<div class="medium-3 small-12 columns end">
            			<div>
            				Hi
            			</div>
            		</div>
            	<?php endforeach; ?>
			<?php else : ?>
				<h3>This contest has no submissions yet, you could be the first!</h3>
			<?php endif ?>
		</div>
	</div>
	<div id='submitting' class='hidden_submission medium-9 small-12 columns'>
		<div class='contest-box'>
			<button id='cancel_it' class='btn'>X</button>
			   <?php switch($contest->platform):
	                case 'facebook': $this->load->view('submissions/templates/facebook'); break;
		            case 'google': $this->load->view('submissions/templates/google'); break; 
		            case 'trending': $this->load->view('submissions/templates/trending'); break; 
		            case 'tagline': $this->load->view('submissions/templates/tagline'); break; 
		            case 'general': $this->load->view('submissions/templates/general'); break; 
		            case 'twitter': $this->load->view('submissions/templates/twitter'); break;
	            endswitch; ?>
		</div>
	</div>
</div>


<?php $this->load->view('templates/footer'); ?>

<script>
	$("#submit_it").click(function(){
		console.log("I heeard a click");
		$("#submissions").addClass("hidden_submission");
		$("#submitting").removeClass('hidden_submission');
	});
	$("#cancel_it").click(function(){
		$("#submissions").removeClass("hidden_submission");
		$("#submitting").addClass('hidden_submission');
	});

</script>