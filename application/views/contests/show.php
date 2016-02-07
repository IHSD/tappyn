

<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<section class='innerpage'>
<div class='row'>
	<div class='small-12 columns'>
		<div class='contest-box'>
			<div class='row'>
				<div class='medium-3 small-12 columns'>
					<h3>
						<?php echo $contest->title; ?>
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
	            	<h3>$50</h3>
					<h4>Ends <?php echo date('D, M d', strtotime($contest->stop_time));?></h4>
				</div>
			</div>
		</div>
	</div>
	<div id='submitting' class='small-12 columns'>
		<div class='contest-box'>
			<div class='row'>
				<div class='medium-2 small-12 columns center-content'>
					<h4><?php echo $contest->company->name; ?></h4>
					<?php if(is_null($contest->company->logo_url)): ?>
						<img style='height:auto;' width='150px' src="<?php echo base_url().'public/img/contest-default.jpg'; ?>">
					<?php else: ?>
						<img style='height:auto;' width='150px' src="<?php echo base_url().'uploads/'.$contest->company->logo_url; ?>">
					<?php endif; ?>
					<ul class='contest-link-list'>
		        	    <li><a href='<?php echo $contest->company->company_url; ?>' class='btn' target='_blank'>Website</a></li>
		        	    <li><a href='<?php echo $contest->company->facebook_url; ?>' class='btn' target='_blank'>Facebook Page</a></li>
                    </ul>
				</div>
		        <div id='brief-content' class='medium-6 small-12 columns'>
		        	<h3 class='text-center'><strong>Ad Brief</strong></h3>
					<h4 class='text-center'>Platform : <?php echo ucfirst($contest->platform); ?></h4>
		        	<div class='row' style='margin-bottom:5px;'>
						<h4 class='center-content'>Target Market</h4>
						<hr>
		        		<div class='medium-12 small-12 columns' style='text-align:justify'>
		        			<?php echo $contest->audience ?>
		        		</div>
		        	</div>
                    <br>
		        	<div class='row' style='margin-bottom:5px;'>
						<h4 class='center-content'>What Makes Us Different</h4>
						<hr>
		        		<div class='medium-12 small-12 columns' style='text-align:justify'>
		        			<?php echo $contest->different ?>
		        		</div>
		        	</div>
                    <br>
		        </div>
		        <div class='medium-4 small-12 columns'>
				<?php $this->load->view('templates/notification', array(
				    'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
				    'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
				)); ?>
	            <?php echo form_open("submissions/create/{$contest->id}");?>
				   <?php switch($contest->platform):
		                case 'facebook': $this->load->view('submissions/templates/facebook', array('contest' => $contest)); break;
			            case 'google': $this->load->view('submissions/templates/google', array('contest' => $contest)); break;
			            case 'trending': $this->load->view('submissions/templates/trending', array('contest' => $contest)); break;
			            case 'tagline': $this->load->view('submissions/templates/tagline', array('contest' => $contest)); break;
			            case 'general': $this->load->view('submissions/templates/general', array('contest' => $contest)); break;
			            case 'twitter': $this->load->view('submissions/templates/twitter', array('contest' => $contest)); break;
		            endswitch; ?>
		            <?php if($this->ion_auth->logged_in()) : ?>
			            <div>
		                	<?php echo form_submit('submit', 'Submit', array("class" => 'btn large-4 large-offset-4 small-12 columns'));?>
		              	</div>
		            <?php else : ?>
						<h4>Account Details</h4>
						<div class='form-row'>
							<div class='large-6 small-12 columns'>
	                            <?php echo form_input(array('name' => 'first_name', 'value' => '', 'placeholder' => 'First Name', 'type' => 'text')); ?>
							</div>
        					<div class='large-6 small-12 columns'>
	                            <?php echo form_input(array('name' => 'last_name', 'value' => '', 'placeholder' => 'Last Name', 'type' => 'text')); ?>
							</div>
                        </div>
		            	 <div class="form-row">
							  <div class='large-12 columns'>
                              	<?php echo form_input(array('name' => 'email','value' => '','placeholder' => 'Email', 'type' => 'text'));?>
							  </div>
                        </div>
                        <div class='row large-12 columns'>
                            <div class='large-6 columns'>
                                <?php echo form_dropdown('age_range', $ages, 'AGES'); ?>
                            </div>
                        	<div class='large-6 columns'>
	                            <?php echo form_dropdown('gender', $genders, 'GENDER'); ?>
							</div>
                        </div>
						<br>
                        <div class='text-right'>
		                	<?php echo form_submit('submit', 'Continue as Guest', array("class" => 'btn large-6 large-offset-3 small-12 columns'));?>
		              	</div>
		            <?php endif; ?>
	                <?php echo form_close();?>
		        </div>
		   	</div>
		</div>
	</div>
</div>
</section>

<?php $this->load->view('templates/footer'); ?>
