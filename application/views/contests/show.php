

<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<section class='innerpage'>
<div class='row'>	
	<div class='small-12 columns'>
		<div class='contest-box'>
			<div class='row'>
				<div class='medium-3 small-12 columns'>
					<h3>
						<?php echo $contest->title; ?> by <?php echo $contest->owner; ?>
					</h3>
					<div class="tabs-box" style='margin:0'>
						<ul class="tabs" style='margin:0;' data-tabs id="top-line-tabs">
	                        <li class="tabs-title"><a href="<?php echo base_url().'contests/'.$contest->id ?>" aria-selected='true'>Brief</a></li>
	                        <li class="tabs-title"><a href="<?php echo base_url().'contests/'.$contest->id.'/submissions' ?>">Submissions</a></li>
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
	            	<h3>$50 Reward</h3>
					<h4>Ends <?php echo date('D, M d', strtotime($contest->stop_time));?></h4>
				</div>
			</div>
		</div>
	</div>
	<div id='submitting' class='small-12 columns'>
		<div class='contest-box'>
			<div class='row'>
		        <div id='brief-content' class='medium-8 small-12 columns'>
		        	<h3 class='text-center'>Ad Brief</h3>
		        	<div class='div-center'><img src="<?php echo base_url().'uploads/'.$contest->logo_url; ?>"></div>
		        	<div class='row' style='margin-bottom:5px;'>
		        		<div class='medium-3 small-12 columns'>
		        			Product and Target Market :
		        		</div>
		        		<div class='medium-9 small-12 columns'>
		        			<?php echo $contest->audience ?>	
		        		</div>
		        	</div>
		        	<div class='row' style='margin-bottom:5px;'>
		        		<div class='medium-3 small-12 columns'>
		        			Difference :
		        		</div>
		        		<div class='medium-9 small-12 columns'>
		        			<?php echo $contest->difference ?>
		        		</div>
		        	</div>
		        	<div class='row' style='margin-bottom:5px;'>
		        		<div class='medium-3 small-12 columns'>
		        			Objective of Ad :
		        		</div>
		        		<div class='medium-9 small-12 columns'>
		        			<?php echo $contest->objective ?>
		        		</div>
		        	</div>
		        	<a href='<?php echo $contest->company_website; ?>' target='_blank'>Company Website</a>
		        	<a href='<?php echo $contest->company_facebook; ?>' target='_blank'>Company Facebook</a>
		        </div>
		        <div class='medium-4 small-12 columns'>
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
		            <div class='text-right'>
	                	<?php echo form_submit('submit', 'Submit', array("class" => 'btn'));?>
	              	</div>
	                <?php echo form_close();?>
		        </div>
		   	</div>
		</div>
	</div>
</div>
</section>

<?php $this->load->view('templates/footer'); ?>
