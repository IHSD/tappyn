

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
                <div class='text-center'><a href="<?php echo base_url().'contests/'.$contest->id.'/submissions' ?>" style='width:100%;cursor:pointer;text-decoration:none;' class='btn tiny'>View submissions</a></div>
            <?php endif ?>
		</div>
	</div>
	<div id='submitting' class='medium-9 small-12 columns'>
		<div class='contest-box'>
			<div class='row'>
				<div class='medium-6 small-12 columns'>
				<?php $this->load->view('templates/notification', array(
				    'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
				    'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
				)); ?>
	            <?php echo form_open("submissions/create/{$contest->id}");?>
				   <?php switch($contest->platform):
		                case 'facebook': $this->load->view('submissions/templates/facebook'); break;
			            case 'google': $this->load->view('submissions/templates/google'); break; 
			            case 'trending': $this->load->view('submissions/templates/trending'); break; 
			            case 'tagline': $this->load->view('submissions/templates/tagline'); break; 
			            case 'general': $this->load->view('submissions/templates/general'); break; 
			            case 'twitter': $this->load->view('submissions/templates/twitter'); break;
		            endswitch; ?>
		            <div class='div-center'>
	                	<?php echo form_submit('submit', 'Submit', array("class" => 'btn'));?>
	              	</div>
	                <?php echo form_close();?>
		        </div>
		        <div class='medium-6 small-12 columns'>
		        	Img here
		        </div>
		   	</div>
		</div>
	</div>
</div>
</section>

<?php $this->load->view('templates/footer'); ?>
