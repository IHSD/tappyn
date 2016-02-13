

<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<?php function plur($count, $text){ return $count.(( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ));} ?>
<section class='innerpage'>
<div class='row'>
	<div class='small-12 columns'>
		<div class='contest-box'  style='background-color:transparent;border:none;border-bottom:2px solid  #FF5E00;padding:0px;'>
			<div class='row'>
				<div class='medium-3 small-12 columns' style='padding-top:10px;'>
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
				<div class='medium-3 small-12 columns' style='padding-top:10px;'>
					<h3>Sharing</h3>
					<h4 style='margin:0px;'>Coming soon!</h4>
				</div>
				<div class='medium-3 small-12 columns'  style='padding-top:30px;'>
					<div style='width:100%'>
		                <div class="progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
		                    <span class="progress-meter" style="width:<?php echo $contest->submission_count * 2; ?>%"></span>
		                </div>
		            </div>
		            <?php if($contest->submission_count < 50) : ?>
		                <div class='text-center'><?php echo $contest->submission_count; ?> of 50 submissions</div>
		            <?php endif ?>
		        </div>
	            <div class='medium-3 small-12 columns text-right'>
	            	<h1>$50</h1>
					<h4 style='margin:0px;margin-top:20px;'>
						 <?php  $stop = new DateTime($contest->stop_time, new DateTimeZone('America/New_York')); $now = new DateTime('now', new DateTimeZone('America/New_York')); $difference = $stop->diff($now);?>
                    	Ends in
                        <?php
                            if($difference->d > 0) echo plur($difference->d, 'day');
                            elseif($difference->h > 0) echo plur($difference->h, 'hour');
                            else echo plur($difference->i, 'minute');
                        ?>
					</h4>
					<h4 style='margin:0px;'><?php echo ucfirst($contest->platform); ?></h4>
				</div>
			</div>
		</div>
	</div>
	<div id='submitting' class='small-12 columns'>
		<div class='contest-box' style='background-color:transparent;border:none;padding:0px;'>
			<div class='row'>
				<div class='medium-8 small-12 columns center-content'>
					<div style='margin-bottom:5px;'>
					<?php if(is_null($contest->company->logo_url)): ?>
						<img style='height:auto;' width='150px' src="<?php echo base_url().'public/img/contest-default.jpg'; ?>">
					<?php else: ?>
						<img style='height:auto;' width='150px' src="<?php echo base_url().'uploads/'.$contest->company->logo_url; ?>">
					<?php endif; ?>
					</div>
			        <div id='brief-content' style='width:100%'>
			        	<div class='row' style='margin-bottom:5px;'>
							<div class='medium-3 small-12 columns'><h4 class='text-left' style='margin:0'>Target Market</h4></div>
			        		<div class='medium-9 small-12 columns' style='text-align:justify'>
			        			<?php echo $contest->audience ?>
			        		</div>
			        	</div>
	                    <br>
			        	<div class='row' style='margin-bottom:5px;'>
							<div class='medium-3 small-12 columns'><h4 class='text-left' style='margin:0'>What Makes Us Different</h4></div>
			        		<div class='medium-9 small-12 columns' style='text-align:justify'>
			        			<?php echo $contest->different ?>
			        		</div>
			        	</div>
	                    <br>
	                    <div class='row' style='margin-bottom:5px;'>
							<div class='medium-3 small-12 columns'><h4 class='text-left' style='margin:0'>Objective</h4></div>
			        		<div class='medium-9 small-12 columns' style='text-align:justify'>
			        			<?php echo $contest->objective ?>
			        		</div>
			        	</div>
			        </div>
			        <div style='padding-bottom:20px;padding-top:20px;'>
			            <a href='<?php echo $contest->company->company_url; ?>' class='btn btn-brief'  target='_blank'>Website</a>
		        	    <a href='<?php echo $contest->company->facebook_url; ?>' class='btn btn-brief'  target='_blank'>Facebook</a>
                   	</div>
			    </div>
		        <div class='medium-4 small-12 columns'>
				<?php $this->load->view('templates/notification', array(
				    'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
				    'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
				)); ?>
				<div class='facebook-form-wrapper'>
	            <?php echo form_open("submissions/create/{$contest->id}");?>
	             <h4 style='margin:0px;margin-bottom:20px;'>Create your ad here</h4>
	             <?php if(!$this->ion_auth->logged_in()) : ?>
						<div class='form-row'>
							<?php echo form_input(array('name' => 'name', 'value' => '', 'placeholder' => 'Name', 'type' => 'text')); ?>
                        </div>
                        <div class="form-row">
                          	<?php echo form_input(array('name' => 'email','value' => '','placeholder' => 'Email', 'type' => 'text')); ?>
                        </div>
						<div class='form-row'>
							<h4 style='text-align:left;margin:0;'>Age</h4>
							<?php foreach($ages as $key => $age) : ?>
								<div style='float:left;'>
								<?php
									echo "<label style='color:#333'>".$age;
                            		echo form_radio('age', $age);
                            		echo "</label>";
                            	?>
                            	</div>
                        	<?php endforeach; ?>
                        </div>
					<br>
		           <?php endif; ?>
				   <?php switch($contest->platform):
		                case 'facebook': $this->load->view('submissions/templates/facebook', array('contest' => $contest)); break;
			            case 'google': $this->load->view('submissions/templates/google', array('contest' => $contest)); break;
			            case 'general': $this->load->view('submissions/templates/general', array('contest' => $contest)); break;
			            case 'twitter': $this->load->view('submissions/templates/twitter', array('contest' => $contest)); break;
		            endswitch; ?>
		            <?php if($this->ion_auth->logged_in()) : ?>
			            <div class='text-right'>
		                	<?php echo form_submit('submit', 'Submit', array("class" => 'btn'));?>
		              	</div>
		            <?php else : ?>
		            	<div class='text-right'>
		                	<?php echo form_submit('submit', 'Submit as Guest', array("class" => 'btn'));?>
		              	</div>
		            <?php endif; ?>
	                <?php echo form_close();?>
	                  <div class='img-example'>
		            	<?php switch($contest->platform):
				        case 'facebook': echo "<img src='".base_url()."public/img/fb_submish.png'>"; break;
				        case 'google': echo "<img src='".base_url()."public/img/google_submish.png'>"; break;
				        case 'twitter': echo "<img src='".base_url()."public/img/twitter_submish.png'>"; break;
				        endswitch; ?>
				    </div>
		        </div>
		        </div>
		   	</div>
		</div>
	</div>
</div>
<script>
fbq('track', 'ViewContent');
</script>
<?php if($this->session->flashdata('track') == 1): ?>
<script>
fbq('track', 'Lead');
</script>
<?php endif; ?>
</section>

<?php $this->load->view('templates/footer'); ?>
