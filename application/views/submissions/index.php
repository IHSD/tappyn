<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<section class='innerpage'>
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
                <div class='text-center'><a href="<?php echo base_url().'contests/'.$contest->id ?>" style='cursor:pointer;text-decoration:none;' class='btn tiny'>Back to Submit</a></div>
            <?php endif ?>
		</div>
	</div>
	<div id='submissions' class='medium-9 small-12 columns'>
		<div class='contest-box'>	
			<?php if(isset($submissions) && count($submissions) > 0) : ?>
				<h3>Other submissions</h3>
				<div class='row'>
	        	<?php foreach($submissions as $submission): ?>
	        		<div class="medium-3 small-12 columns">
	        			hi
	        		</div>
	        	<?php endforeach; ?>
	        	</div>
			<?php else : ?>
				<h3>This contest has no submissions yet, you could be the first!</h3>
			<?php endif ?>
		</div>
	</div>
</div>
</section>

<?php $this->load->view('templates/footer'); ?>
