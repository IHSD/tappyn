<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<section class='innerpage'>
<div class='row'>
	<div class='small-12 columns'>
		<div class='contest-box'>
			<div class='row'>
				<div class='medium-3 small-12 columns'>
					<h3>
						<?php echo $contest->title; ?> by <?php echo $contest->company->name; ?>
					</h3>
					<div class="tabs-box" style='margin:0'>
						<ul class="tabs" style='margin:0;' data-tabs id="top-line-tabs">
	                        <li class="tabs-title"><a href="<?php echo base_url().'contests/'.$contest->id ?>" >Brief</a></li>
	                        <li class="tabs-title"><a href="<?php echo base_url().'contests/'.$contest->id.'/submissions' ?>" aria-selected='true'>Submissions</a></li>
	                    </ul>
	                </div>
				</div>
				<div class='medium-3 small-12 columns'>
					<h3>Sharing</h3>
					<h4>Coming soon!</h4>
				</div>
				<div class='medium-3 small-12 columns'>
					<div style='width:100%'>
		                <div class="progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
		                    <span class="progress-meter" style="width:<?php echo $contest->submission_count; ?>%"></span>
		                </div>
		            </div>
		            <?php if($contest->submission_count < 50) : ?>
		                <div class='text-center'><?php echo $contest->submission_count; ?> of 50 submissions</div>
		            <?php endif ?>
		        </div>
	            <div class='medium-3 small-12 columns text-right'>
	            	<h3>$50</h3>
					<h4>Ends <?php echo date('D, M d', strtotime($contest->stop_time));?></h4>
				</div>
			</div>
		</div>
	</div>
	<div id='submissions' class='small-12 columns'>
			<?php if(isset($submissions) && count($submissions) > 0) : ?>
		        	<?php foreach($submissions as $submission): ?>
		        		<div class="medium-3 small-12 columns contest-box" style='float:left'>
		        			 <?php switch($contest->platform):
				                case 'facebook': $this->load->view('submissions/thumbnails/facebook', array('submission' => $submission)); break;
					            case 'google': $this->load->view('submissions/thumbnails/google', array('submission' => $submission)); break;
					            case 'trending': $this->load->view('submissions/thumbnails/trending', array('submission' => $submission)); break;
					            case 'tagline': $this->load->view('submissions/thumbnails/tagline', array('submission' => $submission)); break;
					            case 'general': $this->load->view('submissions/thumbnails/general', array('submission' => $submission)); break;
					            case 'twitter': $this->load->view('submissions/thumbnails/twitter', array('submission' => $submission)); break;
				            endswitch; ?>
							<?php $last_name = $submission->owner->last_name; ?>
                            <h3 class='submission-owner-title'> - <?php echo (!is_null($submission->owner->first_name) ? $submission->owner->first_name.' '.ucfirst($last_name[0])) ?></h3>
			        	</div>
		        	<?php endforeach; ?>
			<?php else : ?>
				<h3>This contest has no submissions yet, you could be the first!</h3>
			<?php endif ?>
	</div>
</div>
</section>

<?php $this->load->view('templates/footer'); ?>
